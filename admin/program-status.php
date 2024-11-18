<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Fetch data from user_program table along with related user and program details
$ret = "SELECT up.UP_ID, up.UP_Status, u.U_FName, u.U_LName, u.U_IC, u.U_PhoneNo, p.P_Name, p.P_Image,p.P_DateTime,p.P_Location
        FROM user_program up
        INNER JOIN user u ON up.U_ID = u.U_ID
        INNER JOIN program p ON up.P_ID = p.P_ID";

$stmt = $mysqli->prepare($ret);
$stmt->execute();
$res = $stmt->get_result();
$cnt = 1;
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
    <title>Verify Program</title>
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
                            <div class="panel-heading">All Details</div>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Name</th>
                                            <th>IC</th>
                                            <th>Phone No</th>
                                            <th>Program Name</th>
                                            <th>Image</th>
                                            <th>Date</th>
                                            <th>Location</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($row = $res->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo $row['U_FName'] . ' ' . $row['U_LName']; ?></td>
                                                <td><?php echo $row['U_IC']; ?></td>
                                                <td><?php echo $row['U_PhoneNo']; ?></td>
                                                <td><?php echo $row['P_Name']; ?></td>
                                                <td><img src="../staff/image/<?php echo $row['P_Image']; ?>" width="180px" height="80px"></td>
                                                <td><?php echo $row['P_DateTime']; ?></td>
                                                <td><?php echo $row['P_Location']; ?></td>
                                                <td><?php echo $row['UP_Status']; ?></td>
                                                <td><a href="program-updatestatus.php?id=<?php echo $row['UP_ID']; ?>"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;</td>
                                            </tr>
                                            <?php
                                            $cnt++;
                                        } ?>
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
