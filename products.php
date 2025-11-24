<?php
session_start();
include 'db.php';

// --- 1. XỬ LÝ BỘ LỌC (LOGIC PHP) ---

// Khởi tạo câu lệnh gốc: "Lấy tất cả sản phẩm"
// Mẹo: WHERE 1=1 giúp ta dễ dàng nối thêm các điều kiện AND phía sau
$where_clause = "WHERE 1=1"; 
$order_clause = "ORDER BY id DESC"; // Mặc định: Mới nhất lên đầu

// A. Lọc theo DANH MỤC (?cate=1)
$cate_id = isset($_GET['cate']) ? $_GET['cate'] : '';
if ($cate_id != '' && $cate_id != 'all') {
$cate_id = (int)$cate_id;
$where_clause .= " AND category_id = $cate_id";
}

// B. Lọc theo KHOẢNG GIÁ (?price=range1)
$price_range = isset($_GET['price']) ? $_GET['price'] : '';
if ($price_range == 'duoi-2-trieu') {
$where_clause .= " AND price < 2000000";
} elseif ($price_range == '2-den-5-trieu') {
$where_clause .= " AND price BETWEEN 2000000 AND 5000000";
} elseif ($price_range == '5-den-10-trieu') {
$where_clause .= " AND price BETWEEN 5000000 AND 10000000";
} elseif ($price_range == 'tren-10-trieu') {
$where_clause .= " AND price > 10000000";
}

// C. Sắp xếp (?sort=asc/desc)
$sort = isset($_GET['sort']) ? $_GET['sort'] : '';
if ($sort == 'price_asc') {
$order_clause = "ORDER BY price ASC"; // Giá thấp -> Cao
} elseif ($sort == 'price_desc') {
$order_clause = "ORDER BY price DESC"; // Giá cao -> Thấp
} elseif ($sort == 'name_asc') {
$order_clause = "ORDER BY name ASC"; // Tên A-Z
}

// --- 2. CẤU HÌNH PHÂN TRANG (GIỮ NGUYÊN LOGIC CŨ NHƯNG KẾT HỢP BỘ LỌC) ---

$limit = 9; // Giảm xuống 9 sản phẩm/trang cho đẹp đội hình (3x3)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;

// --- 3. THỰC HIỆN TRUY VẤN ---

// Bước 3.1: Đếm tổng số sản phẩm (đã lọc) để tính số trang
// Quan trọng: Phải đếm dựa trên $where_clause để biết sau khi lọc còn bao nhiêu SP
$sql_count = "SELECT COUNT(*) as total FROM products $where_clause";
$res_count = mysqli_query($conn, $sql_count);
$row_count = mysqli_fetch_assoc($res_count);
$total_records = $row_count['total'];
$total_pages = ceil($total_records / $limit);

// Bước 3.2: Lấy dữ liệu sản phẩm (đã lọc + sắp xếp + phân trang)
$sql = "SELECT * FROM products $where_clause $order_clause LIMIT $limit OFFSET $offset";
$result = mysqli_query($conn, $sql);

// Hàm giúp giữ lại các tham số URL khi bấm chuyển trang (Để không bị mất bộ lọc)
function getUrlParams($remove_key = '') {
$params = $_GET;
if ($remove_key) unset($params[$remove_key]);
return http_build_query($params);
}

$pageTitle = "Sản phẩm - Nội thất SLAND";
include 'header.php';
?>

<style>
/* LAYOUT 2 CỘT */
.shop-container {
display: flex;
max-width: 1200px;
margin: 40px auto;
padding: 0 20px;
gap: 30px;
}

/* CỘT TRÁI: SIDEBAR BỘ LỌC */
.sidebar-filter {
width: 250px;
flex-shrink: 0; /* Không bị co lại */
}
.filter-box {
background: white;
border: 1px solid #eee;
padding: 20px;
border-radius: 8px;
margin-bottom: 20px;
}
.filter-title {
font-weight: bold;
margin-bottom: 15px;
color: #2c3e50;
text-transform: uppercase;
font-size: 14px;
border-bottom: 2px solid #bfa15f;
display: inline-block;
padding-bottom: 5px;
}
.filter-list { list-style: none; padding: 0; margin: 0; }
.filter-list li { margin-bottom: 10px; }
.filter-link {
text-decoration: none;
color: #555;
font-size: 14px;
display: block;
transition: 0.2s;
}
.filter-link:hover, .filter-link.active {
color: #bfa15f;
font-weight: bold;
transform: translateX(5px); /* Hiệu ứng đẩy nhẹ sang phải */
}
.filter-link i { margin-right: 8px; font-size: 12px; }

/* CỘT PHẢI: DANH SÁCH SP */
.main-content { flex: 1; }

/* Thanh công cụ phía trên (Toolbar) */
.shop-toolbar {
display: flex;
justify-content: space-between;
align-items: center;
margin-bottom: 20px;
background: #f9f9f9;
padding: 10px 20px;
border-radius: 5px;
}
.sort-select {
padding: 8px;
border: 1px solid #ddd;
border-radius: 4px;
outline: none;
}

/* Grid sản phẩm (Giữ nguyên CSS cũ nhưng chỉnh lại chút) */
.product-grid {
display: grid;
grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
gap: 30px;
}
.product-card {
background: white; border: 1px solid #eee; border-radius: 8px;
overflow: hidden; transition: 0.3s; position: relative;
}
.product-card:hover { box-shadow: 0 10px 20px rgba(0,0,0,0.1); transform: translateY(-5px); }
.product-img { width: 100%; height: 220px; object-fit: cover; }
.product-info { padding: 15px; text-align: center; }
.product-name { font-weight: bold; color: #333; text-decoration: none; display: block; margin-bottom: 5px; }
.product-price { color: #e74c3c; font-weight: bold; font-size: 16px; }

/* Responsive mobile */
@media (max-width: 768px) {
.shop-container { flex-direction: column; }
.sidebar-filter { width: 100%; }
}

/* Pagination CSS (Giữ nguyên) */
.pagination { display: flex; justify-content: center; margin-top: 40px; gap: 5px; }
.page-link { padding: 8px 12px; border: 1px solid #ddd; text-decoration: none; color: #333; border-radius: 4px; }
.page-link.active { background: #bfa15f; color: white; border-color: #bfa15f; }
</style>

<div class="shop-container">

<aside class="sidebar-filter">

<div class="filter-box">
<div class="filter-title">Danh mục sản phẩm</div>
<ul class="filter-list">
<li><a href="products.php" class="filter-link <?php echo ($cate_id == '') ? 'active' : ''; ?>">
<i class="fas fa-circle"></i> Tất cả
</a></li>
<li><a href="?cate=3&<?php echo getUrlParams('cate'); ?>" class="filter-link <?php echo ($cate_id == 3) ? 'active' : ''; ?>">
<i class="fas fa-couch"></i> Phòng khách
</a></li>
<li><a href="?cate=1&<?php echo getUrlParams('cate'); ?>" class="filter-link <?php echo ($cate_id == 1) ? 'active' : ''; ?>">
<i class="fas fa-bed"></i> Phòng ngủ
</a></li>
<li><a href="?cate=2&<?php echo getUrlParams('cate'); ?>" class="filter-link <?php echo ($cate_id == 2) ? 'active' : ''; ?>">
<i class="fas fa-utensils"></i> Phòng ăn
</a></li>
<li><a href="?cate=4&<?php echo getUrlParams('cate'); ?>" class="filter-link <?php echo ($cate_id == 4) ? 'active' : ''; ?>">
<i class="fas fa-book"></i> Học tập
</a></li>
</ul>
</div>

<div class="filter-box">
<div class="filter-title">Khoảng giá</div>
<ul class="filter-list">
<li><a href="?price=all&<?php echo getUrlParams('price'); ?>" class="filter-link <?php echo ($price_range == '' || $price_range == 'all') ? 'active' : ''; ?>">
<i class="far fa-square"></i> Tất cả mức giá
</a></li>
<li><a href="?price=duoi-2-trieu&<?php echo getUrlParams('price'); ?>" class="filter-link <?php echo ($price_range == 'duoi-2-trieu') ? 'active' : ''; ?>">
<i class="far fa-check-square"></i> Dưới 2 triệu
</a></li>
<li><a href="?price=2-den-5-trieu&<?php echo getUrlParams('price'); ?>" class="filter-link <?php echo ($price_range == '2-den-5-trieu') ? 'active' : ''; ?>">
<i class="far fa-check-square"></i> Từ 2 - 5 triệu
</a></li>
<li><a href="?price=5-den-10-trieu&<?php echo getUrlParams('price'); ?>" class="filter-link <?php echo ($price_range == '5-den-10-trieu') ? 'active' : ''; ?>">
<i class="far fa-check-square"></i> Từ 5 - 10 triệu
</a></li>
<li><a href="?price=tren-10-trieu&<?php echo getUrlParams('price'); ?>" class="filter-link <?php echo ($price_range == 'tren-10-trieu') ? 'active' : ''; ?>">
<i class="far fa-check-square"></i> Trên 10 triệu
</a></li>
</ul>
</div>
</aside>

<main class="main-content">

<div class="shop-toolbar">
<div>Tìm thấy <strong><?php echo $total_records; ?></strong> sản phẩm</div>

<form method="GET" id="sortForm">
<?php if($cate_id) echo '<input type="hidden" name="cate" value="'.$cate_id.'">'; ?>
<?php if($price_range) echo '<input type="hidden" name="price" value="'.$price_range.'">'; ?>

<select name="sort" class="sort-select" onchange="document.getElementById('sortForm').submit()">
<option value="">Sắp xếp mặc định</option>
<option value="price_asc" <?php if($sort=='price_asc') echo 'selected'; ?>>Giá: Thấp đến Cao</option>
<option value="price_desc" <?php if($sort=='price_desc') echo 'selected'; ?>>Giá: Cao đến Thấp</option>
<option value="name_asc" <?php if($sort=='name_asc') echo 'selected'; ?>>Tên: A - Z</option>
</select>
</form>
</div>

<div class="product-grid">
<?php
if (mysqli_num_rows($result) > 0) {
while ($row = mysqli_fetch_assoc($result)) {
$imgUrl = !empty($row['image_url']) ? $row['image_url'] : 'picture/no-image.jpg';
?>
<div class="product-card"
data-id="<?php echo $row['id']; ?>"
data-name="<?php echo htmlspecialchars($row['name']); ?>"
data-price="<?php echo $row['price']; ?>"
data-img="<?php echo $imgUrl; ?>">

<a href="product_detail.php?id=<?php echo $row['id']; ?>">
<img src="<?php echo $imgUrl; ?>" alt="<?php echo $row['name']; ?>" class="product-img">
</a>
<div class="product-info">
<a href="product_detail.php?id=<?php echo $row['id']; ?>" class="product-name"><?php echo $row['name']; ?></a>
<div class="product-price"><?php echo number_format($row['price']); ?>đ</div>
<button class="btn-add-cart" onclick="addToCart(this)"
style="margin-top:10px; padding:8px 15px; background:#2c3e50; color:white; border:none; border-radius:4px; cursor:pointer;">
Thêm vào giỏ
</button>
</div>
</div>
<?php
}
} else {
echo "<div style='text-align:center; grid-column:1/-1; padding:50px;'>
<p>Không tìm thấy sản phẩm nào phù hợp tiêu chí lọc.</p>
<a href='products.php' style='color:#bfa15f'>Xóa bộ lọc</a>
</div>";
}
?>
</div>

<?php if($total_pages > 1): ?>
<div class="pagination">
<?php
// Tạo chuỗi tham số URL hiện tại (để khi bấm trang 2 nó không mất bộ lọc)
$current_url_params = getUrlParams('page');
?>

<?php for ($i = 1; $i <= $total_pages; $i++): ?>
<a href="?page=<?php echo $i; ?>&<?php echo $current_url_params; ?>"
class="page-link <?php echo ($i == $page) ? 'active' : ''; ?>">
<?php echo $i; ?>
</a>
<?php endfor; ?>
</div>
<?php endif; ?>

</main>
</div>

<?php include 'footer.php'; ?>