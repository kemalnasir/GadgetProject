<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
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
    <title>Manage Gadget</title>
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
                        <h2 class="page-title" style="margin-top:4%">Manage Gadget</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">All Gadget Details</div>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Gadget Name</th>
                                            <th>Category</th>
                                            <th>Image</th>
                                             <th>Description</th>
                                            <th>Video</th>
                                            <th>Audio</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT p.P_ID, p.P_Name, c.C_Name, p.P_Image, p.P_Desc,p.P_Video, p.P_Audio
                                                  FROM product p
                                                  JOIN category c ON p.C_ID = c.C_ID
                                                  ORDER BY p.P_RegDate DESC";
                                        $stmt = $mysqli->prepare($query);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $res->fetch_object()) {
                                        ?>
                                            <tr>
                                                <td><?php echo $cnt; ?></td>
                                                <td><?php echo htmlspecialchars($row->P_Name); ?></td>
                                                <td><?php echo htmlspecialchars($row->C_Name); ?></td>
                                                <td><img src="../admin/<?php echo htmlspecialchars($row->P_Image); ?>" width="230px" height="120px"></td>
                                                <td><?php echo htmlspecialchars($row->P_Desc); ?></td>
                                                <td>
                                                    <?php if (!empty($row->P_Video)) : ?>
                                                        <video width="320" height="240" controls>
                                                            <source src="../admin/<?php echo htmlspecialchars($row->P_Video); ?>" type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    <?php else : ?>
                                                        N/A
                                                    <?php endif; ?>
                                                </td>
                                                <td>
    <?php if (!empty($row->P_Audio)) : ?>
        <audio controls>
            <source src="../admin/<?php echo htmlspecialchars($row->P_Audio); ?>" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>
    <?php else : ?>
        N/A
    <?php endif; ?>
</td>
                                                <td>
                                                    <a href="gadget-update.php?id=<?php echo $row->P_ID;?>" class="btn btn-primary btn-sm">Update</a>
                                                    <a href="gadget-remove.php?id=<?php echo $row->P_ID;?>" class="btn btn-danger btn-sm">Delete</a>
                                                </td>
                                            </tr>
                                        <?php
                                            $cnt++;
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
