<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
require '../vendor/autoload.php'; // Adjust the path to your PHPMailer autoload file
check_login();

if (isset($_POST['submit'])) {
    try {
        $status = $_POST['status'];
        $comment = $_POST['comment'];
        $id = $_GET['id'];

        // Get the program details based on P_ID and join with category
        $programDetailsQuery = "
            SELECT 
                p.P_Name, 
                p.P_Description, 
                p.P_DateTime, 
                p.P_Capacity, 
                p.P_Location, 
                p.P_Person, 
                c.C_Name 
            FROM 
                program p 
            JOIN 
                category c ON p.C_ID = c.C_ID 
            WHERE 
                p.P_ID = ?";
        $programDetailsStmt = $mysqli->prepare($programDetailsQuery);
        $programDetailsStmt->bind_param('i', $id);
        $programDetailsStmt->execute();
        $programDetailsResult = $programDetailsStmt->get_result();
        $row = $programDetailsResult->fetch_assoc();

        // Assign program details to variables (including category name)
        $pname = $row['P_Name'];
        $category_name = $row['C_Name'];
        $pdesc = $row['P_Description'];
        $pdate = $row['P_DateTime'];
        $pcapacity = $row['P_Capacity'];
        $plocate = $row['P_Location'];
        $pperson = $row['P_Person'];

        // Get the current status and approved date
        $currentStatusQuery = "SELECT P_Status, P_ApprovedDate FROM program WHERE P_ID = ?";
        $currentStatusStmt = $mysqli->prepare($currentStatusQuery);
        $currentStatusStmt->bind_param('i', $id);
        $currentStatusStmt->execute();
        $currentStatusResult = $currentStatusStmt->get_result();
        $row = $currentStatusResult->fetch_assoc();
        $currentStatus = $row['P_Status'];
        $currentApprovedDate = $row['P_ApprovedDate'];

        // Check if new status is different from current status
        if ($status === $currentStatus) {
            echo "<script>alert('Status baru sama dengan status semasa. Tiada kemaskini diperlukan.');</script>";
        } else {
            // Update user program status, comment, and approved date
            $updateQuery = "UPDATE program SET P_Status=?, P_Comment=?, P_ApprovedDate=NOW() WHERE P_ID=?";
            $updateStmt = $mysqli->prepare($updateQuery);
            $updateStmt->bind_param('ssi', $status, $comment, $id);

            if ($updateStmt->execute()) {
                // Fetch the email of the logged-in user based on session ID
                $logged_in_user_email_query = "SELECT U_Email FROM user WHERE U_ID = ?";
                $stmt_logged_in_email = $mysqli->prepare($logged_in_user_email_query);
                $stmt_logged_in_email->bind_param('s', $_SESSION['id']);
                $stmt_logged_in_email->execute();
                $result_logged_in_email = $stmt_logged_in_email->get_result();
                $logged_in_user_email_row = $result_logged_in_email->fetch_assoc();
                $logged_in_user_email = $logged_in_user_email_row['U_Email'];

                // Fetch all staff emails
                $staff_email_query = "SELECT U_Email FROM user WHERE U_Roles = 'staff'";
                $stmt_staff_email = $mysqli->prepare($staff_email_query);
                $stmt_staff_email->execute();
                $result_staff_email = $stmt_staff_email->get_result();

                // SMTP configuration
                $mail = new PHPMailer\PHPMailer\PHPMailer();
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'syakiraimn2@gmail.com'; // Replace with your SMTP username
                $mail->Password = 'yjoi uies iodo oohj'; // Replace with your SMTP password
                $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('syakiraimn2@gmail.com', 'E-Masjidlite');

                // Customize email content based on the status
                switch ($status) {
                    case 'Diluluskan':
                        $mail->Subject = 'Permohonan Program Diluluskan';
                        $mail->Body = "
                            <h2>Permohonan Program Baharu!</h2>
                            <p>Tahniah! status permohonan program berikut telah diluluskan di platform E-masjidlite.</p>
                            <hr>
                            <h2>Butiran Program:</h2>
                            <p><strong>Nama Program:</strong> $pname</p>
                            <p><strong>Kategori:</strong> $category_name</p>
                            <p><strong>Penerangan:</strong> $pdesc</p>
                            <p><strong>Tarikh & Masa Program:</strong> " . date('F j, Y g:i A', strtotime($pdate)) . "</p>
                            <p><strong>Lokasi:</strong> $plocate</p>
                            <p><strong>Kapasiti Program:</strong> $pcapacity orang peserta</p>
                            <p><strong>Penyelaras:</strong> $pperson</p>
                            <hr>
                            <p>Permohonan program ini telah berjaya diluluskan. Sebarang maklumat akan dikemaskini di platform E-masjidlite. </p>
                            <p>Sekian, terima kasih.</p>
                            <p>Admin E-masjidlite</p>
                        ";

                        $mail->AltBody = "Permohonan program berikut telah berjaya diluluskan di platform E-masjidlite.\n\n";
                        $mail->AltBody .= "Butiran Program:\n";
                        $mail->AltBody .= "Nama Program: $pname\n";
                        $mail->AltBody .= "Kategori: $category_name\n";
                        $mail->AltBody .= "Penerangan: $pdesc\n";
                        $mail->AltBody .= "Tarikh & Masa Program: " . date('F j, Y g:i A', strtotime($pdate)) . "\n";
                        $mail->AltBody .= "Lokasi: $plocate\n";
                        $mail->AltBody .= "Kapasiti Program: $pcapacity orang peserta\n";
                        $mail->AltBody .= "Penyelaras: $pperson\n\n";
                        $mail->AltBody .= "Permohonan program ini telah berjaya diluluskan.\n\n";
                        $mail->AltBody .= "Sekian, terima kasih.\nAdmin E-masjidlite";
                        break;

                    case 'Ditolak':
                        $mail->Subject = 'Permohonan Program Ditolak';
                        $mail->Body = "
                            <p>Harap Maaf, status permohonan program berikut telah ditolak di platform E-masjidlite.</p>
                            <hr>
                            <h2>Butiran Program:</h2>
                            <p><strong>Nama Program:</strong> $pname</p>
                            <p><strong>Kategori:</strong> $category_name</p>
                            <p><strong>Penerangan:</strong> $pdesc</p>
                            <p><strong>Tarikh & Masa Program:</strong> " . date('F j, Y g:i A', strtotime($pdate)) . "</p>
                            <p><strong>Lokasi:</strong> $plocate</p>
                            <p><strong>Kapasiti Program:</strong> $pcapacity orang peserta</p>
                            <p><strong>Penyelaras:</strong> $pperson</p>
                            <hr>
                            <p>Sila semak komen untuk maklumat lanjut di platform E-masjidlite.</p>
                            <p>Sekian, terima kasih.</p>
                            <p>Admin E-masjidlite</p>
                        ";

                        $mail->AltBody = "Maaf, permohonan program berikut telah ditolak di platform E-masjidlite.\n\n";
                        $mail->AltBody .= "Butiran Program:\n";
                        $mail->AltBody .= "Nama Program: $pname\n";
                        $mail->AltBody .= "Kategori: $category_name\n";
                        $mail->AltBody .= "Penerangan: $pdesc\n";
                        $mail->AltBody .= "Tarikh & Masa: " . date('F j, Y g:i A', strtotime($pdate)) . "\n";
                        $mail->AltBody .= "Lokasi: $plocate\n";
                        $mail->AltBody .= "Kapasiti: $pcapacity orang peserta\n";
                        $mail->AltBody .= "Penyelaras: $pperson\n\n";
                        $mail->AltBody .= "Sekian, terima kasih.\nAdmin E-masjidlite";
                        break;

                    case 'Meminta Semakan / Kemaskini':
                        $mail->Subject = 'Permohonan Program Memerlukan Semakan / Kemaskini';
                        $mail->Body = "
                            <p>Harap Maaf, Status permohonan program berikut memerlukan semakan atau kemaskini semula di platform E-masjidlite.</p>
                            <hr>
                            <h2>Butiran Program:</h2>
                            <p><strong>Nama Program:</strong> $pname</p>
                            <p><strong>Kategori:</strong> $category_name</p>
                            <p><strong>Penerangan:</strong> $pdesc</p>
                            <p><strong>Tarikh & Masa Program:</strong> " . date('F j, Y g:i A', strtotime($pdate)) . "</p>
                            <p><strong>Lokasi:</strong> $plocate</p>
                            <p><strong>Kapasiti Program:</strong> $pcapacity orang peserta</p>
                            <p><strong>Penyelaras:</strong> $pperson</p>
                            <hr>
                            <p>Sila semak komen untuk maklumat lanjut di platform E-masjidlite.</p>
                            <p>Sekian, terima kasih.</p>
                            <p>Admin E-masjidlite</p>
                        ";

                        $mail->AltBody = "Permohonan program berikut memerlukan semakan atau kemaskini di platform E-masjidlite.\n\n";
                        $mail->AltBody .= "Butiran Program:\n";
                        $mail->AltBody .= "Nama Program: $pname\n";
                        $mail->AltBody .= "Kategori: $category_name\n";
                        $mail->AltBody .= "Penerangan: $pdesc\n";
                        $mail->AltBody .= "Tarikh & Masa: " . date('F j, Y g:i A', strtotime($pdate)) . "\n";
                        $mail->AltBody .= "Lokasi: $plocate\n";
                        $mail->AltBody .= "Kapasiti: $pcapacity orang peserta\n";
                        $mail->AltBody .= "Penyelaras: $pperson\n\n";
                        $mail->AltBody .= "Sila semak komen untuk maklumat lanjut.\n\n";
                        $mail->AltBody .= "Sekian, terima kasih.\nAdmin E-masjidlite";
                        break;

                    default:
                        $mail->Subject = 'Status Permohonan Program Dikemaskini';
                        $mail->Body = "
                            <p>Status permohonan program telah dikemaskini di platform E-masjidlite.</p>
                            <hr>
                            <h2>Butiran Program:</h2>
                            <p><strong>Nama Program:</strong> $pname</p>
                            <p><strong>Kategori:</strong> $category_name</p>
                            <p><strong>Penerangan:</strong> $pdesc</p>
                            <p><strong>Tarikh & Masa:</strong> " . date('F j, Y g:i A', strtotime($pdate)) . "</p>
                            <p><strong>Lokasi:</strong> $plocate</p>
                            <p><strong>Kapasiti:</strong> $pcapacity orang peserta</p>
                            <p><strong>Penyelaras:</strong> $pperson</p>
                            <hr>
                            <p>Sekian, terima kasih.</p>
                            <p>Admin E-masjidlite</p>
                        ";

                        $mail->AltBody = "Status permohonan program telah dikemaskini di platform E-masjidlite.\n\n";
                        $mail->AltBody .= "Butiran Program:\n";
                        $mail->AltBody .= "Nama Program: $pname\n";
                        $mail->AltBody .= "Kategori: $category_name\n";
                        $mail->AltBody .= "Penerangan: $pdesc\n";
                        $mail->AltBody .= "Tarikh & Masa: " . date('F j, Y g:i A', strtotime($pdate)) . "\n";
                        $mail->AltBody .= "Lokasi: $plocate\n";
                        $mail->AltBody .= "Kapasiti: $pcapacity orang peserta\n";
                        $mail->AltBody .= "Penyelaras: $pperson\n\n";
                        $mail->AltBody .= "Sekian, terima kasih.\nAdmin E-masjidlite";
                        break;
                }

                // Send email to each staff member
                while ($staff_email_row = $result_staff_email->fetch_assoc()) {
                    $staff_email = $staff_email_row['U_Email'];
                    $mail->addAddress($staff_email);

                    if (!$mail->send()) {
                        echo "Email could not be sent to {$staff_email}. Mailer Error: {$mail->ErrorInfo}<br>";
                    } else {
                        
                    }

                    // Clear all recipients for the next iteration
                    $mail->clearAddresses();
                }

                // Send email to the logged-in user
                $mail->addAddress($logged_in_user_email);
                if (!$mail->send()) {
                    echo "Email could not be sent to the logged-in user ({$logged_in_user_email}). Mailer Error: {$mail->ErrorInfo}<br>";
                } else {
                    
                }

                echo "<script>alert('Status dan komen telah berjaya dikemaskini. Email telah dihantar.');</script>";
                echo "<script>window.location.href='program-details.php?id=".$id."';</script>";
            } else {
                echo "Failed to execute update statement.";
            }
        }
    } catch(Exception $e) {
        echo $e->getMessage();
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

    <title>Kemaskini Status</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
    <script type="text/javascript" src="js/jquery-1.11.3-jquery.min.js"></script>
    <script type="text/javascript" src="js/validation.min.js"></script>
</head>
<body>
    <?php include('includes/header.php');?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php');?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title"></h2>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Kemaskini Status dan Komen</div>
                                    <div class="panel-body">
                                        <form method="post" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']; ?>">
                                            <?php
                                            $id = $_GET['id'];
                                            $ret = "SELECT P_Status, P_ApprovedDate FROM program WHERE P_ID=?";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->bind_param('i', $id);
                                            $stmt->execute();
                                            $res = $stmt->get_result();

                                            if($row = $res->fetch_object()) {
                                            ?>
                                            <div class="hr-dashed"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Status Semasa</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" value="<?php echo $row->P_Status; ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Status Baru</label>
                                                <div class="col-sm-8">
                                                    <select class="form-control" name="status" required>
                                                        <option value="">Pilih Status</option>
                                                        <option value="Diluluskan">Diluluskan</option>
                                                        <option value="Ditolak">Ditolak</option>
                                                        <option value="Meminta Semakan / Kemaskini">Meminta Semakan / Kemaskini</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Komen</label>
                                                <div class="col-sm-8">
                                                    <textarea class="form-control" name="comment" rows="5" placeholder="Masukkan komen anda di sini"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Tarikh</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" value="<?php echo $row->P_ApprovedDate; ?>" readonly>
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div class="col-sm-8 col-sm-offset-2">
                                                <input class="btn btn-primary" type="submit" name="submit" value="Kemaskini Status dan Komen">
                                                <a class="btn btn-danger" href="program-details.php?id=<?php echo $id; ?>" onclick="return confirmCancel();">Batal</a>
                                            </div>
                                        </form>
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

    <script>
    function confirmCancel() {
        return confirm("Adakah anda pasti mahu membatalkan? Sebarang perubahan yang belum disimpan akan hilang.");
    }
    </script>
</body>
</html>
