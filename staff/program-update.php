<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if (isset($_POST['btn_update'])) {
    try {
        $pid = $_POST['pid'];
        $pname = $_POST['pn'];
        $pdesc = $_POST['pdesc'];
        $pdate = date('Y-m-d H:i:s', strtotime($_POST['pdate']));
        $pcapacity = $_POST['pcapacity'];
        $plocate = $_POST['plocate'];
        $pperson = $_POST['pperson'];
        $cid = $_POST['cid'];

        // Fetch existing program details to manage file paths and check status
        $query_fetch = "SELECT * FROM program WHERE P_ID=?";
        $stmt_fetch = $mysqli->prepare($query_fetch);
        $stmt_fetch->bind_param('i', $pid);
        $stmt_fetch->execute();
        $result_fetch = $stmt_fetch->get_result();
        $program = $result_fetch->fetch_assoc();

        if (in_array($program['P_Status'], ['Diluluskan', 'Ditolak'])) {
            echo "<script>alert('Program ini tidak boleh dikemaskini kerana status program telah " . htmlspecialchars($program['P_Status']) . "');</script>";
            echo "<script>window.location.href='program-details.php';</script>";
            exit();
        }

        $existing_image = $program['P_Image'];
        $existing_file = $program['P_File'];
        $existing_cert = $program['P_Cert'];

        $uploadFileTo = $existing_file;
        $uploadImageTo = $existing_image;
        $uploadCertTo = $existing_cert;

        $errors = [];

        // Handle File Uploads if new files are uploaded
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

        // Update query construction
        if (empty($errors)) {
            $query = "UPDATE program SET P_Name=?, P_Description=?, P_Image=?, P_File=?, P_DateTime=?, P_Capacity=?, P_Location=?, P_Person=?, P_Cert=?, C_ID=? WHERE P_ID=?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('sssssisssii', $pname, $pdesc, $uploadImageTo, $uploadFileTo, $pdate, $pcapacity, $plocate, $pperson, $uploadCertTo, $cid, $pid);
            $stmt->execute();

            echo "<script>alert('Program berjaya dikemaskini');</script>";
            header('Refresh:0.1; url=program-details.php');
        } else {
            foreach ($errors as $error) {
                echo "<script>alert('$error');</script>";
            }
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    }
}

// Fetch existing program details for editing
if (isset($_GET['id'])) {
    $pid = $_GET['id'];
    $query = "SELECT * FROM program WHERE P_ID=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $pid);
    $stmt->execute();
    $result = $stmt->get_result();
    $program = $result->fetch_assoc();

    // Check if program status is "diluluskan" or "ditolak"
    if (in_array($program['P_Status'], ['diluluskan', 'ditolak'])) {
        echo "<script>alert('Program ini tidak boleh dikemaskini kerana statusnya adalah " . htmlspecialchars($program['P_Status']) . "');</script>";
        echo "<script>window.location.href='program-details.php';</script>";
        exit();
    }
}
?>

<!doctype html>
<html lang="en" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title>Kemaskini Program</title>
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
                        <h2 class="page-title" style="margin-top:4%">Kemaskini Maklumat Program</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Kemaskini Maklumat Program</div>
                            <div class="panel-body">
                                <form method="post" class="form-horizontal" enctype="multipart/form-data">
                                    <input type="hidden" name="pid" value="<?php echo htmlspecialchars($program['P_ID']); ?>">

                                      <div class="form-group">
                                         <label class="col-sm-2 control-label" style="color: red;"><span style="color: red;">*</span> Required</label>
                                        <div class="col-sm-8">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Nama Program <span style="color: red;">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="pn" class="form-control" value="<?php echo htmlspecialchars($program['P_Name']); ?>" required>
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
                                                    $selected = ($row->C_ID == $program['C_ID']) ? 'selected' : '';
                                                    echo "<option value='" . $row->C_ID . "' $selected>" . htmlspecialchars($row->C_Name) . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Penerangan <span style="color: red;">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="pdesc" class="form-control" value="<?php echo htmlspecialchars($program['P_Description']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Gambar</label>
                                        <div class="col-sm-8">
                                            <?php if (!empty($program['P_Image'])): ?>
                                                <img src="../staff/<?php echo htmlspecialchars($program['P_Image']); ?>" width="250" height="170">
                                                <br>
                                                <br>
                                            <?php endif; ?>
                                            <input type="file" name="pimg" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Kertas Kerja <span style="color: red;">*</span></label>
                                        <div class="col-sm-8">
                                            <?php if (!empty($program['P_File'])): ?>
                                                <a href="../staff/<?php echo htmlspecialchars($program['P_File']); ?>" target="_blank">Lihat Kertas Kerja</a>
                                                <br>
                                                <p class="help-block">Kertas Kerja Semasa: <?php echo htmlspecialchars(basename($program['P_File'])); ?></p>
                                            <?php endif; ?>
                                            <input type="file" name="pfile" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Tarikh & Masa Program <span style="color: red;">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="datetime-local" name="pdate" class="form-control" id="datepicker" required value="<?php echo date('Y-m-d\TH:i', strtotime($program['P_DateTime'])); ?>">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Kapasiti Program <span style="color: red;">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="number" name="pcapacity" class="form-control" min="1" value="<?php echo htmlspecialchars($program['P_Capacity']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Lokasi <span style="color: red;">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="plocate" class="form-control" value="<?php echo htmlspecialchars($program['P_Location']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Penyelaras <span style="color: red;">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="pperson" class="form-control" value="<?php echo htmlspecialchars($program['P_Person']); ?>" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Sijil (Pilihan)</label>
                                        <div class="col-sm-8">
                                            <?php if (!empty($program['P_Cert'])): ?>
                                                <a href="../staff/<?php echo htmlspecialchars($program['P_Cert']); ?>" target="_blank">Lihat Sijil</a>
                                                <br>
                                                <p class="help-block">Sijil Semasa: <?php echo htmlspecialchars(basename($program['P_Cert'])); ?></p>
                                            <?php endif; ?>
                                            <input type="file" name="pcert" class="form-control">
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
