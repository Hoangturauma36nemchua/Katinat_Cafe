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
    <title>Danh sách sản phẩm - Giáng Sinh 🎄</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #ff4d4d 0%, #fff 100%);
            margin: 0;
            padding: 20px;
            overflow-x: hidden;
        }
        h2 {
            text-align: center;
            color: #fff;
            text-shadow: 2px 2px 4px #000;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            background-color: #c00;
            padding: 10px 20px;
            border-radius: 10px;
        }
        a.add-btn, a.logout-btn {
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            color: #fff;
        }
        a.add-btn { background-color: #28a745; }
        a.add-btn:hover { background-color: #218838; }
        a.logout-btn { background-color: #dc3545; }
        a.logout-btn:hover { background-color: #c82333; }

        table {
            border-collapse: collapse;
            width: 90%;
            margin: 0 auto 50px auto;
            background-color: rgba(255,255,255,0.9);
        }
        table, th, td {
            border: 1px solid #c00;
            padding: 8px;
        }
        th {
            background-color: #c00;
            color: #fff;
        }
        td {
            text-align: center;
        }
        a.delete-btn {
            color: #fff;
            background-color: #ff4d4d;
            padding: 4px 8px;
            border-radius: 4px;
            text-decoration: none;
        }
        a.delete-btn:hover {
            background-color: #c00;
        }

        /* 🎄 Icon cây thông và 🎅 Santa + tuần lộc */
        .floating-icon {
            position: fixed;
            font-size: 2em;
            z-index: 9999;
            pointer-events: none; /* không chắn nút bấm */
        }
        .tree-icon {
            top: 20px;
            left: 20px;
            animation: tree-move 5s infinite alternate;
        }
        @keyframes tree-move {
            0% { transform: translateY(0); }
            100% { transform: translateY(10px); }
        }
        .santa-icon {
            bottom: 20px;
            left: -50px;
            font-size: 2.5em;
            animation: santa-move 15s linear infinite;
        }
        @keyframes santa-move {
            0% { left: -50px; }
            100% { left: 100%; }
        }

        /* Hiệu ứng tuyết rơi */
        .snowflake {
            position: absolute;
            top: -10px;
            width: 10px;
            height: 10px;
            background: #fff;
            border-radius: 50%;
            opacity: 0.8;
            animation: fall linear infinite;
        }
        @keyframes fall {
            0% { transform: translateY(0) translateX(0); opacity: 1; }
            100% { transform: translateY(800px) translateX(50px); opacity: 0; }
        }
    </style>
</head>
<body>

<!-- Cây thông và Santa icon -->
<div class="floating-icon tree-icon">🎄</div>
<div class="floating-icon santa-icon">🎅🦌🦌</div>

<div class="header">
    <h2>Danh sách sản phẩm - Giáng Sinh 🎄</h2>
    <div>
        Xin chào, <strong><?= $_SESSION['username'] ?></strong> |
        <a href="logout.php" class="logout-btn">Đăng xuất</a>
    </div>
</div>

<!-- Nút Thêm món và Thống kê doanh thu -->
<a href="add.php" class="add-btn">Thêm món</a>
<a href="thongke.php" class="add-btn">Thống kê doanh thu</a>
<br><br>

<table>
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
                <td><?= $row['Price'] ?></td>
                <td><?= $row['Size'] ?></td>
                <td><?= $row['Topping'] ?></td>
                <td><?= $row['Quantity'] ?></td>
                <td>
                    <a href="?delete_id=<?= $row['ProductID'] ?>" class="delete-btn" 
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

<!-- Snowflakes -->
<script>
    for(let i=0; i<50; i++){
        let snow = document.createElement('div');
        snow.classList.add('snowflake');
        snow.style.left = Math.random() * window.innerWidth + 'px';
        snow.style.animationDuration = 3 + Math.random()*5 + 's';
        snow.style.width = 5 + Math.random()*10 + 'px';
        snow.style.height = snow.style.width;
        document.body.appendChild(snow);
    }
</script>

</body>
</html>

<?php
$conn->close();
?>
