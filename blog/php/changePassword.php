<?php
session_start();

// 检查用户是否登录
if (!isset($_SESSION['username'])) {
    echo "<script>
            alert('请先登录！');
            window.location.href = '../html/login.html';
          </script>";
    exit();
}

// 获取用户输入
$password1 = trim($_POST['password1']);
$password2 = trim($_POST['password2']);
$name = $_SESSION['username'];

// 验证输入是否为空
if (empty($password1) || empty($password2)) {
    echo "<script>
            alert('密码不能为空，请重新输入！');
            window.location.href = '../html/changePassword.html';
          </script>";
    exit();
}

// 验证两次密码是否一致
if ($password1 !== $password2) {
    echo "<script>
            alert('两次密码输入不一致，请重新输入！');
            window.location.href = '../html/changePassword.html';
          </script>";
    exit();
}

// 验证密码是否符合要求
if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+[\]{}|;:,.<>?]).{8,}$/", $password1)) {
    echo "<script>
            alert('密码不符合要求！必须包含小写字母、大写字母、数字、特殊字符，且长度至少为8位。');
            window.location.href = '../html/changePassword.html';
          </script>";
    exit();
}

// 数据库配置
$mysql_servername = "localhost";
$mysql_username = "root";
$mysql_password = "1234";
$mysql_db = "blog_database";

// 创建数据库连接
$conn = new mysqli($mysql_servername, $mysql_username, $mysql_password, $mysql_db);

// 检查数据库连接是否成功
if ($conn->connect_error) {
    die("数据库连接失败: " . $conn->connect_error);
}

// 验证当前用户是否存在
$sql_get_password = "SELECT password FROM user WHERE username = ?";
$stmt_get = $conn->prepare($sql_get_password);
$stmt_get->bind_param("s", $name);
$stmt_get->execute();
$result_get = $stmt_get->get_result();

if ($result_get && $row = $result_get->fetch_assoc()) {
    $old_password = $row['password'];
} else {
    echo "<script>
            alert('用户信息获取失败，请重新登录！');
            window.location.href = '../html/login.html';
          </script>";
    $stmt_get->close();
    $conn->close();
    exit();
}

// 更新密码
$sql_update_password = "UPDATE user SET password = ? WHERE username = ?";
$stmt_update = $conn->prepare($sql_update_password);
$stmt_update->bind_param("ss", $password1, $name);

if ($stmt_update->execute()) {
    echo "<script>
            alert('密码修改成功，请重新登录！');
            window.location.href = '../html/login.html';
          </script>";
} else {
    echo "<script>
            alert('密码修改失败，请稍后再试！');
            window.location.href = '../html/changePassword.html';
          </script>";
}

// 关闭连接
$stmt_get->close();
$stmt_update->close();
$conn->close();
?>
