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
    <title>Maklumat Pengguna</title>
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
                        <h2 class="page-title" style="margin-top:4%">Senarai Maklumat Pengguna</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Semua Butiran Pengguna</div>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>No. Kad Pengenalan</th>
                                            <th>Nama</th>
                                            <th>Email</th>
                                            <th>No. Telefon</th>
                                            <th>Jantina</th>
                                            <th>Peranan</th>
                                            <th>Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
<?php
$query = "SELECT * FROM user ORDER BY U_RegDate DESC";
$result = $mysqli->query($query);
if ($result && $result->num_rows > 0) {
    $count = 1;
    while ($row = $result->fetch_assoc()) {
?>
        <tr>
            <td><?php echo $count; ?></td>
            <td><?php echo $row['U_IC']; ?></td>
            <td><?php echo $row['U_FName'] . ' ' . $row['U_LName']; ?></td>
            <td><?php echo $row['U_Email']; ?></td>
            <td><?php echo $row['U_PhoneNo']; ?></td>
            <td><?php echo $row['U_Gender']; ?></td>
            <td><?php echo $row['U_Roles']; ?></td>
            <td>
                <?php 
                // Check if the role is not 'admin' to show the edit link
                if ($row['U_Roles'] != 'admin') {
                ?>
                    <a href="user-updaterole.php?id=<?php echo $row['U_ID']; ?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;
                <?php
                }
                ?>
            </td>
        </tr>
<?php
        $count++;
    }
    $result->free();
} else {
    echo "<tr><td colspan='8'>Tiada pengguna dijumpai.</td></tr>";
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
