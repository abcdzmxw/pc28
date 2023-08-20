<?php

namespace app\api\controller;

use app\common\controller\Api;
use Support\GameRuleService;
use Support\Identify\IdentifyFactory;
use think\Db;

/**
 * 首页接口
 */
class Index extends Api
{
    protected array $noNeedLogin = ['*'];
    protected array $noNeedRight = ['*'];



    /**
     * 首页
     *
     */
    public function index()
    {
        $result['siteInfo']['title'] = Db::name('config')->where(['id'=>1])->value('value');
        $result['siteInfo']['logo'] = Db::name('config')->where(['id'=>19])->value('value');
        $result['banner'] = Db::name('banner')->where(['status'=>1])->whereNull('deletetime')->order("weigh","desc")->select();
        $agId = Db::name('domain')->where('domain',$this->request->host())->value('admin_id');
        $result["domain"] = Db::name('domain')->where(['admin_id'=>$agId,'status'=>1])->select();
        $this->success('请求成功',$result);
    }

    public function articleList()
    {
        $result['articleTabs'] = Db::name('article_class')->where('status',1)->order('weigh','desc')->field(['id','title'])->select();
        array_walk($result['articleTabs'],function (&$item){
            $item['articles'] = Db::name("article")->where([
                'status'=>1,
                'article_class_id'=>$item['id']
            ])->order('weigh','desc')
                ->select();
        });
        $result['tops'] = $item['articles'] = Db::name("article")
            ->where(['status'=>1,])
            ->order('weigh','desc')
            ->limit(0,3)
            ->select();
        $this->success('',$result);
    }




    public function test()
    {
//        $identity = IdentifyFactory::makeIdentify(0);
//        $result = $identity->identify("单100");
//        $this->success('请求成功',$result);

//            $nowIssue = GameRuleService::nowIssue();
//            $this->success('',$nowIssue);

        $service = new GameRuleService();
        $service->createRule(4,1);
        $service->createRule(4,2);
        $service->createRule(4,3);
        $service->createRule(4,4);
        $service->createRule(3,5);
        $service->createRule(3,6);
        $service->createRule(2,7);
        $service->createRule(1,8);
        $this->success('请求成功',);
    }

}
