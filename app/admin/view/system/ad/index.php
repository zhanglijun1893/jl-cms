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
                <el-button
                        @click="layerOpen('{:Url('form')}',this.innerText)"
                        icon="el-icon-circle-plus" size="mini" type="primary">
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
                <el-table-column prop="name" label="广告名称"></el-table-column>
                <el-table-column prop="link" label="广告链接"></el-table-column>
                <el-table-column prop="image_path" label="广告图片"></el-table-column>
                <el-table-column prop="profile.name" label="广告位置"></el-table-column>
                <el-table-column prop="display" :formatter="formatShow" label="显示/隐藏"></el-table-column>
                <el-table-column label="操作" align="center" width="180">
                    <template slot-scope="scope">
                        <el-button plain @click="layerOpen('/admin/system.ad/form?id='+scope.row.id,'修改')" size="mini" type="primary">修改</el-button>
                        <el-button v-if="scope.row.id>1" @click="ajaxRemove(scope.$index,'{:Url('/admin/system.ad/delete')}?id='+scope.row.id,scope.row.name,1)" plain size="mini" type="danger">删除</el-button>
                    </template>
                </el-table-column>
            </el-table>
        </div>
    </div>
</div>
</body>
<script type="text/javascript">
    let data={:json_encode($data)};
    app = new Vue({
        el: '#app',
        methods:{
            formatShow: function (row, column, cellValue) {
                if (cellValue === 1){
                    return '隐藏';
                } else {
                    return '显示';
                }
            }

        }
    });
</script>
{/block}