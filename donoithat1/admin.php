<?php
session_start();
include 'db.php'; // Kết nối database

// Kiểm tra bảo mật Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
header("Location: login.php");
exit();
}

// 1. Lấy thống kê Đơn hàng
$res_orders_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders");
$orders_count = mysqli_fetch_assoc($res_orders_count)['total'];

// 2. Lấy thống kê Sản phẩm
$res_products_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
$products_count = mysqli_fetch_assoc($res_products_count)['total'];

// 3. Lấy thống kê Tin nhắn
$admin_id = $_SESSION['user_id'];
$sql_chat_count = "SELECT COUNT(DISTINCT sender_id) as total FROM messages WHERE sender_id != '$admin_id'";
$res_chat_count = mysqli_query($conn, $sql_chat_count);
$chat_count = ($res_chat_count) ? mysqli_fetch_assoc($res_chat_count)['total'] : 0;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - SLAND</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
body { margin: 0; font-family: sans-serif; display: flex; background-color: #f4f6f9; }

/* Sidebar Styles */
.sidebar { width: 250px; background: #2c3e50; color: white; height: 100vh; padding: 20px; position: fixed; }
.sidebar h3 { text-align: center; margin-bottom: 30px; color: #ecf0f1; border-bottom: 1px solid #34495e; padding-bottom: 10px; }
.sidebar a { display: flex; align-items: center; gap: 10px; color: #bdc3c7; text-decoration: none; padding: 15px; border-bottom: 1px solid #34495e; transition: 0.3s; }
.sidebar a:hover { background: #34495e; color: white; padding-left: 20px; }
.sidebar a.active { background: #bfa15f; color: white; }

.content { flex: 1; padding: 30px; margin-left: 290px; }

/* Stats Cards */
.stats-container { display: flex; gap: 20px; margin-bottom: 40px; }
.card { flex: 1; padding: 20px; background: white; border-radius: 8px; text-align: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1); transition: transform 0.3s; }
.card:hover { transform: translateY(-5px); }
.card h3 { margin: 0 0 10px; color: #7f8c8d; font-size: 16px; text-transform: uppercase; }
.card p { font-size: 36px; margin: 0; font-weight: bold; }
.card i { font-size: 40px; margin-bottom: 10px; opacity: 0.2; }

h2 { color: #2c3e50; border-left: 5px solid #bfa15f; padding-left: 10px; margin-top: 40px; }

/* Tables */
table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.1); margin-top: 15px; }
th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
th { background-color: #34495e; color: white; text-transform: uppercase; font-size: 12px; }
tr:hover { background-color: #f9f9f9; }

.btn-delete { color: white; background: #e74c3c; padding: 5px 10px; text-decoration: none; border-radius: 4px; font-size: 12px; }
.btn-delete:hover { background: #c0392b; }
.product-img { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd; }

/* CSS cho nút cập nhật đơn hàng */
.btn-save-order { background: #2c3e50; color: white; border: none; padding: 6px 10px; border-radius: 4px; cursor: pointer; transition: 0.3s; }
.btn-save-order:hover { background: #bfa15f; }
.status-select { padding: 5px; border-radius: 4px; border: 1px solid #ccc; outline: none; font-size: 13px; }
</style>
</head>
<body>

<div class="sidebar">
<h3>SLAND ADMIN</h3>
<a href="admin.php" class="active"><i class="fas fa-home"></i> Tổng quan</a>

<a href="admin_chat.php" style="background: #2980b9; color: white; font-weight: bold;">
<i class="fas fa-comments"></i> Hỗ trợ trực tuyến
</a>

<a href="#list-products"><i class="fas fa-box"></i> Quản lý Sản phẩm</a>
<a href="#list-orders"><i class="fas fa-file-invoice-dollar"></i> Quản lý Đơn hàng</a>
<a href="admin_add_product.php"><i class="fas fa-plus-circle"></i> Thêm Sản phẩm</a>
<a href="admin_reviews.php"><i class="fas fa-star"></i> Quản lý Đánh giá</a>

<a href="logout.php" style="background: #c0392b; color: white; margin-top: 20px; text-align: center; border-radius: 4px; display: block;">
<i class="fas fa-sign-out-alt"></i> Đăng xuất
</a>
</div>

<div class="content">
<div style="display:flex; justify-content:space-between; align-items:center;">
<h1>Xin chào Admin: <?php echo $_SESSION['username']; ?></h1>
<a href="index.php" target="_blank" style="text-decoration:none; color:#bfa15f;">Xem trang chủ <i class="fas fa-external-link-alt"></i></a>
</div>
<hr style="border: 0; border-top: 1px solid #ccc; margin-bottom: 30px;">

<div class="stats-container">
<div class="card" style="border-bottom: 4px solid #e74c3c;">
<i class="fas fa-shopping-cart" style="color: #e74c3c;"></i>
<h3>Tổng Đơn hàng</h3>
<p style="color: #e74c3c"><?php echo $orders_count; ?></p>
</div>

<div class="card" style="border-bottom: 4px solid #27ae60;">
<i class="fas fa-box-open" style="color: #27ae60;"></i>
<h3>Tổng Sản phẩm</h3>
<p style="color: #27ae60"><?php echo $products_count; ?></p>
</div>

<div class="card" style="border-bottom: 4px solid #2980b9; cursor: pointer;" onclick="window.location.href='admin_chat.php'">
<i class="fas fa-comments" style="color: #2980b9;"></i>
<h3>Khách cần hỗ trợ</h3>
<p style="color: #2980b9"><?php echo $chat_count; ?></p>
<small style="color: #777;">người đang nhắn tin</small>
</div>
</div>

<h2 id="list-orders">📝 Danh sách Đơn hàng mới nhất</h2>
<table>
<thead>
<tr>
<th>ID</th>
<th>Khách hàng</th>
<th>SĐT</th>
<th>Tổng tiền</th>
<th>PTTT</th>
<th>Ngày đặt</th>
<th>Trạng thái (Cập nhật)</th>
</tr>
</thead>
<tbody>
<?php
$sql = "SELECT * FROM orders ORDER BY created_at DESC LIMIT 10"; // Tăng lên 10 đơn cho dễ nhìn
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) > 0) {
while ($row = mysqli_fetch_assoc($result)) {
?>
<tr>
<td>#<?php echo $row['id']; ?></td>
<td>
<strong><?php echo $row['fullname']; ?></strong>
</td>
<td><?php echo $row['phone']; ?></td>
<td style='color:#e74c3c; font-weight:bold;'><?php echo number_format($row['total_money']); ?>đ</td>
<td><?php echo $row['payment_method']; ?></td>
<td><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>

<td>
<form action="update_order_status.php" method="POST" style="display:flex; align-items:center; gap:5px;">
<input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">

<select name="status" class="status-select"
style="<?php 
// Đổi màu chữ dropdown cho sinh động
if($row['status']=='Đã giao hàng') echo 'color:green; font-weight:bold;';
elseif($row['status']=='Đã hủy') echo 'color:red; font-weight:bold;';
elseif($row['status']=='Đang giao hàng') echo 'color:blue; font-weight:bold;';
else echo 'color:#d35400; font-weight:bold;';
?>">
<option value="Chờ xử lý" <?php if($row['status']=='Chờ xử lý') echo 'selected'; ?>>⏳ Chờ xử lý</option>
<option value="Đang giao hàng" <?php if($row['status']=='Đang giao hàng') echo 'selected'; ?>>🚚 Đang giao hàng</option>
<option value="Đã giao hàng" <?php if($row['status']=='Đã giao hàng') echo 'selected'; ?>>✅ Đã giao hàng</option>
<option value="Đã hủy" <?php if($row['status']=='Đã hủy') echo 'selected'; ?>>❌ Đã hủy</option>
</select>

<button type="submit" class="btn-save-order" title="Lưu trạng thái">
<i class="fas fa-save"></i>
</button>
</form>
</td>
</tr>
<?php
}
} else {
echo "<tr><td colspan='7' style='text-align:center'>Chưa có đơn hàng nào.</td></tr>";
}
?>
</tbody>
</table>

<div style="text-align: right; margin-top: 10px;">
<a href="#" style="color: #34495e; text-decoration: none; font-weight:bold;">Xem tất cả đơn hàng &rarr;</a>
</div>

<h2 id="list-products" style="margin-top: 50px;">📦 Danh sách Sản phẩm</h2>
<table>
<thead>
<tr>
<th>ID</th>
<th>Ảnh</th>
<th>Tên sản phẩm</th>
<th>Giá tiền</th>
<th>Danh mục</th>
<th>Hành động</th>
</tr>
</thead>
<tbody>
<?php
$sql_prod = "SELECT * FROM products ORDER BY id DESC";
$res_prod = mysqli_query($conn, $sql_prod);

if (mysqli_num_rows($res_prod) > 0) {
while ($prod = mysqli_fetch_assoc($res_prod)) {
$imgUrl = !empty($prod['image_url']) ? $prod['image_url'] : 'picture/no-image.jpg';
?>
<tr>
<td><?php echo $prod['id']; ?></td>
<td><img src="<?php echo $imgUrl; ?>" class="product-img"></td>
<td style="font-weight:600;"><?php echo $prod['name']; ?></td>
<td style="color:#e74c3c; font-weight: bold;"><?php echo number_format($prod['price']); ?>đ</td>
<td>
<?php
if($prod['category_id'] == 1) echo "<span style='color:#8e44ad'>Phòng ngủ</span>";
elseif($prod['category_id'] == 2) echo "<span style='color:#d35400'>Phòng ăn</span>";
elseif($prod['category_id'] == 3) echo "<span style='color:#2980b9'>Phòng khách</span>";
elseif($prod['category_id'] == 4) echo "<span style='color:#27ae60'>Học tập</span>";
else echo "Khác";
?>
</td>
<td>
<a href="admin_delete_product.php?id=<?php echo $prod['id']; ?>" 
   class="btn-delete"
   onclick="return confirm('Bạn chắc chắn muốn xóa: <?php echo $prod['name']; ?>?');">
   <i class="fas fa-trash"></i> Xóa
</a>
</td>
</tr>
<?php
}
} else {
echo "<tr><td colspan='6' style='text-align:center'>Chưa có sản phẩm nào.</td></tr>";
}
?>
</tbody>
</table>

</div>

</body>
</html>