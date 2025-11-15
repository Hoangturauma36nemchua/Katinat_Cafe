<?php
$servername = "localhost";
$dbusername = "root";  // user MySQL mặc định
$dbpassword = "";      // password mặc định nếu chưa đổi
$dbname = "quanly_caphe";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}
?>
