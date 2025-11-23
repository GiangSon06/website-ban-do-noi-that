<?php
session_start();
include 'db.php';

// Chặn nếu chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
header("Location: login.php");
exit();
}
$pageTitle = "Thanh toán";
include 'header.php';
?>

<div style="max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
<h2 style="text-align: center;">Thông tin thanh toán</h2>

<form id="checkoutForm" method="POST" action="process_order.php">
<input type="hidden" name="cart_data" id="cart_data">
<input type="hidden" name="total_money" id="total_money_input">

<div class="input-group">
<label>Họ và tên người nhận:</label>
<input type="text" name="fullname" required style="width: 100%; padding: 10px; margin-bottom: 10px;">
</div>

<div class="input-group">
<label>Số điện thoại:</label>
<input type="text" name="phone" required style="width: 100%; padding: 10px; margin-bottom: 10px;">
</div>

<div class="input-group">
<label>Địa chỉ giao hàng:</label>
<textarea name="address" required style="width: 100%; padding: 10px; margin-bottom: 10px; height: 80px;"></textarea>
</div>

<div class="input-group">
<label>Phương thức thanh toán:</label>
<select name="payment_method" style="width: 100%; padding: 10px; margin-bottom: 20px;">
<option value="cod">Thanh toán khi nhận hàng (COD)</option>
<option value="banking">Chuyển khoản ngân hàng</option>
</select>
</div>

<div style="margin-bottom: 20px; font-weight: bold; font-size: 18px;">
Tổng thanh toán: <span id="checkout_total" style="color: red;">0đ</span>
</div>

<button type="submit" style="width: 100%; padding: 15px; background: #bfa15f; color: white; border: none; font-weight: bold; cursor: pointer;">XÁC NHẬN ĐẶT HÀNG</button>
</form>
</div>

<script>
// Lấy dữ liệu từ LocalStorage đổ vào form trước khi gửi
document.addEventListener("DOMContentLoaded", function() {
let cart = JSON.parse(localStorage.getItem("MY_CART")) || [];

if(cart.length === 0) {
alert("Giỏ hàng trống!");
window.location.href = "index.php";
}

// Tính tổng tiền
let total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

// Gán dữ liệu vào các input ẩn để PHP nhận được
document.getElementById('cart_data').value = JSON.stringify(cart);
document.getElementById('total_money_input').value = total;

// Hiển thị tổng tiền
document.getElementById('checkout_total').innerText = total.toLocaleString('vi-VN') + 'đ';
});
</script>

<?php include 'footer.php'; ?>