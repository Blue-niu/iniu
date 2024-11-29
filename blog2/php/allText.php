<?php
session_start();

// 获取当前用户的用户名
$name = $_SESSION['username'] ?? null; // 使用空合并运算符，确保$name不会为未定义

// 如果用户名未设置，返回错误
if (!$name) {
    echo json_encode([
        "status" => "error",
        "message" => "用户未登录"
    ]);
    exit();
}

// 数据库连接配置
$servername = "localhost";  // 数据库服务器
$username = "root";         // 数据库用户名
$password_db = "1234";      // 数据库密码
$database = "blog_database"; // 数据库名称

// 创建数据库连接
$conn = new mysqli($servername, $username, $password_db, $database);

// 检查数据库连接是否成功
if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "数据库连接失败: " . $conn->connect_error
    ]);
    exit();
}

// 初始化用户ID
$user_id = null;

// 使用预处理语句查询用户ID，防止SQL注入
$sql_user = "SELECT id FROM user WHERE username = ?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("s", $name); // 绑定用户名参数
$stmt->execute();
$result = $stmt->get_result();

// 如果用户存在，获取用户ID
if ($result && $row = $result->fetch_assoc()) {
    $user_id = (int)$row['id'];
}
$stmt->close(); // 关闭预处理语句

// 如果用户ID为空，返回错误
if (!$user_id) {
    echo json_encode([
        "status" => "error",
        "message" => "用户不存在或未找到"
    ]);
    $conn->close();
    exit();
}

// 查询用户的留言
$messages = []; // 存储留言的数组
$sql_text = "
    SELECT blog_content.content, user.username, blog_content.time 
    FROM blog_content
    JOIN user ON blog_content.user_id = user.id
    WHERE blog_content.user_id = ? 
    ORDER BY blog_content.time DESC
";
$stmt = $conn->prepare($sql_text);
$stmt->bind_param("i", $user_id); // 绑定用户ID参数
$stmt->execute();
$result2 = $stmt->get_result();

// 检查查询是否成功
if ($result2 === false) {
    echo json_encode([
        "status" => "error",
        "message" => "查询留言失败: " . $conn->error
    ]);
    $stmt->close();
    $conn->close();
    exit();
}

// 处理查询结果，将每条留言存入数组
while ($row = $result2->fetch_assoc()) {
    $messages[] = [
        "username" => htmlspecialchars($row["username"]), // 转义用户名，防止XSS
        "content" => htmlspecialchars($row["content"]),   // 转义留言内容，防止XSS
        "created_at" => $row["time"]                      // 留言时间
    ];
}

$stmt->close(); // 关闭预处理语句
$conn->close(); // 关闭数据库连接

// 返回 JSON 格式的留言数据
header('Content-Type: application/json');
echo json_encode([
    "status" => "success",
    "messages" => $messages
]);
?>
