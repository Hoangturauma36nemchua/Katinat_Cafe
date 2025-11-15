<?php
/*
 * File: php/index.php (Back-end JSON cho Dashboard)
 */
session_start();
header('Content-Type: application/json'); 

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Chưa đăng nhập']);
    exit();
}

// 2. Kết nối CSDL
require 'connect.php';

// 3. Lấy dữ liệu sản phẩm
$sql = "SELECT * FROM AddProducts ORDER BY ProductID";
$result = $conn->query($sql);

$products = []; 
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row; 
    }
}
$conn->close();

// 4. Trả về dữ liệu
$data_to_return = [
    'username' => $_SESSION['username'],
    'products' => $products
];

echo json_encode($data_to_return);
?>