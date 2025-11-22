<?php
session_start();
require_once "connect.php";

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm'];

    if ($password !== $confirm) {
        $error = 'Mật khẩu không trùng khớp!';
    } else {
        // Kiểm tra tên đã tồn tại chưa
        $stmt = $conn->prepare("SELECT * FROM users WHERE name=? LIMIT 1");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = 'Tên đăng nhập đã tồn tại!';
        } else {
            $stmt = $conn->prepare("INSERT INTO users (name, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $password);

            if ($stmt->execute()) {
                $success = 'Đăng ký thành công!';  // ✅ Bỏ "Đăng nhập ngay"
            } else {
                $error = 'Lỗi server khi tạo tài khoản!';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký</title>
    <link rel="stylesheet" href="register.css">
</head>
<body>

<h2>Đăng ký</h2>

<?php if($error): ?>
    <p class="error"><?= $error ?></p>
<?php endif; ?>

<?php if($success): ?>
    <p class="success"><?= $success ?></p>
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
