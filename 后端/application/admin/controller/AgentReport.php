<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use think\Db;

class agentReport extends Backend
{
    /**
     * Game模型对象
     * @var \app\admin\model\Game
     */
    protected $model = null;
    protected $searchFields = 'id,name,title';
    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Game;
        $this->view->assign("statusList", $this->model->getStatusList());
    }

    public function import()
    {
        parent::import();
    }

    public function index()
    {
        if ($this->request->isAjax()){
//            $result['quantity'];
            $flitter = json_decode($this->request->param('filter'),true);
            if (!empty($flitter['id'])){
                $staffs = Db::name('user')->where([
                    'admin_id'=>$this->auth->id,
                    'inviter_id'=>(int)$flitter['id'],
                    'group_id'=>1
                ])->field(['id','username'])->select();
            }else{
                $staffs = Db::name('user')->where([
                    'admin_id'=>$this->auth->id,
                    'group_id'=>3
                ])->field(['id','username'])->select();
            }
            $allInfo  =[];
            $allInfo['in'] = 0;
            $allInfo['up'] = 0;
            $allInfo['use'] = 0;
            $allInfo['hit'] = 0;
            $allInfo['weigh'] = 0;
            $allInfo['profit'] = 0;
            array_walk($staffs,function (&$item)use ($flitter,&$allInfo){
                if (!empty($flitter['id'])){
                    $userIds = $item['id'];
                    $item['money'] = Db::name('user')->where(['id'=>$item['id'],'group_id'=>1])->where('inviter_id',$flitter['id'])->sum('money');
                    $item['score'] = Db::name('user')->where(['id'=>$item['id'],'group_id'=>1])->where('inviter_id',$flitter['id'])->sum('score');
                }else{
                    $userIds = Db::name('user')->where('inviter_id',$item['id'])->group('id')->column('id');
                    $item['money'] = Db::name('user')->where(['group_id'=>1])->where('inviter_id',$item['id'])->sum('money');
                    $item['score'] = Db::name('user')->where(['group_id'=>1])->where('inviter_id',$item['id'])->sum('score');
                }

                $date[0] = 945970345;
                $date[1] =253401586345;
                if (!empty($flitter['createtime'])){
                    $date = explode(' - ',$flitter['createtime']);
                    $date[0] = strtotime($date[0]);
                    $date[1] = strtotime($date[1]);
                }
                $reports = Db::name('quantity_log')
                    ->whereBetween("createtime",[$date[0],$date[1]])
                    ->whereNull('deletetime')
                    ->whereIn('user_id',$userIds)
                    ->where('status',1)
                    ->group('type')
                    ->field(["type,sum(quantity) as sums"])->select();
                $item['in'] = 0;
                $item['up'] = 0;
                $item['use'] = 0;
                $item['hit'] = 0;
                $item['weigh'] = 0;
                foreach ($reports as $rep){
                    //类型:0=充值,1=提现,2=下单,3=中奖,,4=赠送
                    switch ($rep['type']){
                        case 0:
                            $item['in'] = $rep['sums'];
                            $allInfo['in'] += $item['in'];
                            break;
                        case 1:
                            $item['up'] = $rep['sums'];
                            $allInfo['up'] += $item['up'];
                            break;
                        case 2:
                            $item['use'] = $rep['sums'];
                            $allInfo['use'] += $item['use'];
                            break;
                        case 3:
                            $item['hit'] = $rep['sums'];
                            $allInfo['hit'] += $item['hit'];
                            break;
                        case 4:
                            $item['weigh'] = $rep['sums'];
                            $allInfo['weigh'] += $item['weigh'];
                    }
                }
                $item['profit'] = $item['hit'] - $item['use'];
                $allInfo['profit'] += $item['profit'];
            });
            $result['rows'] =$staffs;
            $result['total'] = 1;
            $result['allInfo'] = $allInfo;
            return json($result);
        }

        return parent::index();
    }

    public function detail(array $filter)
    {
        $dateArr = explode(' - ',$filter['createtime']);
        $id = (int)$filter['id'];



    }


}