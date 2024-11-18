<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if (isset($_POST['submit'])) {
    try {
        $role = $_POST['role'];
        $id = $_GET['id'];

        // Get the current role
        $currentRoleQuery = "SELECT U_Roles FROM user WHERE U_ID = ?";
        $currentRoleStmt = $mysqli->prepare($currentRoleQuery);
        $currentRoleStmt->bind_param('i', $id);
        $currentRoleStmt->execute();
        $currentRoleStmt->store_result();
        $currentRoleStmt->bind_result($currentRole);
        $currentRoleStmt->fetch();
        $currentRoleStmt->close();

        // Check if new role is different from the current role
        if ($role === $currentRole) {
            echo "<script>alert('Peranan baru sama dengan peranan semasa. Tiada kemaskini diperlukan.');</script>";
        } else {
            // Update user role
            $updateQuery = "UPDATE user SET U_Roles=? WHERE U_ID=?";
            $updateStmt = $mysqli->prepare($updateQuery);
            $updateStmt->bind_param('si', $role, $id);

            if ($updateStmt->execute()) {
                echo "<script>alert('Peranan telah dikemaskini dengan berjaya.');</script>";
                echo "<script>window.location.href='user-details.php';</script>";
            } else {
                echo "<script>alert('Ralat semasa mengemaskini peranan.');</script>";
            }
            $updateStmt->close();
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
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">

    <title>Kemaskini Peranan</title>
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
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title"></h2>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Kemaskini Peranan</div>
                                    <div class="panel-body">
                                        <form method="post" class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']).'?id='.$_GET['id']; ?>">
                                            <?php
                                            $id = $_GET['id'];
                                            $ret = "SELECT * FROM user WHERE U_ID=?";
                                            $stmt = $mysqli->prepare($ret);
                                            $stmt->bind_param('i', $id);
                                            $stmt->execute();
                                            $res = $stmt->get_result();

                                            if ($row = $res->fetch_object()) {
                                            ?>
                                            <div class="hr-dashed"></div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Peranan Semasa</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" value="<?php echo $row->U_Roles; ?>" readonly>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Peranan Baru</label>
                                                <div class="col-sm-8">
                                                    <select class="form-control" name="role" required>
                                                        <option value="">Pilih Peranan</option>
                                                        <option value="admin">Admin</option>
                                                        <option value="staff">Staff</option>
                                                        <option value="user">User</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <?php } ?>

                                            <div class="col-sm-8 col-sm-offset-2">
                                                <input class="btn btn-primary" type="submit" name="submit" value="Kemaskini Peranan">
                                                <a class="btn btn-danger" href="user-details.php" onclick="return confirmCancel();">Batal</a>
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
