<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if (isset($_POST['submit'])) {
    $ptName = $_POST['pt_name'];
    $ptTime = $_POST['pt_time'];

    // Semak jika nama waktu solat sudah wujud
    $result = "SELECT COUNT(*) FROM prayertime WHERE PT_Name=?";
    $stmt = $mysqli->prepare($result);
    $stmt->bind_param('s', $ptName);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    // Jika nama waktu solat sudah wujud, paparkan alert
    if ($count > 0) {
        echo "<script>alert('Waktu Solat sudah dimasukkan sebelum ini.');</script>";
    } else {
        // Jika nama waktu solat belum wujud, masukkan baru
        $query = "INSERT INTO prayertime (PT_Name, PT_Time) VALUES (?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ss', $ptName, $ptTime);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Waktu Solat telah berjaya ditambah.');</script>";
        header('Refresh:0.1; url=prayertime-details.php');
    }
}
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
    <title>Tambah Waktu Solat</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
    <script type="text/javascript" src="js/validation.min.js"></script>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <br>
                  <br>
                    <div class="col-md-12"> 
                        <h2 class="page-title" style="margin-top:1%">Tambah Waktu Solat</h2>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Tambah Waktu Solat</div>
                                    <div class="panel-body">
                                        <form method="post" class="form-horizontal">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Nama Solat</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="pt_name" id="pt_name" value="" required="required">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Masa Solat</label>
                                                <div class="col-sm-8">
                                                    <input type="time" class="form-control" name="pt_time" id="pt_time" value="" required="required">
                                                </div>
                                            </div>
                                            <script>
                                                function confirmCancel() {
                                                    return confirm("Adakah anda pasti untuk membatalkan?");
                                                }
                                            </script>
                                            <div class="col-sm-8 col-sm-offset-2">
                                                <input class="btn btn-primary" type="submit" name="submit" value="Tambah Waktu Solat">
                                                <a class="btn btn-danger" href="prayertime-details.php" onclick="return confirmCancel();">Batal</a>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
