<?php include "config/session.php";
date_default_timezone_set('Asia/Manila');
$activeLink = 'visitormap';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VisitLog | Blank Page</title>
    <link rel="icon" type="image/png" href="assets/img/logo.png" />

    <?php include "component/global-css.php";?>
</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <?php include_once "component/nav.php";?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include_once "component/sidebar.php";?>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="height:auto">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Department Map</h1>
                        </div>
                        <!-- <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="users.php">Users</a></li>
                                <li class="breadcrumb-item active">Add User</li>
                            </ol>
                        </div> -->
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
                                        <div class="col-12 col-lg-6">
                                            <!-- CONTENT -->
                                            <form id="mapForm">
                                                <input type="hidden" name="address" id="address"
                                                    value="<?php echo $_SERVER['SERVER_ADDR'];?>">
                                                <div class="form-group">
                                                    <label>Visitor</label>
                                                    <select class="form-control" name="visitor" id="visitor">
                                                        <option value="" selected disabled>Please Select</option>
                                                        <?php
                                                            $data = $db->selectWhereLike('visitors', array('id' => '!=', 'rfid' => '!='), array('updated_at' => Date('Y-m-d')));
                                                            foreach ($data as $row) {
                                                                echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                                                            }
                                                        ?>
                                                    </select>
                                                    <small class="err d-block text-danger err_visitor"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label>Destination</label>
                                                    <select class="form-control" name="department" id="department">
                                                        <option value="" selected disabled>Please Select</option>
                                                        <option value="<?php echo $dept::Cashier?>">Cashier</option>
                                                        <option value="<?php echo $dept::Registrar?>">Registrar</option>
                                                        <option value="<?php echo $dept::Clinic?>">Clinic</option>
                                                        <option value="<?php echo $dept::DisciplineOffice?>">Discipline
                                                            Office</option>
                                                    </select>
                                                    <small class="err d-block text-danger err_department"></small>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12">
                                                        <button type="submit" class="btn btn-primary"
                                                            id="submit">Generate QR</button>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>
                                        <div class="col-12 col-lg-6">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div id="qrcodeVal"></div>
                                                    <div id="qrcode"></div>
                                                </div>
                                            </div>
                                        </div>
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

    <?php include "component/global-js.php";?>
    <!-- QR CODE Generator -->
    <script src="assets/lib/qrcode/js/qrcode.js"></script>
    <!-- Custom -->
    <script src="assets/js/visitormap.js"></script>
</body>

</html>