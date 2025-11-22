<?php
session_start();
require_once __DIR__ . "/connect.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Lấy số liệu thống kê
$tong_sp = $conn->query("SELECT COUNT(*) AS total FROM addproducts")->fetch_assoc()['total'];
$tong_hang_ton = $conn->query("SELECT SUM(Quantity) AS total FROM addproducts")->fetch_assoc()['total'];
$tong_gia_tri_ton = $conn->query("SELECT SUM(Quantity * Price) AS total FROM addproducts")->fetch_assoc()['total'];
$ton_nhieu = $conn->query("SELECT ProductName, Quantity FROM addproducts ORDER BY Quantity DESC LIMIT 1")->fetch_assoc();
$ban_chay_nhat = $conn->query("SELECT ProductName, Sold FROM addproducts ORDER BY Sold DESC LIMIT 1")->fetch_assoc();
$hang_sap_het = $conn->query("SELECT ProductName, Quantity FROM addproducts WHERE Quantity < 10 ORDER BY Quantity ASC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Dashboard Thống kê - Tết 🎉</title>
<style>
body {
    font-family: Arial, sans-serif;
    background: linear-gradient(to bottom, #ff4d4d 0%, #ffd700 100%);
    padding: 20px;
    position: relative;
    overflow-x: hidden;
}
h2 {
    text-align: center;
    color: #fff;
    text-shadow: 2px 2px 4px #000;
    margin-bottom: 30px;
}
.dashboard {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
}
.card {
    background: rgba(255,255,255,0.9);
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0,0,0,0.3);
    padding: 20px;
    width: 220px;
    text-align: center;
}
.card h3 { margin-bottom: 10px; color: #c00; }
.card p { font-size: 16px; margin: 5px 0; }
ul { text-align: left; padding-left: 20px; margin: 5px 0; }
.back-btn {
    display: block;
    text-align: center;
    margin-top: 30px;
    background: #28a745;
    color: white;
    padding: 10px 15px;
    border-radius: 6px;
    text-decoration: none;
    width: 200px;
    margin-left:auto; margin-right:auto;
}
.back-btn:hover { background: #218838; }

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

<h2>📊 Dashboard Thống kê sản phẩm - Tết 🎉</h2>

<div class="dashboard">
    <div class="card">
        <h3>Tổng sản phẩm</h3>
        <p><?= $tong_sp ?></p>
    </div>
    <div class="card">
        <h3>Tổng hàng tồn</h3>
        <p><?= $tong_hang_ton ?></p>
    </div>
    <div class="card">
        <h3>Tổng giá trị tồn</h3>
        <p><?= number_format($tong_gia_tri_ton) ?> VNĐ</p>
    </div>
    <div class="card">
        <h3>Tồn kho nhiều nhất</h3>
        <p><?= $ton_nhieu['ProductName'] ?> (<?= $ton_nhieu['Quantity'] ?>)</p>
    </div>
    <div class="card">
        <h3>Bán chạy nhất</h3>
        <p><?= $ban_chay_nhat['ProductName'] ?> (<?= $ban_chay_nhat['Sold'] ?>)</p>
    </div>
    <div class="card">
        <h3>Sản phẩm sắp hết</h3>
        <?php if ($hang_sap_het->num_rows > 0): ?>
            <ul>
                <?php while ($row = $hang_sap_het->fetch_assoc()): ?>
                    <li><?= $row['ProductName'] ?> (<?= $row['Quantity'] ?>)</li>
                <?php endwhile; ?>
            </ul>
        <?php else: ?>
            <p>Không có</p>
        <?php endif; ?>
    </div>
</div>

<a href="index.php" class="back-btn">← Quay về trang chính</a>

<!-- Icon Tết rơi -->
<script>
const icons = ['🧧','🎍','🎊','🧨','🌸','🟩'];
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
