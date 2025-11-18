<?php
session_start();
require_once "connect.php"; // kết nối database

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    // Kiểm tra mật khẩu
    if ($password !== $confirm) {
        $error = 'Mật khẩu không trùng khớp!';
    } else {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // Chuẩn bị statement để tránh lỗi SQL Injection
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $username, $hash);

        if ($stmt->execute()) {
            // Nếu thành công, chuyển thẳng sang login
            header("Location: login.php");
            exit();
        } else {
            $error = 'Tên đăng nhập đã tồn tại hoặc lỗi server!';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; background: #f2f2f2; }
        h2 { text-align: center; }
        form { max-width: 300px; margin: auto; padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 0 10px #aaa; }
        input { width: 100%; padding: 8px; margin: 5px 0; }
        button { width: 100%; padding: 8px; margin-top: 10px; background: #007bff; color: #fff; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
        p { text-align: center; }
        a { color: #007bff; text-decoration: none; }
        a:hover { text-decoration: underline; }
        .error { color: red; text-align: center; }
    </style>
</head>
<body>

<h2>Đăng ký</h2>

<?php if($error): ?>
    <p class="error"><?= $error ?></p>
<?php endif; ?>

<form method="POST" action="">
    <input type="text" name="username" placeholder="Tên đăng nhập" required>
    <input type="password" name="password" placeholder="Mật khẩu" required>
    <input type="password" name="confirm" placeholder="Xác nhận mật khẩu" required>
    <button type="submit">Đăng ký</button>
</form>

<p><a href="login.php">Quay lại đăng nhập</a></p>
<p><a href="index.php">Trang chủ</a></p>

</body>
</html>
