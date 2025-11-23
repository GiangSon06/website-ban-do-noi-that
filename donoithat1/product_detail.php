<?php
session_start();
include 'db.php';
include 'header.php';
// 1. Lấy ID sản phẩm từ URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
$product_id = $_GET['id'];
} else {
// Nếu không có ID hoặc ID không hợp lệ, chuyển hướng về trang chủ
header("Location: products.php");
exit();
}

// 2. Truy vấn Database
$sql = "SELECT * FROM products WHERE id = $product_id";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 1) {
$product = mysqli_fetch_assoc($result);
$pageTitle = $product['name'];
} else {
// Không tìm thấy sản phẩm
$pageTitle = "Sản phẩm không tồn tại";
$product = null;
}


?>

<main class="product-detail-page">
<?php if ($product): ?>
<div class="detail-container">
<div class="detail-gallery">
<img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>">
</div>

<div class="detail-info">
<h1><?php echo $product['name']; ?></h1>

<div class="price-box">
Giá: <span class="current-price"><?php echo number_format($product['price'], 0, ',', '.') . 'đ'; ?></span>
</div>

<div class="short-specs">
<ul>
<li><i class="fas fa-check-circle"></i> Chất liệu: <strong><?php echo $product['material']; ?></strong></li>
<li><i class="fas fa-check-circle"></i> Kích thước: <strong><?php echo $product['dimensions']; ?></strong></li>
<li><i class="fas fa-check-circle"></i> Bảo hành: <strong><?php echo $product['warranty']; ?></strong></li>
</ul>
</div>

<div class="actions">
<button class="btn-add-to-cart"
onclick="addToCart(this)"
data-id="<?php echo $product['id']; ?>"
data-name="<?php echo $product['name']; ?>"
data-price="<?php echo $product['price']; ?>"
data-img="<?php echo $product['image_url']; ?>">
<i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng
</button>

<button class="btn-buy-now"
onclick="buyNow(this)"
data-id="<?php echo $product['id']; ?>"
data-name="<?php echo $product['name']; ?>"
data-price="<?php echo $product['price']; ?>"
data-img="<?php echo $product['image_url']; ?>">
Mua ngay
</button>
</div>
</div>
</div>

<div class="detail-description">
<h2>Mô tả sản phẩm</h2>
<p><?php echo nl2br($product['description']); ?></p>
</div>

<?php else: ?>
<h2 style="text-align: center; padding: 50px;">Sản phẩm bạn tìm không tồn tại.</h2>
<?php endif; ?>
</main>

<?php include 'footer.php'; ?>