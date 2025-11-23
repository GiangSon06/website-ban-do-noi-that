<footer class="footer">
<div class="footer-container">
<div class="footer-col">
<h3>NỘI THẤT SLAND</h3>
<p>
Nội Thất SLAND là thương hiệu nội thất với nhiều năm kinh nghiệm
trong sản xuất & xuất khẩu nội thất đạt chuẩn quốc tế.
</p>
</div>

<div class="footer-col">
<h3>THÔNG TIN</h3>
<ul>
<li><a href="#">Chính Sách Bán Hàng</a></li>
<li><a href="#">Chính Sách Giao Hàng & Lắp Đặt</a></li>
<li><a href="#">Chính Sách Bảo Hành & Bảo Trì</a></li>
<li><a href="#">Chính Sách Đổi Trả</a></li>
</ul>
</div>

<div class="footer-col">
<h3>THÔNG TIN LIÊN HỆ</h3>
<p><strong>Trụ sở chính:</strong> 41A Đ. Phú Diễn, Bắc Từ Liêm, Hà Nội</p>
<p>Hotline: 0326976832</p>
<p>CSKH: Dangnamson24@gmail.com</p>
</div>

<div class="footer-col">
<h3>FANPAGE</h3>
<iframe
src="https://www.facebook.com/plugins/page.php?
href=https%3A%2F%2Fwww.facebook.com%2Ffacebook
&tabs&width=300&height=200&small_header=false&adapt_container_width=true&hide_cover=false&show_facepile=true"
width="300"
height="200"
style="border: none; overflow: hidden"
scrolling="no"
frameborder="0"
allowfullscreen="true"
></iframe>
</div>
</div>
<div class="footer-bottom">
<div class="social-icons">
<a href="https://www.facebook.com/giang.son.114064"
><img src="picture/facebook.jpg" alt="Facebook"
/></a>
<a href="https://www.youtube.com/@kenjiac1413"
><img src="picture/youtube.png" alt="YouTube"
/></a>
<a href="https://www.instagram.com/nhimsthongthai/"
><img src="picture/instagram.jpg" alt="Instagram"
/></a>
<a href="https://www.tiktok.com/@nhimsthongthai"
><img src="picture/tiktok.jpg" alt="TikTok"
/></a>
</div>
<p>
<a
href="https://www.google.com/maps/place/Hanoi+University+of+Natural+Resources+and+Environment/@21.0470484,105.7581067,16z/data=!4m6!3m5!1s0x313454c3ce577141:0xb1a1ac92701777bc!8m2!3d21.0470486!4d105.7624371!16s%2Fg%2F11b6dylw9c?entry=ttu&g_ep=EgoyMDI1MDkxMC4wIKXMDSoASAFQAw%3D%3D"
target="_blank"
>Chỉ đường đến showroom trên Google Maps</a
>
</p>

<div class="footer-bottom">
<p>&copy; 2024 Nội thất SLAND. All rights reserved.</p>
</div>
</footer>
<div id="chat-widget">
<button id="chat-btn" onclick="toggleChat()">💬 Chat với SLAND</button>

<div id="chat-box" class="hidden">
<div class="chat-header">
<span>Hỗ trợ trực tuyến</span>
<button onclick="toggleChat()">✖</button>
</div>

<div class="chat-body" id="chat-content">
<?php if (!isset($_SESSION['user_id'])): ?>
<p style="text-align:center; padding-top:50px; color:#777;">
Vui lòng <a href="login.php" style="color:#bfa15f">Đăng nhập</a> để chat với nhân viên.
</p>
<?php else: ?>
<p style="text-align:center; color:#999; font-size:12px">Bắt đầu trò chuyện...</p>
<?php endif; ?>
</div>

<?php if (isset($_SESSION['user_id'])): ?>
<div class="chat-footer">
<input type="text" id="msg-input" placeholder="Nhập tin nhắn...">
<button onclick="sendMessage()">Gửi</button>
</div>
<?php endif; ?>
</div>
</div>

<style>
/* CSS cho Chat Widget */
#chat-widget { position: fixed; bottom: 20px; right: 20px; z-index: 1000; font-family: sans-serif; }

#chat-btn {
background: #bfa15f; color: white; border: none;
padding: 15px 25px; border-radius: 30px;
font-weight: bold; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.2);
font-size: 16px; transition: 0.3s;
}
#chat-btn:hover { transform: scale(1.05); background: #a38645; }

#chat-box {
width: 300px; height: 400px; background: white;
border-radius: 10px; box-shadow: 0 5px 20px rgba(0,0,0,0.2);
display: flex; flex-direction: column; overflow: hidden;
position: absolute; bottom: 60px; right: 0;
transition: 0.3s;
}
.hidden { display: none !important; }

.chat-header { background: #2c3e50; color: white; padding: 10px 15px; display: flex; justify-content: space-between; align-items: center; font-weight: bold; }
.chat-header button { background: none; border: none; color: white; font-size: 18px; cursor: pointer; }

.chat-body { flex: 1; padding: 10px; overflow-y: auto; background: #f9f9f9; display: flex; flex-direction: column; gap: 8px; }

/* Bong bóng chat */
.msg { max-width: 80%; padding: 8px 12px; border-radius: 15px; font-size: 14px; line-height: 1.4; word-wrap: break-word; }
.msg.me { align-self: flex-end; background: #bfa15f; color: white; border-bottom-right-radius: 2px; }
.msg.other { align-self: flex-start; background: #e0e0e0; color: #333; border-bottom-left-radius: 2px; }

.chat-footer { padding: 10px; border-top: 1px solid #ddd; display: flex; gap: 5px; background: white; }
.chat-footer input { flex: 1; padding: 8px; border: 1px solid #ccc; border-radius: 20px; outline: none; }
.chat-footer button { background: #2c3e50; color: white; border: none; padding: 8px 15px; border-radius: 20px; cursor: pointer; }
</style>

<script>
function toggleChat() {
document.getElementById('chat-box').classList.toggle('hidden');
scrollToBottom();
}

function scrollToBottom() {
const chatBody = document.getElementById('chat-content');
chatBody.scrollTop = chatBody.scrollHeight;
}

// Gửi tin nhắn
function sendMessage() {
const input = document.getElementById('msg-input');
const msg = input.value.trim();
if (msg === "") return;

const formData = new FormData();
formData.append('action', 'send');
formData.append('message', msg);

fetch('chat_backend.php', { method: 'POST', body: formData })
.then(() => {
input.value = ""; // Xóa ô nhập
loadMessages();   // Tải lại tin nhắn ngay
});
}

// Tải tin nhắn (Gọi liên tục mỗi 2 giây)
function loadMessages() {
// Chỉ chạy nếu chat box đang mở và user đã đăng nhập
const chatBox = document.getElementById('chat-box');
if (!chatBox.classList.contains('hidden')) {
const formData = new FormData();
formData.append('action', 'load');

fetch('chat_backend.php', { method: 'POST', body: formData })
.then(response => response.text())
.then(data => {
document.getElementById('chat-content').innerHTML = data;
// Không auto scroll nếu user đang đọc tin cũ (có thể thêm logic sau)
});
}
}

// Kích hoạt vòng lặp tải tin nhắn (Polling)
setInterval(loadMessages, 2000); // 2000ms = 2 giây

// Cho phép nhấn Enter để gửi
document.getElementById('msg-input')?.addEventListener("keypress", function(event) {
if (event.key === "Enter") {
event.preventDefault();
sendMessage();
}
});
</script>
<script src="script.js"></script>
<script src="review.js"></script>
</body>
</html>