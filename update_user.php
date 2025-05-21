<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_SESSION["admin_token"])) {
        echo "<p style='color:red'>Oturum token bulunamadı. Lütfen tekrar giriş yapın.</p>";
        exit;
    }

    $token = $_SESSION["admin_token"]; 

    $data = [
        "username" => $_POST["username"],
        "email" => $_POST["email"],
        "password" => $_POST["password"]
    ];

    $url = "http://localhost:5000/api/auth/user";
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $token"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result["message"])) {
        header("Location: admin.php");
        exit;
    } elseif (isset($result["error"])) {
        echo "<p style='color:red'>" . htmlspecialchars($result["error"]) . "</p>";
    } else {
        echo "<pre>" . print_r($result, true) . "</pre>";
    }
}
?>
