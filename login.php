<?php include "config/session.php";?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>VisitLog | Login</title>
    <link rel="icon" type="image/png" href="assets/img/logo.png" />
  
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="assets/font/googleSourceSans.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="template/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="template/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="template/dist/css/adminlte.min.css">
  <!-- Customs -->
  <link rel="stylesheet" href="assets/css/login.css">
</head>
<body class="hold-transition login-page">
    <div class="login-box">
        <div class="login-logo">
            <a href="../../index2.html"><b>VisitLog</b></a>
        </div>
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
            <p class="login-box-msg">RFID Driven visitor management system with mobile integration</p>
            
            <div class="err err_msg"></div>
            <form id="loginForm" action="../../index3.html" method="post">
                <div class="input-group mb-3">
                    <input type="text" name="username" id="username" class="form-control" placeholder="Username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                
                <div class="input-group mb-3">
                    <input type="password" name="password" id="password" class="form-control" placeholder="Password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">La Consolacion College Tanauan Batangas, PH</div>
                </div>
                <div class="row mt-3">
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block" id="submit">Sign In</button>
                    </div>
                </div>
            </form>

            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
    <!-- /.login-box -->

    <?php include "component/global-js.php";?>
    <script src="assets/js/login.js"></script>
</body>
</html>
