<?php
session_start();

// Kiểm tra nếu có dữ liệu gửi lên
if (isset($_POST['id'])) {
$id = $_POST['id'];
$name = $_POST['name'];
$price = $_POST['price'];
$image = $_POST['image'];
$quantity = 1;

// Tạo giỏ hàng nếu chưa có
if (!isset($_SESSION['cart'])) {
$_SESSION['cart'] = array();
}

// Kiểm tra sản phẩm đã có trong giỏ chưa
if (isset($_SESSION['cart'][$id])) {
$_SESSION['cart'][$id]['quantity'] += 1; // Có rồi thì tăng số lượng
} else {
// Chưa có thì thêm mới
$_SESSION['cart'][$id] = array(
'name' => $name,
'price' => $price,
'image' => $image,
'quantity' => 1
);
}

// Tính tổng số lượng sản phẩm trong giỏ để trả về cho JS
$total_quantity = 0;
foreach ($_SESSION['cart'] as $item) {
$total_quantity += $item['quantity'];
}

echo $total_quantity; // Trả về con số mới (ví dụ: 1, 2, 5...)
}
?>