<?php
declare (strict_types = 1);

namespace app\model;

use think\Console;
use think\facade\Cache;
use think\Model;

/**
 * @mixin think\Model
 */
class CategoryModel extends Model
{
    //表名
    protected $name = 'category';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';
    // 定义时间戳字段名
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';
    protected static $breadcrumb = "首页";
    private static $en_name = "";

    public function profile()
    {
        return $this->hasOne(ContentModel::class,'c_id','id')->bind(['title']);
    }

    /**
     * 面包屑
     * @param $id
     * @return string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getBreadcrumb($id)
    {
        $m = self::getCache();
        foreach ($m as $key=>$value) {
            if ($value['id'] == $id) {
                if ($value['parent_id']!=0) {
                    self::getBreadcrumb($value['parent_id']);
                }
                self::$breadcrumb .= "&nbsp; > &nbsp;".$value['name'];
            }
        }
        return self::$breadcrumb;
    }

    /**
     * 获取栏目顶级英文名称
     * @param $id
     * @return mixed|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getParentEnName($id)
    {
        $m = self::getCache();
        foreach ($m as $key=>$value) {
            if ($value['id'] == $id) {
                if ($value['parent_id']==0) self::$en_name = $value['en_name'];
                self::getParentEnName($value['parent_id']);
            }
        }
        return self::$en_name;
    }

    /**
     * 添加缓存
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function setCache()
    {
        $model = self::where('display',0)
            ->order('list_order DESC, id ASC')
            ->select()->toArray();
        cache(CACHE_CATEGORY, NULL);
        cache(CACHE_CATEGORY, $model);
        //cache(CACHE_CATEGORY);
    }
    /**
     * 获取缓存
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function getCache()
    {
        $config = cache(CACHE_CATEGORY);
        if (empty($config)) {
            self::setCache();
            $config = cache(CACHE_CATEGORY);
        }
        return $config;
    }
    //列表页element表格排序
    public static function orderList($data, $parentId = 0, $indexView=0) {
        $list = [];
        foreach ($data as $k => $res) {
            if ($res['parent_id'] == $parentId) {
                $d['id'] = $res['id'];
                $d['name'] = $res['name'];
                $d['en_name'] = $res['en_name'];
                $d['link'] = $res['link'];
                $d['parent_id'] = $res['parent_id'];
                $d['display'] = $res['display'];
                if ($indexView==0) {
                    $d['index_view'] = !empty($res['index_view']) ? self::getTypeViewName($res['index_view']) : "不显示";
                } else {
                    $d['index_view'] = $res['index_view'];
                }
                $d['template'] = categoryTemplate()[$res['template']]['label'];
                $d['children'] = self::orderList($data,$res['id'],$indexView);
                unset($data[$k]);
                $list[] = $d;
            }
        }
        return $list;
    }
    //获取首页显示名称
    public static function getTypeViewName($str)
    {
        $index_view = ['不显示','导航','页面','页尾'];
        $name = "";
        if (!empty($str)) {
            $arr = explode(",", $str);
            foreach ($arr as $value) {
                if (!empty($index_view[$value])) {
                    $name .= $index_view[$value].",";
                }
            }
        }
        return empty($name) ? $name : substr($name,0,-1);
    }

    public static function vueTree($data,$parentId,$selectId,$label="title") {
        $list = [];
        foreach ($data as $k => $res) {
            if ($res['parent_id'] == $parentId) {
                $d['id'] = $res['id'];
                $d['cId'] = $res['c_id'];
                $d[$label] = $res['name'];
                $d['template'] = $res['template'];
                $d['expand'] = true;
                if ($res['id'] == $selectId) {
                    $d['selected'] = true;
                }
                $d['children'] = self::vueTree($data,$res['id'],$selectId,$label);
                unset($data[$k]);
                $list[] = $d;
            }
        }
        return $list;
    }

    /**
     * 分类级联菜单
     * @param $data
     * @param int $parentId
     * @return array
     */
    public static function vueList($data, $parentId = 0) {
        $list = [];
        foreach ($data as $k => $res) {
            if ($res['parent_id'] == $parentId) {
                $d['value'] = $res['id'];
                $d['label'] = $res['name'];
                $d['children'] = self::vueList($data,$res['id']);
                unset($data[$k]);
                $list[] = $d;
            }
        }
        return $list;
    }
}
