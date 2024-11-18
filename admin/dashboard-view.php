<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
?>

<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    
    <title>Laman Utama</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<?php include("includes/header.php");?>

<div class="ts-main-content">
    <?php include("includes/sidebar.php");?>
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 text-center"> <!-- Centering the content -->
                    <h2 class="page-title" style="margin-top:4%">Laman Utama</h2>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="ts-main-content">   

                                  
                                <div class="col-md-4">
                                        <div class="panel panel-default">
                                            <div class="panel-body bk-success text-light">
                                                <div class="stat-panel text-center">
                                                    <?php
                                                    $result ="SELECT count(*) FROM Category ";
                                                    $stmt = $mysqli->prepare($result);
                                                    $stmt->execute();
                                                    $stmt->bind_result($count);
                                                    $stmt->fetch();
                                                    $stmt->close();
                                                    ?>
                                                    <div class="stat-panel-number h1 "><?php echo $count;?></div>
                                                    <div class="stat-panel-title text-lowercase"> KATEGORI</div>
                                                </div>
                                            </div>
                                            <a href="category-details.php" class="block-anchor panel-footer">Butiran Penuh<i class="fa fa-arrow-right"></i></a>
                                        </div>
                                </div>  

                                <div class="col-md-4">
                                        <div class="panel panel-default">
                                            <div class="panel-body bk-success text-light">
                                                <div class="stat-panel text-center">
                                                    <?php
                                                    $result ="SELECT count(*) FROM program ";
                                                    $stmt = $mysqli->prepare($result);
                                                    $stmt->execute();
                                                    $stmt->bind_result($count);
                                                    $stmt->fetch();
                                                    $stmt->close();
                                                    ?>
                                                    <div class="stat-panel-number h1 "><?php echo $count;?></div>
                                                    <div class="stat-panel-title text-lowercase"> BUTIRAN PROGRAM</div>
                                                </div>
                                            </div>
                                            <a href="view-program.php" class="block-anchor panel-footer">Butiran Penuh<i class="fa fa-arrow-right"></i></a>
                                        </div>
                                </div>        
                                
                                <div class="col-md-4">
                                        <div class="panel panel-default">
                                            <div class="panel-body bk-success text-light">
                                                <div class="stat-panel text-center">
                                                    <?php
                                                    $result ="SELECT count(*) FROM surah ";
                                                    $stmt = $mysqli->prepare($result);
                                                    $stmt->execute();
                                                    $stmt->bind_result($count);
                                                    $stmt->fetch();
                                                    $stmt->close();
                                                    ?>
                                                    <div class="stat-panel-number h1 "><?php echo $count;?></div>
                                                    <div class="stat-panel-title text-lowercase"> SURAH RINGKAS</div>
                                                </div>
                                            </div>
                                            <a href="surah-details.php" class="block-anchor panel-footer">Butiran Penuh<i class="fa fa-arrow-right"></i></a>
                                        </div>
                                </div>     
                                
                                 <div class="col-md-4">
                                        <div class="panel panel-default">
                                            <div class="panel-body bk-success text-light">
                                                <div class="stat-panel text-center">
                                                    <?php
                                                    $result ="SELECT count(*) FROM prayertime ";
                                                    $stmt = $mysqli->prepare($result);
                                                    $stmt->execute();
                                                    $stmt->bind_result($count);
                                                    $stmt->fetch();
                                                    $stmt->close();
                                                    ?>
                                                    <div class="stat-panel-number h1 "><?php echo $count;?></div>
                                                    <div class="stat-panel-title text-lowercase">WAKTU SOLAT</div>
                                                </div>
                                            </div>
                                            <a href="prayertime-details.php" class="block-anchor panel-footer">Butiran Penuh<i class="fa fa-arrow-right"></i></a>
                                        </div>
                                </div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <!-- Loading Scripts -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap-select.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/fileinput.js"></script>
    <script src="js/chartData.js"></script>
    <script src="js/main.js"></script>    
</body>
</html>
