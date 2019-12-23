<?php include_once INSTALL_PATH."/step/header.php";?>
<body>
    <div class="container">
        <div class="header">
            <img src="./images/logo.gif" alt="logo"/>
            <p><?php echo $steps[$step];?></p>
        </div>
        <div class="content">
            <table cellpadding="0" cellspacing="0" class="table_list">
                <tr>
                    <th class="th1">环境检测</th>
                    <th class="th2">推荐配置</th>
                    <th class="th3">当前状态</th>
                </tr>
                <tr>
                    <td>WEB 服务器</td>
                    <td>Apache/Nginx/IIS</td>
                    <td><?php echo $_SERVER['SERVER_SOFTWARE']; ?></td>
                </tr>
                <tr>
                    <td>PHP 版本</td>
                    <td>PHP 7.0.0 及以上</td>
                    <td>
                        <?php if(phpversion() >= '7.0.0'){ ?>
                            <span><img alt="correct" src="images/correct.gif" /></span>
                        <?php }else{ $err++; ?>
                            <div class="error"><img alt="error" src="images/error.gif" />&nbsp;无法安装</div>
                        <?php }?>
                    </td>
                </tr>
                <tr>
                    <td>safe_mode</td>
                    <td>基础配置</td>
                    <td>
                        <?php if (ini_get('safe_mode')) { $err++; ?>
                            <div class="error"><img alt="error" src="images/error.gif" />&nbsp;无法安装</div>
                        <?php }else{ ?>
                            <span><img alt="correct" src="images/correct.gif" /></span>
                        <?php }?>
                    </td>
                </tr>
                <tr>
                    <td>GD库</td>
                    <td>必须开启</td>
                    <td>
                        <?php if (empty(gd_info()['GD Version'])) { $err++; ?>
                            <div class="error"><img alt="error" src="images/error.gif" />&nbsp;无法安装</div>
                        <?php }else{ ?>
                            <span><img alt="correct" src="images/correct.gif" /></span>
                        <?php }?>
                    </td>
                </tr>
                <tr>
                    <td>mysqli</td>
                    <td>必须开启</td>
                    <td>
                        <?php if (!function_exists('mysqli_connect')) { $err++; ?>
                            <div class="error"><img alt="error" src="images/error.gif" />&nbsp;无法安装</div>
                        <?php }else{ ?>
                            <span><img alt="correct" src="images/correct.gif" /></span>
                        <?php }?>
                    </td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" class="table_list">
                <tr>
                    <th class="th1">函数检测</th>
                    <th class="th2">推荐配置</th>
                    <th class="th3">当前状态</th>
                </tr>
                <tr>
                    <td>curl_init</td>
                    <td>必须扩展</td>
                    <td>
                        <?php if (!function_exists('curl_init')) { $err++; ?>
                            <div class="error"><img alt="error" src="images/error.gif" />&nbsp;无法安装</div>
                        <?php }else{ ?>
                            <span><img alt="correct" src="images/correct.gif" /></span>
                        <?php }?>
                    </td>
                </tr>
                <tr>
                    <td>file_put_contents</td>
                    <td>必须扩展</td>
                    <td>
                        <?php if (!function_exists('file_put_contents')) { $err++; ?>
                            <div class="error"><img alt="error" src="images/error.gif" />&nbsp;无法安装</div>
                        <?php }else{ ?>
                            <span><img alt="correct" src="images/correct.gif" /></span>
                        <?php }?>
                    </td>
                </tr>
            </table>
            <table cellpadding="0" cellspacing="0" class="table_list">
                <tr>
                    <th class="th1">目录、文件权限检查</th>
                    <th class="th2">推荐配置</th>
                    <th class="th3">当前状态</th>
                </tr>
                <?php
                foreach($folder as $dir){
                    $is_write = false;
                    $testDir = ROOT_PATH.$dir;
                    if (file_exists($testDir) && is_file($testDir)) {
                        $is_write = is_writable($testDir);
                        !empty($is_write) && $is_write = is_readable($testDir);
                    } else {
                        dir_create($testDir);
                        $is_write = testWrite($testDir);
                        !empty($is_write) && $is_write = is_readable($testDir);
                    }

                    if($is_write){
                        $w = '<img alt="correct" src="images/correct.gif" />';
                    }else{
                        $w = '<img alt="error" src="images/error.gif" />';
                        $err++;
                    }
                    ?>
                    <tr>
                        <td class="first"><?php echo $dir; ?></td>
                        <td>读写</td>
                        <td><?php echo $w; ?></td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
        <div class="start-btn p30">
            <a href="javascript:history.go(-1);">上一步</a>
            <?php if($err>0){?>
                <a onclick="alert('当前配置不满足JlCMS安装需求，无法继续安装！');">检测不过</a>
            <?php }else{?>
                <a href="?step=3">下一步</a>
            <?php }?>
        </div>
    </div>
</body>
</html>

