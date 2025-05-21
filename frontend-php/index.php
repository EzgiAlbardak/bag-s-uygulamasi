<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$isLoggedIn = isset($_SESSION['token']);
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Bağış Takip Sistemi</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #f8f9fa, #e3f2fd);
        }
        .main-container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 60px;
        }
        .content {
            background-color: white;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
            width: 350px;
        }
        h1 {
            color: #007bff;
            margin-bottom: 30px;
        }
        .link-container a {
            display: block;
            padding: 12px;
            margin-bottom: 12px;
            background-color: #007bff;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.2s ease;
        }
        .link-container a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="main-container">
    <div class="content">
        <h1>Bağış Takip Sistemi</h1>
        <div class="link-container">
            <?php if (!$isLoggedIn): ?>
                <a href="login.php">Giriş Yap</a>
                <a href="register.php">Kayıt Ol</a>
            <?php else: ?>
                <a href="donate.php">Bağış Yap</a>
                <?php if ($isAdmin): ?>
                    <a href="admin.php">Admin Görünüm</a>
                <?php else: ?>
                    <a href="get_donations.php">Yaptığım Bağışlar</a>
                <?php endif; ?>
                <a href="logout.php">Çıkış Yap</a>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
