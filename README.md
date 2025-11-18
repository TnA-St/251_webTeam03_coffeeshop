# ☕ Hệ thống Quản lý Quán Cà Phê (Coffee Shop Manager)

Đây là một ứng dụng web đơn giản được xây dựng bằng **PHP thuần** và **MySQL** để quản lý sản phẩm và đơn hàng cho quán cà phê. Giao diện được thiết kế hiện đại với hiệu ứng **Glassmorphism** sử dụng **Bootstrap 5**.

## 🚀 Tính năng chính

* **Quản trị viên (Admin):**
    * Đăng nhập/Đăng xuất bảo mật (Session).
    * **Quản lý sản phẩm:** Xem danh sách, Thêm mới, Chỉnh sửa, Xóa sản phẩm.
    * **Quản lý đơn hàng:** Xem danh sách các đơn hàng đã đặt, chi tiết ngày giờ và tổng tiền.
* **Giao diện:**
    * Thiết kế Responsive (thích hợp cho cả điện thoại và máy tính).
    * Hiệu ứng kính mờ (Glass UI).

## 🛠️ Công nghệ sử dụng

* **Backend:** PHP (Native - Không dùng Framework).
* **Database:** MySQL.
* **Frontend:** HTML5, CSS3, Bootstrap 5.3.
* **Server:** Apache (XAMPP/WAMP).

## ⚙️ Hướng dẫn Cài đặt

Để chạy dự án này trên máy cục bộ (Localhost), vui lòng làm theo các bước sau:

### Bước 1: Chuẩn bị môi trường
Cài đặt **XAMPP** hoặc **WAMP** server hỗ trợ PHP và MySQL.

### Bước 2: Cấu hình Database
1.  Mở **phpMyAdmin** (thường là `http://localhost/phpmyadmin`).
2.  Tạo một cơ sở dữ liệu mới tên là: `cafe_manager`.
3.  Nhấn vào tab **Import**, chọn file `cafe_manager.sql` đi kèm trong source code và nhấn **Go** (Thực hiện).

### Bước 3: Cài đặt Code
1.  Copy thư mục dự án vào thư mục `htdocs` của XAMPP (ví dụ: `C:\xampp\htdocs\cafe-shop`).
2.  Mở file `config.php` và kiểm tra cấu hình kết nối (nếu bạn có mật khẩu root):
    ```php
    $host = 'localhost';
    $db   = 'cafe_manager';
    $user = 'root';
    $pass = ''; // Điền mật khẩu MySQL của bạn nếu có
    ```

### Bước 4: Chạy ứng dụng
Mở trình duyệt và truy cập:
`http://localhost/cafe-shop` (hoặc tên thư mục bạn đã đặt).

## 🔐 Thông tin Đăng nhập (Mặc định)

Hệ thống đã tạo sẵn tài khoản Admin:

* **Username:** `admin`
* **Password:** `password`

*(Lưu ý: Mật khẩu hiện đang được lưu dạng văn bản thường để thuận tiện cho việc học tập và kiểm thử).*

## 📂 Cấu trúc Thư mục

```text
cafe-shop/
├── config.php       # Kết nối CSDL (PDO) và khởi động Session
├── index.php        # Trang chủ & Quản lý sản phẩm
├── login.php        # Trang đăng nhập
├── logout.php       # Xử lý đăng xuất
├── orders.php       # Trang quản lý đơn hàng
├── addnew.php       # Chức năng thêm sản phẩm
├── edit.php         # Chức năng sửa sản phẩm
├── delete.php       # Chức năng xóa sản phẩm
├── cafe_manager.sql # File cơ sở dữ liệu
└── README.md        # Hướng dẫn sử dụng
