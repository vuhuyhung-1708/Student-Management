<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xem Điểm Học Phần</title>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Access/Css/xemdiem_hocphan.css">
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

    // Lấy email từ session và truy vấn mã sinh viên
    $email = $_SESSION['email'];
    $sql_get_ma_sv = "SELECT MaSV FROM SinhVien WHERE Email = ?";
    $stmt = $conn->prepare($sql_get_ma_sv);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $ma_sv = $row['MaSV'];
    } else {
        echo "Không tìm thấy mã sinh viên.";
        exit;
    }

    // Truy vấn điểm học phần
    $sql = "SELECT LopHocPhan.TenLHP, LopHocPhan.NamHoc, LopHocPhan.HocKy, BangDiem.Diem
            FROM BangDiem
            INNER JOIN LopHocPhan ON BangDiem.MaLHP = LopHocPhan.MaLHP
            WHERE BangDiem.MaSV = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ma_sv);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>
    <div class="grades-container">
        <h1><i class='bx bxs-graduation'></i> Điểm Học Phần</h1>
        <table class="grades-table">
            <thead>
                <tr>
                    <th>Tên Học Phần</th>
                    <th>Năm Học</th>
                    <th>Học Kỳ</th>
                    <th>Điểm</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['TenLHP']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['NamHoc']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['HocKy']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Diem']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>Không có dữ liệu.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
