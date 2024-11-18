<?php
include('includes/config.php');
include('includes/checklogin.php'); // Include your authentication logic

// Initialize variables to store existing Surah details
$sname = "";
$sverse = "";
$sfile_name = "";
$saudio_name = "";

// Fetch existing Surah details from database if Surah ID is provided
if (isset($_GET['id'])) {
    $surah_id = $_GET['id'];

    // Fetch existing Surah details from database
    $query_fetch = "SELECT S_Name, S_Verse, S_File, S_Audio FROM surah WHERE S_ID = ?";
    $stmt_fetch = $mysqli->prepare($query_fetch);
    $stmt_fetch->bind_param('i', $surah_id);
    $stmt_fetch->execute();
    $stmt_fetch->bind_result($sname, $sverse, $sfile_name, $saudio_name);
    $stmt_fetch->fetch();
    $stmt_fetch->close();
}

// Handle form submission for updating Surah details
if (isset($_POST['btn_update'])) {
    try {
        $new_sname = $_POST['pc'];
        $new_sverse = $_POST['pp'];
        $valid_formats = ['pdf', 'doc', 'docx', 'txt']; // Allowed file formats
        $valid_audio_formats = ['mp3', 'wav', 'ogg']; // Allowed audio formats

        $update_allowed = true;

        // File upload handling if a new file is uploaded
        if (isset($_FILES['pfile']) && $_FILES['pfile']['error'] === UPLOAD_ERR_OK) {
            $sfile_name = $_FILES['pfile']['name'];
            $sfile_tmp = $_FILES['pfile']['tmp_name'];
            $sfile_ext = strtolower(pathinfo($sfile_name, PATHINFO_EXTENSION));

            // Validate file format
            if (in_array($sfile_ext, $valid_formats)) {
                $uploadFileTo = "file/" . round(microtime(true)) . '.' . $sfile_ext;
                if (move_uploaded_file($sfile_tmp, $uploadFileTo)) {
                    $sfile_name = $uploadFileTo; // Update file name in database
                } else {
                    echo "<script>alert('Failed to upload file');</script>";
                    $update_allowed = false;
                }
            } else {
                echo "<script>alert('Invalid file format. Allowed formats are: " . implode(", ", $valid_formats) . "');</script>";
                $update_allowed = false;
            }
        }

        // Audio file handling if a new audio file is uploaded
        if (isset($_FILES['paudio']) && $_FILES['paudio']['error'] === UPLOAD_ERR_OK) {
            $saudio_name = $_FILES['paudio']['name'];
            $saudio_tmp = $_FILES['paudio']['tmp_name'];
            $saudio_ext = strtolower(pathinfo($saudio_name, PATHINFO_EXTENSION));

            // Validate audio format
            if (in_array($saudio_ext, $valid_audio_formats)) {
                $uploadAudioTo = "audio/" . round(microtime(true)) . '.' . $saudio_ext;
                if (move_uploaded_file($saudio_tmp, $uploadAudioTo)) {
                    $saudio_name = $uploadAudioTo; // Update audio name in database
                } else {
                    echo "<script>alert('Failed to upload audio file');</script>";
                    $update_allowed = false;
                }
            } else {
                echo "<script>alert('Invalid audio format. Allowed formats are: " . implode(", ", $valid_audio_formats) . "');</script>";
                $update_allowed = false;
            }
        }

        // Check if the update is allowed based on the validation
        if ($update_allowed) {
            // Update query to modify only the necessary fields
            $query_update = "UPDATE surah SET S_Name = ?, S_Verse = ?, S_File = ?, S_Audio = ? WHERE S_ID = ?";
            $stmt_update = $mysqli->prepare($query_update);
            $stmt_update->bind_param('ssssi', $new_sname, $new_sverse, $sfile_name, $saudio_name, $surah_id);

            if ($stmt_update->execute()) {
                echo "<script>alert('Surah berjaya dikemaskini');</script>";
                header('Refresh:0.1; url=surah-details.php'); // Redirect to details page
            } else {
                echo "<script>alert('Failed to update Surah details');</script>";
            }
        }
    } catch (Exception $e) {
        echo "<script>alert('Exception: ".$e->getMessage()."');</script>";
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
    <title>Kemaskini Surah Ringkas</title>
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
                    <br>
                    <br>
                        <h2 class="page-title" style="margin-top:1%">Kemaskini Surah Ringkas</h2>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Kemaskini Surah Ringkas</div>
                                    <div class="panel-body"> 
                                        <form method="post" class="form-horizontal" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Nama Surah</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="pc" class="form-control" value="<?php echo htmlspecialchars($sname); ?>" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Jumlah Ayat</label>
                                                <div class="col-sm-8">
                                                    <input type="text" name="pp" class="form-control" value="<?php echo htmlspecialchars($sverse); ?>" required>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Fail Surah</label>
                                                <div class="col-sm-8">
                                                    <input type="file" name="pfile" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Audio Surah</label>
                                                <div class="col-sm-8">
                                                    <input type="file" name="paudio" class="form-control">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-8">
                                                    <button type="submit" name="btn_update" class="btn btn-primary">Kemaskini Surah</button>
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
            return confirm("Adakah anda pasti untuk membatalkan? Segala perubahan yang belum disimpan akan hilang.");
        }
    </script>
</body>
</html>
