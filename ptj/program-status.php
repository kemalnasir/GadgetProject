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
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Permohonan Program</title>
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
                        <h2 class="page-title" style="margin-top:4%">Senarai Permohonan Program dalam semakan</h2>
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
                                            <th>Catatan Staf</th>
                                            <th>Status</th>
                                            <th>Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Prepared statement to fetch program details
                                        $stmt = $mysqli->prepare("SELECT program.P_ID, program.P_Name, category.C_Name, program.P_Description, program.P_Image, program.P_File, program.P_DateTime, program.P_Capacity, program.P_Location, program.P_Person, program.P_Cert, program.P_Status, program.P_CommentUpdate	
                                                                FROM program
                                                                JOIN category ON program.C_ID = category.C_ID
                                                                WHERE program.P_Status = 'dalam semakan'
                                                                ORDER BY program.P_RegDate DESC");
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $res->fetch_object()) :
                                            ?>
                                            <tr>
                                                <td><?= $cnt ?></td>
                                                <td><?= $row->P_Name ?></td>
                                                <td><?= $row->C_Name ?></td>
                                                <td><img src="../staff/<?= $row->P_Image ?>" width="200px" height="120px"></td>
                                                <td><a href="../staff/<?= $row->P_File ?>" target="_blank"><i class="fa fa-download"></i></a></td>
                                                <td><?= $row->P_Description ?></td>
                                                <td><?= date('d M, Y h:i A', strtotime($row->P_DateTime)) ?></td>
                                                <td><?= $row->P_Capacity ?></td>
                                                <td><?= $row->P_Location ?></td>
                                                <td><?= $row->P_Person ?></td>
                                                <td><a href="../staff/<?= htmlspecialchars($row->P_Cert) ?>" target="_blank"><i class="fa fa-download"></i></a></td>
                                                  <?php if ($row->P_Status == 'dalam semakan') : ?>
                                                    <td><?= $row->P_CommentUpdate ?></td>
                                                <?php else : ?>
                                                    <td></td>
                                                <?php endif; ?>
                                                <td><?= $row->P_Status ?></td>
                                                <td><a href="program-updatestatus.php?id=<?= $row->P_ID ?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;</td>
                                            </tr>
                                            <?php
                                            $cnt++;
                                        endwhile;
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
