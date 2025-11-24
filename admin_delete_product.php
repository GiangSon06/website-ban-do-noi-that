<?php
session_start();
include 'db.php';

// 1. Check quyền Admin (Bắt buộc)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
header("Location: login.php");
exit();
}

// 2. Kiểm tra xem có ID sản phẩm được gửi lên không
if (isset($_GET['id'])) {
// Ép kiểu sang số nguyên để chống hack SQL Injection
$id = (int)$_GET['id'];

// --- BƯỚC QUAN TRỌNG: Lấy đường dẫn ảnh để xóa file ảnh trước ---
$sql_get_img = "SELECT image_url FROM products WHERE id = $id";
$result = mysqli_query($conn, $sql_get_img);

if ($row = mysqli_fetch_assoc($result)) {
$image_path = $row['image_url'];

// Kiểm tra nếu file ảnh tồn tại trong thư mục thì xóa nó đi
if (!empty($image_path) && file_exists($image_path)) {
unlink($image_path); // Hàm unlink dùng để xóa file
}
}

// --- BƯỚC CUỐI: Xóa dòng dữ liệu trong Database ---
$sql_delete = "DELETE FROM products WHERE id = $id";

if (mysqli_query($conn, $sql_delete)) {
// Xóa xong thì quay về trang admin và báo thành công
echo "<script>
alert('Đã xóa sản phẩm thành công!');
window.location.href = 'admin.php';
  </script>";
} else {
echo "Lỗi xóa database: " . mysqli_error($conn);
}

} else {
// Nếu không có ID thì đá về admin
header("Location: admin.php");
}
?>