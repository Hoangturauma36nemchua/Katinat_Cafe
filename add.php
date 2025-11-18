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

    $stmt = $conn->prepare("INSERT INTO addproducts (ProductName, Price, Size, Topping, Quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdssi", $name, $price, $size, $topping, $quantity);

    if ($stmt->execute()) {
        $success = true;
    } else {
        $error = $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm - Tết 🎉</title>
    <style>
        /* Nền Tết đỏ vàng */
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(to bottom, #ff4d4d, #ffd700);
            margin: 0;
            padding: 20px;
            overflow-x: hidden;
            position: relative;
        }

        h2 {
            text-align: center;
            color: #fff;
            text-shadow: 2px 2px 4px #000;
            margin-top: 0;
        }

        form {
            width: 300px;
            margin: 20px auto;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        input, select, button {
            padding: 8px;
            border-radius: 4px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        button {
            background-color: #28a745;
            color: #fff;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #218838;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 10px;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        /* Icon Tết rơi */
        .tet-icon {
            position: absolute;
            top: -50px; /* lên cao hơn */
            animation-name: fall;
            animation-timing-function: linear;
            animation-iteration-count: infinite;
        }

        @keyframes fall {
            0% {transform: translateY(0);}
            100% {transform: translateY(800px);}
        }

    </style>
</head>
<body>

<h2>Thêm sản phẩm mới</h2>

<?php if ($success): ?>
    <p style="color:green; text-align:center;">Thêm sản phẩm thành công! <a href="index.php">Quay về trang chủ</a></p>
<?php elseif ($error): ?>
    <p style="color:red; text-align:center;">Lỗi: <?= htmlspecialchars($error) ?></p>
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

<a href="index.php">Quay về trang chủ</a>

<!-- Icon Tết rơi -->
<script>
    const icons = ['🧧', '🎍', '🎊', '🟩', '🧨', '🌸']; // Lì xì, bánh chưng, hoa mai, pháo
    for(let i=0; i<50; i++){
        let icon = document.createElement('div');
        icon.textContent = icons[Math.floor(Math.random() * icons.length)];
        icon.classList.add('tet-icon');
        icon.style.left = Math.random() * window.innerWidth + 'px';
        icon.style.fontSize = (15 + Math.random()*25) + 'px';
        icon.style.opacity = 0.5 + Math.random()*0.5;
        icon.style.animationDuration = (3 + Math.random()*5) + 's';
        document.body.appendChild(icon);
    }
</script>

</body>
</html>

<?php $conn->close(); ?>
