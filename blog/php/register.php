<?php
// 获取并清理用户输入
function cleanInput($data) {
    return htmlspecialchars(trim($data));
}

// 数据库连接配置
$servername = "localhost";
$username = "root";
$password_db = "1234";
$database = "blog_database";

// 获取用户输入并清理
$text_name = cleanInput($_POST["username"]);
$text_password = cleanInput($_POST["password"]);
$security_question = cleanInput($_POST["security_question"]);
$security_answer = cleanInput($_POST["security_answer"]);

// 检查请求方法是否为POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 检查必填字段
    if (empty($text_name) || empty($text_password)) {
        echo "<script>alert('用户名或密码不能为空');
         window.location.href = '../html/register.html';</script>";
        exit();
    }

    // 检查用户名是否合法
    if (!preg_match("/^[a-zA-Z_]*$/", $text_name)) {
        echo "<script>alert('用户名包含非法字符，请重新输入');
         window.location.href = '../html/register.html';</script>";
        exit();
    }

    // 检查密码强度
    if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+[\]{}|;:,.<>?]).{8,}$/", $text_password)) {
        echo "<script>alert('密码不符合要求！必须包含小写字母、大写字母、数字、特殊字符，且长度至少为8位。'); 
        window.location.href = '../html/register.html';</script>";
        exit();
    }
}

// 创建数据库连接
$conn = new mysqli($servername, $username, $password_db, $database);

// 检查数据库连接是否成功
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 检查用户名是否已存在
$sql_username = "SELECT username FROM user WHERE username = ?";
$stmt = $conn->prepare($sql_username);
$stmt->bind_param("s", $text_name);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    // 用户名已存在
    echo "<script>alert('用户名已存在，请重新输入'); window.location.href = '../html/register.html';</script>";
    $stmt->close();
    $conn->close();
    exit();
} else {
    // 用户名不存在，进行注册
    $current_time = date("Y-m-d H:i:s");

    // 插入新用户，密码以明文形式存储
    $sql_user = "INSERT INTO user (username, password, loginTime, security_question, security_answer) 
                 VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_user);
    $stmt_insert->bind_param("sssss", $text_name, $text_password, $current_time, $security_question, $security_answer);

    if ($stmt_insert->execute()) {
        // 注册成功
        echo "<script>alert('注册成功，请登录！'); 
        window.location.href = '../html/login.html';</script>";
    } else {
        // 注册失败
        echo "<script>alert('注册失败，请稍后再试！'); 
        window.location.href = '../html/register.html';</script>";
    }

    $stmt_insert->close();
}

// 关闭数据库连接
$conn->close();
?>
