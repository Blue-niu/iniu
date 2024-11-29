<?php
// 这个PHP还有问题，如果一个人和一个人发布留言的时间一样，就会出现bug
session_start();

$name = $_SESSION['username'];
$mysql_servername="localhost";
$mysql_username="root";
$mysql_password="1234";
$mysql_db="blog_database";
$Text=htmlspecialchars($_POST['text']);
//连接数据库
$conn=new mysqli($mysql_servername,$mysql_username,$mysql_password,$mysql_db);

if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
//通过前端传过来的数据接收
if (isset($_POST['created_at'])) {
    $created_at = $_POST['created_at']; // 获取留言的时间
}

//查询用户id
$sql = "SELECT id FROM user WHERE username = '$name'";
$result = $conn->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    $id = intval($row['id']);  // 将id转换为整数 
} 

// 查询user_id
$sql_user_id = "SELECT user_id FROM blog_content WHERE time = '$created_at'";
$result_user_id = $conn->query($sql_user_id);

if ($result_user_id && $row = $result_user_id->fetch_assoc()) {
    $user_id = intval($row['user_id']);  // 将user_id转换为整数
} 
if($id===$user_id){
    $sql_text="update blog_content set content='$Text' where user_id='$user_id' and time = '$created_at'";
    $result_sql_text=$conn->query($sql_text);
    if($result_sql_text){
        echo "<script>
        alert('修改成功');
        window.location.href = '../html/allText.html';
        </script>";
    }

}




?>