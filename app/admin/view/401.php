<html>
    <header></header>
    <style>
        .middle-box {
            color: #676a6c;
            max-width: 400px;
            z-index: 100;
            margin: 0 auto;
            padding-top: 40px;
        }
        .middle-box h1 {
            font-size: 170px;
            font-weight: 100;
            margin: 0;
            padding: 0;
        }
        .font-bold {
            font-weight: 600;
        }
    </style>
<body>
<div class="middle-box text-center animated fadeInDown">
    <h1>401</h1>
    <h3 class="font-bold">页面没有权限访问！</h3>
    <div class="error-desc">
        抱歉，页面好像有权限访问~
        <?php var_dump($message); ?>
    </div>
</div>
</body>
</html>
