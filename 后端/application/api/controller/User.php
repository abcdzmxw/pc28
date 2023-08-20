<?php

namespace app\api\controller;

use app\common\controller\Api;
use app\common\library\Ems;
use app\common\library\Sms;
use fast\Random;
use think\Config;
use think\console\command\Help;
use think\Db;
use think\Validate;
use function Composer\Autoload\includeFile;
use function GuzzleHttp\Psr7\str;
use function GuzzleHttp\Psr7\uri_for;
use function Symfony\Component\Translation\t;

/**
 * 会员接口
 */
class User extends Api
{
    protected array $noNeedLogin = ['login', 'mobilelogin', 'register', 'resetpwd', 'changeemail', 'changemobile', 'third'];
    protected array $noNeedRight = ['*'];

    public function _initialize()
    {
        parent::_initialize();

        if (!Config::get('fastadmin.usercenter')) {
            $this->error(__('User center already closed'));
        }

    }

//    /**
//     * 会员中心
//     */
//    public function index()
//    {
//        $this->success('', ['welcome' => $this->auth->nickname]);
//    }

    /**
     * 会员登录
     *
     * @ApiMethod (POST)
     * @throws \think\exception\DbException
     */
    public function login()
    {
        $account = $this->request->post('account');
        $password = $this->request->post('password');
        if (!$account || !$password) {
            $this->error(__('Invalid parameters'));
        }
        $ret = $this->auth->login($account, $password);
        if ($ret) {
            $data = ['userinfo' => $this->auth->getUserinfo()];
            $this->success(__('Logged in successful'), $data);
        } else {
            $this->error($this->auth->getError());
        }
    }

    public function userInfo()
    {
        $this->success($this->auth->getUserinfo());
    }
//      /**
//     * 手机验证码登录
//     *
//     * @ApiMethod (POST)
//     * @param string $mobile  手机号
//     * @param string $captcha 验证码
//     */
//    public function mobilelogin()
//    {
//        $mobile = $this->request->post('mobile');
//        $captcha = $this->request->post('captcha');
//        if (!$mobile || !$captcha) {
//            $this->error(__('Invalid parameters'));
//        }
//        if (!Validate::regex($mobile, "^1\d{10}$")) {
//            $this->error(__('Mobile is incorrect'));
//        }
//        if (!Sms::check($mobile, $captcha, 'mobilelogin')) {
//            $this->error(__('Captcha is incorrect'));
//        }
//        $user = \app\common\model\User::getByMobile($mobile);
//        if ($user) {
//            if ($user->status != 'normal') {
//                $this->error(__('Account is locked'));
//            }
//            //如果已经有账号则直接登录
//            $ret = $this->auth->direct($user->id);
//        } else {
//            $ret = $this->auth->register($mobile, Random::alnum(), '', $mobile, []);
//        }
//        if ($ret) {
//            Sms::flush($mobile, 'mobilelogin');
//            $data = ['userinfo' => $this->auth->getUserinfo()];
//            $this->success(__('Logged in successful'), $data);
//        } else {
//            $this->error($this->auth->getError());
//        }
//    }

    /**
     * 注册会员
     *
     * @ApiMethod (POST)
     */
    public function register()
    {
        $username = $this->request->param('username');
        $password = $this->request->param('password');
        $email = $this->request->param('email');
        $mobile = $this->request->param('mobile');
        $code = $this->request->param('code');
        $Group = $this->request->param('group_id',1);
        $inviter_id = $this->request->param('inviter_id',1);
        if (!$username || !$password) {
            $this->error(__('Invalid parameters'));
        }
//        if ($email && !Validate::is($email, "email")) {
//            $this->error(__('Email is incorrect'));
//        }
//        if ($mobile && !Validate::regex($mobile, "^1\d{10}$")) {
//            $this->error(__('Mobile is incorrect'));
//        }
//        $ret = Sms::check($mobile, $code, 'register');
//        if (!$ret) {
//            $this->error(__('Captcha is incorrect'));
//        }
        if ($Group != 1 and $Group != 2)
            $this->error('group err');
        $extend = ['admin_id'=>$this->agentId,'group_id'=>$Group,'inviter_id' =>$inviter_id];
        $ret = $this->auth->register($username, $password, $email, $mobile, $extend);
        if ($ret) {
            $data = ['userinfo' => $this->auth->getUserinfo()];
            $this->success(__('Sign up successful'), $data);
        } else {
            $this->error($this->auth->getError());
        }
    }

    /**
     * 退出登录
     * @ApiMethod (POST)
     */
    public function logout()
    {
        if (!$this->request->isPost()) {
            $this->error(__('Invalid parameters'));
        }
        $this->auth->logout();
        $this->success(__('Logout successful'));
    }

    /**
     * 修改会员个人信息
     *
     * @ApiMethod (POST)
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function profile()
    {
        $user = $this->auth->getUser();
        $username = $this->request->post('username');
        $nickname = $this->request->post('nickname');
        $idcard = $this->request->post('idcard');
        $bio = $this->request->post('bio');
        $avatar = $this->request->post('avatar', '', 'trim,strip_tags,htmlspecialchars');
//        if ($username) {
//            $exists = \app\common\model\User::where('username', $username)->where('id', '<>', $this->auth->id)->find();
//            if ($exists) {
//                $this->error(__('Username already exists'));
//            }
//            $user->username = $username;
//        }
//        if ($nickname) {
//            $exists = \app\common\model\User::where('nickname', $nickname)->where('id', '<>', $this->auth->id)->find();
//            if ($exists) {
//                $this->error(__('Nickname already exists'));
//            }
//            $user->nickname = $nickname;
//        }
//        $user->bio = $bio;
//        $user->avatar = $avatar;
        $user->idcard = $idcard;
        $user->save();
        $this->success();
    }

    /**
     * 银行卡 or usdt 收款信息
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function payInfo()
    {
        $payType = (int)$this->request->param('type');
        $result = Db::name('user_pay')->where('user_id',$this->auth->id)->where('status',$payType)->find();
        $this->success('',$result);
    }

    /**
     * 更新or 添加收款信息
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function setPayInfo()
    {
        $data['acc'] = $this->request->param('acc');
        $data['user_id'] = $this->auth->id;
        $data['acc_name'] = $this->request->param('acc_name');
        $data['acc_bank'] = $this->request->param('acc_bank');
        $data['mark'] = $this->request->param('mark');
        $data['status'] = (int)$this->request->param('type');
        $data['updatetime'] = time();
        $data['createtime'] = time();
        $isTrue = Db::name('user_pay')->where('user_id',$this->auth->id)->where('status',$data['status'])->value('id');
        if ($isTrue){
            unset($data['createtime']);
            Db::name('user_pay')->where('user_id',$this->auth->id)->where('status',$data['status'])->update($data);
        }else{
            Db::name('user_pay')->insert($data);
        }
        $this->success('更新成功');
    }

    public function safetyInfo()
    {
        $result['isTrue'] =  (bool)Db::name('user')->where('id',$this->auth->id)->value('pass_code');
        $this->success('',$result);
    }

    /**
     * 修改安全碼
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function setSafety()
    {
        $newCode = $this->request->param('new_code');
        $lastCode = $this->request->param('after_code');
        if ($newCode == $lastCode ){
            $this->error(__('新安全碼不能相同'));
        }
        if (strlen($newCode) !=6)
            $this->error(__('長度為六位'));
        $code = Db::name('user')->where('id',$this->auth->id)->value('pass_code');
        if ($code !== $lastCode and $code !=''){
            $this->error('修改失敗 密碼錯誤');
        }
        Db::name('user')->where('id',$this->auth->id)->update(['pass_code'=>$newCode]);
        $this->success('修改成功');
    }

    /**
     * 修改密碼
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function newPass()
    {
        $newCode = $this->request->param('new_pass');
        $lastCode = $this->request->param('after_pass');
        if ($newCode == $lastCode ){
            $this->error(__('新密碼不能相同'));
        }
        if (strlen($newCode) >= 20)
            $this->error(__('最長為20位'));
        $data = Db::name('user')->where('id',$this->auth->id)->field(['password','salt'])->find();
        $code = $this->auth->getEncryptPassword($lastCode, $data['salt']);
        if ($code !== $data['password']){
            $this->error('修改失敗 密碼錯誤');
        }
        Db::name('user')->where('id',$this->auth->id)->update(['password'=>$this->auth->getEncryptPassword($newCode, $data['salt'])]);
        $this->success('修改成功');

    }

    /**
     * 注单列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function orderList()
    {
        $checkDate = strtotime($this->request->param('begin_date'));;
        $endDate = strtotime($this->request->param('end_date'))+86400000;
        $whether_hit = $this->request->param('whether_hit',false);
        $map = [];
        if (!($whether_hit === false))
            $map = ['o.whether_hit'=>$whether_hit];
        $result = Db::name('order o')->where('user_id',$this->auth->id)
            ->join('game g','o.game_id = g.id')
            ->join('issue i','o.issue_id = i.id')
            ->field(['o.id','o.orderNo','o.quantity','o.whether_hit','o.plan_name','o.play_method','o.play_code','o.lottery_code','o.status','o.profit','g.name','i.issue','o.createtime'])
            ->whereBetween('o.createtime',[$checkDate,$endDate])
            ->whereNull('o.deletetime')
            ->whereNull('i.deletetime')
            ->order('o.id','desc')
            ->where($map)
//            ->limit(500)
            ->select();
        $this->success('',$result);
    }

    /**
     * 积分日志
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function quantityLogList()
    {
        $checkDate = strtotime($this->request->param('begin_date'));;
        $endDate = strtotime($this->request->param('end_date'))+86400000;
        $type = $this->request->param('type',false);
        $map = [];
        if (!($type === false)){
            $map = ['type'=>$type];
        }

        $result = Db::name('quantity_log')
            ->where('user_id',$this->auth->id)
            ->whereBetween('createtime',[$checkDate,$endDate])
            ->whereNull('deletetime')
            ->where($map)
            ->order('id','desc')
            ->field(['id','quantity','over','mark','type','status','createtime'])
//            ->limit(500)
            ->select();
        $this->success('',$result);
    }

    public function withdrawInfo()
    {
        $groupId = Db::name('user')->where('id',$this->auth->id)->value('group_id');
        if ($groupId != 1){
            $this->error('游客賬號無權限 請先注冊為正式賬號');
        }
        $result['money'] = Db::name('user')->where('id',$this->auth->id)->value('money');
        $result['pay_list'] = Db::name('user_pay')->where('user_id',$this->auth->id)->select();
        $this->success('',$result);
    }

    public function withdrawAction()
    {
        $type = $this->request->param('type');
        $money = $this->request->param('money');
        $groupId = Db::name('user')->where('id',$this->auth->id)->value('group_id');
        if ($groupId != 1){
            $this->error('游客賬號無權限 請先注冊為正式賬號');
        }
        $userMoney = Db::name('user')->where('id',$this->auth->id)->value('money');
        $userScore = Db::name('user')->where('id',$this->auth->id)->value('score');
        $isTrue = Db::name('config')->where('id',23)->value('value');
        $water_limit = Db::name('config')->where('id',22)->value('value');
        if ($userScore > 0  and  $isTrue){
            $this->error('彩金必須使用完畢');
        }
        $orderQuantity = Db::name('quantity_log')->where(['user_id'=>$this->auth->id,'type'=>2])->sum('quantity');
        $windowQuantity = Db::name('quantity_log')->where(['user_id'=>$this->auth->id,'type'=>0])->sum('quantity');
        if ( -$orderQuantity < bcmul($windowQuantity,$water_limit,2))
            $this->error('流水必須達到 '.bcmul($windowQuantity,$water_limit,2));
        $isTrue = Db::name('quantity_log')->where(['user_id'=>$this->auth->id,'type'=>1,'status'=>0])->value('id');
        if ($isTrue){
            $this->error('有未處理的請求 請聯係客服');
        }
        $payInfo = Db::name('user_pay')->where('user_id',$this->auth->id)->where('status',$type)->field(['acc','acc_name','acc_bank','mark'])->find();
        if (empty($payInfo))
            $this->error('體現信息錯誤');
        $data['mark'] = implode(' ',array_values($payInfo));
        $data['user_id'] = $this->auth->id;
        $data['admin_id'] = $this->agentId;
        $data['quantity'] = -$money;
        $data['over'] = bcsub($userMoney,$money,2);
        $data['type'] = '1';
        $data['status'] = '0';
        $data['createtime'] = time();
        $data['updatetime'] = time();
        Db::startTrans();
        try {
            $isTrue = Db::name('user')->where('id',$this->auth->id)->where('money',$userMoney)
                ->dec('money',$money)->update();
            if (!$isTrue)
                throw new \Exception('體現失敗');
            $isTrue = Db::name('quantity_log')->insert($data);
            if (!$isTrue)
                throw new \Exception('體現失敗');
            Db::commit();
        }catch (\Throwable $e){
            Db::rollback();
            $this->error($e->getMessage());
        }
        $this->success('已提交請等待客服處理');
    }

    /**
     * 充值
     */
    public function recharge()
    {
        $groupId = Db::name('user')->where('id',$this->auth->id)->value('group_id');
        if ($groupId != 1){
            $this->error('游客賬號無權限 請先注冊為正式賬號');
        }
        $result['money'] = Db::name('user')->where('id',$this->auth->id)->value('money');
        $result['usdt'] = Db::name('config')->where('name','usdt')->value('value');
        $result['bankname'] = Db::name('config')->where('name','bankname')->value('value');
        $result['bankxm'] = Db::name('config')->where('name','bankxm')->value('value');
        $result['banknum'] = Db::name('config')->where('name','banknum')->value('value');
        $this->success('',$result);
    }

//    /**
//     * 修改邮箱
//     *
//     * @ApiMethod (POST)
//     * @param string $email   邮箱
//     * @param string $captcha 验证码
//     */
//    public function changeemail()
//    {
//        $user = $this->auth->getUser();
//        $email = $this->request->post('email');
//        $captcha = $this->request->post('captcha');
//        if (!$email || !$captcha) {
//            $this->error(__('Invalid parameters'));
//        }
//        if (!Validate::is($email, "email")) {
//            $this->error(__('Email is incorrect'));
//        }
//        if (\app\common\model\User::where('email', $email)->where('id', '<>', $user->id)->find()) {
//            $this->error(__('Email already exists'));
//        }
//        $result = Ems::check($email, $captcha, 'changeemail');
//        if (!$result) {
//            $this->error(__('Captcha is incorrect'));
//        }
//        $verification = $user->verification;
//        $verification->email = 1;
//        $user->verification = $verification;
//        $user->email = $email;
//        $user->save();
//
//        Ems::flush($email, 'changeemail');
//        $this->success();
//    }
//
//    /**
//     * 修改手机号
//     *
//     * @ApiMethod (POST)
//     * @param string $mobile  手机号
//     * @param string $captcha 验证码
//     */
//    public function changemobile()
//    {
//        $user = $this->auth->getUser();
//        $mobile = $this->request->post('mobile');
//        $captcha = $this->request->post('captcha');
//        if (!$mobile || !$captcha) {
//            $this->error(__('Invalid parameters'));
//        }
//        if (!Validate::regex($mobile, "^1\d{10}$")) {
//            $this->error(__('Mobile is incorrect'));
//        }
//        if (\app\common\model\User::where('mobile', $mobile)->where('id', '<>', $user->id)->find()) {
//            $this->error(__('Mobile already exists'));
//        }
//        $result = Sms::check($mobile, $captcha, 'changemobile');
//        if (!$result) {
//            $this->error(__('Captcha is incorrect'));
//        }
//        $verification = $user->verification;
//        $verification->mobile = 1;
//        $user->verification = $verification;
//        $user->mobile = $mobile;
//        $user->save();
//
//        Sms::flush($mobile, 'changemobile');
//        $this->success();
//    }
//
//    /**
//     * 第三方登录
//     *
//     * @ApiMethod (POST)
//     * @param string $platform 平台名称
//     * @param string $code     Code码
//     */
//    public function third()
//    {
//        $url = url('user/index');
//        $platform = $this->request->post("platform");
//        $code = $this->request->post("code");
//        $config = get_addon_config('third');
//        if (!$config || !isset($config[$platform])) {
//            $this->error(__('Invalid parameters'));
//        }
//        $app = new \addons\third\library\Application($config);
//        //通过code换access_token和绑定会员
//        $result = $app->{$platform}->getUserInfo(['code' => $code]);
//        if ($result) {
//            $loginret = \addons\third\library\Service::connect($platform, $result);
//            if ($loginret) {
//                $data = [
//                    'userinfo'  => $this->auth->getUserinfo(),
//                    'thirdinfo' => $result
//                ];
//                $this->success(__('Logged in successful'), $data);
//            }
//        }
//        $this->error(__('Operation failed'), $url);
//    }
//
//    /**
//     * 重置密码
//     *
//     * @ApiMethod (POST)
//     * @param string $mobile      手机号
//     * @param string $newpassword 新密码
//     * @param string $captcha     验证码
//     */
//    public function resetpwd()
//    {
//        $type = $this->request->post("type");
//        $mobile = $this->request->post("mobile");
//        $email = $this->request->post("email");
//        $newpassword = $this->request->post("newpassword");
//        $captcha = $this->request->post("captcha");
//        if (!$newpassword || !$captcha) {
//            $this->error(__('Invalid parameters'));
//        }
//        if ($type == 'mobile') {
//            if (!Validate::regex($mobile, "^1\d{10}$")) {
//                $this->error(__('Mobile is incorrect'));
//            }
//            $user = \app\common\model\User::getByMobile($mobile);
//            if (!$user) {
//                $this->error(__('User not found'));
//            }
//            $ret = Sms::check($mobile, $captcha, 'resetpwd');
//            if (!$ret) {
//                $this->error(__('Captcha is incorrect'));
//            }
//            Sms::flush($mobile, 'resetpwd');
//        } else {
//            if (!Validate::is($email, "email")) {
//                $this->error(__('Email is incorrect'));
//            }
//            $user = \app\common\model\User::getByEmail($email);
//            if (!$user) {
//                $this->error(__('User not found'));
//            }
//            $ret = Ems::check($email, $captcha, 'resetpwd');
//            if (!$ret) {
//                $this->error(__('Captcha is incorrect'));
//            }
//            Ems::flush($email, 'resetpwd');
//        }
//        //模拟一次登录
//        $this->auth->direct($user->id);
//        $ret = $this->auth->changepwd($newpassword, '', true);
//        if ($ret) {
//            $this->success(__('Reset password successful'));
//        } else {
//            $this->error($this->auth->getError());
//        }
//    }
}
