<?php

namespace app\api\controller;

use app\common\controller\Api;
use Support\GameRuleService;
use Support\LastMessage;
use think\Controller;
use think\Db;

class Carbontask extends Api
{
    protected array $noNeedLogin = ['*'];
    protected array $noNeedRight = ['*'];

    public function dingshijiesuan()
    {   // 定时结算
        $carbon = \Carbon\Carbon::now('Asia/Shanghai');
        $Issue =GameRuleService::nowIssue();
        $tempIssue = $Issue['issue'];
//        if ((int)$carbon->rawFormat('s') < 30){
//
//            $this->success('未到结算时间');
//        }
//        if ((int)$carbon->rawFormat('i') % 3 != 0){
//            $this->success('未到结算时间');
//        }
        $orders = Db::name('order')->where('status',0)->select();
        $issueList = [];
        $count =0;
        $gameList = Db::name('game')->where('status',1)->field(['id','name'])->select();
        $roomList = Db::name('game_plan')->where('status',1)->select();
        $gameMap = array_column($gameList,'name','id');

//        GameRuleService::makeRebootOrder($roomList);

        GameRuleService::makeRebootProfit($gameList);
        array_walk($orders,function ($order) use (&$count,&$issueList,$tempIssue,$gameMap,$Issue){

            if(!array_key_exists($order['issue_id'],$issueList)){
                $issue = Db::name('issue')->where('id',$order['issue_id'])->find();
                if (!$issue)
                    return;
                $issueList[$order['issue_id']] = $issue;
            };
            $issue = $issueList[$order['issue_id']];
            if ((int)$issue['issue'] > $Issue['last_issue'])
                return;
            [$isTrue,$order] = GameRuleService::orderOddsSettle($order,$issue);
            if (!$isTrue)return;
            if ($order['whether_hit']){
                $userQuantity = Db::name('user')->where('id',$order['user_id'])->value('money');
                $quantityData['user_id'] = $order['user_id'];
                $quantityData['admin_id'] = $order['admin_id'];
                $quantityData['quantity'] = $order['profit'];
                $quantityData['updatetime'] = time();
                $quantityData['createtime'] = time();
                $quantityData['over'] = bcadd($userQuantity,$order['profit'],2);
                $quantityData['mark'] = $order['orderNo'];
                $quantityData['type'] = 3;
                $quantityData['status'] = 1;
            }

            Db::startTrans();
            try {

                $isTrue = Db::name('order')->where('id',$order['id'])->update($order);
                if (!$isTrue) throw new \Exception('处理失败');
                if ($order['whether_hit']){
                    $isTrue = Db::name('quantity_log')->insertGetId($quantityData);
                    if (!$isTrue){
                        throw new \Exception('处理失败');
                    }
                    Db::name('user')->where('id',$order['user_id'])->where('money',$userQuantity)->inc('money',$order['profit'])->update();
                    $lastMessage = new LastMessage();
                    $message['nickname'] = substr(Db::name('user')->where('id',$order['user_id'])->value('nickname'),0,2).substr("***",0,mt_rand(1,6));
                    $message['profit'] = $order['profit'];
                    $message['user_id']= $order['user_id'];
                    $message['game_name'] = $gameMap[$order['game_id']];
                    $message['game_id'] = $order['game_id'];
                    $message['order_id'] = $order['id'];
                    $message['issue'] = $issue['issue'];
                    $message['id'] = $isTrue;
                    $lastMessage->add(0,$order['plan_id'],$message);
                }

                Db::commit();

            }catch (\Throwable $e){
                Db::rollback();
                return;
            }
            $count++;

        });




        $this->success('处理订单数'.$count);
    }


}