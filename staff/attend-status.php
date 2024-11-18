<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Fetch data from user_program table along with related user and program details
$stmt = $mysqli->prepare("SELECT up.UP_ID, up.UP_Status, u.U_FName, u.U_LName, u.U_IC, u.U_PhoneNo, p.P_Name, p.P_Image, p.P_DateTime, p.P_Location, p.P_Description, p.P_Person, c.C_Name
                          FROM user_program up
                          INNER JOIN user u ON up.U_ID = u.U_ID
                          INNER JOIN program p ON up.P_ID = p.P_ID
                          INNER JOIN category c ON p.C_ID = c.C_ID 
                          ORDER BY up.UP_RegDate DESC");
$stmt->execute();
$res = $stmt->get_result();

// Process form submission for attendance
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errors = []; // Array to collect any errors encountered during form processing

    foreach ($_POST['attendance'] as $up_id => $status) {
        // Validate $status (optional)
        // Example validation: Ensure $status is either 'Hadir' or 'Tidak Hadir'
        if (!in_array($status, ['Hadir', 'Tidak Hadir'])) {
            $errors[] = "Invalid attendance status for UP_ID $up_id.";
            continue; // Skip updating this entry and log error
        }

        // Update attendance status in database
        $update_query = "UPDATE user_program SET UP_Status = ? WHERE UP_ID = ?";
        $update_stmt = $mysqli->prepare($update_query);
        $update_stmt->bind_param('si', $status, $up_id);

        if ($update_stmt->execute()) {
            // Update successful
        } else {
            // Handle update failure
            $errors[] = "Failed to update attendance for UP_ID $up_id.";
        }
    }

    // Check if there were any errors
    if (empty($errors)) {
        echo "<script>alert('Status Kehadiran berjaya dikemaskini');</script>";
        header('Refresh:0.1; url=attend-status.php');
        exit;
    } else {
        foreach ($errors as $error) {
            echo "<script>alert('$error');</script>";
        }
    }
}

// Fetch combined program names and categories for the dropdown
$combined_stmt = $mysqli->prepare("SELECT DISTINCT CONCAT(p.P_Name, ' - ', c.C_Name) AS ProgramCategory 
                                      FROM program p 
                                      INNER JOIN category c ON p.C_ID = c.C_ID");
$combined_stmt->execute();
$combined_res = $combined_stmt->get_result();
$program_categories = [];
while ($combined_row = $combined_res->fetch_assoc()) {
    $program_categories[] = $combined_row['ProgramCategory'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Senarai Kehadiran Program</title>
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
                        <h2 class="page-title" style="margin-top:4%">Senarai Butiran Peserta</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Senarai Butiran Peserta</div>
                            <div class="panel-body">
                                <form method="post" action="">
                                    <div class="form-group">
                                        <label for="programCategoryFilter">Pilih Nama Program:</label>
                                        <div class="input-group">
                                            <select id="programCategoryFilter" class="form-control">
                                                <option value="">Semua Program</option>
                                                <?php foreach ($program_categories as $program_category) { ?>
                                                    <option value="<?php echo htmlspecialchars($program_category); ?>"><?php echo htmlspecialchars($program_category); ?></option>
                                                <?php } ?>
                                            </select>
                                            <span class="input-group-btn">
                                                <button id="filterButton" class="btn btn-primary" type="button">Cari</button>
                                            </span>
                                        </div>
                                    </div>
                                    <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>Bil.</th>
                                                <th>Nama Peserta</th>
                                                <th>No. IC</th>
                                                <th>No. Telefon</th>
                                                <th>Status</th>
                                                <th>Kehadiran</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $cnt = 1; // Initialize counter
                                            while($row = $res->fetch_assoc()) { ?>
                                                <tr class="programRow" data-program="<?php echo htmlspecialchars($row['P_Name']); ?>" data-category="<?php echo htmlspecialchars($row['C_Name']); ?>">
                                                    <td><?php echo htmlspecialchars($cnt); ?></td>
                                                    <td><?php echo htmlspecialchars($row['U_FName'] . ' ' . $row['U_LName']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['U_IC']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['U_PhoneNo']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['UP_Status']); ?></td>
                                                    <td>
                                                        <!-- Use a hidden input to submit "Tidak Hadir" if checkbox is unchecked -->
                                                        <input type="hidden" name="attendance[<?php echo $row['UP_ID']; ?>]" value="Tidak Hadir">
                                                        <!-- Checkbox for "Hadir" status, checked if UP_Status is "Hadir" -->
                                                        <input type="checkbox" name="attendance[<?php echo $row['UP_ID']; ?>]" value="Hadir"
                                                            <?php if ($row['UP_Status'] == 'Hadir') echo 'checked'; ?>>
                                                    </td>
                                                </tr>
                                                <?php $cnt++; ?>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                    <div id="noRecordsMessage" style="display:none; text-align:center; margin-top:10px;">Tiada rekod ditemui</div>
                                    <button type="submit" class="btn btn-primary">Kemaskini Kehadiran</button>
                                </form>
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
    $(document).ready(function() {
        $('#filterButton').click(function() {
            var selectedProgramCategory = $('#programCategoryFilter').val().toLowerCase();
            var count = 1; // Initialize counter
            var recordsFound = false; // Flag to check if any records are found

            $('.programRow').each(function() {
                var programName = $(this).data('program').toLowerCase();
                var categoryName = $(this).data('category').toLowerCase();
                var combinedValue = (programName + ' - ' + categoryName).toLowerCase();
                
                if (selectedProgramCategory === "" || combinedValue === selectedProgramCategory) {
                    $(this).show();
                    $(this).find('td:first').text(count); // Update the "Bil." column with the counter
                    count++; // Increment counter
                    recordsFound = true; // Set flag to true
                } else {
                    $(this).hide();
                }
            });

            // Show or hide "No records found" message based on recordsFound flag
            if (!recordsFound) {
                $('#noRecordsMessage').show();
            } else {
                $('#noRecordsMessage').hide();
            }
        });
    });
    </script>
</body>
</html>
