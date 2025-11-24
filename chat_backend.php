<?php
session_start();
include 'db.php';

$action = isset($_POST['action']) ? $_POST['action'] : '';

// 1. Gửi tin nhắn
if ($action == 'send') {
$sender_id = $_SESSION['user_id'];
// Nếu là user thường thì gửi cho Admin (ID cố định là 1 hoặc 0 tùy bạn chỉnh)
// Ở đây mình để Admin có ID là 4 (theo ảnh DB cũ của bạn).
// Nếu người gửi là Admin (role='admin') thì receiver lấy từ post

$msg = mysqli_real_escape_string($conn, $_POST['message']);

if ($_SESSION['role'] == 'admin') {
$receiver_id = $_POST['receiver_id']; // Admin gửi cho user cụ thể
} else {
$receiver_id = 4; // User gửi cho Admin (ID 4 là admin trong ảnh bạn gửi)
}

if (!empty($msg)) {
$sql = "INSERT INTO messages (sender_id, receiver_id, message) VALUES ('$sender_id', '$receiver_id', '$msg')";
mysqli_query($conn, $sql);
}
}

// 2. Lấy tin nhắn (Load hội thoại)
if ($action == 'load') {
// Nếu là Admin, cần biết đang chat với ai
if ($_SESSION['role'] == 'admin') {
$user_id = $_POST['partner_id']; // ID của khách hàng đang chat cùng
$my_id = $_SESSION['user_id'];
} else {
$user_id = $_SESSION['user_id']; // ID của khách
$my_id = 4; // ID của Admin
}

// Lấy tin nhắn 2 chiều giữa Admin và User này
$sql = "SELECT * FROM messages 
WHERE (sender_id = '$user_id' AND receiver_id = '$my_id') 
OR (sender_id = '$my_id' AND receiver_id = '$user_id') 
ORDER BY created_at ASC";

$result = mysqli_query($conn, $sql);

while ($row = mysqli_fetch_assoc($result)) {
// Kiểm tra xem tin nhắn này là của "Tôi" hay "Đối phương"
$is_me = ($row['sender_id'] == $_SESSION['user_id']) ? 'me' : 'other';

echo '<div class="msg ' . $is_me . '">';
echo '<span>' . htmlspecialchars($row['message']) . '</span>';
echo '</div>';
}
}
?>