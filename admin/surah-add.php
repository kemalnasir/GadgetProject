<?php
include('includes/config.php');
include('includes/checklogin.php'); // Remove if not needed

if(isset($_POST['btn_insert']))
{
    try
    {      
        $sname = $_POST['pc'];
        $sverse = $_POST['pp'];

        // File upload for Surah File
        $sfile_name = $_FILES['pfile']['name'];
        $sfile_tmp = $_FILES['pfile']['tmp_name'];
        $sfile_ext = strtolower(pathinfo($sfile_name, PATHINFO_EXTENSION));

        // File upload for Surah Audio
        $saudio_name = $_FILES['paudio']['name'];
        $saudio_tmp = $_FILES['paudio']['tmp_name'];
        $saudio_ext = strtolower(pathinfo($saudio_name, PATHINFO_EXTENSION));

        // Check file extensions and sizes
        $allowed_file_extensions = array("zip", "pdf", "docx");
        $allowed_audio_extensions = array("mp3");

        if (!in_array($sfile_ext, $allowed_file_extensions)) {
            echo "<script>alert('Your file extension must be .zip, .pdf, or .docx');</script>";
        } elseif (!in_array($saudio_ext, $allowed_audio_extensions)) {
            echo "<script>alert('Your audio must be .mp3 only');</script>";
        } elseif ($_FILES['pfile']['size'] > 2097152) { // Check file size directly
            echo "<script>alert('Your file must be exactly 2MB');</script>";
        } else {
            // Check if surah name already exists
            $query_check = "SELECT S_Name FROM surah WHERE S_Name = ?";
            $stmt_check = $mysqli->prepare($query_check);
            $stmt_check->bind_param('s', $sname);
            $stmt_check->execute();
            $stmt_check->store_result();

            if ($stmt_check->num_rows > 0) {
                echo "<script>alert('Surah telah berjaya didaftarkan. Sila masukkan nama surah yang lain.');</script>";
            } else {
                // Move uploaded files to appropriate directories
                $uploadFileTo = "file/" . round(microtime(true)) . '.' . $sfile_ext;
                $uploadAudioTo = "audio/" . round(microtime(true)) . '.' . $saudio_ext;

                if (move_uploaded_file($sfile_tmp, $uploadFileTo) && move_uploaded_file($saudio_tmp, $uploadAudioTo)) {
                    // Insert data into database
                    $query_insert = "INSERT INTO surah (S_Name, S_Verse, S_File, S_Audio) VALUES (?, ?, ?, ?)";
                    $stmt_insert = $mysqli->prepare($query_insert);
                    $stmt_insert->bind_param('ssss', $sname, $sverse, $uploadFileTo, $uploadAudioTo);

                    if ($stmt_insert->execute()) {
                        echo "<script>alert('Surah ringkas berjaya ditambah');</script>";
                        header('Refresh:0.1; url=surah-details.php');
                    } else {
                        echo "<script>alert('Surah ringkas gagal ditambah');</script>";
                    }
                } else {
                    echo "<script>alert('Failed to upload files');</script>";
                }
            }
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }
}
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
    <title>Tambah Surah Ringkas</title>
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
                  <br>
                  <br>
                    <div class="col-md-12"> 
                        <h2 class="page-title" style="margin-top:1%">Tambah Surah Ringkas</h2>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Tambah Surah Ringkas</div>
                                    <div class="panel-body"> 
                                                    <form method="post" class="form-horizontal" enctype="multipart/form-data">
                                                        
                                                    <div class="form-group">
                                                            <label class="col-sm-2 control-label">Nama Surah</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" name="pc" class="form-control" required>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-sm-2 control-label">Jumlah Ayat</label>
                                                            <div class="col-sm-8">
                                                                <input type="text" name="pp" class="form-control" required>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-sm-2 control-label">Fail Surah</label>
                                                            <div class="col-sm-8">
                                                                <input type="file" name="pfile" class="form-control" required>
                                                            </div>
                                                        </div>

                                                        <div class="form-group">
                                                            <label class="col-sm-2 control-label">Audio Surah</label>
                                                            <div class="col-sm-8">
                                                                <input type="file" name="paudio" class="form-control" required>
                                                            </div>
                                                        </div>
                                                        <script>
                                                            function confirmCancel() {
                                                                return confirm("Adakah anda pasti untuk membatalkan?");
                                                            }
                                                        </script>

                                                        <div class="form-group">
                                                            <div class="col-sm-offset-2 col-sm-8">
                                                                <button type="submit" name="btn_insert" class="btn btn-primary">Masukkan Surah</button>
                                                                <a class="btn btn-danger" href="surah-details.php" onclick="return confirmCancel();">Batal</a>
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
</body>
</html>
