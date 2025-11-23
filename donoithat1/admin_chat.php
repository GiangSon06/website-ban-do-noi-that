<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
header("Location: login.php");
exit();
}

// Lấy danh sách những người đã nhắn tin cho Admin (ID 4)
$sql_users = "SELECT DISTINCT users.id, users.username
FROM messages
JOIN users ON messages.sender_id = users.id
WHERE messages.receiver_id = 4 OR messages.sender_id = 4";
$res_users = mysqli_query($conn, $sql_users);

$current_chat_user = isset($_GET['user_id']) ? $_GET['user_id'] : null;
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Admin Chat Support</title>
<style>
body { display: flex; height: 100vh; margin: 0; font-family: sans-serif; }
.user-list { width: 250px; background: #2c3e50; color: white; border-right: 1px solid #ccc; overflow-y: auto; }
.user-list h3 { padding: 20px; text-align: center; border-bottom: 1px solid #34495e; margin: 0; }
.user-item { padding: 15px; border-bottom: 1px solid #34495e; cursor: pointer; display: block; color: #bdc3c7; text-decoration: none; }
.user-item:hover, .user-item.active { background: #34495e; color: white; }

.chat-area { flex: 1; display: flex; flex-direction: column; background: #f4f4f4; }
.messages { flex: 1; padding: 20px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px; }
.input-area { padding: 20px; background: white; border-top: 1px solid #ddd; display: flex; gap: 10px; }
.input-area input { flex: 1; padding: 10px; border: 1px solid #ccc; border-radius: 5px; }
.input-area button { padding: 10px 20px; background: #bfa15f; color: white; border: none; border-radius: 5px; cursor: pointer; }

/* Style tin nhắn giống bên User */
.msg { max-width: 70%; padding: 10px 15px; border-radius: 20px; }
.msg.me { align-self: flex-end; background: #2980b9; color: white; } /* Admin màu xanh dương */
.msg.other { align-self: flex-start; background: white; border: 1px solid #ddd; }
</style>
</head>
<body>

<div class="user-list">
<h3>Danh sách Chat</h3>
<a href="admin.php" style="display:block; padding:10px; text-align:center; background:#c0392b; color:white; text-decoration:none;">Quay lại Dashboard</a>
<?php while ($u = mysqli_fetch_assoc($res_users)): ?>
<a href="?user_id=<?php echo $u['id']; ?>" class="user-item <?php echo ($current_chat_user == $u['id']) ? 'active' : ''; ?>">
👤 <?php echo $u['username']; ?>
</a>
<?php endwhile; ?>
</div>

<div class="chat-area">
<?php if ($current_chat_user): ?>
<div class="messages" id="admin-chat-content">
<p style="text-align:center">Đang tải...</p>
</div>
<div class="input-area">
<input type="text" id="admin-input" placeholder="Nhập tin nhắn trả lời...">
<button onclick="sendAdminMessage()">Gửi</button>
</div>
<?php else: ?>
<div style="margin:auto; text-align:center; color:#777;">
<h2>Chọn một khách hàng để bắt đầu chat</h2>
</div>
<?php endif; ?>
</div>

<script>
const currentUserId = "<?php echo $current_chat_user; ?>";

function loadAdminMessages() {
if (!currentUserId) return;

const formData = new FormData();
formData.append('action', 'load');
formData.append('partner_id', currentUserId);

fetch('chat_backend.php', { method: 'POST', body: formData })
.then(response => response.text())
.then(data => {
document.getElementById('admin-chat-content').innerHTML = data;
});
}

function sendAdminMessage() {
const input = document.getElementById('admin-input');
const msg = input.value.trim();
if (msg === "" || !currentUserId) return;

const formData = new FormData();
formData.append('action', 'send');
formData.append('message', msg);
formData.append('receiver_id', currentUserId); // Gửi cho user đang chọn

fetch('chat_backend.php', { method: 'POST', body: formData })
.then(() => {
input.value = "";
loadAdminMessages();
});
}

if (currentUserId) {
setInterval(loadAdminMessages, 2000);
}
</script>

</body>
</html>