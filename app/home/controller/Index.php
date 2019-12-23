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

namespace app\home\controller;

use app\model\CategoryModel;
use app\model\ContentModel;

class Index extends HomeBaseController
{
    //首页
    public function index()
    {
        return $this->fetch();
    }
    //详情
    public function view($id)
    {
        $page = ContentModel::where('id',$id)->find();
        $view = empty($page['view_name']) ? "view" : $page['view_name'];
        $breadcrumb = CategoryModel::getBreadcrumb($page['c_id']);
        $this->assign('breadcrumb',$breadcrumb);
        $this->assign('page',$page);
        return $this->fetch($view);
    }
    //列表
    public function list($name)
    {
        $model = CategoryModel::where('en_name',$name)->find();
        $active = CategoryModel::getParentEnName($model['id']);
        $data = ContentModel::where('c_id',$model->id)
            ->order("list_order DESC, id DESC")
            ->paginate(5);
        /*$page = $data->render();
        $this->assign("page",$page);*/
        $this->assign("data",$data);
        if (empty($model)) abort(404, '页面异常');
        $breadcrumb = CategoryModel::getBreadcrumb($model['id']);
        $this->assign('breadcrumb',$breadcrumb);
        $this->assign('active',$active);
        $this->assign('name',$model['en_name']);
        $this->assign('model',$model);
        switch ($model['template']) {
            case '1': { //单页
                $page = ContentModel::where("c_id",$model['id'])->find();
                $this->assign("page",$page);
                return $this->fetch('list/list_single');
                break;
            }
            case '2': { //图片模板
                return $this->fetch('list/list_images');
                break;
            }
            case '3': { //下载模板
                return $this->fetch('list/list_download');
                break;
            }
            default: {  //列表
                return $this->fetch('list/list');
                break;
            }
        }
    }

}
