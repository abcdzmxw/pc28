<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

/**
 * @property int $game_id
 * @property int $issue
 * @property string $site_1
 * @property string $site_2
 * @property string $site_3
 * @property string $code
 * @property string $mark
 */
class Issue extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'issue';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'status_text'
    ];
    

    
    public function getStatusList()
    {
        return ['0' => __('Status 0'), '1' => __('Status 1')];
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ? $value : (isset($data['status']) ? $data['status'] : '');
        $list = $this->getStatusList();
        return isset($list[$value]) ? $list[$value] : '';
    }




}
