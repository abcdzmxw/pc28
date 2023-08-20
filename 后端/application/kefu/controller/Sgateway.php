<?php

namespace app\kefu\controller;

use GatewayWorker\Gateway;
use Workerman\Worker;

require_once __DIR__ . '/../../../addons/kefu/library/GatewayWorker/vendor/autoload.php';

/**
 * Win下启动 gateway服务 专用类
 */
class Sgateway
{

    function __construct()
    {
        $kefu_config = get_addon_config('kefu');

        // gateway 进程
        $context   = [];
        $ssl_start = false;
        if ($kefu_config['wss_switch'] && $kefu_config['ssl_cert'] && $kefu_config['ssl_cert_key']) {
            $context ['ssl'] = [
                // 使用绝对路径
                'local_cert'  => $kefu_config['ssl_cert'], // 也可以是crt文件
                'local_pk'    => $kefu_config['ssl_cert_key'],
                'verify_peer' => false,
                //'allow_self_signed' => true, //如果是自签名证书开启此选项
            ];

            $ssl_start = true;
        }

        $gateway = new Gateway("websocket://0.0.0.0:" . $kefu_config['websocket_port'], $context);

        if ($ssl_start) {
            // 开始SSL
            $gateway->transport = 'ssl';
        }

        // gateway名称，status方便查看
        $gateway->name = 'KeFuGateway' . ($ssl_start ? '-wss' : '');

        // gateway进程数
        $gateway->count = $kefu_config['gateway_process_number'];

        // 本机ip，分布式部署时使用内网ip
        $gateway->lanIp = '127.0.0.1';

        // 内部通讯起始端口，假如$gateway->count=4，起始端口为4000
        // 则一般会使用4000 4001 4002 4003 4个端口作为内部通讯端口 
        $gateway->startPort = $kefu_config['internal_start_port'];

        // 服务注册地址
        $gateway->registerAddress = '127.0.0.1:' . $kefu_config['register_port'];

        // 心跳间隔
        $gateway->pingInterval = 30;

        $gateway->pingNotResponseLimit = 1;

        // 心跳数据
        $gateway->pingData = '';

        // 如果不是在根目录启动，则运行runAll方法
        if (!defined('GLOBAL_START')) {
            Worker::runAll();
        }
    }
}