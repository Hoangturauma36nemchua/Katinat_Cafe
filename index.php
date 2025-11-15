<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$host="localhost"; $dbname="quanly_caphe"; $user="root"; $pass="";
$conn = new mysqli($host,$user,$pass,$dbname);
if($conn->connect_error) die("Kết nối thất bại: ".$conn->connect_error);

$search = $_GET['search'] ?? '';
$stmt = $conn->prepare("SELECT * FROM SanPham WHERE TenSP LIKE ?");
$param = "%$search%";
$stmt->bind_param("s",$param);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
$products = [];
while($row=$result->fetch_assoc()){
    $row['ThanhTien']=$row['Gia']*$row['SoLuong'];
    $total+=$row['ThanhTien'];
    $products[]=$row;
}
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
</head>
<body>
<h1>Xin chào <?=htmlspecialchars($_SESSION['username'])?></h1>
<a href="logout.php">Đăng xuất</a> | <a href="add.php">Thêm sản phẩm</a>

<h2>Tìm kiếm sản phẩm</h2>
<form method="get" action="">
    <input type="text" name="search" placeholder="Tên sản phẩm" value="<?=htmlspecialchars($search)?>">
    <button type="submit">Tìm</button>
</form>

<h2>Danh sách sản phẩm</h2>
<table border="1" cellpadding="5" cellspacing="0">
<tr>
<th>ID</th><th>Tên</th><th>Giá</th><th>Số lượng</th><th>Thành tiền</th><th>Hành động</th>
</tr>
<?php foreach($products as $p): ?>
<tr>
<td><?=$p['id']?></td>
<td><?=htmlspecialchars($p['TenSP'])?></td>
<td><?=number_format($p['Gia'],2)?></td>
<td><?=$p['SoLuong']?></td>
<td><?=number_format($p['ThanhTien'],2)?></td>
<td><a href="edit.php?id=<?=$p['id']?>">Sửa</a></td>
</tr>
<?php endforeach;?>
</table>
<h3>Tổng tiền: <?=number_format($total,2)?> VNĐ</h3>
</body>
</html>
