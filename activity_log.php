<?php
session_start();
require_once "connect.php";

// Nếu user chưa login, redirect về login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// --- Xử lý Check In ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'checkin') {
    $today = date('Y-m-d');
    $check = $conn->prepare("SELECT * FROM activity_log WHERE Username=? AND Date=?");
    $check->bind_param("ss", $username, $today);
    $check->execute();
    $res = $check->get_result();

    if ($res->num_rows === 0) {
        $stmt = $conn->prepare("INSERT INTO activity_log (Username, Role, TimeIn) VALUES (?, 'Nhân viên', NOW())");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->close();
    }
}

// --- Xử lý Check Out ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'checkout') {
    $today = date('Y-m-d');
    $stmt = $conn->prepare("UPDATE activity_log SET TimeOut=NOW() WHERE Username=? AND Date=?");
    $stmt->bind_param("ss", $username, $today);
    $stmt->execute();
    $stmt->close();
    session_destroy();
    header("Location: login.php");
    exit();
}

// --- Xử lý cập nhật Role ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_role'], $_POST['log_id'], $_POST['role'])) {
    $log_id = intval($_POST['log_id']);
    $new_role = $_POST['role'];
    $stmt = $conn->prepare("UPDATE activity_log SET Role=? WHERE LogID=?");
    $stmt->bind_param("si", $new_role, $log_id);
    $stmt->execute();
    $stmt->close();
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// --- Lấy dữ liệu nhật ký ---
$result = $conn->query("SELECT * FROM activity_log ORDER BY Date DESC, TimeIn DESC");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Nhật ký hoạt động</title>
<link rel="stylesheet" href="activity_log.css">
</head>
<body>

<div class="top-bar">
    Xin chào, <strong><?= htmlspecialchars($username) ?></strong>
    <form method="POST" style="display:inline;">
        <input type="hidden" name="action" value="checkout">
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</div>

<h2>Nhật ký hoạt động</h2>

<form method="POST">
    <input type="hidden" name="action" value="checkin">
    <button type="submit" class="checkin-btn">Check In</button>
</form>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Tên</th>
            <th>Chức vụ</th>
            <th>Ngày</th>
            <th>Giờ vào</th>
            <th>Giờ ra</th>
            <th>Hành động</th>
        </tr>
    </thead>
    <tbody>
    <?php $i=1; while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($row['Username']) ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="log_id" value="<?= $row['LogID'] ?>">
                    <select name="role">
                        <option value="Nhân viên" <?= $row['Role']=='Nhân viên'?'selected':'' ?>>Nhân viên</option>
                        <option value="Admin" <?= $row['Role']=='Admin'?'selected':'' ?>>Admin</option>
                    </select>
            </td>
            <td><?= $row['Date'] ?></td>
            <td><?= $row['TimeIn'] ?? '-' ?></td>
            <td><?= $row['TimeOut'] ?? '-' ?></td>
            <td>
                    <button type="submit" name="update_role">Cập nhật</button>
                </form>
            </td>
        </tr>
    <?php endwhile; ?>
    </tbody>
</table>

<!-- Link quay về trang chủ ở cuối trang -->
<div class="back-home">
    <a href="index.php">← Quay về trang chủ</a>
</div>

</body>
</html>

<?php $conn->close(); ?>
