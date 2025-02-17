<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Sinh Viên</title>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Access/Css/quanly_sinhvien.css">
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
    include_once '../Admin/menungang_admin.php';
    // Kiểm tra xem người dùng đã đăng nhập chưa
    if (!isset($_SESSION['email'])) {
        header('Location: ../View/Login.php?error=not_logged_in');
        exit;
    }

    // Thiết lập biến phân trang
    $limit = 10; // Số sinh viên mỗi trang
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Trang hiện tại
    $start = ($page - 1) * $limit; // Bắt đầu từ

    // Tìm kiếm
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Lấy danh sách sinh viên từ cơ sở dữ liệu
    $sql = "SELECT * FROM SinhVien WHERE HoTen LIKE ? OR Lop LIKE ? LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $search_param = "%" . $search . "%";
    $stmt->bind_param("ssii", $search_param, $search_param, $start, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    // Tổng số sinh viên (dùng để tính số trang)
    $sql_total = "SELECT COUNT(*) AS total FROM SinhVien WHERE HoTen LIKE ? OR Lop LIKE ?";
    $stmt_total = $conn->prepare($sql_total);
    $stmt_total->bind_param("ss", $search_param, $search_param);
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
    $total_students = $result_total->fetch_assoc()['total'];
    $total_pages = ceil($total_students / $limit); // Tổng số trang
    ?>
    <div class="student-management">
        <h1><i class='bx bxs-user'></i> Quản Lý Sinh Viên</h1>
        <div class="search-container">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Tìm kiếm..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit"><i class='bx bx-search'></i></button>
            </form>
        </div>
        <table class="student-table">
            <thead>
                <tr>
                    <th>Mã SV</th>
                    <th>Họ Tên</th>
                    <th>Ngày Sinh</th>
                    <th>Giới Tính</th>
                    <th>Lớp</th>
                    <th>Email</th>
                    <th>Số Điện Thoại</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['MaSV']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['HoTen']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['NgaySinh']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['GioiTinh']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Lop']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Email']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['SoDienThoai']) . "</td>";
                        echo "<td>
                                <a href='edit_sinhvien.php?masv=" . $row['MaSV'] . "' class='btn-edit'><i class='bx bxs-edit'></i> Sửa</a>
                                <a href='delete_sinhvien.php?masv=" . $row['MaSV'] . "' class='btn-delete' onclick='return confirm(\"Bạn có chắc muốn xóa không?\");'><i class='bx bxs-trash'></i> Xóa</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='8'>Không có sinh viên nào.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <a href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $i; ?>" class="<?php echo ($page == $i) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
        <div class="add-sinhvien">
            <a href="add_sinhvien.php" class="btn-add"><i class='bx bx-plus-circle'></i> Thêm Sinh Viên</a>
        </div>
    </div>
</body>
</html>
