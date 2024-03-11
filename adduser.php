<?php include "config/session.php";
$activeLink = 'addusers';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VisitLog | Add User</title>
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
                            <h1>Add User</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="users.php">Users</a></li>
                                <li class="breadcrumb-item active">Add User</li>
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
                                    <form id="addUserForm">
                                        <div class="row">
                                            <div class="col-12">
                                                <div class="err err_msg"></div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-6">
                                                <!-- CONTENT -->
                                                <div class="form-group">
                                                    <label for="firstname">First Name</label>
                                                    <input type="text" class="form-control" id="firstname"
                                                        name="firstname" placeholder="First Name">
                                                    <small class="err d-block text-danger err_firstname"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="lastname">Last Name</label>
                                                    <input type="text" class="form-control" id="lastname"
                                                        name="lastname" placeholder="Last Name">
                                                    <small class="err d-block text-danger err_lastname"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" id="email" name="email"
                                                        placeholder="Email">
                                                    <small class="err d-block text-danger err_email"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="role">Role</label>
                                                    <select name="role" id="role" class="form-control">
                                                        <option value="" selected disabled>Please Select</option>
                                                        <option value="1">Admin</option>
                                                        <option value="2">Head Security</option>
                                                        <option value="3">Department</option>
                                                    </select>
                                                    <small class="err d-block text-danger err_role"></small>
                                                </div>

                                                <div class="form-group dept-group" style="display:none">
                                                    <label for="dept">Department</label>
                                                    <select name="dept" id="dept" class="form-control">
                                                        <option value="" selected disabled>Please Select</option>
                                                        <option value="1">Cashier</option>
                                                        <option value="2">Registrar</option>
                                                        <option value="3">Clinic</option>
                                                        <option value="4">Discipline Office</option>
                                                        <option value="5">Guard</option>
                                                    </select>
                                                    <small class="err d-block text-danger err_dept"></small>
                                                </div>

                                            </div>
                                            <div class="col-6">

                                                <div class="form-group">
                                                    <label for="username">Username</label>
                                                    <input type="text" class="form-control" id="username"
                                                        name="username" placeholder="Username">
                                                    <small class="err d-block text-danger err_username"></small>
                                                </div>

                                                <div class="form-group">
                                                    <label for="password">Password</label>
                                                    <input type="password" class="form-control" id="password"
                                                        name="password" placeholder="Password">
                                                    <small class="d-block text-warning">
                                                        Password must be atleast 8 characters.
                                                    </small>
                                                    <small class="d-block text-warning">
                                                        Password must not be exceed to 10 characters.
                                                    </small>
                                                    <!-- <small class="err d-block text-danger err_password"></small> -->
                                                </div>

                                                <div class="form-group">
                                                    <label for="cpassword">Confirm Password</label>
                                                    <input type="password" class="form-control" id="cpassword"
                                                        name="cpassword" placeholder="Confirm Password">
                                                    <small class="d-block text-warning">
                                                        Password must be atleast 8 characters.
                                                    </small>
                                                    <small class="d-block text-warning">
                                                        Password must not be exceed to 10 characters.
                                                    </small>
                                                    <!-- <small class="err d-block text-danger err_cpassword"></small> -->
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary float-right"
                                                    id="submit">Save</button>
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
    <script src="assets/js/adduser.js"></script>
</body>

</html>