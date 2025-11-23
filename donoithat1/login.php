<?php
session_start();
include 'db.php';

$message = "";

// Nếu đã đăng nhập rồi thì đẩy về trang chủ hoặc admin
if (isset($_SESSION['user_id'])) {
if ($_SESSION['role'] == 'admin') {
header("Location: admin.php");
} else {
header("Location: index.php");
}
exit();
}

if (isset($_POST['login'])) {
$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $sql);

if (mysqli_num_rows($result) === 1) {
$row = mysqli_fetch_assoc($result);

// Kiểm tra mật khẩu hash
if (password_verify($password, $row['password'])) {
// LƯU SESSION
$_SESSION['user_id'] = $row['id'];
$_SESSION['username'] = $row['username'];
$_SESSION['role'] = $row['role'];

// Điều hướng
if ($row['role'] == 'admin') {
header("Location: admin.php");
} else {
header("Location: index.php");
}
exit();
} else {
$message = "<p class='error'>Mật khẩu không đúng!</p>";
}
} else {
$message = "<p class='error'>Tài khoản không tồn tại!</p>";
}
}
?>

<?php 
$pageTitle = "Đăng nhập";
include 'header.php'; 
?>

<div class="auth-container">
<div class="auth-box">
<h2>Đăng Nhập</h2>
<?php echo $message; ?>

<form method="POST" action="">
<div class="input-group">
<label>Tên đăng nhập</label>
<input type="text" name="username" required placeholder="Nhập tên đăng nhập...">
</div>

<div class="input-group">
<label>Mật khẩu</label>
<input type="password" name="password" required placeholder="Nhập mật khẩu...">
</div>

<button type="submit" name="login" class="btn-auth">Đăng Nhập</button>
</form>

<div class="auth-footer">
<p>Chưa có tài khoản? <a href="register.php">Đăng ký ngay</a></p>
</div>
</div>
</div>

<?php include 'footer.php'; ?>