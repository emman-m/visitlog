<?php
session_start();
date_default_timezone_set('Asia/Manila');
require 'enum/UserRole.php';
require 'enum/Department.php';
require 'class/Purpose.php';
$user_role = new UserRole();
$dept = new Department();
$purClass = new Purpose();

$baseUrl = strtolower(explode('/', $_SERVER['SERVER_PROTOCOL'])[0]).'://'.$_SERVER['SERVER_NAME'].'/visitlog/';
$url = explode('/', $_SERVER['PHP_SELF']);
switch ($url[2]) {
    case 'login.php':
        if (isset($_SESSION['uid'])) {
            header('location:'.$baseUrl);
        }
        break;
    default:
        if (!isset($_SESSION['uid'])) {
            header('location:'.$baseUrl.'login.php');
        }
        break;
}

// if ($url[2] == 'login.php') {
//     if (isset($_SESSION['uid'])) {
//         header('location:'.$baseUrl);
//         echo $url[2];
//     }
// } else {
//     if (!isset($_SESSION['uid'])) {
//         header('location:'.$baseUrl.'login.php');
//     }
// }

?>
<script>
    var notif = <?php echo isset($_SESSION['notif_count']) ? $_SESSION['notif_count'] : 0?>;
</script>