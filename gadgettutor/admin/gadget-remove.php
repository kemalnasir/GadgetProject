<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Handle program deletion
if(isset($_GET['del'])) {
    $id = intval($_GET['del']);
    $query = "DELETE FROM product WHERE P_ID=?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('i', $id);
    if($stmt->execute()) {
        echo "<script>alert('Gadget successfully deleted.');</script>";
    } else {
        echo "<script>alert('Failed to delete program. It is referenced in another table.');</script>";
    }
    $stmt->close();
}

// Fetch program data with category name
$aid = $_SESSION['id'];
$query = "SELECT p.P_ID, p.P_Name, p.P_Desc, p.P_Image, p.P_Video, p.P_Audio, c.C_Name 
          FROM product p 
          JOIN category c ON p.C_ID = c.C_ID
          ORDER BY p.P_RegDate DESC";
$stmt = $mysqli->prepare($query);
$stmt->execute();
$res = $stmt->get_result();
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
    <title>Remove Gadget</title>
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
                        <h2 class="page-title" style="margin-top:4%">Remove Gadget</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Remove Gadget List</div>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Program Name</th>
                                            <th>category</th>
                                            <th>Description</th>
                                            <th>Image</th>
                                            <th>Video</th>
                                            <th>Audio</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cnt = 1;
                                        while($row = $res->fetch_assoc()) {
                                            ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo $row['P_Name']; ?></td>
                                                 <td><?php echo $row['C_Name']; ?></td>
                                                <td><?php echo $row['P_Desc']; ?></td>
                                                <td><img src="../admin/<?php echo $row['P_Image']; ?>" width="230px" height="120px"></td>
                                                <td>
                                                    <?php if (!empty($row['P_Video'])): ?>
                                                        <video width="320" height="240" controls>
                                                            <source src="../admin/<?php echo $row['P_Video']; ?>" type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    <?php else: ?>
                                                        No video available
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if (!empty($row['P_Audio'])): ?>
                                                        <audio controls>
                                                            <source src="../admin/<?php echo $row['P_Audio']; ?>" type="audio/mpeg">
                                                            Your browser does not support the audio element.
                                                        </audio>
                                                    <?php else: ?>
                                                        No audio available
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="gadget-remove.php?del=<?php echo $row['P_ID']; ?>" onclick="return confirm('Are you sure you want to delete?');" title="Delete"><i class="fa fa-trash fa-lg"></i></a>
                                                </td>
                                            </tr>
                                            <?php
                                            $cnt++;
                                        }
                                        $stmt->close();
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
