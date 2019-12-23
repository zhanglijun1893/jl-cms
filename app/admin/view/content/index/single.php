{extend name="header"}
{block name="plugins"}
<link href="{___PLUGINS__}/iview/styles/iview.css" rel="stylesheet">
<script src="{___PLUGINS__}/iview/iview.min.js"></script>
{/block}
{block name="content"}
<link href="{___PLUGINS__}/bootstrap/bootstrap.min.css" rel="stylesheet">
<link href="{___PLUGINS__}/summernote/summernote.css" rel="stylesheet">
<link href="{___PLUGINS__}/summernote/summernote-bs4.css" rel="stylesheet">
<script src="{___PLUGINS__}/bootstrap/bootstrap.min.js"></script>
<script src="{___PLUGINS__}/summernote/summernote.min.js"></script>
<script src="{___PLUGINS__}/summernote/summernote-zh-CN.js"></script>
<style>
    .note-editor {height: auto !important}
</style>
<body>
<div class="wrapper wrapper-content" id="app">
    <form-create ref="fc" :rule="rule" :option="option"></form-create>
</div>
<script type="text/javascript">
    let data = {:json_encode($data)};
    let category = {:json_encode($category)};
    let arr = [];
    let dataSource = [
        {type: "input", field: "category_id", title: "栏目",
            value: category.name,props:{disabled:true}, col: {span:24}},
        {type:'input', field:'author', title:'来源', value: data.author, col: {span:8}},
        {type:"hidden", field:"c_id", value: category.id},
        {type:'hidden', field:'title', value: category.name},
        {
            type:'upload', field:'images_path', title:'图片', value: data.images_path,
            props:{"type": "select", "uploadType": "image","name": "image",
                "maxLength": 1, "accept":"image\/*", "format":["jpg","jpeg","png","gif"],
                action: "{:url('upload/index')}",allowRemove: true,
                "onSuccess": function (res, file) {file.url = res.data.url;},
                "onRemove": function (res, file) {}
            }
        },
        {type:'input', field:'meta_keywords', title:'关键字', value: data.meta_keywords,
            props: {"type": "textarea"}
        },
        {type:'input', field:'meta_description', title:'描述', value: data.meta_description,
            props: {"type": "textarea"}
        },
        {
            type:'input',field:'content', title:'内容',value: data.content,
            props: {"type": "textarea", "element-id":'summernote'}
        },
        {type:"hidden", field:"id", value: data.id}
    ];
    app = new Vue({
        el: "#app",
        data: {
            rule: dataSource,
            option: {
                onSubmit: function (formData) {
                    $f.btn.loading();
                    formData.content = $('#summernote').summernote('code');
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
    $(function () {
        $('#summernote').summernote({
            lang: 'zh-CN',
            height: 350,
            callbacks: {
                onImageUpload: function (file) {
                    sendFile(file);
                }
            }
        });
    });
    //上传方法
    function sendFile(file) {
        let formData = new FormData();
        formData.append('image', file[0]);
        //formData.append('root', '1');
        $.ajax({
            url: '{:url("admin/upload/index")}',//后台文件上传接口
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                if (res.status === 200) {
                    $('#summernote').summernote('insertImage', res.data.url);
                } else {
                    console.log(res.message);
                }
            }
        });
    }
</script>
<!--{include file="form-json-js"}-->
</body>
{/block}
