<?php
session_start();

// 验证用户是否已登录
if (!isset($_SESSION['username'])) {
    echo "<script>
            alert('请先登录！');
            window.location.href = '../html/login.html';
          </script>";
    exit();
}

// 数据库配置
$servername = "localhost";
$username = "root";
$password_db = "1234";
$database = "blog_database";

// 获取用户输入并处理
$text = trim($_POST["text"]);
$name = $_SESSION['username'];

// 验证输入是否为空
if (empty($text)) {
    echo "<script>
            alert('内容不能为空！');
            window.location.href = '../html/Main_interface.html';
          </script>";
    exit();
}

// 创建数据库连接
$conn = new mysqli($servername, $username, $password_db, $database);

// 检查数据库连接是否成功
if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}

// 使用预处理语句获取用户ID
$sql_id = "SELECT id FROM user WHERE username = ?";
$stmt_id = $conn->prepare($sql_id);
$stmt_id->bind_param("s", $name);
$stmt_id->execute();
$result_id = $stmt_id->get_result();

if ($result_id && $row = $result_id->fetch_assoc()) {
    $user_id = (int)$row['id'];
} else {
    echo "<script>
            alert('用户信息获取失败，请重试！');
            window.location.href = '../html/login.html';
          </script>";
    $stmt_id->close();
    $conn->close();
    exit();
}

// 使用预处理语句插入博客内容
$current_time = date("Y-m-d H:i:s");
$sql_text = "INSERT INTO blog_content (content, time, user_id) VALUES (?, ?, ?)";
$stmt_text = $conn->prepare($sql_text);
$stmt_text->bind_param("ssi", $text, $current_time, $user_id);

if ($stmt_text->execute()) {
    echo "<script>
            alert('发布成功！');
            window.location.href = '../html/Main_interface.html';
          </script>";
} else {
    echo "<script>
            alert('发布失败，请稍后再试！');
            window.location.href = '../html/Main_interface.html';
          </script>";
}

// 关闭连接
$stmt_id->close();
$stmt_text->close();
$conn->close();
?>
