<?php
/*
 * File: php/connect.php
 * Kết nối đến cơ sở dữ liệu QLCF trên XAMPP
 */
$servername = "localhost";
$username = "root";        
$password = "";            
$dbname = "QLCF";          

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed (Kết nối thất bại): " . $conn->connect_error);
}

if (!$conn->set_charset("utf8mb4")) {
    printf("Error loading character set utf8mb4: %s\n", $conn->error);
    exit();
}
?>