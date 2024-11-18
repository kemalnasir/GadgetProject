<?php
// Database connection parameters
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db_name = 'dbgadget';

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

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
    <title>Feedback Details</title>
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
                        <h2 class="page-title" style="margin-top:4%">List of System Feedback Details</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">All Feedback Details</div>
                            <div class="panel-body">
                                <table id="feedbackTable" class="display table table-striped table-bordered table-hover"
                                    cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No.</th> <!-- Counter column -->
                                            <th>User Name</th>
                                            <th>Email</th>
                                            <th>Feedback Description</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT u.U_Name, u.U_Email, f.F_Desc, f.F_Date
                                                FROM feedback f
                                                LEFT JOIN user u ON f.U_ID = u.U_ID
                                                ORDER BY f.F_Date DESC";
                                        $result = mysqli_query($conn, $sql);
                                        if (!$result) {
                                            die('Error: ' . mysqli_error($conn));
                                        }
                                        
                                        if (mysqli_num_rows($result) > 0) {
                                            $count = 1; // Initialize counter
                                            while ($row = mysqli_fetch_assoc($result)) {
                                        ?>
                                                <tr>
                                                    <td><?php echo $count; ?></td>
                                                    <td><?php echo htmlspecialchars($row['U_Name']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['U_Email']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['F_Desc']); ?></td>
                                                    <td><?php echo $row['F_Date']; ?></td>
                                                </tr>
                                        <?php
                                                $count++; // Increment counter
                                            }
                                        } else {
                                            echo "<tr><td colspan='5'>No feedback found</td></tr>";
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
    <script>
        $(document).ready(function () {
            $('#feedbackTable').DataTable();
        });
    </script>
</body>

</html>
