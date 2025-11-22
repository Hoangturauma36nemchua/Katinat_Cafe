<?php
session_start();
require_once "connect.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Xử lý POST khi submit form
$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['tensanpham'] ?? '';
    $price = $_POST['gia'] ?? 0;
    $size = $_POST['size'] ?? '';
    $topping = $_POST['topping'] ?? '';
    $quantity = $_POST['soluong'] ?? 1;

    $price = floatval($price);
    $quantity = intval($quantity);

    // Thêm vào bảng products
    $stmt1 = $conn->prepare("INSERT INTO products (ProductName, Price, Size, Topping, Quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt1->bind_param("sdssi", $name, $price, $size, $topping, $quantity);

    // Thêm vào bảng menu
    $stmt2 = $conn->prepare("INSERT INTO menu (ProductName, Price, Size, Topping, Quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt2->bind_param("sdssi", $name, $price, $size, $topping, $quantity);

    if ($stmt1->execute() && $stmt2->execute()) {
        $success = true;
    } else {
        $error = $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm - Tết 🎉</title>
    <link rel="stylesheet" href="add.css">
</head>
<body>

<h2>Thêm sản phẩm mới</h2>

<?php if ($success): ?>
    <div class="alert success">
        Thêm sản phẩm <strong><?= htmlspecialchars($name) ?></strong> thành công! 
        <a href="index.php">Quay về trang chủ</a>
    </div>
<?php elseif ($error): ?>
    <div class="alert error">
        Lỗi: <?= htmlspecialchars($error) ?>
    </div>
<?php endif; ?>

<form method="POST" action="">
    <input type="text" name="tensanpham" placeholder="Tên sản phẩm" required>
    <input type="number" name="gia" placeholder="Giá" required>
    <select name="size" required>
        <option value="S">S</option>
        <option value="M" selected>M</option>
        <option value="L">L</option>
    </select>
    <input type="text" name="topping" placeholder="Topping">
    <input type="number" name="soluong" placeholder="Số lượng" min="0" value="1" required>
    <button type="submit">Thêm sản phẩm</button>
</form>

<a href="index.php" class="back-link">Quay về trang chủ</a>

</body>
</html>

<?php $conn->close(); ?>
