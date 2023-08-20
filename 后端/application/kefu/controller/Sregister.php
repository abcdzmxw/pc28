<?php

namespace app\kefu\controller;

use GatewayWorker\Register;
use Workerman\Worker;

// 自动加载类
require_once __DIR__ . '/../../../addons/kefu/library/GatewayWorker/vendor/autoload.php';

/**
 * Win下启动 register服务 专用类
 */
class Sregister
{

    function __construct()
    {
        // 获取插件配置
        $kefu_config = get_addon_config('kefu');
        // register 必须是text协议
        $register = new Register('text://0.0.0.0:' . $kefu_config['register_port']);

        // 如果不是在根目录启动，则运行runAll方法
        if (!defined('GLOBAL_START')) {
            Worker::runAll();
        }
    }
}