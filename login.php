<?php
session_start();
require_once "connect.php"; // kết nối database

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        // Kiểm tra username
        $stmt = $conn->prepare("SELECT * FROM users WHERE name=? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && $password === $user['password']) { // nếu muốn bảo mật hơn, dùng password_hash
            $_SESSION['username'] = $user['name'];
            $_SESSION['role'] = $user['role'] ?? 'user';
            header("Location: index.php");
            exit();
        } else {
            $error = 'Sai tên đăng nhập hoặc mật khẩu!';
        }
    } else {
        $error = 'Vui lòng nhập đầy đủ thông tin!';
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng nhập</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="login-container">
    <h2>Đăng nhập</h2>

    <?php if($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Tên đăng nhập" required class="input-large">
        <input type="password" name="password" placeholder="Mật khẩu" required class="input-large">
        <button type="submit" class="btn-large">Đăng nhập</button>
    </form>

    <p class="register-link">Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
</div>
</body>
</html>
