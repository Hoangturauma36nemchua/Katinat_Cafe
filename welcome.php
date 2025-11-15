<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chào mừng</title>
</head>
<body>
    <h1>Đăng nhập thành công!</h1>
    <p>Chào mừng <strong><?= htmlspecialchars($username) ?></strong> đến với hệ thống quản lý bán cà phê.</p>
    <a href="logout.php">Đăng xuất</a>
</body>
</html>
