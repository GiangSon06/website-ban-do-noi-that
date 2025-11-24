<?php
session_start();
include 'db.php';

// Kiểm tra Admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
die("Bạn không có quyền truy cập.");
}

if (!isset($_GET['id'])) {
die("Không tìm thấy đơn hàng.");
}

$order_id = (int)$_GET['id'];

// 1. Lấy thông tin đơn hàng
$sql_order = "SELECT * FROM orders WHERE id = $order_id";
$res_order = mysqli_query($conn, $sql_order);
$order = mysqli_fetch_assoc($res_order);

// 2. Lấy chi tiết sản phẩm
$sql_items = "SELECT od.*, p.name 
  FROM order_details od 
  JOIN products p ON od.product_id = p.id 
  WHERE od.order_id = $order_id";
$res_items = mysqli_query($conn, $sql_items);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Hóa đơn #<?php echo $order_id; ?></title>
<style>
body { font-family: 'DejaVu Sans', sans-serif; font-size: 14px; line-height: 1.6; color: #000; }
.invoice-box {
max-width: 800px;
margin: auto;
padding: 30px;
border: 1px solid #eee;
box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
}

/* Header hóa đơn */
.header { display: flex; justify-content: space-between; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
.shop-info h2 { margin: 0; text-transform: uppercase; }
.invoice-info { text-align: right; }

/* Bảng sản phẩm */
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
th { background: #eee; text-align: left; padding: 10px; border-bottom: 1px solid #333; }
td { padding: 10px; border-bottom: 1px solid #eee; }
.text-right { text-align: right; }
.total-row td { border-top: 2px solid #333; font-weight: bold; font-size: 16px; }

/* Ẩn nút in khi in ra giấy */
@media print {
.no-print { display: none; }
.invoice-box { border: none; box-shadow: none; }
}

.btn-print {
background: #2c3e50; color: white; padding: 10px 20px; 
text-decoration: none; border-radius: 5px; cursor: pointer;
}
</style>
</head>
<body onload="window.print()">

<div style="text-align:center; margin-bottom:20px;" class="no-print">
<a href="admin.php" style="margin-right:20px; text-decoration:none; color:blue;">&larr; Quay lại Admin</a>
<button onclick="window.print()" class="btn-print">🖨️ In Hóa Đơn</button>
</div>

<div class="invoice-box">
<div class="header">
<div class="shop-info">
<h2>NỘI THẤT SLAND</h2>
<p>41A Đ. Phú Diễn, Bắc Từ Liêm, Hà Nội<br>Hotline: 0326.976.832</p>
</div>
<div class="invoice-info">
<h3>HÓA ĐƠN BÁN HÀNG</h3>
<p>Mã đơn: <strong>#<?php echo $order_id; ?></strong><br>
Ngày đặt: <?php echo date('d/m/Y', strtotime($order['created_at'])); ?></p>
</div>
</div>

<div class="customer-info">
<p><strong>Khách hàng:</strong> <?php echo $order['fullname']; ?></p>
<p><strong>Số điện thoại:</strong> <?php echo $order['phone']; ?></p>
<p><strong>Địa chỉ giao hàng:</strong> <?php echo $order['address']; ?></p>
</div>

<table>
<thead>
<tr>
<th>Sản phẩm</th>
<th class="text-right">Đơn giá</th>
<th class="text-right">SL</th>
<th class="text-right">Thành tiền</th>
</tr>
</thead>
<tbody>
<?php while ($item = mysqli_fetch_assoc($res_items)): ?>
<tr>
<td><?php echo $item['name']; ?></td>
<td class="text-right"><?php echo number_format($item['price']); ?>đ</td>
<td class="text-right"><?php echo $item['quantity']; ?></td>
<td class="text-right"><?php echo number_format($item['price'] * $item['quantity']); ?>đ</td>
</tr>
<?php endwhile; ?>

<tr class="total-row">
<td colspan="3" class="text-right">TỔNG THANH TOÁN:</td>
<td class="text-right"><?php echo number_format($order['total_money']); ?> VNĐ</td>
</tr>
</tbody>
</table>

<div style="display:flex; justify-content:space-between; margin-top:50px; text-align:center;">
<div>
<strong>Người mua hàng</strong><br>
<small>(Ký, ghi rõ họ tên)</small>
</div>
<div>
<strong>Người bán hàng</strong><br>
<small>(Ký, đóng dấu)</small>
</div>
</div>

<p style="text-align:center; margin-top:50px; font-style:italic;">Cảm ơn quý khách đã tin tưởng SLAND!</p>
</div>

</body>
</html>