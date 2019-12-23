<?php
    include_once INSTALL_PATH."/step/header.php";
    $host = $_SERVER['HTTP_HOST'];
?>

    <div class="container">
        <div class="header">
            <img src="./images/logo.gif" alt="logo"/>
            <h1><?php echo $steps[$step];?></h1>
        </div>

        <div class="content">
            <div class="step5">
                <h1>JlCMS已经成功安装完成！</h1>
                <p>为了站点的安全，安装完成后即可将网站根目录下的“install”文件夹删除</p>
                <div class="act">
                    <a href="<?= 'http://'.$host;?>">进入前台</a>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="../admin/index">进入后台</a>
                </div>
            </div>
        </div>

    </div>
<?php include_once INSTALL_PATH."/step/footer.php";?>