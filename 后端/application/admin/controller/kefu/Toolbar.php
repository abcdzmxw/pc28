<?php

namespace app\admin\controller\kefu;

use app\common\controller\Backend;

/**
 * 窗口工具栏管理
 *
 * @icon fa fa-circle-o
 */
class Toolbar extends Backend
{

    /**
     * KefuToolbar模型对象
     * @var \app\admin\model\KefuToolbar
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\KefuToolbar;
        $this->view->assign("statusList", $this->model->getStatusList());
        $this->view->assign("PositionList", $this->model->getPositionList());
    }

}
