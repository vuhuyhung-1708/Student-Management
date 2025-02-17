<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Điểm</title>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Access/Css/quanly_diem.css">
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

    // Phân trang
    $limit = 10;
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $start = ($page - 1) * $limit;

    // Tìm kiếm
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Lấy danh sách điểm
    $sql = "SELECT BangDiem.MaSV, SinhVien.HoTen, SinhVien.Lop, BangDiem.MaLHP, LopHocPhan.TenLHP, BangDiem.Diem 
            FROM BangDiem
            INNER JOIN SinhVien ON BangDiem.MaSV = SinhVien.MaSV
            INNER JOIN LopHocPhan ON BangDiem.MaLHP = LopHocPhan.MaLHP
            WHERE BangDiem.MaSV LIKE ? OR SinhVien.HoTen LIKE ? OR BangDiem.MaLHP LIKE ?
            LIMIT ?, ?";
    $search_param = "%" . $search . "%";
    $params = [
        "ssiii", // Loại dữ liệu
        &$search_param, &$search_param, &$search_param, &$start, &$limit
    ];
    $stmt = $conn->prepare($sql);
    call_user_func_array([$stmt, 'bind_param'], $params);
    $stmt->execute();
    $result = $stmt->get_result();

    // Tổng số điểm để phân trang
    $sql_total = "SELECT COUNT(*) AS total
                  FROM BangDiem
                  INNER JOIN SinhVien ON BangDiem.MaSV = SinhVien.MaSV
                  WHERE BangDiem.MaSV LIKE ? OR SinhVien.HoTen LIKE ? OR BangDiem.MaLHP LIKE ?";
    $stmt_total = $conn->prepare($sql_total);
    $stmt_total->bind_param("sss", $search_param, $search_param, $search_param);
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
    $total_scores = $result_total->fetch_assoc()['total'];
    $total_pages = ceil($total_scores / $limit);
    ?>
    <div class="score-management">
        <h1><i class='bx bxs-graduation'></i> Quản Lý Điểm</h1>
        <div class="search-container">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Tìm kiếm theo mã SV, tên hoặc mã học phần..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit"><i class='bx bx-search'></i></button>
            </form>
        </div>
        <table class="score-table">
            <thead>
                <tr>
                    <th>Mã SV</th>
                    <th>Họ Tên</th>
                    <th>Lớp</th>
                    <th>Mã Học Phần</th>
                    <th>Tên Học Phần</th>
                    <th>Điểm</th>
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
                        echo "<td>" . htmlspecialchars($row['Lop']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['MaLHP']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['TenLHP']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['Diem']) . "</td>";
                        echo "<td>
                                <a href='edit_diem.php?masv=" . htmlspecialchars($row['MaSV']) . "&malhp=" . htmlspecialchars($row['MaLHP']) . "' class='btn-edit'>
                                    <i class='bx bxs-edit'></i> Sửa
                                </a>
                                <a href='delete_diem.php?masv=" . htmlspecialchars($row['MaSV']) . "&malhp=" . htmlspecialchars($row['MaLHP']) . "' class='btn-delete' 
                                   onclick='return confirm(\"Bạn có chắc muốn xóa không?\");'>
                                    <i class='bx bxs-trash'></i> Xóa
                                </a>
                              </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>Không tìm thấy dữ liệu.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?page=<?php echo $i; ?>&search=<?php echo htmlspecialchars($search); ?>" 
                   class="<?php echo ($page == $i) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
        <div class="add-score">
            <a href="add_diem.php" class="btn-add"><i class='bx bx-plus-circle'></i> Thêm Điểm</a>
        </div>
    </div>
</body>
</html>
