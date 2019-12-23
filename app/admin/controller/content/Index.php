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
use app\common\SnowflakeIdWorker;
use app\common\Utils;
use app\model\CategoryModel;
use app\model\ContentModel;
use app\admin\FormBuilder as Form;
use think\db\Where;
use think\facade\View;
use think\Request;
use think\facade\Route;

class Index extends BaseAdminController
{
    //栏目菜单
    public function index($id=0)
    {
        $category = CategoryModel::vueTree(CategoryModel::select(),0,$id,"label");
        $this->assign("category",$category);
        return $this->fetch();
    }
    //内容列表
    public function lists($id=0)
    {
        $where = !empty($id) ? ['c_id'=>$id]: [];
        $data = ContentModel::withJoin('profile','LEFT')
            ->order('id','DESC')
            ->where($where)
            ->select();
        $this->assign("id",$id);
        $this->assign("data",$data);
        return $this->fetch();
    }
    //单页
    public function single($id)
    {
        $category = CategoryModel::where('id',$id)->find();
        if (empty($category)) return $this->fail("栏目不存在");

        $model = ContentModel::where('c_id',$id)->find();
        if (empty($model)) {
            $model = new ContentModel();
        }
        $this->assign("data",$model);
        $this->assign("category",$category);
        return $this->fetch();
    }
    //列表表单
    public function form($id=0,$cId=0)
    {
        if ($id) {
            $model = ContentModel::where("id",$id)->find()->toArray();
            if (empty($model)) return $this->fail("内容不存在");
        } else {
            $model = new ContentModel();
        }
        $t_id = '';
        $m = CategoryModel::select();
        foreach ($m as $v) {
            if ($v['id']==$model['c_id']) {
                $t_id = $v['template'];
            }
        }
        $list = CategoryModel::vueList($m);
        $view_name = empty(getTemplate()[$t_id]) ? '' : getTemplate()[$t_id];
        $this->assign("view_name",$view_name);
        $this->assign("category",$list);
        $this->assign("cid",$cId);
        $this->assign("data",$model);
        return $this->fetch();
    }

    public function save(Request $request)
    {
        if ($request->isPost()) {
            $data = $request->put();
            if (empty($data['c_id'])) return $this->fail($data);
            $data['index_view'] = empty($data['index_view']) ? "" : implode(",",$data['index_view']);
            if (!empty($data['c_id']) && is_array($data['c_id'])) {
                $data['c_ids'] = empty($data['c_id']) ? "" : implode(",",$data['c_id']);
                $data['c_id'] = end($data['c_id']);
            }
            if (empty($data['id'])) {
                $s = new SnowflakeIdWorker(1);
                $data['id'] = $s->nextId();
                $model = ContentModel::create($data);
            } else {
                $model = ContentModel::update($data,['id'=>$data['id']]);
            }
            if ($model) return $this->success();
        }
        return $this->fail();
    }
    /**
     * 删除指定资源
     */
    public function delete($id)
    {
        $m = ContentModel::where("id",$id)->delete();
        if ($m) return $this->success();
        return $this->fail();
    }
}
