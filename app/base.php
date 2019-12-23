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
// 数据绝对路径
define('APP_PATH', __DIR__ . '/app/');

//缓存名称
define('CACHE_CONFIG',  'systemConfig');        //系统表
define('CACHE_CATEGORY',  'systemCategory');    //栏目
define('CACHE_CATEGORY_',  'systemCategory_');  //栏目id name,栏目下内容
define('CACHE_ROLE_URL_','systemRoleUrl_');       //角色拥有的url