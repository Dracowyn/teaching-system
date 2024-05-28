<?php
// +----------------------------------------------------------------------
// | BuildAdmin Workerman 文件监听配置
// +----------------------------------------------------------------------

return [
    'switch'      => false, // 是否开启PHP文件更改监控（调试模式下自动开启）
    'interval'    => 2, // 文件监控检测时间间隔（秒）
    'soft_reboot' => true, // 在没有请求时（空闲）时才检测，仅 http 服务下有效
    'paths'       => [
        __DIR__ . '/../app',
        __DIR__ . '/../config',
        __DIR__ . '/../extend',
        __DIR__ . '/../vendor/composer',
        __DIR__ . '/../modules/workerman',
    ], // 文件监控目录
    'extensions'  => ['php', 'stub', 'env'], // 监控的文件类型
];