<?php
session_start();
include 'db.php';

// Kiểm tra bảo mật
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
header("Location: login.php");
exit();
}

// --- PHẦN 1: TRUY VẤN DỮ LIỆU THỐNG KÊ ---

// 1. Tổng đơn hàng
$res_orders_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM orders");
$orders_count = mysqli_fetch_assoc($res_orders_count)['total'];

// 2. Tổng sản phẩm
$res_products_count = mysqli_query($conn, "SELECT COUNT(*) as total FROM products");
$products_count = mysqli_fetch_assoc($res_products_count)['total'];

// 3. Khách đang chat
$admin_id = $_SESSION['user_id'];
$res_chat = mysqli_query($conn, "SELECT COUNT(DISTINCT sender_id) as total FROM messages WHERE sender_id != '$admin_id'");
$chat_count = ($res_chat) ? mysqli_fetch_assoc($res_chat)['total'] : 0;

// 4. DATA CHO BIỂU ĐỒ (Doanh thu 7 ngày gần nhất)
// Logic: Lấy ngày và tổng tiền, trừ những đơn đã hủy
$sql_chart = "SELECT DATE(created_at) as date, SUM(total_money) as revenue
FROM orders
WHERE status != 'Đã hủy'
GROUP BY DATE(created_at)
ORDER BY date DESC LIMIT 7";
$res_chart = mysqli_query($conn, $sql_chart);

$chart_data = [];
while($row = mysqli_fetch_assoc($res_chart)) {
 $chart_data[] = $row; // Lưu vào mảng
}
// Đảo ngược mảng để ngày cũ bên trái, ngày mới bên phải
$chart_data = array_reverse($chart_data);

// Tách thành 2 mảng riêng để đưa vào Chart.js
$labels = [];// Chứa ngày (Trục ngang)
$data= [];// Chứa tiền (Trục dọc)

foreach($chart_data as $item) {
 $labels[] = date('d/m', strtotime($item['date'])); // Định dạng ngày dd/mm
$data[]= $item['revenue'];
}

// Chuyển mảng PHP sang JSON để Javascript đọc được
$json_labels = json_encode($labels);
$json_data= json_encode($data);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard - SLAND</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
body { margin: 0; font-family: 'Segoe UI', sans-serif; display: flex; background-color: #f4f6f9; }

/* Sidebar */
.sidebar { width: 250px; background: #2c3e50; color: white; height: 100vh; padding: 20px; position: fixed; z-index: 100; }
.sidebar h3 { text-align: center; margin-bottom: 30px; color: #ecf0f1; border-bottom: 1px solid #34495e; padding-bottom: 10px; }
.sidebar a { display: flex; align-items: center; gap: 10px; color: #bdc3c7; text-decoration: none; padding: 15px; border-bottom: 1px solid #34495e; transition: 0.3s; }
.sidebar a:hover, .sidebar a.active { background: #bfa15f; color: white; padding-left: 20px; }

/* Content */
.content { flex: 1; padding: 30px; margin-left: 290px; }

/* Stats Cards */
.stats-container { display: flex; gap: 20px; margin-bottom: 40px; }
.card { flex: 1; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); text-align: center; transition: transform 0.3s; }
.card:hover { transform: translateY(-5px); }
.card h3 { font-size: 14px; color: #7f8c8d; text-transform: uppercase; margin-top: 10px; }
.card p { font-size: 32px; font-weight: bold; margin: 5px 0; }
.card i { font-size: 40px; opacity: 0.2; }

/* Chart Section */
.chart-wrapper { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom: 40px; }

/* Table */
table { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
th, td { padding: 15px; text-align: left; border-bottom: 1px solid #eee; }
th { background-color: #34495e; color: white; text-transform: uppercase; font-size: 12px; }

.btn-save-order { background: #2c3e50; color: white; border: none; padding: 6px 10px; border-radius: 4px; cursor: pointer; }
.btn-save-order:hover { background: #bfa15f; }
.status-select { padding: 5px; border: 1px solid #ccc; border-radius: 4px; outline: none; }
</style>
</head>
<body>

<div class="sidebar">
<h3>SLAND ADMIN</h3>
<a href="admin.php" class="active"><i class="fas fa-home"></i> Tổng quan</a>
<a href="admin_chat.php" style="background: #2980b9; color: white;"><i class="fas fa-comments"></i> Hỗ trợ trực tuyến</a>
<a href="#chart-section"><i class="fas fa-chart-bar"></i> Biểu đồ doanh thu</a>
<a href="#list-orders"><i class="fas fa-file-invoice"></i> Quản lý Đơn hàng</a>
<a href="#list-products"><i class="fas fa-box"></i> Quản lý Sản phẩm</a>
<a href="admin_add_product.php"><i class="fas fa-plus-circle"></i> Thêm Sản phẩm</a>
<a href="admin_reviews.php"><i class="fas fa-star"></i> Quản lý Đánh giá</a>
<a href="logout.php" style="background: #c0392b; color: white; margin-top: 20px;"><i class="fas fa-sign-out-alt"></i> Đăng xuất</a>
</div>

<div class="content">
<div style="display:flex; justify-content:space-between; align-items:center;">
<h1>Dashboard Quản Trị</h1>
<a href="index.php" target="_blank" style="color:#bfa15f; text-decoration:none;">Xem website <i class="fas fa-external-link-alt"></i></a>
</div>
<hr style="border: 0; border-top: 1px solid #ccc; margin-bottom: 30px;">

<div class="stats-container">
<div class="card" style="border-bottom: 4px solid #e74c3c;">
<i class="fas fa-shopping-cart" style="color: #e74c3c;"></i>
<h3>Đơn hàng</h3>
<p style="color: #e74c3c"><?php echo $orders_count; ?></p>
</div>
<div class="card" style="border-bottom: 4px solid #27ae60;">
<i class="fas fa-box-open" style="color: #27ae60;"></i>
<h3>Sản phẩm</h3>
<p style="color: #27ae60"><?php echo $products_count; ?></p>
</div>
<div class="card" style="border-bottom: 4px solid #2980b9; cursor:pointer;" onclick="location.href='admin_chat.php'">
<i class="fas fa-comments" style="color: #2980b9;"></i>
<h3>Tin nhắn mới</h3>
<p style="color: #2980b9"><?php echo $chat_count; ?></p>
</div>
</div>

<div id="chart-section" class="chart-wrapper">
<h2 style="margin-top:0; color:#2c3e50;"><i class="fas fa-chart-line"></i> Doanh thu 7 ngày gần nhất</h2>
<canvas id="revenueChart" height="100"></canvas>
</div>

<h2 id="list-orders">📝 Đơn hàng mới nhất</h2>
<table>
<thead>
<tr>
<th>ID</th>
<th>Khách hàng</th>
<th>Tổng tiền</th>
<th>Ngày đặt</th>
<th>Trạng thái</th>
</tr>
</thead>
<tbody>
<?php
 $sql = "SELECT * FROM orders ORDER BY created_at DESC LIMIT 5";
$result = mysqli_query($conn, $sql);
if (mysqli_num_rows($result) > 0) {
while ($row = mysqli_fetch_assoc($result)) {
echo "<tr>";
echo "<td>#{$row['id']}</td>";
echo "<td><strong>{$row['fullname']}</strong><br><small>{$row['phone']}</small></td>";
echo "<td style='color:#e74c3c; font-weight:bold;'>" . number_format($row['total_money']) . "đ</td>";
echo "<td>" . date('d/m', strtotime($row['created_at'])) . "</td>";
?>
<td>
<form action="update_order_status.php" method="POST" style="display:flex; gap:5px;">
<input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
<select name="status" class="status-select">
<option value="Chờ xử lý" <?php if($row['status']=='Chờ xử lý') echo 'selected'; ?>>⏳ Chờ</option>
<option value="Đang giao hàng" <?php if($row['status']=='Đang giao hàng') echo 'selected'; ?>>🚚 Ship</option>
<option value="Đã giao hàng" <?php if($row['status']=='Đã giao hàng') echo 'selected'; ?>>✅ Xong</option>
<option value="Đã hủy" <?php if($row['status']=='Đã hủy') echo 'selected'; ?>>❌ Hủy</option>
</select>
<button type="submit" class="btn-save-order" title="Lưu"><i class="fas fa-save"></i></button>
</form>
<a href="print_order.php?id=<?php echo $row['id']; ?>" target="_blank" title="In hóa đơn"
style="margin-left:5px; background: #7f8c8d; color: white; padding: 6px 10px; border-radius: 4px; text-decoration: none;">
<i class="fas fa-print"></i>
</a>
</td>
<?php
echo "</tr>";
}
} else {
echo "<tr><td colspan='5' style='text-align:center'>Chưa có dữ liệu</td></tr>";
}
?>
</tbody>
</table>

<h2 id="list-products" style="margin-top: 50px;">📦 Sản phẩm mới</h2>
<table>
<thead>
<tr>
<th>ID</th>
<th>Ảnh</th>
<th>Tên sản phẩm</th>
<th>Giá</th>
<th>Hành động</th>
</tr>
</thead>
<tbody>
<?php
 $res_prod = mysqli_query($conn, "SELECT * FROM products ORDER BY id DESC LIMIT 10");
while ($prod = mysqli_fetch_assoc($res_prod)) {
$img = !empty($prod['image_url']) ? $prod['image_url'] : 'picture/no-image.jpg';
echo "<tr>";
echo "<td>{$prod['id']}</td>";
echo "<td><img src='$img' width='40' style='border-radius:4px;'></td>";
echo "<td>{$prod['name']}</td>";
echo "<td>" . number_format($prod['price']) . "đ</td>";
echo "<td><a href='admin_delete_product.php?id={$prod['id']}' onclick='return confirm(\"Xóa nha?\")' style='color:red;'><i class='fas fa-trash'></i></a></td>";
echo "</tr>";
}
?>
</tbody>
</table>

</div>

<script>
// Lấy dữ liệu từ PHP
const labels = <?php echo $json_labels; ?>; // Ví dụ: ["20/11", "21/11", ...]
const data = <?php echo $json_data; ?>;// Ví dụ: [500000, 1200000, ...]

const ctx = document.getElementById('revenueChart').getContext('2d');
const myChart = new Chart(ctx, {
type: 'bar', // Loại biểu đồ: bar (cột), line (đường)
data: {
labels: labels,
datasets: [{
label: 'Doanh thu (VNĐ)',
data: data,
backgroundColor: 'rgba(191, 161, 95, 0.6)', // Màu vàng SLAND
borderColor: 'rgba(191, 161, 95, 1)',
borderWidth: 1
}]
},
options: {
scales: {
y: {
beginAtZero: true,
ticks: {
callback: function(value) {
return value.toLocaleString('vi-VN') + 'đ'; // Định dạng tiền tệ trục dọc
}
}
}
},
responsive: true
}
});
</script>

</body>
</html>