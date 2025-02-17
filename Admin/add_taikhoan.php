<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Tài Khoản</title>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Access/Css/add_taikhoan.css">
</head>
<body>
    <?php
    // Kết nối cơ sở dữ liệu
    include_once '../Includes/Connect_db.php';

    // Kiểm tra xem người dùng đã gửi form hay chưa
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? trim($_POST['password']) : '';
        $role = isset($_POST['role']) ? trim($_POST['role']) : '';

        // Kiểm tra dữ liệu nhập vào
        if (empty($email) || empty($password) || empty($role)) {
            $message = "Vui lòng nhập đầy đủ thông tin!";
        } else {
            // Kiểm tra tài khoản đã tồn tại
            $sql_check = "SELECT * FROM TaiKhoan WHERE Email = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("s", $email);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                $message = "Tài khoản đã tồn tại!";
            } else {
                // Thêm tài khoản mới
                $sql_insert = "INSERT INTO TaiKhoan (Email, MatKhau, VaiTro) VALUES (?, ?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bind_param("sss", $email, $password, $role);

                if ($stmt_insert->execute()) {
                    $message = "Thêm tài khoản thành công!";
                } else {
                    $message = "Lỗi khi thêm tài khoản: " . $conn->error;
                }
            }
        }
    }
    ?>
    <div class="add-account-container">
        <h1><i class='bx bxs-user-plus'></i> Thêm Tài Khoản</h1>
        <?php if (isset($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Nhập email" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu:</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
            </div>
            <div class="form-group">
                <label for="role">Vai trò:</label>
                <select id="role" name="role" required>
                    <option value="">-- Chọn vai trò --</option>
                    <option value="SinhVien">Sinh Viên</option>
                    <option value="PhongDaoTao">Phòng Đào Tạo</option>
                </select>
            </div>
            <button type="submit" class="btn-submit"><i class='bx bx-save'></i> Thêm Tài Khoản</button>
        </form>
    </div>
</body>
</html>
