<?php
/*
 * File: php/logout.php
 * Xử lý đăng xuất và chuyển hướng
 */

// 1. Luôn bắt đầu session
session_start();

// 2. Xóa tất cả các biến session
$_SESSION = array();

// 3. Hủy hoàn toàn session
session_destroy();

// 4. Chuyển hướng người dùng về trang đăng nhập
header("Location: ../html/login.html");
exit;
?>