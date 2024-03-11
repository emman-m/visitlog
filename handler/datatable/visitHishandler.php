<?php
session_start();
require '../../class/DatabaseManager.php';
require '../../enum/Department.php';
$db = new DatabaseManager();
$dept = new Department();

$draw = intval($_POST['draw']);
$offset = intval($_POST['start']);
$limit = intval($_POST['length']);
$order = $_POST['order'] ?? null;
$search = $_POST['search'];
$search = $search['value'];
$col = 0;
$dir = "";

if (!empty($order)) {
    foreach ($order as $o) {
        $col = $o['column'];
        $dir = $o['dir'];
    }
}

if ($dir != "asc" && $dir != "desc") {
    $dir = "desc";
}

$columns = array(
    0 => 'va.id',
    1 => 'v.name',
    2 => 'v.address',
    3 => 'va.purpose',
    4 => 'va.time_in',
    4 => 'va.time_out',
);
if (!isset($columns[$col])) {
    $order = $columns[0];
} else {
    $order = $columns[$col + 1];
}

$data = $db->dt_select_join_va($_SESSION['dept'], $order, $dir, $search, $limit, $offset, $columns);
$return_data = array();
foreach ($data as $row) {
    $dateIn = new DateTime($row['time_in']);

    $timeOut = "";
    if ($row['time_out'] !== null) {
        $dateOut = new DateTime($row['time_out']);
        $timeOut = $dateOut->format("D F n Y, h:i A");
    }

    $timeIn = $dateIn->format("D F n Y, h:i A");

    if ((intVal($_SESSION['dept']) !== 0) && (intVal($_SESSION['dept']) !== 5)) {
        $deptstr = $row['purpose'];
    } else {
        $deptstr = '[' . $dept->get_name($row['department']) . ']' . $row['purpose'];
    }


    $return_data[] = array(
        $row['name'],
        $row['address'],
        $deptstr,
        $timeIn,
        $timeOut,
    );
}
$total = intval($db->selectCount('visitors_activity', array('department' => $_SESSION['dept'])));
$total_filtered = intval($db->dt_select_join_va($_SESSION['dept'], $order, $dir, $search, $limit, $offset, $columns, false));
$result = array(
    "draw" => $draw,
    "recordsTotal" => $total,
    "recordsFiltered" => empty($search) ? $total : $total_filtered,
    "data" => $return_data,
);
echo json_encode($result);
