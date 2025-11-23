<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
header("Location: login.php");
exit();
}

// Xử lý xóa đánh giá
if (isset($_GET['delete'])) {
$id = $_GET['delete'];
mysqli_query($conn, "DELETE FROM reviews WHERE id=$id");
header("Location: admin_reviews.php");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<title>Quản lý Đánh giá</title>
<style>
table { width: 100%; border-collapse: collapse; margin-top: 20px; }
th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
th { background: #333; color: white; }
.btn-del { color: white; background: red; text-decoration: none; padding: 5px 10px; border-radius: 4px; }
</style>
</head>
<body>
<a href="admin.php">Quay lại Dashboard</a>
<h2>Danh sách đánh giá của khách hàng</h2>

<table>
<tr>
<th>ID</th>
<th>Khách hàng</th>
<th>Nội dung</th>
<th>Ngày viết</th>
<th>Hành động</th>
</tr>
<?php
$sql = "SELECT r.*, u.username FROM reviews r JOIN users u ON r.user_id = u.id ORDER BY r.created_at DESC";
$result = mysqli_query($conn, $sql);
while ($row = mysqli_fetch_assoc($result)) {
echo "<tr>";
echo "<td>{$row['id']}</td>";
echo "<td>{$row['username']}</td>";
echo "<td>{$row['content']}</td>";
echo "<td>{$row['created_at']}</td>";
echo "<td><a href='admin_reviews.php?delete={$row['id']}' class='btn-del' onclick='return confirm(\"Xóa đánh giá này?\")'>Xóa</a></td>";
echo "</tr>";
}
?>
</table>
</body>
</html>