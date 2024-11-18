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
    <title>Butiran Sumbangan Pengguna</title>
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
                        <h2 class="page-title" style="margin-top:4%">Butiran Sumbangan</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Semua Butiran Sumbangan</div>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Bil.</th>
                                            <th>Nama Program</th>
                                            <th>Kategori</th>
                                            <th>Gambar Program</th>
                                            <th>Penerangan</th>
                                            <th>Tarikh & Masa Program</th>
                                            <th>Lokasi</th>
                                            <th>Jumlah Sumbangan</th>
                                            <th>Butiran Sumbangan</th>
                                            <th>Kaedah Pembayaran</th>
                                            <th>Tarikh</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $userId = $_SESSION['id']; // Assuming user ID is stored in session

                                            $query = "SELECT d.D_Amount, d.D_Description, d.D_Date, d.D_MethodPay, p.P_Name, p.P_Image, p.P_Description, p.P_DateTime, p.P_Location, c.C_Name
                                                      FROM donation d
                                                      JOIN user_donation ud ON d.D_ID = ud.D_ID
                                                      JOIN user_program up ON ud.UP_ID = up.UP_ID
                                                      JOIN program p ON up.P_ID = p.P_ID
                                                      JOIN category c ON p.C_ID = c.C_ID
                                                      WHERE up.U_ID = ?
                                                      ORDER BY d.D_Date DESC ";
                                            $stmt = $mysqli->prepare($query);
                                            if (!$stmt) {
                                                die('Preparation failed: ' . $mysqli->error);
                                            }
                                            $stmt->bind_param("i", $userId);
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            if ($res->num_rows === 0) {
                                                echo "<tr><td colspan='11'>Tiada sumbangan ditemui.</td></tr>";
                                            }
                                            $cnt = 1;
                                            while ($row = $res->fetch_object()) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $cnt; ?></td>
                                                    <td><?php echo $row->P_Name; ?></td>
                                                    <td><?php echo $row->C_Name; ?></td>
                                                    <td><img src="../staff/<?php echo $row->P_Image; ?>" width="200px" height="120px"></td>
                                                    <td><?php echo $row->P_Description; ?></td>
                                                    <td><?php echo date('d M, Y h:i A', strtotime($row->P_DateTime)); ?></td>
                                                    <td><?php echo $row->P_Location; ?></td>
                                                    <td>RM <?php echo number_format($row->D_Amount, 2); ?></td>
                                                    <td><?php echo $row->D_Description; ?></td>
                                                    <td><?php echo $row->D_MethodPay; ?></td>
                                                    <td><?php echo date('d M, Y h:i A', strtotime($row->D_Date)); ?></td>
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
