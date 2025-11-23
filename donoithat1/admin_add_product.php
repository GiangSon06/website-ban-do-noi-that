<?php
session_start();
include 'db.php';

// 1. Check quyền Admin chặt chẽ
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
header("Location: login.php");
exit();
}

$message = "";

if (isset($_POST['add_product'])) {
// 2. Xử lý dữ liệu đầu vào an toàn (Chống lỗi khi tên có dấu nháy ')
$name = mysqli_real_escape_string($conn, $_POST['name']);
$price = mysqli_real_escape_string($conn, $_POST['price']);
$desc = mysqli_real_escape_string($conn, $_POST['description']);
$cat_id = (int)$_POST['category_id']; // Ép kiểu số cho ID danh mục

// Xử lý upload ảnh
if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
$target_dir = "picture/";
// Tạo tên file ngẫu nhiên để tránh trùng lặp (ví dụ: sp_123123.jpg)
$file_extension = pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
$new_file_name = "sp_" . time() . "." . $file_extension;
$target_file = $target_dir . $new_file_name;

// Kiểm tra file ảnh có hợp lệ không
$check = getimagesize($_FILES["image"]["tmp_name"]);
if ($check !== false) {
if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {

// 3. Lưu vào DB (Cột price trong DB phải là DECIMAL(15,0) hoặc BIGINT)
$sql = "INSERT INTO products (name, price, description, category_id, image_url) 
VALUES ('$name', '$price', '$desc', '$cat_id', '$target_file')";

if (mysqli_query($conn, $sql)) {
$message = "<p style='color:green; font-weight:bold;'>✅ Thêm sản phẩm thành công!</p>";
} else {
// In lỗi chi tiết để debug
$message = "<p style='color:red'>❌ Lỗi SQL: " . mysqli_error($conn) . "</p>";
}
} else {
$message = "<p style='color:red'>❌ Không thể lưu file ảnh vào thư mục picture/.</p>";
}
} else {
$message = "<p style='color:red'>❌ File tải lên không phải là ảnh.</p>";
}
} else {
$message = "<p style='color:red'>❌ Vui lòng chọn ảnh sản phẩm.</p>";
}
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thêm sản phẩm - Admin</title>
<style>
body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; background-color: #f4f4f4; }
.container { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); max-width: 500px; margin: auto; }
input, textarea, select { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
button { background: #bfa15f; color: white; padding: 12px; border: none; width: 100%; cursor: pointer; font-size: 16px; border-radius: 4px; font-weight: bold; }
button:hover { background: #a38645; }
h2 { text-align: center; color: #333; }
.back-link { display: block; margin-bottom: 20px; text-decoration: none; color: #555; }
</style>
</head>
<body>

<div class="container">
<a href="admin.php" class="back-link">← Quay lại Dashboard</a>
<h2>Thêm Sản Phẩm Mới</h2>

<?php echo $message; ?>

<form method="POST" enctype="multipart/form-data">
<label>Tên sản phẩm:</label>
<input type="text" name="name" placeholder="Ví dụ: Sofa da cao cấp" required>

<label>Giá tiền (VNĐ):</label>
<input type="number" name="price" placeholder="Ví dụ: 5000000" required min="0">

<label>Mô tả:</label>
<textarea name="description" rows="4" placeholder="Mô tả chi tiết sản phẩm..."></textarea>

<label>Danh mục:</label>
<select name="category_id">
<option value="3">Phòng khách</option>
<option value="1">Phòng ngủ</option>
<option value="2">Phòng ăn</option>
<option value="4">Học tập</option>
</select>

<label>Ảnh đại diện:</label>
<input type="file" name="image" accept="image/*" required>

<button type="submit" name="add_product">Lưu Sản Phẩm</button>
</form>
</div>

</body>
</html>