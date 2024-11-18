<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
require '../vendor/autoload.php'; // Adjust the path to your PHPMailer

if (isset($_POST['btn_insert'])) {
    try {
        // Sanitize inputs to prevent SQL injection
        $pname = $_POST['pn'];
        $pdesc = $_POST['pdesc'];
        $pdate = date('Y-m-d H:i:s', strtotime($_POST['pdate']));
        $pcapacity = $_POST['pcapacity'];
        $plocate = $_POST['plocate'];
        $pperson = $_POST['pperson'];
        $cid = $_POST['cid'];

        // Initialize variables for file paths
        $uploadFileTo = '';
        $uploadImageTo = '';
        $uploadCertTo = '';

        $errors = [];

        // Handle File Uploads
        if (isset($_FILES['pfile']) && $_FILES['pfile']['name']) {
            $fileExt = strtolower(pathinfo($_FILES['pfile']['name'], PATHINFO_EXTENSION));
            $file_name = round(microtime(true)) . '.' . $fileExt;
            $uploadFileTo = "file/" . $file_name;
            if (!move_uploaded_file($_FILES['pfile']['tmp_name'], $uploadFileTo)) {
                $errors[] = "Muat naik fail gagal.";
            }
        }

        if (isset($_FILES['pcert']) && $_FILES['pcert']['name']) {
            $certExt = strtolower(pathinfo($_FILES['pcert']['name'], PATHINFO_EXTENSION));
            $cert_name = round(microtime(true)) . '.' . $certExt;
            $uploadCertTo = "cert/" . $cert_name;
            if (!move_uploaded_file($_FILES['pcert']['tmp_name'], $uploadCertTo)) {
                $errors[] = "Muat naik sijil gagal.";
            }
        }

        if (isset($_FILES['pimg']) && $_FILES['pimg']['name']) {
            $imageExt = strtolower(pathinfo($_FILES['pimg']['name'], PATHINFO_EXTENSION));
            if (!in_array($imageExt, ['jpg', 'jpeg', 'png'])) {
                $errors[] = "File gambar mestilah dalam format .jpg, .jpeg, atau .png";
            }
            if ($_FILES['pimg']['size'] > 2097152) {
                $errors[] = "Saiz gambar mestilah tidak melebihi 2MB";
            }
            if (empty($errors)) {
                $image_name = round(microtime(true)) . '.' . $imageExt;
                $uploadImageTo = "image/" . $image_name;
                if (!move_uploaded_file($_FILES['pimg']['tmp_name'], $uploadImageTo)) {
                    $errors[] = "Muat naik gambar gagal.";
                }
            }
        }

        // Insert query construction with P_Status
        if (empty($errors)) {
            $pstatus = 'dalam semakan'; // Default status

            $query = "INSERT INTO program (P_Name, P_Description, P_Image, P_File, P_DateTime, P_Capacity, P_Location, P_Person, P_Cert, C_ID, P_Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('sssssisssis', $pname, $pdesc, $uploadImageTo, $uploadFileTo, $pdate, $pcapacity, $plocate, $pperson, $uploadCertTo, $cid, $pstatus);
            $stmt->execute();

            // Fetch user's email from the database based on session
            $user_email_query = "SELECT U_Email FROM user WHERE U_ID = ?";
            $stmt_email = $mysqli->prepare($user_email_query);
            $stmt_email->bind_param('s', $_SESSION['id']);
            $stmt_email->execute();
            $result_email = $stmt_email->get_result();
            $user_email_row = $result_email->fetch_assoc();
            $recipient_email = $user_email_row['U_Email'];

            // Fetch category name from the database using a JOIN
            $category_query = "SELECT c.C_Name FROM category c JOIN program p ON c.C_ID = p.C_ID WHERE p.C_ID = ?";
            $stmt_category = $mysqli->prepare($category_query);
            $stmt_category->bind_param('i', $cid);
            $stmt_category->execute();
            $result_category = $stmt_category->get_result();
            $category_row = $result_category->fetch_assoc();
            $category_name = $category_row['C_Name'];

            // SMTP configuration and sending email
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
            $mail->SMTPAuth = true;
            $mail->Username = 'syakiraimn2@gmail.com'; // Replace with your SMTP username
            $mail->Password = 'yjoi uies iodo oohj'; // Replace with your SMTP password
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('syakiraimn2@gmail.com', 'E-Masjidlite');
            $mail->addAddress($recipient_email);

            $mail->isHTML(true);
            $mail->Subject = 'Permohonan Program Baharu';
            $mail->Body = "
                <h2>Permohonan Program Baharu!</h2>
                <p>Anda telah berjaya membuat permohonan program baru di platform E-masjidlite.</p>
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
                <p>Permohonan program ini telah berjaya dimasukkan dan kini sedang dalam proses semakan.</p>
                <p>Sekian, terima kasih.</p>
                <p>Admin E-masjidlite</p>
            ";

            if (!$mail->send()) {
                echo "<script>alert('Penghantaran email gagal. Kesalahan: {$mail->ErrorInfo}');</script>";
            } else {
                echo "<script>alert('Permohonan Program berjaya dihantar dan dalam proses untuk semakan. Pemberitahuan email telah dihantar.');</script>";
                echo "<script>window.location.href = 'program-details.php';</script>";
                exit;
            }
        } else {
            foreach ($errors as $error) {
                echo "<script>alert('$error');</script>";
            }
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}
?>

<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Permohonan Program</title>
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
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title" style="margin-top:4%">Permohonan Program</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Permohonan Program </div>
                            <div class="panel-body">
                                <form method="post" class="form-horizontal" enctype="multipart/form-data">
                                  <div class="form-group">
                                         <label class="col-sm-2 control-label" style="color: red;"><span style="color: red;">*</span> Required</label>
                                        <div class="col-sm-8">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Nama Program <span style="color: red;">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="pn" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Kategori <span style="color: red;">*</span></label>
                                        <div class="col-sm-8">
                                            <select name="cid" id="cid" class="form-control" required>
                                                <option value="">Pilih Kategori</option>
                                                <?php 
                                                $query = "SELECT * FROM category";
                                                $stmt = $mysqli->prepare($query);
                                                $stmt->execute();
                                                $res = $stmt->get_result();
                                                while ($row = $res->fetch_object()) {
                                                    echo "<option value='" . $row->C_ID . "'>" . htmlspecialchars($row->C_Name) . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Penerangan <span style="color: red;">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="pdesc" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Gambar </label>
                                        <div class="col-sm-8">
                                            <input type="file" name="pimg" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Kertas Kerja <span style="color: red;">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="file" name="pfile" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Tarikh & Masa Program <span style="color: red;">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="datetime-local" name="pdate" class="form-control" id="datepicker" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Kapasiti Program <span style="color: red;">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="number" name="pcapacity" class="form-control" min="1" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Lokasi <span style="color: red;">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="plocate" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Penyelaras <span style="color: red;">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="pperson" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Sijil (Pilihan)</label>
                                        <div class="col-sm-8">
                                            <input type="file" name="pcert" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-9 m-t-15">
                                            <input type="submit" name="btn_insert" class="btn btn-success" value="Mohon">
                                            <a href="program-details.php" class="btn btn-danger">Batal</a>
                                        </div>
                                    </div>
                                </form>
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
            document.addEventListener('DOMContentLoaded', function() {
                const datepicker = document.getElementById('datepicker');
                const today = new Date();
                const tomorrow = new Date(today);
                tomorrow.setDate(tomorrow.getDate() + 1);
                const yyyy = tomorrow.getFullYear();
                const mm = String(tomorrow.getMonth() + 1).padStart(2, '0');
                const dd = String(tomorrow.getDate()).padStart(2, '0');
                const minDate = `${yyyy}-${mm}-${dd}T00:00`;
                datepicker.setAttribute('min', minDate);
            });
        </script>
    </body>
</html>
