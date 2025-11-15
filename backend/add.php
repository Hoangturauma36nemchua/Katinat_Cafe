<?php
/*
 * File: php/add.php (Back-end JSON cho Thêm)
 */
session_start();
header('Content-Type: application/json');

$response = ['success' => false];

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    http_response_code(401);
    $response['message'] = 'Chưa đăng nhập';
    echo json_encode($response);
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require 'connect.php';
    $productName = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    // 3. Gọi Stored Procedure
    $stmt = $conn->prepare("CALL AddNewProduct(?, ?, ?)");
    $stmt->bind_param("sdi", $productName, $price, $quantity);
    
    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['message'] = 'Lỗi khi thêm vào CSDL';
    }
    
    $stmt->close();
    $conn->close();
} else {
    $response['message'] = 'Phương thức không hợp lệ';
}

echo json_encode($response);
?>