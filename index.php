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
// [ 应用入口文件 ]
namespace think;

// 检测PHP环境
if(version_compare(PHP_VERSION,'7.0.0','<'))
    die('本系统要求PHP版本>=7.0以上，当前PHP版本为：'.PHP_VERSION . '，请到切换PHP版本。<a href="http://www.junlankeji.com/help/" target="_blank">点击查看JlCMS安装教程</a>');

// 检测是否已安装
if(file_exists("./install/") && !file_exists("./install/install.lock")){
    header('Location:./install/index.php');
    exit();
}

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/app/base.php';
define('DATA_PATH', __DIR__ . '/data/');
// 执行HTTP应用并响应
$http = (new App())->http;

$response = $http->run();

$response->send();

$http->end($response);
