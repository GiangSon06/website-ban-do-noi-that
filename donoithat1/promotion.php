<?php
session_start();
$pageTitle = "Săn Sale Giờ Vàng - Nội Thất SLAND";
include 'header.php';
include 'db.php';

// Lấy các sản phẩm đang giảm giá (Tức là giá gốc > giá bán hiện tại)
// Nếu chưa có dữ liệu old_price, bạn hãy chạy lệnh SQL ở Bước 1 nhé
$sql = "SELECT * FROM products WHERE old_price > price ORDER BY id DESC";
$result = mysqli_query($conn, $sql);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* --- BANNER FLASH SALE --- */
    .promo-banner {
        background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('picture/slide2.jpg');
        background-size: cover;
        background-position: center;
        color: white;
        text-align: center;
        padding: 80px 20px;
        position: relative;
    }
    .promo-title {
        font-size: 48px;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: 2px;
        margin-bottom: 10px;
        color: #f1c40f; /* Màu vàng tươi cho nổi bật */
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }
    .promo-subtitle { font-size: 20px; margin-bottom: 30px; opacity: 0.9; }

    /* Đồng hồ đếm ngược */
    .countdown-container {
        display: flex; justify-content: center; gap: 20px; margin-top: 20px;
    }
    .time-box {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255,255,255,0.3);
        padding: 15px;
        border-radius: 8px;
        min-width: 80px;
    }
    .time-val { font-size: 32px; font-weight: bold; color: #fff; display: block; }
    .time-label { font-size: 12px; text-transform: uppercase; letter-spacing: 1px; }

    /* --- DANH SÁCH SẢN PHẨM --- */
    .promo-section { padding: 60px 0; background: #fdfdfd; }
    .container-custom { max-width: 1200px; margin: auto; padding: 0 15px; }
    
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
    }

    /* Card sản phẩm cao cấp */
    .promo-card {
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        position: relative;
        border: 1px solid #eee;
    }
    .promo-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }

    /* Nhãn giảm giá (-30%) */
    .discount-badge {
        position: absolute;
        top: 15px; left: 15px;
        background: #e74c3c; /* Màu đỏ khuyến mãi */
        color: white;
        padding: 5px 10px;
        font-weight: bold;
        border-radius: 4px;
        font-size: 14px;
        z-index: 2;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }

    .card-img {
        width: 100%;
        height: 250px;
        object-fit: cover;
        transition: transform 0.5s;
    }
    .promo-card:hover .card-img { transform: scale(1.05); } /* Zoom ảnh nhẹ khi hover */

    .card-body { padding: 20px; text-align: center; }
    .prod-name {
        font-size: 18px;
        color: #2c3e50;
        font-weight: 600;
        margin-bottom: 10px;
        display: block;
        text-decoration: none;
    }
    .prod-name:hover { color: #bfa15f; }

    .price-box { margin-bottom: 15px; }
    .old-price {
        text-decoration: line-through;
        color: #999;
        font-size: 14px;
        margin-right: 10px;
    }
    .new-price {
        color: #e74c3c;
        font-size: 20px;
        font-weight: bold;
    }

    .btn-buy {
        display: inline-block;
        padding: 10px 25px;
        background: #2c3e50;
        color: white;
        text-decoration: none;
        border-radius: 25px;
        font-size: 14px;
        font-weight: bold;
        transition: 0.3s;
    }
    .btn-buy:hover { background: #bfa15f; color: white; }

    /* Khi không có sản phẩm nào */
    .no-promo {
        text-align: center;
        padding: 50px;
        font-size: 18px;
        color: #777;
    }
</style>

<div class="promo-banner">
    <div class="promo-title">SIÊU SALE THÁNG 11</div>
    <div class="promo-subtitle">Ưu đãi lên đến 50% - Duy nhất trong tuần này!</div>
    
    <div class="countdown-container" id="countdown">
        <div class="time-box"><span class="time-val" id="days">00</span><span class="time-label">Ngày</span></div>
        <div class="time-box"><span class="time-val" id="hours">00</span><span class="time-label">Giờ</span></div>
        <div class="time-box"><span class="time-val" id="minutes">00</span><span class="time-label">Phút</span></div>
        <div class="time-box"><span class="time-val" id="seconds">00</span><span class="time-label">Giây</span></div>
    </div>
</div>

<section class="promo-section">
    <div class="container-custom">
        <h2 style="text-align:center; margin-bottom:40px; color:#2c3e50; text-transform:uppercase; border-bottom: 2px solid #bfa15f; display:inline-block; padding-bottom:10px; position:relative; left:50%; transform:translateX(-50%);">
            Sản phẩm đang giảm giá
        </h2>

        <div class="product-grid">
            <?php
            if (mysqli_num_rows($result) > 0) {
                while ($row = mysqli_fetch_assoc($result)) {
                    // Tính toán % giảm giá
                    // Công thức: (Giá cũ - Giá mới) / Giá cũ * 100
                    $old = $row['old_price'];
                    $curr = $row['price'];
                    $percent = 0;
                    if($old > 0) {
                        $percent = round((($old - $curr) / $old) * 100);
                    }

                    $imgUrl = !empty($row['image_url']) ? $row['image_url'] : 'picture/no-image.jpg';
                    ?>
                    
                    <div class="promo-card">
                        <div class="discount-badge">-<?php echo $percent; ?>%</div>
                        
                        <a href="product_detail.php?id=<?php echo $row['id']; ?>" style="display:block; overflow:hidden;">
                            <img src="<?php echo $imgUrl; ?>" alt="<?php echo $row['name']; ?>" class="card-img">
                        </a>
                        
                        <div class="card-body">
                            <a href="product_detail.php?id=<?php echo $row['id']; ?>" class="prod-name">
                                <?php echo $row['name']; ?>
                            </a>
                            
                            <div class="price-box">
                                <span class="old-price"><?php echo number_format($old, 0, ',', '.'); ?>đ</span>
                                <span class="new-price"><?php echo number_format($curr, 0, ',', '.'); ?>đ</span>
                            </div>
                            
                            <button onclick="addToCart(this)"
                                    class="btn-buy"
                                    style="cursor:pointer; border:none;"
                                    data-id="<?php echo $row['id']; ?>"
                                    data-name="<?php echo $row['name']; ?>"
                                    data-price="<?php echo $curr; ?>"
                                    data-image="<?php echo $imgUrl; ?>">
                                <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                            </button>
                        </div>
                    </div>

                    <?php
                }
            } else {
                echo "<div class='no-promo' style='grid-column: 1/-1;'>
                        <img src='picture/empty-cart.png' style='width:100px; display:block; margin:0 auto 20px auto; opacity:0.5;'>
                        <h3>Hiện chưa có chương trình khuyến mãi nào.</h3>
                        <p>Vui lòng quay lại sau nhé!</p>
                      </div>";
            }
            ?>
        </div>
    </div>
</section>

<script>
    // Đặt ngày kết thúc khuyến mãi (Ví dụ: 3 ngày tính từ hôm nay)
    // Bạn có thể chỉnh lại ngày cụ thể: new Date("Oct 30, 2025 23:59:59")
    let countDownDate = new Date();
    countDownDate.setDate(countDownDate.getDate() + 3); 
    countDownDate = countDownDate.getTime();

    let x = setInterval(function() {
        let now = new Date().getTime();
        let distance = countDownDate - now;

        let days = Math.floor(distance / (1000 * 60 * 60 * 24));
        let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        let seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("days").innerHTML = days < 10 ? "0" + days : days;
        document.getElementById("hours").innerHTML = hours < 10 ? "0" + hours : hours;
        document.getElementById("minutes").innerHTML = minutes < 10 ? "0" + minutes : minutes;
        document.getElementById("seconds").innerHTML = seconds < 10 ? "0" + seconds : seconds;

        if (distance < 0) {
            clearInterval(x);
            document.getElementById("countdown").innerHTML = "<h3 style='color:white'>Chương trình đã kết thúc</h3>";
        }
    }, 1000);
</script>

<?php include 'footer.php'; ?>