<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Tài Khoản</title>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Access/Css/quanly_taikhoan.css">
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

    // Thiết lập phân trang
    $limit = 10; // Số tài khoản mỗi trang
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Trang hiện tại
    $start = ($page - 1) * $limit; // Bắt đầu từ

    // Tìm kiếm tài khoản
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    // Lấy danh sách tài khoản
    $sql = "SELECT * FROM TaiKhoan WHERE Email LIKE ? LIMIT ?, ?";
    $stmt = $conn->prepare($sql);
    $search_param = "%" . $search . "%";
    $stmt->bind_param("sii", $search_param, $start, $limit);
    $stmt->execute();
    $result = $stmt->get_result();

    // Tổng số tài khoản để tính số trang
    $sql_total = "SELECT COUNT(*) AS total FROM TaiKhoan WHERE Email LIKE ?";
    $stmt_total = $conn->prepare($sql_total);
    $stmt_total->bind_param("s", $search_param);
    $stmt_total->execute();
    $result_total = $stmt_total->get_result();
    $total_accounts = $result_total->fetch_assoc()['total'];
    $total_pages = ceil($total_accounts / $limit);
    ?>
    <div class="account-management">
        <h1><i class='bx bx-user-circle'></i> Quản Lý Tài Khoản</h1>
        <div class="search-container">
            <form method="GET" action="">
                <input type="text" name="search" placeholder="Tìm kiếm tài khoản..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit"><i class='bx bx-search'></i></button>
            </form>
        </div>
        <table class="account-table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Vai Trò</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['Email']); ?></td>
                            <td><?php echo htmlspecialchars($row['VaiTro']); ?></td>
                            <td>
                                <a href="edit_taikhoan.php?email=<?php echo $row['Email']; ?>" class="btn-edit"><i class='bx bxs-edit'></i> Sửa</a>
                                <a href="delete_taikhoan.php?email=<?php echo $row['Email']; ?>" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa tài khoản này?');"><i class='bx bxs-trash'></i> Xóa</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3">Không tìm thấy tài khoản nào.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?search=<?php echo htmlspecialchars($search); ?>&page=<?php echo $i; ?>" class="<?php echo ($page == $i) ? 'active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
        </div>
        <div class="add-account">
            <a href="add_taikhoan.php" class="btn-add"><i class='bx bx-plus-circle'></i> Thêm Tài Khoản</a>
        </div>
    </div>
</body>
</html>
