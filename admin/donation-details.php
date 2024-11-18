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
    <title>Senarai Sumbangan Peserta</title>
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
                        <h2 class="page-title" style="margin-top:4%">Senarai Sumbangan Peserta</h2>

                        <div class="panel panel-default">
                            <div class="panel-heading">Semua Butiran Sumbangan</div>
                            <div class="panel-body">
                                <!-- Search Form -->
                                <form method="post" action="" class="form-inline">
                                    <div class="form-group">
                                        <label for="program_category" class="sr-only">Nama Program - Kategori</label>
                                        <select class="form-control" id="program_category" name="program_category">
                                            <option value="">Pilih Nama Program</option>
                                            <?php
                                            // Fetch distinct program and category combinations
                                            $stmt = $mysqli->prepare("SELECT DISTINCT CONCAT(program.P_Name, ' - ', category.C_Name) AS ProgramCategory
                                                                      FROM program
                                                                      JOIN category ON program.C_ID = category.C_ID");
                                            $stmt->execute();
                                            $res = $stmt->get_result();
                                            while ($row = $res->fetch_assoc()) {
                                                $selected = (isset($_POST['program_category']) && $_POST['program_category'] == $row['ProgramCategory']) ? 'selected' : '';
                                                echo '<option value="' . htmlspecialchars($row['ProgramCategory']) . '" ' . $selected . '>' . htmlspecialchars($row['ProgramCategory']) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="month" class="sr-only">Bulan</label>
                                        <input type="month" class="form-control" id="month" name="month" placeholder="Bulan" 
                                               value="<?php echo isset($_POST['month']) ? htmlspecialchars($_POST['month']) : ''; ?>">
                                    </div>

                                    <button type="submit" name="search" class="btn btn-primary">Cari</button>
                                </form>
                                <br>

                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Peserta</th>
                                            <th>No.Telefon</th>
                                            <th>Jumlah Sumbangan</th>
                                            <th>Butiran Sumbangan</th>
                                            <th>Kaedah Pembayaran</th>
                                            <th>Tarikh Sumbangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Handle search filter
                                        if (isset($_POST['search'])) {
                                            $program_category = $_POST['program_category'];
                                            $month = isset($_POST['month']) ? $_POST['month'] : '';

                                            // SQL query based on whether a program is selected or not
                                            if (!empty($program_category)) {
                                                $ret = "SELECT program.P_Name, category.C_Name, donation.D_Amount, donation.D_Description, donation.D_Date, donation.D_MethodPay, 
                                                        CONCAT(user.U_Fname, ' ', user.U_Lname) AS UserName, user.U_PhoneNo
                                                        FROM program
                                                        JOIN category ON program.C_ID = category.C_ID
                                                        LEFT JOIN user_program ON program.P_ID = user_program.P_ID
                                                        LEFT JOIN user_donation ON user_program.UP_ID = user_donation.UP_ID
                                                        LEFT JOIN donation ON user_donation.D_ID = donation.D_ID
                                                        LEFT JOIN user ON user_program.U_ID = user.U_ID
                                                        WHERE CONCAT(program.P_Name, ' - ', category.C_Name) = ? 
                                                        AND (DATE_FORMAT(donation.D_Date, '%Y-%m') = ? OR ? = '')
                                                        ORDER BY donation.D_Date DESC";

                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->bind_param('sss', $program_category, $month, $month);
                                            } else {
                                                // If no specific program is selected, show all data
                                                $ret = "SELECT program.P_Name, category.C_Name, donation.D_Amount, donation.D_Description, donation.D_Date, donation.D_MethodPay, 
                                                        CONCAT(user.U_Fname, ' ', user.U_Lname) AS UserName, user.U_PhoneNo
                                                        FROM program
                                                        JOIN category ON program.C_ID = category.C_ID
                                                        LEFT JOIN user_program ON program.P_ID = user_program.P_ID
                                                        LEFT JOIN user_donation ON user_program.UP_ID = user_donation.UP_ID
                                                        LEFT JOIN donation ON user_donation.D_ID = donation.D_ID
                                                        LEFT JOIN user ON user_program.U_ID = user.U_ID
                                                        WHERE (DATE_FORMAT(donation.D_Date, '%Y-%m') = ? OR ? = '')
                                                        ORDER BY donation.D_Date DESC";

                                                $stmt = $mysqli->prepare($ret);
                                                $stmt->bind_param('ss', $month, $month);
                                            }
                                        } else {
                                            // Default query: show all data
                                            $ret = "SELECT program.P_Name, category.C_Name, donation.D_Amount, donation.D_Description, donation.D_Date, donation.D_MethodPay, 
                                                    CONCAT(user.U_Fname, ' ', user.U_Lname) AS UserName, user.U_PhoneNo
                                                    FROM program
                                                    JOIN category ON program.C_ID = category.C_ID
                                                    LEFT JOIN user_program ON program.P_ID = user_program.P_ID
                                                    LEFT JOIN user_donation ON user_program.UP_ID = user_donation.UP_ID
                                                    LEFT JOIN donation ON user_donation.D_ID = donation.D_ID
                                                    LEFT JOIN user ON user_program.U_ID = user.U_ID
                                                    ORDER BY donation.D_Date DESC";

                                            $stmt = $mysqli->prepare($ret);
                                        }

                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $res->fetch_object()) {
                                        ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo $row->UserName; ?></td>
                                                <td><?php echo $row->U_PhoneNo; ?></td>
                                                <td><?php echo 'RM ' . number_format($row->D_Amount, 2); ?></td>
                                                <td><?php echo $row->D_Description; ?></td>
                                                <td><?php echo $row->D_MethodPay; ?></td>
                                                <td><?php echo date('d M, Y h:i A', strtotime($row->D_Date)); ?></td>
                                            </tr>
                                        <?php
                                            $cnt++;
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
