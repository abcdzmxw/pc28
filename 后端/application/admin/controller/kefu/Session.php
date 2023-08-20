<?php

namespace app\admin\controller\kefu;

use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * KeFu会话管理
 *
 * @icon fa fa-circle-o
 */
class Session extends Backend
{

    /**
     * KeFuSession模型对象
     * @var \app\admin\model\KeFuSession
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\KeFuSession;

    }

    /**
     * 删除-同时删除该会话的聊天记录
     */
    public function del($ids = "")
    {
        if ($ids) {
            $pk       = $this->model->getPk();
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $list = $this->model->where($pk, 'in', $ids)->select();

            $count = 0;
            Db::startTrans();
            try {
                foreach ($list as $k => $v) {
                    $count += $res = $v->delete();

                    if ($res) {
                        Db::name('kefu_record')->where('session_id', $v->id)->delete();
                    }
                }
                Db::commit();
            } catch (PDOException $e) {
                Db::rollback();
                $this->error($e->getMessage());
            } catch (Exception $e) {
                Db::rollback();
                $this->error($e->getMessage());
            }
            if ($count) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
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
            $total = $this->model->with(['kefuuser', 'csr'])->where($where)->order($sort, $order)->count();

            $list = $this->model->with(['kefuuser', 'csr'])
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
}
