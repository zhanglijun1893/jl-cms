<?php
// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

return [
    'http_exception_template' => [
        // 定义404错误的模板文件地址
        404 =>  \think\facade\App::getAppPath() . 'view/404.php',
        // 还可以定义其它的HTTP status
        401 =>  \think\facade\App::getAppPath() . 'view/401.php',
    ]
];
