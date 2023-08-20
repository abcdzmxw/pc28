<?php

namespace app\admin\controller\user;

use app\common\controller\Backend;
use app\common\library\Auth;
use think\Db;

/**
 * 会员管理
 *
 * @icon fa fa-user
 */
class User extends Backend
{

    protected $relationSearch = true;
    protected $searchFields = 'id,username,nickname';
    protected $dataLimit = 'auth'; //默认基类中为false，表示不启用，可额外使用auth和personal两个值
    protected $dataLimitField = 'admin_id'; //数据关联字段,当前控制器对应的模型表中必须存在该字段

    /**
     * @var \app\admin\model\User
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = model('User');
    }

    /**
     * 查看
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            [$where, $sort, $order, $offset, $limit] = $this->buildparams();
            $list = $this->model
                ->with('group')
                ->where($where)
                ->order($sort, $order)
                ->paginate($limit);
            foreach ($list as $k => $v) {
                $v->avatar = $v->avatar ? cdnurl($v->avatar, true) : letter_avatar($v->nickname);
                $v->hidden(['password', 'salt']);
            }
            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {

        if ($this->request->isPost()) {
            $row = $this->request->param();

            if ($this->auth->id != 1 and $row['row']['group_id'] != 3)
                $this->error('無權限添加會員');
            $this->token();
        }
        return parent::add();
    }



    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        if ($this->request->isPost()) {
            $this->token();
        }
        $row = $this->model->get($ids);
        $this->modelValidate = true;
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $this->view->assign('groupList', build_select('row[group_id]', \app\admin\model\UserGroup::column('id,name'), $row['group_id'], ['class' => 'form-control selectpicker']));
        return parent::edit($ids);
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if (!$this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        $ids = $ids ? $ids : $this->request->post("ids");
        $row = $this->model->get($ids);
        $this->modelValidate = true;
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        Auth::instance()->delete($row['id']);
        $this->success();
    }

    public function give()
    {
        if($this->request->isAjax()){
            $params =$this->request->param()['row'];
            $params['user_id'] = $params['id'];
            unset($params['id']);
            $params['createtime'] = time();
            $params['updatetime'] = time();
            $params['status'] ='1';
            $params['type'] = '4';
            $params['admin_id'] = Db::name('user')->where('id',$params['user_id'])->value('admin_id');
            Db::startTrans();
            try {
                $score = Db::name('user')->where('id',$params['user_id'])->value('score');
                $params['over'] = bcadd($score,$params['quantity'],2);
                $id= Db::name('quantity_log')->insertGetId($params);
                if (!$id){
                    throw new \Exception('处理失败');
                }
                $isTrue = Db::name('user')->where('id',$params['user_id'])->where('score',$score)->inc('score',$params['quantity'])->update();
                if (!$isTrue){
                    throw new \Exception('处理失败');
                }
                Db::commit();
            }catch (\Throwable $e){
                $this->error($e->getMessage());
            }
            $this->success('处理成功');
        }

        return $this->view->fetch();
    }

    public function addpoints()
    {
        if($this->request->isAjax()){
            $params =$this->request->param()['row'];
            $params['user_id'] = $params['id'];
            unset($params['id']);
            $params['createtime'] = time();
            $params['updatetime'] = time();
            $params['status'] ='1';
            $params['type'] = '0';
            $params['admin_id'] = Db::name('user')->where('id',$params['user_id'])->value('admin_id');
            Db::startTrans();
            try {
                $money = Db::name('user')->where('id',$params['user_id'])->value('money');
                $params['over'] = bcadd($money,$params['quantity'],2);
                $id= Db::name('quantity_log')->insertGetId($params);
                if (!$id){
                    throw new \Exception('处理失败');
                }
                $isTrue = Db::name('user')->where('id',$params['user_id'])->where('money',$money)->inc('money',$params['quantity'])->update();
                if (!$isTrue){
                    throw new \Exception('处理失败');
                }
                Db::commit();
            }catch (\Throwable $e){
                $this->error($e->getMessage());
            }
            $this->success('处理成功');
        }
        return $this->view->fetch();
    }
}
