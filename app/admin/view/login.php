<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <title>君澜科技 - 管理系统</title>
    <!--[if lt IE 9]><meta http-equiv="refresh" content="0;ie.html" /><![endif]-->
    <link rel="shortcut icon" href="{__STATIC__}/favicon.ico">
    <link href="{__STATIC__}/admin/css/login.css" rel="stylesheet">
    <script>
        if(window.top!==window.self){window.top.location=window.location};
    </script>
</head>
<body>

<div class="admin_login">
    <div class="admin_login_title">
        <strong>管理系统</strong>
        <em>君澜科技</em>
    </div>
    <form action="" method="post">
        <div class="admin_login_row">
            <input type="text" name="username" placeholder="用户名" required autofocus>
        </div>
        <div class="admin_login_row">
            <input type="password" name="password" required placeholder="密码">
        </div>
        <div class="admin_login_code">
            <div class="admin_login_row">
                <input type="text" required name="captcha"  placeholder="验证码">
            </div>
        </div>
        <div class="admin_login_verify_code">
            <img onclick="captchaImg(this)" src="{:Url('index/captcha')}" style="width:100%;height:100%;cursor:pointer;" alt="换一个" title="换一个">
        </div>
        <div class="admin_login_error clearFix">
            <span>{:$error}</span>
        </div>
        <div class="admin_login_btn">
            <button type="submit" class="submit_btn">登录</button>
        </div>
    </form>
    <div class="admin_login_copy">
        <div style="margin-top: 15px">
            <p>© 2019 <a href="http://www.junlankeji.com">君澜科技</a> 版权所有</p>
            <p>京B-1893-01</p>
        </div>
    </div>
</div>
<script type="text/javascript">
    function captchaImg(obj) {
        obj.src = obj.src+'?'+Date.parse(new Date());
    }
</script>
</body>
</html>
