<?php include_once INSTALL_PATH."/step/header.php";?>
    <div class="container">
        <div class="header">
            <img src="./images/logo.gif" alt="logo"/>
            <p><?php echo $steps[$step];?></p>
        </div>

        <div class="content">
            <div class="pact">
                <div class="pact-txt">
                    <p><?php echo format_textarea($license)?></p>
                </div>
            </div>
        </div>

        <div class="start-btn">
            <a href="?step=2">开始安装</a>
        </div>
    </div>
<?php include_once INSTALL_PATH."/step/footer.php";?>