<?php include_once INSTALL_PATH."/step/header.php";?>
    <div class="container">
        <div class="header">
            <img src="./images/logo.gif" alt="logo"/>
            <p><?php echo $steps[$step];?></p>
        </div>

        <div class="content">
            <div class="pact">
                <div class="pact-txt">
                    <div id="installMessage" >正在准备安装 ...<br /></div>
                </div>
            </div>
        </div>

        <div class="start-btn">
            <a href="?step=2">开始安装</a>
        </div>
    </div>

    <!--消息-->
    <div class="alert-message" id="alertMessage"></div>

<script>
    let status = -1;
    let installMessage= document.getElementById('installMessage');
    let data = <?php echo json_encode($_POST);?>;
    window.onload=function(){
        console.log("a");
        reloads(status);
        /*
        console.log(data);
        installMessage.insertAdjacentHTML('afterend', "<li><span class=\"correct_span\">&radic;</span>创建数据表11，完成!<span style=\"float: right;\">2019-0909</span></li> ");
        installMessage.insertAdjacentHTML('afterend', "<li><span class=\"correct_span\">&radic;</span>创建数据表22，完成!<span style=\"float: right;\">2019-0909</span></li> ");*/
    };

    function reloads(status) {
        let ajax = new XMLHttpRequest();
        let data = "<?php echo $data;?>";
        let installMessage= document.getElementById('installMessage');
        let url = './index.php?step=install&status='+status;
        ajax.open("POST",url,true);
        ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        ajax.send(data);
        ajax.onreadystatechange = function() {
            if (ajax.readyState === 4 && ajax.status === 200) {
                let res = JSON.parse(ajax.responseText);
                if (res.status===400) {
                    msg(res.message);
                    return false;
                } else if (res.status===200) {
                    setTimeout('next()',2000);
                    return true;
                } else if(res.status<200) {
                    installMessage.insertAdjacentHTML('afterend', res.message);
                    reloads(res.status);
                }
            }
        };
    }
    function next(){
        window.location.href='<?php echo $_SERVER['PHP_SELF']; ?>?step=5';
    }
    //消息
    function msg(text) {
        let alertMessage= document.getElementById('alertMessage');
        let node = document.querySelector(".alert-message");
        alertMessage.innerHTML=text;
        node.classList.add('jl-show');
        setTimeout(function () {
            node.classList.remove('jl-show');
        }, 4000);
    }
</script>
<?php include_once INSTALL_PATH."/step/footer.php";?>