<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Sinh Viên</title>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Access/Css/add_sinhvien.css">
</head>
<body>
    <?php
    include_once '../Includes/Connect_db.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ma_sv = isset($_POST['ma_sv']) ? trim($_POST['ma_sv']) : '';
        $ho_ten = isset($_POST['ho_ten']) ? trim($_POST['ho_ten']) : '';
        $ngay_sinh = isset($_POST['ngay_sinh']) ? trim($_POST['ngay_sinh']) : '';
        $gioi_tinh = isset($_POST['gioi_tinh']) ? trim($_POST['gioi_tinh']) : '';
        $lop = isset($_POST['lop']) ? trim($_POST['lop']) : '';
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $so_dien_thoai = isset($_POST['so_dien_thoai']) ? trim($_POST['so_dien_thoai']) : '';

        if ($ma_sv && $ho_ten && $ngay_sinh && $gioi_tinh && $lop && $email && $so_dien_thoai) {
            $sql_insert = "INSERT INTO SinhVien (MaSV, HoTen, NgaySinh, GioiTinh, Lop, Email, SoDienThoai)
                           VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("sssssss", $ma_sv, $ho_ten, $ngay_sinh, $gioi_tinh, $lop, $email, $so_dien_thoai);

            if ($stmt->execute()) {
                $message = "Thêm sinh viên thành công!";
            } else {
                $message = "Lỗi: " . $conn->error;
            }
        } else {
            $message = "Vui lòng nhập đầy đủ thông tin!";
        }
    }
    ?>
    <div class="form-container">
        <h1><i class='bx bxs-user-plus'></i> Thêm Sinh Viên</h1>
        <?php if (isset($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="ma_sv">Mã Sinh Viên:</label>
            <input type="text" id="ma_sv" name="ma_sv" required>

            <label for="ho_ten">Họ Tên:</label>
            <input type="text" id="ho_ten" name="ho_ten" required>

            <label for="ngay_sinh">Ngày Sinh:</label>
            <input type="date" id="ngay_sinh" name="ngay_sinh" required>

            <label for="gioi_tinh">Giới Tính:</label>
            <select id="gioi_tinh" name="gioi_tinh" required>
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
            </select>

            <label for="lop">Lớp:</label>
            <input type="text" id="lop" name="lop" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="so_dien_thoai">Số Điện Thoại:</label>
            <input type="text" id="so_dien_thoai" name="so_dien_thoai" required>

            <button type="submit" class="btn-submit"><i class='bx bx-save'></i> Thêm Sinh Viên</button>
        </form>
    </div>
</body>
</html>
