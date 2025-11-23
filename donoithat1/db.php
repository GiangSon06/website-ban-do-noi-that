<?php
$servername = "localhost";
$username = "root"; // Mặc định XAMPP
$password = "Giangson@05";     // Mặc định XAMPP để trống
$dbname = "donoithat"; // Đảm bảo bạn đã tạo DB tên này trong phpMyAdmin

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
die("Kết nối thất bại: " . mysqli_connect_error());
}
// Đặt bảng mã để không lỗi font tiếng Việt
mysqli_set_charset($conn, "utf8");
?>