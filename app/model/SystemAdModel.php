<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin think\Model
 */
class SystemAdModel extends Model
{
    //主键
    protected $pk = 'id';
    //表名
    protected $name = 'system_ad';
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';
    // 定义时间戳字段名
    protected $createTime = 'create_at';
    protected $updateTime = 'update_at';

    public function profile()
    {
        return $this->hasOne(SystemAdPositionModel::class,'id','p_id');
    }
}
