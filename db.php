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
// 1. Chỉnh giờ PHP về Việt Nam
date_default_timezone_set('Asia/Ho_Chi_Minh');

// 2. Chỉnh giờ MySQL về Việt Nam (GMT+7)
mysqli_query($conn, "SET time_zone = '+07:00'");
?>