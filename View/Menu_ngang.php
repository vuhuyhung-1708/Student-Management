<?php
// Khởi động session nếu chưa được khởi động
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra session
if (!isset($_SESSION['email'])) {
    header("Location: ../View/Login.php?error=not_logged_in");
    exit;
}

$email = $_SESSION['email']; // Lấy email từ session
?>
<link rel="stylesheet" href="../Access/Css/Menu_ngang.css">
<nav class="main-menu">
    <ul>
        <li><a href="../SinhVien/dashboard.php">Trang Chủ</a></li>
        <li><a href="../SinhVien/thongtin_canhan.php">Xem thông tin cá nhân</a></li>
        <li><a href="../SinhVien/xemdiem_hocphan.php">Xem điểm học phần</a></li>
        <li><a href="../SinhVien/danhsachhocphan.php">Xem danh sách học phần</a></li>
        <li><a href="../SinhVien/doimatkhau.php">Đổi mật khẩu</a></li>
        <li><a href="../Logout.php">Đăng xuất</a></li>
    </ul>
</nav>
