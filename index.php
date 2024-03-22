<?php include "config/session.php";
$activeLink = 'dashboard';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VisitLog | Dashboard</title>
    <link rel="icon" type="image/png" href="assets/img/logo.png" />
    <?php include "component/global-css.php"; ?>
    <!-- DataTables -->
    <!-- <link rel="stylesheet" href="template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css"> -->
    <link rel="stylesheet" href="assets/lib/datatable/css/dataTables.dataTables.min.css">
    <!-- custom css -->
    <link rel="stylesheet" href="assets/css/index.css">
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
                            <h1>Monthly Report for <?php echo date('F') ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item active">Dashboard</li>
                            </ol>
                        </div>
                    </div>
                </div><!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="err_msg"></div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">List of Appointment</h3>
                                </div>
                                <div class="card-body visitor-list">
                                    <table id="visitorTable" class="display" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Department</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Available Department</h3>
                                </div>
                                <div class="card-body">

                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                    <!-- Old and new report, Top offices report -->
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Old and New Visitor</h3>
                                </div>
                                <div class="card-body">
                                    <div class="chart">
                                        <div class="chartjs-size-monitor">
                                            <div class="chartjs-size-monitor-expand">
                                                <div class=""></div>
                                            </div>
                                            <div class="chartjs-size-monitor-shrink">
                                                <div class=""></div>
                                            </div>
                                        </div>
                                        <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Top Offices</h3>
                                </div>
                                <div class="card-body">
                                    <div class="chart">
                                        <div class="chartjs-size-monitor">
                                            <div class="chartjs-size-monitor-expand">
                                                <div class=""></div>
                                            </div>
                                            <div class="chartjs-size-monitor-shrink">
                                                <div class=""></div>
                                            </div>
                                        </div>
                                        <canvas id="stackedBarChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">Busy Days</h3>
                                </div>
                                <div class="card-body">
                                    <div class="chart">
                                        <div class="chartjs-size-monitor">
                                            <div class="chartjs-size-monitor-expand">
                                                <div class=""></div>
                                            </div>
                                            <div class="chartjs-size-monitor-shrink">
                                                <div class=""></div>
                                            </div>
                                        </div>
                                        <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 572px;" width="715" height="312" class="chartjs-render-monitor"></canvas>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                            </div>

                        </div>
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
    <!-- DataTables  & Plugins -->
    <!-- <script src="template/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="template/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="template/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="template/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="template/plugins/jszip/jszip.min.js"></script>
    <script src="template/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="template/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="template/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="template/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="template/plugins/datatables-buttons/js/buttons.colVis.min.js"></script> -->
    <script src="assets/lib/datatable/js/dataTables.min.js"></script>
    <!-- ChartJS -->
    <script src="template/plugins/chart.js/Chart.min.js"></script>
    <!-- Customs -->
    <script src="assets/js/report.js"></script>
    <script src="assets/js/index.js"></script>
</body>

</html>