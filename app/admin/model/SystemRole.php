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

/**
 * @mixin think\Model
 */
class SystemRole extends Model
{
    //主键
    protected $pk = 'id';
    //表名
    protected $name = 'system_role';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';
    // 定义时间戳字段名
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public static function setCache($id)
    {
        $m = self::find($id);
        if (empty($m) && $m['menu_id']) return;
        $ids = explode(',',$m['menu_id']);
        $menu = SystemMenu::where('id','in',$ids)->select();
        //var_dump($menu);
        if (empty($menu)) return;
        $url = [];
        foreach ($menu as $menu) {
            $url[] = $menu['controller'].'/'.$menu['action'];
        }
        cache(CACHE_ROLE_URL_.$id, NULL);
        cache(CACHE_ROLE_URL_.$id, $url);
    }
}
