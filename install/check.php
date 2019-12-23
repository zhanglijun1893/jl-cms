<?php
extract($_POST);
if (empty($dbHost) || empty($dbPort) || empty($dbUser) || empty($dbPassword) || empty($dbName)) {
    return resJson(400,"参数不全");
}
$conn = @mysqli_connect($dbHost, $dbUser, $dbPassword,NULL,$dbPort);

if (mysqli_connect_errno($conn)){
    resJson(400,"数据库连接失败，请重新设定");
} else {
    $result = mysqli_query($conn,"select count(table_name) as c from information_schema.`TABLES` where table_schema='$dbName'");
    $result = $result->fetch_array();
    if($result['c'] > 0) {
        resJson(400,"数据库已经存在");
    }
}

function resJson($status=200,$message="操作成功",$data=[]) {
    echo json_encode([
        'status' => $status,
        'data' => $data,
        'message' => $message,
    ]);
    exit;
}


