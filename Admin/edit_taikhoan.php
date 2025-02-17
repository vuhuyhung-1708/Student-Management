<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập Nhật Tài Khoản</title>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Access/Css/edit_taikhoan.css">
</head>
<body>
    <?php
    // Kết nối cơ sở dữ liệu
    include_once '../Includes/Connect_db.php';
    include_once '../View/header.php';
    include_once '../Admin/menungang_admin.php';
    // Lấy email từ URL
    $email = isset($_GET['email']) ? $_GET['email'] : '';

    // Kiểm tra xem tài khoản có tồn tại không
    $sql_check = "SELECT * FROM TaiKhoan WHERE Email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows === 0) {
        echo "<p class='message'>Tài khoản không tồn tại.</p>";
        exit;
    }

    $account = $result_check->fetch_assoc();

    // Xử lý khi cập nhật
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $role = isset($_POST['role']) ? trim($_POST['role']) : '';

        if (empty($password) || empty($role)) {
            $message = "Vui lòng nhập đầy đủ thông tin!";
        } else {
            // Cập nhật thông tin tài khoản
            $sql_update = "UPDATE TaiKhoan SET MatKhau = ?, VaiTro = ? WHERE Email = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("sss", $password, $role, $email);

            if ($stmt_update->execute()) {
                $message = "Cập nhật tài khoản thành công!";
            } else {
                $message = "Lỗi khi cập nhật: " . $conn->error;
            }
        }
    }
    ?>
    <div class="update-account-container">
        <h1><i class='bx bxs-user-check'></i> Cập Nhật Tài Khoản</h1>
        <?php if (isset($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($account['Email']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" value="<?php echo htmlspecialchars($account['MatKhau']); ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Vai trò:</label>
                <select id="role" name="role" required>
                    <option value="SinhVien" <?php echo ($account['VaiTro'] === 'SinhVien') ? 'selected' : ''; ?>>Sinh Viên</option>
                    <option value="PhongDaoTao" <?php echo ($account['VaiTro'] === 'PhongDaoTao') ? 'selected' : ''; ?>>Phòng Đào Tạo</option>
                </select>
            </div>
            <button type="submit" class="btn-submit"><i class='bx bx-save'></i> Cập Nhật</button>
        </form>
    </div>
</body>
</html>
