{extend name="header"}
{block name="plugins"}
<link href="{___PLUGINS__}/iview/styles/iview.css" rel="stylesheet">
<script src="{___PLUGINS__}/iview/iview.min.js"></script>
{/block}
{block name="content"}
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeIn" id="app">
    <Alert type="info" show-icon>
        <Icon type="md-checkmark" slot="icon"></Icon>
        <i class="icon-ok green"></i>欢迎使用
        <strong class="green">君澜科技<small>&nbsp;后台管理系统 (v4.4)</small></strong>
    </Alert>
    <div class="ibox float-e-margins">
        <div class="ibox-title clearFix">
            <h5>系统信息</h5>
        </div>
        <div class="ibox-content">
            <i-table border stripe size="small" :columns="columns1" :show-header="false" :data="data1"></i-table>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    new Vue({
        el: '#app',
        data: {
            columns1: [
                {key: 'name'},
                {key: 'value'}
            ],
            data1: [
                {
                    name: "服务器操作系统：{:explode(' ', php_uname())[0]} [内核版本：{:explode(' ', php_uname())[2]}]",
                    value: "服务器域名/IP地址：{:$_SERVER['SERVER_NAME']} [{:gethostbyname($_SERVER['SERVER_NAME'])}]"
                },
                {
                    name: "PHP版本：{:PHP_VERSION}",
                    value: 'PHP运行方式：{:strtoupper(php_sapi_name())}'
                },
                {
                    name: "系统时间：{:date('Y年n月j日 H:i:s')}",
                    value: "程序最长运行时间(max_execution_time)：{:get_cfg_var('max_execution_time')}秒",
                },
                {
                    name: "POST最大字节数(post_max_size)：{:get_cfg_var('post_max_size')}",
                    value: "程序最多允许使用内存量(memory_limit)：{:get_cfg_var('memory_limit')}",
                }
            ]
        }
    });
</script>
{/block}