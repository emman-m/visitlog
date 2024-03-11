<?php
$ml = '';
if ($url[2] == 'logger.php') {
    $ml = 'ml-0';
}
?>
<nav class="main-header navbar navbar-expand navbar-primary navbar-dark navbar-light <?php echo $ml?>">
    <!-- Left navbar links -->
    <?php
    if ($ml == "") {
        ?>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="index.php" class="nav-link">Home</a>
            </li>
        </ul>
        <?php
    }
    ?>
    

    <!-- Right navbar links -->
    <ul class="align-items-center d-flex ml-auto navbar-nav">
        <li class="nav-item justify-item-center">
            <?php echo Date('D F n Y');?> - <span id="cur_time"> <?php echo Date('g:i:s A')?></span>
        </li>
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge notif-count-badge"></span>
            </a>
            <div id="notif" class="dropdown-menu dropdown-menu-lg dropdown-menu-right"></div>
        </li>
        
        <li class="nav-item">
            <a class="nav-link" href="logout.php" role="button" title="Logout">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </li>
    </ul>
</nav>