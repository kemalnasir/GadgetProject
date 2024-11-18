<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if(isset($_POST['submit'])) {
    try {
        $status = $_POST['status'];
        $id = $_GET['id'];

        // Get the current status
        $currentStatusQuery = "SELECT UP_Status FROM user_program WHERE UP_ID = ?";
        $currentStatusStmt = $mysqli->prepare($currentStatusQuery);
        $currentStatusStmt->bind_param('i', $id);
        $currentStatusStmt->execute();
        $currentStatusStmt->store_result();
        $currentStatusStmt->bind_result($currentStatus);
        $currentStatusStmt->fetch();

        // Check if new status is different from current status
        if ($status === $currentStatus) {
            echo "<script>alert('The new status is the same as the current status. No update needed.');</script>";
        } else {
            // Update user program status
            $updateQuery = "UPDATE user_program SET UP_Status=? WHERE UP_ID=?";
            $updateStmt = $mysqli->prepare($updateQuery);
            $updateStmt->bind_param('si', $status, $id);

            if ($updateStmt->execute()) {
                echo "<script>alert('Status has been updated successfully.');</script>";
                echo "<script>window.location.href='program-status.php';</script>";
            } else {
                echo "<script>alert('Error updating status.');</script>";
            }
        }
    } catch(Exception $e) {
        echo $e->getMessage();
    }
}
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

    <title>Update Status</title>
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
                                    <div class="panel-heading">Update Status</div>
                                    <div class="panel-body">
                                        <form method="post" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']; ?>">
                                            <?php
                                            $id = $_GET['id'];
                                            $ret = "SELECT * FROM user_program WHERE UP_ID=?";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->bind_param('i', $id);
                                            $stmt->execute();
                                            $res = $stmt->get_result();

                                            if($row = $res->fetch_object()) {
                                            ?>
                                            <div class="hr-dashed"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Current Status</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" value="<?php echo $row->UP_Status; ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">New Status</label>
                                                <div class="col-sm-8">
                                                    <select class="form-control" name="status" required>
                                                        <option value="">Select Status</option>
                                                        <option value="Approved">Approved</option>
                                                        <option value="Declined">Declined</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div class="col-sm-8 col-sm-offset-2">
                                                <input class="btn btn-primary" type="submit" name="submit" value="Update Status">
                                                <a class="btn btn-danger" href="program-status.php" onclick="return confirmCancel();">Cancel</a>
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
        return confirm("Are you sure you want to cancel? Any unsaved changes will be lost.");
    }
    </script>
</body>
</html>
