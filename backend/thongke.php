<?php
/*
 * File: php/thongke.php (Phiên bản truyền thống, có HTML)
 */
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../html/login.html");
    exit();
}

require 'connect.php';
$sql = "CALL GetSalesStatisticsReport()";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Báo cáo Thống kê - Katinat Cafe</title>
    <style>
        /* --- CSS Tuyết rơi --- */
        body { position: relative; } 
        .snow { position: absolute; top: -20px; background: white; border-radius: 50%; opacity: 0.8; animation: fall linear infinite; }
        @keyframes fall { 0% { transform: translateY(0); } 100% { transform: translateY(105vh); } }
        .snow:nth-child(1) { width: 5px; height: 5px; left: 10%; animation-duration: 10s; animation-delay: -3s; }
        .snow:nth-child(2) { width: 3px; height: 3px; left: 20%; animation-duration: 12s; animation-delay: -1s; }
        .snow:nth-child(3) { width: 6px; height: 6px; left: 30%; animation-duration: 8s;  animation-delay: -5s; }
        .snow:nth-child(4) { width: 4px; height: 4px; left: 40%; animation-duration: 15s; animation-delay: -7s; }
        .snow:nth-child(5) { width: 3px; height: 3px; left: 50%; animation-duration: 10s; animation-delay: -4s; }
        .snow:nth-child(6) { width: 5px; height: 5px; left: 60%; animation-duration: 9s;  animation-delay: -2s; }
        .snow:nth-child(7) { width: 2px; height: 2px; left: 70%; animation-duration: 13s; animation-delay: -6s; }
        .snow:nth-child(8) { width: 6px; height: 6px; left: 80%; animation-duration: 7s;  animation-delay: -1.5s; }
        .snow:nth-child(9) { width: 4px; height: 4px; left: 90%; animation-duration: 11s; animation-delay: -8s; }
        .snow:nth-child(10){ width: 3px; height: 3px; left: 95%; animation-duration: 14s; animation-delay: -4s; }
        .snow-container { position: fixed; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; z-index: 1; } 

        /* --- Hiệu ứng xe Tuần Lộc (Phải -> Trái) --- */
        .sleigh-animation {
            position: fixed;
            bottom: 20px; 
            font-size: 40px; 
            text-shadow: 0 2px 5px rgba(0,0,0,0.5);
            z-index: 10; 
            pointer-events: none;
            width: 600px; /* Độ dài đoàn xe */
            white-space: nowrap; /* Ngăn rớt dòng */
            animation: sleigh-ride-R-L 20s linear infinite;
        }
        @keyframes sleigh-ride-R-L {
            0% { transform: translateX(100vw); }
            100% { transform: translateX(-600px); }
        }
        
        /* --- Giao diện (Nền đỏ, bảng trắng) --- */
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            background: linear-gradient(135deg, #a7001e, #6a0013); /* Nền đỏ Noel */
            min-height: 100vh;
            margin: 0;
            padding: 20px 0; /* Padding cho container */
            overflow-x: hidden; 
        }
        .container {
            max-width: 1100px;
            margin: 0 auto;
            background: #fff; 
            padding: 25px; 
            border-radius: 8px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            position: relative;
            z-index: 5;
        }
        h1 { border-bottom: 3px solid #007bff; padding-bottom: 10px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #007bff; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        a.btn-back { 
            text-decoration: none; 
            background: #d90429; /* Nút quay về màu đỏ */
            color: white; 
            padding: 10px 15px; 
            border-radius: 5px; 
            margin-top: 20px; 
            display: inline-block; 
        }
    </style>
</head>
<body>
    <div class="sleigh-animation">✨🎄🎁🦌🦌🦌🛷🎁🎄✨</div>

    <div class="snow-container" aria-hidden="true">
        <div class="snow"></div> <div class="snow"></div> <div class="snow"></div>
        <div class="snow"></div> <div class="snow"></div> <div class="snow"></div>
        <div class="snow"></div> <div class="snow"></div> <div class="snow"></div>
        <div class="snow"></div>
    </div>

    <div class="container">
        <h1>Thống kê Doanh thu 📈</h1>
        <a href="../html/index.html" class="btn-back">Quay về Dashboard</a>

        <table>
            <thead>
                <tr>
                    <th>Tên Sản Phẩm</th>
                    <th>Tổng số lượng đã bán</th>
                    <th>Tổng doanh thu (VND)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result && $result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["ProductName"]) . "</td>";
                        echo "<td>" . $row["TotalQuantitySold"] . "</td>";
                        echo "<td>" . number_format($row["TotalRevenue"], 0, ',', '.') . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3' style='text-align: center;'>Chưa có dữ liệu thống kê.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>