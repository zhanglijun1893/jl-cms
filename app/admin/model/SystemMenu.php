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
declare (strict_types = 1);

namespace app\admin\model;

use think\Model;
use think\facade\Route;

/**
 * @mixin think\Model
 */
class SystemMenu extends Model
{
    //主键
    protected $pk = 'id';
    //表名
    protected $name = 'system_menu';

    public static function orderList($data, $parentId = 0) {
        $list = [];
        foreach ($data as $k => $res) {
            if ($res['parent_id'] == $parentId) {
                $d['id'] = $res['id'];
                $d['name'] = $res['name'];
                $d['display'] = $res['display'];
                $d['parent_id'] = $res['parent_id'];
                $d['children'] = self::orderList($data,$res['id']);
                unset($data[$k]);
                $list[] = $d;
            }
        }
        return $list;
    }
    public static function vueTree($data, $parentId = 0,$select = "") {
        $list = [];
        foreach ($data as $k => $res) {
            if ($res['parent_id'] == $parentId) {
                $d['id'] = $res['id'];
                $d['title'] = $res['name'];
                $d['expand'] = true;
                $d['checked'] = false;
               // $d['menu_id'] = [];
                if (!empty($select)) {
                    $arr = explode(",",$select);
                    if (in_array($res['id'],$arr)) {
                        $d['checked'] = true;
                        /*if (!empty($res['menu_id'])) {
                            $d['menu_id'] = explode(",",$res['menu_id']);
                        }*/
                    }
                }
                $d['children'] = self::vueTree($data,$res['id'],$select);
                unset($data[$k]);
                $list[] = $d;
            }
        }
        return $list;
    }
    public static function menuList()
    {
        $menusList = self::where('display','0')->order('list_order DESC')->select();
        return self::tidyMenuTier(true,$menusList);
    }
    public static function tidyMenuTier($adminFilter = false,$menusList,$pid = 0,$navList = [])
    {
        foreach ($menusList as $k=>$menu){
            $menu = $menu->getData();
            if($menu['parent_id'] == $pid){
                unset($menusList[$k]);
                //$authName = self::getAuthName($menu['action'],$menu['controller'],$menu['module']);// 按钮链接
                $menu['child'] = self::tidyMenuTier($adminFilter,$menusList,$menu['id']);
                if($pid != 0 && !count($menu['child']) && !$menu['controller'] && !$menu['action']) continue;
                $menu['url'] = !count($menu['child']) ? Route::buildUrl($menu['module'].'/'.$menu['controller'].'/'.$menu['action']) : 'javascript:void(0);';
                if($pid == 0 && !count($menu['child'])) continue;
                $navList[] = $menu;
            }
        }
        return $navList;
    }
    public static function getAuthName($action,$controller,$module)
    {
        return strtolower($module.'/'.$controller.'/'.$action);
    }
}
