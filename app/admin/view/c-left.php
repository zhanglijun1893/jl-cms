<body style="height: 100%">
<style scoped>
    html,body{
        height: 100%;
    }
    .layout{
        border: 1px solid #d7dde4;
        background: #f5f7f9;
        position: relative;
        border-radius: 4px;
        overflow: hidden;
    }
    .layout-header-bar{
        background: #fff;
        box-shadow: 0 1px 1px rgba(0,0,0,.1);
    }
    .layout-logo-left{
        width: 90%;
        height: 30px;
        background: #5b6270;
        border-radius: 3px;
        margin: 15px auto;
    }
    .menu-icon{
        transition: all .3s;
    }
    .rotate-icon{
        transform: rotate(-90deg);
    }
    .menu-item span{
        display: inline-block;
        overflow: hidden;
        width: 69px;
        text-overflow: ellipsis;
        white-space: nowrap;
        vertical-align: bottom;
        transition: width .2s ease .2s;
    }
    .menu-item i{
        transform: translateX(0px);
        transition: font-size .2s ease, transform .2s ease;
        vertical-align: middle;
        font-size: 16px;
    }
    .collapsed-menu span{
        width: 0;
        transition: width .2s ease;
    }
    .collapsed-menu i{
        transform: translateX(5px);
        transition: font-size .2s ease .2s, transform .2s ease .2s;
        vertical-align: middle;
        font-size: 22px;
    }
    .ivu-layout-sider-children {
        border-right: 1px solid #ccc;
    }
    .ivu-layout-sider-collapsed {
        width: 0!important;
        flex:0 0!important;
        min-width:0!important;
    }
    .ivu-layout-sider-collapsed .menuitemClasses {
        display: none;
        /*-webkit-transition: all .2s ease-in-out;
        transition: all .2s ease-in-out;*/
        -webkit-transition: background .3s ease;
        transition: background .3s ease;
    }
    .menuitemClasses {
        padding: 15px;
    }
</style>
<div id="app" style="height: 100%">
    <Sider width="160" ref="side1" hide-trigger collapsible :collapsed-width="78" v-model="isCollapsed" :style="{background: '#fff'}">
        <div class="menuitemClasses" >
            <Tree :data="category" @on-select-change="selectChange"></Tree>
        </div>
    </Sider>
</div>
</body>


