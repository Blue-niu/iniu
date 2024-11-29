<?php
// 获取用户输入的数据并进行处理
$username = htmlspecialchars($_POST['username']);
$security_question = htmlspecialchars($_POST['security_question']);
$security_answer = htmlspecialchars($_POST['security_answer']);
$new_password = htmlspecialchars($_POST['new_password']);

// 数据库连接配置
$servername = "localhost";
$db_username = "root";
$db_password = "1234";
$database = "blog_database";

// 检查请求方法是否为POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 检查用户名、密保问题、密保答案是否为空
    if (empty($username) || empty($security_question) || empty($security_answer) || empty($new_password)) {
        echo "<script>
                alert('所有字段都必须填写');
                window.location.href = 'ForgotPassword.html';  // 重新定向到表单页面
              </script>";
        exit();
    }

    // 创建数据库连接
    $conn = new mysqli($servername, $db_username, $db_password, $database);

    // 检查数据库连接
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }

    // 使用查询检查用户名、密保问题及答案是否正确
    $sql = "SELECT * FROM user WHERE username = ? AND security_question = ? AND security_answer = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $security_question, $security_answer);
    $stmt->execute();
    $result = $stmt->get_result();

    // 如果找到匹配的记录，则允许用户重置密码
    if ($result && $result->num_rows > 0) {
        // 更新密码，直接使用明文密码
        $update_sql = "UPDATE user SET password = ? WHERE username = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ss", $new_password, $username);
        
        if ($update_stmt->execute()) {
            echo "<script>
                    alert('密码重置成功！');
                    window.location.href = '../html/login.html'; // 重定向到登录页面
                  </script>";
        } else {
            echo "<script>
                    alert('密码重置失败，请稍后再试！');
                    window.location.href = '../html/ForgotPassword.html';  // 重新定向到重置页面
                  </script>";
        }
    } else {
        echo "<script>
                alert('用户名或密保问题/答案不正确');
                window.location.href = '../html/ForgotPassword.html';  // 重新定向到表单页面
              </script>";
    }

    // 关闭数据库连接
    $stmt->close();
    $conn->close();
}
?>
