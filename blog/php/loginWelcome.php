<?php
session_start();

// 数据库配置
$servername = "localhost";
$username = "root";
$password_db = "1234";
$database = "blog_database";

// 检查请求方法
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo "<script>
            alert('非法请求！');
            window.location.href = 'login.html';
          </script>";
    exit();
}

// 获取并处理用户输入
$name = htmlspecialchars(trim($_POST["username"]));
$password = htmlspecialchars(trim($_POST["password"]));

// 验证输入是否为空
if (empty($name) || empty($password)) {
    echo "<script>
            alert('用户名或密码不能为空');
            window.location.href = 'login.html';
          </script>";
    exit();
}

// 验证用户名合法性
if (!preg_match("/^[a-zA-Z_]+$/", $name)) {
    echo "<script>
            alert('用户名包含非法字符，请重新输入');
            window.location.href = 'login.html';
          </script>";
    exit();
}

// 创建数据库连接
$conn = new mysqli($servername, $username, $password_db, $database);

// 检查连接是否成功
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

// 使用预处理语句查询用户信息
$sql = "SELECT password FROM user WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $name); // 绑定参数
$stmt->execute();
$result = $stmt->get_result();

// 检查查询结果
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // 验证密码（直接明文比对）
    if ($password === $row['password']) {
        // 设置会话
        $_SESSION['username'] = $name;

        // 更新用户登录时间
        $current_time = date("Y-m-d H:i:s");
        $update_sql = "UPDATE user SET loginTime = ? WHERE username = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $current_time, $name);
        $update_stmt->execute();

        echo "<script>
                alert('登录成功！');
                window.location.href = '../html/Main_interface.html';
              </script>";
        exit();
    } else {
        // 密码错误
        echo "<script>
                alert('密码错误，请重新登录！');
                window.location.href = '../html/login.html';
              </script>";
        exit();
    }
} else {
    // 用户名不存在
    echo "<script>
            alert('用户名不存在，请重新登录！');
            window.location.href = '../html/login.html';
          </script>";
    exit();
}

// 关闭连接
$stmt->close();
$conn->close();
?>
