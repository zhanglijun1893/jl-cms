<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?=$form->getTitle()?></title>
    <link href="{___PLUGINS__}/iview/styles/iview.css" rel="stylesheet">
    <script src="{___PLUGINS__}/js/vue.min.js"></script>
    <script src="{___PLUGINS__}/iview/iview.min.js"></script>
    <script src="{___PLUGINS__}/js/jquery.min.js"></script>
    <script src="{___PLUGINS__}/js/form-create.min.js"></script>
    <style>
        /*弹框样式修改*/
        .ivu-modal{top: 20px;}
        .ivu-modal .ivu-modal-body{padding: 10px;}
        .ivu-modal .ivu-modal-body .ivu-modal-confirm-head{padding:0 0 10px 0;}
        .ivu-modal .ivu-modal-body .ivu-modal-confirm-footer{display: none;padding-bottom: 10px;}
        .ivu-date-picker {display: inline-block;line-height: normal;width: 280px;}
        .ivu-modal-footer{display: none;}
        body{padding: 20px;}
    </style>
</head>
<body>
<script>
    formCreate.formSuccess = function(form,$r){
        <?=$form->getSuccessScript()?>
        //刷新父级页面
//        parent.$(".J_iframe:visible")[0].contentWindow.location.reload();
        //关闭当前窗口
//        var index = parent.layer.getFrameIndex(window.name);
//        parent.layer.close(index);
        //提交成功后按钮恢复
       // $r.btn.loading(false);
    };

    (function () {
        var create = (function () {
            var getRule = function () {
                var rule = <?=json_encode($form->getRules())?>;
                rule.forEach(function (c) {
                    if ((c.type == 'cascader' || c.type == 'tree') && Object.prototype.toString.call(c.props.data) == '[object String]') {
                        if (c.props.data.indexOf('js.') === 0) {
                            c.props.data = window[c.props.data.replace('js.', '')];
                        }
                    }
                });
                return rule;
            }, vm = new Vue,name = 'formBuilderExec<?= !$form->getId() ? '' : '_'.$form->getId() ?>';
            var _b = false;
            window[name] =  function create(el, callback) {
                if(_b) return ;
                _b = true;
                if (!el) el = document.body;
                var $f = formCreate.create(getRule(), {
                    el: el,
                    form:<?=json_encode($form->getConfig('form'))?>,
                    row:<?=json_encode($form->getConfig('row'))?>,
                    submitBtn:<?=$form->isSubmitBtn() ? '{}' : 'false'?>,
                    resetBtn:<?=$form->isResetBtn() ? 'true' : '{}'?>,
                    iframeHelper:true,
                    global:{
                        upload: {
                            props:{
                                onExceededSize: function (file) {
                                    vm.$Message.error(file.name + '超出指定大小限制');
                                },
                                onFormatError: function () {
                                    vm.$Message.error(file.name + '格式验证失败');
                                },
                                onError: function (error) {
                                    vm.$Message.error(file.name + '上传失败,(' + error + ')');
                                },
                                onSuccess: function (res, file) {
                                    if (res.status === 200) {
                                        file.url = res.data.url;
                                    } else {
                                        vm.$Message.error(res.msg);
                                    }
                                },
                            },
                        },
                    },
                    //表单提交事件
                    onSubmit: function (formData) {
                        $f.btn.loading(true);
                        $.ajax({
                            url: '<?=$form->getAction()?>',
                            type: '<?=$form->getMethod()?>',
                            dataType: 'json',
                            data: formData,
                            success: function (data) {
                                if (data.status === 200) {
                                    vm.$Message.success(data.message);
                                   // $f.btn.loading(false);
                                    formCreate.formSuccess && formCreate.formSuccess(data, $f, formData);
                                    callback && callback(0, data, $f, formData);
                                    //TODO 表单提交成功!
                                } else {
                                    vm.$Message.error(data.message || '表单提交失败');
                                    $f.btn.loading(false);
                                    callback && callback(1, data, $f, formData);
                                    //TODO 表单提交失败
                                }
                            },
                            error: function (e) {
                                vm.$Message.error('表单提交失败');
                                $f.btn.loading(false);
                            }
                        });
                    }
                });
                return $f;
            };
            return window[name];
        }());

        window.$f = create();
//        create();
    })();
</script>
</body>
</html>
