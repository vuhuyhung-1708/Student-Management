<?php
// Thông tin kết nối cơ sở dữ liệu
$host = "localhost"; // Địa chỉ máy chủ MySQL
$user = "root";      // Tên người dùng MySQL
$password = "";      // Mật khẩu MySQL
$database = "qldsv"; // Tên cơ sở dữ liệu

// Tạo kết nối
$conn = new mysqli($host, $user, $password, $database);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Thiết lập charset để hỗ trợ tiếng Việt
$conn->set_charset("utf8");

?>
