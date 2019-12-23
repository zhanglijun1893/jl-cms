<?php

use app\model\ConfigModel;
$systemConfig = cache('systemConfig');
$template = "default";
if (empty($systemConfig)) {
    ConfigModel::setCache();
    $systemConfig = cache('systemConfig');
}

if (!empty($systemConfig['template']) && file_exists(request()->server('DOCUMENT_ROOT')."/templates/".$systemConfig['template']."/config.php")) {
    $template = $systemConfig['template'];
}

return [
    // 模板后缀
    'view_suffix'  => 'html',
    // 模板目录名
    'view_dir_name' => 'templates/'.$template,
    // 视图输出字符串内容替换
    'tpl_replace_string'       => [
        '{__PUBLIC__}'  =>  '/templates/',
        '{__HOME_TEMPLATES__}'  =>  '/templates/'.$template.'/home/statics/',
        '{__STATIC__}'  =>  '/templates/statics/'
    ]
];
