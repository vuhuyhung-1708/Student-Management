<?php
// Bắt đầu session
session_start();

// Hủy tất cả các biến session
session_unset();

// Hủy session
session_destroy();

// Chuyển hướng về trang đăng nhập
header("Location: Login.php?message=logged_out");
exit;
?>
