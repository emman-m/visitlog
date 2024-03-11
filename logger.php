<?php include "config/session.php";
if ($_SESSION['dept'] == 0) {
    echo "Page Invalid";
    die();
    header('location: index.php');
}
$activeLink = 'rfid';
date_default_timezone_set('Asia/Manila');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VisitLog | <?php echo $dept->get_name($_SESSION['dept'])?></title>
    <link rel="icon" type="image/png" href="assets/img/logo.png" />

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="assets/font/googleSourceSans.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="template/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="template/dist/css/adminlte.min.css">
    <!-- Custom -->
    <link rel="stylesheet" href="assets/font/poppins/stylesheet.css">
    <link rel="stylesheet" href="assets/css/logger.css">
</head>

<body class="hold-transition sidebar-mini">
    <!-- Site wrapper -->
    <div class="wrapper main">
        <div class="panel">
            <div class="panel2 align-content-center align-items-center d-flex justify-content-center">
                <!-- LEFT -->
                <div class="col-left text-center">
                    <div class="row">
                        <div class="col-12">
                            <div class="logo">
                                <img src="assets/img/logo.png" alt="">
                            </div>

                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="welcome">
                                WELCOME!
                            </div>
                            <p class="ins mt-3">Please tap your card on the in-reader to<br>
                                scan and process the information.</p>
                        </div>
                    </div>
                </div>
                <!-- RIGHT -->
                <div class="col-right align-items-center d-flex flex-column flex-wrap justify-content-center">
                    <!-- Scan Panel -->
                    <div class="right-panel scan-panel">
                        <div class="row">
                            <div class="col-12 text-center">
                                <div class="dept"><?php echo $dept->get_name($_SESSION['dept'])?></div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-12">
                                <div class="rfid">RFID:</div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="input-group">
                                    <!-- <i class="fas fa-id-card"></i> -->
                                    <div class="align-items-center d-flex">
                                        <img class="card-icon" src="assets/icon/card.png" alt="Card">
                                    </div>
                                    <form id="RFID" style="margin:0">
                                        <input type="text" class="idField" id="idField" autocomplete="off" autofocus>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="row time-data">
                            <div class="col-12">
                                <div class="logger-label">
                                    Day: <?php echo Date('l')?>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="logger-label">
                                    Date: <?php echo Date('F d, Y')?>
                                </div>
                            </div>
                            <div class="col-12 mt-2">
                                <div class="logger-label">
                                    Time: <span id="time"><?php echo Date('H:i:s A')?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Loading Panel -->
                    <div class="right-panel loading-panel" style="display:none">
                        <div>
                            <i class="fas fa-spinner fa-pulse"></i>
                        </div>

                    </div>
                    <!-- No-Data Panel -->
                    <div class="right-panel nodata-panel" style="display:none">
                        <div style="font-size:30px; color:white">
                            No Data Found
                        </div>

                    </div>
                    <!-- Result Panel -->
                    <div class="right-panel result-panel" style="display:none">
                        <div class="row">
                            <div class="col-12 d-flex justify-content-center">
                                <div class="res-img-div">
                                    <img id="imageData" class="res-img" src=""
                                    onerror="this.src='assets/img/empty-img.jpg'">
                                </div>
                            </div>
                        </div>
                        <!-- NAME -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="res-label">NAME:</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="nameData" class="res-data"></div>
                            </div>
                        </div>
                        <!-- TIME IN -->
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="res-label">TIME IN:</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="timeInData" class="res-data"></div>
                            </div>
                        </div>
                        <!-- TIME OUT -->
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="res-label">TIME OUT:</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="timeOutData" class="res-data"></div>
                            </div>
                        </div>
                        <!-- PURPOSE OF VISIT -->
                        <div class="row mt-2">
                            <div class="col-12">
                                <div class="res-label">PURPOSE OF VISIT:</div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div id="purposeData" class="res-data purpose"></div>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12 closing-timer-div">
                                Closing in <span class="closing-timer">8</span> . . .
                            </div>
                        </div>
                    </div>
                    <div class="exit-link">
                        <a href="index.php">EXIT <img class="exit-icon" src="assets/icon/exit.png" alt="Exit"></a>
                    </div>

                </div>

            </div>
        </div>
    </div>
    <!-- ./wrapper -->

    <?php include "component/global-js.php";?>
    <!-- Custom -->
    <script src="assets/js/logger.js"></script>

</body>

</html>