<?php
session_start();

if (!isset($_SESSION["is_admin"]) || $_SESSION["is_admin"] !== true || empty($_SESSION["admin_token"])) {
    header("Location: login.php");
    exit;
}

$adminToken = $_SESSION["admin_token"];
$query = isset($_GET['query']) ? $_GET['query'] : "";
$donationResults = [];

$donationUrl = "http://localhost:5000/api/donations/all_admin?query=" . urlencode($query);
$ch = curl_init($donationUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $adminToken"
]);
$response = curl_exec($ch);
curl_close($ch);
$donationResults = json_decode($response, true);
$donations = isset($donationResults["donations"]) ? $donationResults["donations"] : [];

$userUrl = "http://localhost:5000/api/auth/all_users";
$ch = curl_init($userUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $adminToken"
]);
$response = curl_exec($ch);
curl_close($ch);
$users = json_decode($response, true);
if (!is_array($users) || !isset($users[0]["id"])) {
    echo "<p style='color:red;'>❌ Kullanıcı listesi alınamadı. Token süreci dolmuş olabilir.</p>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
    $users = [];
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Paneli</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f5f8fc;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            max-width: 1200px;
            margin: 100px auto;
            gap: 30px;
            padding: 20px;
        }

        .box {
            flex: 1;
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        h2, h3, h4 {
            color: #007bff;
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .popup {
            display: none;
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translateX(-50%);
            background: #fff;
            padding: 20px;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            border-radius: 8px;
            width: 350px;
            z-index: 999;
        }

        label {
            font-weight: bold;
        }

        input, button {
            width: 100%;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .filter-form input {
            margin-bottom: 10px;
        }

        .filter-form button {
            background-color: #007bff;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
        }

        .filter-form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php include 'header.php'; ?>

<div class="container">
    <!-- Sol kolon: Kullanıcılar -->
    <div class="box">
        <h3>Kullanıcılar</h3>
        <table>
            <tr><th>ID</th><th>Ad</th><th>E-posta</th><th>İşlem</th></tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><button onclick="openEditPopup(<?= $user['id'] ?>, '<?= $user['username'] ?>', '<?= $user['email'] ?>')">Düzenle</button></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <!-- Sağ kolon: Bağışlar -->
    <div class="box">
        <h3>Bağışları Filtrele</h3>
        <form method="GET" class="filter-form">
            <input type="text" name="query" value="<?= htmlspecialchars($query) ?>" placeholder="Ad veya E-posta ile filtrele">
            <button type="submit">Filtrele</button>
        </form>

        <?php if (!empty($donationResults)): ?>
            <h4>Sonuçlar</h4>
            <table>
                <tr><th>Ad</th><th>E-posta</th><th>Tutar</th><th>Açıklama</th><th>Tarih</th></tr>
                <?php foreach ($donations as $donation): ?>
                    <tr>
                        <td><?= htmlspecialchars($donation['username']) ?></td>
                        <td><?= htmlspecialchars($donation['email']) ?></td>
                        <td><?= htmlspecialchars($donation['amount']) ?> TL</td>
                        <td><?= htmlspecialchars($donation['description']) ?></td>
                        <td><?= htmlspecialchars($donation['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php else: ?>
            <p>Bağış bulunamadı.</p>
        <?php endif; ?>
    </div>
</div>

<div id="editPopup" class="popup">
    <h3>Kullanıcıyı Düzenle</h3>
    <form method="POST" action="update_user.php">
        <input type="hidden" name="id" id="editId">
        <input type="hidden" name="token" value="<?= htmlspecialchars($adminToken) ?>">

        <label>Ad</label>
        <input type="text" name="username" id="editUsername">
        <label>E-posta</label>
        <input type="email" name="email" id="editEmail">
        <label>Yeni Şifre (Opsiyonel)</label>
        <input type="password" name="password">
        <button type="submit">Kaydet</button>
        <button type="button" onclick="closeEditPopup()">İptal</button>
    </form>
</div>

<script>
function openEditPopup(id, username, email) {
    document.getElementById('editId').value = id;
    document.getElementById('editUsername').value = username;
    document.getElementById('editEmail').value = email;
    document.getElementById('editPopup').style.display = 'block';
}
function closeEditPopup() {
    document.getElementById('editPopup').style.display = 'none';
}
</script>

</body>
</html>
