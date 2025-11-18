<?php
session_start();
require_once __DIR__ . "/connect.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Lấy danh sách sản phẩm và tính thống kê
$sql = "
    SELECT 
        p.ProductID, 
        p.ProductName, 
        p.Quantity AS QuantityLeft,
        IFNULL(SUM(o.Quantity), 0) AS QuantitySold,
        p.Price,
        IFNULL(SUM(o.Quantity * p.Price), 0) AS TotalRevenue
    FROM addproducts p
    LEFT JOIN addorders o ON p.ProductID = o.ProductID
    GROUP BY p.ProductID
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thống kê doanh thu - Tết 🎉</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #ff4d4d 0%, #ffd700 100%);
            margin: 0;
            padding: 20px;
            overflow-x: hidden;
            position: relative;
        }
        h2 {
            text-align: center;
            color: #fff;
            text-shadow: 2px 2px 4px #000;
        }
        table {
            border-collapse: collapse;
            width: 90%;
            margin: 20px auto 50px auto;
            background-color: rgba(255,255,255,0.9);
        }
        table, th, td {
            border: 1px solid #c00;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #c00;
            color: #fff;
        }
        a.back-btn {
            display: block;
            width: 150px;
            margin: 10px auto;
            text-align: center;
            padding: 8px 10px;
            background: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        a.back-btn:hover { background-color: #218838; }

        /* Icon Tết rơi */
        .tet-icon {
            position: absolute;
            top: -50px;
            font-size: 2em;
            z-index: 9999;
            pointer-events: none;
            animation-name: fall;
            animation-timing-function: linear;
            animation-iteration-count: infinite;
        }
        @keyframes fall {
            0% { transform: translateY(0); }
            100% { transform: translateY(800px); }
        }
    </style>
</head>
<body>

<h2>Thống kê doanh thu - Tết 🎉</h2>

<table>
    <tr>
        <th>ID</th>
        <th>Tên sản phẩm</th>
        <th>Đã bán</th>
        <th>Còn tồn</th>
        <th>Giá</th>
        <th>Tổng tiền bán được</th>
    </tr>

    <?php if($result && $result->num_rows > 0): ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['ProductID'] ?></td>
                <td><?= $row['ProductName'] ?></td>
                <td><?= $row['QuantitySold'] ?></td>
                <td><?= $row['QuantityLeft'] ?></td>
                <td><?= number_format($row['Price'],0,',','.') ?> đ</td>
                <td><?= number_format($row['TotalRevenue'],0,',','.') ?> đ</td>
            </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="6">Không có dữ liệu</td></tr>
    <?php endif; ?>
</table>

<a href="index.php" class="back-btn">Quay về trang chủ</a>

<!-- Icon Tết rơi -->
<script>
    const icons = ['🧧','🎍','🎊','🧨','🌸','🟩']; // lì xì, hoa mai, pháo, bánh chưng
    for(let i=0; i<50; i++){
        let icon = document.createElement('div');
        icon.textContent = icons[Math.floor(Math.random()*icons.length)];
        icon.classList.add('tet-icon');
        icon.style.left = Math.random() * window.innerWidth + 'px';
        icon.style.fontSize = (20 + Math.random()*25) + 'px';
        icon.style.opacity = 0.5 + Math.random()*0.5;
        icon.style.animationDuration = (3 + Math.random()*5) + 's';
        document.body.appendChild(icon);
    }
</script>

</body>
</html>

<?php $conn->close(); ?>
