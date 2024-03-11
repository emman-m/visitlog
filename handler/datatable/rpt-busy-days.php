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
    1 => 'day_of_week',
    2 => 'count'
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

// // NEW
// $new = $db->selectNative("SELECT *, '#3b8bba' as stat FROM visitors_activity va JOIN visitors v ON va.visitor_id = v.id WHERE DATE(va.time_in) >= '".$fromDate."' AND DATE(va.time_in) <= '".$toDate."' AND va.visitor_id IN (SELECT id FROM visitors WHERE created_at >= '".$fromDate."%') GROUP BY v.id");
// // OLD
// $old = $db->selectNative("SELECT *, '#d2d6de' as stat FROM visitors_activity va JOIN visitors v ON va.visitor_id = v.id WHERE DATE(va.time_in) >= '".$fromDate."' AND DATE(va.time_in) <= '".$toDate."' AND va.visitor_id IN (SELECT id FROM visitors WHERE created_at < '".$fromDate."%' AND updated_at >= '".$fromDate."%')  GROUP BY v.id");

// NEW
$new = $db->selectNative("SELECT DAYNAME(va.time_in) AS day_of_week, COUNT(DAYNAME(va.time_in)) as count, v.id AS visitor_id, v.name, v.email, v.gender, v.address, v.valid_id, va.time_in FROM visitors_activity va JOIN visitors v ON va.visitor_id = v.id WHERE va.time_in >= '".$fromDate."' AND va.time_in <= '".$toDate."' AND department != '".$dept::Guard."' GROUP BY day_of_week ORDER BY FIELD(day_of_week, 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');");

$data = $new;
$return_data = array();

$dayLabel = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
$c = 0;
foreach ($dayLabel as $dayRow) {
    foreach ($data as $row) {
        
        if ($dayRow == $row['day_of_week']) {
            $return_data[] = array(
                $row['day_of_week'],
                $row['count']
            );
            unset($data[$c++]);
            break 1;
        } else {
            $return_data[] = array(
                $dayRow,
                0
            );
            break 1;
        }
        $c++;
    }

}

// foreach ($data as $row) {

//     $return_data[] = array(
//         $row['day_of_week'],
//         $row['count']
//     );
// }
$total = intval(count($new));
$total_filtered = intval(0);
$result = array(
    "draw" => $draw,
    "recordsTotal" => $total,
    "recordsFiltered" => empty($search) ? $total : $total_filtered,
    "data" => $return_data,
);
echo json_encode($result);