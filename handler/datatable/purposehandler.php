<?php
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
    $dir = "asc";
}

$columns = array(
    0 => 'id',
    1 => 'department',
    2 => 'name'
);
if(!isset($columns[$col])){
    $order = $columns[0];
} else {
    $order = $columns[$col+1];
}

$condition = [];
if (intVal($_POST['dept']) !== 0) {
    $condition = array(
        'department' => $_POST['dept']
    );
}


$data = $db->dt_select("purpose", $order, $dir, $search, $limit, $offset, $columns, $condition);
$return_data = array();
foreach ($data as $row) {

    $action = '<a href="javascript:void(0)" data-id="'. $row["id"].'" class="btn btn-xs btn-danger btn-delete">Delete</a>';

    $return_data[] = array(
        $dept->get_name($row['department']),
        $row['name'],
        $action
    );
}
$total = count($db->selectWhere('purpose', $condition));
$total_filtered = count($data);
$result = array(
    "draw" => $draw,
    "recordsTotal" => $total,
    "recordsFiltered" => empty($search) ? $total : $total_filtered,
    "data" => $return_data,
);
echo json_encode($result);