<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Access/Css/dashboard.css"> <!-- Liên kết CSS -->
    <title>Trang Chủ</title>
</head>
<body>
    <?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start(); // Chỉ khởi động session nếu chưa được khởi động
    }
    include_once '../View/header.php';
    include_once '../View/Menu_ngang.php';
    ?>
   <div class="banner-container">
        <div class="banner-wrapper">
            <!-- Banner 1 -->
            <div class="banner">
                <img src="../Access/Img/Banner/1156x350.png" alt="Banner 1" class="banner-image">
            </div>
            <!-- Banner 2 -->
            <div class="banner">
                <img src="../Access/Img/Banner/1133444.jpg" alt="Banner 2" class="banner-image">
            </div>
        </div>
    </div>
    <div class="news-section">
        <iframe src="../View/tintuc.html" class="news-iframe" frameborder="0"></iframe>
    </div>
</body>
</html>
