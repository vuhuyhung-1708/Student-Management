<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Điểm</title>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Access/Css/add_diem.css">
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

    // Lấy danh sách sinh viên
    $sql_sinhvien = "SELECT MaSV, HoTen FROM SinhVien";
    $result_sinhvien = $conn->query($sql_sinhvien);

    // Lấy danh sách học phần
    $sql_hocphan = "SELECT MaLHP, TenLHP FROM LopHocPhan";
    $result_hocphan = $conn->query($sql_hocphan);

    // Xử lý khi form được gửi
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ma_sv = isset($_POST['masv']) ? $_POST['masv'] : '';
        $ma_lhp = isset($_POST['malhp']) ? $_POST['malhp'] : '';
        $diem = isset($_POST['diem']) ? (float)$_POST['diem'] : null;

        if (!empty($ma_sv) && !empty($ma_lhp) && !is_null($diem) && $diem >= 0 && $diem <= 10) {
            $sql_check = "SELECT * FROM BangDiem WHERE MaSV = ? AND MaLHP = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("ss", $ma_sv, $ma_lhp);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                echo "<p class='error'>Điểm của sinh viên này đã tồn tại cho học phần này.</p>";
            } else {
                $sql_insert = "INSERT INTO BangDiem (MaSV, MaLHP, Diem) VALUES (?, ?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                $stmt_insert->bind_param("ssd", $ma_sv, $ma_lhp, $diem);

                if ($stmt_insert->execute()) {
                    echo "<p class='success'>Thêm điểm thành công.</p>";
                    header("Refresh: 2; url=quanly_diem.php");
                    exit;
                } else {
                    echo "<p class='error'>Lỗi khi thêm điểm.</p>";
                }
            }
        } else {
            echo "<p class='error'>Vui lòng điền đầy đủ thông tin và đảm bảo điểm từ 0 đến 10.</p>";
        }
    }
    ?>

    <div class="add-score">
        <h1><i class='bx bxs-plus-circle'></i> Thêm Điểm</h1>
        <form method="POST" action="">
            <div class="form-group">
                <label for="masv">Mã Sinh Viên:</label>
                <select name="masv" id="masv" required>
                    <option value="">-- Chọn sinh viên --</option>
                    <?php while ($row = $result_sinhvien->fetch_assoc()): ?>
                        <option value="<?php echo $row['MaSV']; ?>"><?php echo $row['MaSV'] . " - " . $row['HoTen']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="malhp">Mã Học Phần:</label>
                <select name="malhp" id="malhp" required>
                    <option value="">-- Chọn học phần --</option>
                    <?php while ($row = $result_hocphan->fetch_assoc()): ?>
                        <option value="<?php echo $row['MaLHP']; ?>"><?php echo $row['MaLHP'] . " - " . $row['TenLHP']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="diem">Điểm:</label>
                <input type="number" id="diem" name="diem" step="0.1" min="0" max="10" placeholder="Nhập điểm" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-save"><i class='bx bx-save'></i> Lưu</button>
                <a href="quanly_diem.php" class="btn-cancel"><i class='bx bx-x'></i> Hủy</a>
            </div>
        </form>
    </div>
</body>
</html>
