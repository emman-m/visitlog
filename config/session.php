<?php
session_start();
date_default_timezone_set('Asia/Manila');
require 'enum/UserRole.php';
require 'enum/Department.php';
require 'class/DatabaseManager.php';
require_once 'class/Functions.php';
$user_role = new UserRole();
$dept = new Department();
$db = new DatabaseManager();
$fn = new Functions();

if (isset($_SESSION['uid']) && (!$db->checkUserActive($_SESSION['uid']))) {
    session_destroy();
    header('location:login.php');
}

$baseUrl = strtolower(explode('/', $_SERVER['SERVER_PROTOCOL'])[0]) . '://' . $_SERVER['SERVER_NAME'] . '/visitlog/';
$url = explode('/', $_SERVER['PHP_SELF']);
switch ($url[2]) {
    case 'login.php':
        if (isset($_SESSION['uid'])) {
            header('location:' . $baseUrl);
        }
        break;
    default:
        if (!isset($_SESSION['uid'])) {
            header('location:' . $baseUrl . 'login.php');
        }
        break;
}


?>
<script>
    var notif = <?php echo isset($_SESSION['notif_count']) ? $_SESSION['notif_count'] : 0 ?>;
</script>