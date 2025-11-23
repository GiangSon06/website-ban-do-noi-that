<?php
$pageTitle = "Sản phẩm - Nội Thất Giang Sơn";
include 'header.php';
include 'db.php'; // <--- Thêm dòng kết nối database

// Lấy tất cả sản phẩm
$sql_products = "SELECT id, name, price, image_url, category_id FROM products ORDER BY id DESC";
$result_products = mysqli_query($conn, $sql_products);
?>

    <section class="banner_products">
        <img src="picture/section_products.jpg" alt="Banner sản phẩm" style="width:100%"/>
    </section>

    <section id="products">
        <h2>Tất cả sản phẩm</h2>
        <div class="category-menu">
            <button onclick="filterCategory('all')">Tất cả</button>
            <button onclick="filterCategory('3')">Phòng khách</button>
            <button onclick="filterCategory('1')">Phòng ngủ</button>
            <button onclick="filterCategory('2')">Phòng ăn</button>
            <button onclick="filterCategory('4')">Học tập</button>
        </div>

        <div class="product-list">
            <?php
            if (mysqli_num_rows($result_products) > 0) {
                while ($product = mysqli_fetch_assoc($result_products)) {

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
                echo "<p style='grid-column: 1 / -1; text-align: center; color: #777;'>Hiện chưa có sản phẩm nào được hiển thị.</p>";
            }
            ?>
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

<?php include 'footer.php'; ?>