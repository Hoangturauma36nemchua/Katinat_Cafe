<?php
session_start();
if (!isset($_SESSION['username'])) { header("Location: login.php"); exit(); }

$host="localhost"; $dbname="quanly_caphe"; $user="root"; $pass="";
$conn = new mysqli($host,$user,$pass,$dbname);
if($conn->connect_error) die("Kết nối thất bại: ".$conn->connect_error);

$message='';
if($_SERVER['REQUEST_METHOD']=='POST'){
    $ten=$_POST['tenSP']??'';
    $gia=$_POST['gia']??0;
    $sl=$_POST['soluong']??0;
    if($ten && $gia>0 && $sl>0){
        $stmt=$conn->prepare("INSERT INTO SanPham (TenSP,Gia,SoLuong) VALUES (?,?,?)");
        $stmt->bind_param("sdi",$ten,$gia,$sl);
        $stmt->execute();
        $stmt->close();
        header("Location: index.php");
        exit();
    } else $message="Nhập đầy đủ thông tin hợp lệ!";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head><meta charset="UTF-8"><title>Thêm sản phẩm</title></head>
<body>
<h2>Thêm sản phẩm mới</h2>
<form method="post" action="">
<input type="text" name="tenSP" placeholder="Tên sản phẩm" required><br>
<input type="number" name="gia" step="0.01" placeholder="Giá" required><br>
<input type="number" name="soluong" placeholder="Số lượng" required><br>
<button type="submit">Thêm</button>
</form>
<?php if($message) echo "<p style='color:red;'>$message</p>"; ?>
<a href="index.php">Quay lại Dashboard</a>
</body>
</html>
