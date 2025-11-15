<?php
session_start();
if (!isset($_SESSION['username'])) { header("Location: login.php"); exit(); }

$id=$_GET['id']??0;
$host="localhost"; $dbname="quanly_caphe"; $user="root"; $pass="";
$conn = new mysqli($host,$user,$pass,$dbname);
if($conn->connect_error) die("Kết nối thất bại: ".$conn->connect_error);

$message='';
if($_SERVER['REQUEST_METHOD']=='POST'){
    $ten=$_POST['tenSP']??'';
    $gia=$_POST['gia']??0;
    $sl=$_POST['soluong']??0;
    if($ten && $gia>0 && $sl>0){
        $stmt=$conn->prepare("UPDATE SanPham SET TenSP=?,Gia=?,SoLuong=? WHERE id=?");
        $stmt->bind_param("sdii",$ten,$gia,$sl,$id);
        $stmt->execute();
        $stmt->close();
        header("Location: index.php");
        exit();
    } else $message="Nhập đầy đủ thông tin hợp lệ!";
}

$stmt=$conn->prepare("SELECT * FROM SanPham WHERE id=?");
$stmt->bind_param("i",$id);
$stmt->execute();
$result=$stmt->get_result();
$product=$result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="vi">
<head><meta charset="UTF-8"><title>Sửa sản phẩm</title></head>
<body>
<h2>Sửa sản phẩm</h2>
<form method="post" action="">
<input type="text" name="tenSP" value="<?=htmlspecialchars($product['TenSP'])?>" required><br>
<input type="number" name="gia" step="0.01" value="<?=$product['Gia']?>" required><br>
<input type="number" name="soluong" value="<?=$product['SoLuong']?>" required><br>
<button type="submit">Cập nhật</button>
</form>
<?php if($message) echo "<p style='color:red;'>$message</p>"; ?>
<a href="index.php">Quay lại Dashboard</a>
</body>
</html>
