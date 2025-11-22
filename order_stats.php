<?php
session_start();
require_once "connect.php";

$success = '';
$error = '';

// Thêm khách mới
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['CustomerName'] ?? '');
    $phone = trim($_POST['Phone'] ?? '');
    if ($name && $phone) {
        $stmt = $conn->prepare("INSERT INTO order_stats (CustomerName, Phone) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $phone);
        if ($stmt->execute()) {
            $success = "Thêm khách hàng thành công!";
        } else {
            $error = "Lỗi thêm khách: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $error = "Vui lòng nhập đầy đủ tên và số điện thoại!";
    }
}

// Cập nhật trạng thái
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['stat_id'], $_POST['status']) && (!isset($_POST['action']))) {
    $stat_id = intval($_POST['stat_id']);
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE order_stats SET Status = ? WHERE StatID = ?");
    $stmt->bind_param("si", $status, $stat_id);
    $stmt->execute();
    $stmt->close();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Lấy dữ liệu stats
$result = $conn->query("SELECT * FROM order_stats ORDER BY StatID DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Order Stats</title>
    <link rel="stylesheet" href="order_stats.css">
</head>
<body>

<div class="container">

    <h2>Quản lý Order Stats</h2>

    <?php if ($success): ?>
        <p class="alert success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>
    <?php if ($error): ?>
        <p class="alert error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- Form thêm khách mới -->
    <h3>Thêm khách hàng mới</h3>
    <form method="POST" class="add-form">
        <input type="hidden" name="action" value="add">
        <input type="text" name="CustomerName" placeholder="Tên khách" required>
        <input type="text" name="Phone" placeholder="Số điện thoại" required>
        <button type="submit">Thêm khách</button>
    </form>

    <hr>

    <!-- Bảng Order Stats -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Tên khách</th>
                <th>Số điện thoại</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['StatID'] ?></td>
                    <td><?= htmlspecialchars($row['CustomerName']) ?></td>
                    <td><?= htmlspecialchars($row['Phone']) ?></td>
                    <td>
                        <form method="POST" class="status-form">
                            <input type="hidden" name="stat_id" value="<?= $row['StatID'] ?>">
                            <select name="status">
                                <option value="Đang chờ" <?= $row['Status']=='Đang chờ'?'selected':'' ?>>Đang chờ</option>
                                <option value="Đang xử lý" <?= $row['Status']=='Đang xử lý'?'selected':'' ?>>Đang xử lý</option>
                                <option value="Hủy" <?= $row['Status']=='Hủy'?'selected':'' ?>>Hủy</option>
                            </select>
                    </td>
                    <td>
                            <button type="submit">Cập nhật</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- Link quay về trang chủ -->
    <div class="back-home">
        <a href="index.php">← Quay về trang chủ</a>
    </div>

</div>

</body>
</html>

<?php $conn->close(); ?>
