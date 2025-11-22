<?php
session_start();
require_once "connect.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$success = '';
$error = '';

// Lấy danh sách món từ menu (dùng để hiển thị form)
$menu = $conn->query("SELECT * FROM menu");

// Lấy mã giảm giá
$discounts = $conn->query("SELECT * FROM discounts WHERE Quantity > Used");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['CustomerName'] ?? '');
    $phone = trim($_POST['Phone'] ?? '');
    $email = trim($_POST['Email'] ?? '');
    $address = trim($_POST['Address'] ?? '');
    $note = trim($_POST['Note'] ?? '');
    $discount_id = !empty($_POST['DiscountID']) ? intval($_POST['DiscountID']) : null;
    $total = floatval($_POST['TotalPrice'] ?? 0);
    $items = $_POST['qty'] ?? [];

    // kiểm tra chọn món (ít nhất 1 món có qty > 0)
    $hasItem = false;
    foreach ($items as $pid => $q) {
        if (intval($q) > 0) { $hasItem = true; break; }
    }

    if ($name && $phone && $email && $address && $total > 0 && $hasItem) {

        // xử lý mã giảm giá (nếu có)
        if ($discount_id) {
            $discount_res = $conn->query("SELECT * FROM discounts WHERE DiscountID={$discount_id} AND Quantity > Used");
            if ($discount_res && $discount_res->num_rows > 0) {
                $discount = $discount_res->fetch_assoc();
                $total = $total * (1 - $discount['DiscountPercent'] / 100);
                // trừ mã giảm giá
                $conn->query("UPDATE discounts SET Used = Used + 1, Quantity = Quantity - 1 WHERE DiscountID = {$discount_id}");
            } else {
                $error = "Mã giảm giá không hợp lệ hoặc đã hết!";
            }
        }

        if (!$error) {
            // tạo chuỗi tên món đã đặt
            $orderedNames = [];
            foreach ($items as $pid => $qty) {
                $pid = intval($pid);
                $qty = intval($qty);
                if ($qty <= 0) continue;

                // kiểm tra tồn kho
                $res = $conn->query("SELECT ProductName, Quantity FROM menu WHERE ProductID = {$pid}");
                if (!$res || $res->num_rows == 0) {
                    $error = "Món (ID: {$pid}) không tồn tại.";
                    break;
                }
                $row = $res->fetch_assoc();
                $stock = intval($row['Quantity']);
                if ($qty > $stock) {
                    $error = "Món \"{$row['ProductName']}\" không đủ số lượng (tồn: {$stock}).";
                    break;
                }

                $orderedNames[] = $row['ProductName'] . " x$qty";

                // trừ tồn kho ngay
                $conn->query("UPDATE menu SET Quantity = Quantity - {$qty} WHERE ProductID = {$pid}");
            }

            if (!$error) {
                $orderedNamesStr = implode(", ", $orderedNames);

                // lưu đơn hàng
                $stmt = $conn->prepare("
                    INSERT INTO orders (CustomerName, Phone, Email, Address, Note, OrderedItems, DiscountID, TotalPrice)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                if (!$stmt) {
                    $error = "Lỗi chuẩn bị câu lệnh: " . $conn->error;
                } else {
                    $stmt->bind_param("ssssssid", $name, $phone, $email, $address, $note, $orderedNamesStr, $discount_id, $total);
                    if ($stmt->execute()) {
                        $success = "Đặt hàng thành công! Tổng phải trả: " . number_format($total, 0, ",", ".") . " VNĐ";
                        $menu = $conn->query("SELECT * FROM menu");
                        $discounts = $conn->query("SELECT * FROM discounts WHERE Quantity > Used");
                    } else {
                        $error = "Lỗi khi lưu đơn hàng: " . $stmt->error;
                    }
                    $stmt->close();
                }
            }
        }

    } else {
        $error = "Vui lòng điền đầy đủ thông tin và chọn ít nhất 1 món!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt hàng - Tết 🎉</title>
    <link rel="stylesheet" href="order.css">
</head>
<body>

<div class="top-bar">
    Xin chào, <strong><?= htmlspecialchars($_SESSION['username']) ?></strong> |
    <a href="logout.php">Đăng xuất</a>
</div>

<h2>Đặt hàng mới</h2>

<?php if ($success): ?>
    <p class="alert success"><?= $success ?></p>
<?php elseif ($error): ?>
    <p class="alert error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="POST" class="order-form" id="orderForm">

    <h3>Chọn món ăn</h3>
    <div class="product-list">
        <?php while ($p = $menu->fetch_assoc()): ?>
            <div class="product-item">
                <label class="product-label">
                    <input type="checkbox" class="check" data-price="<?= $p['Price'] ?>" data-id="<?= $p['ProductID'] ?>">
                    <?= htmlspecialchars($p['ProductName']) ?> -
                    <?= number_format($p['Price'], 0, ",", ".") ?> VNĐ
                    <?php if (isset($p['Quantity'])): ?>
                        <span class="stock">(Tồn: <?= intval($p['Quantity']) ?>)</span>
                    <?php endif; ?>
                </label>
                <input type="number" class="qty" name="qty[<?= $p['ProductID'] ?>]" min="1" value="1" disabled>
            </div>
        <?php endwhile; ?>
    </div>

    <h3>Tổng tiền: <span id="total">0</span> VNĐ</h3>
    <input type="hidden" name="TotalPrice" id="TotalPrice">

    <hr>

    <input type="text" name="CustomerName" placeholder="Tên khách hàng" required>
    <input type="text" name="Phone" placeholder="Số điện thoại" required>
    <input type="email" name="Email" placeholder="Email" required>
    <input type="text" name="Address" placeholder="Địa chỉ" required>
    <textarea name="Note" placeholder="Ghi chú"></textarea>

    <select name="DiscountID">
        <option value="">-- Chọn mã giảm giá --</option>
        <?php while ($row = $discounts->fetch_assoc()): ?>
            <option value="<?= $row['DiscountID'] ?>">
                <?= htmlspecialchars($row['DiscountName']) ?> (<?= $row['DiscountPercent'] ?>%)
            </option>
        <?php endwhile; ?>
    </select>

    <button type="submit">Đặt hàng</button>
</form>

<a href="index.php" class="back-link">Quay về trang chủ</a>

<script src="order.js"></script>
</body>
</html>

<?php $conn->close(); ?>
