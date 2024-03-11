<?php
date_default_timezone_set('Asia/Manila');
session_start();
require '../../class/DatabaseManager.php';
require '../../enum/UserRole.php';
require '../../enum/Department.php';
$db = new DatabaseManager();
$user_role = new UserRole();
$dept = new Department();

$draw = intval($_POST['draw']);
$offset = intval($_POST['start']);
$limit = intval($_POST['length']);
$order = $_POST['order'] ?? null;
$search = $_POST['search'];
$search = $search['value'];
$col = 0;
$dir = "";

if(!empty($order)){
    foreach($order as $o){
        $col = $o['column'];
        $dir = $o['dir'];
    }
}

if($dir != "asc" && $dir != "desc"){
    $dir = "desc";
}

$columns = array(
    0 => 'id',
    1 => 'name',
    2 => 'address'
);
if(!isset($columns[$col])){
    $order = $columns[0];
} else {
    $order = $columns[$col+1];
}

if (!isset($_POST['from']) && !isset($_POST['to'])) {
    $fromDate = date('Y-m-01');
    $toDate  = date('Y-m-t');
} else {
    $fromDate = $_POST['from'];
    $toDate  = $_POST['to'];
}

// $data = $db->dt_select("user_account", $order, $dir, $search, $limit, $offset, $columns);
// NEW
$new = $db->selectNative("SELECT *, '#00a65a' as stat FROM visitors WHERE created_at >= '".$fromDate."%' AND created_at <= '".$toDate."%'");
// OLD
$old = $db->selectNative("SELECT *, '#d2d6de' as stat FROM visitors WHERE created_at < '".$fromDate."%' AND updated_at >= '".$fromDate."%' AND updated_at <= '".$toDate."%'");

$data = array_merge($new, $old);
$return_data = array();
foreach ($data as $row) {
    
    $legend = '<div style="width:100%;height:24px;background-color:'.$row['stat'].'"> </div>';

    $return_data[] = array(
        $row['name'],
        $row['address'],
        $legend
    );
}
$total = intval(count($new) + count($old));
$total_filtered = intval(0);
$result = array(
    "draw" => $draw,
    "recordsTotal" => $total,
    "recordsFiltered" => empty($search) ? $total : $total_filtered,
    "data" => $return_data,
);
echo json_encode($result);