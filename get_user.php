<?php
$token = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST["token"];

    $url = "http://localhost:5000/api/auth/user";
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $token"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if (isset($result["username"])) {
        echo "<h3>Kullanıcı Bilgileri:</h3>";
        echo "<p><strong>Kullanıcı Adı:</strong> " . htmlspecialchars($result["username"]) . "</p>";
        echo "<p><strong>E-posta:</strong> " . htmlspecialchars($result["email"]) . "</p>";
        echo "<p><strong>ID:</strong> " . htmlspecialchars($result["id"]) . "</p>";
    } elseif (isset($result["error"])) {
        echo "<p style='color:red'>" . htmlspecialchars($result["error"]) . "</p>";
    } else {
        echo "<pre>" . print_r($result, true) . "</pre>";
    }
}
?>

<h2>Kullanıcı Bilgilerini Getir</h2>
<form method="POST">
    Token: <input type="text" name="token" required style="width: 400px;"><br><br>
    <button type="submit">Getir</button>
</form>
