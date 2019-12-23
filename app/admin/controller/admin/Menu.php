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

use app\common\Utils;
use app\admin\model\SystemMenu;
use think\Request;
use think\facade\Route as Url;
use app\admin\FormBuilder as Form;
use app\admin\controller\BaseAdminController;

class Menu extends BaseAdminController
{
    /**
     * 显示资源列表
     *
     * @return string
     */
    public function index()
    {
        $m = SystemMenu::select()->toArray();
        $data = SystemMenu::orderList($m);
        $this->assign('data',$data);
        return $this->fetch();
    }

    public function form($parentId=0,$id=0)
    {
        $display = $list_order = 0;
        if ($id) {
            $m = SystemMenu::where('id', $id)->find();
            $parentId = $m['parent_id'];
            $display = $m['display'];
            $list_order = $m['list_order'];
        } else {
            $m = new SystemMenu();
        }
        $field = [];
        $field[] = Form::select('parent_id','上级菜单',(string)$parentId)->setOptions(function(){
            $list =sortListTier(SystemMenu::select()->toArray(),'0','parent_id','id');
            $menus = [['value'=>0,'label'=>'顶级菜单']];
            foreach ($list as $menu){
                $menus[] = ['value'=>$menu['id'],'label'=>$menu['html'].$menu['name']];
            }
            return $menus;
        });
        $field[] = Form::input('name','菜单名称',$m['name'])->required('菜单名称必填');
        $field[] = Form::hidden("module","admin");
        $field[] = Form::input('controller','控制器',$m['controller'])->required('菜单名称必填');
        $field[] = Form::input('action','方法',$m['action']);
        $field[] = Form::input('icon','图标',$m['icon']);
        /*$field[] = Form::frameInputOne('icon1','图标',Url::buildUrl('admin/widget.widgets/icon',['fodder'=>'icon']),$m['icon'])->icon('ios-ionic')->height('500px');*/
        $field[] = Form::radio('display','是否菜单',$display)->options([['value'=>0,'label'=>'显示(菜单只显示三级)'],['value'=>1,'label'=>'隐藏']]);
        $field[] = Form::number('list_order','排序',$list_order);
        $field[] = Form::hidden("id",$id);

        $form = Form::make_post_form('添加权限',$field,Url::buildUrl('save'));
        $this->assign(compact('form'));
        return $this->fetch("/form-builder");
    }
    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return false|string
     */
    public function save(Request $request)
    {
        $data = request()->param();
        if ($data['parent_id'] && is_array($data['parent_id'])) {
            $data['parent_id'] = $data['parent_id'][0];
        }
        if ($data['id']) {
            $m = SystemMenu::update($data,['id'=>$data['id']]);
        } else {
            $m = SystemMenu::create($data);
        }
        if ($m) {
            return $this->success();
        }
        return $this->fail();
    }
    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return false|string
     */
    public function delete($id)
    {
        $m = SystemMenu::destroy($id);
        if ($m) return $this->success();
        return $this->fail();
    }
}
