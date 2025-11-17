<?php
session_start();
header('Content-Type: application/json');

$response = ['success' => false];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    require_once __DIR__ . "/connect.php";

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmPassword'] ?? '';

    if(empty($username) || empty($password) || empty($confirmPassword)){
        $response['message'] = "Vui lòng điền đầy đủ thông tin";
        echo json_encode($response);
        exit();
    }

    if($password !== $confirmPassword){
        $response['message'] = "Mật khẩu nhập lại không khớp";
        echo json_encode($response);
        exit();
    }

    // Kiểm tra username tồn tại chưa
    $stmt = $conn->prepare("SELECT id FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows > 0){
        $response['message'] = "Username đã tồn tại";
        echo json_encode($response);
        exit();
    }
    $stmt->close();

    // Mã hóa password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Thêm user mới
    $stmt = $conn->prepare("INSERT INTO Users (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $hashed_password);
    if($stmt->execute()){
        $response['success'] = true;
    } else {
        $response['message'] = "Đăng ký thất bại, thử lại sau";
    }

    $stmt->close();
    $conn->close();

} else {
    $response['message'] = "Phương thức không hợp lệ - Hãy dùng POST";
}

echo json_encode($response);
?>
