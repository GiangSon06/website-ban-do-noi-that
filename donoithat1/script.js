// --- KHỞI TẠO VÀ CHẠY NGAY KHI TẢI TRANG ---

// Giỏ hàng (Vẫn giữ LocalStorage để hiển thị Popup nhanh, nhưng dữ liệu gốc nằm ở Server)
let cart = JSON.parse(localStorage.getItem("MY_CART")) || [];
const cartCount = document.getElementById("cart-count");
const cartItems = document.getElementById("cart-items");
const cartTotal = document.getElementById("cart-total");
const cartPopup = document.getElementById("cart-popup");

window.onload = function () {
  updateCartUI(); // Cập nhật giao diện popup
  setupSearchListener();
};

function setupSearchListener() {
  const searchBox = document.getElementById("search-box");
  const productCards = document.querySelectorAll(".product-card");
  if (searchBox) {
    searchBox.addEventListener("keyup", function () {
      const keyword = searchBox.value.toLowerCase();
      productCards.forEach((card) => {
        const name = card.getAttribute("data-name").toLowerCase();
        if (name.includes(keyword)) {
          card.style.display = "block";
        } else {
          card.style.display = "none";
        }
      });
    });
  }
}

// --- SLIDER ---
let slideIndex = 0;
const slides = document.querySelectorAll(".banner img, .slides img");
function showSlide() {
  slides.forEach((slide, i) => {
    slide.style.display = i === slideIndex ? "block" : "none";
  });
  slideIndex = (slideIndex + 1) % slides.length;
}
if (slides.length > 0) {
  showSlide();
  setInterval(showSlide, 3000);
}

// --- LOGIC GIỎ HÀNG (QUAN TRỌNG NHẤT) ---

function saveCart() {
  localStorage.setItem("MY_CART", JSON.stringify(cart));
}

// Hàm thêm vào giỏ hàng (Đã nâng cấp AJAX)
function addToCart(button, isBuyNow = false) {
  // 1. Lấy dữ liệu sản phẩm
  const container = button.closest(".product-card") || button; // Hỗ trợ cả trang detail và index

  // Nếu không tìm thấy thẻ chứa data, thử tìm button chính nó
  const id =
    container.getAttribute("data-id") || button.getAttribute("data-id");
  const name =
    container.getAttribute("data-name") || button.getAttribute("data-name");
  const price = parseInt(
    container.getAttribute("data-price") || button.getAttribute("data-price")
  );

  // Lấy ảnh
  let image =
    container.getAttribute("data-img") || button.getAttribute("data-img");
  if (!image) {
    const imgTag = container.querySelector("img");
    if (imgTag) image = imgTag.src;
  }

  // --- BƯỚC 1: CẬP NHẬT GIAO DIỆN CLIENT (POPUP) ---
  // (Giữ cái này để người dùng thấy popup cập nhật ngay lập tức)
  const existing = cart.find((item) => item.id === id);
  if (existing) {
    existing.quantity++;
  } else {
    cart.push({ id, name, price, image, quantity: 1 });
  }
  saveCart();
  updateCartUI(); // Cập nhật danh sách trong popup

  // --- BƯỚC 2: GỬI DỮ LIỆU LÊN SERVER (FIX LỖI MẤT GIỎ HÀNG) ---
  const formData = new FormData();
  formData.append("id", id);
  formData.append("name", name);
  formData.append("price", price);
  formData.append("image", image);

  fetch("add_to_cart.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => response.text())
    .then((totalQty) => {
      // Cập nhật số lượng trên icon giỏ hàng (Badge màu đỏ) từ Server trả về
      if (cartCount) {
        cartCount.innerText = totalQty;
        cartCount.style.display = "inline-block";
      }

      // Xử lý sau khi thêm thành công
      if (isBuyNow) {
        window.location.href = "checkout.php";
      } else {
        alert(`Đã thêm "${name}" vào giỏ hàng!`);
      }
    })
    .catch((error) => {
      console.error("Lỗi:", error);
      alert("Có lỗi xảy ra khi đồng bộ giỏ hàng.");
    });
}

// --- HÀM MUA NGAY ---
function buyNow(button) {
  addToCart(button, true);
}

// Cập nhật giao diện Popup (Dựa trên LocalStorage để nhanh)
function updateCartUI() {
  if (!cartItems || !cartTotal) return;

  cartItems.innerHTML = "";
  let total = 0;

  cart.forEach((item, index) => {
    total += item.price * item.quantity;

    const li = document.createElement("li");
    li.innerHTML = `
            <img src="${item.image}" alt="${
      item.name
    }" style="width:50px;height:50px;object-fit:cover;margin-right:8px;border-radius:5px;">
            <div style="flex:1;">
                <p style="margin:0; font-size:14px;"><strong>${
                  item.name
                }</strong></p>
                <p style="margin:0; font-size:13px; color:#555;">${item.price.toLocaleString(
                  "vi-VN"
                )}đ x ${item.quantity}</p>
            </div>
            <button onclick="removeFromCart(${index})" style="background:red; color:white; border:none; width:20px; height:20px; cursor:pointer; border-radius:50%; font-size:10px; display:flex; align-items:center; justify-content:center;">X</button>
        `;
    li.style.display = "flex";
    li.style.alignItems = "center";
    li.style.marginBottom = "10px";
    li.style.borderBottom = "1px solid #eee";
    li.style.paddingBottom = "8px";

    cartItems.appendChild(li);
  });

  // Chỉ cập nhật tổng tiền popup, số lượng trên icon thì để Server lo
  cartTotal.textContent = total.toLocaleString("vi-VN");
}

// Xóa sản phẩm khỏi Popup (Và cần xóa Session - cái này cần file remove_cart.php nếu muốn chuẩn 100%)
// Ở đây tạm thời xóa visual client
function removeFromCart(index) {
  cart.splice(index, 1);
  saveCart();
  updateCartUI();
  // Lưu ý: Để xóa triệt để trên Server, bạn cần làm thêm chức năng xóa ajax tương tự addToCart
  // Nhưng hiện tại để fix lỗi "Thêm xong chuyển trang mất" thì code này đã đủ.
}

function toggleCart() {
  if (cartPopup) {
    cartPopup.style.display =
      cartPopup.style.display === "block" ? "none" : "block";
  }
}

function clearCart() {
  if (confirm("Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?")) {
    cart = [];
    saveCart();
    updateCartUI();
    // Cần thêm AJAX clear session nếu muốn đồng bộ hoàn toàn
  }
}

function checkout() {
  // Chuyển hướng sang trang thanh toán PHP, PHP sẽ đọc từ Session
  window.location.href = "checkout.php";
}

function filterCategory(category) {
  const products = document.querySelectorAll(".product-card");
  products.forEach((product) => {
    const productCategory = product.getAttribute("data-category");
    if (category === "all") {
      product.style.display = "block";
    } else if (productCategory === category) {
      product.style.display = "block";
    } else {
      product.style.display = "none";
    }
  });
}
