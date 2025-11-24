<?php
session_start();
include 'db.php';

// Gọi thư viện PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$message = "";

if (isset($_POST['gui_yeu_cau'])) {
$email = mysqli_real_escape_string($conn, $_POST['email']);

// 1. Kiểm tra email có tồn tại không
$check = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

if (mysqli_num_rows($check) > 0) {
// 2. Tạo token ngẫu nhiên và thời hạn (ví dụ 15 phút)
$token = bin2hex(random_bytes(16));
$expire = date("Y-m-d H:i:s", time() + 60 * 15); // Hết hạn sau 15 phút

// 3. Lưu token vào DB
$sql = "UPDATE users SET reset_token='$token', reset_expire='$expire' WHERE email='$email'";
mysqli_query($conn, $sql);

// 4. Gửi Email (Cấu hình Gmail)
$mail = new PHPMailer(true);
try {
// Cấu hình Server
$mail->isSMTP();
$mail->Host   = 'smtp.gmail.com';
$mail->SMTPAuth   = true;
$mail->Username   = 'Dangnamson24@gmail.com'; // <--- THAY EMAIL CỦA BẠN VÀO ĐÂY
$mail->Password   = 'lkmj dilf ooyf wyjx'; // <--- THAY MẬT KHẨU ỨNG DỤNG VÀO ĐÂY (Ko phải pass đăng nhập)
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
$mail->Port   = 587;
$mail->CharSet= 'UTF-8';

// Người gửi - Người nhận
$mail->setFrom('email_cua_ban@gmail.com', 'Noi That SLAND Support');
$mail->addAddress($email);

// Nội dung
$link = "http://localhost/donoithat1/datlaimatkhau.php?email=$email&token=$token";

$mail->isHTML(true);
$mail->Subject = 'Khôi phục mật khẩu - Nội thất SLAND';
$mail->Body= "Chào bạn, <br> Bấm vào link sau để đặt lại mật khẩu: <a href='$link'>$link</a> <br> Link hết hạn sau 15 phút.";

$mail->send();
$message = "<p style='color:green'>Đã gửi link khôi phục vào email. Vui lòng kiểm tra hộp thư!</p>";
} catch (Exception $e) {
$message = "<p style='color:red'>Không thể gửi mail. Lỗi: {$mail->ErrorInfo}</p>";
}
} else {
$message = "<p style='color:red'>Email này chưa được đăng ký!</p>";
}
}
?>

<?php $pageTitle = "Quên mật khẩu"; include 'header.php'; ?>

<div style="max-width: 400px; margin: 50px auto; padding: 30px; border: 1px solid #ddd; border-radius: 8px; text-align: center;">
<h2>Quên mật khẩu?</h2>
<p>Nhập email của bạn để lấy lại mật khẩu.</p>
<?php echo $message; ?>

<form method="POST">
<input type="email" name="email" required placeholder="Nhập email của bạn..."
style="width: 100%; padding: 10px; margin: 15px 0; border: 1px solid #ccc; border-radius: 4px;">
<button type="submit" name="gui_yeu_cau"
style="width: 100%; padding: 10px; background: #bfa15f; color: white; border: none; font-weight: bold; border-radius: 4px; cursor: pointer;">
GỬI YÊU CẦU
</button>
</form>
<br>
<a href="login.php" style="text-decoration: none; color: #555;">&larr; Quay lại đăng nhập</a>
</div>

<?php include 'footer.php'; ?>