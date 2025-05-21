<?php
if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET["token"])) {
    $token = $_GET["token"];
    $min_amount = $_GET["min_amount"] ?? '';
    $start_date = $_GET["start_date"] ?? '';

    $query = http_build_query([
        "min_amount" => $min_amount,
        "start_date" => $start_date
    ]);

    $ch = curl_init("http://localhost:5000/api/donations?$query");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $donations = json_decode($response, true);
    echo "<h3>Bağışlar</h3><ul>";
    if (is_array($donations)) {
        foreach ($donations as $item) {
            echo "<li><strong>{$item['amount']}₺</strong> - {$item['description']} ({$item['created_at']})</li>";
        }
    } else {
        echo "<pre>" . print_r($donations, true) . "</pre>";
    }
    echo "</ul>";
}
?>

<h2>Bağış Filtrele</h2>
<form method="GET">
    JWT Token: <input type="text" name="token"><br><br>
    Min. Tutar: <input type="number" name="min_amount"><br><br>
    Başlangıç Tarihi: <input type="date" name="start_date"><br><br>
    <button type="submit">Filtrele</button>
</form>
