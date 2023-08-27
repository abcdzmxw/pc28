<?php


namespace Support;


use app\admin\model\Issue;
use Carbon\Carbon;
use Support\Identify\Instance\IdentifyDanDan;
use Support\Identify\Structure\BetInfo;
use think\Db;

/**
 * @author 唤雨
 * Class GameRuleService
 * @package App\Service
 * 负责创建 规则 和是否中奖以及赔付金额判定
 */
class GameRuleService
{
    const RULE_CLASS = [
        1 => '高',
        2 => '低',
        3 => '多',
        4 => '空',
        //11 => '多单',
        //12 => '多双',
        //13 => '空单',
        //14 => '空双',
        //21 => '极阴',
        //22 => '极阳',
        //31 => '豹',
        //32 => '顺',
        //33 => '对',
//        34 => '豹回本',
//        35 => '顺回本',
//        36 => '对回本',
//        37 => '视（0,8,9）（0,1,9）为顺',
        41 => '0',
        42 => '1',
        43 => '2',
        44 => '3',
        45 => '4',
        46 => '5',
        47 => '6',
        48 => '7',
        49 => '8',
        410 => '9',
        411 => '10',
        412 => '11',
        413 => '12',
        414 => '14',
        415 => '15',
        416 => '16',
        417 => '17',
        418 => '18',
        419 => '19',
        420 => '20',
        421 => '21',
        422 => '22',
        423 => '23',
        424 => '24',
        425 => '25',
        426 => '26',
        427 => '27',

//        415 => '特码注数限制',
//        51 => '1314回本',
//        52 => '09回本',
//        61 => '注满xxx大小单双',
//        62 => '注满xxx大小单双',
//        63 => '注满xxx大小单双',
//        71 => '禁止反向下注',
//        72 => '只能压一种组合',
//        73 => '禁杀组合',
//        81 => '下注限额大小单双',
//        82 => '下注限额组合',
//        83 => '下注限额极大极小',
//        84 => '下注限额特码',
//        85 => '下注限额豹顺对',
//        86 => '下注限额总限额',
    ];
//    private array $reverseRule = [
//        1 => [2, 13, 14],
//        2 => [1, 11, 12],
//        3 => [4, 12, 14],
//        4 => [3, 11, 13],
//        11 => [14, 2, 4],
//        12 => [13, 2, 3],
//        13 => [12, 1, 4],
//        14 => [11, 1, 3],
//        21 => [22],
//        22 => [21]
//    ];
//
//
//    //豹顺对回本
//    private array $reverseRule1 = [
//        1 => 2,
//        2 => 1,
//        3 => 4,
//        4 => 3,
//        11 => 14,
//        12 => 13,
//        13 => 12,
//        14 => 11,
//        21 => 22,
//        22 => 21
//    ];

    /**
     * 创建PC28房间配置
     * @param $roomId
     * @return bool
     */
    public function createRule($game_id, $roomId): bool
    {
        $ruleDataAll = [];
        $temp['game_plan_id'] = $roomId;
        $temp['status'] = 1;
        $temp['game_id'] = $game_id;
        $temp['odds'] = 0;
        $temp['limit_min'] = 0;
        $temp['limit_max'] = 1000;
        foreach (self::RULE_CLASS as $class => $mark) {
            $temp['class'] = $class;
            $temp['class_name'] = self::RULE_CLASS[$class];
            switch ($class) {
                case 1:
                case 2:
                case 3:
                case 4:
                    $temp['odds'] = 2.2;
                    break;
//                case 11:
//                case 12:
//                case 13:
//                case 14:
//                    $temp['odds'] = 5;
//                    break;
//                case 21:
//                case 22:
//                    $temp['odds'] = 10;
//                    break;
//                case 31:
//                case 32:
//                case 33:
//                    $temp['odds'] = 50;
//                    break;
//                case 34:
//                case 35:
//                case 36:
//                    //豹顺对回本
//                    break;
//                case 41:
//                    $temp['odds'] = 1000;
//                    break;
//                case 42:
//                    $temp['odds'] = 900;
//                    break;
//                case 43:
//                    $temp['odds'] = 800;
//                    break;
//                case 44:
//                    $temp['odds'] = 700;
//                    break;
//                case 45:
//                    $temp['odds'] = 600;
//                    break;
//                case 46:
//                    $temp['odds'] = 500;
//                    break;
//                case 47:
//                    $temp['odds'] = 400;
//                    break;
//                case 48:
//                    $temp['odds'] = 300;
//                    break;
//                case 49:
//                    $temp['odds'] = 200;
//                    break;
//                case 410:
//                    $temp['odds'] = 100;
//                    break;
//                case 411:
//                    $temp['odds'] = 10;
//                    break;
//                case 412:
//                    $temp['odds'] = 5;
//                    break;
//                case 413:
//                    $temp['odds'] = 3;
//                    break;
//                case 414:
//                case 415:
//                    $temp['odds'] = 2;
//                    $temp['odds'] = 2;
//                    break;
//                case 51:
//                case 52:
//                    $temp['status'] = 0;
//                    $temp['odds'] = 1;
//                    break;
//                case 61:
//                    $temp['res'] = 0;
//                    $temp['odds'] = 2;
//                    $temp['limit_max'] = 4;
//                    $temp['limit_min'] = 4;
//                    $temp['status'] = 0;
//                    break;
//                case 62:
//                    $temp['res'] = 100;
//                    $temp['odds'] = 2;
//                    $temp['limit_max'] = 1;
//                    $temp['limit_min'] = 1;
//                    $temp['status'] = 0;
//                    break;
//                case 63:
//                    $temp['res'] = 100;
//                    $temp['odds'] = 1;
//                    $temp['limit_max'] = 1;
//                    $temp['limit_min'] = 1;
//                    $temp['status'] = 0;
//                    break;
//                case 81:
//                case 82:
//                case 83:
//                case 84:
//                case 85:
//                    $temp['limit_max'] = 2000;
//                    $temp['limit_min'] = 1;
//                    break;
//                case 86:
//                    $temp['odds'] = 5000;
//                    break;

            }
            $ruleDataAll[] = $temp;
        }
        Db::name('plan_rule')->insertAll($ruleDataAll);
        $this->getRules($roomId);
        return true;
        //Db::table('room_rule')->insert(room_rule);
    }


    /**
     * @param $roomId
     * @return array|false|mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getRules($roomId)
    {
        $RoomRules = Db::name("plan_rule")->where('game_plan_id', $roomId)->select();
        $keys = array_column((array)$RoomRules, 'class');
        $RoomRules = array_combine($keys, (array)$RoomRules);
        return $RoomRules;
    }


    /**
     * 下注限制验证
     */
    public function verifyPayLimit(BetInfo $betInfo, $issue, $agentId, $userId, $roomId): array
    {
        $roomRuleConfig = $this->getRules($roomId);
        if ($roomRuleConfig[$betInfo->PlayClass]['status'] != 1) {
            return [false, '当前玩法未开启'];
        }
        return $this->_PayLimitVerifyQuantity($betInfo, $issue, $agentId, $userId, $roomRuleConfig);
    }

    public function setOdds(BetInfo $betInfo, $roomId): BetInfo
    {
        $roomRuleConfig = $this->getRules($roomId);
        $betInfo->odds = $roomRuleConfig[$betInfo->PlayClass]['odds'];
        return $betInfo;
    }


    /**
     * 对注单的金额进行 验证限制
     * @param \Support\Identify\Structure\BetInfo $betInfo
     * @param $issue
     * @param $agentId
     * @param $userId
     * @param array $roomRuleConf
     * @return array
     */
    private function _PayLimitVerifyQuantity(BetInfo $betInfo, $issue, $agentId, $userId, array $roomRuleConf): array
    {
        /*        $temp['res'] = 0;
        $temp['odds'] = 2;
        $temp['limit_max'] = 4;
        $temp['limit_min'] = 4;*/
        $quantitySum = Db::table('order')->where([
            'agent_id' => $agentId,
            'user_id' => $userId,
            'issue' => $issue,
            'status' => 0
        ])->sum('quantity');
        if ($quantitySum + $betInfo->Quantity > $roomRuleConf[86]['odds']) {
            return [false, "下注失败 单期限额：" . $roomRuleConf[86]['odds']];
        }
        switch ($betInfo->PlayClass) {
            case 1:
            case 2:
            case 3:
            case 4:
                if ($betInfo->Quantity < $roomRuleConf[81]['limit_min'] or $betInfo->Quantity > $roomRuleConf[81]['limit_max']) {
                    return [false, "下注失败 注单积分不在限制范围内"];
                }
                break;
            case 11:
            case 12:
            case 13:
            case 14:
                if ($betInfo->Quantity < $roomRuleConf[82]['limit_min'] or $betInfo->Quantity > $roomRuleConf[82]['limit_max']) {
                    return [false, "下注失败 注单积分不在限制范围内"];
                }

                break;
            case 21:
            case 22:
                $quantitySum = Db::table('order')
                    ->where(['agent_id' => $agentId, 'user_id' => $userId, 'issue' => $issue,])
                    ->whereIn('play_class', [22, 21])
                    ->sum('quantity');
                $quantitySum += $betInfo->Quantity;
                if ($betInfo->Quantity < $roomRuleConf[83]['limit_min'] or $quantitySum > $roomRuleConf[83]['limit_max']) {
                    return [false, "下注失败 注单积分不在限制范围内"];
                }
                break;
            case 31:
            case 32:
            case 33:
                if ($betInfo->Quantity < $roomRuleConf[85]['limit_min'] or $betInfo->Quantity > $roomRuleConf[85]['limit_max']) {
                    return [false, "下注失败 注单积分不在限制范围内"];
                }
                break;
            case 41:
            case 42:
            case 43:
            case 44:
            case 45:
            case 46:
            case 47:
            case 48:
            case 49:
            case 410:
            case 411:
            case 412:
            case 413:
            case 414:
            case 415:
            case 416:
            case 417:
            case 418:
            case 419:
            case 420:
            case 421:
            case 422:
            case 423:
            case 424:
            case 425:
            case 426:
            case 427:
                $count = 0;
                $tempArr = [41, 42, 43, 44, 45, 46, 47, 48, 49, 410, 411, 412, 413, 414, 415, 416, 417, 418, 419, 420, 421, 422, 423, 424, 425, 426, 427,];
                foreach ($tempArr as $playClass) {
                    $count += $this->getReverseLimitVerifyCache($agentId, $userId, $betInfo->game_id, $playClass, $issue) ? 1 : 0;
                }
                if ($count >= $roomRuleConf[415]['odds']) {
                    return [false, "下注失败 特码单期不同类型最多下{$count}注"];
                }
                /*                $quantitySum = Db::table('order')
                                    ->where(['agent_id' => $agentId, 'user_id' => $userId, 'issue' => $issue,])
                                    ->whereIn('play_class', [41, 42, 43, 44, 45, 46, 47, 48, 49, 410, 411, 412, 413, 414])
                                    ->sum('quantity');
                                $quantitySum += $betInfo->Quantity;*/
                if ($betInfo->Quantity < $roomRuleConf[84]['limit_min'] or $betInfo->Quantity > $roomRuleConf[84]['limit_max']) {
                    return [false, "下注失败 注单积分不在限制范围内"];
                }
                break;
        }
        return [true, ''];
    }

    /**
     * 单笔订单算账
     * @param array $order
     * @param array $issue
     * @return array
     */
    public static function  orderOddsSettle(array $order, array $issue): array
    {

//        31 => '豹子',
//        32 => '顺子',
//        33 => '对子',
//        34 => '豹回本',
//        35 => '顺回本',
//        36 => '对回本',
//        37 => '视（0,8,9）（0,1,9）为顺',

        $order['lottery_code'] = $issue['site_1'] . ' ' . $issue['site_2'] . ' ' . $issue['site_3'] . ' ' . $issue['code'] . ' ' . $issue['mark'];
        $order['status'] = 1;
        switch ($order['play_type']) {
            case 1:
            case 2:
            case 3:
            case 4:
                if (!isset(array_flip(explode(' ', $order['play_code']))[$issue['code']])) {
                    $order['whether_hit'] = 0;
                    break;
                }
                $order['whether_hit'] = 1;
                break;
            default:
                return [false, '订单处理错误'];
        }

        if ($order['whether_hit'] == 1 and $order['odds'] != 0)
            $order['profit'] = bcmul($order['odds'], $order['quantity'], 2);
        return [true, $order];
    }

    /**
     * 是否需要计算 0 9回本
     */
    private function whetherHitSpecial(Issue $issue): bool
    {
        if (($issue->site_1 == 0 or $issue->site_2 == 0 or $issue->site_3 == 0)) {
            return true;
        }
        if (($issue->site_1 == 9 or $issue->site_2 == 9 or $issue->site_3 == 9)) {
            return true;
        }
        return false;
    }

    /**
     * 判断当前开奖是否为指定的结果
     * @param \App\Model\Issue $issue
     * @param $class
     * @return bool
     */
    private function _isIssueBaseClass(Issue $issue, $class): bool
    {
        if (!isset(explode(' ', IdentifyDanDan::NUM_CODE[IdentifyDanDan::PAY_CLASS[$class]])[$issue->code])) {
            return false;
        }
        return true;
    }

    /**
     * 计算出当期的豹顺对
     * @param \app\admin\model\Issue $issue
     * @return \app\admin\model\Issue
     */
    public function lotterySpecial(Issue $issue): Issue
    {   //废弃方法 不用计算
        //$roomRuleConfig = $this->getRules($roomId);
        if ($issue->site_2 == $issue->site_1 or $issue->site_2 == $issue->site_3 or $issue->site_1 == $issue->site_3) {
            $issue->mark = self::RULE_CLASS[33];
        }
        if ($issue->site_3 - $issue->site_1 == 2 and $issue->site_3 - $issue->site_2 == 1) {
            $issue->mark = self::RULE_CLASS[32];
        }/* elseif ($roomRuleConfig[37]['status'] == 1) {
            if ($issue->site_1 == 0 and ($issue->site_2 == 8 or $issue->site_2 == 1) and $issue->site_3 == 9) {
                $issue->mark = self::RULE_CLASS[32];
            }
        }*/
        if ($issue->site_1 == $issue->site_2 and $issue->site_1 == $issue->site_3) {
            $issue->mark = self::RULE_CLASS[31];
        }
        return $issue;
    }

    /**
     * 计算出大小单双
     */
    public static function lotteryBase(array $Issue): array
    {
        $result = [];
        if (isset(array_flip(explode(' ', IdentifyDanDan::NUM_CODE['高']))[$Issue['code']])) {
            $result[] = '高';
        } else {
            $result[] = '低';
        }
        if (isset(array_flip(explode(' ', IdentifyDanDan::NUM_CODE['多']))[$Issue['code']])) {
            $result[] = '多';
        } else {
            $result[] = '空';
        }
        return $result;
    }

    static public function IsClose(string $issue): bool
    {
        $carbon = Carbon::now('Asia/Shanghai');
        $tempIssue = (int)$carbon->rawFormat("YmdHi");
//        if ((int)$issue < $tempIssue) { //传入的期号必须大于等于 当前时间 才可能是当前期号 or (int)$issue - $tempIssue > 4
//            return true;
//        }
//        if ((int)$issue == $tempIssue and (int)$carbon->rawFormat('s') <= 30) return true;
        if ((int)$issue <= $tempIssue) return true;
        return false;
    }

    static public function nowIssue(): array
    {
        $carbon = Carbon::now('Asia/Shanghai');
        $tempIssue = (int)$carbon->rawFormat("YmdHi");
        $sur = $carbon->rawFormat('i') % 3;
        $copyCarbon = clone $carbon;
        if ($sur == 0 and $carbon->rawFormat('s') < 30){
            $result['openTime'] = $carbon->rawFormat('Y-m-d H:i:30');
            $carbon->setSecond(30);
            $result['openTime1'] = $carbon->diffInRealSeconds(Carbon::now());
            $result['issue'] = $tempIssue;
            $result['last_issue'] = (int)$copyCarbon->subMinutes(1)->rawFormat("YmdHi");
            $result['a'] = 1;
            return $result;
        }elseif ($sur == 0 and $carbon->rawFormat('s') >= 30){
            $carbon->addMinutes(3);
            $result['openTime'] = $carbon->rawFormat('Y-m-d H:i:30');
            $carbon->setSecond(30);
            $result['openTime1'] = $carbon->diffInRealSeconds(Carbon::now());
            $result['issue'] = (int)$copyCarbon->addMinutes(1)->rawFormat("YmdHi");
            $result['last_issue'] = (int)$copyCarbon->subMinutes(1)->rawFormat("YmdHi");
            $result['a'] = 2;
            return $result;
        }else{
            $temp = 3 - $sur;
            $carbon->addMinutes($temp);
            $copyCarbon->addMinutes($temp);
            $result['openTime'] = $carbon->rawFormat('Y-m-d H:i:30');
            $carbon->setSecond(30);
            $result['openTime1'] = $carbon->diffInRealSeconds(Carbon::now());
            $result['issue'] = (int)$carbon->rawFormat("YmdHi");
            $result['last_issue'] = (int)$copyCarbon->subMinutes(1)->rawFormat("YmdHi");;
            $result['a'] = 3;
            return $result;
        }

    }

    public static function makeRebootOrder($roomList)
    {
        // 生成假的订单
//        $roomList = Db::name('game_plan')->where('status',1)->select();
//        $gameMap = array_column($gameList,'name','id');
        $Issue =GameRuleService::nowIssue();
        if(GameRuleService::IsClose($Issue['issue'])){
            return;
        };
        $tempIssue = $Issue['issue'];
        array_walk($roomList,function ($room)use ($tempIssue){
            $lastMessage = new LastMessage();
            $len = $lastMessage->len(1,$room['id']);
            $len = 0;
//            if ($len >= 20)
//                return;
            for (;$len<=5;$len++){
                $message['issue'] = $tempIssue;
                $message['user_id']= 0;
                $message['id']= time();
                $rand = mt_rand(0, 3);
                $message['nickname'] = randCnStr(2,mt_rand(0,2))."***";
                $message['play_method'] = array_values(IdentifyDanDan::PAY_CLASS)[$rand];;
                $message['play_type'] = array_keys(IdentifyDanDan::PAY_CLASS)[$rand];
                $message['play_code'] = IdentifyDanDan::NUM_CODE[$message['play_method']];
                $quantity = mt_rand($room['min_limit'], 1000);
//                $quantity_arr = str_split($quantity);
//                $resQuantity = 0;
//                array_walk($quantity_arr,function ($num,$key) use ($quantity_arr,&$resQuantity){
//                    if ($key == 0){
//                        $resQuantity = $num;
//                        return;
//                    }elseif ($key == 1 and count($quantity_arr) > 2 and mt_rand(0,1) == 1){
//                        $resQuantity.=$num;
//                        return;
//                    }
//
//                    $resQuantity.='0';
//                });
                $message['quantity'] = $quantity.'0';
                $message['after_quantity'] = 0;
                $lastMessage->add(1,$room['id'],$message);
            }

        });
    }

    public static function makeRebootProfit($gameList)
    {
        $Issue =GameRuleService::nowIssue();
//        if(!GameRuleService::IsClose($Issue['issue'])){
//            return;
//        };
        $tempIssue = $Issue['last_issue'];
        $lastMessage = new LastMessage();
        $lastMessage->del(0,0);
        array_walk($gameList,function ($game)use ($tempIssue,$lastMessage){

//           $len = $lastMessage->len(0,0);
            $len = 0;
//           if ($len >= 20)
//               return;
            for (;$len<=mt_rand(2,5);$len++){
                $message['nickname'] = randCnStr(2,mt_rand(0,2))."***";
               // $quantity = mt_rand(100, 90000);

                $minAmount = 50;
                $maxAmount = 90000;
                $decimalPlaces = 2; // 小数位数
                $randomAmount = $minAmount + mt_rand() / mt_getrandmax() * ($maxAmount - $minAmount);
                $formattedAmount = number_format($randomAmount, $decimalPlaces);

                // $quantity_arr = str_split($quantity);
//                $resQuantity = 0;
//                array_walk($quantity_arr,function ($num,$key) use ($quantity_arr,&$resQuantity){
//                    if ($key == 0){
//                        $resQuantity = $num;
//                        return;
//                    }elseif ($key == 1 and count($quantity_arr) > 2 and mt_rand(0,1) == 1){
//                        $resQuantity.=$num;
//                        return;
//                    }
//
//                    $resQuantity.='0';
//                });
                $message['id'] = time();
                $message['profit'] = $formattedAmount;;
                $message['user_id']= 0;
                $message['game_name'] = $game['name'];
                $message['game_id'] = $game['id'];
                $message['order_id'] = 0;
                $message['issue'] = $tempIssue;
                $lastMessage->add(0,0,$message);
            }

        });
    }
}
























