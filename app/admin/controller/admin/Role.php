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

namespace app\admin\controller\admin;

use app\admin\controller\BaseAdminController;
use app\admin\model\SystemMenu;
use app\admin\model\SystemRole;
use think\Request;

class Role extends BaseAdminController
{
    /**
     * 列表
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $data = SystemRole::select();
        $this->assign("data",$data);
        return $this->fetch();
    }

    /**
     * 添加修改
     * @param int $id
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function form($id=0)
    {
        $menu = SystemMenu::select();
        if ($id) {
            $data = SystemRole::find($id);
            if(empty($data)) return $this->fail("角色不存在");
            $tree = SystemMenu::vueTree($menu,0,$data['menu_id']);
        } else {
            $data = new SystemRole();
            $tree = SystemMenu::vueTree($menu);
        }

        $this->assign("tree",$tree);
        $this->assign("data",$data);
        return $this->fetch();
    }
    public function save(Request $request)
    {
        $data = $request->put();
        if (empty($data['name'])) $this->fail("角色名不能为空");
        if (!empty($data['menu_id']) && is_array($data['menu_id'])) {
            $data['menu_id'] = implode(",", $data['menu_id']);
            if (empty($data['id'])) {
                unset($data['id']);
                $m = SystemRole::create($data);
            } else {
                $m = SystemRole::update($data,['id'=>$data['id']]);
            }
            if ($m) {
                SystemRole::setCache($m['id']);
                return $this->success();
            }
        }
        return $this->fail();
    }

    /**
     * 删除指定资源
     * @param $id
     * @return false|string
     * @throws \Exception
     */
    public function delete($id)
    {
        $m = SystemRole::where("id",$id)->delete();
        if ($m) return $this->success();
        return $this->fail();
    }
}
