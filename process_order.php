<?php
session_start();
include 'db.php';
include 'header.php'; // Để giữ giao diện đẹp

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
// 1. Lấy dữ liệu từ Form Checkout
$user_id = $_SESSION['user_id'];
$fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
$phone = mysqli_real_escape_string($conn, $_POST['phone']);
$address = mysqli_real_escape_string($conn, $_POST['address']); // Địa chỉ đã ghép chuỗi từ JS
$payment_method = $_POST['payment_method'];
$total_money = $_POST['total_money'];

// Lấy giỏ hàng từ JSON gửi lên hoặc từ Session
// Ở đây mình ưu tiên lấy từ Session cho bảo mật
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

if (empty($cart)) {
die("Giỏ hàng trống!");
}

// 2. Lưu Đơn hàng vào bảng `orders`
$sql_order = "INSERT INTO orders (user_id, fullname, phone, address, total_money, payment_method, status, created_at) 
VALUES ('$user_id', '$fullname', '$phone', '$address', '$total_money', '$payment_method', 'Chờ xử lý', NOW())";

if (mysqli_query($conn, $sql_order)) {
$order_id = mysqli_insert_id($conn); // Lấy ID đơn hàng vừa tạo

// 3. Lưu chi tiết đơn hàng vào bảng `order_details`
foreach ($cart as $key_id => $item) {
// SỬA LỖI: Nếu trong $item không có 'id', ta lấy luôn $key_id làm ID sản phẩm
$product_id = isset($item['id']) ? $item['id'] : $key_id;

$price = $item['price'];
$quantity = $item['quantity'];

// Đảm bảo dữ liệu an toàn
$product_id = (int)$product_id;
$price = (float)$price;
$quantity = (int)$quantity;

$sql_detail = "INSERT INTO order_details (order_id, product_id, price, quantity) 
VALUES ('$order_id', '$product_id', '$price', '$quantity')";
mysqli_query($conn, $sql_detail);
}

// 4. Xóa giỏ hàng sau khi đặt thành công
unset($_SESSION['cart']);
echo "<script>localStorage.removeItem('MY_CART');</script>"; // Xóa cả localstorage

// --- PHẦN HIỂN THỊ THÔNG BÁO THÀNH CÔNG ---
?>
<div style="max-width: 600px; margin: 50px auto; text-align: center; padding: 30px; border: 1px solid #ddd; border-radius: 8px; box-shadow: 0 5px 15px rgba(0,0,0,0.1);">

<i class="fas fa-check-circle" style="font-size: 60px; color: #27ae60; margin-bottom: 20px;"></i>
<h2 style="color: #2c3e50;">ĐẶT HÀNG THÀNH CÔNG!</h2>
<p>Mã đơn hàng của bạn: <strong>#<?php echo $order_id; ?></strong></p>
<p>Cảm ơn bạn đã tin tưởng Nội thất SLAND.</p>

<?php if ($payment_method == 'banking'): ?>

<div style="background: #f9f9f9; padding: 20px; border-radius: 8px; margin-top: 20px;">
<h3 style="color: #2980b9;">Thông tin chuyển khoản</h3>
<p>Vui lòng quét mã QR bên dưới để thanh toán:</p>

<img src="https://img.vietqr.io/image/MB-023004099999-compact2.png?amount=<?php echo $total_money; ?>&addInfo=THANHTOAN DON <?php echo $order_id; ?>" 
style="max-width: 300px; border: 2px solid #ccc; border-radius: 8px;">

<p style="margin-top: 10px; font-size: 14px; color: #555;">
Nội dung: <strong>THANHTOAN DON <?php echo $order_id; ?></strong><br>
Số tiền: <strong><?php echo number_format($total_money); ?>đ</strong>
</p>
</div>

<?php endif; ?>

<div style="margin-top: 30px;">
<a href="index.php" style="background: #bfa15f; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; font-weight: bold;">Tiếp tục mua sắm</a>
<a href="my_orders.php" style="margin-left: 10px; color: #555; text-decoration: none;">Xem đơn hàng</a>
</div>
</div>
<?php
} else {
echo "Lỗi hệ thống: " . mysqli_error($conn);
}
}
include 'footer.php';
?>