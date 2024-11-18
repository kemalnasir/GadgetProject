<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if(isset($_POST['submit'])) {
    $userid = $_SESSION['id'];
    $programid = $_POST['programid'];

    $check_query = "SELECT COUNT(*) FROM user_program WHERE U_ID = ? AND P_ID = ?";
    $check_stmt = $mysqli->prepare($check_query);
    $check_stmt->bind_param('ii', $userid, $programid);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    $check_stmt->close();

    if($count > 0) {
        echo "<script>alert('You have already applied for this program.');</script>";
         echo '<script>window.location.href = "user-viewprogram.php";</script>';

    } else {
        // Insert into user_program table
        $insert_query = "INSERT INTO user_program (UP_Status, U_ID, P_ID) VALUES (?, ?, ?)";
        $insert_stmt = $mysqli->prepare($insert_query);
        $up_status = 'Under Review';
        
        $insert_stmt->bind_param('sii', $up_status, $userid, $programid);
        $insert_stmt->execute();
        
        if($insert_stmt->affected_rows > 0) {
            echo '<script>alert("Your request has been submitted for review.");</script>';
             echo '<script>window.location.href = "user-detailsjoin.php";</script>';
        } else {
            echo '<script>alert("Failed to submit your request. Please try again later.");</script>';
        }

        $insert_stmt->close();
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
    <title>Enroll Program</title>
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
                        <h2 class="" style="margin-top:2.5%"></h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Program Information</div>
                            <div class="panel-body">
                                <form method="post" action="" class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Name </label>
                                        <div class="col-sm-8">
                                            <?php 
                                                $aid = $_SESSION['id'];
                                                $query = "SELECT U_FName, U_LName FROM user WHERE U_ID = ?";
                                                $stmt = $mysqli->prepare($query);
                                                $stmt->bind_param('i', $aid);
                                                $stmt->execute();
                                                $stmt->bind_result($fname, $lname);
                                                $stmt->fetch();
                                                $stmt->close();
                                            ?>
                                            <input type="text" value="<?php echo $fname . ' ' . $lname;?>" disabled class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Program Name</label>
                                        <div class="col-sm-8">
                                            <select name="programid" id="programid" class="form-control" required>
                                                <option value="">Select Program</option>
                                                <?php 
                                                    $query = "SELECT P_ID, P_Name FROM program";
                                                    $result = $mysqli->query($query);
                                                    while($row = $result->fetch_assoc()) {
                                                        echo '<option value="' . $row['P_ID'] . '">' . $row['P_Name'] . '</option>';
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

 

                                    <div class="col-sm-8 col-sm-offset-2">
                                        <input type="submit" name="submit" value="JOINED" class="btn btn-primary">
                                        <button class="btn btn-default" type="reset">RESET</button>
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
</body>
</html>
