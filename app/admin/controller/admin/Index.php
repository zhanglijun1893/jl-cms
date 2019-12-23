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
declare (strict_types=1);

namespace app\admin\controller\admin;

use app\admin\controller\BaseAdminController;
use app\admin\FormBuilder as Form;
use app\admin\model\SystemAdmin;
use app\admin\model\SystemRole;
use app\common\Utils;
use think\facade\Route;
use think\facade\Session;
use think\Request;

class Index extends BaseAdminController
{
    /**
     * 显示资源列表
     *
     */
    public function index()
    {
        $data = SystemAdmin::withJoin('profile', 'LEFT')
            ->order('id', 'DESC')->select();
        $this->assign("data", $data);
        return $this->fetch();
    }

    //表单
    public function form($id = 0)
    {
        $roleId = 0;
        if ($id) {
            $model = SystemAdmin::find($id);
            if (empty($model)) return $this->fail("用户不存在");
            $roleId = $model['role_id'];
        } else {
            $model = new SystemAdmin();
        }
        $field = [];
        $field[] = Form::input('username', '用户名', $model['username'])->required('用户名必填');

        if (empty($id)) {
            $field[] = Form::password('password', '密码')->required('密码必填');
            $field[] = Form::password('confirm_password', '确认密码')->required('密码必填');
            if ($id!=1) {
                $field[] = Form::select('role_id', '角色', (string)$roleId)->setOptions(function () {
                    $roleModel = SystemRole::select();
                    $role = [];
                    foreach ($roleModel as $value) {
                        $role[] = ['value' => $value['id'], 'label' => $value['name']];
                    }
                    return $role;
                });
            }
        }

        $field[] = Form::input('real_name', '真实姓名', $model['real_name']);
        $field[] = Form::input('phone', '手机', $model['phone']);
        $field[] = Form::hidden("id", $model['id']);
        $form = Form::make_post_form('新增管理员', $field, Route::buildUrl('save'));
        $this->assign(compact('form'));
        return $this->fetch("/form-builder");
    }

    public function editPassword($id=0, Request $request)
    {
        if ($id==1 && Session::get("adminId")!=1) return $this->fail("没有权限");
        $model = SystemAdmin::find($id);
        if (empty($model)) return $this->fail("用户不存在");

        if ($request->isPost()) {
            $data = $request->put();
            if (!empty($data['id'])) {
                if (strlen($data['password']) < 6) return $this->fail("密码长度至少六位");
                if ($data['password'] != $data['confirm_password']) return $this->fail("两次密码不一至");
                $encrypt = create_randomStr();
                $data['encrypt'] = $encrypt;
                $data['password'] = md5(md5(trim($data['password'])).$encrypt);
                $model = SystemAdmin::update($data, ['id' => $data['id']]);
                if ($model) return $this->success();
                return $this->fail();
            }
        }

        $field = [];
        $field[] = Form::password('password', '密码')->required('密码必填');
        $field[] = Form::password('confirm_password', '确认密码')->required('确认密码必填');
        $field[] = Form::hidden("id", $model['id']);
        $form = Form::make_post_form('修改密码', $field, Route::buildUrl());
        $this->assign(compact('form'));
        return $this->fetch("/form-builder");
    }

    /**
     * 保存新建的资源
     *
     * @param \think\Request $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        if ($request->isPost()) {
            $data = $request->put();
            if (empty($data['id'])) {
                if (strlen($data['password']) < 6) return $this->fail("密码长度至少六位");
                if ($data['password'] != $data['confirm_password']) return $this->fail("两次密码不一至");
                $encrypt = create_randomStr();
                $data['encrypt'] = $encrypt;
                $data['password'] = md5(md5($data['password']).$encrypt);
                $model = SystemAdmin::create($data);
            } else {
                $model = SystemAdmin::update($data, ['id' => $data['id']]);
            }
            if ($model) return $this->success();
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
        if ($id == 1) return $this->fail("admin不能删除");
        $m = SystemAdmin::where("id", $id)->delete();
        if ($m) return $this->success();
        return $this->fail();
    }
}
