<?php
/*
 * SaaS self-registration page
 * Allows clients to register as tenants / agents
 */

session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

include_once(__DIR__ . '/../include/db_config.php');
include_once(__DIR__ . '/../lib/Owner.class.php');

$error = '';
$success = '';

$planParam = $_GET['plan'] ?? 'bronze';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['agent_name']);
    $phone = trim($_POST['phone']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $level = $_POST['level'] ?? 'bronze'; // bronze, silver, gold, platinum
    
    if (empty($name) || empty($phone) || empty($password)) {
        $error = 'Nama Lengkap, Nomor WhatsApp, dan Password wajib diisi!';
    } else {
        $ownerObj = new Owner();
        
        // Check if phone/email already registered
        $existing = $ownerObj->getOwnerByIdentifier($phone);
        if (!$existing && !empty($email)) {
            $existing = $ownerObj->getOwnerByIdentifier($email);
        }
        
        if ($existing) {
            $error = 'Nomor WhatsApp atau Email ini sudah terdaftar!';
        } else {
            // Generate clean username from name
            $usernameBase = preg_replace('/[^a-zA-Z0-9]/', '', strtolower($name));
            if (empty($usernameBase)) {
                $usernameBase = 'owner';
            }
            $username = $usernameBase;
            
            // Ensure unique username
            $db = getDBConnection();
            $stmt = $db->prepare("SELECT COUNT(*) FROM owners WHERE username = ?");
            $stmt->execute([$username]);
            $count = (int)$stmt->fetchColumn();
            if ($count > 0) {
                $username = $usernameBase . rand(100, 999);
            }
            
            // Paid plans are pending ('inactive') until paid
            $status = ($level === 'bronze') ? 'active' : 'inactive';
            
            $data = [
                'username' => $username,
                'agent_name' => $name, // Used for display
                'phone' => $phone,
                'email' => $email,
                'password' => $password,
                'status' => $status,
                'level' => $level
            ];
            
            $result = $ownerObj->createOwner($data);
            if ($result['success']) {
                $ownerId = $result['owner_id'];
                
                if ($level === 'bronze') {
                    $success = "Akun Owner Anda berhasil dibuat! Username Anda adalah: <strong>$username</strong>";
                } else {
                    // Paid plans -> Sumopod QRIS checkout
                    $prices = [
                        'silver' => 50000,
                        'gold' => 150000,
                        'platinum' => 300000
                    ];
                    $amount = $prices[$level] ?? 50000;
                    
                    try {
                        // Get Sumopod API Keys
                        $stmt = $db->prepare("SELECT setting_value FROM payment_gateway_config WHERE gateway_name = 'sumopod' AND setting_key = 'api_key'");
                        $stmt->execute();
                        $apiKey = $stmt->fetchColumn();
                        
                        $stmt = $db->prepare("SELECT setting_value FROM payment_gateway_config WHERE gateway_name = 'sumopod' AND setting_key = 'is_sandbox'");
                        $stmt->execute();
                        $isSandbox = (int)$stmt->fetchColumn();
                        
                        $apiUrl = $isSandbox 
                            ? 'https://api-pay-sandbox.sumopod.com/api/v1/payments' 
                            : 'https://api-pay.sumopod.com/api/v1/payments';
                        
                        $orderId = "SAAS-" . $ownerId;
                        
                        // Hit Sumopod API
                        $curl = curl_init();
                        curl_setopt_array($curl, [
                            CURLOPT_URL => $apiUrl,
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_POST => true,
                            CURLOPT_POSTFIELDS => json_encode([
                                'order_id' => $orderId,
                                'amount' => $amount,
                                'currency' => 'IDR',
                                'expires_in_hours' => 24,
                                'success_return_url' => 'https://' . $_SERVER['HTTP_HOST'] . '/public/register.php?status=success&code=' . $username,
                                'cancel_return_url' => 'https://' . $_SERVER['HTTP_HOST'] . '/public/register.php?status=cancelled',
                                'payment_method_type_code' => 'QRIS'
                            ]),
                            CURLOPT_HTTPHEADER => [
                                'Content-Type: application/json',
                                'X-Api-Key: ' . ($apiKey ?: '642a01968d53909d47205eacaacf3c78a63c96637d44ae42f1e6e265eb6095f1')
                            ]
                        ]);
                        
                        $response = curl_exec($curl);
                        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
                        curl_close($curl);
                        
                        $res = json_decode($response, true);
                        if ($httpCode >= 200 && $httpCode < 300 && isset($res['payment_link_url'])) {
                            header("Location: " . $res['payment_link_url']);
                            exit();
                        } else {
                            // Fallback auto-active if API error
                            $db->prepare("UPDATE owners SET status = 'active' WHERE id = ?")->execute([$ownerId]);
                            $success = "Akun Owner dibuat (Aktif otomatis karena gangguan Sumopod). Username: <strong>$username</strong>";
                        }
                    } catch (Exception $ex) {
                        $success = "Akun Owner dibuat (Aktif otomatis karena gangguan system). Username: <strong>$username</strong>";
                    }
                }
            } else {
                $error = $result['message'];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar SaaS Mikhmon Agent - Platform Voucher & OLT ISP</title>
    <!-- Google Fonts & FontAwesome -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <style>
        :root {
            --bg-color: #0b0f19;
            --card-bg: rgba(17, 24, 39, 0.7);
            --border-color: rgba(255, 255, 255, 0.1);
            --primary-color: #3b82f6;
            --primary-glow: rgba(59, 130, 246, 0.5);
            --success-color: #10b981;
            --danger-color: #ef4444;
            --text-main: #f3f4f6;
            --text-muted: #9ca3af;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            background-image: 
                radial-gradient(at 10% 20%, rgba(59, 130, 246, 0.15) 0px, transparent 50%),
                radial-gradient(at 90% 80%, rgba(16, 185, 129, 0.1) 0px, transparent 50%);
            background-attachment: fixed;
            color: var(--text-main);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        .container {
            width: 100%;
            max-width: 900px;
        }

        /* Glassmorphism Card */
        .register-card {
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.5);
            transition: all 0.3s ease;
        }

        .register-header {
            text-align: center;
            margin-bottom: 40px;
        }

        .register-header img {
            width: 64px;
            height: 64px;
            margin-bottom: 16px;
            filter: drop-shadow(0 0 10px var(--primary-glow));
        }

        .register-header h1 {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
            background: linear-gradient(135deg, #fff 0%, #9ca3af 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .register-header p {
            color: var(--text-muted);
            font-size: 16px;
        }

        /* Forms Layout */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: var(--text-main);
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 14px 16px;
            background: rgba(31, 41, 55, 0.5);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            color: #fff;
            font-size: 15px;
            outline: none;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px var(--primary-glow);
            background: rgba(31, 41, 55, 0.8);
        }

        /* Package Grid selector */
        .plans-label {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 16px;
            display: block;
        }

        .plans-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 30px;
        }

        @media (max-width: 768px) {
            .plans-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .plans-grid {
                grid-template-columns: 1fr;
            }
        }

        .plan-card {
            background: rgba(31, 41, 55, 0.4);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px 16px;
            text-align: center;
            cursor: pointer;
            position: relative;
            transition: all 0.2s ease;
        }

        .plan-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .plan-card input[type="radio"] {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        .plan-card.active {
            border-color: var(--primary-color);
            background: rgba(59, 130, 246, 0.1);
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.2);
        }

        .plan-card h3 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .plan-card .price {
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 10px;
        }

        .plan-card .features {
            font-size: 12px;
            color: var(--text-muted);
            line-height: 1.4;
        }

        /* Buttons & Alerts */
        .btn-register {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, var(--primary-color) 0%, #2563eb 100%);
            border: none;
            border-radius: 12px;
            color: #fff;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
            transition: all 0.2s ease;
        }

        .btn-register:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        .alert {
            padding: 16px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 15px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: #a7f3d0;
            flex-direction: column;
            text-align: center;
            padding: 30px;
        }

        .alert-success h2 {
            margin-bottom: 10px;
            color: var(--success-color);
        }

        .login-link {
            text-align: center;
            margin-top: 24px;
            font-size: 15px;
            color: var(--text-muted);
        }

        .login-link a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="register-card">
        
        <?php if ($success): ?>
            <div class="alert alert-success">
                <h2><i class="fa fa-check-circle-o" style="font-size: 48px;"></i></h2>
                <h2>Registrasi Berhasil!</h2>
                <p style="margin-bottom: 20px;"><?= $success; ?></p>
                <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 20px;">
                    Silakan gunakan Username / Email / WhatsApp dan password Anda untuk masuk ke dashboard Admin.
                </p>
                <a href="../admin.php" class="btn-register" style="display: inline-block; text-decoration: none; text-align: center; width: auto; padding: 12px 30px;">
                    Masuk Dashboard Admin
                </a>
            </div>
        <?php else: ?>
            
            <div class="register-header">
                <img src="../img/favicon.png" alt="Mikhmon Logo">
                <h1>Daftar Mikhmon SaaS</h1>
                <p>Mulai kelola voucher Mikrotik & monitoring OLT Anda dalam hitungan menit.</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-circle"></i>
                    <span><?= $error; ?></span>
                </div>
            <?php endif; ?>

            <form action="" method="POST" autocomplete="off">
                <div class="form-row">
                    <div class="form-group">
                        <label for="agent_name">Nama Lengkap / Perusahaan / ISP *</label>
                        <input type="text" id="agent_name" name="agent_name" class="form-control" placeholder="Contoh: Samudra Indah Net" required value="<?= isset($_POST['agent_name']) ? htmlspecialchars($_POST['agent_name']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="phone">Nomor WhatsApp (Untuk Login) *</label>
                        <input type="text" id="phone" name="phone" class="form-control" placeholder="Contoh: 081234567890" required value="<?= isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Alamat Email (Opsional)</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Contoh: admin@samudraindah.net" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>
                    <div class="form-group">
                        <label for="password">Password Akun *</label>
                        <input type="password" id="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>
                </div>

                <span class="plans-label">Pilih Paket Langganan SaaS:</span>
                <div class="plans-grid">
                    <div class="plan-card <?= $planParam == 'bronze' ? 'active' : ''; ?>" onclick="selectPlan(this, 'bronze')">
                        <input type="radio" name="level" value="bronze" <?= $planParam == 'bronze' ? 'checked' : ''; ?>>
                        <h3>Bronze</h3>
                        <div class="price">Gratis (Masa Uji)</div>
                        <div class="features">1 Router<br>100 Voucher / bln</div>
                    </div>
                    <div class="plan-card <?= $planParam == 'silver' ? 'active' : ''; ?>" onclick="selectPlan(this, 'silver')">
                        <input type="radio" name="level" value="silver" <?= $planParam == 'silver' ? 'checked' : ''; ?>>
                        <h3>Silver</h3>
                        <div class="price">Rp 50rb / bln</div>
                        <div class="features">2 Router<br>300 Voucher / bln</div>
                    </div>
                    <div class="plan-card <?= $planParam == 'gold' ? 'active' : ''; ?>" onclick="selectPlan(this, 'gold')">
                        <input type="radio" name="level" value="gold" <?= $planParam == 'gold' ? 'checked' : ''; ?>>
                        <h3>Gold</h3>
                        <div class="price">Rp 150rb / bln</div>
                        <div class="features">4 Router + 3 OLT<br>Unlimited Voucher</div>
                    </div>
                    <div class="plan-card <?= $planParam == 'platinum' ? 'active' : ''; ?>" onclick="selectPlan(this, 'platinum')">
                        <input type="radio" name="level" value="platinum" <?= $planParam == 'platinum' ? 'checked' : ''; ?>>
                        <h3>Platinum</h3>
                        <div class="price">Rp 300rb / bln</div>
                        <div class="features">Ulimited Router & OLT<br>Prioritas Support</div>
                    </div>
                </div>

                <button type="submit" class="btn-register">Daftar & Buat Akun</button>

                <div class="login-link">
                    Sudah memiliki akun? <a href="../admin.php">Masuk di sini</a>
                </div>
            </form>
            
        <?php endif; ?>
        
    </div>
</div>

<script>
    function selectPlan(card, planValue) {
        // Remove active class from all cards
        document.querySelectorAll('.plan-card').forEach(function(c) {
            c.classList.remove('active');
        });
        
        // Add active class to clicked card
        card.classList.add('active');
        
        // Check corresponding radio button
        card.querySelector('input[type="radio"]').checked = true;
    }
</script>
</body>
</html>
