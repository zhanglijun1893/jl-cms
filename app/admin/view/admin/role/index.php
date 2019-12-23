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
                <el-button @click="layerOpen('{:Url('form')}',this.innerText)" icon="el-icon-circle-plus" size="mini" type="primary">添加菜单</el-button>
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
                <el-table-column prop="name" label="角色名称"></el-table-column>
                <el-table-column prop="description" label="描述"></el-table-column>
                <el-table-column label="操作" align="center" width="180">
                    <template slot-scope="scope">
                        <el-button plain @click="layerOpen('/admin/admin.role/form?id='+scope.row.id,'编辑分类')" size="mini" type="primary">编辑
                        </el-button>
                        <el-button @click="ajaxRemove(scope.$index,'{:Url('/admin/admin.role/delete')}?id='+scope.row.id,scope.row.name,1)" plain size="mini" type="danger">删除</el-button>
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
        el: '#app'
    });
</script>
{/block}