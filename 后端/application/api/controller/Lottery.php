<?php

namespace app\api\controller;

use app\common\controller\Api;
use Support\GameRuleService;
use Support\Identify\Instance\IdentifyDanDan;
use Support\LastMessage;
use think\Db;

class Lottery extends Api
{
    protected array $noNeedLogin = ['index', 'heart'];
    protected array $noNeedRight = ['*'];


    /**
     * 交易大廳
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    function index()
    {
        $result['gameList'] = Db::name('game')
            ->where('status', 1)
            ->whereNull('deletetime')
            ->order('weigh', 'desc')
            ->field(['id', 'name', 'mark', 'status'])
            ->select();
        array_walk($result['gameList'], function (&$item) {
            $item['planList'] = Db::name("game_plan")
                ->where(['status' => 1, 'game_id' => $item['id']])
                ->whereNull('deletetime')
                ->order('weigh', 'desc')
                ->select();
            foreach ($item['planList'] as &$plan) {
                $plan['robot_num'] += mt_rand(1, 100);
            }
            $item['issue'] = GameRuleService::nowIssue();
            $item['issue']['last_code'] = Db::name('issue')->where('game_id', $item['id'])->where('issue', $item['issue']['last_issue'])->value('code');
        });
        $this->success('', $result);
    }

    function oddsList()
    {
        $planId = $this->request->param('plan_id');
        $result['rule_base'] = Db::name('plan_rule')
            ->where('game_plan_id', $planId)
            ->whereIn('class', [1, 2, 3, 4, 11, 12, 13, 14, 21, 22])
            ->whereNotNull('deletetime')
            ->select();
        $result['rule_num'] = Db::name('plan_rule')
            ->where('game_plan_id', $planId)
            ->whereIn('class', [41, 42, 43, 44, 45, 46, 47, 48, 49, 410, 411, 412, 413, 414, 415, 416, 417, 418, 419, 420, 421, 422, 423, 424, 425, 426, 427,])
            ->select();
        $result['num_code'] = IdentifyDanDan::NUM_CODE;
        
        $this->success('', $result);
    }


    /**
     * 下單
     */
    function playOrder()
    {
        $issue = (int)$this->request->param('issue');
        $gameId = (int)$this->request->param('game_id');
        $planId = (int)$this->request->param('plan_id');
        $playType = (int)$this->request->param('play_type');
        $playName = '';
        if (in_array($playType, [31, 32, 33])) {
            $playCode = '';//豹順對
        } else if (array_key_exists($playType, IdentifyDanDan::PAY_CLASS)) {
            $playCode = IdentifyDanDan::NUM_CODE[IdentifyDanDan::PAY_CLASS[$playType]];
            $playName = IdentifyDanDan::PAY_CLASS[$playType];
        } elseif (array_key_exists($playType, IdentifyDanDan::PAY_CLASS_SINGLE1)) {
            $playCode = IdentifyDanDan::PAY_CLASS_SINGLE1[$playType];
            $playName = $playCode;
        } else {
            $this->error('玩法錯誤');
        }
        $money = $this->request->param('money');
        $min_limit = (float)Db::name('game_plan')->where(['id' => $planId, 'game_id' => $gameId])->value('min_limit');
        if ($min_limit == 0 or (float)$money < $min_limit) {
            $this->error("最低購入{$min_limit}");
        }
        $userId = $this->auth->id;
        $issueInfo = Db::name('issue')->where('game_id', $gameId)->where('issue', $issue)->find();
        if (empty($issueInfo) or $issueInfo['status'] == 1 or $issueInfo['game_id'] != $gameId or GameRuleService::IsClose($issueInfo['issue'])) {
            $this->error('期號錯誤');
        }
        if (Db::name('game')->where('id', $gameId)->value('status') != 1)
            $this->error('當前停售');
        $userQuantityInfo = Db::name('user')->where('id', $userId)->field(['money', 'score', 'nickname'])->find();
        $temp = 5 + strlen($this->agentId);
        $orderData['orderNo'] = $issueInfo['issue'] . substr(time(), $temp) . $this->agentId . mt_rand(100000, 999999);
        $orderData['user_id'] = $userId;
        $orderData['admin_id'] = $this->agentId;
        $orderData['issue_id'] = $issueInfo['id'];
        $orderData['game_id'] = $gameId;
        $orderData['status'] = 0;
        $orderData['odds'] = Db::name('plan_rule')->where(['game_plan_id' => $planId, 'class' => $playType])->value('odds');
        $orderData['game_id'] = $gameId;
        $orderData['play_type'] = $playType;
        $orderData['play_code'] = $playCode;
        $orderData['play_method'] = $playName;
        $orderData['plan_id'] = $planId;
        $orderData['plan_name'] = Db::name('game_plan')->where('id', $planId)->value('title');
        $orderData['updatetime'] = time();
        $orderData['createtime'] = time();
        $orderData['quantity'] = $money;
        $orderData['began_quantity'] = $userQuantityInfo['money'];
        if ($userQuantityInfo['score'] > 0 and $userQuantityInfo['score'] < $money) {
            $decQuantity = $money - $userQuantityInfo['score'];
            $score = "0";
        } elseif ($userQuantityInfo['score'] > 0 and $userQuantityInfo['score'] >= $money) {
            $score = $userQuantityInfo['score'] - $money;
            $decQuantity ="0";
        } else {
            $decQuantity = $money;
            $score = 0;
        }
        $orderData['after_quantity'] = bcsub($userQuantityInfo['money'], $decQuantity);
        if ((int)$orderData['after_quantity'] < 0) {
            $this->error('余額不足，請聯係客服充值');
        }
        $quantityData['user_id'] = $userId;
        $quantityData['admin_id'] = $this->agentId;
        $quantityData['quantity'] = -$money;
        $quantityData['updatetime'] = time();
        $quantityData['createtime'] = time();
        $quantityData['over'] = $orderData['after_quantity'];
        $quantityData['type'] = 2;
        $quantityData['status'] = 1;
        Db::startTrans();
        try {
            $isTrue = Db::name('user')
                ->where(['id' => $userId, 'money' => $userQuantityInfo['money'], 'score' => $userQuantityInfo['score']])
                ->update(['score' => $score, 'money' => $orderData['after_quantity']]);
            if (!$isTrue) {
                throw new \Exception('下單失敗');
            }

            $quantityLogId = Db::name('quantity_log')->insertGetId($quantityData);
            $orderData['quantity_log_id'] = $quantityLogId;
            $isTrue = Db::name('order')->insertGetId($orderData);
            if (!$isTrue) {
                throw new \Exception('下單失敗');
            }
            Db::commit();
        } catch (\Throwable $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        $result['issue'] = $issueInfo['issue'];
        $result['user_id'] = $orderData['user_id'];
        $result['id'] = $isTrue;
        $result['play_method'] = $playName;
        $result['play_type'] = $playType;
        $result['play_code'] = $playCode;
        $result['quantity'] = $orderData['quantity'];
        $result['nickname'] = assoc_substr($userQuantityInfo['nickname'],  6) . substr("***", 0, mt_rand(1, 6));
        $result['after_quantity'] = $orderData['after_quantity'];
        $lastMessage = new LastMessage();
        $lastMessage->add(1, $orderData['plan_id'], $result);
        
        $this->success('下單成功', $result);
    }

    public function heart()
    {
        $planId = $this->request->param('plan_id');
        $gameId = $this->request->param('game_id');

        $result['game_status'] = Db::name('game')->where('id', $gameId)->value('status');
        if ($result['game_status'] == 1 and mt_rand(0, 10) >= 7) {
            $roomList = Db::name('game_plan')->where('status', 1)->select();
            GameRuleService::makeRebootOrder($roomList);
        }
        $result['issue'] = GameRuleService::nowIssue();
        $lastMessage = new LastMessage();
        $message  = $lastMessage->gets(1, $planId);
        array_walk($message,function ($order) use (&$result){
            if (empty($result) or empty($order['issue']) )
                return;
            if ($order['issue'] == $result['issue']['issue']){
                $result['message'][] = $order;
            }
        });
        $result['hit_notice'] = $lastMessage->gets(0, 0);
        $result['user_quantity'] = Db::name('user')->where('id', $this->auth->id)->field(['id', 'money', 'score'])->find();
        $result['last_issue'] = Db::name('issue')->where('issue', $result['issue']['last_issue'])->where('game_id',$gameId)->find();
        $result['last_issue']['base'] = $result['last_issue'] ? GameRuleService::lotteryBase($result['last_issue']) : null;
        $result['issue']['whether_close'] = GameRuleService::IsClose($result['issue']['issue']);
        
        /*$result = Common::strReplace('高', __('高'), $result);*/
        $this->success('', $result);
    }

    public function issueList()
    {
        $gameId = $this->request->param('game_id');
        $date = $this->request->param('date');
        if (empty($date)) {
            $nowIssue = GameRuleService::nowIssue()['issue'];

        }else{
            $nowIssue = str_replace('-','',$date);
        }
        $id = (int)Db::name('issue')->where(['issue'=> $nowIssue,'game_id'=>$gameId])->value('id');
        $issueList = Db::name('issue')
            ->where('game_id', $gameId)
            ->where('id', '<', $id)
            ->order('id', 'desc')
            ->limit(480)->select();
        array_walk($issueList, function (&$issue) {
            $issue['base'] = GameRuleService::lotteryBase($issue);
            $key_str = implode('',$issue['base']);
            $issue['join'] = str_replace(['做','平'],'',$key_str);

        });
        
        $this->success('', $issueList);
    }

    public function cand()
    {
       $temp = [
           "多单"=>[1, 5, 1, 1],
           "多双"=>[1, 5, 7, 1],
           "空单"=>[5, 1, 1, 1],
           "空双"=>[5, 1, 7, 1]];
        $gameId = $this->request->param('game_id');
//        $date = $this->request->param('date');
//        if (empty($date)) {
//            $nowIssue = GameRuleService::nowIssue()['issue'];
//
//        }else{
//            $nowIssue = str_replace('-','',$date);
//        }
        $nowIssue = GameRuleService::nowIssue()['issue'];
        $id = (int)Db::name('issue')->where(['issue'=> $nowIssue,'game_id'=>$gameId])->value('id');
        $issueList = Db::name('issue')
            ->where('game_id', $gameId)
            ->where('id', '<', $id)
            ->order('id', 'desc')
            ->limit(50)->select();
        $count = 14;
        array_walk($issueList, function (&$issue)use ($temp) {
            $issue['base'] = GameRuleService::lotteryBase($issue);
            $key_str = implode('',$issue['base']);
            $issue['join'] = str_replace(['做','平'],'',$key_str);
            $issue['cand'] = $temp[$issue['join']];
            $rand = mt_rand(0,5);
            $issue['cand'] =  array_map(function ($data) use ($rand){
                return $rand + $data;
            },$issue['cand']);
            $issue['cand_code'] = mt_rand(-10,15);
        });
        $this->success('',$issueList);
    }

//    public function


}