<?php
$servername = "localhost";
$username = "root";
$password = "1234";
$database="blog_database";

// 创建连接
$conn = new mysqli($servername, $username, $password,$database);

if($conn->connect_error){
    die("连接失败".$conn->connect_error);
}



//预处理绑定
$stmt=$conn->prepare("insert into user (username,password,loginTime) values(?,?,?)");
$stmt->bind_param("sss",$firstname,$password,$loginTime);
$firstname=htmlspecialchars($_POST['username']);
$password=htmlspecialchars($_POST['password']);
$loginTime = date("Y-m-d H:i:s");
$stmt->execute();
echo "插入成功";

$stmt->close();

$conn->close();
?>
