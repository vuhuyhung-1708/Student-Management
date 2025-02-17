<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Học Phần</title>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Access/Css/add_hocphan.css">
</head>
<body>
    <?php
    // Kết nối cơ sở dữ liệu
    include_once '../Includes/Connect_db.php';

    // Xử lý khi form được gửi
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $ma_lhp = isset($_POST['ma_lhp']) ? trim($_POST['ma_lhp']) : '';
        $ten_lhp = isset($_POST['ten_lhp']) ? trim($_POST['ten_lhp']) : '';
        $nam_hoc = isset($_POST['nam_hoc']) ? trim($_POST['nam_hoc']) : '';
        $hoc_ky = isset($_POST['hoc_ky']) ? trim($_POST['hoc_ky']) : '';
        $giang_vien = isset($_POST['giang_vien']) ? trim($_POST['giang_vien']) : '';

        // Kiểm tra thông tin nhập vào
        if (empty($ma_lhp) || empty($ten_lhp) || empty($nam_hoc) || empty($hoc_ky) || empty($giang_vien)) {
            $message = "Vui lòng nhập đầy đủ thông tin!";
        } else {
            // Thêm học phần vào cơ sở dữ liệu
            $sql_insert = "INSERT INTO LopHocPhan (MaLHP, TenLHP, NamHoc, HocKy, GiangVien) 
                           VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql_insert);
            $stmt->bind_param("sssss", $ma_lhp, $ten_lhp, $nam_hoc, $hoc_ky, $giang_vien);

            if ($stmt->execute()) {
                $message = "Thêm học phần thành công!";
            } else {
                $message = "Lỗi khi thêm học phần: " . $conn->error;
            }
        }
    }
    ?>
    <div class="form-container">
        <h1><i class='bx bxs-book-add'></i> Thêm Học Phần</h1>
        <?php if (isset($message)): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="POST" action="">
            <label for="ma_lhp">Mã Học Phần:</label>
            <input type="text" id="ma_lhp" name="ma_lhp" placeholder="Nhập mã học phần" required>

            <label for="ten_lhp">Tên Học Phần:</label>
            <input type="text" id="ten_lhp" name="ten_lhp" placeholder="Nhập tên học phần" required>

            <label for="nam_hoc">Năm Học:</label>
            <input type="text" id="nam_hoc" name="nam_hoc" placeholder="VD: 2024" required>

            <label for="hoc_ky">Học Kỳ:</label>
            <input type="text" id="hoc_ky" name="hoc_ky" placeholder="VD: HK1 hoặc HK2" required>

            <label for="giang_vien">Giảng Viên:</label>
            <input type="text" id="giang_vien" name="giang_vien" placeholder="Nhập tên giảng viên" required>

            <button type="submit" class="btn-submit"><i class='bx bx-save'></i> Thêm Học Phần</button>
        </form>
    </div>
</body>
</html>
