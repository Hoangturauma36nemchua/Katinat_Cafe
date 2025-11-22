<?php
session_start();
require_once "connect.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Xử lý POST khi cập nhật từng dòng
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_id'])) {
    $id = intval($_POST['update_id']);
    $name = $_POST['ProductName'] ?? '';
    $price = floatval($_POST['Price'] ?? 0);
    $size = $_POST['Size'] ?? '';
    $topping = $_POST['Topping'] ?? '';
    $quantity = intval($_POST['Quantity'] ?? 0);

    // Update cả menu và products
    $stmt1 = $conn->prepare("UPDATE menu SET ProductName=?, Price=?, Size=?, Topping=?, Quantity=? WHERE ProductID=?");
    $stmt1->bind_param("sdssii", $name, $price, $size, $topping, $quantity, $id);
    $stmt1->execute();

    $stmt2 = $conn->prepare("UPDATE products SET ProductName=?, Price=?, Size=?, Topping=?, Quantity=? WHERE ProductID=?");
    $stmt2->bind_param("sdssii", $name, $price, $size, $topping, $quantity, $id);
    $stmt2->execute();

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
    <title>Chỉnh sửa sản phẩm - Tết 🎉</title>
    <link rel="stylesheet" href="edit.css">
</head>
<body>

<div class="top-bar">
    Xin chào, <strong><?= $_SESSION['username'] ?></strong> | 
    <a href="logout.php">Đăng xuất</a>
</div>

<h2>Chỉnh sửa sản phẩm</h2>

<table class="product-table">
    <tr>
        <th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Giá</th>
        <th>Size</th>
        <th>Topping</th>
        <th>Số lượng</th>
        <th>Cập nhật</th>
    </tr>
    <?php if ($result && $result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <form method="POST">
                <tr>
                    <td><?= $row['ProductID'] ?></td>
                    <td><input type="text" name="ProductName" value="<?= htmlspecialchars($row['ProductName']) ?>" required></td>
                    <td><input type="number" name="Price" value="<?= $row['Price'] ?>" required></td>
                    <td>
                        <select name="Size">
                            <option value="S" <?= $row['Size']=='S'?'selected':'' ?>>S</option>
                            <option value="M" <?= $row['Size']=='M'?'selected':'' ?>>M</option>
                            <option value="L" <?= $row['Size']=='L'?'selected':'' ?>>L</option>
                        </select>
                    </td>
                    <td><input type="text" name="Topping" value="<?= htmlspecialchars($row['Topping']) ?>"></td>
                    <td><input type="number" name="Quantity" value="<?= $row['Quantity'] ?>" min="0" required></td>
                    <td>
                        <input type="hidden" name="update_id" value="<?= $row['ProductID'] ?>">
                        <button type="submit" class="update-btn">Cập nhật</button>
                    </td>
                </tr>
            </form>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="7">Không có dữ liệu</td></tr>
    <?php endif; ?>
</table>

<a href="index.php" class="back-link">Quay về trang chủ</a>

</body>
</html>

<?php $conn->close(); ?>
