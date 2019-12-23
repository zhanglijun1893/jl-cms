<?php include_once INSTALL_PATH."/step/header.php";?>
    <div class="container">
        <div class="header">
            <img src="./images/logo.gif" alt="logo"/>
            <p><?php echo $steps[$step];?></p>
        </div>

        <form id="jlForm" name="jlForm" action="index.php?step=4" method="post" onsubmit="return submitForm();">
        <div class="content">
            <table cellpadding="0" cellspacing="0" class="table_data">
                <tr>
                    <th width="25%" align="right">数据库主机：</th>
                    <td>
                        <input type="text" id="dbHost" name="dbHost" value="127.0.0.1">
                        <span>一般为127.0.0.1 或 localhost</span>
                    </td>
                </tr>
                <tr>
                    <th width="25%" align="right">数据库端口：</th>
                    <td>
                        <input type="text" id="dbPort" name="dbPort" value="3306">
                        <span>一般为3306</span>
                    </td>
                </tr>
                <tr>
                    <th width="25%" align="right">数据库账号：</th>
                    <td><input type="text" id="dbUser" name="dbUser" value="root"></td>
                </tr>
                <tr>
                    <th width="25%" align="right">数据库密码：</th>
                    <td><input type="password" id="dbPassword" name="dbPassword" value=""></td>
                </tr>
                <tr>
                    <th width="25%" align="right">数据库名称：</th>
                    <td><input type="text" id="dbName" name="dbName" value="jlcms"></td>
                </tr>
                <tr>
                    <th width="25%" align="right">数据库表前缀：</th>
                    <td><input type="text" id="dbPrefix" name="dbPrefix" value="jl_"></td>
                </tr>
            </table>
        </div>
        <div class="content-title">管理员信息</div>

        <div class="content">

            <table cellpadding="0" cellspacing="0" class="table_data">
                <tr>
                    <th width="25%" align="right">管理员帐号：</th>
                    <td><input type="text" id="username" name="username" value="admin"></td>
                </tr>
                <tr>
                    <th width="25%" align="right">管理员密码：</th>
                    <td><input type="password" id="password" name="password" value=""></td>
                </tr>

                <tr>
                    <th width="25%" align="right">确认密码：</th>
                    <td><input type="password" id="confirm_password" name="confirm_password"></td>
                </tr>
            </table>
        </div>

        <div class="start-btn p30">
            <a href="javascript:history.go(-1);">上一步</a>
            <button id="msgBtn">创建数据</button>
        </div>
        </form>
    </div>

    <!--消息-->
    <div class="alert-message" id="alertMessage"></div>

<script>
    function submitForm() {
        let dbHost= document.getElementById('dbHost');
        let dbPort= document.getElementById('dbPort');
        let dbUser= document.getElementById('dbUser');
        let dbPassword= document.getElementById('dbPassword');
        let dbName= document.getElementById('dbName');
        let dbPrefix= document.getElementById('dbPrefix');

        let username= document.getElementById('username');
        let password= document.getElementById('password');
        let confirm_password= document.getElementById('confirm_password');

        if (dbHost.value.length <=0) {
            err(dbHost,"数据库地址不能为空");
            return false;
        }
        if (dbPort.value.length <=0) {
            err(dbPort,"数据库端口不能为空");
            return false;
        }
        if (dbUser.value.length <=0) {
            err(dbUser,"数据库账号不能为空");
            return false;
        }
        if (dbPassword.value.length <=0) {
            err(dbPassword,"数据库密码不能为空");
            return false;
        }
        if (dbName.value.length <=0) {
            err(dbName,"数据库名称不能为空");
            return false;
        }
        if (dbPrefix.value.length <=0) {
            err(dbPrefix,"数据库表前缀不能为空");
            return false;
        }
        if (username.value.length <=0) {
            err(username,"管理员帐号不能为空");
            return false;
        }
        if (password.value.length < 6) {
            err(password,"管理员密码在6位以上");
            return false;
        }
        if (confirm_password.value !== password.value  ) {
            err(confirm_password,"两次密码不一致");
            return false;
        }
        let data = "dbHost="+dbHost.value+"&dbPort="+dbPort.value+"&dbUser="+dbUser.value+"&dbPassword="+dbPassword.value+"&dbName="+dbName.value;
        let url = "./index.php?step=testDB";
        post(data,url);
        return false;
    }
    function post(data,url) {
        document.getElementById("msgBtn").disabled=true;
        let ajax = new XMLHttpRequest();
        ajax.open("POST",url,true);
        ajax.setRequestHeader("Content-type","application/x-www-form-urlencoded");
        ajax.send(data);
        ajax.onreadystatechange = function() {
            if (ajax.readyState === 4 && ajax.status === 200) {
                let res = JSON.parse(ajax.responseText);
                if (res.status===200) {
                    document.getElementById("msgBtn").disabled=false;
                    document.jlForm.submit();
                    return true;
                } else {
                    msg(res.message);
                    document.getElementById("msgBtn").disabled=false;
                    return false;
                }
            }
        };
    }
    function err(el, txt) {
        if (el.value <=0) {
            el.focus();
            msg(txt);
        }
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