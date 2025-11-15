<?php
$host = "localhost";
$user = "root";      // mặc định XAMPP không có password
$pass = "";
$db = "quanly_caphe";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

$conn->set_charset("utf8");

?>
