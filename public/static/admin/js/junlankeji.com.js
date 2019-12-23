/**
 * 弹层
 * @param url
 * @param title
 * @param wh
 */
function layerOpen(url,title,wh) {
    wh === undefined && (wh = {});
    let area=[(wh.w || 750)+'px', (wh.h || 680)+'px'];
    parent.layer.open({
        type: 2,
        title:title,
        area: area,
        fixed: false, //不固定
        maxmin: true,
        moveOut:false,//true  可以拖出窗外  false 只能在窗内拖
        anim:5,//出场动画 isOutAnim bool 关闭动画
        offset:'auto',//['100px','100px'],//'auto',//初始位置  ['100px','100px'] t[ 上 左]
        shade:0,//遮罩
        resize:true,//是否允许拉伸
        content: url,//内容
        move:'.layui-layer-title',// 默认".layui-layer-title",// 触发拖动的元素
        moveEnd:function(){//拖动之后回调
            //console.log(this);
        }
    });
}
/**
 * 删除
 * @param index
 * @param url
 * @param title
 */
function ajaxRemove(index,url, title) {
    layer.confirm('确认要删除《' + title + '》吗？', {icon: 0},
        function () {
            $.ajax({
                type: 'GET',
                url: url,
                success: function (r) {
                    if (r.status === 200) {
                        data.splice(index, 1);
                        layer.msg('已删除!', {
                            icon: 1, time: 1000
                        });
                        return 1;
                    } else {
                        layer.msg(data.message);
                        return 0;
                    }
                }
            });
    });
}



/**
 * 表单提交按钮
 * @param url
 * @param jumpUrl
 */
function submitForm(url,r) {
    if (!$("#jl-form").valid()) return false;
    $("#jl-form").find(":button").attr("disabled", true);
    $.ajax({
        type: "POST",
        url: basePath + url,
        data: $("#jl-form").serialize(),
        success: function (data) {
            if (data.status === 200) {
                layer.msg("操作成功");
                setTimeout(function () {
                    $("#jl-form").find(":button").attr("disabled", false);
                    if (r===1) {//刷新父页面
                        window.parent.location.reload();
                    } else if (r===2) {//刷新当前页面
                        window.location.reload();
                    } else {//更新表格数据
                       // window.parent.refresh();
                        parent.$(".J_iframe:visible")[0].contentWindow.refresh();
                    }
                    window.parent.layer.closeAll();
                }, 500);
                return false
            } else {
                layer.msg(data.message);
                return false;
            }
        },
        error:function(e){
            console.log(e);
        }
    });
    return false;
}

function layerIframeParent(url, title, opt) {
    opt === undefined && (opt = {});
    var area=[(opt.w || 800)+'px', (opt.h || 604)+'px'];

    parent.layer.open({
        type: 2,
        title: title,
        area: area,
        fixed: false, //不固定
        maxmin: true, //开启最大化最小化按钮,
        moveOut:false,//true  可以拖出窗外  false 只能在窗内拖
        anim:5,//出场动画 isOutAnim bool 关闭动画
        offset:'auto',//['100px','100px'],//'auto',//初始位置  ['100px','100px'] t[ 上 左]
        //offset:['104px'],
        shade: false,//遮罩
        resize:true,//是否允许拉伸
        content: basePath + url,
        move:'.layui-layer-title',// 默认".layui-layer-title",// 触发拖动的元素
        shadeClose: true

        /*
        moveEnd:function(){//拖动之后回调
                console.log(this);
            }
        */
    });
}

/**
 * layer 弹层
 * @param url
 * @param title
 * @param width
 * @param height
 */
function layerIframe(url, title, opt) {
    opt === undefined && (opt = {});
    var area=[(opt.w || 800)+'px', (opt.h || 550)+'px'];

    layer.open({
        type: 2,
        title: title,
        area: area,
        fixed: false, //不固定
        maxmin: true, //开启最大化最小化按钮,
        moveOut:false,//true  可以拖出窗外  false 只能在窗内拖
        anim:5,//出场动画 isOutAnim bool 关闭动画
        offset:'auto',//['100px','100px'],//'auto',//初始位置  ['100px','100px'] t[ 上 左]
        //offset:['104px'],
        shade: false,//遮罩
        resize:true,//是否允许拉伸
        content: basePath + url,
        move:'.layui-layer-title',// 默认".layui-layer-title",// 触发拖动的元素
        shadeClose: true

        /*
        moveEnd:function(){//拖动之后回调
                console.log(this);
            }
        */
    });
}
/*function remove(obj, url) {
    console.log("remove");
    layer.confirm('确认要删除吗？', {
            icon: 0,
            skin: 'layui-layer-lan'
        },
        function () {
            $.ajax({
                type: 'GET',
                url: url,
                success: function (data) {
                    if (data.status === 200) {
                        $(obj).parents("tr").remove();
                        layer.msg('已删除!', {
                            icon: 1, time: 1000
                        });
                    } else {
                        layer.msg(data.message);
                    }
                }
            });
        });
}*/

/**
 * webUploader 文件上传
 * @param number 上传数量
 * @param element_id 返指定元素ID
 * @param callback  回调方法
 * @param fileType 文件类型
 * @param thumbnail 1创建图缩略图
 */
function webUploader(number, element_id, callback, fileType,thumbnail) {
    var url = basePath + "upload/webUploader?number="+number+"&element_id="+element_id+"&callback="+callback+"&fileType="+fileType+"&thumbnail="+thumbnail;
    layer.open({
        type: 2,
        title: "上传图片",
        shadeClose: true,
        shade: false,
        maxmin: true, //开启最大化最小化按钮
        area: ['60%', '60%'],
        content: url
    });
}




