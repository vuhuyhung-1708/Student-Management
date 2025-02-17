<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa Điểm</title>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Access/Css/delete_diem.css">
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

    if (empty($ma_sv) || empty($ma_lhp)) {
        echo "<p class='error'>Mã sinh viên hoặc mã học phần không hợp lệ.</p>";
        exit;
    }

    // Lấy thông tin điểm
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
        echo "<p class='error'>Không tìm thấy dữ liệu để xóa.</p>";
        exit;
    }

    $row = $result->fetch_assoc();

    // Xử lý khi xác nhận xóa
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sql_delete = "DELETE FROM BangDiem WHERE MaSV = ? AND MaLHP = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("ss", $ma_sv, $ma_lhp);

        if ($stmt_delete->execute()) {
            echo "<p class='success'>Xóa điểm thành công.</p>";
            header("Refresh: 2; url=quanly_diem.php");
            exit;
        } else {
            echo "<p class='error'>Lỗi khi xóa điểm.</p>";
        }
    }
    ?>

    <div class="delete-score">
        <h1><i class='bx bxs-trash'></i> Xóa Điểm</h1>
        <p>Bạn có chắc chắn muốn xóa điểm này?</p>
        <ul>
            <li><strong>Mã Sinh Viên:</strong> <?php echo htmlspecialchars($ma_sv); ?></li>
            <li><strong>Họ Tên Sinh Viên:</strong> <?php echo htmlspecialchars($row['HoTen']); ?></li>
            <li><strong>Mã Học Phần:</strong> <?php echo htmlspecialchars($ma_lhp); ?></li>
            <li><strong>Tên Học Phần:</strong> <?php echo htmlspecialchars($row['TenLHP']); ?></li>
            <li><strong>Điểm:</strong> <?php echo htmlspecialchars($row['Diem']); ?></li>
        </ul>
        <form method="POST">
            <button type="submit" class="btn-confirm"><i class='bx bxs-check-circle'></i> Xác Nhận</button>
            <a href="quanly_diem.php" class="btn-cancel"><i class='bx bxs-x-circle'></i> Hủy</a>
        </form>
    </div>
</body>
</html>
