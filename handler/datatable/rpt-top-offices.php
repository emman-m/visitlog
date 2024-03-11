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

/* for new visitors data
SELECT department, COUNT(DISTINCT visitor_id) AS department_count FROM visitors_activity va JOIN visitors v ON va.visitor_id = v.id WHERE DATE(va.time_in) >= '".$fromDate."' AND DATE(va.time_in) <= '".$toDate."' AND va.visitor_id IN (SELECT id FROM visitors WHERE created_at >= '".$fromDate."%') GROUP BY department;
*/
/* for old visitors data
SELECT department, COUNT(DISTINCT visitor_id) AS department_count FROM visitors_activity va JOIN visitors v ON va.visitor_id = v.id WHERE DATE(va.time_in) >= '".$fromDate."' AND DATE(va.time_in) <= '".$toDate."' AND va.visitor_id IN (SELECT id FROM visitors WHERE created_at < '".$fromDate."%' AND updated_at >= '".$fromDate."%') GROUP BY department;
*/
// NEW
$new = $db->selectNative("SELECT v.name, v.address, va.purpose, va.department, '#3b8bba' as stat FROM visitors_activity va JOIN visitors v ON va.visitor_id = v.id WHERE DATE(va.time_in) >= '".$fromDate."' AND DATE(va.time_in) <= '".$toDate."' AND va.visitor_id IN (SELECT id FROM visitors WHERE created_at >= '".$fromDate."%') AND department != '".$dept::Guard."' GROUP BY v.id");
// OLD
$old = $db->selectNative("SELECT v.name, v.address, va.purpose, va.department, '#d2d6de' as stat FROM visitors_activity va JOIN visitors v ON va.visitor_id = v.id WHERE DATE(va.time_in) >= '".$fromDate."' AND DATE(va.time_in) <= '".$toDate."' AND va.visitor_id IN (SELECT id FROM visitors WHERE created_at < '".$fromDate."%' AND updated_at >= '".$fromDate."%') AND department != '".$dept::Guard."' GROUP BY v.id");

$data = array_merge($new, $old);
$return_data = array();
foreach ($data as $row) {
    
    $legend = '<div style="width:100%;height:24px;background-color:'.$row['stat'].'"> </div>';

    $return_data[] = array(
        $row['name'],
        $row['address'],
        $dept->get_name($row['department']),
        '<div class="ellipsis" title="' . $row['purpose'] . '">' . $row['purpose'] . '</div>',
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