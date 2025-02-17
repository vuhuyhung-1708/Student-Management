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
        <li><a href="../Admin/dashboard.php">Trang Chủ</a></li>
        <li><a href="../Admin/quanly_sinhvien.php">Quản Lý Sinh Viên</a></li>
        <li><a href="../Admin/quanly_hocphan.php">Quản Lý Học Phần</a></li>
        <li><a href="../Admin/quanly_diem.php">Quản Lý Điểm</a></li>
        <li><a href="../Admin/thongke.php">Thống Kê</a></li>
        <li><a href="../Admin/quanly_taikhoan.php">Quản Lý Tài Khoản</a></li>
        <li><a href="../Admin/thongbao.html">Thông Báo</a></li>
        <li><a href="../Logout.php">Đăng xuất</a></li>
    </ul>
</nav>
