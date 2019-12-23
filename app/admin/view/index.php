<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>君澜科技 - 管理系统</title>
    <!--[if lt IE 9]><meta http-equiv="refresh" content="0;ie.html" /><![endif]-->
    <link rel="shortcut icon" href="{__STATIC__}/favicon.ico">
    <link href="{___PLUGINS__}/bootstrap/bootstrap.min.css" rel="stylesheet">
    <link href="{___PLUGINS__}/css/animate.css" rel="stylesheet">
    <link href="{___PLUGINS__}/css/h.ui.css" rel="stylesheet">
    <link href="{___PLUGINS__}/css/font-awesome.min.css" rel="stylesheet">
    <style>/*logo 刷新小按钮样式*/
    .logo5 {height: 40px; margin: 0 0 20px 15px}
    .nav-header {padding: 33px 25px 15px}
    .nav > li.active {border-left: 4px solid #2d8cf0}
    #content-main {height: calc(100% - 78px)}
    .pace .pace-progress {background: #2d8cf0}
    .roll-right.J_tabLeft {right: 200px}
    .roll-right.J_tabReply {background: #fff;height: 40px;width: 40px;right: 40px;outline: 0}
    .roll-right.J_tabRight {right: 160px}
    .roll-right.J_tabRefresh {background: #fff;right: 80px;height: 40px;width: 40px;outline: 0}
    .roll-right.J_tabFullScreen {background: #fff;height: 40px;width: 40px;outline: 0;right: 120px}
    .dropdown.J_tabClose {height: 40px;width: 40px;outline: 0;right: 0}
    .roll-right.btn-group button {width: 40px}
    .roll-right.btn-group {width: 40px;right: 0}
    </style>
</head>
<body class="fixed-sidebar full-height-layout gray-bg" style="overflow:hidden">
<div id="wrapper">
    <!--左侧导航开始-->
    <nav class="navbar-default navbar-static-side" role="navigation">
        <div class="nav-close"><i class="fa fa-times-circle"></i></div>
        <div class="sidebar-collapse">
            <ul class="nav" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element">
                        <span>
                            <img alt="logo" class="logo5" src="{__STATIC__}/images/logo5.gif" />
                        </span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear">
                               <span class="block m-t-xs">
                                   <strong class="font-bold">{:$adminName}</strong>
                               </span>
                                <span class="text-muted text-xs block">
                                    <span>{:$roleName}</span>
                                    <b class="caret"></b>
                                </span>
                            </span>
                        </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a class="J_menuItem" href="{:url('system/config/index')}">网站设置</a></li>
                            <li><a class="J_menuItem" href="#">联系我们</a></li>
                            <li class="divider"></li>
                            <li><a href="{:url('/admin/index/logout')}">安全退出</a></li>
                        </ul>
                    </div>
                    <div class="logo-element">JL</div>
                </li>
                <li class="active">
                    <a class="J_menuItem" href="welcome" data-index="0">
                        <i class="fa fa-home"></i><span class="nav-label">首页</span>
                    </a>
                </li>
                <!--  菜单  -->
                {volist name="menuList" id="menu"}
                <?php if(isset($menu['child']) && count($menu['child']) > 0){ ?>
                    <li>
                        <a href="#"><i class="fa fa-{$menu.icon}"></i> <span class="nav-label">{$menu.name}</span><span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            {volist name="menu.child" id="child"}
                            <li>
                                <?php if(isset($child['child']) && count($child['child']) > 0){ ?>
                                    <a href="#"><i class="fa fa-{$child.icon}"></i>{$child.name}<span class="fa arrow"></span></a>
                                    <ul class="nav nav-third-level">
                                        {volist name="child.child" id="song"}
                                        <li><a class="J_menuItem" href="{$song.url}"><i class="fa fa-{$song.icon}"></i> {$song.name}</a></li>
                                        {/volist}
                                    </ul>
                                <?php }else{ ?>
                                    <a class="J_menuItem" href="{$child.url}"><i class="fa fa-{$child.icon}"></i>{$child.name}</a>
                                <?php } ?>
                            </li>
                            {/volist}
                        </ul>
                    </li>
                <?php } ?>
                {/volist}
            </ul>
        </div>
    </nav>
    <!--左侧导航结束-->
    <!--右侧部分开始-->
    <div id="page-wrapper" class="gray-bg dashbard-1">
        <div class="row content-tabs">
            <button class="roll-nav roll-left navbar-minimalize" style="padding: 0;margin: 0;"><i class="fa fa-bars"></i></button>
            <nav class="page-tabs J_menuTabs">
                <div class="page-tabs-content">
                    <a href="javascript:;" class="active J_menuTab" data-id="welcome">首页</a>
                </div>
            </nav>
            <button class="roll-nav roll-right J_tabLeft"><i class="fa fa-backward"></i></button>
            <button class="roll-nav roll-right J_tabRight"><i class="fa fa-forward"></i></button>
            <a href="javascript:void(0);" class="roll-nav roll-right J_tabReply" title="返回"><i class="fa fa-reply"></i> </a>
            <a href="javascript:void(0);" class="roll-nav roll-right J_tabRefresh" title="刷新"><i class="fa fa-refresh"></i> </a>
            <a href="javascript:void(0);" class="roll-nav roll-right J_tabFullScreen" title="全屏"><i class="fa fa-arrows"></i> </a>

            <div class="btn-group roll-nav roll-right">
                <button class="roll-right dropdown J_tabClose" data-toggle="dropdown">
                    <i class="fa fa-power-off"></i><span class="caret"></span>
                </button>
                <ul role="menu" class="dropdown-menu dropdown-menu-right">
                    <li class="J_tabShowActive"><a>定位当前选项卡</a></li>
                    <li class="divider"></li>
                    <li class="J_tabCloseAll"><a>关闭全部选项卡</a></li>
                    <li class="J_tabCloseOther"><a>关闭其他选项卡</a>
                    </li>
                </ul>
            </div>
        </div>
        <!--内容-->
        <div class="row J_mainContent" id="content-main">
            <iframe frameborder="0" class="J_iframe" name="iframe0" width="100%" height="100%"
                    src="{:Url('index/welcome')}" data-id="welcome"></iframe>
        </div>
        <div class="footer">
            <div class="pull-right">&copy; 2019
                <a href="http://www.junlankeji.com/" target="_blank">君澜科技</a>
            </div>
        </div>
    </div>
    <!--右侧部分结束-->
</div>
<script src="{___PLUGINS__}/js/jquery.min.js"></script>
<script src="{___PLUGINS__}/bootstrap/bootstrap.min.js"></script>
<script src="{___PLUGINS__}/js/jquery.metisMenu.js"></script>
<script src="{___PLUGINS__}/js/jquery.slimscroll.min.js"></script>
<script src="{___PLUGINS__}/js/contabs.js"></script>
<script src="{___PLUGINS__}/js//pace.min.js"></script>
<script src="{___PLUGINS__}/js/h.ui.js"></script>
<script src="{___PLUGINS__}/layer/layer.min.js"></script>
</body>
</html>

