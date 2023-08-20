<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class KefuToolbar extends Model
{

    use SoftDelete;


    // 表名
    protected $name = 'kefu_toolbar';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'status_text',
        'position_text'
    ];

    public function getPositionList()
    {
        return [
            'frontend' => __('Position frontend'),
            'backend'  => __('Position backend'),
            'general'  => __('Position general')
        ];
    }


    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list  = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getPositionTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['position']) ? $data['position'] : '');
        $list  = $this->getPositionList();
        return isset($list[$value]) ? $list[$value] : '';
    }


}
