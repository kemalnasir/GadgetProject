<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Create a trigger to update notification count
$queryTrigger = "CREATE TRIGGER update_notification_count AFTER INSERT ON program
                 FOR EACH ROW
                 BEGIN
                     IF NEW.P_Status = 'dalam semakan' THEN
                         UPDATE notification_count SET count = count + 1;
                     END IF;
                 END;";

// Execute the trigger creation query
$mysqli->query($queryTrigger);

// Function to fetch programs under review and count
function fetchProgramsUnderReview() {
    global $mysqli;

    $programsUnderReview = array();
    $query = "SELECT P_Name, P_Status, P_RegDate FROM program WHERE P_Status = 'dalam semakan'";
    $result = $mysqli->query($query);

    $count = 0; // Initialize count
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $programsUnderReview[] = array(
                'no' => ++$count, // Increment count and assign as row number
                'name' => $row['P_Name'],
                'status' => $row['P_Status'],
                'regdate' => $row['P_RegDate']
            );
        }
    }

    return array('count' => $count, 'programs' => $programsUnderReview);
}

// Fetch programs under review
$data = fetchProgramsUnderReview();
$programsUnderReview = $data['programs'];
$programsCount = $data['count'];
?>

<!DOCTYPE html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Notifikasi</title> <!-- Notification in Malay -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-1.11.3-jquery.min.js"></script>
    <script src="js/validation.min.js"></script>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <br>
                        <h2 class="page-title">Notifikasi</h2> <!-- Notifications in Malay -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Permohonan Program dalam Semakan</div> <!-- Programs Under Review in Malay -->
                                    <div class="panel-body">
                                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                            <thead>
                                                <tr>
                                                    <th>Bil.</th>
                                                    <th>Nama Program</th> <!-- Program Name in Malay -->
                                                    <th>Status</th>
                                                    <th>Date</th>
                                                    <!-- Add more columns as needed -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($programsUnderReview)) {
                                                    foreach ($programsUnderReview as $program) {
                                                        echo '<tr>';
                                                        echo '<td>' . $program['no'] . '</td>'; // Display row number
                                                        echo '<td>' . htmlspecialchars($program['name']) . '</td>';
                                                        echo '<td>' . htmlspecialchars($program['status']) . '</td>';
                                                        // Format date and time
                                                        $timestamp = strtotime($program['regdate']);
                                                        $formattedDate = date('j M, Y g:i A', $timestamp); // e.g., 15 Jun, 2024 10:30 AM
                                                        echo '<td>' . htmlspecialchars($formattedDate) . '</td>';
                                                        echo '</tr>';
                                                    }
                                                } else {
                                                    echo '<tr><td colspan="4">Tiada permohonan program.</td></tr>'; // No programs under review found in Malay
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php if ($programsCount > 0): ?>
                        <script>
                            $(document).ready(function() {
                                alert('Anda mempunyai <?php echo $programsCount; ?> program dalam proses semakan!');
                            });
                        </script>
                        <?php endif; ?>
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
