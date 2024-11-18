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
    <title>Senarai Maklum Balas Peserta</title>
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
                        <h2 class="page-title" style="margin-top:4%">Senarai Maklum Balas Peserta</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Senarai Butiran Maklum Balas Peserta</div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="programFilter">Pilih Nama Program:</label>
                                    <div class="input-group">
                                        <select id="programFilter" class="form-control">
                                            <option value="">Semua Program</option>
                                            <?php
                                            // Fetch distinct program names and their corresponding categories for the dropdown
                                            $program_stmt = $mysqli->prepare("SELECT DISTINCT program.P_Name, category.C_Name FROM program LEFT JOIN category ON program.C_ID = category.C_ID");
                                            $program_stmt->execute();
                                            $program_res = $program_stmt->get_result();
                                            while ($program_row = $program_res->fetch_assoc()) {
                                                $program_category = htmlspecialchars($program_row['P_Name']) . " - " . htmlspecialchars($program_row['C_Name']);
                                                echo '<option value="' . $program_category . '">' . $program_category . '</option>';
                                            }
                                            ?>
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
                                            <th>Status</th>
                                            <th>Komen Peserta</th>
                                            <th>Tarikh Komen</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $ret = "SELECT feedback.FB_Comment, feedback.FB_DateTime,
                                                    CONCAT(user.U_Fname, ' ', user.U_Lname) AS UserName, user.U_IC, 
                                                    program.P_Name, program.P_Image, program.P_Description, program.P_DateTime AS ProgramDateTime, 
                                                    program.P_Location, program.P_Person, user_program.UP_Status, 
                                                    category.C_Name
                                                    FROM feedback
                                                    LEFT JOIN user_program ON feedback.UP_ID = user_program.UP_ID
                                                    LEFT JOIN user ON user_program.U_ID = user.U_ID
                                                    LEFT JOIN program ON user_program.P_ID = program.P_ID
                                                    LEFT JOIN category ON program.C_ID = category.C_ID
                                                    ORDER BY feedback.FB_DateTime DESC";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            $cnt = 1;
                                            while ($row = $res->fetch_object()) {
                                                $program_category = htmlspecialchars($row->P_Name) . " - " . htmlspecialchars($row->C_Name);
                                        ?>
                                                <tr class="programRow" data-program="<?php echo $program_category; ?>">
                                                    <td class="counter"><?php echo $cnt; ?></td>
                                                    <td><?php echo htmlspecialchars($row->UserName); ?></td>
                                                    <td><?php echo htmlspecialchars($row->U_IC); ?></td>
                                                    <td><?php echo htmlspecialchars($row->UP_Status); ?></td>
                                                    <td><?php echo htmlspecialchars($row->FB_Comment); ?></td>
                                                    <td><?php echo htmlspecialchars(date('d M, Y h:i A', strtotime($row->FB_DateTime))); ?></td>
                                                </tr>
                                        <?php
                                                $cnt++;
                                            }
                                        ?>
                                    </tbody>
                                </table>
                                <div id="noRecordsMessage" style="display:none; text-align:center; margin-top:10px;">Tiada rekod ditemui</div>
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
                var selectedProgram = $('#programFilter').val().toLowerCase();
                var count = 1; // Initialize counter
                var hasVisibleRows = false; // Track if any row is visible

                $('.programRow').each(function() {
                    var programName = $(this).data('program').toLowerCase();
                    if (selectedProgram === "" || programName === selectedProgram) {
                        $(this).show();
                        $(this).find('.counter').text(count); // Update the "Bil." column with the counter
                        count++; // Increment counter
                        hasVisibleRows = true; // Set flag to true since this row is visible
                    } else {
                        $(this).hide();
                    }
                });

                // Show or hide the "No records found" message
                if (!hasVisibleRows) {
                    $('#noRecordsMessage').show();
                } else {
                    $('#noRecordsMessage').hide();
                }
            });
        });
    </script>
</body>
</html>
