<?php
session_start();
include 'db.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
header("Location: login.php");
exit();
}

// Kiểm tra ID đơn hàng
if (!isset($_GET['id'])) {
header("Location: my_orders.php");
exit();
}

$order_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

// 1. Lấy thông tin chung của đơn hàng (Và phải đảm bảo đơn này đúng là của user đang đăng nhập)
$sql_order = "SELECT * FROM orders WHERE id = '$order_id' AND user_id = '$user_id'";
$res_order = mysqli_query($conn, $sql_order);

if (mysqli_num_rows($res_order) == 0) {
echo "Đơn hàng không tồn tại hoặc bạn không có quyền xem.";
exit();
}
$order = mysqli_fetch_assoc($res_order);

// 2. Lấy danh sách sản phẩm trong đơn hàng
// JOIN bảng order_details với bảng products để lấy tên và ảnh sản phẩm
$sql_items = "SELECT od.*, p.name, p.image_url 
  FROM order_details od 
  JOIN products p ON od.product_id = p.id 
  WHERE od.order_id = '$order_id'";
$res_items = mysqli_query($conn, $sql_items);

$pageTitle = "Chi tiết đơn hàng #" . $order_id;
include 'header.php';
?>

<div style="max-width: 800px; margin: 40px auto; padding: 20px; background: #fff; border: 1px solid #eee; border-radius: 8px;">

<a href="my_orders.php" style="text-decoration:none; color:#777; font-size:14px;">&larr; Quay lại lịch sử đơn hàng</a>

<div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #eee; padding-bottom:15px; margin-top:20px;">
<h2 style="margin:0; color:#2c3e50;">Đơn hàng #<?php echo $order_id; ?></h2>
<span style="background:#f1f1f1; padding:5px 10px; border-radius:4px; font-weight:bold; color:#555;">
<?php echo $order['status']; ?>
</span>
</div>

<div style="margin: 20px 0; color:#555;">
<p><strong>Người nhận:</strong> <?php echo htmlspecialchars($order['fullname']); ?></p>
<p><strong>Số điện thoại:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
<p><strong>Địa chỉ:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
<p><strong>Ngày đặt:</strong> <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?></p>
</div>

<h3 style="margin-top:30px; border-bottom:2px solid #bfa15f; display:inline-block; padding-bottom:5px;">Sản phẩm đã mua</h3>
<table style="width:100%; border-collapse:collapse; margin-top:15px;">
<tr style="background:#f9f9f9; text-align:left;">
<th style="padding:10px;">Sản phẩm</th>
<th style="padding:10px;">Giá</th>
<th style="padding:10px; text-align:center;">Số lượng</th>
<th style="padding:10px; text-align:right;">Thành tiền</th>
</tr>
<?php 
$total_check = 0;
while ($item = mysqli_fetch_assoc($res_items)): 
$subtotal = $item['price'] * $item['quantity'];
$total_check += $subtotal;
// Xử lý ảnh (nếu thiếu ảnh thì dùng ảnh mặc định)
$img = !empty($item['image_url']) ? $item['image_url'] : 'picture/no-image.jpg';
?>
<tr>
<td style="padding:10px; border-bottom:1px solid #eee;">
<div style="display:flex; align-items:center;">
<img src="<?php echo $img; ?>" style="width:50px; height:50px; object-fit:cover; margin-right:10px; border-radius:4px;">
<span><?php echo htmlspecialchars($item['name']); ?></span>
</div>
</td>
<td style="padding:10px; border-bottom:1px solid #eee;">
<?php echo number_format($item['price'], 0, ',', '.'); ?>đ
</td>
<td style="padding:10px; border-bottom:1px solid #eee; text-align:center;">
x<?php echo $item['quantity']; ?>
</td>
<td style="padding:10px; border-bottom:1px solid #eee; text-align:right; font-weight:bold;">
<?php echo number_format($subtotal, 0, ',', '.'); ?>đ
</td>
</tr>
<?php endwhile; ?>
</table>

<div style="text-align:right; margin-top:20px; font-size:18px;">
Tổng cộng: <strong style="color:#e74c3c; font-size:24px;"><?php echo number_format($order['total_money'], 0, ',', '.'); ?>đ</strong>
</div>

</div>

<?php include 'footer.php'; ?>