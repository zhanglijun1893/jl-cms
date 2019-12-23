<?php
declare (strict_types = 1);

namespace app\api\controller;

use app\BaseController;
use app\model\CategoryModel;
use app\model\ConfigModel;
use app\model\ContentModel;
use app\model\SystemAdModel;
use think\Request;

class Index extends BaseController
{
    /**
     * 首页菜单
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getCategory()
    {
        $category = cache('headerCategory');
        if (empty($category)) {
            CategoryModel::setCache();
            $category = cache('headerCategory');
        }
        return $this->success($category);
    }

    /**
     * 获取配置文件
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getConfig()
    {
        $config = cache("systemConfig");
        if (empty($config)) {
            ConfigModel::setCache();
            $config = cache("systemConfig");
        }
        return $this->success($config);
    }

    /**
     * 广告图片
     * @param $id
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAd($id)
    {
        $list = SystemAdModel::where('p_id',$id)
            ->order("list_order DESC, id DESC")
            ->select();
        return $this->success($list);
    }
    public function index()
    {
        return $this->success();
    }
    //首页单页栏目+内容
    public function getAbout()
    {
        $category = $this->getCategoryData(1,2);
        if (empty($category)) return $this->success();
        $model = ContentModel::where('c_id',$category['id'])
            ->field("id,content")
            ->limit(1)
            ->order("list_order DESC, id DESC")
            ->select()->toArray();
        if (empty($model)) return $this->success();
        $list = array_merge($category,['content'=>$model]);
        return $this->success($list);
    }
    //首页红色风格新闻列表
    public function getNewsAll()
    {
        $category = $this->getCategoryData(0,2);
        if (empty($category)) return $this->success();
        //查看是否有子类
        $cc = CategoryModel::getCache();
        $arr = [];
        foreach ($cc as $key=>$value) {
            if ($category['id'] == $value['parent_id']) {
                $item = $value;
                $item['content'] =  ContentModel::where('c_id',$value['id'])
                    ->where('index_view',1)
                    ->field("id,title,update_at")
                    ->limit(10)
                    ->order("list_order DESC, id DESC")->select()->toArray();
                $arr[] = $item;
            }
        }
        if (empty($arr)) return $this->success();
        $list = array_merge($category,['content'=>$arr]);
        return $this->success($list);
    }
    //首页默认风格新闻列表
    public function getNews()
    {
        $category = $this->getCategoryData(0,2);
        if (empty($category)) return $this->success();
        //查看是否有子类
        $cc = CategoryModel::getCache();
        $ids = [];
        foreach ($cc as $key=>$value) {
            if ($category['id'] == $value['parent_id']) {
                $ids[] = $value['id'];
            }
        }
        $model = ContentModel::where('c_id','in',$ids)
            ->where('index_view',1)
            ->field("id,title,images_path,meta_description")
            ->limit(4)
            ->order("list_order DESC, id DESC")->select()->toArray();
        if (empty($model)) return $this->success();
        $list = array_merge($category,['content'=>$model]);
        return $this->success($list);
    }
    //首页下载列表
    public function getDownload()
    {
        $category = $this->getCategoryData(3,2);
        if (empty($category)) return $this->success();
        $model = ContentModel::where([
            'c_id'=>$category['id'],
            'index_view'=>1
        ])
            ->field("id,title,images_path,content")
            ->limit(4)
            ->order("list_order DESC, id DESC")
            ->select()->toArray();
        if (empty($model)) return $this->success();
        $list = array_merge($category,['content'=>$model]);
        return $this->success($list);
    }
    //首页下载列表
    public function getPic()
    {
        $category = $this->getCategoryData(2,2);
        if (empty($category)) return $this->success();
        $model = ContentModel::where([
            'c_id'=>$category['id'],
            'index_view'=>1
        ])
            ->field("id,title,images_path,content")
            ->limit(4)
            ->order("list_order DESC, id DESC")
            ->select()->toArray();
        if (empty($model)) return $this->success();
        $list = array_merge($category,['content'=>$model]);
        return $this->success($list);
    }
    private function getCategoryData($template,$iv) {
        $model = CategoryModel::getCache();
        $category = [];
        foreach ($model as $key=>$value) {
            $index_view = explode(",",$value['index_view']);
            if ($value['template']==$template && in_array($iv,$index_view)) {
                $category = $value;
            }
        }
        return $category;
    }
}
