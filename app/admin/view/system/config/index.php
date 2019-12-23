{extend name="header"}
{block name="plugins"}
<link href="{___PLUGINS__}/iview/styles/iview.css" rel="stylesheet">
<script src="{___PLUGINS__}/iview/iview.min.js"></script>
{/block}
{block name="content"}
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeIn" id="app">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>系统设置</h5>
        </div>
        <div class="ibox-content">
            <tabs type="card" :animated="false">
                <tab-pane label="网站信息">
                    <form-create ref="fc" :rule="rule" :option="option"></form-create>
                </tab-pane>
            </tabs>
        </div>
</div>
</body>
<script type="text/javascript">
    let data = {:json_encode($data)};
    let template =  {:json_encode($template)};
    let rows = 10;
    let columns = [
        {type:'input', field:'name', title:'网站名称', value: data.name, col: {span:19}},
        {type:'input', field:'description', title:'网站简介', value: data.description, col: {span:19}},
        {type:'input', field:'address', title:'网站地址', value: data.address, col: {span:19}},
        {type:'input', field:'tel', title:'网站电话', value: data.tel, col: {span:19}},
        {type:'upload', field:'logo', title:'网站LOGO', value: data.logo,
            props:{"type": "select", "uploadType": "image", action: "{:url('upload/index')}",
                "name": "image", "maxLength": 1, "accept":"image\/*", "format":["jpg","jpeg","png","gif"],
                allowRemove: true,
                "onSuccess": function (res, file) {
                    file.url = res.data.url;
                },
                "onRemove": function (res, file) {}
            }
        },
        {type: "select", field: "template", title: "选择模板", value: [data.template], options: template,
            props: {"placeholder": "请选择", "not-found-text": "无匹配数据"}, col: {span:19}
        },
        {type:'input', field:'meta_title', title:'meta标题', value: data.meta_title, col: {span:19}},
        {type:'input', field:'meta_keywords', title:'meta关键词', value: data.meta_keywords, col: {span:19}},
        {type:'input', field:'meta_description', title:'meta描述', value: data.meta_description, props: {"type":"textarea"},col: {span:19}},
        {type:'input', field:'copyright', title:'版权信息', value: data.copyright, col: {span:19}}
    ];

    app = new Vue({
        el: '#app',
        data: {
            rule: columns,
            option: {
                submitBtn: {
                    type: "primary",
                    offset: 1,
                    xs: 1,
                    long: false,//开启后，按钮的长度为 100%
                    col: {
                        push: 2,//栅格向右移动格数
                        pull: 2,//栅格向左移动格数
                        span: 2
                    }
                },
                onSubmit: function (formData) {
                    $f.btn.loading();
                    $.ajax({
                        url: '{:url("save")}',
                        type: 'POST',
                        dataType: 'json',
                        data: formData,
                        success: function (res) {
                            if (res.status ===200 ) {
                                window.location.reload();
                            } else  {
                                app.$Message.error(res.message);
                            }
                        },
                        error: function (e) {
                            console.log(e);
                            app.$Message.error("表单提交失败");
                            $f.btn.loading(false);
                        }
                    });
                }
            }
        },
        methods: {},
        mounted: function() {
            $f = this.$refs.fc.$f;
            this.formData = $f.model();
        },
    });
</script>
{/block}