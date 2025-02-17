<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Học Phần</title>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Access/Css/edit_hocphan.css">
</head>
<body>
    <?php
    // Kết nối cơ sở dữ liệu
    include_once '../Includes/Connect_db.php';
    include_once '../View/header.php';
    include_once '../Admin/menungang_admin.php';
    // Lấy mã học phần từ URL
    $malhp = isset($_GET['malhp']) ? trim($_GET['malhp']) : '';

    // Kiểm tra mã học phần
    if (empty($malhp)) {
        echo "<p class='message error'>Mã học phần không hợp lệ!</p>";
        exit;
    }

    // Lấy thông tin học phần từ cơ sở dữ liệu
    $sql_get = "SELECT * FROM LopHocPhan WHERE MaLHP = ?";
    $stmt_get = $conn->prepare($sql_get);
    $stmt_get->bind_param("s", $malhp);
    $stmt_get->execute();
    $result_get = $stmt_get->get_result();

    if ($result_get->num_rows === 0) {
        echo "<p class='message error'>Học phần không tồn tại trong cơ sở dữ liệu!</p>";
        exit;
    }

    $hocphan = $result_get->fetch_assoc();

    // Xử lý khi form được gửi
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ten_lhp = isset($_POST['ten_lhp']) ? trim($_POST['ten_lhp']) : '';
        $nam_hoc = isset($_POST['nam_hoc']) ? trim($_POST['nam_hoc']) : '';
        $hoc_ky = isset($_POST['hoc_ky']) ? trim($_POST['hoc_ky']) : '';
        $giang_vien = isset($_POST['giang_vien']) ? trim($_POST['giang_vien']) : '';

        // Kiểm tra dữ liệu nhập vào
        if (empty($ten_lhp) || empty($nam_hoc) || empty($hoc_ky) || empty($giang_vien)) {
            $message = "Vui lòng nhập đầy đủ thông tin!";
        } else {
            // Cập nhật học phần
            $sql_update = "UPDATE LopHocPhan SET TenLHP = ?, NamHoc = ?, HocKy = ?, GiangVien = ? WHERE MaLHP = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param("sssss", $ten_lhp, $nam_hoc, $hoc_ky, $giang_vien, $malhp);

            if ($stmt_update->execute()) {
                $message = "Cập nhật học phần thành công!";
                $hocphan['TenLHP'] = $ten_lhp;
                $hocphan['NamHoc'] = $nam_hoc;
                $hocphan['HocKy'] = $hoc_ky;
                $hocphan['GiangVien'] = $giang_vien;
            } else {
                $message = "Lỗi khi cập nhật: " . $conn->error;
            }
        }
    }
    ?>
    <div class="form-container">
        <h1><i class='bx bxs-edit'></i> Chỉnh Sửa Học Phần</h1>
        <?php if (isset($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="ma_lhp">Mã Học Phần:</label>
            <input type="text" id="ma_lhp" name="ma_lhp" value="<?php echo htmlspecialchars($hocphan['MaLHP']); ?>" readonly>

            <label for="ten_lhp">Tên Học Phần:</label>
            <input type="text" id="ten_lhp" name="ten_lhp" value="<?php echo htmlspecialchars($hocphan['TenLHP']); ?>" required>

            <label for="nam_hoc">Năm Học:</label>
            <input type="text" id="nam_hoc" name="nam_hoc" value="<?php echo htmlspecialchars($hocphan['NamHoc']); ?>" required>

            <label for="hoc_ky">Học Kỳ:</label>
            <input type="text" id="hoc_ky" name="hoc_ky" value="<?php echo htmlspecialchars($hocphan['HocKy']); ?>" required>

            <label for="giang_vien">Giảng Viên:</label>
            <input type="text" id="giang_vien" name="giang_vien" value="<?php echo htmlspecialchars($hocphan['GiangVien']); ?>" required>

            <button type="submit" class="btn-submit"><i class='bx bx-save'></i> Cập Nhật</button>
        </form>
    </div>
</body>
</html>
