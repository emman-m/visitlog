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
    0 => 'id',
    1 => 'name',
);
if (!isset($columns[$col])) {
    $order = $columns[0];
} else {
    $order = $columns[$col + 1];
}

$condition = array(
    'updated_at' => '1'
);
$dateNow = date('Y-m-d');

if ($_SESSION['role'] == $user_role::SecurityHead || $_SESSION['dept'] == $dept::Guard) {
    $data = $db->selectNative("SELECT * FROM visitors WHERE updated_at LIKE '$dateNow%'");
} else {
    $data = $db->selectNative("SELECT * FROM visitors WHERE purpose LIKE '%\"" . $_SESSION['dept'] . "\"%' AND updated_at LIKE '$dateNow%'");
}

$return_data = array();
foreach ($data as $row) {
    $purpose = [];
    $row2 = [];
    foreach (json_decode($row['purpose'], true) as $key => $value) {
        if (($_SESSION['role'] == $user_role::SecurityHead || $_SESSION['dept'] == $dept::Guard) || (intVal($key) == intVal($_SESSION['dept']))) {
            $activity = $db->selectNative("SELECT * FROM visitors_activity WHERE visitor_id = '" . $row['id'] . "' AND department = '" . $key . "' AND time_in LIKE '$dateNow%'");
            if ($activity) {
                $purpose[] = '<span class="badge badge-pill badge-success"><i class="fas fa-check"></i> ' . $dept->get_name($key) . '</span>';
            } else {
                $purpose[] = '<span class="badge badge-pill badge-warning"> ' . $dept->get_name($key) . '</span>';
            }
        }
    }
    $return_data[] = array(
        $row['name'],
        implode(' ', $purpose),
        $row['id']
    );
}
$total = intval($db->selectCount('visitors'));
$total_filtered = count($data);
$result = array(
    "draw" => $draw,
    "recordsTotal" => $total,
    "recordsFiltered" => empty($search) ? $total : $total_filtered,
    "data" => $return_data,
);
echo json_encode($result);
