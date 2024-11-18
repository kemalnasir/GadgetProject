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
    <title>Butiran Program</title>
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
                        <h2 class="page-title" style="margin-top:4%">Senarai Permohonan Program</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Senarai Permohonan Program</div>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Bil.</th>
                                            <th>Nama Program</th>
                                            <th>Kategori</th>
                                            <th>Gambar</th>
                                            <th>Fail</th>
                                            <th>Penerangan</th>
                                            <th>Tarikh & Masa Program</th>
                                            <th>Kapasiti Program</th>
                                            <th>Lokasi</th>
                                            <th>Penyelaras</th>
                                            <th>Sijil</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $ret = "SELECT program.P_Name, category.C_Name, program.P_Description, program.P_Image, program.P_File, program.P_DateTime, program.P_Capacity, program.P_Location, program.P_Person, program.P_Cert, program.P_Status
                                                    FROM program 
                                                    JOIN category ON program.C_ID = category.C_ID
                                                    ORDER BY program.P_RegDate DESC";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            $cnt = 1;
                                            while ($row = $res->fetch_object()) {
                                                // Format date and time to include date first, followed by month
                                                $date = date('d M, Y h:i A', strtotime($row->P_DateTime));
                                        ?>
                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><?php echo htmlspecialchars($row->P_Name); ?></td>
                                                    <td><?php echo htmlspecialchars($row->C_Name); ?></td>
                                                    <td><img src="../staff/<?php echo htmlspecialchars($row->P_Image); ?>" width="200px" height="120px"></td>
                                                    <td><a href="../staff/<?php echo htmlspecialchars($row->P_File); ?>" target="_blank"><i class="fa fa-download"></i></a></td>
                                                    <td><?php echo htmlspecialchars($row->P_Description); ?></td>
                                                    <td><?php echo $date; ?></td>
                                                    <td><?php echo htmlspecialchars($row->P_Capacity); ?></td>
                                                    <td><?php echo htmlspecialchars($row->P_Location); ?></td>
                                                    <td><?php echo htmlspecialchars($row->P_Person); ?></td>
                                                    <td><a href="../staff/<?php echo htmlspecialchars($row->P_Cert); ?>" target="_blank"><i class="fa fa-download"></i></a></td>
                                                    <td><?php echo htmlspecialchars($row->P_Status); ?></td>
                                                </tr>
                                        <?php
                                                $cnt = $cnt + 1;
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

    <!-- Skrip Muat Turun -->
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
