<?php
$pageTitle = "Trang chủ - Nội Thất SLAND";
include 'header.php';
include 'db.php'; // Kết nối database

// SỬA LỖI: Thêm category_id vào câu lệnh SELECT
$sql_featured = "SELECT id, name, price, image_url, category_id FROM products ORDER BY id DESC LIMIT 5";
$result_featured = mysqli_query($conn, $sql_featured);

// Tùy chọn: Tắt hiển thị lỗi PHP nếu bạn vẫn thấy dòng C:/xampp/htdocs/...
// error_reporting(0);
// ini_set('display_errors', 0);
?>

    <section class="banner">
        <div class="slides">
            <img src="picture/slide2.jpg" alt="Banner 1" style="width:100%; display:block;" />
        </div>
    </section>

    <section class="categories">
        <h2>Danh mục sản phẩm</h2>
        <div class="category-list">
            <button class="cat-item" onclick="filterCategory('all')">Tất cả</button>
            <button class="cat-item" onclick="filterCategory('3')">Phòng khách</button>
            <button class="cat-item" onclick="filterCategory('1')">Phòng ngủ</button>
            <button class="cat-item" onclick="filterCategory('2')">Phòng ăn</button>
            <button class="cat-item" onclick="filterCategory('4')">Học tập</button>
        </div>
    </section>

    <section class="products">
        <h2>Sản phẩm nổi bật</h2>
        <div class="product-list">
            <?php
            if (mysqli_num_rows($result_featured) > 0) {
                while ($product = mysqli_fetch_assoc($result_featured)) {
                    // Định dạng giá: 4.500.000đ
                    $formatted_price = number_format($product['price'], 0, ',', '.') . 'đ';
                    ?>
                    <div
                        class="product-card"
                        data-id="<?php echo $product['id']; ?>"
                        data-name="<?php echo $product['name']; ?>"
                        data-price="<?php echo $product['price']; ?>"
                        data-category="<?php echo $product['category_id']; ?>" 
                        >
                        <a href="product_detail.php?id=<?php echo $product['id']; ?>" class="product-link">
                            <img src="<?php echo $product['image_url']; ?>" alt="<?php echo $product['name']; ?>" />
                            <h3><?php echo $product['name']; ?></h3>
                        </a>
                        
                        <p class="price"><?php echo $formatted_price; ?></p>
                        <button onclick="addToCart(this)">Thêm vào giỏ</button>
                    </div>
                    <?php
                }
            } else {
                echo "<p style='grid-column: 1 / -1; text-align: center; color: #777;'>Hiện chưa có sản phẩm nổi bật nào.</p>";
            }
            ?>
        </div>
    </section>

<section class="reviews" style="background: #f9f9f9; padding: 40px 0;">
    <h2 style="text-align:center; margin-bottom:30px;">Khách hàng nói gì?</h2>
    
    <div class="review-list" style="display: flex; justify-content: center; gap: 20px; flex-wrap: wrap;">
        <?php
        // Lấy 3 đánh giá mới nhất
        $sql_reviews = "SELECT r.content, u.username FROM reviews r JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC LIMIT 3";
        $res_reviews = mysqli_query($conn, $sql_reviews);

        if (mysqli_num_rows($res_reviews) > 0) {
            while ($rev = mysqli_fetch_assoc($res_reviews)) {
                echo '<div class="review-card" style="background:white; padding:20px; border-radius:8px; width:300px; box-shadow:0 2px 5px rgba(0,0,0,0.1);">';
                echo '<p style="font-style:italic">"' . htmlspecialchars($rev['content']) . '"</p>';
                echo '<strong style="display:block; margin-top:10px; text-align:right">- ' . htmlspecialchars($rev['username']) . '</strong>';
                echo '</div>';
            }
        } else {
            echo "<p>Chưa có đánh giá nào.</p>";
        }
        ?>
    </div>

    <div style="max-width: 600px; margin: 40px auto; text-align: center;">
        <?php if (isset($_SESSION['user_id'])): ?>
            <h3>Viết đánh giá của bạn</h3>
            <form id="reviewForm" style="margin-top: 15px;">
                <textarea name="content" required placeholder="Chia sẻ cảm nhận của bạn về sản phẩm..." style="width: 100%; height: 80px; padding: 10px; border-radius: 5px; border: 1px solid #ccc;"></textarea>
                <button type="submit" style="margin-top: 10px; padding: 10px 20px; background: #bfa15f; color: white; border: none; border-radius: 5px; cursor: pointer; font-weight: bold;">Gửi đánh giá</button>
            </form>
        <?php else: ?>
            <p><i><a href="login.php" style="color: #bfa15f;">Đăng nhập</a> để viết đánh giá.</i></p>
        <?php endif; ?>
    </div>
</section>

    <div id="cart-popup" class="cart-popup">
        <h3>🛒 Giỏ hàng</h3>
        <ul id="cart-items"></ul>
        <p><strong>Tổng tiền: <span id="cart-total">0</span>đ</strong></p>
        <div class="cart-actions">
            <button onclick="clearCart()">Xóa giỏ hàng</button>
            <button onclick="checkout()">Thanh toán</button>
        </div>
    </div>

<script src="reviews.js"></script>
<?php include 'footer.php'; ?>