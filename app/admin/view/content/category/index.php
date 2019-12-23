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
                <el-button @click="layerOpen('{:Url(\'form\')}',this.innerText)" icon="el-icon-circle-plus" size="mini" type="primary">
                    添加
                </el-button>
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
                <el-table-column prop="name" label="栏目名称"></el-table-column>
                <el-table-column prop="en_name" label="英文名称"></el-table-column>
                <el-table-column prop="template" label="栏目模板"></el-table-column>
                <el-table-column prop="link" label="外部链接"></el-table-column>
                <el-table-column prop="index_view" label="首页显示"></el-table-column>
                <el-table-column label="操作" align="center" width="180">
                    <template slot-scope="scope">
                        <el-button plain @click="layerOpen('{:Url(\'form\')}?id='+scope.row.id,'编辑')" size="mini" type="primary">编辑
                        </el-button>
                        <el-button @click="ajaxRemove(scope.$index,'{:Url(\'delete\')}?id='+scope.row.id,scope.row.name,1)" plain size="mini" type="danger">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </div>
</div>
<script type="text/javascript">
    let data={:json_encode($data)};
    let rows = 10;
    let columns = [];
    app = new Vue({
        el: '#app'
    });
</script>
</body>
{/block}