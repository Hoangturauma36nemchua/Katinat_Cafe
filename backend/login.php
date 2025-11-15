<?php
/*
 * File: php/login.php (Back-end JSON cho Login)
 */
session_start();
header('Content-Type: application/json');

$response = ['success' => false];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'connect.php'; 
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Gọi Stored Procedure 'UserLogin'
    $stmt = $conn->prepare("CALL UserLogin(?, ?)");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    if ($row && $row['Message'] == 'Đăng nhập thành công') {
        $response['success'] = true;
        $_SESSION['username'] = $username; // Lưu session
    } else {
        $response['message'] = $row['Message'] ?: 'Tên người dùng hoặc mật khẩu không đúng';
    }
    
    $stmt->close();
    $conn->close();
} else {
    $response['message'] = 'Phương thức không hợp lệ';
}

echo json_encode($response);
?>