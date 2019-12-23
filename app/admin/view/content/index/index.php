{extend name="header"}
{block name="plugins"}
<link href="{___PLUGINS__}/element/element.css" rel="stylesheet">
<script src="{___PLUGINS__}/element/element.js"></script>
{/block}
{block name="content"}
<style>
    html, body {
        height: 100%;
        font-size: 12px;
        padding: 0;
        margin: 0;
    }
    .el-menu {
        padding: 10px;

    }
    .el-tree-node__label {
        font-size: 12px;
    }
    .el-header {
        background-color: #ffffff;
        color: #333;
        line-height: 60px;
    }
    .el-menu-vertical-demo:not(.el-menu--collapse) {
        width: 150px;
    }
    .el-menu--collapse {
        width: 0;
        padding: 0;
    }
    .el-menu--collapse .el-tree {
        visibility: hidden;
    }
    .el-main {
        padding: 0;
    }
</style>
<body class="gray-bg">
<div id="app" style="height: 100%;">
    <el-container style="border: 1px solid #eee; height: 100%">
        <el-menu class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose" :collapse="isCollapse">
            <el-tree
                    ref="tree"
                    :data="data"
                    node-key="id"
                    highlight-current
                    current-node-key
                    default-expand-all
                    :props="defaultProps"
                    @node-click="handleNodeClick"
            ></el-tree>
        </el-menu>
        <el-container>
            <el-header>
                <el-switch v-model="isCollapse"></el-switch>
            </el-header>
            <el-main>
                <iframe frameborder="0" class="J_iframe" name="c_iframe" width="100%" height="100%"
                        src="{:Url('lists')}" data-id="welcome"></iframe>
            </el-main>
        </el-container>
    </el-container>
</div>
</body>

<script type="text/javascript">
    let category = {:json_encode($category)};
    app = new Vue({
        el: '#app',
        data: {
            isCollapse: false,
            data: category,
            selectKey: [9],
            treeKey: '',
            defaultProps: {
                children: 'children',
                label: 'label'
            }
        },
        computed: {
            rotateIcon() {
                return [
                    'menu-icon',
                    this.isCollapsed ? 'rotate-icon' : ''
                ];
            },
            menuitemClasses() {
                return [
                    'menu-item',
                    this.isCollapsed ? 'collapsed-menu' : ''
                ]
            }
        },
        methods: {
            handleOpen(key, keyPath) {
                console.log(key, keyPath);
            },
            handleClose(key, keyPath) {
                console.log(key, keyPath);
            },
            collapsedSider() {
                this.$refs.side1.toggleCollapse();
            },
            selectChange(data) {
                if (data[0].template === 1) {
                    window.c_iframe.location.href = "{:url('single')}?id=" + data[0].id;
                } else {
                    window.c_iframe.location.href = "{:url('lists')}?id=" + data[0].id;
                }
                return false;
            },
            handleNodeClick(data) {
                if (data.children.length <= 0) {
                    if (data.template === 1) {
                        window.c_iframe.location.href = "{:url('single')}?id=" + data.id;
                    } else {
                        window.c_iframe.location.href = "{:url('lists')}?id=" + data.id;
                    }
                }
            }
        }
    });
</script>
{/block}