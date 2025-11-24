<?php
// Kiểm tra và bắt đầu session nếu chưa có
if (session_status() === PHP_SESSION_NONE) {
session_start();
}

// --- LOGIC TÍNH TỔNG SỐ LƯỢNG GIỎ HÀNG TỪ SESSION ---
$total_quantity = 0;
if (isset($_SESSION['cart'])) {
foreach ($_SESSION['cart'] as $item) {
$total_quantity += $item['quantity'];
}
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo isset($pageTitle) ? $pageTitle : 'Nội Thất SLAND'; ?></title>

<link rel="stylesheet" href="style.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<script>
// Kiểm tra biến session từ PHP và gán vào biến JS để dùng cho logic "Mua ngay"
var isLoggedIn = <?php echo isset($_SESSION['user_id']) ? 'true' : 'false'; ?>;
</script>
</head>
<body>
<header>
<nav class="navbar">
<div class="logo">
<a href="index.php" style="text-decoration:none; color:inherit; font-weight:bold; font-size:24px;">
NỘI THẤT SLAND
</a>
</div>

<ul class="menu">
<li><a href="index.php">Trang chủ</a></li>
<li class="dropdown"><a href="products.php">Sản phẩm</a></li>
<li><a href="promotion.php">Khuyến mãi</a></li>
<li><a href="about.php">Giới thiệu</a></li>
<li><a href="contact.php">Liên hệ</a></li>
</ul>

<div class="auth" style="display: flex; align-items: center; gap: 15px;">
<input type="text" id="search-box" placeholder="🔍 Tìm sản phẩm..." style="padding: 5px 10px; border-radius: 15px; border: 1px solid #ccc; font-size: 14px;" />

<?php if (isset($_SESSION['username'])): ?>
<div class="user-info" style="font-size: 14px; color: white;">
<span>Hi, <b><?php echo htmlspecialchars($_SESSION['username']); ?></b></span>
<a href="my_orders.php" title="Xem lịch sử mua hàng" 
style="color: #f1c40f; text-decoration: none; font-weight: bold; border: 1px solid #f1c40f; padding: 2px 8px; border-radius: 4px; transition: 0.3s;">
<i class="fas fa-file-invoice-dollar"></i> Đơn hàng
</a>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
<a href="admin.php" style="color: #ff4757; font-weight: bold; margin-left:5px;">[Admin]</a>
<?php endif; ?>

<a href="logout.php" style="color: #dfe6e9; margin-left: 5px; font-size: 12px; text-decoration: none;">(Thoát)</a>
</div>
<?php else: ?>
<div class="login-links" style="font-size: 14px;">
<a href="login.php" style="color: white; text-decoration: none;">Đăng nhập</a>
<span style="color:white">|</span>
<a href="register.php" style="color: white; text-decoration: none;">Đăng ký</a>
</div>
<?php endif; ?>

<div class="cart-icon" onclick="toggleCart()" style="cursor: pointer; position: relative; color: white; font-size: 20px;">
<i class="fas fa-shopping-cart"></i> <span id="cart-count" style="
background-color: #e74c3c;
color: white;
border-radius: 50%;
padding: 2px 6px;
font-size: 11px;
position: absolute;
top: -8px;
right: -10px;
font-weight: bold;
border: 1px solid white;
/* Ẩn nếu số lượng bằng 0 */
display: <?php echo ($total_quantity > 0) ? 'inline-block' : 'none'; ?>;
">
<?php echo $total_quantity; ?>
</span>
</div>
</div>
</nav>
</header>