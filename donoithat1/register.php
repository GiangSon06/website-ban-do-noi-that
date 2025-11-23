<?php
session_start();
include 'db.php';

$message = "";

if (isset($_POST['register'])) {
$username = mysqli_real_escape_string($conn, $_POST['username']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Kiểm tra mật khẩu nhập lại
if ($password !== $confirm_password) {
$message = "<p class='error'>Mật khẩu nhập lại không khớp!</p>";
} else {
// Kiểm tra xem user đã tồn tại chưa
$check = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
$rs = mysqli_query($conn, $check);

if (mysqli_num_rows($rs) > 0) {
$message = "<p class='error'>Tên đăng nhập hoặc Email đã tồn tại!</p>";
} else {
// Mã hóa mật khẩu
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert vào DB (Mặc định role là 'user')
$sql = "INSERT INTO users (username, email, password, role) VALUES ('$username', '$email', '$hashed_password', 'user')";

if (mysqli_query($conn, $sql)) {
$message = "<p class='success'>Đăng ký thành công! <a href='login.php'>Đăng nhập ngay</a></p>";
} else {
$message = "<p class='error'>Lỗi hệ thống: " . mysqli_error($conn) . "</p>";
}
}
}
}
?>

<?php
$pageTitle = "Đăng ký tài khoản";
include 'header.php';
?>

<div class="auth-container">
<div class="auth-box">
<h2>Đăng Ký Thành Viên</h2>
<?php echo $message; ?>

<form method="POST" action="">
<div class="input-group">
<label>Tên đăng nhập</label>
<input type="text" name="username" required placeholder="Nhập tên đăng nhập...">
</div>

<div class="input-group">
<label>Email</label>
<input type="email" name="email" required placeholder="Nhập email...">
</div>

<div class="input-group">
<label>Mật khẩu</label>
<input type="password" name="password" required placeholder="Nhập mật khẩu...">
</div>

<div class="input-group">
<label>Nhập lại mật khẩu</label>
<input type="password" name="confirm_password" required placeholder="Nhập lại mật khẩu...">
</div>

<button type="submit" name="register" class="btn-auth">Đăng Ký</button>
</form>

<div class="auth-footer">
<p>Đã có tài khoản? <a href="login.php">Đăng nhập tại đây</a></p>
</div>
</div>
</div>

<?php include 'footer.php'; ?>