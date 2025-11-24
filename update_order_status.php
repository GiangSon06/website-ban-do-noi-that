<?php
session_start();
include 'db.php';

// 1. Kiểm tra quyền Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
header("Location: login.php");
exit();
}

// 2. Kiểm tra dữ liệu gửi lên
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['order_id'])) {
$order_id = (int)$_POST['order_id'];
$new_status = mysqli_real_escape_string($conn, $_POST['status']);

// 3. Cập nhật vào Database
$sql = "UPDATE orders SET status = '$new_status' WHERE id = $order_id";

if (mysqli_query($conn, $sql)) {
// Thành công: Quay lại trang admin
echo "<script>
alert('Cập nhật trạng thái đơn hàng #$order_id thành công!');
window.location.href = 'admin.php#list-orders';
</script>";
} else {
echo "Lỗi: " . mysqli_error($conn);
}
} else {
// Nếu truy cập trực tiếp thì đá về admin
header("Location: admin.php");
}
?>