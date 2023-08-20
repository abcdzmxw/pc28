<?php

namespace app\admin\model;

use think\Model;


class KeFuCsrKpi extends Model
{


    // 表名
    protected $name = 'kefu_csr_config';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'last_reception_time_text',
        'status_text',
        'sum_reception_count',
        'sum_message_count'
    ];


    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1'), '2' => __('Status 2'), '3' => __('Status 3')];
    }

    public function getSumReceptionCountAttr($value, $data)
    {
        return \think\Db::name('kefu_reception_log')->where('csr_id', $data['admin_id'])->count('id');
    }

    public function getSumMessageCountAttr($value, $data)
    {
        return \think\Db::name('kefu_record')
            ->where('sender_identity', 0)
            ->where('sender_id', $data['admin_id'])
            ->count('id');
    }


    public function getLastReceptionTimeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['last_reception_time']) ? $data['last_reception_time'] : '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list  = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    protected function setLastReceptionTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


    public function admin()
    {
        return $this->belongsTo('Admin', 'admin_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
