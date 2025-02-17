<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Điểm</title>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Access/Css/edit_diem.css">
</head>
<body>
    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    include_once '../Includes/Connect_db.php';
    include_once '../View/header.php';
    include_once '../Admin/menungang_admin.php';

    if (!isset($_SESSION['email'])) {
        header('Location: ../View/Login.php?error=not_logged_in');
        exit;
    }

    // Lấy mã sinh viên và mã học phần từ URL
    $ma_sv = isset($_GET['masv']) ? $_GET['masv'] : '';
    $ma_lhp = isset($_GET['malhp']) ? $_GET['malhp'] : '';

    // Kiểm tra nếu không có mã được truyền vào
    if (empty($ma_sv) || empty($ma_lhp)) {
        echo "<p class='error'>Mã sinh viên hoặc mã học phần không hợp lệ.</p>";
        exit;
    }

    // Lấy dữ liệu điểm hiện tại
    $sql = "SELECT BangDiem.Diem, SinhVien.HoTen, LopHocPhan.TenLHP
            FROM BangDiem
            INNER JOIN SinhVien ON BangDiem.MaSV = SinhVien.MaSV
            INNER JOIN LopHocPhan ON BangDiem.MaLHP = LopHocPhan.MaLHP
            WHERE BangDiem.MaSV = ? AND BangDiem.MaLHP = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $ma_sv, $ma_lhp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        echo "<p class='error'>Không tìm thấy dữ liệu.</p>";
        exit;
    }

    $row = $result->fetch_assoc();

    // Xử lý khi form được gửi
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $diem_moi = isset($_POST['diem']) ? (float)$_POST['diem'] : null;

        if (!is_null($diem_moi) && $diem_moi >= 0 && $diem_moi <= 10) {
            $sql_update = "UPDATE BangDiem SET Diem = ? WHERE MaSV = ? AND MaLHP = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("dss", $diem_moi, $ma_sv, $ma_lhp);

            if ($stmt_update->execute()) {
                echo "<p class='success'>Cập nhật điểm thành công.</p>";
                header("Refresh: 2; url=quanly_diem.php");
                exit;
            } else {
                echo "<p class='error'>Lỗi khi cập nhật điểm.</p>";
            }
        } else {
            echo "<p class='error'>Điểm không hợp lệ. Vui lòng nhập giá trị từ 0 đến 10.</p>";
        }
    }
    ?>

    <div class="edit-score">
        <h1><i class='bx bxs-edit'></i> Chỉnh Sửa Điểm</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="masv">Mã Sinh Viên:</label>
                <input type="text" id="masv" value="<?php echo htmlspecialchars($ma_sv); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="hoten">Họ Tên Sinh Viên:</label>
                <input type="text" id="hoten" value="<?php echo htmlspecialchars($row['HoTen']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="malhp">Mã Học Phần:</label>
                <input type="text" id="malhp" value="<?php echo htmlspecialchars($ma_lhp); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="tenlhp">Tên Học Phần:</label>
                <input type="text" id="tenlhp" value="<?php echo htmlspecialchars($row['TenLHP']); ?>" readonly>
            </div>
            <div class="form-group">
                <label for="diem">Điểm:</label>
                <input type="number" id="diem" name="diem" value="<?php echo htmlspecialchars($row['Diem']); ?>" step="0.1" min="0" max="10" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-save"><i class='bx bx-save'></i> Lưu</button>
                <a href="quanly_diem.php" class="btn-cancel"><i class='bx bx-x'></i> Hủy</a>
            </div>
        </form>
    </div>
</body>
</html>
