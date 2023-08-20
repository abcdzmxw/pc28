<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class KeFuSession extends Model
{

    use SoftDelete;

    // 表名
    protected $name = 'kefu_session';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'user_message_count',
        'csr_message_count',
    ];

    public function getUserMessageCountAttr($value, $data)
    {
        return \think\Db::name('kefu_record')
            ->where('session_id', $data['id'])
            ->where('message_type', '<>', 3)
            ->where('sender_identity', 1)
            ->count('id');
    }

    public function getCsrMessageCountAttr($value, $data)
    {
        return \think\Db::name('kefu_record')
            ->where('session_id', $data['id'])
            ->where('sender_identity', 0)
            ->count('id');
    }

    public function kefuuser()
    {
        return $this->belongsTo('KeFuUser', 'user_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }

    public function csr()
    {
        return $this->belongsTo('Admin', 'csr_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
