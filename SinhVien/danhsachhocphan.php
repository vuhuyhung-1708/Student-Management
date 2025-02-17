<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Học Phần</title>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Access/Css/danhsachhocphan.css">
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

    // Xử lý đăng ký học phần
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ma_lhp'])) {
        $ma_lhp = $_POST['ma_lhp'];

        // Kiểm tra xem sinh viên đã đăng ký học phần này chưa
        $check_sql = "SELECT * FROM DangKyLopHocPhan WHERE MaSV = ? AND MaLHP = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("ss", $ma_sv, $ma_lhp);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $message = "Bạn đã đăng ký học phần này.";
        } else {
            // Thêm học phần vào bảng đăng ký
            $insert_sql = "INSERT INTO DangKyLopHocPhan (MaSV, MaLHP) VALUES (?, ?)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("ss", $ma_sv, $ma_lhp);

            if ($insert_stmt->execute()) {
                $message = "Đăng ký học phần thành công!";
            } else {
                $message = "Có lỗi xảy ra, vui lòng thử lại.";
            }
        }
    }

    // Truy vấn danh sách học phần đã đăng ký
    $sql_registered = "SELECT LopHocPhan.MaLHP, LopHocPhan.TenLHP, LopHocPhan.NamHoc, LopHocPhan.HocKy, LopHocPhan.GiangVien
                       FROM DangKyLopHocPhan
                       INNER JOIN LopHocPhan ON DangKyLopHocPhan.MaLHP = LopHocPhan.MaLHP
                       WHERE DangKyLopHocPhan.MaSV = ?";
    $stmt_registered = $conn->prepare($sql_registered);
    $stmt_registered->bind_param("s", $ma_sv);
    $stmt_registered->execute();
    $result_registered = $stmt_registered->get_result();

    // Truy vấn danh sách học phần chưa đăng ký
    $sql_not_registered = "SELECT MaLHP, TenLHP, NamHoc, HocKy, GiangVien
                           FROM LopHocPhan
                           WHERE MaLHP NOT IN (
                               SELECT MaLHP FROM DangKyLopHocPhan WHERE MaSV = ?
                           )";
    $stmt_not_registered = $conn->prepare($sql_not_registered);
    $stmt_not_registered->bind_param("s", $ma_sv);
    $stmt_not_registered->execute();
    $result_not_registered = $stmt_not_registered->get_result();
    ?>
    <div class="course-container">
        <h1><i class='bx bxs-book'></i> Danh Sách Học Phần Đã Tham Gia</h1>
        <table class="course-table">
            <thead>
                <tr>
                    <th>Mã Học Phần</th>
                    <th>Tên Học Phần</th>
                    <th>Năm Học</th>
                    <th>Học Kỳ</th>
                    <th>Giảng Viên</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_registered->num_rows > 0) {
                    while ($row = $result_registered->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['MaLHP']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['TenLHP']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['NamHoc']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['HocKy']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['GiangVien']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Không có học phần nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <h1><i class='bx bxs-book-add'></i> Danh Sách Học Phần Chưa Tham Gia</h1>
        <?php if (isset($message)) : ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <table class="course-table">
            <thead>
                <tr>
                    <th>Mã Học Phần</th>
                    <th>Tên Học Phần</th>
                    <th>Năm Học</th>
                    <th>Học Kỳ</th>
                    <th>Giảng Viên</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result_not_registered->num_rows > 0) {
                    while ($row = $result_not_registered->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['MaLHP']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['TenLHP']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['NamHoc']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['HocKy']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['GiangVien']) . "</td>";
                        echo "<td>
                                <form method='POST' class='action-form'>
                                    <input type='hidden' name='ma_lhp' value='" . htmlspecialchars($row['MaLHP']) . "'>
                                    <button type='submit' class='btn-register'><i class='bx bx-add-to-queue'></i> Đăng Ký</button>
                                </form>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Không có học phần nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</body>
</html>
