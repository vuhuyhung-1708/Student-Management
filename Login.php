<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Access/Css/login.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <title>Login Form</title>
</head>
<body>
    <div class="login-container">
        <form action="XuLy_Login.php" method="POST">
            <h1>Login Form</h1>
            <div class="input-group">
                <i class='bx bx-envelope'></i>
                <input type="email" name="email" placeholder="Email" required>
            </div>
            <div class="input-group">
                <i class='bx bx-lock'></i>
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn">LOGIN</button>
            <p>Or login with</p>
            <div class="social-login">
                <button type="button" class="btn-facebook">
                    <i class='bx bxl-facebook'></i> Facebook
                </button>
                <button type="button" class="btn-google">
                    <i class='bx bxl-google'></i> Google
                </button>
            </div>
            <p>Not a member? <a href="Signup.php">Signup now</a></p>
        </form>
    </div>
</body>
</html>
