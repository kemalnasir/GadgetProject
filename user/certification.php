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
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Butiran Sijil</title>
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
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title" style="margin-top:4%">Butiran Sijil</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Semua Sejarah Program</div>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Bil.</th>
                                            <th>Nama Program</th>
                                            <th>Kategori</th>
                                            <th>Gambar</th>
                                            <th>Penerangan</th>
                                            <th>Tarikh & Masa Program</th>
                                            <th>Lokasi</th>
                                            <th>Penyelaras</th>
                                            <th>Status</th>
                                            <th>Sijil</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $userid = $_SESSION['id'];
                                        $query = "SELECT up.UP_ID, up.UP_Status, p.P_Name, c.C_Name, p.P_Description, p.P_Image, p.P_DateTime, p.P_Location, p.P_Person, p.P_Cert
                                                  FROM program p
                                                  INNER JOIN category c ON p.C_ID = c.C_ID
                                                  INNER JOIN user_program up ON p.P_ID = up.P_ID
                                                  WHERE up.U_ID = ? AND up.UP_Status = 'Hadir'
                                                  ORDER BY up.UP_RegDate DESC";
                                        $stmt = $mysqli->prepare($query);
                                        if ($stmt === false) {
                                            die('Ralat persediaan MySQL: ' . $mysqli->error);
                                        }
                                        $stmt->bind_param('i', $userid);
                                        $stmt->execute();
                                        $res = $stmt->get_result();

                                        $counter = 1;
                                        while ($row = $res->fetch_assoc()) {
                                            ?>
                                            <tr>
                                                <td><?php echo $counter; ?></td>
                                                <td><?php echo $row['P_Name']; ?></td>
                                                <td><?php echo $row['C_Name']; ?></td>
                                                <td><img src="../staff/<?php echo $row['P_Image']; ?>" alt="Gambar Program" style="width: 200px; height: 120px;"></td>
                                                <td><?php echo $row['P_Description']; ?></td>
                                                <td><?php echo date('d M, Y h:i A', strtotime($row['P_DateTime'])); ?></td> <!-- Format date and time -->
                                                <td><?php echo $row['P_Location']; ?></td>
                                                <td><?php echo $row['P_Person']; ?></td>
                                                <td><?php echo $row['UP_Status']; ?></td>
                                                <td>
                                                    <?php if (!empty($row['P_Cert']) && file_exists("../staff/".$row['P_Cert'])) { ?>
                                                        <a href="../staff/<?php echo $row['P_Cert']; ?>" target="_blank">Muat Turun di Sini <i class="fa fa-download"></i></a>
                                                    <?php } else { ?>
                                                        Tidak Tersedia
                                                    <?php } ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $counter++;
                                        }
                                        $stmt->close();
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

    <!-- Skrip Pemuatan -->
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