<?php
/*
 * File: php/edit.php (Phiên bản truyền thống, có HTML)
 */
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../html/login.html");
    exit();
}

require 'connect.php';
$product_id = '';
$productName = '';
$price = '';
$quantity = '';
$message = ''; 

// --- PHẦN 1: XỬ LÝ LOGIC (POST) ---
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['product_id'];
    $productName = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];

    $stmt = $conn->prepare("CALL UpdateProductDetails(?, ?, ?, ?)");
    $stmt->bind_param("isdi", $product_id, $productName, $price, $quantity);
    
    if ($stmt->execute()) {
        header("Location: ../html/index.html");
        exit();
    } else {
        $message = "Cập nhật thất bại. Vui lòng thử lại.";
    }
    $stmt->close();
}

// --- PHẦN 2: LẤY DỮ LIỆU (GET) ---
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $product_id = $_GET['id'];
        $stmt = $conn->prepare("SELECT ProductName, Price, QuantityInStock FROM AddProducts WHERE ProductID = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $productName = $row['ProductName'];
            $price = $row['Price'];
            $quantity = $row['QuantityInStock'];
        } else {
            $message = "Không tìm thấy sản phẩm.";
        }
        $stmt->close();
    } else {
        header("Location: ../html/index.html");
        exit;
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa sản phẩm - Katinat Cafe</title>
    <style>
        /* --- CSS Tuyết rơi --- */
        body { position: relative; overflow: hidden; }
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

        /* --- Giao diện (Nền đỏ, form trắng) --- */
        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            background: linear-gradient(135deg, #a7001e, #6a0013); /* Nền đỏ Noel */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            position: relative;
            background-color: #ffffff;
            padding: 2.5rem;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            width: 400px;
            text-align: center;
            z-index: 5;
        }
        h2 { font-size: 2rem; color: #333; margin-bottom: 2rem; font-weight: 600; }
        .form-group { margin-bottom: 1.5rem; text-align: left; }
        label { display: block; margin-bottom: 0.5rem; color: #555; font-weight: 500; }
        input { width: 100%; padding: 0.8rem; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button {
            width: 100%;
            padding: 0.9rem;
            border: none;
            border-radius: 5px;
            background-color: #ffc107; /* Màu vàng Gold */
            color: black;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
        }
        button:hover { background-color: #e0a800; }
        a { color: #f0f0f0; text-decoration: none; display: block; text-align: center; margin-top: 1.5rem; font-weight: 500; }
        .error-msg { background-color: #f8d7da; color: #721c24; padding: 0.75rem; border-radius: 5px; margin-bottom: 1rem; }
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
        <form method="post" action="edit.php">
            <h2>Sửa sản phẩm 🔔</h2>
            <?php if (!empty($message)): ?>
                <div class="error-msg"><?php echo $message; ?></div>
            <?php endif; ?>

            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
            <div class="form-group">
                <label for="product_name">Tên sản phẩm:</label>
                <input type="text" id="product_name" name="product_name" value="<?php echo htmlspecialchars($productName); ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Giá (VND):</label>
                <input type="number" id="price" name="price" min="0" value="<?php echo $price; ?>" required>
            </div>
            <div class="form-group">
                <label for="quantity">Số lượng trong kho:</label>
                <input type="number" id="quantity" name="quantity" min="0" value="<?php echo $quantity; ?>" required>
            </div>
            <div class="form-group">
                <button type="submit">Cập nhật sản phẩm</button>
            </div>
        </form>
        <a href="../html/index.html">Hủy & Quay về Dashboard</a>
    </div>
</body>
</html>