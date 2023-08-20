<?php

namespace app\kefu\controller;

use GatewayWorker\Gateway;
use Workerman\Autoloader;
use Workerman\Worker;

// 自动加载类
require_once __DIR__ . '/../../../addons/kefu/library/GatewayWorker/vendor/autoload.php';

/**
 * Win下启动 text_gateway服务 专用类
 */
class Stextgateway
{

    function __construct()
    {
        Autoloader::setRootPath(__DIR__);

        $kefu_config = get_addon_config('kefu');

        $internal_gateway                  = new Gateway("Text://127.0.0.1:" . ($kefu_config['register_port'] + 100));
        $internal_gateway->name            = 'KeFuGateway';
        $internal_gateway->startPort       = $kefu_config['internal_start_port'] + 1000;
        $internal_gateway->registerAddress = '127.0.0.1:' . $kefu_config['register_port'];// 端口为start_register.php中监听的端口

        // 如果不是在根目录启动，则运行runAll方法
        if (!defined('GLOBAL_START')) {
            Worker::runAll();
        }
    }
}