<?php
session_start();
include('includes/config.php');
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
    <link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
    <link href="assets/css/slick.css" rel="stylesheet">
    <link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">

    <link rel="stylesheet" id="switcher-css" type="text/css" href="assets/switcher/css/switcher.css" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/red.css" title="red" media="all" data-default-color="true" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/orange.css" title="orange" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/blue.css" title="blue" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/pink.css" title="pink" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/green.css" title="green" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/purple.css" title="purple" media="all" />

    <title>E-Masjidlite - Senarai Hubungi</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
        body {
            background-image: url('image/masjidutem.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: black;
        }

        .wrapper {
            min-height: 100%;
            position: relative;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }

        .panel-transparent {
            background-color: rgba(255, 255, 255, 0.7);
            color: black;
        }
    </style>
</head>

<body>

    <div class="wrapper">

        <?php include('includes/headermain.php'); ?>

        <div class="ts-main-content">
            <div class="container-fluid">
                <div class="col-md-12">
                    <h2 class="page-title">Senarai Hubungi</h2>
                    <div class="panel panel-default panel-transparent">
                        <div class="panel-heading">Butiran Admin dan Staf Masjid Sayydina Abu Bakar, UTeM</div>
                        <div class="panel-body">
                            <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Nombor Hubungan</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $ret = "SELECT * FROM user WHERE U_Roles IN ('admin', 'staff')";
                                    $stmt = $mysqli->prepare($ret);
                                    $stmt->execute();
                                    $res = $stmt->get_result();

                                    while ($row = $res->fetch_object()) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row->U_FName . ' ' . $row->U_LName; ?></td>
                                            <td><?php echo $row->U_PhoneNo; ?></td>
                                            <td><?php echo $row->U_Email; ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="push"></div> <!-- Push the footer to the bottom -->

    </div>

    <div class="footer">
        <?php include('includes/footer.php'); ?>
        <div id="back-top" class="back-top">
            <a href="#top"><i class="fa fa-angle-up" aria-hidden="true"></i> </a>
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
