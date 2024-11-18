<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $programId = $_POST['program_id'];
    $userId = $_SESSION['id'];

    if (isset($_POST['join'])) {
        // Mula transaksi
        $mysqli->begin_transaction();

        try {
            // Semak jika pengguna sudah menyertai program ini
            $checkQuery = "SELECT UP_Status FROM user_program WHERE U_ID = ? AND P_ID = ?";
            $checkStmt = $mysqli->prepare($checkQuery);
            $checkStmt->bind_param('ii', $userId, $programId);
            $checkStmt->execute();
            $checkStmt->store_result();

            if ($checkStmt->num_rows > 0) {
                // Pengguna sudah menyertai program ini, berikan makluman
                echo "<script>alert('Anda sudah menyertai program ini.');</script>";
            } else {
                // Masukkan permohonan menyertai ke dalam user_program
                $insertQuery = "INSERT INTO user_program (U_ID, UP_Status, P_ID) VALUES (?, 'Tidak Hadir', ?)";
                $insertStmt = $mysqli->prepare($insertQuery);
                $insertStmt->bind_param('ii', $userId, $programId);
                $insertStmt->execute();
                $insertStmt->close();

                // Kemaskini kapasiti program
                $updateQuery = "UPDATE program SET P_Capacity = P_Capacity - 1 WHERE P_ID = ?";
                $updateStmt = $mysqli->prepare($updateQuery);
                $updateStmt->bind_param('i', $programId);
                $updateStmt->execute();
                $updateStmt->close();

                // Commit transaksi
                $mysqli->commit();

                // Set mesej berjaya dan redirect
                $_SESSION['success_message'] = "Anda berjaya mendaftar untuk program ini. Sila hadir pada tarikh dan masa yang di tetapkan.";
                header("Location: user-viewprogram.php");
                exit();
            }
        } catch (Exception $e) {
            // Rollback transaksi jika ada kesilapan
            $mysqli->rollback();
            echo "Ralat: " . $e->getMessage();
        }
    } elseif (isset($_POST['unjoin'])) {
        // Mula transaksi
        $mysqli->begin_transaction();

        try {
            // Padam permohonan menyertai dari user_program
            $deleteQuery = "DELETE FROM user_program WHERE U_ID = ? AND P_ID = ?";
            $deleteStmt = $mysqli->prepare($deleteQuery);
            $deleteStmt->bind_param('ii', $userId, $programId);
            $deleteStmt->execute();
            $deleteStmt->close();

            // Kemaskini kapasiti program
            $updateQuery = "UPDATE program SET P_Capacity = P_Capacity + 1 WHERE P_ID = ?";
            $updateStmt = $mysqli->prepare($updateQuery);
            $updateStmt->bind_param('i', $programId);
            $updateStmt->execute();
            $updateStmt->close();

            // Commit transaksi
            $mysqli->commit();

            // Set mesej berjaya dan redirect
            $_SESSION['success_message'] = "Anda berjaya membatalkan pendaftaran dari program ini.";
            header("Location: user-viewprogram.php");
            exit();
        } catch (Exception $e) {
            // Rollback transaksi jika ada kesilapan
            $mysqli->rollback();
            echo "Ralat: " . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="ms" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Maklumat Program</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
    <script>
        function showAlert(message) {
            alert(message);
        }
    </script>
</head>
<body>
    <?php include('includes/header.php');?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php');?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title" style="margin-top:4%">Maklumat Program</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Semua Maklumat Program</div>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Bil.</th>
                                            <th>Nama Program</th>
                                            <th>Kategori</th>
                                            <th>Gambar</th>
                                            <th>Penerangan</th>
                                            <th>Tarikh & Masa Program</th>
                                            <th>Kapasiti Tersedia</th>
                                            <th>Lokasi</th>
                                            <th>Penyelaras</th>
                                            <th>Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Dapatkan senarai program yang belum dihadiri oleh pengguna
                                        $query = "SELECT program.*, category.C_Name, user_program.UP_Status
                                                  FROM program
                                                  JOIN category ON program.C_ID = category.C_ID
                                                  LEFT JOIN user_program ON program.P_ID = user_program.P_ID AND user_program.U_ID = ?
                                                  WHERE program.P_Remark = 'published' AND (user_program.UP_Status IS NULL OR user_program.UP_Status != 'Hadir')
                                                  ORDER BY program.P_DateTime ASC";
                                        $stmt = $mysqli->prepare($query);
                                        $stmt->bind_param('i', $_SESSION['id']);
                                        $stmt->execute();
                                        $result = $stmt->get_result();

                                        if ($result && $result->num_rows > 0) {
                                            $count = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                ?>
                                                <tr>
                                                    <td><?php echo $count; ?></td>
                                                    <td><?php echo $row['P_Name']; ?></td>
                                                    <td><?php echo $row['C_Name']; ?></td>
                                                    <td><img src="../staff/<?php echo $row['P_Image']; ?>" width="200px" height="120px"></td>
                                                    <td><?php echo $row['P_Description']; ?></td>
                                                    <td><?php echo date('d M, Y h:i A', strtotime($row['P_DateTime'])); ?></td>
                                                    <td><?php echo $row['P_Capacity']; ?></td>
                                                    <td><?php echo $row['P_Location']; ?></td>
                                                    <td><?php echo $row['P_Person']; ?></td>
                                                    <td>
                                                        <form method="POST" action="">
                                                            <input type="hidden" name="program_id" value="<?php echo $row['P_ID']; ?>">
                                                            <?php
                                                            // Semak jika pengguna sudah menyertai program ini
                                                            if ($row['UP_Status'] === 'Hadir') {
                                                                // Pengguna hadir, paparkan status kehadiran atau tindakan yang berkaitan
                                                                echo "Hadir";
                                                            } elseif ($row['UP_Status'] === 'Tidak Hadir') {
                                                                // Pengguna tidak hadir, papar butang Batal Sertai
                                                                ?>
                                                                <button type="submit" name="unjoin" class="btn btn-danger">Batal Sertai</button>
                                                                <?php
                                                            } else {
                                                                // Pengguna belum menyertai
                                                                if ($row['P_Capacity'] > 0) {
                                                                    // Papar butang Sertai jika kapasiti masih ada
                                                                    ?>
                                                                    <button type="submit" name="join" class="btn btn-primary">Sertai</button>
                                                                    <?php
                                                                } else {
                                                                    // Kapasiti penuh, papar mesej
                                                                    echo "Slot Penuh";
                                                                }
                                                            }
                                                            ?>
                                                        </form>
                                                    </td>
                                                </tr>
                                                <?php
                                                $count++;
                                            }
                                            $result->free();
                                        } else {
                                            echo "<tr><td colspan='10'>Tiada program dijumpai.</td></tr>";
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

    <!-- Papar alert jika terdapat mesej berjaya -->
    <?php
    if (isset($_SESSION['success_message'])) {
        echo "<script>showAlert('" . $_SESSION['success_message'] . "');</script>";
        unset($_SESSION['success_message']);
    }
    ?>

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
