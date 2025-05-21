<?php
session_start();

$errorMessage = "";
$successMessage = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $registerData = [
        "username" => $username,
        "email" => $email,
        "password" => $password
    ];

    $registerUrl = "http://localhost:5000/api/auth/register";
    $ch = curl_init($registerUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($registerData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_POST, true);
    $registerResponse = curl_exec($ch);
    curl_close($ch);

    $registerResult = json_decode($registerResponse, true);

    if (isset($registerResult["message"])) {
        $loginData = [
            "email" => $email,
            "password" => $password
        ];
        $loginUrl = "http://localhost:5000/api/auth/login";
        $ch = curl_init($loginUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($loginData));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_POST, true);
        $loginResponse = curl_exec($ch);
        curl_close($ch);

        $loginResult = json_decode($loginResponse, true);

        if (isset($loginResult["access_token"])) {
            $_SESSION["token"] = $loginResult["access_token"];
            $_SESSION["username"] = $loginResult["username"];
            $_SESSION["email"] = $email;

            header("Location: index.php");
            exit;
        } else {
            $errorMessage = "Kayıt başarılı, ancak otomatik giriş başarısız oldu.";
        }
    } elseif (isset($registerResult["error"])) {
        $errorMessage = htmlspecialchars($registerResult["error"]);
    } else {
        $errorMessage = "Bilinmeyen bir hata oluştu.";
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
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
            width: 380px;
        }
        h2 {
            color: #007bff;
            text-align: center;
            margin-bottom: 20px;
        }
        input[type="text"],
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
        .message {
            text-align: center;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .error { color: red; }
        .success { color: green; }
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
    <h2>Kayıt Ol</h2>

    <?php if ($errorMessage): ?>
        <div class="message error"><?= $errorMessage ?></div>
    <?php elseif ($successMessage): ?>
        <div class="message success"><?= $successMessage ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="username" placeholder="Kullanıcı Adı" required>
        <input type="email" name="email" placeholder="E-Posta" required>
        <input type="password" name="password" placeholder="Şifre" required>
        <button type="submit">Kayıt Ol</button>
    </form>

    <div class="link">
        <a href="login.php">Zaten bir hesabınız var mı? Giriş yap</a>
    </div>
</div>

</body>
</html>
