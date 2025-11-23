<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
$user_id = $_SESSION['user_id'];
$fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$address = mysqli_real_escape_string($conn, $_POST['address']);
$payment_method = $_POST['payment_method'];
$total_money = $_POST['total_money'];

// Lấy dữ liệu giỏ hàng (JSON string) và giải mã
$cart_json = $_POST['cart_data'];
$cart_items = json_decode($cart_json, true);

if (!empty($cart_items)) {
// 1. Lưu vào bảng ORDERS
$sql_order = "INSERT INTO orders (user_id, fullname, phone, address, payment_method, total_money) 
VALUES ('$user_id', '$fullname', '$phone', '$address', '$payment_method', '$total_money')";

if (mysqli_query($conn, $sql_order)) {
$order_id = mysqli_insert_id($conn); // Lấy ID đơn hàng vừa tạo

// 2. Lưu chi tiết vào bảng ORDER_DETAILS
foreach ($cart_items as $item) {
$pid = $item['id'];
$pname = mysqli_real_escape_string($conn, $item['name']);
$price = $item['price'];
$qty = $item['quantity'];

$sql_detail = "INSERT INTO order_details (order_id, product_id, product_name, price, quantity) 
VALUES ('$order_id', '$pid', '$pname', '$price', '$qty')";
mysqli_query($conn, $sql_detail);
}

// 3. Xóa giỏ hàng trong LocalStorage (bằng Javascript sau khi redirect)
echo "<script>
localStorage.removeItem('MY_CART');
alert('Đặt hàng thành công! Cảm ơn bạn.');
window.location.href = 'my_orders.php';
</script>";
} else {
echo "Lỗi: " . mysqli_error($conn);
}
} else {
echo "Giỏ hàng rỗng!";
}
} else {
header("Location: index.php");
}
?>