<?php

namespace app\admin\controller\kefu;

use app\common\controller\Backend;
use think\Db;
use think\Exception;
use think\exception\PDOException;
use think\exception\ValidateException;

/**
 * KeFu消息记录管理
 *
 * @icon fa fa-circle-o
 */
class Record extends Backend
{

    /**
     * KeFuRecord模型对象
     * @var \app\admin\model\KeFuRecord
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\KeFuRecord;
        $this->view->assign("senderIdentityList", $this->model->getSenderIdentityList());
        $this->view->assign("messageTypeList", $this->model->getMessageTypeList());
        $this->view->assign("statusList", $this->model->getStatusList());
        $toolbar      = [];
        $toolbar_temp = Db::name('kefu_toolbar')
            ->field('mark,card_url')
            ->whereIn('mark', 'order,goods')
            ->where('deletetime', null)
            ->select();
        // 以mark为键
        foreach ($toolbar_temp as $key => $value) {
            $toolbar[$value['mark']] = $value;
        }
        $this->assignconfig('toolbar', $toolbar);
    }

    /**
     * 会话聊天记录
     */
    public function sessionRecord()
    {
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

            $list = collection($list)->toArray();

            foreach ($list as $key => $value) {
                $list[$key]['message'] = htmlspecialchars_decode($value['message']);

                if ($list[$key]['sender_identity'] == 0) {

                    $list[$key]['sender_id'] = Db::name('admin')
                        ->where('id', $list[$key]['sender_id'])
                        ->value('nickname');
                } else {

                    $sender = Db::name('kefu_user')
                        ->alias('u')
                        ->field('u.nickname,fu.nickname as fu_nickname')
                        ->join('user fu', 'u.user_id=fu.id', 'LEFT')
                        ->where('u.id', $list[$key]['sender_id'])
                        ->find();

                    $list[$key]['sender_id'] = $sender['fu_nickname'] ? $sender['fu_nickname'] : $sender['nickname'];
                }
            }

            $result = ["total" => $total, "rows" => $list];
            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 查看
     */
    public function index()
    {
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

            $list = collection($list)->toArray();

            foreach ($list as $key => $value) {
                $list[$key]['message'] = htmlspecialchars_decode($value['message']);

                // 把消息中的图片替换掉
                $list[$key]['message'] = preg_replace("/<img (.*?)\">/", "[图片]", $list[$key]['message']);

                if ($list[$key]['sender_identity'] == 0) {

                    $list[$key]['sender_id'] = Db::name('admin')
                        ->where('id', $list[$key]['sender_id'])
                        ->value('nickname');
                } else {

                    $sender = Db::name('kefu_user')
                        ->alias('u')
                        ->field('u.nickname,fu.nickname as fu_nickname')
                        ->join('user fu', 'u.user_id=fu.id', 'LEFT')
                        ->where('u.id', $list[$key]['sender_id'])
                        ->find();

                    $list[$key]['sender_id'] = $sender['fu_nickname'] ? $sender['fu_nickname'] : $sender['nickname'];
                }
            }

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
                $params = method_exists($this, 'preExcludeFields') ? $this->preExcludeFields($params) : $params;
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

        if ($row->message_type == 1) {
            $row->message = '<img height="200" src="' . $row->message . '" />';
        }

        if ($row->message_type == 0 || $row->message_type == 3) {
            $row->message = htmlspecialchars_decode($row->message);
        }

        $this->view->assign("row", $row);
        return $this->view->fetch();
    }
}
