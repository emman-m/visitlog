<?php include "config/session.php";
$activeLink = 'visitors';

require "class/DatabaseManager.php";
$db = new DatabaseManager();

if (!isset($_GET['id'])) {
    header('location:visitors.php');
} else {
    $data = $db->selectWhere('visitors', array('id' => $_GET['id']));
    if (!$data) {
        header('location:visitors.php');
    } else {
        $row = $data[0];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VisitLog | Edit Visitor</title>
    <link rel="icon" type="image/png" href="assets/img/logo.png" />

    <?php include "component/global-css.php"; ?>
    <!-- DataTables -->
    <link rel="stylesheet" href="template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <!-- Customs -->
    <link rel="stylesheet" href="assets/css/addvisitors.css">
</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <?php include_once "component/nav.php"; ?>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <?php include_once "component/sidebar.php"; ?>

        <!-- Modal -->
        <div class="modal fade" id="cameraModal" data-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Take a Photo</h4>
                        <button type="button" class="close" onclick="stopCamera()" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <video id="cameraFeed" autoplay style="width:100%;aspect-ratio: auto;display:none"></video><br>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" onclick="stopCamera()" data-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="capturePhoto()" data-dismiss="modal">Capture</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper" style="height:auto">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Edit Visitor</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="visitors.php">Visitors</a></li>
                                <li class="breadcrumb-item active">Edit Visitor</li>
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
                                    <form id="addVisitorForm">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="err err_msg"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <!-- CONTENT -->
                                                <h4>Personal Information</h4>
                                                <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text" class="form-control" id="name" name="name" placeholder="Name" value="<?php echo $row['name'] ?>">
                                                    <small class="err d-block text-danger err_name"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?php echo $row['email'] ?>">
                                                    <small class="err d-block text-danger err_email"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="email">Gender</label>
                                                    <select class="form-control" name="gender" id="gender">
                                                        <option value="" disabled>Please Select</option>
                                                        <option value="0" <?php echo $row['gender'] == 0 ? 'selected' : '' ?>>Female
                                                        </option>
                                                        <option value="1" <?php echo $row['gender'] == 1 ? 'selected' : '' ?>>Male
                                                        </option>
                                                    </select>
                                                    <small class="err d-block text-danger err_email"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="birthday">Birthday</label>
                                                    <input type="date" class="form-control" id="birthday" name="birthday" placeholder="Birthday" value="<?php echo $row['birthday'] ?>">
                                                    <small class="err d-block text-danger err_birthday"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="age">Age</label>
                                                    <input type="text" class="form-control" id="age" name="age" placeholder="Age" value="<?php echo $row['age'] ?>" readonly>
                                                    <small class="err d-block text-danger err_age"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="address">Address</label>
                                                    <input type="text" class="form-control" id="address" name="address" placeholder="Address" value="<?php echo $row['address'] ?>">
                                                    <small class="err d-block text-danger err_address"></small>
                                                </div>
                                                <hr>
                                                <h4>Department Appointment</h4>

                                                <!-- INPUTs -->
                                                <!-- Cashier -->
                                                <div class="form-group purpose-select">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" name="p[]" id="deptCashier" value="<?php echo $dept::Cashier ?>">
                                                        <label for="deptCashier" class="custom-control-label">Cashier</label>
                                                    </div>
                                                </div>
                                                <div class="container cashier-option" style="display:none" style="display:none">
                                                    <?php
                                                    $cashier = $purClass->get_purpose($dept::Cashier);
                                                    $i = 0;
                                                    foreach ($cashier as $val) {
                                                        echo '
                                                                <div class="form-group purpose-select">
                                                                    <div class="custom-control custom-radio">
                                                                        <input class="custom-control-input" type="radio"  name="p-cash" id="cash' . $i . '" value="' . $val . '">
                                                                        <label for="cash' . $i . '" class="custom-control-label font-weight-normal" >' . $val . '</label>
                                                                    </div>
                                                                </div>
                                                            ';
                                                        $i++;
                                                    }
                                                    echo '  <div class="form-group purpose-select">

                                                                <div class="custom-control custom-radio">
                                                                    <input class="custom-control-input" type="radio" name="p-cash" id="cash' . $i + 1 . '" value="Other">
                                                                    <label for="cash' . $i + 1 . '" class="custom-control-label font-weight-normal" >Other</label>
                                                                    <textarea class="form-control" name="p-cash-other" id="p-cash-other" rows="5" placeholder="..."></textarea>
                                                                </div>
                                                                <small class="err d-block text-danger err_purpose"></small>
                                                            </div>
                                                        ';
                                                    ?>
                                                    <small class="err d-block text-danger err_cashieroption"></small>
                                                </div>

                                                <!-- Registrar -->
                                                <div class="form-group purpose-select">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" name="p[]" id="deptRegistrar" value="<?php echo $dept::Registrar ?>">
                                                        <label for="deptRegistrar" class="custom-control-label">Registrar</label>
                                                    </div>
                                                </div>
                                                <div class="container reg-option" style="display:none">
                                                    <?php
                                                    $registrar = $purClass->get_purpose($dept::Registrar);
                                                    $i = 0;
                                                    foreach ($registrar as $val) {
                                                        echo '
                                                                <div class="form-group purpose-select">
                                                                    <div class="custom-control custom-radio">
                                                                        <input class="custom-control-input" type="radio"  name="p-reg" id="reg' . $i . '" value="' . $val . '">
                                                                        <label for="reg' . $i . '" class="custom-control-label font-weight-normal" >' . $val . '</label>
                                                                    </div>
                                                                </div>
                                                            ';
                                                        $i++;
                                                    }
                                                    echo '  <div class="form-group purpose-select">

                                                                <div class="custom-control custom-radio">
                                                                    <input class="custom-control-input" type="radio" name="p-reg" id="reg' . $i + 1 . '" value="Other">
                                                                    <label for="reg' . $i + 1 . '" class="custom-control-label font-weight-normal" >Other</label>
                                                                    <textarea class="form-control" name="p-reg-other" id="p-reg-other" rows="5" placeholder="..."></textarea>
                                                                </div>
                                                                <small class="err d-block text-danger err_purpose"></small>
                                                            </div>
                                                        ';
                                                    ?>
                                                    <small class="err d-block text-danger err_regoption"></small>
                                                </div>

                                                <!-- Clinic -->
                                                <div class="form-group purpose-select">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" name="p[]" id="deptClinic" value="<?php echo $dept::Clinic ?>">
                                                        <label for="deptClinic" class="custom-control-label">Clinic</label>
                                                    </div>
                                                </div>
                                                <div class="container clinic-option" style="display:none">
                                                    <?php
                                                    $clinic = $purClass->get_purpose($dept::Clinic);
                                                    $i = 0;
                                                    foreach ($clinic as $val) {
                                                        echo '
                                                                <div class="form-group purpose-select">
                                                                    <div class="custom-control custom-radio">
                                                                        <input class="custom-control-input" type="radio"  name="p-clinic" id="clinic' . $i . '" value="' . $val . '">
                                                                        <label for="clinic' . $i . '" class="custom-control-label font-weight-normal" >' . $val . '</label>
                                                                    </div>
                                                                </div>
                                                            ';
                                                        $i++;
                                                    }
                                                    echo '  <div class="form-group purpose-select">

                                                                <div class="custom-control custom-radio">
                                                                    <input class="custom-control-input" type="radio" name="p-clinic" id="clinic' . $i + 1 . '" value="Other">
                                                                    <label for="clinic' . $i + 1 . '" class="custom-control-label font-weight-normal" >Other</label>
                                                                    <textarea class="form-control" name="p-clinic-other" id="p-clinic-other" rows="5" placeholder="..."></textarea>
                                                                </div>
                                                                <small class="err d-block text-danger err_purpose"></small>
                                                            </div>
                                                        ';
                                                    ?>
                                                    <small class="err d-block text-danger err_clinicoption"></small>
                                                </div>

                                                <!-- DisciplineOffice -->
                                                <div class="form-group purpose-select">
                                                    <div class="custom-control custom-checkbox">
                                                        <input class="custom-control-input" type="checkbox" name="p[]" id="deptDisciplineOffice" value="<?php echo $dept::DisciplineOffice ?>">
                                                        <label for="deptDisciplineOffice" class="custom-control-label">Discipline Office</label>
                                                    </div>
                                                </div>
                                                <div class="container discipline-option" style="display:none">
                                                    <?php
                                                    $disof = $purClass->get_purpose($dept::DisciplineOffice);
                                                    $i = 0;
                                                    foreach ($disof as $val) {
                                                        echo '
                                                                <div class="form-group purpose-select">
                                                                    <div class="custom-control custom-radio">
                                                                        <input class="custom-control-input" type="radio"  name="p-discipline" id="discipline' . $i . '" value="' . $val . '">
                                                                        <label for="discipline' . $i . '" class="custom-control-label font-weight-normal" >' . $val . '</label>
                                                                    </div>
                                                                </div>
                                                            ';
                                                        $i++;
                                                    }
                                                    echo '  <div class="form-group purpose-select">

                                                                <div class="custom-control custom-radio">
                                                                    <input class="custom-control-input" type="radio" name="p-discipline" id="discipline' . $i + 1 . '" value="Other">
                                                                    <label for="discipline' . $i + 1 . '" class="custom-control-label font-weight-normal" >Other</label>
                                                                    <textarea class="form-control" name="p-discipline-other" id="p-discipline-other" rows="5" placeholder="..."></textarea>
                                                                </div>
                                                                <small class="err d-block text-danger err_purpose"></small>
                                                            </div>
                                                        ';
                                                    ?>
                                                    <small class="err d-block text-danger err_disciplineoption"></small>
                                                </div>
                                                <!-- INPUTs -->
                                                <small class="err d-block text-danger err_purpose"></small>

                                            </div>
                                            <div class="col-md-6">
                                                <h4>Visitor's Data</h4>
                                                <div class="form-group">
                                                    <label for="rfid">RFID</label>
                                                    <input type="text" class="form-control" id="rfid" name="rfid" placeholder="RFID">
                                                    <small class="err d-block text-danger err_rfid"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="validid">Valid ID</label>
                                                    <input type="text" class="form-control" id="validid" name="validid" placeholder="ID #" value="<?php echo $row['valid_id'] ?>">
                                                    <small class="err d-block text-danger err_validid"></small>
                                                </div>

                                                <div class="form-group image-upload">
                                                    <label for="" class="attachment-label">
                                                        Take a Photo<br>
                                                        <img id="preview" src="assets/img/visitor/<?php echo $row['image'] ?>" class="attachment img img-200" onerror="this.src='assets/img/empty-img.jpg'"><br>
                                                    </label><br>
                                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#cameraModal" onclick="openCamera()">
                                                        Take A Photo
                                                    </button>
                                                    <input type="hidden" class="form-control" id="img" name="img" style="display: block">
                                                    <small class="err d-block text-danger err_img"></small>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="button" class="btn btn-primary float-right" id="submit">Save</button>
                                            </div>
                                        </div>
                                    </form>

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
    <!-- Custom Script -->
    <script src="assets/js/editvisitors.js"></script>
</body>

</html>