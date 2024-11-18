<?php
session_start();

// Include database connection and session check
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Check if ID parameter is set
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Fetch product details from the database
    $query = "SELECT * FROM product WHERE P_ID=?";
    $stmt = $mysqli->prepare($query);
    if ($stmt === false) {
        die('MySQL prepare error: ' . $mysqli->error);
    }
    
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $res = $stmt->get_result();
    
    if ($res->num_rows > 0) {
        $row = $res->fetch_object();
    } else {
        echo "<script>alert('Product not found.');</script>";
        exit;
    }
} else {
    echo "<script>alert('Invalid request.');</script>";
    exit;
}

// Process form submission
if (isset($_POST['btn_update'])) {
    try {
        // Retrieve form data
        $pname = $_POST['pname'];
        $pdesc = $_POST['pdesc'];
        $cid = $_POST['cid'];

        // Initialize variables for file uploads
        $uploadImageTo = $row->P_Image;
        $uploadVideoTo = $row->P_Video;
        $uploadAudioTo = $row->P_Audio;

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
            // Adjust size limit or other constraints as needed for videos
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
            // Adjust size limit or other constraints as needed for audio files
            if (empty($errors)) {
                $audio_name = 'audio_' . round(microtime(true)) . '.' . $audioExt;
                $uploadAudioTo = "audio/" . $audio_name;
                if (!move_uploaded_file($_FILES['paudio']['tmp_name'], $uploadAudioTo)) {
                    $errors[] = "Failed to upload audio.";
                }
            }
        }

        // Update database if no errors
        if (empty($errors)) {
            $query = "UPDATE product SET P_Name=?, P_Desc=?, P_Image=?, P_Video=?, P_Audio=?, C_ID=? WHERE P_ID=?";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('ssssssi', $pname, $pdesc, $uploadImageTo, $uploadVideoTo, $uploadAudioTo, $cid, $id);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                echo "<script>alert('gadget updated successfully');</script>";
                header('Refresh:0.1; url=gadget-details.php'); // Redirect to product details page
            } else {
                echo "<script>alert('Failed to update gadget. Please try again.');</script>";
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
    <title>Update Product</title>
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
                        <h2 class="page-title" style="margin-top:4%">Update Gadget Details</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Update Product</div>
                            <div class="panel-body">
                                <form method="post" class="form-horizontal" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Gadget Name</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="pname" class="form-control" value="<?php echo htmlspecialchars($row->P_Name); ?>" required>
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
                                                while ($category = $result->fetch_assoc()) {
                                                    $selected = ($category['C_ID'] == $row->C_ID) ? 'selected' : '';
                                                    echo "<option value='" . $category['C_ID'] . "' $selected>" . htmlspecialchars($category['C_Name']) . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                           <div class="form-group">
                                        <label class="col-sm-2 control-label">Description</label>
                                        <div class="col-sm-8">
                                            <textarea name="pdesc" class="form-control" rows="5" required><?php echo htmlspecialchars($row->P_Desc); ?></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Current Image</label>
                                        <div class="col-sm-8">
                                            <img src="<?php echo htmlspecialchars($row->P_Image); ?>" width="230px" height="120px"><br>
                                            <input type="file" name="pimage" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Current Video</label>
                                        <div class="col-sm-8">
                                            <video width="320" height="240" controls>
                                                <source src="<?php echo htmlspecialchars($row->P_Video); ?>" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video><br>
                                            <input type="file" name="pvideo" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Current Audio</label>
                                        <div class="col-sm-8">
                                            <audio controls>
                                                <source src="<?php echo htmlspecialchars($row->P_Audio); ?>" type="audio/mpeg">
                                                Your browser does not support the audio element.
                                            </audio><br>
                                            <input type="file" name="paudio" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-9 m-t-15">
                                            <input type="submit" name="btn_update" class="btn btn-success" value="Update">
                                            <a href="gadget-manage.php" class="btn btn-danger">Cancel</a>
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
