<?php

return [
    'autoload' => false,
    'hooks' => [
        'upgrade' => [
            'kefu',
        ],
        'app_init' => [
            'kefu',
        ],
        'config_init' => [
            'summernote',
        ],
    ],
    'route' => [
        '/example$' => 'example/index/index',
        '/example/d/[:name]' => 'example/demo/index',
        '/example/d1/[:name]' => 'example/demo/demo1',
        '/example/d2/[:name]' => 'example/demo/demo2',
    ],
    'priority' => [],
    'domain' => '',
];
