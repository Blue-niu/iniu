<?php
// 数据库配置
$servername = "localhost";
$username = "root";
$password = "1234";
$database = "blog_database";

// 获取前端发送的用户名
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'])) {
    $input_username = htmlspecialchars($_POST['username']);

    // 检查用户名是否为空
    if (empty($input_username)) {
        echo "用户名不能为空！";
        exit();
    }

    // 连接数据库
    $conn = new mysqli($servername, $username, $password, $database);

    // 检查连接
    if ($conn->connect_error) {
        echo "数据库连接失败！";
        exit();
    }

    // 查询用户名是否存在
    $sql = "SELECT username FROM user WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $input_username);
    $stmt->execute();
    $result = $stmt->get_result();

    // 返回检查结果
    if ($result->num_rows > 0) {
        echo "用户名已存在，请换一个！";
    } else {
        echo "用户名可以使用！";
    }

    // 关闭连接
    $stmt->close();
    $conn->close();
} else {
    echo "无效请求！";
}
?>
