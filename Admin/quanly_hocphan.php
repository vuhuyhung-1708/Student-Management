<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Học Phần</title>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Access/Css/quanly_hocphan.css">
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
    $limit = 10; // Số học phần mỗi trang
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Trang hiện tại
    $start = ($page - 1) * $limit; // Bắt đầu từ

    // Tìm kiếm
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Lấy danh sách học phần từ cơ sở dữ liệu
    $sql = "SELECT * FROM LopHocPhan WHERE TenLHP LIKE ? OR GiangVien LIKE ? LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $search_param = "%" . $search . "%";
    $stmt->bind_param("ssii", $search_param, $search_param, $start, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    // Tổng số học phần (dùng để tính số trang)
    $sql_total = "SELECT COUNT(*) AS total FROM LopHocPhan WHERE TenLHP LIKE ? OR GiangVien LIKE ?";
    $stmt_total = $conn->prepare($sql_total);
    $stmt_total->bind_param("ss", $search_param, $search_param);
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
    $total_courses = $result_total->fetch_assoc()['total'];
    $total_pages = ceil($total_courses / $limit); // Tổng số trang
    ?>
    <div class="course-management">
        <h1><i class='bx bxs-book'></i> Quản Lý Học Phần</h1>
        <div class="search-container">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Tìm kiếm..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit"><i class='bx bx-search'></i></button>
            </form>
        </div>
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
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row['MaLHP']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['TenLHP']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['NamHoc']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['HocKy']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['GiangVien']) . "</td>";
                        echo "<td>
                                <a href='edit_hocphan.php?malhp=" . $row['MaLHP'] . "' class='btn-edit'><i class='bx bxs-edit'></i> Sửa</a>
                                <a href='delete_hocphan.php?malhp=" . $row['MaLHP'] . "' class='btn-delete' onclick='return confirm(\"Bạn có chắc muốn xóa không?\");'><i class='bx bxs-trash'></i> Xóa</a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>Không có học phần nào.</td></tr>";
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
        <div class="add-hocphan">
            <a href="add_hocphan.php" class="btn-add"><i class='bx bx-plus-circle'></i> Thêm Học Phần</a>
        </div>
    </div>
</body>
</html>
