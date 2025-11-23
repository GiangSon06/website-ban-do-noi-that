<?php
session_start();
$pageTitle = "Về SLAND - Câu chuyện thương hiệu";
include 'header.php';
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

<style>
    /* --- CSS RIÊNG CHO TRANG ABOUT --- */
    .about-hero {
        background: url('picture/slide2.jpg') center/cover no-repeat fixed; /* Hiệu ứng Parallax */
        height: 400px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        color: white;
        text-align: center;
    }
    .about-hero::after {
        content: "";
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.6); /* Lớp phủ tối để chữ nổi bật */
    }
    .hero-content { position: relative; z-index: 2; max-width: 800px; padding: 20px; }
    .hero-content h1 { font-size: 48px; margin-bottom: 10px; font-family: serif; letter-spacing: 2px; }
    .hero-content p { font-size: 18px; opacity: 0.9; }

    /* Section chung */
    .section-padding { padding: 80px 0; }
    .container-custom { max-width: 1140px; margin: auto; padding: 0 20px; }
    
    /* Câu chuyện thương hiệu (Split Layout) */
    .story-section { display: flex; align-items: center; gap: 50px; flex-wrap: wrap; }
    .story-img { flex: 1; min-width: 300px; }
    .story-img img { width: 100%; border-radius: 10px; box-shadow: 20px 20px 0px #f0f0f0; }
    .story-content { flex: 1; min-width: 300px; }
    .section-title { font-size: 32px; color: #2c3e50; margin-bottom: 20px; position: relative; padding-bottom: 15px; }
    .section-title::after {
        content: ""; position: absolute; bottom: 0; left: 0;
        width: 60px; height: 3px; background: #bfa15f;
    }
    .story-content p { color: #666; line-height: 1.8; margin-bottom: 20px; text-align: justify; }

    /* Số liệu ấn tượng */
    .stats-section { background: #f9f9f9; text-align: center; }
    .stats-grid { display: flex; justify-content: space-around; flex-wrap: wrap; gap: 30px; }
    .stat-item h3 { font-size: 42px; color: #bfa15f; margin: 0; font-weight: bold; }
    .stat-item p { color: #555; font-weight: 600; margin-top: 5px; }

    /* Giá trị cốt lõi */
    .values-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 40px; }
    .value-card { 
        background: white; padding: 40px 30px; text-align: center; 
        border: 1px solid #eee; transition: 0.3s; border-radius: 8px;
    }
    .value-card:hover { transform: translateY(-10px); box-shadow: 0 15px 30px rgba(0,0,0,0.1); border-color: #bfa15f; }
    .value-icon { font-size: 40px; color: #bfa15f; margin-bottom: 20px; }
    .value-card h4 { font-size: 20px; color: #2c3e50; margin-bottom: 15px; }
    .value-card p { color: #777; font-size: 14px; line-height: 1.6; }

    /* Responsive mobile */
    @media (max-width: 768px) {
        .about-hero { height: 300px; }
        .hero-content h1 { font-size: 32px; }
        .story-section { flex-direction: column; }
    }
</style>

<section class="about-hero">
    <div class="hero-content">
        <h1>NỘI THẤT SLAND</h1>
        <p>Kiến tạo không gian - Khẳng định đẳng cấp sống</p>
    </div>
</section>

<section class="section-padding">
    <div class="container-custom">
        <div class="story-section">
            <div class="story-img">
                <img src="picture/slide2.jpg" alt="Showroom SLAND">
            </div>
            <div class="story-content">
                <h2 class="section-title">Về Chúng Tôi</h2>
                <p>
                    <strong>Nội thất SLAND</strong> không chỉ là một thương hiệu nội thất, mà là người kể chuyện cho ngôi nhà của bạn. Được thành lập với niềm đam mê mãnh liệt về kiến trúc và nghệ thuật sắp đặt, chúng tôi tin rằng mỗi món đồ nội thất đều mang một linh hồn riêng.
                </p>
                <p>
                    Chúng tôi cung cấp những sản phẩm nội thất đa dạng cho <strong>phòng khách, phòng ngủ, phòng ăn</strong> và không gian làm việc. Sự kết hợp giữa thiết kế hiện đại, chất liệu bền vững và giá cả hợp lý chính là kim chỉ nam hoạt động của SLAND.
                </p>
                <p>
                    Với phương châm <em>"Chất lượng tạo niềm tin – Phong cách khẳng định giá trị"</em>, SLAND cam kết đồng hành cùng bạn trên hành trình xây dựng tổ ấm mơ ước.
                </p>
            </div>
        </div>
    </div>
</section>

<section class="section-padding stats-section">
    <div class="container-custom">
        <div class="stats-grid">
            <div class="stat-item">
                <h3>10+</h3>
                <p>Năm Kinh Nghiệm</p>
            </div>
            <div class="stat-item">
                <h3>1.500+</h3>
                <p>Dự Án Hoàn Thành</p>
            </div>
            <div class="stat-item">
                <h3>5.000+</h3>
                <p>Khách Hàng Hài Lòng</p>
            </div>
            <div class="stat-item">
                <h3>300+</h3>
                <p>Mẫu Mã Độc Quyền</p>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container-custom">
        <div style="text-align: center; max-width: 700px; margin: 0 auto 50px auto;">
            <h2 class="section-title" style="display: inline-block;">Tại sao chọn SLAND?</h2>
            <p style="color: #777; margin-top: 15px;">Chúng tôi không chỉ bán sản phẩm, chúng tôi bán sự an tâm và thẩm mỹ.</p>
        </div>

        <div class="values-grid">
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-gem"></i></div>
                <h4>Chất Lượng Cao Cấp</h4>
                <p>Mỗi sản phẩm đều trải qua quy trình kiểm định nghiêm ngặt, sử dụng vật liệu gỗ tự nhiên và da nhập khẩu bền bỉ theo thời gian.</p>
            </div>
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-pencil-ruler"></i></div>
                <h4>Thiết Kế Tinh Tế</h4>
                <p>Đội ngũ thiết kế của SLAND luôn cập nhật các xu hướng nội thất mới nhất từ Ý và Bắc Âu để mang lại vẻ đẹp thời thượng.</p>
            </div>
            <div class="value-card">
                <div class="value-icon"><i class="fas fa-headset"></i></div>
                <h4>Tận Tâm Phục Vụ</h4>
                <p>Dịch vụ tư vấn 24/7, bảo hành lên đến 5 năm và chính sách đổi trả linh hoạt giúp khách hàng hoàn toàn yên tâm.</p>
            </div>
        </div>
    </div>
</section>

<section style="background: #2c3e50; color: white; padding: 60px 0; text-align: center;">
    <div class="container-custom">
        <h2 style="margin-bottom: 20px;">Bạn đã sẵn sàng thay đổi không gian sống?</h2>
        <p style="margin-bottom: 30px; opacity: 0.8;">Hãy ghé thăm showroom của chúng tôi hoặc liên hệ để được tư vấn miễn phí.</p>
        <a href="contact.php" style="background: #bfa15f; color: white; padding: 15px 40px; text-decoration: none; font-weight: bold; border-radius: 30px; transition: 0.3s;">LIÊN HỆ NGAY</a>
    </div>
</section>

<?php include 'footer.php'; ?>