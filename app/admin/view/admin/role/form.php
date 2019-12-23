{extend name="header"}
{block name="plugins"}
<link href="{___PLUGINS__}/iview/styles/iview.css" rel="stylesheet">
<script src="{___PLUGINS__}/iview/iview.min.js"></script>
{/block}
{block name="content"}
<style>
    textarea {
        font-size: 12px!important;
    }
</style>
<body >
<div class="wrapper wrapper-content animated fadeIn" id="app">
    <form-create ref="fc" :rule="rule" :option="option"></form-create>
</div>
</body>
<script type="text/javascript">
    let data = {:json_encode($data)};
    let tree = {:json_encode($tree)};
    let menu = data.menu_id? data.menu_id.split(','):[];
    let rows = 10;
    let columns = [
        {
            type:'input', field:'name', title:'角色', value: data.name, col: {span:18},
            validate: [{required: true, message: '角色必填'}]
        },
        {
            type:'input', field:'description', title:'描述', value: data.description, col: {span:18},
            props: {"type": "textarea"}
        },
        {
            type:"tree",field:"menu_id", title:"权限分配",value:menu,
            props:{data:tree, type:'checked', showCheckbox:true, emptyText:'暂无数据'},
            //validate: [{required: true, message: '权限至少选择一项',type: 'array'}]
        },
        {type:"hidden", field:"id", value: data.id}
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
                        success: function (data) {
                            if (data.status === 200) {
                                app.$Message.success(data.message);
                                parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
                                setTimeout(function(){
                                    parent.layer.close(parent.layer.getFrameIndex(window.name));
                                    $f.btn.loading(false);
                                },2000);
                            } else {
                                app.$Message.error(data.message || '表单提交失败');
                                $f.btn.loading(false);
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