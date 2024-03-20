<?php
if (!isset($_GET['id']) || $_GET['id'] == null) {
    die();
}

include "config/session.php";
require 'class/DatabaseManager.php';
$db = new DatabaseManager();
$activeLink = 'dashboard';


$data[0] = $db->selectWhere("notif", array('id' => $_GET['id']));
$notif = $data[0][0];

if ($_SESSION['role'] == $user_role::SecurityHead || $_SESSION['dept'] == $dept::Guard) {
    $data[1] = $db->selectWhere("visitors_activity", array('id' => $notif['activity_id']));
    if ($data[1]) {
        $activity = $data[1][0];
        $rfid = true;
    } else {
        $data[1] = [];
        $activity = [];
        $rfid = false;
    }
} else {
    $data[1] = [];
    $activity = [];
    $rfid = false;
}

$data[2] = $db->selectWhere("visitors", array('id' => $notif['visitor_id']));
$visitor = $data[2][0];
// Update notif to read
if ($notif['is_read'] == 0) {
    $db->update("notif", array('is_read' => 1), array('id' => $_GET['id']));
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VisitLog | Details</title>
    <link rel="icon" type="image/png" href="assets/img/logo.png" />

    <?php include "component/global-css.php"; ?>
    <link rel="stylesheet" href="assets/css/details.css">
</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <?php include_once "component/nav.php"; ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include_once "component/sidebar.php"; ?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="height:auto">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Activity Details</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                <li class="breadcrumb-item active">Activity</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-body">
                                    <div class="row">

                                        <div class="col-12 col-lg-5">

                                            <div class="row mt-2">
                                                <div class="col-12 text-center">
                                                    <img src="assets/img/visitor/<?php echo $visitor['image'] ?>" alt="" class="img img-h-250">
                                                </div>
                                            </div>

                                            <div class="row mt-4">
                                                <div class="col-12 d-flex">
                                                    <div class="label">
                                                        Name:
                                                    </div>
                                                    <div class="data-content mlc-3">
                                                        <div class="data-input"><?php echo $visitor['name']; ?></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-12 d-flex">
                                                    <div class="label">
                                                        Address:
                                                    </div>
                                                    <div class="data-content mlc-3">
                                                        <div class="data-input"><?php echo $visitor['address']; ?></div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row mt-2">
                                                <div class="col-12 d-flex">
                                                    <div class="label">
                                                        Purpose:
                                                    </div>
                                                    <div class="data-content mlc-3">
                                                        <div class="data-area">
                                                            <?php
                                                            echo '<ol><li>' . implode('</li><li>', json_decode($activity['purpose'], true)) . '</li></ol>';
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                        <?php if (!empty($activity)) { ?>


                                            <div class="col-12 col-lg-7">
                                                <div class="row mt-2">
                                                    <div class="col-12 text-center">
                                                        <img src="assets/img/map/<?php echo $dept->get_name($activity['department']); ?>.png" alt="" class="img img-h-250">
                                                    </div>
                                                </div>

                                                <div class="row mt-4">
                                                    <div class="col-12 d-flex">
                                                        <div class="label">
                                                            Department:
                                                        </div>
                                                        <div class="data-content mlc-3">
                                                            <div class="data-input"><?php echo $dept->get_name($activity['department']); ?></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-12 d-flex">
                                                        <div class="label">
                                                            Time In:
                                                        </div>
                                                        <div class="data-content mlc-3">
                                                            <div class="data-input"><?php echo Date('l M d, Y H:i A', strtotime($activity['time_in'])); ?></div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row mt-2">
                                                    <div class="col-12 d-flex">
                                                        <div class="label">
                                                            Time Out:
                                                        </div>
                                                        <div class="data-content mlc-3">
                                                            <div class="data-input"><?php echo $activity['time_out'] == null ? '--:--' : Date('l M d, Y H:i A', strtotime($activity['time_out'])); ?></div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        <?php } ?>
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                </div>


            </section>
            <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <footer class="main-footer">
            <div class="float-right d-none d-sm-block">
                <b>Version</b> 1.0
            </div>
            <strong>Capstone &copy; 2024 </strong> All rights
            reserved.
        </footer>
    </div>
    <!-- ./wrapper -->

    <?php include "component/global-js.php"; ?>
    <!-- Custom -->
</body>

</html>