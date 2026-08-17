<?php 
if(substr($_SERVER["REQUEST_URI"], -10) == "config.php"){header("Location:./");}; 
$data['mikhmon'] = array ('1'=>'mikhmon<|<alijaya','mikhmon>|>aGdiaWJj');

$data['ALIJAYA-NET'] = array ('1'=>'ALIJAYA-NET!192.168.8.1:8700','ALIJAYA-NET@|@alijaya','ALIJAYA-NET#|#aGdiaWJj','ALIJAYA-NET%ALIJAYA-NET','ALIJAYA-NET^alijayanet.login','ALIJAYA-NET&Rp','ALIJAYA-NET*10','ALIJAYA-NET(1','ALIJAYA-NET)','ALIJAYA-NET=10','ALIJAYA-NET@!@enable');



$data['new-2364'] = array ('1'=>'new-2364!','new-2364@|@','new-2364#|#','new-2364%','new-2364^','new-2364&Rp','new-2364*10','new-2364(1','new-2364)','new-2364=10','new-2364@!@disable');
$data['new-1185'] = array ('1'=>'new-1185!','new-1185@|@','new-1185#|#','new-1185%','new-1185^','new-1185&Rp','new-1185*10','new-1185(1','new-1185)','new-1185=10','new-1185@!@disable');
$data['new-7927'] = array ('1'=>'new-7927!','new-7927@|@','new-7927#|#','new-7927%','new-7927^','new-7927&Rp','new-7927*10','new-7927(1','new-7927)','new-7927=10','new-7927@!@disable');
$data['new-3240'] = array ('1'=>'new-3240!','new-3240@|@','new-3240#|#','new-3240%','new-3240^','new-3240&Rp','new-3240*10','new-3240(1','new-3240)','new-3240=10','new-3240@!@disable');
$data['new-9721'] = array ('1'=>'new-9721!','new-9721@|@','new-9721#|#','new-9721%','new-9721^','new-9721&Rp','new-9721*10','new-9721(1','new-9721)','new-9721=10','new-9721@!@disable');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['owner_id']) && $_SESSION['owner_id'] > 0) {
    $temp_mikhmon = $data['mikhmon'] ?? null;
    $data = [];
    if ($temp_mikhmon) {
        $data['mikhmon'] = $temp_mikhmon;
    }
    
    try {
        if (!function_exists('getDBConnection')) {
            require_once(__DIR__ . '/db_config.php');
        }
        $db = getDBConnection();
        if ($db) {
            $stmt = $db->prepare("SELECT * FROM router_sessions WHERE owner_id = ?");
            $stmt->execute([$_SESSION['owner_id']]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as $row) {
                $session_name = $row['session_name'];
                $data[$session_name] = [
                    '1' => $session_name . '!' . $row['ip_address'],
                    '2' => $session_name . '@|@' . $row['username'],
                    '3' => $session_name . '#|#' . $row['password'],
                    '4' => $session_name . '%' . $row['hotspot_name'],
                    '5' => $session_name . '^' . $row['dns_name'],
                    '6' => $session_name . '&' . $row['currency'],
                    '7' => $session_name . '*' . $row['auto_reload'],
                    '8' => $session_name . '(' . $row['interface'],
                    '9' => $session_name . ')' . $row['info_limit'],
                    '10' => $session_name . '=' . $row['idle_timeout'],
                    '11' => $session_name . '@!@' . $row['live_report']
                ];
            }
        }
    } catch (Exception $e) {
        // Silent catch
    }
}