<?php

namespace app\admin\controller\kefu;

use app\common\controller\Backend;
use addons\kefu\library\StrComparison;

/**
 * 知识库管理
 *
 * @icon fa fa-circle-o
 */
class Kbs extends Backend
{

    /**
     * KeFuKbs模型对象
     * @var \app\admin\model\KeFuKbs
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\KeFuKbs;
        $this->view->assign("statusList", $this->model->getStatusList());
    }

    public function testMatch()
    {
        if ($this->request->isPost()) {
            $params = $this->request->only(['str1', 'str2']);
            if ($params['str1'] && $params['str2']) {
                $StrComparison = new StrComparison;
                $match         = $StrComparison->getSimilar($params['str1'], $params['str2']);
                $this->success('ok', null, $match);
            } else {
                $this->error('', null, 0);
            }
        }
        return $this->view->fetch();
    }

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        // $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model->where($where)->order($sort, $order)->count();

            $list = $this->model->where($where)->order($sort, $order)->limit($offset, $limit)->select();

            /*foreach ($list as $row) {

                $row->getRelation('admin')->visible(['nickname']);
            }*/
            $list   = collection($list)->toArray();
            $result = ["total" => $total, "rows" => $list];

            return json($result);
        }
        return $this->view->fetch();
    }
}
