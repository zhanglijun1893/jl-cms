<?php
declare (strict_types = 1);

namespace app\admin\controller;

use app\admin\model\SystemRole;
use app\BaseController;
use think\exception\HttpResponseException;
use think\facade\Session;
use think\facade\Request;

class BaseAdminController extends BaseController
{
    protected function initialize()
    {
        parent::initialize();
        $action = Request::controller(true) . "/" . Request::action(true);
        $r = ["index/login","index/captcha","index/logout"];
        if (!in_array($action,$r)) {
            //没有登录
            if (!Session::has('adminId') || !Session::has('adminName')) {
                throw new HttpResponseException(redirect('/admin/index/login'));
            }
            if (session('adminId') == 1 && session('adminName') == 'admin' ) return true;
            $common = ['index/index','index/welcome'];
            if (!in_array($action,$common)) {
                $this->checkAuth($action);
            }
        }
        return true;
    }
    //验证权限
    private function checkAuth($action)
    {
        $roleId = session('roleId');
        $url = cache(CACHE_ROLE_URL_.$roleId);
        if (empty($url)) {
            $url = SystemRole::setCache($roleId);
        }
        if (!in_array($action,$url)) {
            if (Request::action(true) == 'save' || Request::action(true) == 'psave') {
                header('Content-type: application/json; charset=utf-8');
                echo json_encode(['status'=>400,'message'=>"没有访问权限",'data'=>[]]);
                exit;
            }
            $this->error(401,"没有访问权限！");
        }
    }
    //错误页面
    public function error($code=400,$message="")
    {
        $this->assign('code',$code);
        $this->assign("message",$message);
        exit($this->fetch('/error'));
    }
}
