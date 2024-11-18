<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

$userId = $_SESSION['id'];

$query = "
    SELECT fb.FB_ID, fb.FB_Comment, fb.FB_DateTime, p.P_Name, p.P_Image, p.P_DateTime, p.P_Location, p.P_Description, c.C_Name
    FROM feedback fb
    JOIN user_program up ON fb.UP_ID = up.UP_ID
    JOIN program p ON up.P_ID = p.P_ID
    JOIN category c ON p.C_ID = c.C_ID
    WHERE up.U_ID = ?
    Order BY fb.FB_DateTime DESC
";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
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
    <title>Butiran Maklum Balas Pengguna</title> <!-- Updated title based on the page content -->
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
                        <h2 class="page-title" style="margin-top:4%">Butiran Maklum Balas Pengguna</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Semua Butiran Maklum Balas</div>
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
                                            <th>Komen</th>
                                            <th>Tarikh</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        if ($result->num_rows > 0) {
                                            $bil = 1; // Start numbering
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . $bil . "</td>";
                                                echo "<td>" . $row['P_Name'] . "</td>";
                                                echo "<td>" . $row['C_Name'] . "</td>";
                                                echo "<td><img src='../staff/" . $row['P_Image'] . "' width='200px' height='120px'></td>";
                                                echo "<td>" . $row['P_Description'] . "</td>";
                                                echo "<td>" . date('d M, Y h:i A', strtotime($row['P_DateTime'])) . "</td>"; // Format date and time
                                                echo "<td>" . $row['P_Location'] . "</td>";
                                                echo "<td>" . $row['FB_Comment'] . "</td>";
                                                echo "<td>" . date('d M, Y h:i A', strtotime($row['FB_DateTime'])) . "</td>"; // Format date and time
                                                echo "</tr>";
                                                $bil++; // Increment numbering
                                            }
                                        } else {
                                            echo "<tr><td colspan='9'>Tiada maklum balas dijumpai</td></tr>";
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
