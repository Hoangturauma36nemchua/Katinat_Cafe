<?php
session_start();
require_once "connect.php"; // kết nối database

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Lấy thông tin user từ DB
    $stmt = $conn->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Kiểm tra mật khẩu
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        header("Location: index.php"); // quay về trang chủ
        exit();
    } else {
        $error = 'Sai tên đăng nhập hoặc mật khẩu!';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f2f2f2; }
        h2 { text-align: center; }
        form { max-width: 300px; margin: auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 0 10px #aaa; }
        input { width: 100%; padding: 8px; margin: 5px 0; }
        button { width: 100%; padding: 8px; margin-top: 10px; background: #28a745; color: #fff; border: none; cursor: pointer; }
        button:hover { background: #218838; }
        p { text-align: center; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>

<h2>Đăng nhập</h2>

<?php if($error): ?>
    <p class="error"><?= $error ?></p>
<?php endif; ?>

<form method="POST" action="">
    <input type="text" name="username" placeholder="Tên đăng nhập" required>
    <input type="password" name="password" placeholder="Mật khẩu" required>
    <button type="submit">Đăng nhập</button>
</form>

<p>Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
<p><a href="index.php">Trang chủ</a></p>

</body>
</html>
