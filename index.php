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
    $conn->query("DELETE FROM addproducts WHERE ProductID = $delete_id");
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Lấy dữ liệu từ bảng
$sql = "SELECT * FROM addproducts";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách sản phẩm - Tết 🎉</title>
    <!-- Link tới file CSS -->
    <link rel="stylesheet" href="index.css">
</head>
<body>

<!-- Header tên đăng nhập + logout sang phải -->
<div class="top-bar">
    Xin chào, <strong><?= $_SESSION['username'] ?></strong> |
    <a href="logout.php">Đăng xuất</a>
</div>

<h2>Danh sách sản phẩm - Tết 🎉</h2>

<!-- Nút hành động -->
<a href="add.php" class="action-btn">Thêm món</a>
<a href="thongkesp.php" class="action-btn">Thống kê sản phẩm</a>
<a href="thongke.php" class="action-btn">Thống kê doanh thu</a>
<br><br>

<!-- Bảng sản phẩm -->
<table border="1" cellpadding="5" cellspacing="0">
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
                <td><?= $row['ProductName'] ?></td>
                <td><?= number_format($row['Price'], 0, ',', '.') ?>.00 VNĐ</td>
                <td><?= $row['Size'] ?></td>
                <td><?= $row['Topping'] ?></td>
                <td><?= $row['Quantity'] ?></td>
                <td>
                    <a href="?delete_id=<?= $row['ProductID'] ?>" 
                       class="delete-btn"
                       onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">
                       Xóa
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="7">Không có dữ liệu</td></tr>
    <?php endif; ?>
</table>

<!-- Pháo nổ Tết rơi -->
<!-- Icon Tết rơi (lì xì, bánh chưng, hoa mai, pháo…) -->
<script>
    const icons = ['🧧', '🎍', '🎊', '🟩', '🧨', '🌸']; // danh sách icon Tết

    for(let i=0; i<50; i++){
        let icon = document.createElement('div');
        // chọn ngẫu nhiên 1 icon
        icon.textContent = icons[Math.floor(Math.random() * icons.length)];
        icon.style.position = 'absolute';
        icon.style.top = '-50px'; // bắt đầu từ trên cao
        icon.style.left = Math.random() * window.innerWidth + 'px';
        icon.style.fontSize = 15 + Math.random() * 25 + 'px';
        icon.style.opacity = 0.5 + Math.random() * 0.5;
        icon.style.animation = `fall ${3 + Math.random()*5}s linear infinite`;
        icon.style.pointerEvents = 'none';
        document.body.appendChild(icon);
    }

    const style = document.createElement('style');
    style.textContent = `
        @keyframes fall {
            0% { transform: translateY(0); }
            100% { transform: translateY(900px); }
        }
    `;
    document.head.appendChild(style);
</script>

</body>
</html>

<?php
$conn->close();
?>
