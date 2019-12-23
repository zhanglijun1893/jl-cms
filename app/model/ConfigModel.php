<?php
declare (strict_types = 1);

namespace app\model;
use think\Model;

/**
 * @mixin think\Model
 */
class ConfigModel extends Model
{
    //表名
    protected $name = 'system_config';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';
    // 定义时间戳字段名
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    /**
     * 添加缓存
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public static function setCache()
    {
        $config = self::select();
        $arr = [];
        foreach ($config as $key=>$value) {
            $arr[$value['name']] = $value['value'];
        }
        cache(CACHE_CONFIG,NULL);
        cache(CACHE_CONFIG, $arr);
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
        $config = cache(CACHE_CONFIG);
        if (empty($config)) {
            self::setCache();
            $config = cache(CACHE_CONFIG);
        }
        return $config;
    }
}
