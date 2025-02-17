<?php
// Kết nối cơ sở dữ liệu
include_once '../Includes/Connect_db.php';
include_once '../View/header.php';
include_once '../Admin/menungang_admin.php';
// Lấy email từ URL
$email = isset($_GET['email']) ? $_GET['email'] : '';

// Kiểm tra xem email có được cung cấp không
if (empty($email)) {
    echo "Email không hợp lệ!";
    exit;
}

// Lấy thông tin tài khoản từ database
$sql_check = "SELECT * FROM TaiKhoan WHERE Email = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $email);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    echo "Tài khoản không tồn tại!";
    exit;
}

$account = $result_check->fetch_assoc();

// Xử lý khi xác nhận xóa
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql_delete = "DELETE FROM TaiKhoan WHERE Email = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("s", $email);

    if ($stmt_delete->execute()) {
        echo "<p class='message-success'>Xóa tài khoản thành công!</p>";
        header("refresh:2;url=quanly_taikhoan.php"); // Quay lại trang quản lý tài khoản sau 2 giây
        exit;
    } else {
        echo "<p class='message-error'>Lỗi khi xóa tài khoản: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa Tài Khoản</title>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Access/Css/delete_taikhoan.css">
</head>
<body>
    <div class="delete-account-container">
        <h1><i class='bx bxs-trash'></i> Xóa Tài Khoản</h1>
        <p>Bạn có chắc chắn muốn xóa tài khoản này?</p>
        <div class="account-info">
            <p><strong>Email:</strong> <?php echo htmlspecialchars($account['Email']); ?></p>
            <p><strong>Vai Trò:</strong> <?php echo htmlspecialchars($account['VaiTro']); ?></p>
        </div>
        <form method="POST" action="">
            <button type="submit" class="btn-delete"><i class='bx bx-check'></i> Xác Nhận Xóa</button>
            <a href="quanly_taikhoan.php" class="btn-cancel"><i class='bx bx-x'></i> Hủy</a>
        </form>
    </div>
</body>
</html>
