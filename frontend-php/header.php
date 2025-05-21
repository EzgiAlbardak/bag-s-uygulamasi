<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$isLoggedIn = isset($_SESSION['token']);
$isAdmin = isset($_SESSION['is_admin']) && $_SESSION['is_admin'] === true;

$token = $_SESSION['token'] ?? null;
$jwt = $token ? json_decode(base64_decode(explode('.', $token)[1]), true) : null;
$exp = $jwt['exp'] ?? null;
$currentTime = time();
?>
<div class="header">
    <div class="logo">Bağış Takip</div>
    <div class="nav-center">
        <?php if ($isLoggedIn): ?>
            <a href="index.php">Anasayfa</a>
            <a href="get_donations.php">Bağışlarım</a>
            <a href="logout.php">Çıkış Yap</a>
            <?php if ($exp): ?>
                <span class="session-info" id="sessionTimer">⏳ Oturum süresi hesaplanıyor...</span>
                <script>
                    const sessionExp = <?= $exp ?>;
                    const now = Math.floor(Date.now() / 1000);
                    let secondsLeft = sessionExp - now;

                    function formatTime(sec) {
                        const m = Math.floor(sec / 60);
                        const s = sec % 60;
                        return `${m}dk ${s}sn`;
                    }

                    function updateTimer() {
                        const timer = document.getElementById("sessionTimer");
                        if (secondsLeft <= 0) {
                            timer.innerHTML = "⛔ Oturum süresi doldu. Lütfen tekrar giriş yapın.";
                            timer.style.color = "red";
                        } else {
                            timer.innerHTML = `⏳ Oturum: ${formatTime(secondsLeft)}`;
                            secondsLeft--;
                            setTimeout(updateTimer, 1000);
                        }
                    }
                    updateTimer();
                </script>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<style>
    .header {
        background-color: #3f80ff;
        color: white;
        padding: 15px 30px;
        display: flex;
        justify-content: center;
        align-items: center;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 100;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .logo {
        position: absolute;
        left: 20px;
        font-weight: bold;
        font-size: 20px;
    }
    .nav-center {
        display: flex;
        gap: 20px;
        align-items: center;
    }
    .nav-center a {
        color: white;
        text-decoration: none;
        font-weight: bold;
    }
    .nav-center a:hover {
        text-decoration: underline;
    }
    .session-info {
        font-size: 14px;
        margin-left: 10px;
    }
</style>
