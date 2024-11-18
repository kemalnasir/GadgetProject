<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Function to fetch notifications for approved programs and count
function fetchApprovedPrograms() {
    global $mysqli;

    $approvedPrograms = array();
    $query = "SELECT P_Name, P_ApprovedDate, P_Status FROM program WHERE P_Status = 'Diluluskan' Order by P_ApprovedDate DESC";
    $result = $mysqli->query($query);

    if ($result->num_rows > 0) {
        $count = 1; // Initialize row number
        while ($row = $result->fetch_assoc()) {
            $approvedPrograms[] = array(
                'no' => $count, // Assign row number
                'name' => $row['P_Name'],
                'datetime' => $row['P_ApprovedDate'],
                'status' => $row['P_Status']
            );
            $count++; // Increment row number
        }
    }

    return $approvedPrograms;
}

// Fetch approved programs
$approvedPrograms = fetchApprovedPrograms();

// Handle form submission for program actions (if needed)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle any form submissions related to program management
    // Example: Add new program, update program details, etc.
    // Add your code here based on specific requirements
}

// Trigger to update notification count in database (pseudo-code)
// Example: Update notification count when a program is approved
// This is a simplified example and should be adapted to your database and application logic
$queryTrigger = "CREATE TRIGGER update_notification_count AFTER INSERT ON program
                 FOR EACH ROW
                 BEGIN
                     IF NEW.P_Status = 'Diluluskan' THEN
                         UPDATE notification_count SET count = count + 1;
                     END IF;
                 END;";
$mysqli->query($queryTrigger);
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
                                    <div class="panel-heading">Program Diluluskan</div> <!-- Notification Approved Programs in Malay -->
                                    <div class="panel-body">
                                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                            <thead>
                                                <tr>
                                                    <th>Bil.</th>
                                                    <th>Nama Program</th> <!-- Program Name in Malay -->
                                                    <th>Status</th>
                                                    <th>Tarikh Diluluskan</th> <!-- Date in Malay -->
                                                    <!-- Add more columns as needed -->
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (!empty($approvedPrograms)) {
                                                    foreach ($approvedPrograms as $program) {
                                                        echo '<tr>';
                                                        echo '<td>' . $program['no'] . '</td>'; // Display row number
                                                        echo '<td>' . htmlspecialchars($program['name']) . '</td>';
                                                        echo '<td>' . htmlspecialchars($program['status']) . '</td>';

                                                        // Format date and time
                                                        $timestamp = strtotime($program['datetime']);
                                                        $formattedDate = date('j M, Y g:i A', $timestamp); // e.g., 15 Jun, 2024 10:30 AM
                                                        echo '<td>' . htmlspecialchars($formattedDate) . '</td>';

                                                        // Add more columns as needed
                                                        echo '</tr>';
                                                    }
                                                } else {
                                                    echo '<tr><td colspan="4">Tiada program yang diluluskan.</td></tr>'; // No approved programs found in Malay
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
