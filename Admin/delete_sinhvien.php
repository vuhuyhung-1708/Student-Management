<?php
include_once '../Includes/Connect_db.php';
include_once '../View/header.php';
include_once '../Admin/menungang_admin.php';
$ma_sv = isset($_GET['masv']) ? $_GET['masv'] : '';
if (empty($ma_sv)) {
    echo "Mã sinh viên không hợp lệ!";
    exit;
}

$sql_check = "SELECT * FROM SinhVien WHERE MaSV = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("s", $ma_sv);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    echo "Sinh viên không tồn tại!";
    exit;
}

$student = $result_check->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sql_delete = "DELETE FROM SinhVien WHERE MaSV = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("s", $ma_sv);

    if ($stmt_delete->execute()) {
        echo "<p class='success'>Xóa sinh viên thành công!</p>";
        header("refresh:2;url=quanly_sinhvien.php");
        exit;
    } else {
        echo "<p class='error'>Xóa thất bại: " . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa Sinh Viên</title>
    <link rel="stylesheet" href="../Access/Css/delete_sinhvien.css">
</head>
<body>
    <div class="delete-student-container">
        <h1>Xóa Sinh Viên</h1>
        <p>Bạn có chắc muốn xóa sinh viên này không?</p>
        <div class="student-info">
            <p><strong>Mã Sinh Viên:</strong> <?php echo htmlspecialchars($student['MaSV']); ?></p>
            <p><strong>Họ Tên:</strong> <?php echo htmlspecialchars($student['HoTen']); ?></p>
        </div>
        <form method="POST">
            <button type="submit">Xóa</button>
            <a href="quanly_sinhvien.php">Hủy</a>
        </form>
    </div>
</body>
</html>
