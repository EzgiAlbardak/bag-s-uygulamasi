<?php
session_start();

if (!isset($_SESSION["token"])) {
    echo "Bu sayfayı görmek için giriş yapmalısınız.";
    exit;
}

$email = $_SESSION["email"];
$token = $_SESSION["token"];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Bağış Yap</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(to right, #e3f2fd, #f8f9fa);
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 400px;
            margin: 100px auto;
            background-color: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            text-align: center;
        }

        h2 {
            color: #007bff;
            margin-bottom: 25px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 16px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background-color 0.2s;
            width: 100%;
        }

        button:hover {
            background-color: #0056b3;
        }

        #cardPopup {
            display: none;
            position: fixed;
            top: 20%;
            left: 50%;
            transform: translate(-50%, -20%);
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 25px rgba(0,0,0,0.2);
            width: 320px;
            z-index: 999;
        }

        #cardPopup input {
            margin-bottom: 12px;
            width: 100%;
        }

        #popupSuccess {
            position: fixed;
            top: 15%;
            left: 50%;
            transform: translateX(-50%);
            background: #e7fbe7;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 16px 24px;
            border-radius: 10px;
            display: none;
            font-weight: bold;
            z-index: 1000;
        }
    </style>
</head>
<body>

<?php include 'header.php'; ?>

<div class="container">
    <h2>Bağış Yap</h2>
    <form id="donationForm">
        <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" readonly>
        <input type="number" name="amount" placeholder="Tutar (₺)" required>
        <input type="text" name="description" placeholder="Açıklama" required>
        <button type="button" onclick="showCardPopup()">Bağış Yap</button>
    </form>
</div>

<div id="cardPopup">
    <h3>Kart Bilgileri</h3>
    <input type="text" id="cardNumber" placeholder="Kart Numarası (1234 5678 9012 3456)">
    <input type="text" id="expiry" placeholder="Son Kullanma (MM/YY)">
    <input type="text" id="cvv" placeholder="CVV (123)">
    <button onclick="confirmCard()">Onayla ve Gönder</button>
</div>

<div id="popupSuccess">✅ Bağış başarıyla gönderildi!</div>

<script>
    const USER_TOKEN = "<?= $_SESSION['token'] ?>";

    function showCardPopup() {
        document.getElementById("cardPopup").style.display = "block";
    }

    function confirmCard() {
        const cardNumber = document.getElementById("cardNumber").value.trim();
        const expiry = document.getElementById("expiry").value.trim();
        const cvv = document.getElementById("cvv").value.trim();

        if (!cardNumber || !expiry || !cvv) {
            alert("Lütfen tüm kart bilgilerini doldurun.");
            return;
        }

        document.getElementById("cardPopup").style.display = "none";

        const form = document.getElementById("donationForm");
        const formData = new FormData(form);
        const data = {
            amount: formData.get("amount"),
            description: formData.get("description")
        };

        fetch("http://localhost:5000/api/donations/", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Authorization": `Bearer ${USER_TOKEN}`
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(result => {
            if (result.message) {
                document.getElementById("popupSuccess").style.display = "block";
                form.reset();
                setTimeout(() => {
                    document.getElementById("popupSuccess").style.display = "none";
                }, 3000);
            } else {
                alert("❌ Hata: " + (result.error || "Bağış gönderilemedi."));
            }
        })
        .catch(err => {
            alert("❌ Bağlantı hatası: " + err.message);
        });
    }
</script>

</body>
</html>
