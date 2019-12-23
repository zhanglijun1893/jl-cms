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
    let category =  {:json_encode($category)};
    let display = data.display ? data.display : 0;
    let columns = [
        {type: "select", field: "parent_id", title: "上级栏目", value: [data.parent_id], options: category,
            props: {"placeholder": "请选择", "not-found-text": "无匹配数据"}, col: {span:19}
        },
        {type:'input', field:'name', title:'栏目名称', value: data.name, col: {span:19}},
        {type:'input', field:'en_name', title:'英文名称', value: data.name, col: {span:19}},
        {type:'upload', field:'file_path', title:'栏目图片', value: data.file_path,
            props:{"type": "select", "uploadType": "image", action: "{:url('upload/index')}",
                "name": "image", "maxLength": 1, "accept":"image\/*", "format":["jpg","jpeg","png","gif"],
                allowRemove: true,
                "onSuccess": function (res, file) {
                    file.url = res.data.url;
                },
                "onRemove": function (res, file) {}
            }
        },
        {type:'input', field:'link', title:'外部链接', value: data.link, col: {span:19}},
        {type:'input', field:'seo_title', title:'SEO标题', value: data.seo_title, col: {span:19}},
        {type:'input', field:'seo_keywords', title:'SEO关键字', value: data.seo_keywords, col: {span:19}},
        {type:'input', field:'seo_description', title:'SEO描述', value: data.seo_description, col: {span:19}},
        {type:"checkbox",field:"index_view", title:"首页显示", value:data.index_view,
            options:[
                {value:1,label:"导航"},
                {value:2,label:"首页"},
                {value:3,label:"页尾"},
            ]
        },
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