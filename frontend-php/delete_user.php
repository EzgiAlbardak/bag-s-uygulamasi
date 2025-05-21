<?php
session_start();

if (!isset($_SESSION["admin_token"])) {
    echo "<p style='color:red;'>Admin girişi yapılmamış. Lütfen önce giriş yapın.</p>";
    exit;
}

$token = $_SESSION["admin_token"];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["user_id"])) {
    $userId = $_POST["user_id"];
    $url = "http://localhost:5000/api/auth/user/$userId";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $token"
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);
    if (isset($result["message"])) {
        echo "<p style='color:green'><strong>" . htmlspecialchars($result["message"]) . "</strong></p>";
    } elseif (isset($result["error"])) {
        echo "<p style='color:red'>" . htmlspecialchars($result["error"]) . "</p>";
    }
}

$ch = curl_init("http://localhost:5000/api/auth/all_users");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token"
]);
$response = curl_exec($ch);
curl_close($ch);

$users = json_decode($response, true);
?>

<h2>Kullanıcıları Sil</h2>

<?php if (is_array($users)): ?>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Kullanıcı Adı</th>
            <th>E-posta</th>
            <th>İşlem</th>
        </tr>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= htmlspecialchars($user["id"]) ?></td>
                <td><?= htmlspecialchars($user["username"]) ?></td>
                <td><?= htmlspecialchars($user["email"]) ?></td>
                <td>
                    <form method="POST" onsubmit="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz?');">
                        <input type="hidden" name="user_id" value="<?= $user["id"] ?>">
                        <button type="submit" style="color:red;">Sil</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p style="color:red;">Kullanıcı listesi alınamadı. Token süresi dolmuş olabilir.</p>
<?php endif; ?>
