<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
if (!isset($_SESSION['user_id'])) {
echo "Vui lòng đăng nhập để đánh giá!";
exit();
}

$user_id = $_SESSION['user_id'];
$content = mysqli_real_escape_string($conn, $_POST['content']);

if (!empty($content)) {
$sql = "INSERT INTO reviews (user_id, content) VALUES ('$user_id', '$content')";
if (mysqli_query($conn, $sql)) {
echo "success";
} else {
// Thêm exit() để đảm bảo chỉ in ra lỗi này
echo "Lỗi SQL: " . mysqli_error($conn); 
exit(); 
}
} else {
echo "Nội dung không được để trống!";
exit(); // Thêm exit()
}
}
?>