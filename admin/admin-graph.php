<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Initialize variables for month and year, default to current month and year
$month = isset($_GET['month']) ? $_GET['month'] : date('m');
$year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Prepare SQL query to get total donations by program name, filtered by month and year, and sorted by the month of donation
$sql = "SELECT P.P_Name, SUM(D.D_Amount) AS TotalDonation
        FROM program P
        INNER JOIN user_program UP ON P.P_ID = UP.P_ID
        INNER JOIN user_donation UD ON UP.UP_ID = UD.UP_ID
        INNER JOIN donation D ON UD.D_ID = D.D_ID
        WHERE MONTH(D.D_Date) = ? AND YEAR(D.D_Date) = ?
        GROUP BY P.P_Name
        ORDER BY MONTH(D.D_Date), P.P_Name";  // Sorting by month and then program name

$stmt = $mysqli->prepare($sql);
if ($stmt === false) {
    die('Failed to prepare statement: ' . htmlspecialchars($mysqli->error));
}

$stmt->bind_param('ii', $month, $year);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die('Query failed: ' . htmlspecialchars($mysqli->error));
}

$programNames = [];
$totalDonations = [];

while ($row = $result->fetch_assoc()) {
    $programNames[] = $row['P_Name'];
    $totalDonations[] = $row['TotalDonation'] ? $row['TotalDonation'] : 0;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="ms" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Jumlah Sumbangan Mengikut Program</title>

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
    <?php include("includes/header.php"); ?>

    <div class="ts-main-content">
        <?php include("includes/sidebar.php"); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 style="margin-top: 6%">Laporan Sumbangan</h2>

                        <!-- Total counts and statistics panels -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-body bk-success text-light">
                                        <div class="stat-panel text-center">
                                            <div class="stat-panel-number h1"><?php echo count($programNames); ?></div>
                                            <div class="stat-panel-title text-uppercase">Program</div>
                                        </div>
                                    </div>
                                    <a href="program.php" class="block-anchor panel-footer"></a>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="panel panel-default">
                                    <div class="panel-body bk-success text-light">
                                        <div class="stat-panel text-center">
                                            <div class="stat-panel-number h1"><?php echo 'RM ' . number_format(array_sum($totalDonations), 2); ?></div>
                                            <div class="stat-panel-title text-uppercase">Jumlah Sumbangan</div>
                                        </div>
                                    </div>
                                    <a href="donation-details.php" class="block-anchor panel-footer"></a>
                                </div>
                            </div>
                        </div>

                        <!-- Filter Form -->
                        <form method="GET" action="" class="form-inline mb-4">
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="month" class="sr-only">Bulan</label>
                                <select name="month" id="month" class="form-control">
                                    <?php for ($m = 1; $m <= 12; $m++): ?>
                                        <option value="<?php echo $m; ?>" <?php if ($m == $month) echo 'selected'; ?>><?php echo date('F', mktime(0, 0, 0, $m, 1)); ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="form-group mx-sm-3 mb-2">
                                <label for="year" class="sr-only">Tahun</label>
                                <select name="year" id="year" class="form-control">
                                    <?php for ($y = 2020; $y <= date('Y'); $y++): ?>
                                        <option value="<?php echo $y; ?>" <?php if ($y == $year) echo 'selected'; ?>><?php echo $y; ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mb-2">Cari</button>
                        </form>

                        <!-- Chart Section -->
                        <h2 style="margin-top: 4%">Jumlah Sumbangan Mengikut Program</h2>
                        <div style="width: 60%; height: 400px; text-align: center">
                            <canvas id="chartjs_bar"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap-select.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="js/fileinput.js"></script>
    <script src="js/chartData.js"></script>
    <script src="js/main.js"></script>

    <!-- Chart Script -->
    <script>
        $(document).ready(function () {
            var ctxBar = document.getElementById("chartjs_bar").getContext('2d');
            var myBarChart = new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: <?php echo json_encode($programNames); ?>,
                    datasets: [{
                        backgroundColor: [
                            "#f0ad7d", "#eaf07d", "#8dafc7", "#d3a3f7",
                            "#92c78d", "#5969ff", "#ff407b", "#25d5f2",
                            "#ffc750", "#2ec551", "#7040fa", "#ff004e"
                        ],
                        data: <?php echo json_encode($totalDonations); ?>,
                    }]
                },
                options: {
                    legend: {
                        display: true,
                        position: 'bottom',
                        labels: {
                            fontColor: '#71748d',
                            fontFamily: 'Circular Std Book',
                            fontSize: 14,
                        }
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Program'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Jumlah Sumbangan (RM)'
                            },
                            ticks: {
                                beginAtZero: true,
                                precision: 0,
                                stepSize: 100, // Adjust as needed based on your data range
                                callback: function(value, index, values) {
                                    return 'RM ' + value.toFixed(2);
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                return 'RM ' + tooltipItem.yLabel.toFixed(2);
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>

</html>
