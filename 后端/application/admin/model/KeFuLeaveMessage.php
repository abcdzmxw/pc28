<?php

namespace app\admin\model;

use think\Model;

class KeFuLeaveMessage extends Model
{

    // 表名
    protected $name = 'kefu_leave_message';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];

    public function kefuuser()
    {
        return $this->belongsTo('KeFuUser', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
