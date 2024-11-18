<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
?>
<!doctype html>
<html lang="ms" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Urus Waktu Solat</title>
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
    <?php include('includes/header.php');?>

    <div class="ts-main-content">
        <?php include('includes/sidebar.php');?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title" style="margin-top:4%"></h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Semua Butiran Waktu Solat</div>

                            <div class="panel-body">

                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">

                                    <thead>
                                        <tr>
                                            <th>Bil.</th>
                                            <th>Nama Solat</th>
                                            <th>Masa Solat</th>
                                            <th>Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $aid = $_SESSION['id'];
                                            $ret = "SELECT * FROM prayertime";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            $cnt = 1;
                                            while ($row = $res->fetch_object()) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $cnt;?></td>
                                                    <td><?php echo $row->PT_Name;?></td>
                                                    <td><?php echo date("h:i A", strtotime($row->PT_Time));?></td>
                                                    <td>
                                                        <a href="prayertime-update.php?id=<?php echo $row->PT_ID;?>" class="btn btn-primary btn-s">
                                                            Kemaskini
                                                        </a>
                                                        &nbsp;
                                                        <a href="prayertime-remove.php?id=<?php echo $row->PT_ID;?>" class="btn btn-danger btn-s">
                                                            Padam
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php
                                                $cnt++;
                                            }
                                        ?>
                                    </tbody>
                                </table>
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
