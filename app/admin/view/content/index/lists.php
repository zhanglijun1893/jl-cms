{extend name="header"}
{block name="plugins"}
<link href="{___PLUGINS__}/iview/styles/iview.css" rel="stylesheet">
<script src="{___PLUGINS__}/iview/iview.min.js"></script>
{/block}
{block name="content"}
<body class="gray-bg">
<div id="app" style="height: 100%">
    <div class="ibox float-e-margins">
        <div class="ibox-title clearFix">
            <h5><i-button @click="layerOpen('{:Url('form',['cId'=>$id])}',this.innerText,{h:700,w:1100})" icon="md-add-circle" size="small" type="primary">添加</i-button></h5>
        </div>
        <div class="ibox-content">
            <i-table border :columns="columns" :data="data"></i-table>
            <div style="margin: 10px;overflow: hidden">
                <div style="float: right;">
                    <Page :total="total" :page-size="pageSize" @on-change="changePage" show-total show-elevator></Page>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    let data={:json_encode($data)};
    let category=[];
    let rows = 10;
    let columns = [
        {title: 'ID', key: 'id',width: 200},
        {title: '标题', key: 'title'},
        {title: '栏目', key: 'name'},
        {title: '图片', key: 'index_view',
            render: (h, params) => {
            let img = "";
                if (params.row.images_path) {
                    return h('img',{
                        attrs: {
                            src:params.row.images_path
                        },
                        style: {
                            width: '60px',
                            height: '40px'
                        }
                    });
                } else {
                    return h('span', "");
                }

            }
        },
        {title: '首页显示', key: 'index_view',
            render: (h, params) => {
                return h('span', params.row.index_view===1?"显示":"否");
            }
        },
        {title: '更新时间', key: 'update_at'},
        {title: '操作', key: 'action', width: 150, align: 'center',
            render: (h, params) => {
                return h('div', [
                    h('Button', {
                        props: {type: 'primary', size: 'small', ghost: true},
                        style: {marginRight: '5px'},
                        on: {click: () => {
                                layerOpen('{:Url("form")}?id='+params.row.id,'编辑',{h:700,w:1100});
                                // let editUrl = "system/ad/form" + "?id="+ params.row.id;
                                // app.op(editUrl,"编辑商品类型");
                            }}
                    }, '编辑'),
                    h('Button', {
                        props: {type: 'error', size: 'small', ghost: true},
                        on: {click: () => {
                                ajaxRemove(params.index,'{:Url("delete")}?id='+params.row.id,params.row.title,1)
                                //app.remove(params.index,'system/ad/delete?id='+params.row.id,params.row.name)
                            }}
                    }, '删除')
                ]);
            }
        }
    ];
    let tableData = [];
    app = new Vue({
        el: '#app',
        data: {
            isCollapsed: false,
            total: 0,               // 总条数
            pageSize: 10,           //每页显示多少条
            page: 1,                //当前页码
            columns: columns,       //表格头
            data: data              //表格内容
        },
        computed: {
            rotateIcon () {
                return [
                    'menu-icon',
                    this.isCollapsed ? 'rotate-icon' : ''
                ];
            },
            menuitemClasses () {
                return [
                    'menu-item',
                    this.isCollapsed ? 'collapsed-menu' : ''
                ]
            }
        },
        methods: {
            collapsedSider () {
                this.$refs.side1.toggleCollapse();
            },
            selectChange(data) {
                console.log(data);
                if (data[0].template===1) {
                    window.c_iframe.location.href="{:url('single')}?id="+data[0].id;
                } else {
                    window.c_iframe.location.href="{:url('lists')}?cid="+data[0].id;
                }
                return false;
            },
            refresh(id) {        //刷新
                let page = id ? this.page : 1;
                this.fetchData(page);
            },
            changePage(index) {  //分页
                this.page = index;  // 当前页码
                this.fetchData(index);
            },
        }
    });
</script>
{/block}
