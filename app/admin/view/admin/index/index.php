{extend name="header"}
{block name="plugins"}
<link href="{___PLUGINS__}/element/element.css" rel="stylesheet">
<script src="{___PLUGINS__}/element/element.js"></script>
{/block}
{block name="content"}
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeIn" id="app">
    <div class="ibox-op">
        <div class="description">
            <div class="title_name">操作</div>
            <div class="info">
                <el-button @click="layerOpen('{:Url('form')}',this.innerText)" icon="el-icon-circle-plus" size="mini" type="primary">创建管理员</el-button>
            </div>
        </div>
    </div>
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>列表</h5>
        </div>
        <div class="ibox-content">
            <el-table :data="data" row-key="id" border size="small" style="width: 100%;margin-bottom: 20px;" :tree-props="{children: 'children', hasChildren: 'hasChildren'}">
                <el-table-column prop="id" align="center" label="ID" width="150"></el-table-column>
                <el-table-column prop="username" label="用户名"></el-table-column>
                <el-table-column prop="real_name" label="真实姓名"></el-table-column>
                <el-table-column prop="phone" label="电话"></el-table-column>
                <el-table-column prop="name" :formatter="formatRole" label="角色" ></el-table-column>
                <el-table-column prop="last_time" label="最后登录时间"></el-table-column>
                <el-table-column label="操作" align="center" width="280">
                    <template slot-scope="scope">
                        <el-button plain @click="layerOpen('/admin/admin.index/editPassword?id='+scope.row.id,'修改密码')" size="mini" type="primary">修改密码</el-button>
                        <el-button plain @click="layerOpen('/admin/admin.index/form?id='+scope.row.id,'修改信息')" size="mini" type="primary">修改信息</el-button>
                        <el-button v-if="scope.row.id>1" @click="ajaxRemove(scope.$index,'{:Url('/admin/admin.index/delete')}?id='+scope.row.id,scope.row.name,1)" plain size="mini" type="danger">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    let data={:json_encode($data)};
    let rows = 10;
    let columns = [];
    app = new Vue({
        el: '#app',
        methods:{
            formatRole: function (row, column, cellValue) {
                if (row.id === 1){
                    return '超级管理员';
                }else if (row.role_id === 0){
                    return '无权限';
                } else {
                    console.log(cellValue);
                    return cellValue;
                }
            }
        }
    });
</script>
{/block}