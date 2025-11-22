<?php
session_start();
require_once "connect.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Xử lý thêm mã giảm giá mới
$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_discount'])) {
    $name = $_POST['DiscountName'] ?? '';
    $percent = intval($_POST['DiscountPercent'] ?? 0);
    $quantity = intval($_POST['Quantity'] ?? 0);

    if ($name && $percent > 0 && $quantity >= 0) {
        $stmt = $conn->prepare("INSERT INTO discounts (DiscountName, DiscountPercent, Quantity, Used) VALUES (?, ?, ?, 0)");
        $stmt->bind_param("sii", $name, $percent, $quantity);
        if ($stmt->execute()) {
            $success = "Thêm mã giảm giá thành công!";
        } else {
            $error = "Lỗi: ".$stmt->error;
        }
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin hợp lệ!";
    }
}

// Xử lý xóa mã giảm giá
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM discounts WHERE DiscountID=$id");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Lấy dữ liệu hiện có
$result = $conn->query("SELECT * FROM discounts");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý mã giảm giá - Tết 🎉</title>
    <link rel="stylesheet" href="discount.css">
</head>
<body>

<div class="top-bar">
    Xin chào, <strong><?= $_SESSION['username'] ?></strong> | 
    <a href="logout.php">Đăng xuất</a>
</div>

<h2>Quản lý mã giảm giá</h2>
<a href="index.php" class="back-link">Quay về trang chủ</a>

<!-- Thêm mã mới -->
<form method="POST" class="add-form">
    <input type="text" name="DiscountName" placeholder="Tên mã giảm giá" required>
    <input type="number" name="DiscountPercent" placeholder="% giảm" min="1" max="100" required>
    <input type="number" name="Quantity" placeholder="Số lượng" min="0" required>
    <button type="submit" name="add_discount">Thêm mã</button>
</form>

<?php if ($success): ?>
    <p class="alert success"><?= $success ?></p>
<?php elseif ($error): ?>
    <p class="alert error"><?= $error ?></p>
<?php endif; ?>

<!-- Bảng danh sách mã giảm giá -->
<table class="discount-table">
    <tr>
        <th>ID</th>
        <th>Tên mã giảm giá</th>
        <th>% Giảm</th>
        <th>Số lượng</th>
        <th>Đã sử dụng</th>
        <th>Hành động</th>
    </tr>
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['DiscountID'] ?></td>
                <td><?= htmlspecialchars($row['DiscountName']) ?></td>
                <td><?= $row['DiscountPercent'] ?>%</td>
                <td><?= $row['Quantity'] ?></td>
                <td><?= $row['Used'] ?></td>
                <td>
                    <a href="?delete_id=<?= $row['DiscountID'] ?>" class="delete-btn"
                       onclick="return confirm('Bạn có chắc muốn xóa mã giảm giá này?')">Xóa</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="6">Chưa có mã giảm giá</td></tr>
    <?php endif; ?>
</table>

</body>
</html>

<?php $conn->close(); ?>
