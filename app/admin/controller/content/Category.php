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

namespace app\admin\controller\content;

use app\admin\controller\BaseAdminController;
use app\admin\FormBuilder as Form;
use app\model\CategoryModel;
use app\common\Utils;
use think\facade\Route;
use think\Request;

class Category extends BaseAdminController
{
    /**
     * 显示资源列表
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function index()
    {
        $m = CategoryModel::order('list_order DESC, id DESC')->select();
        $data = CategoryModel::orderList($m);
        $this->assign('data',$data);
        return $this->fetch();
    }
    public function form($parentId=0,$id=0)
    {
        if ($id) {
            $model = CategoryModel::where("id",$id)->find();
            if (empty($model)) return $this->fail("栏目不存在");
            $parentId = $model['parent_id'];
        } else {
            $model = new CategoryModel();
        }
        $index_view = [1];
        if (!empty($model['index_view'])) {
            $index_view = explode(",",$model['index_view']);
        }
        $field = [
            Form::select('parent_id','上级栏目',(string)$parentId)->setOptions(function(){
                $list =sortListTier(CategoryModel::select()->toArray(),'0','parent_id','id');
                $menus = [['value'=>0,'label'=>'顶级栏目']];
                foreach ($list as $menu){
                    $menus[] = ['value'=>$menu['id'],'label'=>$menu['html'].$menu['name']];
                }
                return $menus;
            }),
            Form::input('name','栏目名称',$model['name'])->col(Form::col(24))->required('栏目名称必填'),
            Form::input('en_name','英文名称',$model['en_name'])->col(Form::col(24)),
            Form::uploadImageOne('file_path','栏目图片',
                Route::buildUrl('admin/upload/index',['fodder'=>'image']),
                $model['file_path'])->name("image")->uploadType("image"),
            Form::select('template','模板类型',$model['template']?$model['template']:"0")->setOptions(
                categoryTemplate()
            )->col(Form::col(10)),
            Form::input('link','外部链接',$model['link'])->placeholder('http://'),
            Form::input('seo_title','SEO标题',$model['seo_title']),
            Form::input('seo_keywords','SEO关键字',$model['seo_keywords'])->type('textarea'),
            Form::input('seo_description','SEO描述',$model['seo_description'])->type('textarea'),
            Form::checkbox('index_view','首页显示',$index_view)->options([
                ['value'=>1,'label'=>'导航'],
                ['value'=>2,'label'=>'首页'],
                ['value'=>3,'label'=>'页尾'],
            ])->col(Form::col(12)),
            Form::radio('display','显示/隐藏',$model['display']?$model['display']:0)->options([['value'=>0,'label'=>'显示'],['value'=>1,'label'=>'隐藏']]),
            //Form::checkbox('display','显示/隐藏',$model['display']?$model['display']:0)->options([['value'=>0,'label'=>'显示'],['value'=>1,'label'=>'隐藏']]),
            Form::number('list_order','排序',$model['list_order']?$model['list_order']:0),
            Form::hidden("id",$id)
        ];
        $form = Form::make_post_form('添加产品',$field,Route::buildUrl('save'));
        $this->assign(compact('form'));
        return $this->fetch("/form-builder");
    }

    /**
     * 保存新建的资源
     * @param Request $request
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function save(Request $request)
    {
        if ($request->isPost()) {
            $data = $request->put();
            //error_log(json_encode($data),3,$_SERVER['DOCUMENT_ROOT'].'/err_log.txt');
            if (!empty($data['parent_id'])) {
                $m = CategoryModel::find($data['parent_id']);
                $data['level'] = $m['level'] + 1;
            } else {
                $data['level'] = 1;
            }
            $index_view = "";
            if (!empty($data['index_view']) && is_array($data['index_view'])) {
                $index_view = implode(",",$data['index_view']);
            }
            $data['index_view'] = $index_view;
            $data['en_name'] = !empty($data['en_name']) ? $data['en_name'] : to_py($data['name']);
            if (empty($data['id'])) {
                $c = CategoryModel::where('name',$data['name'])->find();
                if (!empty($c)) return $this->fail("栏目名称已存在");
                unset($data['id']);
                $model = CategoryModel::create($data);
            } else {
                $model = CategoryModel::update($data,['id'=>$data['id']]);
            }
            if ($model) {
                //添加栏目缓存
                CategoryModel::setCache();
                return $this->success();
            }
        }
        return $this->fail();
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        $m = CategoryModel::destroy($id);
        if ($m) return $this->success();
        return $this->fail();
    }
}
