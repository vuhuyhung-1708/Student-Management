<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa Học Phần</title>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Access/Css/delete_hocphan.css">
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

    // Lấy thông tin học phần
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

    // Xử lý khi xác nhận xóa
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $sql_delete = "DELETE FROM LopHocPhan WHERE MaLHP = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("s", $malhp);

        if ($stmt_delete->execute()) {
            echo "<p class='message success'>Học phần đã được xóa thành công!</p>";
            header("refresh:2;url=quanly_hocphan.php");
            exit;
        } else {
            echo "<p class='message error'>Lỗi khi xóa học phần: " . $conn->error . "</p>";
        }
    }
    ?>
    <div class="delete-container">
        <h1><i class='bx bxs-trash'></i> Xóa Học Phần</h1>
        <p>Bạn có chắc chắn muốn xóa học phần này?</p>
        <div class="hocphan-info">
            <p><strong>Mã Học Phần:</strong> <?php echo htmlspecialchars($hocphan['MaLHP']); ?></p>
            <p><strong>Tên Học Phần:</strong> <?php echo htmlspecialchars($hocphan['TenLHP']); ?></p>
            <p><strong>Năm Học:</strong> <?php echo htmlspecialchars($hocphan['NamHoc']); ?></p>
            <p><strong>Học Kỳ:</strong> <?php echo htmlspecialchars($hocphan['HocKy']); ?></p>
            <p><strong>Giảng Viên:</strong> <?php echo htmlspecialchars($hocphan['GiangVien']); ?></p>
        </div>
        <form method="POST" action="">
            <button type="submit" class="btn-delete"><i class='bx bx-check'></i> Xác Nhận Xóa</button>
            <a href="quanly_hocphan.php" class="btn-cancel"><i class='bx bx-x'></i> Hủy</a>
        </form>
    </div>
</body>
</html>
