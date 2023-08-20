<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use Support\GameRuleService;
use think\Db;

/**
 * 房间
 *
 * @icon fa fa-circle-o
 */
class GamePlan extends Backend
{
    
    /**
     * GamePlan模型对象
     * @var \app\admin\model\GamePlan
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\GamePlan;
        $this->view->assign("typeList", $this->model->getTypeList());
        $this->view->assign("statusList", $this->model->getStatusList());
    }

    public function import()
    {
        parent::import();
    }

    public function add(): string
    {
        if ($this->request->isAjax()){
            Db::startTrans();
            try {
                $data = $this->request->param()['row'];
//                $data['createtime'] = time();
//                $data['updatetime'] = time();
                $id = Db::name('game_plan')->insertGetId($data);
                if (!$id){
                    throw new \Exception('添加失敗');
                }
                $service = new GameRuleService();
                $service->createRule($data['game_id'],$id);
                Db::commit();
            }catch (\Throwable $e){
                Db::rollback();
                $this->error($e->getMessage());
            }
            $this->success('添加成功');
        }
        return parent::add();
    }
    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

}
