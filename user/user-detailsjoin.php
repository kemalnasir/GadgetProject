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
    <title>Program Joined Details</title>
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
                        <h2 class="page-title" style="margin-top:4%">Program Details</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">All Program Details</div>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>No</th> <!-- Add a column for numbering -->
                                        <th>Program Name</th>
                                        <th>Category</th>
                                        <th>Image</th>
                                        <th>Description</th>
                                        <th>Date & Time</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $userid = $_SESSION['id'];
                                    // Fetch joined programs details
                                    $query = "SELECT P_Name,C_Name, P_Description, P_Image, P_DateTime, P_Location, UP_Status 
                                              FROM program 
                                              INNER JOIN category ON program.C_ID = category.C_ID
                                              INNER JOIN user_program ON program.P_ID = user_program.P_ID 
                                              WHERE user_program.U_ID = ?";
                                    $stmt = $mysqli->prepare($query);
                                    if ($stmt === false) {
                                        die('MySQL prepare error: ' . $mysqli->error);
                                    }
                                    $stmt->bind_param('i', $userid);
                                    $stmt->execute();
                                    $stmt->bind_result($pname, $pcid, $pdesc, $pimage, $pdate, $plocation, $pstatus);

                                    $counter = 1; // Initialize counter for numbering rows
                                    while ($stmt->fetch()) {
                                        ?>
                                        <tr>
                                            <td><?php echo $counter; ?></td> <!-- Display row number -->
                                            <td><?php echo $pname; ?></td>
                                            <td><?php echo $pcid; ?></td>
                                            <td><img src="../staff/image/<?php echo $pimage; ?>" alt="Program Image" style="width: 180px; height: 80px;"></td>
                                            <td><?php echo $pdesc; ?></td>
                                            <td><?php echo $pdate; ?></td>
                                            <td><?php echo $plocation; ?></td>
                                            <td><?php echo $pstatus; ?></td>
                                        </tr>
                                        <?php
                                        $counter++; // Increment row counter
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
