<?php

namespace app\admin\model;

use think\Model;


class KeFuRecord extends Model
{


    // 表名
    protected $name = 'kefu_record';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'sender_identity_text',
        'message_type_text',
        'status_text'
    ];

    public function getSenderIdentityTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['sender_identity']) ? $data['sender_identity'] : '');
        $list  = $this->getSenderIdentityList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getSenderIdentityList()
    {
        return ['0' => __('Sender_identity 0'), '1' => __('Sender_identity 1')];
    }

    public function getMessageTypeTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['message_type']) ? $data['message_type'] : '');
        $list  = $this->getMessageTypeList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getMessageTypeList()
    {
        return [
            '0' => __('Message_type 0'),
            '1' => __('Message_type 1'),
            '2' => __('Message_type 2'),
            '3' => __('Message_type 3'),
            '4' => __('Message_type 4'),
            '5' => __('Message_type 5')
        ];
    }

    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list  = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }

    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1')];
    }


}
