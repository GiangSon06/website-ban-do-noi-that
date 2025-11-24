<?php
session_start();
include 'db.php';

$message = "";

// Kiểm tra xem trên URL có Email và Token không
if (isset($_GET['email']) && isset($_GET['token'])) {
$email = mysqli_real_escape_string($conn, $_GET['email']);
$token = mysqli_real_escape_string($conn, $_GET['token']);

// Kiểm tra token có đúng và còn hạn không
$sql = "SELECT * FROM users WHERE email='$email' AND reset_token='$token' AND reset_expire > NOW()";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) == 0) {
die("<h3 style='text-align:center; margin-top:50px; color:red'>Link đặt lại mật khẩu không hợp lệ hoặc đã hết hạn!</h3>");
}
} else {
header("Location: login.php");
exit();
}

// Xử lý khi bấm nút Đổi mật khẩu
if (isset($_POST['doi_mat_khau'])) {
$pass1 = $_POST['pass1'];
$pass2 = $_POST['pass2'];

if ($pass1 === $pass2) {
// Mã hóa mật khẩu mới
$new_pass_hash = password_hash($pass1, PASSWORD_DEFAULT);

// Cập nhật pass mới và xóa token cũ đi
$sql_update = "UPDATE users SET password='$new_pass_hash', reset_token=NULL, reset_expire=NULL WHERE email='$email'";

if (mysqli_query($conn, $sql_update)) {
echo "<script>alert('Đổi mật khẩu thành công! Mời bạn đăng nhập lại.'); window.location.href='login.php';</script>";
} else {
$message = "<p style='color:red'>Lỗi hệ thống!</p>";
}
} else {
$message = "<p style='color:red'>Mật khẩu nhập lại không khớp!</p>";
}
}
?>

<?php $pageTitle = "Đặt lại mật khẩu"; include 'header.php'; ?>

<div style="max-width: 400px; margin: 50px auto; padding: 30px; border: 1px solid #ddd; border-radius: 8px; text-align: center;">
<h2>Đặt lại mật khẩu mới</h2>
<?php echo $message; ?>

<form method="POST">
<input type="password" name="pass1" required placeholder="Mật khẩu mới..."
style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px;">

<input type="password" name="pass2" required placeholder="Nhập lại mật khẩu..."
style="width: 100%; padding: 10px; margin: 10px 0; border: 1px solid #ccc; border-radius: 4px;">

<button type="submit" name="doi_mat_khau"
style="width: 100%; padding: 10px; background: #2c3e50; color: white; border: none; font-weight: bold; border-radius: 4px; cursor: pointer;">
XÁC NHẬN ĐỔI
</button>
</form>
</div>

<?php include 'footer.php'; ?>