<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống Kê</title>
    <!-- Boxicons CSS -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../Access/Css/thongke.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

    // Thống kê dữ liệu
    $sql_gender = "SELECT GioiTinh, COUNT(*) AS total FROM SinhVien GROUP BY GioiTinh";
    $result_gender = $conn->query($sql_gender);
    $gender_data = [];
    while ($row = $result_gender->fetch_assoc()) {
        $gender_data[] = [
            'label' => $row['GioiTinh'],
            'value' => $row['total']
        ];
    }

    $sql_score_categories = "
        SELECT 
            SUM(CASE WHEN Diem >= 8.5 THEN 1 ELSE 0 END) AS gioi,
            SUM(CASE WHEN Diem >= 7.0 AND Diem < 8.5 THEN 1 ELSE 0 END) AS kha,
            SUM(CASE WHEN Diem >= 5.0 AND Diem < 7.0 THEN 1 ELSE 0 END) AS trung_binh,
            SUM(CASE WHEN Diem < 5.0 THEN 1 ELSE 0 END) AS yeu
        FROM BangDiem";
    $result_score_categories = $conn->query($sql_score_categories);
    $score_categories = $result_score_categories->fetch_assoc();

    // Dữ liệu để truyền vào biểu đồ JS
    $gender_labels = json_encode(array_column($gender_data, 'label'));
    $gender_values = json_encode(array_column($gender_data, 'value'));

    $score_labels = json_encode(["Giỏi", "Khá", "Trung Bình", "Yếu"]);
    $score_values = json_encode(array_values($score_categories));
    ?>
    <div class="statistics-container">
        <h1><i class='bx bx-bar-chart'></i> Thống Kê</h1>

        <!-- Biểu đồ giới tính -->
        <div class="chart-container">
            <h2>Thống Kê Sinh Viên Theo Giới Tính</h2>
            <canvas id="genderChart"></canvas>
        </div>

        <!-- Biểu đồ phân loại điểm -->
        <div class="chart-container">
            <h2>Phân Loại Điểm</h2>
            <canvas id="scoreChart"></canvas>
        </div>
    </div>

    <!-- JavaScript: Chart.js -->
    <script>
        // Biểu đồ giới tính
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        new Chart(genderCtx, {
            type: 'pie', // Biểu đồ tròn
            data: {
                labels: <?php echo $gender_labels; ?>,
                datasets: [{
                    label: 'Số Lượng Sinh Viên',
                    data: <?php echo $gender_values; ?>,
                    backgroundColor: ['#007bff', '#ffc107'], // Màu sắc cho các phần
                    borderColor: ['#ffffff', '#ffffff'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Biểu đồ phân loại điểm
        const scoreCtx = document.getElementById('scoreChart').getContext('2d');
        new Chart(scoreCtx, {
            type: 'bar', // Biểu đồ cột
            data: {
                labels: <?php echo $score_labels; ?>,
                datasets: [{
                    label: 'Số Lượng Sinh Viên',
                    data: <?php echo $score_values; ?>,
                    backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545'], // Màu cho từng cột
                    borderColor: '#ffffff',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Số lượng'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>
</html>
