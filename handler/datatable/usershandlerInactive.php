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
    $dir = "desc";
}

$columns = array(
    0 => 'id',
    1 => 'firstname',
    2 => 'email',
    3 => 'username',
    4 => 'role',
    5 => 'department',
    6 => 'lastname'
);
if(!isset($columns[$col])){
    $order = $columns[0];
} else {
    $order = $columns[$col+1];
}

$condition = array(
    'active' => '0'
);

$data = $db->dt_select("user_account", $order, $dir, $search, $limit, $offset, $columns, $condition);
$return_data = array();
foreach ($data as $row) {
    $role = $user_role->get_name($row['role']);
    $deptartment = $dept->get_name($row['department']);

    $activeState = $row['active'] == 1 ? 'checked' : '';

    $active = '
        <div class="form-group" style="margin-bottom:0;width:20px">
            <div class="custom-control custom-switch" style="">
                <input type="checkbox" data-id="'.$row['id'].'" class="custom-control-input active-inactive" id="active'.$row['id'].'" '.$activeState.'>
                <label class="custom-control-label" for="active'.$row['id'].'"></label>
            </div>
        </div>
    ';

    $action = "<a href='edituser.php?id=".$row['id']."' class='btn btn-xs btn-success mr-2'>Update</a>";
    // $action .= "<a href='javascript:void()' class='btn btn-xs btn-danger btn-delete' data-id='".$row['id']."'>Delete</a>";

    $return_data[] = array(
        $active,
        $row['firstname']." ".$row['lastname'],
        $row['email'],
        $row['username'],
        $role,
        $deptartment,
        $action
    );
}
$total = intval($db->selectCount('user_account'));
$total_filtered = intval($db->dt_select("user_account", $order, $dir, $search, $limit, $offset, $columns, false));
$result = array(
    "draw" => $draw,
    "recordsTotal" => $total,
    "recordsFiltered" => empty($search) ? $total : $total_filtered,
    "data" => $return_data,
);
echo json_encode($result);