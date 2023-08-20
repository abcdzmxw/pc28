<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Order extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'order';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'lottery_art_text',
        'whether_hit_text',
        'status_text'
    ];
    

    
    public function getLotteryArtList()
    {
        return ['0' => __('Lottery_art 0'), '1' => __('Lottery_art 1')];
    }

    public function getWhetherHitList()
    {
        return ['0' => __('Whether_hit 0'), '1' => __('Whether_hit 1')];
    }

    public function getStatusList()
    {
        return ['-1' => __('Status -1'), '0' => __('Status 0'), '1' => __('Status 1')];
    }


    public function getLotteryArtTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['lottery_art']) ? $data['lottery_art'] : '');
        $list = $this->getLotteryArtList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getWhetherHitTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['whether_hit']) ? $data['whether_hit'] : '');
        $list = $this->getWhetherHitList();
        return isset($list[$value]) ? $list[$value] : '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
