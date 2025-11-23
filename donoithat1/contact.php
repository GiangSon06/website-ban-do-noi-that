<?php
session_start();
include 'db.php'; // 1. Kết nối database

// 2. Xử lý Logic khi người dùng bấm Gửi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_contact'])) {
    // Lấy dữ liệu và làm sạch để chống lỗi SQL
    $name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $note = mysqli_real_escape_string($conn, $_POST['message']);

    // Kiểm tra dữ liệu rỗng
    if (!empty($name) && !empty($phone)) {
        // Câu lệnh INSERT (Lưu ý: Bạn phải tạo bảng contacts trong database trước nhé)
        $sql = "INSERT INTO contacts (full_name, phone, message) VALUES ('$name', '$phone', '$note')";
        
        if (mysqli_query($conn, $sql)) {
            echo "<script>alert('Cảm ơn bạn! Chúng tôi sẽ liên hệ lại sớm nhất.'); window.location.href='contact.php';</script>";
        } else {
            echo "<script>alert('Lỗi hệ thống: " . mysqli_error($conn) . "');</script>";
        }
    } else {
        echo "<script>alert('Vui lòng điền tên và số điện thoại.');</script>";
    }
}
?>

<?php 
// 3. Đặt tiêu đề và gọi Header
$pageTitle = "Liên hệ tư vấn - Nội Thất SLAND";
include 'header.php'; 
?>

<style>
    .contact-section { padding: 60px 0; background: #f9f9f9; }
    .contact-container { 
        max-width: 1000px; margin: auto; display: flex; 
        background: white; box-shadow: 0 15px 30px rgba(0,0,0,0.1); 
        border-radius: 12px; overflow: hidden;
    }
    /* Cột ảnh bên trái */
    .contact-image { 
        flex: 1; 
        background: url('picture/slide2.jpg') center/cover no-repeat; 
        min-height: 500px; position: relative;
    }
    .contact-overlay {
        position: absolute; bottom: 0; left: 0; right: 0; 
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent); 
        color: white; padding: 30px;
    }
    /* Cột Form bên phải */
    .contact-form-wrapper { flex: 1; padding: 40px 50px; }
    .form-group { margin-bottom: 20px; }
    .form-label { font-weight: 600; font-size: 14px; color: #333; display: block; margin-bottom: 8px; }
    .form-input { 
        width: 100%; padding: 12px 15px; 
        border: 1px solid #e0e0e0; border-radius: 6px; 
        font-size: 14px; transition: 0.3s; box-sizing: border-box;
    }
    .form-input:focus { border-color: #bfa15f; outline: none; box-shadow: 0 0 5px rgba(191, 161, 95, 0.3); }
    
    .btn-submit {
        width: 100%; padding: 15px; 
        background: #bfa15f; color: white; 
        font-weight: bold; text-transform: uppercase; 
        border: none; border-radius: 6px; cursor: pointer; 
        transition: all 0.3s ease;
        font-size: 15px; margin-top: 10px;
    }
    .btn-submit:hover { background: #a38645; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(191, 161, 95, 0.4); }
    
    /* Responsive trên điện thoại */
    @media (max-width: 768px) {
        .contact-container { flex-direction: column; margin: 15px; }
        .contact-image { min-height: 250px; }
    }
</style>

<section class="contact-section">
    <div class="contact-container">
        
        <div class="contact-image">
            <div class="contact-overlay">
                <h2 style="margin: 0 0 5px 0;">NỘI THẤT SLAND</h2>
                <p style="margin: 0; opacity: 0.9; font-size: 14px;">Kiến tạo không gian - Nâng tầm cuộc sống</p>
            </div>
        </div>

        <div class="contact-form-wrapper">
            <h2 style="margin-bottom: 10px; color: #2c3e50;">Liên hệ tư vấn</h2>
            <p style="margin-bottom: 30px; color: #7f8c8d; font-size: 14px; line-height: 1.6;">
                Hãy để lại thông tin, chuyên viên thiết kế của SLAND sẽ liên hệ tư vấn miễn phí cho bạn trong vòng 30 phút.
            </p>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label class="form-label">Họ và tên <span style="color:red">*</span></label>
                    <input type="text" class="form-input" name="full_name" required placeholder="Nhập họ tên của bạn...">
                </div>

                <div class="form-group">
                    <label class="form-label">Số điện thoại <span style="color:red">*</span></label>
                    <input type="text" class="form-input" name="phone" required placeholder="Nhập số điện thoại...">
                </div>

                <div class="form-group">
                    <label class="form-label">Nội dung cần tư vấn</label>
                    <textarea class="form-input" name="message" rows="4" placeholder="Bạn quan tâm đến sản phẩm nào?"></textarea>
                </div>

                <button type="submit" name="submit_contact" class="btn-submit">
                    GỬI YÊU CẦU TƯ VẤN
                </button>
                
                <div style="margin-top: 20px; text-align: center; font-size: 14px; color: #666;">
                    Hoặc gọi hotline: <a href="tel:0326976832" style="color: #bfa15f; font-weight: bold; text-decoration: none;">032.697.6832</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?php include 'footer.php';?>