<?php
session_start();

// Include database connection and session check
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Process form submission
if (isset($_POST['btn_insert'])) {
    try {
        // Retrieve form data
        $pname = $_POST['pname'];
        $pdesc = $_POST['pdesc'];
        $cid = $_POST['cid'];

        // Initialize variables for file uploads
        $uploadImageTo = '';
        $uploadVideoTo = '';
        $uploadAudioTo = '';

        $errors = [];

        // Handle Image Upload
        if (isset($_FILES['pimage']) && $_FILES['pimage']['name']) {
            $imageExt = strtolower(pathinfo($_FILES['pimage']['name'], PATHINFO_EXTENSION));
            if (!in_array($imageExt, ['jpg', 'jpeg', 'png'])) {
                $errors[] = "Image file must be .jpg, .jpeg, or .png";
            }
            if ($_FILES['pimage']['size'] > 2097152) { // 2MB limit
                $errors[] = "Image size must not exceed 2MB";
            }
            if (empty($errors)) {
                $image_name = 'image_' . round(microtime(true)) . '.' . $imageExt;
                $uploadImageTo = "image/" . $image_name;
                if (!move_uploaded_file($_FILES['pimage']['tmp_name'], $uploadImageTo)) {
                    $errors[] = "Failed to upload image.";
                }
            }
        }

        // Handle Video Upload
        if (isset($_FILES['pvideo']) && $_FILES['pvideo']['name']) {
            $videoExt = strtolower(pathinfo($_FILES['pvideo']['name'], PATHINFO_EXTENSION));
            if (!in_array($videoExt, ['mp4', 'avi', 'mov'])) {
                $errors[] = "Video file must be .mp4, .avi, or .mov";
            }
            if ($_FILES['pvideo']['size'] > 52428800) { // 50MB limit
                $errors[] = "Video size must not exceed 50MB";
            }
            if (empty($errors)) {
                $video_name = 'video_' . round(microtime(true)) . '.' . $videoExt;
                $uploadVideoTo = "video/" . $video_name;
                if (!move_uploaded_file($_FILES['pvideo']['tmp_name'], $uploadVideoTo)) {
                    $errors[] = "Failed to upload video.";
                }
            }
        }

        // Handle Audio Upload
        if (isset($_FILES['paudio']) && $_FILES['paudio']['name']) {
            $audioExt = strtolower(pathinfo($_FILES['paudio']['name'], PATHINFO_EXTENSION));
            if (!in_array($audioExt, ['mp3', 'wav'])) {
                $errors[] = "Audio file must be .mp3 or .wav";
            }
            if ($_FILES['paudio']['size'] > 10485760) { // 10MB limit
                $errors[] = "Audio size must not exceed 10MB";
            }
            if (empty($errors)) {
                $audio_name = 'audio_' . round(microtime(true)) . '.' . $audioExt;
                $uploadAudioTo = "audio/" . $audio_name;
                if (!move_uploaded_file($_FILES['paudio']['tmp_name'], $uploadAudioTo)) {
                    $errors[] = "Failed to upload audio.";
                }
            }
        }

        // Insert data into database if no errors
        if (empty($errors)) {
            $query = "INSERT INTO product (P_Name, P_Desc, P_Image, P_Video, P_Audio, C_ID) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('sssssi', $pname, $pdesc, $uploadImageTo, $uploadVideoTo, $uploadAudioTo, $cid);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "<script>alert('Gadget added successfully');</script>";
                header('Refresh:0.1; url=gadget-details.php'); // Redirect to product details page
            } else {
                echo "<script>alert('Failed to insert gadget. Please try again.');</script>";
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
    <title>Add Gadget</title>
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
                        <h2 class="page-title" style="margin-top:4%">Add Gadget Details</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Add New Gadget</div>
                            <div class="panel-body">
                                <form method="post" class="form-horizontal" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Gadget Name</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="pname" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Category</label>
                                        <div class="col-sm-8">
                                            <select name="cid" id="cid" class="form-control" required>
                                                <option value="">Select Category</option>
                                                <?php 
                                                // Fetch categories from database
                                                $query = "SELECT * FROM category";
                                                $result = $mysqli->query($query);
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value='" . $row['C_ID'] . "'>" . htmlspecialchars($row['C_Name']) . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Description</label>
                                            <div class="col-sm-8">
                                                <textarea name="pdesc" class="form-control" rows="5" required></textarea>
                                            </div>
                                        </div>


                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Image</label>
                                        <div class="col-sm-8">
                                            <input type="file" name="pimage" class="form-control" required>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Video</label>
                                        <div class="col-sm-8">
                                            <input type="file" name="pvideo" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Audio</label>
                                        <div class="col-sm-8">
                                            <input type="file" name="paudio" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-9 m-t-15">
                                            <input type="submit" name="btn_insert" class="btn btn-success" value="Add">
                                            <a href="gadget-details.php" class="btn btn-danger">Cancel</a>
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

    <!-- Scripts -->
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
