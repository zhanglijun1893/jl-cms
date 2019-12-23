<script type="text/javascript">
    new Vue({
        el: "#app",
        data: {
            formData: {},
            rule: dataSource,
            option: {
                resetBtn: { //显示表单重置按钮
                    show:true,//默认不显示
                    long:false,
                    col:{push:3, span:1}
                },
                submitBtn: {
                    type:"primary",
                    offset:1,
                    xs:1,
                    long:false,//开启后，按钮的长度为 100%
                    col:{
                        push:2,//栅格向右移动格数
                        pull:2,//栅格向左移动格数
                        span:2
                    }
                },
                onSubmit: function (formData) {
                    $f.btn.loading();   //按钮进入提交状态
                    $f.resetBtn.disabled(); //重置按钮禁用
                    //商品分类级联菜单，数组
                    if(formData.parentId && (formData.parentId instanceof Array)) {formData.parentId = formData.parentId.pop()}
                    //权限 菜单 id
                    if(formData.menuId && (formData.menuId instanceof Array)) {formData.menuId = formData.menuId.join(",")}
                    axios({
                        method: 'post',
                        url:  url,
                        data: Qs.stringify(formData)
                    }).then(function (response) {
                        if (response.status === 200) {
                            if (local) {
                                app.$Message.success("操作成功");
                                window.location.reload();
                            } else {
                                parent.$(".J_iframe:visible")[0].contentWindow.app.msgSuccess();
                                parent.$(".J_iframe:visible")[0].contentWindow.app.refresh(formData.id);
                                $f.btn.loading(false);
                                window.parent.layer.closeAll();
                            }
                        } else {
                            if (local) {
                                app.$Message.error("操作失败");
                                $f.btn.loading(false);
                            } else {
                                parent.$(".J_iframe:visible")[0].contentWindow.app.msgError(response.data.message);
                                $f.btn.loading(false);
                                window.parent.layer.closeAll();
                            }
                        }
                    }).catch(function (error) {
                        console.log(error);
                        self.fetchError = error;
                    })
                }
            }
        },
        mounted: function() {
            $f = this.$refs.fc.$f;
            this.formData = $f.model();
        }
    });
</script>