<?php
session_start();
include 'db.php';

// 1. Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
header("Location: login.php");
exit();
}

$user_id = $_SESSION['user_id'];
// Lấy danh sách đơn hàng của user đó
$sql = "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

$pageTitle = "Đơn hàng của tôi - SLAND";
include 'header.php';
?>

<style>
.order-container { max-width: 1000px; margin: 50px auto; padding: 0 20px; min-height: 500px; }
.page-title { text-align: center; color: #2c3e50; margin-bottom: 30px; text-transform: uppercase; letter-spacing: 1px; }

/* Style bảng đơn hàng */
.order-table { width: 100%; border-collapse: collapse; background: white; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-radius: 8px; overflow: hidden; }
.order-table th, .order-table td { padding: 15px 20px; text-align: left; border-bottom: 1px solid #eee; }
.order-table th { background-color: #2c3e50; color: white; text-transform: uppercase; font-size: 13px; font-weight: 600; }
.order-table tr:last-child td { border-bottom: none; }
.order-table tr:hover { background-color: #fcfcfc; }

/* Màu sắc trạng thái */
.badge { padding: 5px 10px; border-radius: 20px; font-size: 12px; font-weight: bold; display: inline-block; }
.status-pending { background: #fff3cd; color: #856404; } /* Vàng */
.status-shipping { background: #cce5ff; color: #004085; } /* Xanh dương */
.status-success { background: #d4edda; color: #155724; } /* Xanh lá */
.status-cancel { background: #f8d7da; color: #721c24; } /* Đỏ */

/* Nút xem chi tiết */
.btn-view { 
text-decoration: none; color: #bfa15f; font-weight: bold; border: 1px solid #bfa15f; 
padding: 5px 15px; border-radius: 4px; transition: 0.3s; font-size: 13px;
}
.btn-view:hover { background: #bfa15f; color: white; }

/* Responsive cho điện thoại */
@media (max-width: 768px) {
.order-table, .order-table thead, .order-table tbody, .order-table th, .order-table td, .order-table tr { 
display: block; 
}
.order-table thead tr { position: absolute; top: -9999px; left: -9999px; }
.order-table tr { margin-bottom: 15px; border: 1px solid #ddd; border-radius: 8px; padding: 10px; }
.order-table td { border: none; padding: 8px 0; display: flex; justify-content: space-between; border-bottom: 1px solid #eee; }
.order-table td:before { content: attr(data-label); font-weight: bold; color: #555; margin-right: 10px; }
}
</style>

<div class="order-container">
<h2 class="page-title">Lịch sử đơn hàng</h2>

<?php if (mysqli_num_rows($result) > 0): ?>
<table class="order-table">
<thead>
<tr>
<th>Mã ĐH</th>
<th>Ngày đặt</th>
<th>Tổng tiền</th>
<th>Trạng thái</th>
<th>Thao tác</th>
</tr>
</thead>
<tbody>
<?php while ($row = mysqli_fetch_assoc($result)): ?>
<?php
// Xử lý màu sắc trạng thái
$status_class = 'status-pending';
if ($row['status'] == 'Đang giao hàng') $status_class = 'status-shipping';
if ($row['status'] == 'Đã giao hàng') $status_class = 'status-success';
if ($row['status'] == 'Đã hủy') $status_class = 'status-cancel';
?>
<tr>
<td data-label="Mã ĐH">#<?php echo $row['id']; ?></td>
<td data-label="Ngày đặt"><?php echo date('d/m/Y', strtotime($row['created_at'])); ?></td>
<td data-label="Tổng tiền" style="font-weight:bold; color:#e74c3c;">
<?php echo number_format($row['total_money'], 0, ',', '.'); ?>đ
</td>
<td data-label="Trạng thái">
<span class="badge <?php echo $status_class; ?>">
<?php echo htmlspecialchars($row['status']); ?>
</span>
</td>
<td data-label="Thao tác">
<a href="order_detail.php?id=<?php echo $row['id']; ?>" class="btn-view">
Xem chi tiết &rarr;
</a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<div style="text-align:center; margin-top:50px; color:#777;">
<img src="picture/empty-cart.png" style="width: 100px; opacity: 0.5; margin-bottom: 20px;">
<p>Bạn chưa mua đơn hàng nào.</p>
<a href="products.php" style="color:#bfa15f; font-weight:bold;">Mua sắm ngay</a>
</div>
<?php endif; ?>
</div>

<?php include 'footer.php'; ?>