<?php
/*
 *  Copyright (C) 2018 Laksamadi Guko.
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
session_start();
// hide all error
error_reporting(0);

// Bootstrap Laravel to enable SSO (only if not already running inside Laravel)
if (!function_exists('app')) {
    try {
        if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
            require_once __DIR__ . '/../vendor/autoload.php';
            $app = require_once __DIR__ . '/../bootstrap/app.php';
            $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
            $ssoRequest = Illuminate\Http\Request::create('/owner', 'GET', [], $_COOKIE, [], $_SERVER);
            $kernel->handle($ssoRequest);
            
            // Restore error and exception handlers to avoid strict Laravel error handling in legacy script
            restore_error_handler();
            restore_exception_handler();
        }
    } catch (\Exception $e) {
        // Ignore bootstrapping errors
    }
}

// Automatically log in from Laravel session if authenticated
if (!isset($_SESSION["mikhmon"]) && function_exists('auth') && auth('owners')->check()) {
    $owner = auth('owners')->user();
    $_SESSION["mikhmon"] = $owner->username;
    $_SESSION["owner_id"] = $owner->id;
    $_SESSION["owner_level"] = $owner->level;
    $_SESSION["timezone"] = $owner->timezone ?? 'Asia/Jakarta';
}

// Check subscription expiration for active session on every page load
if (isset($_SESSION["owner_id"]) && $_SESSION["owner_id"] > 0) {
    try {
        if (!function_exists('getDBConnection')) {
            require_once(__DIR__ . '/include/db_config.php');
        }
        $db = getDBConnection();
        $stmt = $db->prepare("SELECT subscription_expires_at, status FROM owners WHERE id = :id");
        $stmt->execute([':id' => $_SESSION["owner_id"]]);
        $ownerCheck = $stmt->fetch();
        
        if ($ownerCheck) {
            $isExpired = false;
            if ($ownerCheck['subscription_expires_at'] && strtotime($ownerCheck['subscription_expires_at']) < time()) {
                $isExpired = true;
            }
            
            if ($isExpired || $ownerCheck['status'] !== 'active') {
                if ($_GET['id'] !== 'logout') {
                    // Redirect to standalone billing page (keep session active for checkout authentication)
                    echo "<script>alert('Masa aktif langganan Anda telah berakhir. Silakan lakukan pembayaran.'); window.location='/owner/subscription';</script>";
                    exit;
                }
            }
        }
    } catch (\Exception $e) {
        // Ignore DB connection errors to avoid breaking the script
    }
}

ob_start("ob_gzhandler");

// check url
$url = $_SERVER['REQUEST_URI'];

// load session MikroTik
$session = $_GET['session'];
$id = $_GET['id'];
$c = $_GET['c'];
$router = $_GET['router'];
$logo = $_GET['logo'];

$ids = array(
  "editor",
  "uplogo",
  "settings",
);

// lang
include('./lang/isocodelang.php');
include('./include/lang.php');
include('./lang/'.$langid.'.php');

// quick bt
include('./include/quickbt.php');

// theme
include('./include/theme.php');
include('./settings/settheme.php');
include('./settings/setlang.php');
if ($_SESSION['theme'] == "") {
    $theme = $theme;
    $themecolor = $themecolor;
  } else {
    $theme = $_SESSION['theme'];
    $themecolor = $_SESSION['themecolor'];
}


// load config
include_once('./include/headhtml.php');
include('./include/config.php');
include('./include/readcfg.php');

include_once('./lib/routeros_api.class.php');
include_once('./lib/formatbytesbites.php');
?>
    
<?php
if ($id == "login" || substr($url, -1) == "p") {

  if (isset($_POST['login'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];
    if ($user == $useradm && $pass == mikhmon_decrypt($passadm)) {
      $_SESSION["mikhmon"] = $user;
      $_SESSION["owner_id"] = 0; // Master Admin
      echo "<script>window.location='./admin.php?id=sessions'</script>";
    } else {
      // Check in owners database table
      try {
          if (!function_exists('getDBConnection')) {
              require_once(__DIR__ . '/include/db_config.php');
          }
          $db = getDBConnection();
          $stmt = $db->prepare("SELECT * FROM owners WHERE username = :username OR email = :email OR phone = :phone");
          $stmt->execute([':username' => $user, ':email' => $user, ':phone' => $user]);
          $owner = $stmt->fetch();
          if ($owner && password_verify($pass, $owner['password'])) {
              // Establish session first so the billing controller can authenticate them
              $_SESSION["mikhmon"] = $owner['username'];
              $_SESSION["owner_id"] = $owner['id'];
              $_SESSION["owner_level"] = $owner['level'];

              $isExpired = false;
              if ($owner['subscription_expires_at'] && strtotime($owner['subscription_expires_at']) < time()) {
                  $isExpired = true;
              }

              if ($owner['status'] !== 'active' || $isExpired) {
                  echo "<script>alert('Akun Anda belum aktif atau masa aktif langganan telah berakhir. Silakan lakukan pembayaran.'); window.location='/owner/subscription';</script>";
                  exit;
              } else {
                  echo "<script>window.location='./admin.php?id=sessions'</script>";
              }
          } else {
              $error = '<div style="width: 100%; padding:5px 0px 5px 0px; border-radius:5px;" class="bg-danger"><i class="fa fa-ban"></i> Alert!<br>Invalid username or password.</div>';
          }
      } catch (Exception $e) {
          $error = '<div style="width: 100%; padding:5px 0px 5px 0px; border-radius:5px;" class="bg-danger"><i class="fa fa-ban"></i> Alert!<br>Error connecting to database.</div>';
      }
    }
  }
  

  include_once('./include/login.php');
} elseif (!isset($_SESSION["mikhmon"])) {
  echo "<script>window.location='./admin.php?id=login'</script>";
} elseif (substr($url, -1) == "/" || substr($url, -4) == ".php") {
  echo "<script>window.location='./admin.php?id=sessions'</script>";

} elseif ($id == "sessions") {
  $_SESSION["connect"] = "";
  include_once('./include/menu.php');
  include_once('./settings/sessions.php');
  /*echo '
  <script type="text/javascript">
    document.getElementById("sessname").onkeypress = function(e) {
    var chr = String.fromCharCode(e.which);
    if (" _!@#$%^&*()+=;|?,~".indexOf(chr) >= 0)
        return false;
    };
    </script>';*/
} elseif ($id == "settings" && !empty($session) || $id == "settings" && !empty($router)) {
  include_once('./include/menu.php');
  include_once('./settings/settings.php');
  echo '
  <script type="text/javascript">
    document.getElementById("sessname").onkeypress = function(e) {
    var chr = String.fromCharCode(e.which);
    if (" _!@#$%^&*()+=;|?,~".indexOf(chr) >= 0)
        return false;
    };
    </script>';
} elseif ($id == "connect"  && !empty($session)) {
  ini_set("max_execution_time",5);  
  include_once('./include/menu.php');
  $API = new RouterosAPI();
  $API->debug = false;
  if ($API->connect($iphost, $userhost, mikhmon_decrypt($passwdhost))){
    $_SESSION["connect"] = "<b class='text-green'>Connected</b>";
    echo "<script>window.location='./?session=" . $session . "'</script>";
  } else {
    $_SESSION["connect"] = "<b class='text-red'>Not Connected</b>";
    $nl = '\n';
    if ($currency == in_array($currency, $cekindo['indo'])) {
      echo "<script>alert('Mikhmon not connected!".$nl."Silakan periksa kembali IP, User, Password dan port API harus enable.".$nl."Jika menggunakan koneksi VPN, pastikan VPN tersebut terkoneksi.')</script>";
    }else{
      echo "<script>alert('Mikhmon not connected!".$nl."Please check the IP, User, Password and port API must be enabled.')</script>";
    }
    if($c == "settings"){
      echo "<script>window.location='./admin.php?id=settings&session=" . $session . "'</script>";
    }else{
      echo "<script>window.location='./admin.php?id=sessions'</script>";
    }
  }
} elseif ($id == "uplogo"  && !empty($session)) {
  include_once('./include/menu.php');
  include_once('./settings/uplogo.php');
} elseif ($id == "reboot"  && !empty($session)) {
  include_once('./process/reboot.php');
} elseif ($id == "shutdown"  && !empty($session)) {
  include_once('./process/shutdown.php');
} elseif ($id == "remove-session" && $session != "") {
  include_once('./include/menu.php');
  if (isset($_SESSION['owner_id']) && $_SESSION['owner_id'] > 0) {
    try {
        if (!function_exists('getDBConnection')) {
            require_once(__DIR__ . '/include/db_config.php');
        }
        $db = getDBConnection();
        $stmt = $db->prepare("DELETE FROM router_sessions WHERE owner_id = ? AND session_name = ?");
        $stmt->execute([$_SESSION['owner_id'], $session]);
    } catch (Exception $e) {}
  } else {
    $fc = file("./include/config.php" );
    $f = fopen("./include/config.php", "w");
    $q = "'";
    $rem = '$data['.$q.$session.$q.']';
    foreach ($fc as $line) {
      if (!strstr($line, $rem))
        fputs($f, $line);
    }
    fclose($f);
  }
  echo "<script>window.location='./admin.php?id=sessions'</script>";
} elseif ($id == "about") {
  include_once('./include/menu.php');
  include_once('./include/about.php');
} elseif ($id == "logout") {
  include_once('./include/menu.php');
  echo "<b class='cl-w'><i class='fa fa-circle-o-notch fa-spin' style='font-size:24px'></i> Logout...</b>";
  session_destroy();
  echo "<script>window.location='./admin.php?id=login'</script>";
} elseif ($id == "remove-logo" && $logo != ""  && !empty($session)) {
  include_once('./include/menu.php');
  $logoFile = basename($logo);
  $ext = strtolower(pathinfo($logoFile, PATHINFO_EXTENSION));
  $allowedExts = ['png', 'jpg', 'jpeg', 'gif', 'webp', 'svg', 'ico'];
  if ($logoFile !== $logo || $logoFile === '' || !in_array($ext, $allowedExts, true)) {
    echo "<script>window.location='./admin.php?id=uplogo&session=" . $session . "'</script>";
    exit;
  }

  $logoDir = realpath(__DIR__ . DIRECTORY_SEPARATOR . 'img');
  $target = realpath(__DIR__ . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $logoFile);
  if ($logoDir === false || $target === false || strpos($target, $logoDir) !== 0) {
    echo "<script>window.location='./admin.php?id=uplogo&session=" . $session . "'</script>";
    exit;
  }

  @unlink($target);
  echo "<script>window.location='./admin.php?id=uplogo&session=" . $session . "'</script>";
} elseif ($id == "editor"  && !empty($session)) {
  include_once('./include/menu.php');
  include_once('./settings/vouchereditor.php');
} elseif (empty($id)) {
  echo "<script>window.location='./admin.php?id=sessions'</script>";
} elseif(in_array($id, $ids) && empty($session)){
	echo "<script>window.location='./admin.php?id=sessions'</script>";
}
?>
<script src="js/mikhmon-ui.<?= $theme; ?>.min.js"></script>
<script src="js/mikhmon.js?t=<?= str_replace(" ","_",date("Y-m-d H:i:s")); ?>"></script>
<?php include('./include/info.php'); ?>
</body>
</html>
