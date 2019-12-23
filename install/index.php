<?php
/**
 * @WeiXinNumber        zhanglijun1893
 * @Copyright			君澜科技
 * @License				http://www.junlankeji.com
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用
 * 不允许对程序代码以任何形式任何目的的再发布,如果商业用途务必到官方购买正版授权
 * 版权所有 君澜科技（北京）有限公司，并保留所有权利。
 *   _               _               _            _ _
 *  (_)             | |             | |          (_|_)
 *  _ _   _ _ __   | | __ _ _ __   | | _____     _ _
 *  | | | | | '_ \  | |/ _` | '_ \  | |/ / _ \   | | |
 *  | | |_| | | | | | | (_| | | | | |   <  __/   | | |
 *  | |\__,_|_| |_| |_|\__,_|_| |_| |_|\_\___|   | |_|
 *  _/ |                                         _/ |
 *  |__/                                         |__/
 */

// 检测是否安装
if (file_exists('./install.lock')) die("你已经安装过该系统，如果想重新安装，请先删除站点install目录下的 install.lock 文件，然后再安装。");

// 检测PHP环境
if(version_compare(PHP_VERSION,'7.0.0','<')) die('本系统要求PHP版本>=7.1以上，当前PHP版本为：'.PHP_VERSION . '，请到切换PHP版本。<a href="http://www.junlankeji.com/help/" target="_blank">点击查看JlCMS安装教程</a>');

//检测安装文件
$sqlFile = "jlcms.sql";
define('ROOT_PATH', dir_path(substr(dirname(__FILE__), 0, -8)));    //入口文件目录
define('APP_DIR', dir_path(substr(dirname(__FILE__), 0, -15)));//项目目录

define('INSTALL_PATH', dirname(__FILE__)."/");

if (!file_exists(INSTALL_PATH . $sqlFile)) {
    echo "缺少必要的安装文件({$sqlFile})!";
    exit;
}

$title = "JlCMS安装向导";
$powered = "Powered by JlCMS";
$steps = array(
    '1' => '许可协议',
    '2' => '检测环境',
    '3' => '账号设置',
    '4' => '创建数据',
    '5' => '完成安装',
);
//$step = isset($_REQUEST['step']) ? intval($_REQUEST['step']) : 1;
$step = isset($_REQUEST['step']) ? trim($_REQUEST['step']) : 1;
//域名地址
$scriptName = !empty($_SERVER["REQUEST_URI"]) ? $scriptName = $_SERVER["REQUEST_URI"] : $scriptName = $_SERVER["PHP_SELF"];
$rootPath = @preg_replace("/\/(I|i)nstall\/index\.php(.*)$/", "", $scriptName);
$domain = empty($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
if ((int) $_SERVER['SERVER_PORT'] != 80) {
    $domain .= ":" . $_SERVER['SERVER_PORT'];
}
$domain = $domain . $rootPath;

//安装
switch ($step) {
    case '1':   //安装许可协议
        $license = file_get_contents("./license.txt");
        include_once("./step/step".$step.".php");
        break;
    case '2':   //环境检测
        $err = 0;
        $folder = array(
            'install',
            'uploads',
            'runtime',
            'public',
            'app/admin/conf',
            'app/config.php',
            'app/database.php',
        );
        include_once("./step/step".$step.".php");
        break;
    case '3':   //环境检测
        include_once("./step/step".$step.".php");
        break;
    case '4':
        if (isset($_POST)) {
            $data = "";
            foreach ($_POST as $key=>$value) {
                $data .= $key."=".$value."&";
            }
            //extract($_POST);
        }
        include_once("./step/step".$step.".php");
        break;
    case '5':
        $random = random(8);
        $str_constant = "<?php".PHP_EOL."define('INSTALL_DATE',".time().");".PHP_EOL."define('SERIALNUMBER','".$random."');";
        @file_put_contents(APP_DIR . '.constant', $str_constant);
        include_once("./step/step".$step.".php");
        @touch('./install.lock');
        break;
    case 'testDB':  //检测数据库
        if (isset($_POST)) {
            extract($_POST);
            if (empty($dbHost) || empty($dbPort) || empty($dbUser) || empty($dbPassword) || empty($dbName)) {
                resJson(400,"信息添写不全");
            }
            $conn = @mysqli_connect($dbHost, $dbUser, $dbPassword,NULL,$dbPort);

            if (mysqli_connect_errno($conn)){
                resJson(400,"数据库连接失败，请重新设定");
            } else {
                $result = mysqli_query($conn,"select count(table_name) as c from information_schema.`TABLES` where table_schema='$dbName'");
                $result = $result->fetch_array();
                if($result['c'] > 0) {
                    resJson(400,"数据库已经存在");
                }
            }
            resJson();
        }
        break;
    case 'install': //执行SQL
        $status =  intval($_GET['status']);
        extract($_POST);

        if (!function_exists('mysqli_connect')) {
            resJson(400,"请安装 mysqli 扩展!");
        }
        $conn = @mysqli_connect($dbHost, $dbUser, $dbPassword,NULL,$dbPort);
        if (mysqli_connect_errno($conn)){
            resJson(400,"连接数据库失败!".mysqli_connect_error($conn));
        }
        mysqli_set_charset($conn, "utf8"); //,character_set_client=binary,sql_mode='';
        $version = mysqli_get_server_info($conn);
        if ($version < 5.1) {
            resJson(400,"数据库版本太低! 必须5.1以上");
        }
        if (!mysqli_select_db($conn,$dbName)) {
            //创建数据时同时设置编码
            if (!mysqli_query($conn,"CREATE DATABASE IF NOT EXISTS `" . $dbName . "` DEFAULT CHARACTER SET utf8;")) {
                resJson(400,"数据库 ".$dbName." 不存在，也没权限创建新的数据库！");
            }
            if ($status == -1) {
                resJson(0,"成功创建数据库:{$dbName}<br>");
            }
            mysqli_select_db($conn , $dbName);
        }

        //读取数据文件
        $sqlData = file_get_contents(ROOT_PATH . 'install/' . $sqlFile);

        $sqlFormat = sql_split($sqlData, $dbPrefix);
       // $sqlFormat = sql_split($sqlData, "jl_");
        $counts = count($sqlFormat);

        //执行SQL
        for ($i = $status; $i < $counts; $i++) {
            $sql = trim($sqlFormat[$i]);
            if (strstr($sql, 'CREATE TABLE')) {
                preg_match('/CREATE TABLE `([^ ]*)`/', $sql, $matches);
                mysqli_query($conn,"DROP TABLE IF EXISTS `$matches[1]");
                $ret = mysqli_query($conn,$sql);
                if ($ret) {
                    $message = '<li><span class="correct_span">&radic;</span>创建数据表' . $matches[1] . '，完成!<span style="float: right;">'.date('Y-m-d H:i:s').'</span></li> ';
                } else {
                    $message = '<li><span class="correct_span error_span">&radic;</span>创建数据表' . $matches[1] . '，失败!<span style="float: right;">'.date('Y-m-d H:i:s').'</span></li>';
                }
                $i++;
                echo json_encode([
                    'status' => $i,
                    'data' => [],
                    'message' => $message,
                ]);
                exit;
            } else {
                if(trim($sql) == '')
                    continue;
                $sql = str_replace('`jl_','`'.$dbPrefix,$sql);//替换表前缀
                $ret = mysqli_query($conn,$sql);
                $message = '';
                $arr = [
                    'status' => $i,
                    'data' => "aaa",
                    'message' => $message,
                ];
                //echo json_encode($arr); exit;
            }
        }
        if ($i >= 200) exit;

        //读取配置文件，并替换真实配置数据1
        $strConfig = file_get_contents(ROOT_PATH . 'install/.env');
        $strConfig = str_replace('#DB_HOST#', $dbHost, $strConfig);
        $strConfig = str_replace('#DB_NAME#', $dbName, $strConfig);
        $strConfig = str_replace('#DB_USER#', $dbUser, $strConfig);
        $strConfig = str_replace('#DB_PWD#', $dbPassword, $strConfig);
        $strConfig = str_replace('#DB_PORT#', $dbPort, $strConfig);
        $strConfig = str_replace('#DB_PREFIX#', $dbPrefix, $strConfig);
        @chmod(ROOT_PATH . '/.env',0777); //数据库配置文件的地址
        @file_put_contents(ROOT_PATH . '/.env', $strConfig); //数据库配置文件的地址

        //插入管理员表字段tp_admin表
        $time = date("Y-m-d H:i:s",time());
        $ip = get_client_ip();
        $ip = empty($ip) ? "0.0.0.0" : $ip;
        mysqli_query($conn,"truncate table {$dbPrefix}system_admin");
        $encrypt = random();
        $password = md5(md5(trim($password)).$encrypt);
        $addAdminSQL = "INSERT INTO `{$dbPrefix}system_admin` (`username`,`password`,`encrypt`, `last_ip`,`create_at`, `update_at`) VALUES ('".$username."','".$password."','".$encrypt."','".$ip."','".$time."','".$time."')";
        $res = mysqli_query($conn,$addAdminSQL);
        if($res){
            $message = '成功添加管理员<br />成功写入配置文件<br>安装完成．';
            $arr = ['status' => 200, 'message' => $message];
            echo json_encode($arr);exit;
        }else{
            $message = '添加管理员失败<br />成功写入配置文件<br>安装完成．';
            $arr = ['status' => 400, 'message' => $message];
            echo json_encode($arr);exit;
        }
        break;
}

//随机数
function random($length=6, $chars = '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ') {
    $hash = '';
    $max = strlen($chars) - 1;
    for($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

// 获取客户端IP地址
function get_client_ip() {
    static $ip = NULL;
    if ($ip !== NULL)
        return $ip;
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos = array_search('unknown', $arr);
        if (false !== $pos)
            unset($arr[$pos]);
        $ip = trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法验证
    $ip = (false !== ip2long($ip)) ? $ip : '0.0.0.0';
    return $ip;
}

//返回json数据
function resJson($status=200,$message="操作成功",$data=[]) {
    echo json_encode([
        'status' => $status,
        'data' => $data,
        'message' => $message,
    ]);
    exit;
}
function sql_split($sql, $tablepre) {

    if ($tablepre != "jl_")
        $sql = str_replace("jl_", $tablepre, $sql);

    $sql = preg_replace("/TYPE=(InnoDB|MyISAM|MEMORY)( DEFAULT CHARSET=[^; ]+)?/", "ENGINE=\\1 DEFAULT CHARSET=utf8", $sql);

    $sql = str_replace("\r", "\n", $sql);
    $ret = array();
    $num = 0;
    $queriesarray = explode(";\n", trim($sql));
    unset($sql);
    foreach ($queriesarray as $query) {
        $ret[$num] = '';
        $queries = explode("\n", trim($query));
        $queries = array_filter($queries);
        foreach ($queries as $query) {
            $str1 = substr($query, 0, 1);
            if ($str1 != '#' && $str1 != '-')
                $ret[$num] .= $query;
        }
        $num++;
    }
    return $ret;
}

//创建目录
function dir_create($path, $mode = 0777) {
    if (is_dir($path))
        return TRUE;
    $ftp_enable = 0;
    $path = dir_path($path);
    $temp = explode('/', $path);
    $cur_dir = '';
    $max = count($temp) - 1;
    for ($i = 0; $i < $max; $i++) {
        $cur_dir .= $temp[$i] . '/';
        if (@is_dir($cur_dir))
            continue;
        @mkdir($cur_dir, 0777, true);
        @chmod($cur_dir, 0777);
    }
    return is_dir($path);
}

//路径转换
function dir_path($path) {
    $path = str_replace('\\', '/', $path);
    if (substr($path, -1) != '/')
        $path = $path . '/';
    return $path;
}

//过虑文件
function format_textarea($string) {
    return nl2br(str_replace(' ', '&nbsp;', htmlspecialchars($string)));
}
//测试写入
function testWrite($d) {
    $tFile = "_test.txt";
    $fp = @fopen($d . "/" . $tFile, "w");
    if (!$fp) {
        return false;
    }
    fclose($fp);
    $rs = @unlink($d . "/" . $tFile);
    if ($rs) {
        return true;
    }
    return false;
}