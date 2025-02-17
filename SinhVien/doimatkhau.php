<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đổi Mật Khẩu</title>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Access/Css/doimatkhau.css">
</head>
<body>
    <?php
    // Bắt đầu session
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

    // Xử lý thay đổi mật khẩu
    $message = "";
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Lấy email từ session
        $email = $_SESSION['email'];

        // Kiểm tra mật khẩu hiện tại
        $sql = "SELECT MatKhau FROM TaiKhoan WHERE Email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['MatKhau'] === $current_password) {
                // Kiểm tra xác nhận mật khẩu mới
                if ($new_password === $confirm_password) {
                    // Cập nhật mật khẩu mới
                    $update_sql = "UPDATE TaiKhoan SET MatKhau = ? WHERE Email = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("ss", $new_password, $email);

                    if ($update_stmt->execute()) {
                        $message = "Đổi mật khẩu thành công!";
                    } else {
                        $message = "Có lỗi xảy ra, vui lòng thử lại.";
                    }
                } else {
                    $message = "Mật khẩu mới và xác nhận mật khẩu không khớp.";
                }
            } else {
                $message = "Mật khẩu hiện tại không đúng.";
            }
        } else {
            $message = "Không tìm thấy tài khoản.";
        }
    }
    ?>
    <div class="change-password-container">
        <h1><i class='bx bxs-key'></i> Đổi Mật Khẩu</h1>
        <?php if ($message) : ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="POST" class="change-password-form">
            <div class="form-group">
                <label for="current_password">Mật Khẩu Hiện Tại:</label>
                <input type="password" name="current_password" id="current_password" required>
            </div>
            <div class="form-group">
                <label for="new_password">Mật Khẩu Mới:</label>
                <input type="password" name="new_password" id="new_password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Xác Nhận Mật Khẩu Mới:</label>
                <input type="password" name="confirm_password" id="confirm_password" required>
            </div>
            <button type="submit" class="btn-change-password"><i class='bx bxs-save'></i> Lưu Thay Đổi</button>
        </form>
    </div>
</body>
</html>
