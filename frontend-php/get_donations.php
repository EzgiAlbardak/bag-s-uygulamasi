<?php
session_start();

if (!isset($_SESSION["token"])) {
    echo "<p style='color:red; text-align:center;'>Lütfen önce giriş yapın.</p>";
    exit;
}

$token = $_SESSION["token"];
$email = $_SESSION["email"];
$donations = [];

$url = "http://localhost:5000/api/donations/all";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);
$response = curl_exec($ch);
curl_close($ch);

$result = json_decode($response, true);
if (is_array($result)) {
    $donations = $result;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yaptığım Bağışlar</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f4f8;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 900px;
            margin: 100px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #007bff;
            text-align: center;
            margin-bottom: 10px;
        }

        .email {
            text-align: center;
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th, td {
            padding: 12px 16px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f2f2f2;
        }

        .empty {
            text-align: center;
            color: #999;
            margin-top: 20px;
            font-style: italic;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <h2>Yaptığım Bağışlar</h2>
    <div class="email">📧 <?= htmlspecialchars($email) ?> kullanıcısının bağışları</div>

    <?php if (!empty($donations)): ?>
        <table>
            <thead>
                <tr>
                    <th>Miktar (₺)</th>
                    <th>Açıklama</th>
                    <th>Tarih</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($donations as $donation): ?>
                <?php if (is_array($donation)): ?>
                    <tr>
                        <td><?= htmlspecialchars($donation["amount"]) ?></td>
                        <td><?= htmlspecialchars($donation["description"]) ?></td>
                        <td><?= htmlspecialchars($donation["created_at"]) ?></td>
                    </tr>
                <?php endif; ?>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="empty">Hiç bağış yapılmamış.</p>
    <?php endif; ?>
</div>

</body>
</html>
