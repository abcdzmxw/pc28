<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use Carbon\Carbon;
use Support\GameRuleService;
use think\Db;
use think\Exception;
use think\exception\PDOException;

/**
 * 历史期号
 *
 * @icon fa fa-circle-o
 */
class Issue extends Backend
{
    
    /**
     * Issue模型对象
     * @var \app\admin\model\Issue
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Issue;
        $this->view->assign("statusList", $this->model->getStatusList());
    }

    public function import()
    {
        parent::import();
    }

    public function addAll()
    {
        if ($this->request->isAjax()){
            $rawDateStr = $this->request->param('date');
            $dateArr = explode(" - ",$rawDateStr);
            if (count($dateArr) != 2) return $this->error("数据错误");
            $startDate = Carbon::createFromFormat("Y-m-d",$dateArr[0])->setTime(0,0,0);
            $endDate = Carbon::createFromFormat("Y-m-d",$dateArr[1])->setTime(0,0,0);

            // 所选的区间都生成闭区间
            $endDate->addDay();

            $game_id = (int)$this->request->param('game_id');

            $tempStartDate = clone $startDate;
            $tempStartDate->setMinute(1);
            $startNUmber = (int)$tempStartDate->rawFormat("YmdHi");
            $dateStr = $startDate->rawFormat("Ymd");
            while ($startDate->lt($endDate)){
                $tempDateStr = $startDate->rawFormat("Ymd");
                if($dateStr != $tempDateStr){
                    $dateStr = $tempDateStr;
                    $tempStartDate = clone $startDate;
                    $tempStartDate->setHour(0);
                    $tempStartDate->setMinute(1);
                    $startNUmber = (int)$tempStartDate->rawFormat("YmdHi");
                }

                $startDate->addMinutes(3);
                $issue = new \app\admin\model\Issue();
                $issue->game_id  =(int)$this->request->input('game_id');
                $issue->site_1 = rand(0,9);
                $issue->site_2 = rand(0,9);
                $issue->site_3 = rand(0,9);
                $issue->game_id = $game_id;
                $issue->code = $issue->site_1 + $issue->site_2 + $issue->site_3;
                $issue->issue = (string)$startNUmber;
                //$issue = $service->lotterySpecial($issue);
                $startNUmber++;


                try {
                    $issue->save();
                } catch (\Throwable $e) {

                }
            }
            return $this->success("添加成功");
        }

        $game = Db::name("game")->select();
        $this->view->assign("game",$game);
        return $this->view->fetch();
    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */
    

}
