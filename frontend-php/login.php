<?php
session_start();

$errorMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $data = [
        "email" => $_POST["email"],
        "password" => $_POST["password"]
    ];

    $url = "http://localhost:5000/api/auth/login";
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result["access_token"])) {
        $token = $result["access_token"];
        $_SESSION["token"] = $token;
        $_SESSION["username"] = $result["username"];
        $_SESSION["email"] = $_POST["email"];

        if ($_POST["email"] === "admin@admin.com") {
            $_SESSION["admin_token"] = $token;
            $_SESSION["is_admin"] = true;
            header("Location: admin.php");
            exit;
        }

        header("Location: index.php");
        exit;
    } elseif (isset($result["error"])) {
        $errorMessage = htmlspecialchars($result["error"], ENT_QUOTES, 'UTF-8');
    } else {
        $errorMessage = "Bilinmeyen bir hata oluştu.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #f8f9fa, #e3f2fd);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            width: 350px;
        }
        h2 {
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px;
            width: 100%;
            border-radius: 6px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }
        .link {
            text-align: center;
            margin-top: 15px;
        }
        .link a {
            color: #007bff;
            text-decoration: none;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Giriş Yap</h2>

    <?php if ($errorMessage): ?>
        <div class="error"><?= $errorMessage ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="email" name="email" placeholder="E-Posta" required>
        <input type="password" name="password" placeholder="Şifre" required>
        <button type="submit">Giriş Yap</button>
    </form>

    <div class="link">
        <a href="register.php">Hesabınız yok mu? Kayıt olun</a>
    </div>
</div>

</body>
</html>
