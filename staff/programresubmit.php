<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
require '../vendor/autoload.php'; // Sesuaikan path ke PHPMailer anda

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['btn_update'])) {
    $mysqli->begin_transaction();
    try {
        $pid = $_POST['pid'];
        $pname = $_POST['pn'];
        $pdesc = $_POST['pdesc'];
        $pdate = date('Y-m-d H:i:s', strtotime($_POST['pdate']));
        $pcapacity = $_POST['pcapacity'];
        $plocate = $_POST['plocate'];
        $pperson = $_POST['pperson'];
        $pcomment = $_POST['pcomment'];
        $cid = $_POST['cid'];

        // Dapatkan butiran program sedia ada untuk menguruskan path fail
        $query_fetch = "SELECT * FROM program WHERE P_ID=?";
        $stmt_fetch = $mysqli->prepare($query_fetch);
        $stmt_fetch->bind_param('i', $pid);
        $stmt_fetch->execute();
        $result_fetch = $stmt_fetch->get_result();
        $program = $result_fetch->fetch_assoc();

        $existing_image = $program['P_Image'];
        $existing_file = $program['P_File'];
        $existing_cert = $program['P_Cert'];

        $uploadFileTo = $existing_file;
        $uploadImageTo = $existing_image;
        $uploadCertTo = $existing_cert;

        $errors = [];

        // Pengendalian Muat Naik Fail jika fail baru dimuat naik
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
                $errors[] = "Sambungan gambar mesti .jpg, .jpeg, atau .png";
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

        // Pembinaan query kemaskini
        if (empty($errors)) {
            // Semak jika status perlu dikemaskini kepada "Dalam Semakan"
            $currentStatus = $program['P_Status'];
            if ($currentStatus !== "dalam semakan") {
                $query_status = "UPDATE program SET P_Status='dalam semakan' WHERE P_ID=?";
                $stmt_status = $mysqli->prepare($query_status);
                $stmt_status->bind_param('i', $pid);
                $stmt_status->execute();
            }

            // Kemaskini semua medan lain
            $query_update = "UPDATE program SET P_Name=?, P_Description=?, P_Image=?, P_File=?, P_DateTime=?, P_Capacity=?, P_Location=?, P_Person=?, P_Cert=?, P_CommentUpdate=?, C_ID=? WHERE P_ID=?";
            $stmt_update = $mysqli->prepare($query_update);
            $stmt_update->bind_param('sssssissssii', $pname, $pdesc, $uploadImageTo, $uploadFileTo, $pdate, $pcapacity, $plocate, $pperson, $uploadCertTo, $pcomment, $cid, $pid);
            $stmt_update->execute();

            // Dapatkan emel pengguna dari database berdasarkan sesi
            $user_email_query = "SELECT U_Email FROM user WHERE U_ID = ?";
            $stmt_email = $mysqli->prepare($user_email_query);
            $stmt_email->bind_param('s', $_SESSION['id']);
            $stmt_email->execute();
            $result_email = $stmt_email->get_result();
            $user_email_row = $result_email->fetch_assoc();
            $recipient_email = $user_email_row['U_Email'];

            // Dapatkan nama kategori dari database menggunakan JOIN
            $category_query = "SELECT c.C_Name FROM category c JOIN program p ON c.C_ID = p.C_ID WHERE p.C_ID = ?";
            $stmt_category = $mysqli->prepare($category_query);
            $stmt_category->bind_param('i', $cid);
            $stmt_category->execute();
            $result_category = $stmt_category->get_result();
            $category_row = $result_category->fetch_assoc();
            $category_name = $category_row['C_Name'];

            // Konfigurasi SMTP dan menghantar emel
            $mail = new PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Gantikan dengan pelayan SMTP anda
            $mail->SMTPAuth = true;
            $mail->Username = 'syakiraimn2@gmail.com'; // Gantikan dengan nama pengguna SMTP anda
            $mail->Password = 'yjoi uies iodo oohj'; // Gantikan dengan kata laluan SMTP anda
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('syakiraimn2@gmail.com', 'E-Masjidlite');
            $mail->addAddress($recipient_email);

            $mail->isHTML(true);
            $mail->Subject = 'Permohonan Program dikemaskini - No Reply'; // Updated email subject;
            $mail->Body = "
                <h2>Permohonan Program dikemaskini</h2>
                <p>Permohonan program anda telah berjaya dikemaskini dan sedang dalam proses semakan.</p>
                <hr>
                <h2>Butiran Program:</h2>
                <p><strong>Nama Program:</strong> $pname</p>
                <p><strong>Kategori:</strong> $category_name</p>
                <p><strong>Penerangan:</strong> $pdesc</p>
                <p><strong>Tarikh & Masa Program:</strong> " . date('j F Y, g:i A', strtotime($pdate)) . "</p>
                <p><strong>Lokasi:</strong> $plocate</p>
                <p><strong>Kapasiti Program:</strong> $pcapacity orang peserta</p>
                <p><strong>Penyelaras:</strong> $pperson</p>
                <hr>
                <p>Terima kasih atas keprihatinan anda. Status program ini akan dikemaskini dengan segera.</p>
                <p>Sekian, terima kasih.</p>
                <p>Admin E-Masjidlite</p>
            ";

            if ($mail->send()) {
                $mysqli->commit();
                echo "<script>alert('Program berjaya dikemaskini dan dalam proses semakan.');</script>";
                header('Refresh:0.1; url=program-details.php?id=' . $pid);
            } else {
                $mysqli->rollback();
                echo "<script>alert('Penghantaran e-mel gagal. Sila cuba lagi.');</script>";
            }
        } else {
            foreach ($errors as $error) {
                echo "<script>alert('$error');</script>";
            }
        }
    } catch (Exception $e) {
        $mysqli->rollback();
        echo "<script>alert('Error: {$e->getMessage()}');</script>";
    }
}

// Dapatkan butiran program sedia ada untuk penyuntingan
if (isset($_GET['id'])) {
    $pid = $_GET['id'];
    $query = "SELECT * FROM program WHERE P_ID=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $pid);
    $stmt->execute();
    $result = $stmt->get_result();
    $program = $result->fetch_assoc();
}
?>


<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Kemaskini Permohonana Program</title>
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
                        <h2 class="page-title" style="margin-top:4%">Kemaskini Permohonan Program</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Kemaskini Permohonan Program</div>
                            <div class="panel-body">
                                <form method="post" class="form-horizontal" enctype="multipart/form-data">
                                    <input type="hidden" name="pid" value="<?php echo htmlspecialchars($program['P_ID']); ?>">

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Nama Program</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="pn" class="form-control" value="<?php echo htmlspecialchars($program['P_Name']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Kategori</label>
                                        <div class="col-sm-8">
                                            <select name="cid" id="cid" class="form-control" required>
                                                <option value="">Pilih Kategori</option>
                                                <?php 
                                                $query = "SELECT * FROM category";
                                                $stmt = $mysqli->prepare($query);
                                                $stmt->execute();
                                                $res = $stmt->get_result();
                                                while ($row = $res->fetch_object()) {
                                                    $selected = ($row->C_ID == $program['C_ID']) ? 'selected' : '';
                                                    echo "<option value='" . $row->C_ID . "' $selected>" . htmlspecialchars($row->C_Name) . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Penerangan</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="pdesc" class="form-control" value="<?php echo htmlspecialchars($program['P_Description']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Gambar</label>
                                        <div class="col-sm-8">
                                            <?php if (!empty($program['P_Image'])): ?>
                                                <img src="../staff/<?php echo htmlspecialchars($program['P_Image']); ?>" width="150" height="150">
                                                <br>
                                            <?php endif; ?>
                                                                                       <input type="file" name="pimg" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Fail</label>
                                        <div class="col-sm-8">
                                            <?php if (!empty($program['P_File'])): ?>
                                                <a href="../staff/<?php echo htmlspecialchars($program['P_File']); ?>" target="_blank">Lihat Fail</a>
                                                <br>
                                            <?php endif; ?>
                                            <input type="file" name="pfile" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Tarikh & Masa</label>
                                        <div class="col-sm-8">
                                            <input type="datetime-local" name="pdate" class="form-control" id="datepicker" required value="<?php echo date('Y-m-d\TH:i', strtotime($program['P_DateTime'])); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Kapasiti Program</label>
                                        <div class="col-sm-8">
                                            <input type="number" name="pcapacity" class="form-control" min="1" value="<?php echo htmlspecialchars($program['P_Capacity']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Lokasi</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="plocate" class="form-control" value="<?php echo htmlspecialchars($program['P_Location']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">PIC</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="pperson" class="form-control" value="<?php echo htmlspecialchars($program['P_Person']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Sijil</label>
                                        <div class="col-sm-8">
                                            <?php if (!empty($program['P_Cert'])): ?>
                                                <a href="../staff/cert/<?php echo htmlspecialchars($program['P_Cert']); ?>" target="_blank">Lihat Sijil</a>
                                                <br>
                                            <?php endif; ?>
                                            <input type="file" name="pcert" class="form-control">
                                        </div>
                                    </div>

                                     <div class="form-group">
                                     <label class="col-sm-2 control-label">Catatan</label>
                                            <div class="col-sm-8">
                                             <textarea class="form-control" name="pcomment" rows="5" placeholder="Masukkan komen anda di sini"></textarea>
                                      </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-9 m-t-15">
                                            <input type="submit" name="btn_update" class="btn btn-success" value="Kemaskini">
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
