<?php
declare (strict_types = 1);

namespace app\admin\controller\system;

use app\admin\controller\BaseAdminController;
use app\model\SystemAdModel;
use app\model\SystemAdPositionModel;
use app\admin\FormBuilder as Form;
use think\facade\Route;
use think\Request;

class Ad extends BaseAdminController
{
    //广告列表
    public function index()
    {
        $data = SystemAdModel::withJoin('profile','LEFT')
            ->order('id','DESC')
            ->select();
        $this->assign('data',$data);
        return $this->fetch();
    }
    //广告表单
    public function form($id = 0)
    {
        if ($id) {
            $model = SystemAdModel::find($id);
            if (empty($model)) return $this->fail();
        } else {
            $model = new SystemAdModel();
        }
        $list =SystemAdPositionModel::select()->toArray();
        $position = [];
        foreach ($list as $value){
            $position[] = ['value'=>$value['id'],'label'=>$value['name']];
        }
        $this->assign("data",$model);
        $this->assign("position",$position);
        return $this->fetch();
    }
    //删除广告位置
    public function delete($id)
    {
        $m = SystemAdModel::destroy($id);
        if ($m) return $this->success();
        return $this->fail();
    }
    //广告保存
    public function save(Request $request)
    {
        if ($request->isPost()) {
            $data = $request->put();
            if (empty($data['id'])) {
                $model = SystemAdModel::create($data);
            } else {
                $model = SystemAdModel::update($data,['id'=>$data['id']]);
            }
            if ($model) {
                return $this->success();
            }
        }
        return $this->fail();
    }
    //广告位置
    public function position()
    {
        $data = SystemAdPositionModel::order('id','DESC')->select();
        $this->assign('data',$data);
        return $this->fetch();
    }
    //删除广告位置
    public function pDelete($id)
    {
        $m = SystemAdPositionModel::destroy($id);
        if ($m) return $this->success();
        return $this->fail();
    }
    //广告位表单
    public function pForm($id = 0)
    {
        if ($id) {
            $model = SystemAdPositionModel::find($id);
            if (empty($model)) return $this->fail();
        } else {
            $model = new SystemAdPositionModel();
        }
        $field[] = Form::input('name', '广告位名称', $model['name'])->required('广告位名称必填');
        $field[] = Form::input('ad_width', '宽度', $model['ad_width']);
        $field[] = Form::input('ad_height', '高度', $model['ad_height']);
        $field[] = Form::input('description', '描述', $model['description']);
        $field[] = Form::radio('display','显示/隐藏',$model['display']?$model['display']:0)
            ->options([['value'=>0,'label'=>'显示'],['value'=>1,'label'=>'隐藏']]);
        $field[] = Form::hidden("id", $model['id']);
        $form = Form::make_post_form('添加广告位', $field, Route::buildUrl('pSave'));
        $this->assign(compact('form'));
        return $this->fetch("/form-builder");
    }
    //广告位保存
    public function pSave(Request $request)
    {
        if ($request->isPost()) {
            $data = $request->put();
            if (empty($data['id'])) {
                $model = SystemAdPositionModel::create($data);
            } else {
                $model = SystemAdPositionModel::update($data,['id'=>$data['id']]);
            }
            if ($model) {
                return $this->success();
            }
        }
        return $this->fail();
    }
}
