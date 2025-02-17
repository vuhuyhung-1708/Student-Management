
<?php
// Kết nối cơ sở dữ liệu
include_once '../Includes/Connect_db.php';
include_once '../View/header.php';
include_once '../Admin/menungang_admin.php';
// Lấy mã sinh viên từ URL
$ma_sv = isset($_GET['masv']) ? $_GET['masv'] : '';

// Kiểm tra xem mã sinh viên có được cung cấp không
if (empty($ma_sv)) {
    echo "Mã sinh viên không hợp lệ!";
    exit;
}

// Lấy thông tin sinh viên
$sql = "SELECT * FROM SinhVien WHERE MaSV = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $ma_sv);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Sinh viên không tồn tại!";
    exit;
}

$student = $result->fetch_assoc();

// Xử lý khi form được gửi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ho_ten = $_POST['ho_ten'];
    $ngay_sinh = $_POST['ngay_sinh'];
    $gioi_tinh = $_POST['gioi_tinh'];
    $lop = $_POST['lop'];
    $email = $_POST['email'];
    $so_dien_thoai = $_POST['so_dien_thoai'];

    $sql_update = "UPDATE SinhVien SET HoTen = ?, NgaySinh = ?, GioiTinh = ?, Lop = ?, Email = ?, SoDienThoai = ? WHERE MaSV = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sssssss", $ho_ten, $ngay_sinh, $gioi_tinh, $lop, $email, $so_dien_thoai, $ma_sv);

    if ($stmt_update->execute()) {
        echo "<p class='success'>Cập nhật thành công!</p>";
        header("refresh:2;url=quanly_sinhvien.php");
        exit;
    } else {
        echo "<p class='error'>Cập nhật thất bại: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sửa Sinh Viên</title>
    <link rel="stylesheet" href="../Access/Css/edit_sinhvien.css">
</head>
<body>
    <div class="edit-student-container">
        <h1>Sửa Thông Tin Sinh Viên</h1>
        <form method="POST">
            <label for="ho_ten">Họ Tên:</label>
            <input type="text" id="ho_ten" name="ho_ten" value="<?php echo htmlspecialchars($student['HoTen']); ?>" required>

            <label for="ngay_sinh">Ngày Sinh:</label>
            <input type="date" id="ngay_sinh" name="ngay_sinh" value="<?php echo htmlspecialchars($student['NgaySinh']); ?>" required>

            <label for="gioi_tinh">Giới Tính:</label>
            <select id="gioi_tinh" name="gioi_tinh">
                <option value="Nam" <?php echo $student['GioiTinh'] === 'Nam' ? 'selected' : ''; ?>>Nam</option>
                <option value="Nữ" <?php echo $student['GioiTinh'] === 'Nữ' ? 'selected' : ''; ?>>Nữ</option>
            </select>

            <label for="lop">Lớp:</label>
            <input type="text" id="lop" name="lop" value="<?php echo htmlspecialchars($student['Lop']); ?>" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($student['Email']); ?>" required>

            <label for="so_dien_thoai">Số Điện Thoại:</label>
            <input type="text" id="so_dien_thoai" name="so_dien_thoai" value="<?php echo htmlspecialchars($student['SoDienThoai']); ?>" required>

            <button type="submit">Lưu</button>
            <a href="quanly_sinhvien.php">Hủy</a>
        </form>
    </div>
</body>
</html>