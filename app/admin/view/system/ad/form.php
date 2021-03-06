{extend name="header"}
{block name="plugins"}
<link href="{___PLUGINS__}/iview/styles/iview.css" rel="stylesheet">
<script src="{___PLUGINS__}/iview/iview.min.js"></script>
{/block}
{block name="content"}
<body>
<div class="wrapper wrapper-content" id="app">
    <form-create ref="fc" :rule="rule" :option="option"></form-create>
</div>
</body>
<script type="text/javascript">
    let data = {:json_encode($data)};
    let position =  {:json_encode($position)};
    let display = data.display ? data.display : 0;
    let columns = [
        {type:'input', field:'name', title:'广告名称', value: data.name, col: {span:19}},
        {type: "select", field: "p_id", title: "广告位置", value: [data.p_id], options: position,
            props: {"placeholder": "请选择", "not-found-text": "无匹配数据"}, col: {span:19}
        },
        {type:'upload', field:'image_path', title:'广告图片', value: data.image_path,
            props:{"type": "select", "uploadType": "image", action: "{:url('upload/index')}",
                "name": "image", "maxLength": 1, "accept":"image\/*", "format":["jpg","jpeg","png","gif"],
                allowRemove: true,
                "onSuccess": function (res, file) {
                    file.url = res.data.url;
                },
                "onRemove": function (res, file) {}
            }
        },
        {type:'input', field:'description', title:'广告描述', value: data.description, col: {span:19}},
        {type:'input', field:'link', title:'广告链接', value: data.link, col: {span:19}},
        {type:'InputNumber', field:'list_order', title:'排序', value: data.list_order},
        {
            type:'radio', field:'display', title:'显示/隐藏', value: display,
            options:[
                {value:0,label:"显示",disabled:false},
                {value:1,label:"隐藏",disabled:false},
            ]
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
                        success: function (res) {;
                            if (res.status === 200) {
                                app.$Message.success(res.message);
                                parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
                                setTimeout(function(){
                                    parent.layer.close(parent.layer.getFrameIndex(window.name));
                                    $f.btn.loading(false);
                                },1500);
                            } else {
                                app.$Message.error(res.message || '表单提交失败');
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