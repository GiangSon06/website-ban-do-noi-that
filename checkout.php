<?php
session_start();
include 'db.php';

// Chặn nếu chưa đăng nhập
if (!isset($_SESSION['user_id'])) {
header("Location: login.php");
exit();
}
$pageTitle = "Thanh toán";
include 'header.php';
?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.21.1/axios.min.js"></script>

<div style="max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
<h2 style="text-align: center;">Thông tin thanh toán</h2>

<form id="checkoutForm" method="POST" action="process_order.php">
<input type="hidden" name="cart_data" id="cart_data">
<input type="hidden" name="total_money" id="total_money_input">

<div class="input-group">
<label>Họ và tên người nhận:</label>
<input type="text" name="fullname" required style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px;">
</div>

<div class="input-group">
<label>Số điện thoại:</label>
<input type="text" name="phone" required style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px;">
</div>

<div class="input-group" style="margin-bottom: 15px;">
<label>Địa chỉ nhận hàng:</label>

<select id="province" style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px;">
<option value="">-- Chọn Tỉnh/Thành phố --</option>
</select>

<select id="district" style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px;" disabled>
<option value="">-- Chọn Quận/Huyện --</option>
</select>

<select id="ward" style="width: 100%; padding: 10px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 4px;" disabled>
<option value="">-- Chọn Phường/Xã --</option>
</select>

<input type="text" id="house_number" placeholder="Số nhà, tên đường cụ thể..." 
style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px;">

<input type="hidden" name="address" id="final_address">
</div>
<div class="input-group">
<label>Phương thức thanh toán:</label>
<select name="payment_method" style="width: 100%; padding: 10px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 4px;">
<option value="cod">Thanh toán khi nhận hàng (COD)</option>
<option value="banking">Chuyển khoản ngân hàng</option>
</select>
</div>

<div style="margin-bottom: 20px; font-weight: bold; font-size: 18px;">
Tổng thanh toán: <span id="checkout_total" style="color: red;">0đ</span>
</div>

<button type="submit" onclick="return checkAddress()" style="width: 100%; padding: 15px; background: #bfa15f; color: white; border: none; font-weight: bold; cursor: pointer; border-radius: 4px;">XÁC NHẬN ĐẶT HÀNG</button>
</form>
</div>

<script>
// --- PHẦN 1: LOGIC XỬ LÝ GIỎ HÀNG (GIỮ NGUYÊN CODE CỦA BẠN) ---
document.addEventListener("DOMContentLoaded", function() {
let cart = JSON.parse(localStorage.getItem("MY_CART")) || [];

if(cart.length === 0) {
alert("Giỏ hàng trống!");
window.location.href = "index.php";
}

// Tính tổng tiền
let total = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);

// Gán dữ liệu vào các input ẩn để PHP nhận được
document.getElementById('cart_data').value = JSON.stringify(cart);
document.getElementById('total_money_input').value = total;

// Hiển thị tổng tiền
document.getElementById('checkout_total').innerText = total.toLocaleString('vi-VN') + 'đ';
});

// --- PHẦN 2: LOGIC API ĐỊA CHỈ (MỚI THÊM) ---
const host = "https://provinces.open-api.vn/api/";

var callAPI = (api) => {
return axios.get(api).then((response) => {
renderData(response.data, "province");
});
}
callAPI('https://provinces.open-api.vn/api/?depth=1');

var callApiDistrict = (api) => {
return axios.get(api).then((response) => {
renderData(response.data.districts, "district");
});
}
var callApiWard = (api) => {
return axios.get(api).then((response) => {
renderData(response.data.wards, "ward");
});
}

var renderData = (array, select) => {
let row = ' <option disable value="">Chọn</option>';
array.forEach(element => {
row += `<option data-name="${element.name}" value="${element.code}">${element.name}</option>`
});
document.querySelector("#" + select).innerHTML = row;
}

$("#province").change(() => {
let provinceCode = $("#province").val();
if(provinceCode){
callApiDistrict(host + "p/" + provinceCode + "?depth=2");
$("#district").removeAttr("disabled");
printResult();
}
});

$("#district").change(() => {
let districtCode = $("#district").val();
if(districtCode){
callApiWard(host + "d/" + districtCode + "?depth=2");
$("#ward").removeAttr("disabled");
printResult();
}
});

$("#ward, #house_number").change(() => {
printResult();
});

// Hàm ghép địa chỉ
var printResult = () => {
let provinceName = $("#province option:selected").data("name") || "";
let districtName = $("#district option:selected").data("name") || "";
let wardName = $("#ward option:selected").data("name") || "";
let house = $("#house_number").val();

if (provinceName && districtName && wardName) {
let fullAddress = `${house}, ${wardName}, ${districtName}, ${provinceName}`;
// Gán giá trị vào ô input ẩn có name="address"
$("#final_address").val(fullAddress);
}
}

// Hàm kiểm tra trước khi submit (đảm bảo khách đã chọn đủ địa chỉ)
function checkAddress() {
let finalAddr = $("#final_address").val();
if (!finalAddr || finalAddr.trim() === "") {
alert("Vui lòng chọn đầy đủ Tỉnh, Huyện, Xã và nhập số nhà!");
return false; // Chặn không cho gửi form
}
return true;
}
</script>

<?php include 'footer.php'; ?>