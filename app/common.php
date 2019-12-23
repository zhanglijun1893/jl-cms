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
// 应用公共文件
use app\model\CategoryModel;
use app\model\ConfigModel;

//获取配置
if(!function_exists('getConfig')) {
    function getConfig() {
        return ConfigModel::getCache();
    }
}
//获取页尾
if(!function_exists('getFooter')) {
    function getFooter() {
        $model = CategoryModel::getCache();
        $category = [];
        foreach ($model as $key=>$value) {
            $index_view = explode(",",$value['index_view']);
            if (in_array(3,$index_view)) {
                $category[] = $value;
            }
        }
        $list = [];
        if (!empty($category)) {
            foreach ($category as $key=>$value) {
                $item['id'] = $value['id'];
                $item['name'] = $value['name'];
                $item['url'] = "";
                $item['list'] = [];
                //查看是否有子类
                $ids = [];
                foreach ($model as $k=>$v) {
                    if ($value['id'] == $v['parent_id']) {
                        $c['id'] = $v['id'];
                        $c['title'] = $v['name'];
                        $c['url'] = url('list',['name'=>$v['en_name']]);
                        $item['list'][] = $c;
                    } else if ($value['id'] == $v['id']) {
                        $c = \app\model\ContentModel::where("c_id",$value['id'])
                            ->field("id,title")
                            ->select()->toArray();
                        if (!empty($c)) {
                            $item['list'] = $c;
                        }
                    }
                }
                $list[] = $item;
            }
        }
        return $list;
    }
}
//栏目列表
if (!function_exists('category'))
{
    function category() {
        return CategoryModel::orderList(CategoryModel::getCache(),0,1);
    }
}
if (!function_exists('sortListTier')) {
    //分级排序
    function sortListTier($data, $parentId = 0, $field = 'parent_id', $pk = 'id', $html = '|-----', $level = 1, $clear = true)
    {
        static $list = [];
        if ($clear) $list = [];
        foreach ($data as $k => $res) {
            if ($res[$field] == $parentId) {
                $res['html'] = str_repeat($html, $level);
                $list[] = $res;
                unset($data[$k]);
                sortListTier($data, $res[$pk], $field, $pk, $html, $level + 1, false);
            }
        }
        return $list;
    }
}

if (!function_exists('check_phone'))
{
    /**
     * 检查手机号码格式
     * @param $phone
     * @return bool
     */
    function check_phone($phone){
        if(preg_match('/1\d{10}$/',$phone))
            return true;
        return false;
    }
}
if (!function_exists('create_randomStr')) {
    /**
     * 生成随机字符串
     * @param int $lenth
     * @return string
     */
    function create_randomStr($lenth = 6) {
        return random($lenth, '123456789abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ');
    }
}
if (!function_exists('random')) {
    /**
     * 产生随机字符串
     *
     * @param    int        $length  输出长度
     * @param    string     $chars   可选的 ，默认为 0123456789
     * @return   string     字符串
     */
    function random($length, $chars = '0123456789') {
        $hash = '';
        $max = strlen($chars) - 1;
        for($i = 0; $i < $length; $i++) {
            $hash .= $chars[mt_rand(0, $max)];
        }
        return $hash;
    }
}

if (!function_exists('categoryTemplate')) {
    /**
     * 栏目模板
     * @return array
     */
    function categoryTemplate()
    {
        return [
            ['value'=>0,'label'=>'列表模板'],
            ['value'=>1,'label'=>'单页模板'],
            ['value'=>2,'label'=>'图片模板'],
            ['value'=>3,'label'=>'下载模板']
        ];
    }
}
if (!function_exists('getTemplate')) {
    //获取模板
    function getTemplate($p=0)
    {
        if ($p) {
            return [0=>'list',1=>'list_single',2=>'list_images',3=>'list_download',];
        }
        return [0=>'view',1=>'view',2=>'view',3=>'view_download',];
    }
}

if (!function_exists('clear_htm_substr'))
{
    /**
     * 截取内容清除html之后的字符串长度，支持中文和其他编码
     * @param string $str       需要转换的字符串
     * @param int $length       截取长度
     * @param int $start        开始位置
     * @param bool $suffix      截断显示字符
     * @param string $charset   编码格式
     * @return mixed|string|string[]|null
     */
    function clear_htm_substr($str='', $length=0, $start=0, $suffix=false, $charset="utf-8") {
        if(mb_strlen($str,'utf-8')>0){
            if (is_string($str) && stripos($str, '&lt;') !== false && stripos($str, '&gt;') !== false) {
                $str = htmlspecialchars_decode($str);
            }
            $str = clear_html($str);
            //$str = clear_md($str);
            if (mb_strlen($str,'utf-8')>$length) {
                return m_substr($str, $length, $start, $suffix, $charset);
            }
            return $str;
        }else{
            return $str;
        }
    }
}
if (!function_exists('clear_md')) {
    function clear_md($str) {
        $regEx = "/!\\[\\]\\((.*?)\\)/";
        $str = preg_replace($regEx, "", $str);
        return $str;
    }

}
if (!function_exists('m_substr'))
{
    /**
     * 字符串截取，支持中文和其他编码
     * @param string $str       需要转换的字符串
     * @param int $length       截取长度
     * @param int $start        开始位置
     * @param bool $suffix      截断显示字符
     * @param string $charset   编码格式
     * @return false|string
     */
    function m_substr($str='', $length=0, $start=0,  $suffix=false, $charset="utf-8") {
        if(function_exists("mb_substr"))
            $slice = mb_substr($str, $start, $length, $charset);
        elseif(function_exists('iconv_substr')) {
            $slice = iconv_substr($str,$start,$length,$charset);
            if(false === $slice) {
                $slice = '';
            }
        }else{
            $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("",array_slice($match[0], $start, $length));
        }

        $str_len = strlen($str); // 原字符串长度
        $slice_len = strlen($slice); // 截取字符串的长度
        if ($slice_len < $str_len) {
            $slice = $suffix ? $slice.'...' : $slice;
        }

        return $slice;
    }
}
if (!function_exists('clear_html'))
{
    /**
     * 过滤Html标签
     * @param $string
     * @return mixed|string|string[]|null
     */
    function clear_html($string){
        $string = trim_space($string);

        if(is_numeric($string)) return $string;
        if(!isset($string) or empty($string)) return '';

        $string = preg_replace('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/','',$string);
        $string  = ($string);

        $string = strip_tags($string,""); //清除HTML如<br />等代码
        // $string = str_replace("\n", "", str_replace(" ", "", $string));//去掉空格和换行
        $string = str_replace("\n", "", $string);//去掉空格和换行
        $string = str_replace("\t","",$string); //去掉制表符号
        $string = str_replace(PHP_EOL,"",$string); //去掉回车换行符号
        $string = str_replace("\r","",$string); //去掉回车
        $string = str_replace("'","‘",$string); //替换单引号
        $string = str_replace("&amp;","&",$string);
        $string = str_replace("=★","",$string);
        $string = str_replace("★=","",$string);
        $string = str_replace("★","",$string);
        $string = str_replace("☆","",$string);
        $string = str_replace("√","",$string);
        $string = str_replace("±","",$string);
        $string = str_replace("‖","",$string);
        $string = str_replace("×","",$string);
        $string = str_replace("∏","",$string);
        $string = str_replace("∷","",$string);
        $string = str_replace("⊥","",$string);
        $string = str_replace("∠","",$string);
        $string = str_replace("⊙","",$string);
        $string = str_replace("≈","",$string);
        $string = str_replace("≤","",$string);
        $string = str_replace("≥","",$string);
        $string = str_replace("∞","",$string);
        $string = str_replace("∵","",$string);
        $string = str_replace("♂","",$string);
        $string = str_replace("♀","",$string);
        $string = str_replace("°","",$string);
        $string = str_replace("¤","",$string);
        $string = str_replace("◎","",$string);
        $string = str_replace("◇","",$string);
        $string = str_replace("◆","",$string);
        $string = str_replace("→","",$string);
        $string = str_replace("←","",$string);
        $string = str_replace("↑","",$string);
        $string = str_replace("↓","",$string);
        $string = str_replace("▲","",$string);
        $string = str_replace("▼","",$string);

        // --过滤微信表情
        $string = preg_replace_callback('/[\xf0-\xf7].{3}/', function($r) { return '';}, $string);

        return $string;
    }
}
if (!function_exists('trim_space'))
{
    /**
     * 过滤前后空格等多种字符
     *
     * @param string $str 字符串
     * @param array $arr 特殊字符的数组集合
     * @return string
     */
    function trim_space($str, $arr = array())
    {
        if (empty($arr)) {
            $arr = array(' ', '　');
        }
        foreach ($arr as $key => $val) {
            $str = preg_replace('/(^'.$val.')|('.$val.'$)/', '', $str);
        }

        return $str;
    }
}

/**
 *  获取拼音以gbk编码为准
 *
 * @param     string  $str     字符串信息
 * @param     int     $ishead  是否取头字母
 * @param     int     $isclose 是否关闭字符串资源
 * @return    string
 */
if ( ! function_exists('to_py'))
{
    function to_py($str, $ishead=0, $isclose=1)
    {
        // return SpGetPinyin(utf82gb($str), $ishead, $isclose);

        $s1 = iconv("UTF-8","gb2312", $str);
        $s2 = iconv("gb2312","UTF-8", $s1);
        if($s2 == $str){$str = $s1;}

        $pinyins = array();
        $restr = '';
        $str = trim($str);
        $slen = strlen($str);
        if($slen < 2)
        {
            return $str;
        }
        if(empty($pinyins))
        {
            $fp = fopen(DATA_PATH.'pinyin.dat', 'r');
            while(!feof($fp))
            {
                $line = trim(fgets($fp));
                $pinyins[$line[0].$line[1]] = substr($line, 3, strlen($line)-3);
            }
            fclose($fp);
        }
        for($i=0; $i<$slen; $i++)
        {
            if(ord($str[$i])>0x80)
            {
                $c = $str[$i].$str[$i+1];
                $i++;
                if(isset($pinyins[$c]))
                {
                    if($ishead==0)
                    {
                        $restr .= $pinyins[$c];
                    }
                    else
                    {
                        $restr .= $pinyins[$c][0];
                    }
                }else
                {
                    $restr .= "_";
                }
            }else if( preg_match("/[a-z0-9]/i", $str[$i]) )
            {
                $restr .= $str[$i];
            }
            else
            {
                $restr .= "_";
            }
        }
        if($isclose==0)
        {
            unset($pinyins);
        }
        return strtolower($restr);
    }
}


