<?php 
session_start(); 
require_once __DIR__ . "/connect.php";    

// Kiểm tra đăng nhập
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}  

// Xử lý xóa nếu có yêu cầu
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $conn->query("DELETE FROM menu WHERE ProductID = $delete_id");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}  

// Lấy dữ liệu từ bảng menu
$sql = "SELECT * FROM menu";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm - Tết 🎉</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="top-bar">
    Xin chào, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong> | 
    <a href="logout.php">Đăng xuất</a>
</div>

<h2>Danh sách sản phẩm - Tết 🎉</h2>

<div class="actions">
    <a href="add.php" class="action-btn">Thêm món</a>
    <a href="orders.php" class="action-btn">Đặt hàng</a>
    <a href="discount.php" class="action-btn">Mã giảm giá</a>
    <a href="order_stats.php" class="action-btn">Thống kê đơn hàng</a>
    <a href="activity_log.php" class="action-btn">Nhật kí hoạt động</a>
</div>

<table class="product-table">
    <tr>
        <th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Giá</th>
        <th>Size</th>
        <th>Topping</th>
        <th>Số lượng</th>
        <th>Chỉnh sửa</th>
    </tr>
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['ProductID'] ?></td>
                <td><?= htmlspecialchars($row['ProductName']) ?></td>
                <td><?= number_format($row['Price'], 0, ',', '.') ?>.00 VNĐ</td>
                <td><?= htmlspecialchars($row['Size']) ?></td>
                <td><?= htmlspecialchars($row['Topping']) ?></td>
                <td><?= $row['Quantity'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $row['ProductID'] ?>" class="edit-btn">Sửa</a>
                    <a href="?delete_id=<?= $row['ProductID'] ?>" class="delete-btn">Xóa</a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="7">Không có dữ liệu</td></tr>
    <?php endif; ?>
</table>

<script src="style.js"></script>
</body>
</html>

<?php $conn->close(); ?>
