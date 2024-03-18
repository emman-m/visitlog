<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index.php" class="brand-link">
        <img src="assets/img/logo.png" alt="La Consolacion College Tanauan Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">VisitLog</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="assets/img/empty-pic.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?php echo $_SESSION['uname'] ?><br>
                    <small>
                        <?php echo $_SESSION['dept'] == 0 ? $user_role->get_name($_SESSION['role']) : $dept->get_name($_SESSION['dept']) ?>
                    </small>
                </a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                        with font-awesome or any other icon font library -->
                <li class="nav-item">
                    <a href="index.php" class="nav-link <?php echo $activeLink == 'dashboard' ? 'active' : ''; ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard
                        </p>
                    </a>

                </li>

                <?php
                if (($_SESSION['role'] == $user_role::Admin) || ($_SESSION['role'] == $user_role::SecurityHead)) {
                ?>
                    <li class="nav-header">Users</li>
                    <li class="nav-item">
                        <a href="users.php" class="nav-link <?php echo $activeLink == 'users' ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-user-cog"></i>
                            <p>
                                Users
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="adduser.php" class="nav-link <?php echo $activeLink == 'addusers' ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-user-plus"></i>
                            <p>
                                Add Users
                            </p>
                        </a>
                    </li>
                <?php } ?>

                <?php if (($_SESSION['role'] == $user_role::SecurityHead) || ($_SESSION['role'] == $user_role::Admin) || ($_SESSION['dept'] == $dept::Guard)) { ?>
                    <li class="nav-header">Visitors</li>
                    <li class="nav-item">
                        <a href="visitors.php" class="nav-link <?php echo $activeLink == 'visitors' ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-user-friends"></i>
                            <p>
                                Visitors
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="addvisitors.php" class="nav-link <?php echo $activeLink == 'addvisitors' ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-user-plus"></i>
                            <p>
                                Add New Visitor
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="purpose.php" class="nav-link <?php echo $activeLink == 'purpose' ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-list-ul"></i>
                            <p>
                                Purpose of Visit List
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="visitormap.php" class="nav-link <?php echo $activeLink == 'visitormap' ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-map-marked-alt"></i>
                            <p>
                                Map
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="visithistory.php" class="nav-link <?php echo $activeLink == 'visithistory' ? 'active' : ''; ?>">
                            <i class="nav-icon far fa-list-alt"></i>
                            <p>
                                Visit History
                            </p>
                        </a>
                    </li>
                <?php } ?>

                <?php if (($_SESSION['role'] == $user_role::Admin) || ($_SESSION['role'] == $user_role::SecurityHead)) { ?>

                    <li class="nav-header">Reports</li>
                    <li class="nav-item">
                        <a href="rpt-old-new.php" class="nav-link <?php echo $activeLink == 'rpt-old-new' ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-chart-pie"></i>
                            <p>
                                Old and New Visitor
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="rpt-top-offices.php" class="nav-link <?php echo $activeLink == 'topOffices' ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>
                                Top Offices
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="rpt-busy-days.php" class="nav-link <?php echo $activeLink == 'busyDays' ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-chart-line"></i>
                            <p>
                                Busy Days
                            </p>
                        </a>
                    </li>
                <?php } ?>


                <?php if ($_SESSION['role'] == $user_role::Department) { ?>
                    <li class="nav-header">Department</li>
                    <li class="nav-item">
                        <a href="logger.php" class="nav-link <?php echo $activeLink == 'rfid' ? 'active' : ''; ?>">
                            <i class="nav-icon fas fa-id-card"></i>
                            <p>
                                Visitor Attendance
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="visithistory.php" class="nav-link <?php echo $activeLink == 'visithistory' ? 'active' : ''; ?>">
                            <i class="nav-icon far fa-list-alt"></i>
                            <p>
                                Visit History
                            </p>
                        </a>
                    </li>
                <?php } ?>


            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>