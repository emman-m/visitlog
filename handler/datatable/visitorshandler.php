<?php
session_start();
require '../../class/DatabaseManager.php';
$db = new DatabaseManager();

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
    2 => 'address',
    3 => 'updated_at',
);
if(!isset($columns[$col])){
    $order = $columns[0];
} else {
    $order = $columns[$col + 1];
}

$data = $db->dt_select("visitors", $order, $dir, $search, $limit, $offset, $columns);
$return_data = array();
foreach ($data as $row) {
    $date = new DateTime($row['updated_at']);
    $updatedAt = $date->format("M d Y D, h:i A");

    $action = "<a href='editvisitor.php?id=".$row['id']."' class='btn btn-xs btn-success mr-2'>Update</a>";
    $action .= "<a href='javascript:void()' class='btn btn-xs btn-danger btn-delete' data-id='".$row['id']."'>Delete</a>";

    $return_data[] = array(
        $row['name'],
        $row['address'],
        $updatedAt,
        $action
    );
}
$total = intval($db->selectCount('visitors'));
$total_filtered = intval($db->dt_select("visitors", $order, $dir, $search, $limit, $offset, $columns, false));
$result = array(
    "draw" => $draw,
    "recordsTotal" => $total,
    "recordsFiltered" => empty($search) ? $total : $total_filtered,
    "data" => $return_data,
);
echo json_encode($result);