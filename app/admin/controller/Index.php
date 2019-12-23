<?php
/**
 * @Copyright			君澜科技
 * @License				http://www.junlankeji.com
 * @Author              jun
 * @WeiXinNumber        zhanglijun1893
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用
 * 不允许对程序代码以任何形式任何目的的再发布,如果商业用途务必到官方购买正版授权
 * 版权所有 君澜科技（北京）有限公司，并保留所有权利。
 */
namespace app\admin\controller;
use app\admin\model\SystemAdmin;
use app\admin\model\SystemMenu;
use app\admin\model\SystemRole;
use app\common\Utils;
use think\facade\Session;

/**
 * 后台首页
 * Class Index
 * @package app\admin\controller
 */
class Index extends BaseAdminController
{
    /**
     * 首页 iframe
     * @return string
     */
    public function index()
    {
        $menuList = SystemMenu::menuList();
        $adminName = Session::get('adminName', '');
        $roleName = Session::get('roleName', '');
        $this->assign("adminName",$adminName);
        $this->assign("roleName",$roleName);
        $this->assign('menuList',$menuList);
        return $this->fetch("/index");
    }

    /**
     * 欢迎页
     * @return \think\response\View
     */
    public function welcome()
    {
        return view("/welcome");
    }

    /**
     * 验证码
     * @return \think\Response
     */
    public function captcha()
    {
        ob_clean();
        return captcha();
    }
    public function logout()
    {
        Session::clear();
        return redirect('/admin');
    }

    /**
     * 登录
     * @return string|\think\response\Redirect
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function login()
    {
        if (request()->isPost()) {
            extract(request()->param());
            if(!captcha_check($captcha)) {
                $this->assign("error","验证码不正确");
                return $this->fetch("/login");
            }
            $m = SystemAdmin::where("username",$username)->find();
            if (!$m) {
                $this->assign("error","用户名或密码不正确");
                return $this->fetch("/login");
            }
            $p = md5(md5($password).$m->encrypt);
            if ($p != $m->password) {
                $this->assign("error","用户名或密码不正确");
                return $this->fetch("/login");
            }
            $role = "";
            if ($m->id==1) {
                $role = "超级管理员";
            } else if($m->role_id == 0) {
                $role = "无权限";
            } else {
                $r = SystemRole::where("id",$m->role_id)
                    ->find();
                $role = $r->name;
            }
            Session::set('adminId',$m->id);
            Session::set('adminName',$m->username);
            Session::set('roleId',$m->role_id);
            Session::set('roleName',$role);
            return redirect(url('/admin/index'));
        }
        $this->assign("error","");
        return $this->fetch("/login");
    }
}
