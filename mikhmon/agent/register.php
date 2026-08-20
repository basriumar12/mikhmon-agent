<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Check if already logged in
if (isset($_SESSION['agent_id'])) {
    header("Location: dashboard.php");
    exit();
}

include_once('../include/db_config.php');
include_once('../lib/Agent.class.php');

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $agent_name = trim($_POST['agent_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $owner_id = (int)($_GET['owner_id'] ?? $_POST['owner_id'] ?? $_SESSION['owner_id'] ?? 1);
    
    if (empty($agent_name) || empty($phone) || empty($password)) {
        $error = 'Nama, nomor telepon, dan password wajib diisi.';
    } elseif ($password !== $confirm_password) {
        $error = 'Konfirmasi password tidak cocok.';
    } elseif (strlen($password) < 6) {
        $error = 'Password minimal 6 karakter.';
    } else {
        try {
            $db = getDBConnection();
            // Check if phone number already exists
            $stmt = $db->prepare("SELECT id FROM agents WHERE phone = ?");
            $stmt->execute([$phone]);
            if ($stmt->fetch()) {
                $error = 'Nomor telepon ini sudah terdaftar sebagai Agen.';
            } else {
                $agentObj = new Agent();
                $agent_code = 'AG' . rand(1000, 9999);
                
                // Ensure agent_code is unique
                $stmtCode = $db->prepare("SELECT id FROM agents WHERE agent_code = ?");
                $stmtCode->execute([$agent_code]);
                if ($stmtCode->fetch()) {
                    $agent_code = 'AG' . rand(10000, 99999);
                }
                
                $data = [
                    'agent_code' => $agent_code,
                    'agent_name' => $agent_name,
                    'phone' => $phone,
                    'email' => $email ?: null,
                    'password' => $password,
                    'status' => 'active',
                    'level' => 'bronze',
                    'balance' => 0,
                    'owner_id' => $owner_id,
                    'created_by' => 'self'
                ];
                
                $res = $agentObj->createAgent($data);
                if ($res['success']) {
                    $success = 'Pendaftaran Agen berhasil! Kode Agen Anda: <strong>' . $agent_code . '</strong>. Silakan login.';
                } else {
                    $error = 'Gagal mendaftar: ' . ($res['message'] ?? 'Terjadi kesalahan sistem.');
                }
            }
        } catch (Exception $e) {
            $error = 'Gagal memproses pendaftaran: ' . $e->getMessage();
        }
    }
}

include_once('include_head.php');
?>

<div style="padding-top: 3%;" class="login-box">
  <div class="card">
    <div class="card-header">
      <h3><i class="fa fa-user-plus"></i> Pendaftaran Agen Reseller</h3>
    </div>
    <div class="card-body">
      <div class="text-center pd-5">
        <img src="../img/favicon.png" alt="MIKHMON Logo">
      </div>
      <div class="text-center">
        <span style="font-size: 22px; margin: 10px; font-weight: bold;">Form Registrasi Agen</span>
      </div>
      
      <?php if (!empty($error)): ?>
        <div style="width: 90%; margin: 10px auto; padding: 10px; border-radius: 5px;" class="bg-danger text-white">
          <i class="fa fa-exclamation-triangle"></i> <?= htmlspecialchars($error); ?>
        </div>
      <?php endif; ?>

      <?php if (!empty($success)): ?>
        <div style="width: 90%; margin: 10px auto; padding: 10px; border-radius: 5px; background-color: #28a745; color: white;">
          <i class="fa fa-check-circle"></i> <?= $success; ?>
          <div style="margin-top: 10px;">
            <a href="index.php" class="btn btn-light btn-sm" style="font-weight: bold;">Masuk ke Login Agen</a>
          </div>
        </div>
      <?php endif; ?>

      <?php if (empty($success)): ?>
      <center>
      <form autocomplete="off" action="" method="post">
      <table class="table" style="width:90%">
        <tr>
          <td>
            <label style="font-weight: bold;">Nama Lengkap / Outlet *</label>
            <input style="width: 100%; height: 35px; font-size: 15px;" class="form-control" type="text" name="agent_name" placeholder="Contoh: Toko Berkah Celular" value="<?= htmlspecialchars($_POST['agent_name'] ?? ''); ?>" required="1" autofocus>
          </td>
        </tr>
        <tr>
          <td>
            <label style="font-weight: bold;">Nomor Telepon / WhatsApp *</label>
            <input style="width: 100%; height: 35px; font-size: 15px;" class="form-control" type="text" name="phone" placeholder="Contoh: 081234567890" value="<?= htmlspecialchars($_POST['phone'] ?? ''); ?>" required="1">
          </td>
        </tr>
        <tr>
          <td>
            <label style="font-weight: bold;">Alamat Email (Opsional)</label>
            <input style="width: 100%; height: 35px; font-size: 15px;" class="form-control" type="email" name="email" placeholder="Contoh: agen@gmail.com" value="<?= htmlspecialchars($_POST['email'] ?? ''); ?>">
          </td>
        </tr>
        <tr>
          <td>
            <label style="font-weight: bold;">Password *</label>
            <input style="width: 100%; height: 35px; font-size: 15px;" class="form-control" type="password" name="password" placeholder="Minimal 6 karakter" required="1">
          </td>
        </tr>
        <tr>
          <td>
            <label style="font-weight: bold;">Konfirmasi Password *</label>
            <input style="width: 100%; height: 35px; font-size: 15px;" class="form-control" type="password" name="confirm_password" placeholder="Ulangi password" required="1">
          </td>
        </tr>
        <tr>
          <td class="align-middle text-center">
            <input style="width: 100%; margin-top:15px; height: 38px; font-weight: bold; font-size: 16px;" class="btn-login bg-primary pointer" type="submit" name="register" value="Daftar Agen Sekarang">
          </td>
        </tr>
        <tr>
          <td class="align-middle text-center" style="padding-top: 15px;">
            <span style="font-size: 14px; color: #777;">Sudah punya akun Agen? <a href="index.php" style="color: #3b82f6; text-decoration: none; font-weight: bold;">Login di sini</a></span>
          </td>
        </tr>
      </table>
      </form>
      </center>
      <?php endif; ?>

    </div>
  </div>
</div>
