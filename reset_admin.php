<?php
/**
 * MikhMon Admin Reset Tool
 * ------------------------------------------------------
 * Taruh file ini di root folder VPS Anda, lalu akses:
 * https://samudraindah.net/reset_admin.php
 * Setelah berhasil login, segera HAPUS file ini demi keamanan.
 */

$configFile = __DIR__ . '/include/config.php';
if (!file_exists($configFile)) {
    die("Error: File include/config.php tidak ditemukan di server VPS!");
}

$content = file_get_contents($configFile);

// Mencari baris konfigurasi admin mikhmon
$pattern = '/\$data\[\'mikhmon\'\]\s*=\s*array\s*\(.*?\);/s';
$replacement = "\$data['mikhmon'] = array ('1'=>'mikhmon<|<alijaya','mikhmon>|>aGdiaWJj');";

if (preg_match($pattern, $content)) {
    $newContent = preg_replace($pattern, $replacement, $content);
    if (file_put_contents($configFile, $newContent) !== false) {
        echo "<h3>✅ Reset Admin Berhasil!</h3>";
        echo "Kredensial login Anda telah direset menjadi:<br>";
        echo "Username: <b>alijaya</b><br>";
        echo "Password: <b>060111</b><br><br>";
        echo "<i>Silakan langsung coba login, lalu segera HAPUS file <b>reset_admin.php</b> ini dari VPS Anda.</i>";
    } else {
        echo "❌ Gagal menulis pembaruan ke file include/config.php. Periksa izin file (write permission) di VPS.";
    }
} else {
    // Jika format regex tidak cocok, kita append atau infokan cara manual
    echo "❌ Format data['mikhmon'] di include/config.php tidak dikenali.<br>";
    echo "Silakan edit manual file <b>include/config.php</b> dan ubah baris \$data['mikhmon'] menjadi:<br>";
    echo "<code>\$data['mikhmon'] = array ('1'=>'mikhmon<|<alijaya','mikhmon>|>aGdiaWJj');</code>";
}
