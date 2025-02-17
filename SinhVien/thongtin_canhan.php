<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thông Tin Cá Nhân</title>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Access/Css/thongtin_canhan.css">
</head>
<body>
    <?php
    // Khởi động session
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Kết nối cơ sở dữ liệu
    include_once '../Includes/Connect_db.php';
    include_once '../View/header.php';
    include_once '../View/Menu_ngang.php';
    // Kiểm tra xem người dùng đã đăng nhập chưa
    if (!isset($_SESSION['email'])) {
        header('Location: ../View/Login.php?error=not_logged_in');
        exit;
    }

    // Lấy email từ session
    $email = $_SESSION['email'];

    // Truy vấn thông tin cá nhân từ database
    $sql = "SELECT MaSV, HoTen, Lop, NgaySinh, GioiTinh, Email, SoDienThoai 
            FROM SinhVien 
            WHERE Email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra và lấy thông tin
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
    } else {
        echo "Không tìm thấy thông tin cá nhân.";
        exit;
    }
    ?>
    <div class="profile-container">
        <h1><i class='bx bxs-user-circle'></i> Thông Tin Cá Nhân</h1>
        <div class="profile-info">
            <p><strong>Mã Sinh Viên:</strong> <?php echo htmlspecialchars($user['MaSV']); ?></p>
            <p><strong>Họ Tên:</strong> <?php echo htmlspecialchars($user['HoTen']); ?></p>
            <p><strong>Lớp:</strong> <?php echo htmlspecialchars($user['Lop']); ?></p>
            <p><strong>Ngày Sinh:</strong> <?php echo htmlspecialchars($user['NgaySinh']); ?></p>
            <p><strong>Giới Tính:</strong> <?php echo htmlspecialchars($user['GioiTinh']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['Email']); ?></p>
            <p><strong>Số Điện Thoại:</strong> <?php echo htmlspecialchars($user['SoDienThoai']); ?></p>
        </div>
        <div class="profile-actions">

            <button class="btn-logout" onclick="window.location.href='../Logout.php';"><i class='bx bx-log-out'></i> Đăng Xuất</button>
        </div>
    </div>
</body>
</html>
