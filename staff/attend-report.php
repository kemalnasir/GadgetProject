<?php
session_start();

// Include necessary files
require_once('includes/config.php'); // Adjust path as necessary
require_once('includes/checklogin.php');
include_once('../vendor/autoload.php'); // Include Dompdf autoload

// Check if user is logged in
check_login();

use Dompdf\Dompdf;
use Dompdf\Options;

// Establish database connection
$conn = new mysqli("localhost", "root", "", "dbmasjidlite"); // Adjust database credentials

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$html_output = '';
$selected_program_name = '';
$selected_status = '';

// Process form submission
if (isset($_GET['Search']) || isset($_GET['DownloadPDF'])) {
    // Get form inputs
    $selected_program_name = isset($_GET['program_name']) ? $_GET['program_name'] : '';
    $selected_status = isset($_GET['status']) ? $_GET['status'] : '';

    // Prepare SQL query
    $sql = "SELECT UP.*, P.*, U.U_FName, U.U_LName, U.U_IC, C.C_Name
            FROM user_program UP
            JOIN program P ON UP.P_ID = P.P_ID
            JOIN category C ON P.C_ID = C.C_ID
            JOIN user U ON UP.U_ID = U.U_ID
            WHERE 1";

    // Append conditions based on inputs
    $params = array();

    if (!empty($selected_program_name)) {
        $sql .= " AND P.P_Name = ?";
        $params[] = $selected_program_name;
    }
    if (!empty($selected_status)) {
        $sql .= " AND UP.UP_Status = ?";
        $params[] = $selected_status;
    }

    $sql .= " ORDER BY UP.UP_RegDate DESC";

    // Prepare statement
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // Bind parameters if there are any
        if (!empty($params)) {
            $types = str_repeat('s', count($params)); // Assuming all params are strings
            $stmt->bind_param($types, ...$params);
        }

        // Execute query
        $stmt->execute();
        $result = $stmt->get_result();

        // Generate HTML output for the table
        ob_start();
        ?>
        <br>
        <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th>Bil.</th>
                    <th>Nama Peserta</th>
                    <th>No. IC</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $no = 1;
                while ($row = $result->fetch_assoc()) {
                    $user_name = $row['U_FName'] . ' ' . $row['U_LName'];
                    echo "<tr>";
                    echo "<td>" . $no++ . "</td>";
                    echo "<td>" . htmlspecialchars($user_name) . "</td>";
                    echo "<td>" . htmlspecialchars($row['U_IC']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['UP_Status']) . "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        <?php
        $html_output = ob_get_clean();

        // If DownloadPDF is clicked, generate PDF and force download
        if (isset($_GET['DownloadPDF'])) {
            // Initialize Dompdf
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $dompdf = new Dompdf($options);

            // Start output buffering for PDF content
            ob_start();
            ?>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <title>Senarai Laporan Kehadiran</title>
                <link rel="stylesheet" href="css/bootstrap.min.css">
                <style>
                    body { font-family: Arial, sans-serif; }
                    h2 { text-align: center; }
                    table { width: 100%; border-collapse: collapse; }
                    table, th, td { border: 1px solid #888; padding: 8px; }
                    img { max-width: 200px; max-height: 120px; } /* Maintain the original size */
                </style>
            </head>
            <body>
                <h2>Senarai Laporan Kehadiran</h2>
                <?php echo $html_output; ?>
            </body>
            </html>
            <?php
            // Get HTML content
            $html = ob_get_clean();

            // Load HTML into Dompdf
            $dompdf->loadHtml($html);

            // Set paper size and orientation
            $dompdf->setPaper('A4', 'landscape');

            // Render PDF (generate)
            $dompdf->render();

            // Output PDF to browser (force download)
            $dompdf->stream("Laporan_Kehadiran_Program.pdf", array("Attachment" => 1));

            // Exit script execution
            exit();
        }

        $stmt->close();
    } else {
        echo "Error in preparing SQL statement: " . $conn->error;
    }
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
    <title>Laporan Kehadiran</title>
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
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include("includes/sidebar.php"); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title" style="margin-top:4%">Butiran Laporan Kehadiran</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Laporan semua kehadiran</div>
                            <div class="panel-body">
                                <div class="container my-4">
                                    <form action="" class="form-inline" method="GET">
                                        <div class="form-group">
                                            <label for="program_name">Pilih Nama Program:</label>
                                            <br>
                                            <select name="program_name" id="program_name" class="form-control">
                                                <option value="">Semua Program</option>
                                                <?php
                                                // Fetch program names along with their categories from the database
                                                $sql = "SELECT P.P_Name, C.C_Name FROM program P 
                                                        JOIN category C ON P.C_ID = C.C_ID";
                                                $result = $conn->query($sql);
                                                if ($result->num_rows > 0) {
                                                    while ($row = $result->fetch_assoc()) {
                                                        // Combine program name and category name
                                                        $combined_name = $row['P_Name'] . " - " . $row['C_Name'];
                                                        echo "<option value='" . htmlspecialchars($row['P_Name']) . "'"
                                                             . ($row['P_Name'] == $selected_program_name ? ' selected' : '') . ">"
                                                             . htmlspecialchars($combined_name) . "</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="form-group ml-3">
                                            <label for="status">Status:</label>
                                            <br>
                                            <select name="status" id="status" class="form-control">
                                                <option value="">Pilih Status</option>
                                                <option value="Hadir" <?php echo $selected_status == 'Hadir' ? 'selected' : ''; ?>>Hadir</option>
                                                <option value="Tidak Hadir" <?php echo $selected_status == 'Tidak Hadir' ? 'selected' : ''; ?>>Tidak Hadir</option>
                                            </select>
                                            <button type="submit" name="Search" class="btn btn-info ml-3">Cari</button>
                                            <button type="submit" name="DownloadPDF" class="btn btn-danger ml-3">Muat Turun PDF</button>
                                        </div>
                                    </form>
                                </div>
                                <!-- Display report table -->
                                <div class="mt-4">
                                    <?php echo $html_output; ?>
                                </div>
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

<?php
// Close the database connection at the end of the script
$conn->close();
?>
