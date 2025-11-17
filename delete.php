<?php
/*
 * File: php/delete.php (Back-end JSON cho Xóa)
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

require 'connect.php';

// 2. Chỉ chạy khi có ID
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $product_id = $_GET['id'];

    // 3. Gọi Stored Procedure
    $stmt = $conn->prepare("CALL DeleteProductFromStore(?)");
    $stmt->bind_param("i", $product_id);
    
    if ($stmt->execute()) {
        $response['success'] = true;
    } else {
        $response['message'] = 'Lỗi khi xóa khỏi CSDL';
    }
    
    $stmt->close();
    $conn->close();

} else {
    $response['message'] = 'Không có ID sản phẩm';
}

echo json_encode($response);
?>