<?php
// Kết nối cơ sở dữ liệu
include 'Includes/Connect_db.php';

// Khởi động session nếu chưa được khởi động
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Lấy dữ liệu từ form
$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// Kiểm tra dữ liệu nhập vào
if (empty($email) || empty($password)) {
    echo "Vui lòng nhập đầy đủ email và mật khẩu!";
    exit;
}

// Truy vấn kiểm tra tài khoản
$sql = "SELECT * FROM TaiKhoan WHERE Email = ? AND MatKhau = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $role = $row['VaiTro']; // Lấy vai trò của người dùng

    // Lưu thông tin vào session
    $_SESSION['email'] = $email;
    $_SESSION['role'] = $role;

    // Chuyển hướng dựa trên vai trò
    if ($role == 'SinhVien') {
        header("Location: SinhVien/dashboard.php");
        exit;
    } elseif ($role == 'PhongDaoTao') {
        header("Location: Admin/dashboard.php");
        exit;
    } else {
        echo "Tài khoản không xác định vai trò!";
    }
} else {
    // Sai email hoặc mật khẩu
    echo "Sai email hoặc mật khẩu!";
}

// Đóng kết nối
$conn->close();
?>
