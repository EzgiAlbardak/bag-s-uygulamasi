<?php
session_start();

if (!isset($_SESSION["token"])) {
    echo "<script>alert('Oturum geçerli değil. Lütfen giriş yapın.'); window.location.href = 'login.php';</script>";
    exit;
}

$token = $_SESSION["token"];
$amount = $_POST["amount"];
$description = $_POST["description"];

$data = [
    "amount" => $amount,
    "description" => $description
];

$url = "http://localhost:5000/api/donations/";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
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
    echo "<script>
        alert('✅ Bağış başarıyla gönderildi!');
        window.location.href = 'index.php';
    </script>";
} else {
    $errorText = isset($result["error"]) ? $result["error"] : "Bilinmeyen bir hata oluştu.";
    echo "<script>
        alert('❌ Bağış gönderilemedi: " . addslashes($errorText) . "');
        window.location.href = 'donate.php';
    </script>";
}
?>
