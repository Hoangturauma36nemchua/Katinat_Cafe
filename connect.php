<?php
/*
 * File: php/connect.php
 * Kết nối MySQL bằng XAMPP
 */

$servername = "localhost";
$username   = "root";
$password   = ""; // XAMPP mặc định để trống mật khẩu
$dbname     = "katinat_coffe_shop"; // CHỈ dùng chữ thường và đúng tên DB

$conn = new mysqli($servername, $username, $password, $dbname);

// Nếu lỗi kết nối
if ($conn->connect_error) {
    die("❌ Kết nối thất bại: " . $conn->connect_error);
}

// Thiết lập tiếng Việt
$conn->query("SET NAMES utf8mb4");

?>
