<?php

namespace app\admin\controller\kefu;

use app\common\controller\Backend;
use think\Db;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * 用户留言记录
 *
 * @icon fa fa-circle-o
 */
class Leavemessage extends Backend
{

    /**
     * KeFuLeaveMessage模型对象
     * @var \app\admin\model\KeFuLeaveMessage
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\KeFuLeaveMessage;

    }

    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model->with(['kefuuser'])->where($where)->order($sort, $order)->count();

            $list = $this->model->with(['kefuuser'])
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            foreach ($list as $row) {
                // 查询关联用户的昵称
                if ($row->kefuuser->user_id) {
                    $row->fu_user_nickname = \think\Db::name('user')
                        ->where('id', $row->kefuuser->user_id)
                        ->value('nickname');
                }

            }
            $list   = collection($list)->toArray();
            $result = ["total" => $total, "rows" => $list];

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                $params = $this->preExcludeFields($params);
                $result = false;
                Db::startTrans();
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name     = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                        $row->validateFailException(true)->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
                    Db::commit();
                } catch (ValidateException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (PDOException $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                } catch (Exception $e) {
                    Db::rollback();
                    $this->error($e->getMessage());
                }
                if ($result !== false) {
                    $this->success();
                } else {
                    $this->error(__('No rows were updated'));
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }

        // 获取用户访问轨迹
        $trajectory = Db::name('kefu_trajectory')
            ->where('user_id', $row->user_id)
            ->where('log_type', 0)
            ->order('createtime desc')
            ->select();
        foreach ($trajectory as $index => $item) {
            $trajectory[$index]['createtime'] = date('Y-m-d H:i:s', $trajectory[$index]['createtime']);
        }

        $this->view->assign("row", $row);
        $this->view->assign("trajectory", $trajectory);
        return $this->view->fetch();
    }
}
