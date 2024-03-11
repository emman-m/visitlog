<?php include "config/session.php";
$activeLink = 'users';

require 'class/DatabaseManager.php';
$db = new DatabaseManager();

if (!isset($_GET['id'])) {
    header('location:users.php');
} else {
    $data = $db->selectWhere('user_account', array('id' => $_GET['id']));
    if (!$data) {
        header('location:users.php');
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
    <title>VisitLog | Edit User</title>
    <link rel="icon" type="image/png" href="assets/img/logo.png" />

    <?php include "component/global-css.php";?>
    <!-- DataTables -->
    <link rel="stylesheet" href="template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
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
                            <h1>Edit User</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="users.php">Users</a></li>
                                <li class="breadcrumb-item active">Edit User</li>
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
                                    <form id="editUserForm">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="err err_msg"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <!-- CONTENT -->
                                                <input type="hidden" name="id" value="<?php echo $row['id']?>">
                                                <div class="form-group">
                                                    <label for="firstname">First Name</label>
                                                    <input type="text" class="form-control" id="firstname" name="firstname"
                                                        placeholder="First Name" value="<?php echo $row['firstname']?>">
                                                    <small class="err d-block text-danger err_firstname"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="lastname">Last Name</label>
                                                    <input type="text" class="form-control" id="lastname" name="lastname"
                                                        placeholder="Last Name" value="<?php echo $row['lastname']?>">
                                                    <small class="err d-block text-danger err_lastname"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email"
                                                        placeholder="Email" value="<?php echo $row['email']?>">
                                                    <small class="err d-block text-danger err_email"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="role">Role</label>
                                                    <select name="role" id="role" class="form-control">
                                                        <option value="" disabled>Please Select</option>
                                                        <option value="1" <?php echo $row['role'] == $user_role::Admin ? 'selected' : ''?>>Admin</option>
                                                        <option value="2" <?php echo $row['role'] == $user_role::SecurityHead ? 'selected' : ''?>>Head Security</option>
                                                        <option value="3" <?php echo $row['role'] == $user_role::Department ? 'selected' : ''?>>Department</option>
                                                    </select>
                                                    <small class="err d-block text-danger err_role"></small>
                                                </div>

                                                <div class="form-group dept-group" style="display:none">
                                                    <label for="dept">Department</label>
                                                    <select name="dept" id="dept" class="form-control">
                                                        <option value="" disabled>Please Select</option>
                                                        <option value="1" <?php echo $row['department'] == $dept::Cashier ? 'selected' : ''?>>Cashier</option>
                                                        <option value="2" <?php echo $row['department'] == $dept::Registrar ? 'selected' : ''?>>Registrar</option>
                                                        <option value="3" <?php echo $row['department'] == $dept::Clinic ? 'selected' : ''?>>Clinic</option>
                                                        <option value="4" <?php echo $row['department'] == $dept::DisciplineOffice ? 'selected' : ''?>>Discipline Office</option>
                                                        <option value="5" <?php echo $row['department'] == $dept::Guard ? 'selected' : ''?>>Guard</option>
                                                    </select>
                                                    <small class="err d-block text-danger err_dept"></small>
                                                </div>
                                                
                                            </div>
                                            <div class="col-6">

                                                <div class="form-group">
                                                    <label for="username">Username</label>
                                                    <input type="text" class="form-control" id="username" name="username"
                                                        placeholder="Username" value="<?php echo $row['username']?>">
                                                    <small class="err d-block text-danger err_username"></small>
                                                </div>
                                                <hr>
                                                <h4>Change Password</h4>

                                                <div class="form-group">
                                                    <label for="oldpass">Current Password</label>
                                                    <input type="password" class="form-control" id="oldpass" name="oldpass"
                                                        placeholder="Current Password">
                                                    <small class="err d-block text-danger err_oldpass"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input type="password" class="form-control" id="password" name="password"
                                                        placeholder="Password">
                                                    <small class="err d-block text-danger err_password"></small>
                                                </div>
                                                
                                                <div class="form-group">
                                                    <label for="cpassword">Confirm Password</label>
                                                    <input type="password" class="form-control" id="cpassword" name="cpassword"
                                                        placeholder="Confirm Password">
                                                    <small class="err d-block text-danger err_cpassword"></small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary float-right" id="submit">Update</button>
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

    <?php include "component/global-js.php";?>
    <!-- Custom Script -->
    <script src="assets/js/edituser.js"></script>
</body>

</html>