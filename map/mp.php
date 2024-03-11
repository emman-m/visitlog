<?php
require "../class/DatabaseManager.php";
require "../enum/Department.php";
$db = new DatabaseManager();
$dept = new Department();

$logData = $db->selectNative("SELECT * FROM visitors_activity WHERE visitor_id = '".$_GET['id']."' ORDER BY time_in DESC LIMIT 1");
$img = "";

// echo "FROM ".$dept->get_name($logData[0]['department']).", To ".$dept->get_name($_GET['destination']);
// FROM
if ($logData) {
    $logDepartment = $logData[0]['department'];
    switch ($logData[0]['department']) {
        case $dept::Cashier :
            $img = "ca-";
            break;
        
        case $dept::Registrar :
            $img = "re-";
            break;
        
        case $dept::Clinic :
            $img = "cl-";
            break;
        
        case $dept::DisciplineOffice :
            $img = "di-";
            break;
        
        case $dept::Guard :
            $img = "gu-";
            break;
        
        default:
            # code...
            break;
    }
} else {
    $logDepartment = 5;
    $img = "gu-";
}

// TO
switch ($_GET['destination']) {
    case $dept::Cashier :
        $img .= "ca.svg";
        break;
    
    case $dept::Registrar :
        $img .= "re.svg";
        break;
    
    case $dept::Clinic :
        $img .= "cl.svg";
        break;
    
    case $dept::DisciplineOffice :
        $img .= "di.svg";
        break;
    
    case $dept::Guard :
        $img .= "gu.svg";
        break;
    
    default:
        # code...
        break;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <title>VisitLog | Map</title>
    <link rel="icon" type="image/png" href="../assets/img/logo.png" />

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="../assets/font/googleSourceSans.css">
    <!-- Font Awesome -->
    <!-- <link rel="stylesheet" href="../template/plugins/fontawesome-free/css/all.min.css"> -->
    <link rel="stylesheet" href="../assets/lib/fontawesome-6.5.1/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../template/dist/css/adminlte.min.css">
    <!-- Custom -->
    <link rel="stylesheet" href="../assets/font/poppins/stylesheet.css">
    <link rel="stylesheet" href="../assets/css/map.css">
</head>

<body class="hold-transition sidebar-mini" style="background-color:#7f8797">
    <!-- Site wrapper -->
    <div class="wrapper">
        <div class="panel">
            <?php if ((intVal($logDepartment) !== intVal($dept::Clinic)) && (intVal($_GET['destination']) !== intVal($dept::Clinic))) { ?>
                <a href="mp.php?id=<?php echo $_GET['id']?>&destination=<?php echo $dept::Clinic?>" class="cl-btn">
                    <i class="fa-solid fa-map-pin fa-bounce" style="color: #1ddb14;font-size:80px"></i>
                </a>
            <?php } else if ($_GET['destination'] == intVal($dept::Clinic)) { ?>
                <div class="cl-div">
                    <i class="fas fa-map-marker-alt fa-beat" style="color: #db1414;font-size:80px"></i>
                </div>
            <?php } ?>

            <?php if ((intVal($logDepartment) !== intVal($dept::Guard)) && (intVal($_GET['destination']) !== intVal($dept::Guard))) { ?>
                <a href="mp.php?id=<?php echo $_GET['id']?>&destination=<?php echo $dept::Guard?>" class="gu-btn">
                    <i class="fa-solid fa-map-pin fa-bounce" style="color: #1ddb14;font-size:80px"></i>
                </a>
            <?php } else if ($_GET['destination'] == intVal($dept::Guard)) { ?>
                <div class="gu-div">
                    <i class="fas fa-map-marker-alt fa-beat" style="color: #db1414;font-size:80px"></i>
                </div>
            <?php } ?>

            <?php if ((intVal($logDepartment) !== intVal($dept::Registrar)) && (intVal($_GET['destination']) !== intVal($dept::Registrar))) { ?>
                <a href="mp.php?id=<?php echo $_GET['id']?>&destination=<?php echo $dept::Registrar?>" class="re-btn">
                    <i class="fa-solid fa-map-pin fa-bounce" style="color: #1ddb14;font-size:80px"></i>
                </a>
            <?php } else if ($_GET['destination'] == intVal($dept::Registrar)) { ?>
                <div class="re-div">
                    <i class="fas fa-map-marker-alt fa-beat" style="color: #db1414;font-size:80px"></i>
                </div>
            <?php } ?>
                
            <?php if ((intVal($logDepartment) !== intVal($dept::Cashier)) && (intVal($_GET['destination']) !== intVal($dept::Cashier))) { ?>
                <a href="mp.php?id=<?php echo $_GET['id']?>&destination=<?php echo $dept::Cashier?>" class="ca-btn">
                    <i class="fa-solid fa-map-pin fa-bounce" style="color: #1ddb14;font-size:80px"></i>
                </a>
            <?php } else if ($_GET['destination'] == intVal($dept::Cashier)) { ?>
                <div class="ca-div">
                    <i class="fas fa-map-marker-alt fa-beat" style="color: #db1414;font-size:80px"></i>
                </div>
            <?php } ?>
            
            <?php if ((intVal($logDepartment) !== intVal($dept::DisciplineOffice)) && (intVal($_GET['destination']) !== intVal($dept::DisciplineOffice))) { ?>
                <a href="mp.php?id=<?php echo $_GET['id']?>&destination=<?php echo $dept::DisciplineOffice?>" class="di-btn">
                    <i class="fa-solid fa-map-pin fa-bounce" style="color: #1ddb14;font-size:80px"></i>
                </a>
            <?php } else if ($_GET['destination'] == intVal($dept::DisciplineOffice)) { ?>
                <div class="di-div">
                    <i class="fas fa-map-marker-alt fa-beat" style="color: #db1414;font-size:80px"></i>
                </div>
            <?php } ?>
            
            <!-- <?php echo '<img src="../assets/img/map/'.$img.'" alt="">';?> -->
            <div class="img">
                <?php echo '<img src="../assets/img/map/'.$img.'" alt="">';?>
            </div>
            
        </div>
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="../template/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="../template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="../template/dist/js/adminlte.min.js"></script>
    <!-- Custom -->
    <!-- <script src="../assets/js/logger.js"></script> -->
    <script>
        $(window).scroll(function() {
            
            // $('.fixed').css('top', $(window).scrollTop() + 10 + 'px');
            // $('.fixed').css('left', $(window).scrollLeft() + 10 + 'px');
            // console.log("top: "+ $(window).scrollTop()+ "left: "+ $(window).scrollLeft());
        });

        <?php if ($logDepartment == $dept::Cashier) { ?>
            $(document).ready(function(){
                setTimeout(() => {
                    window.scrollTo(2666, 1023);
                }, 500);
            })
        <?php } else if ($logDepartment == $dept::Guard) { ?>
            $(document).ready(function(){
                setTimeout(() => {
                    window.scrollTo(2875, 2864);
                }, 500);
            })
        <?php } else if ($logDepartment == $dept::Registrar) { ?>
            $(document).ready(function(){
                setTimeout(() => {
                    window.scrollTo(2876, 1400);
                }, 500);
            })
        <?php } else if ($logDepartment == $dept::Clinic) { ?>
            $(document).ready(function(){
                setTimeout(() => {
                    window.scrollTo(2536, 0);
                }, 500);
            })
        <?php } else if ($logDepartment == $dept::DisciplineOffice) { ?>
            $(document).ready(function(){
                setTimeout(() => {
                    window.scrollTo(963, 0);
                }, 500);
            })
        <?php } ?>
        
        
    </script>

</body>

</html>