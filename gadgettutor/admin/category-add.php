<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
if (isset($_POST['submit'])) {
    $fcname = $_POST['fn'];

    // Check if the category already exists
    $result = "SELECT count(*) FROM category WHERE C_Name=?";
    $stmt = $mysqli->prepare($result);
    $stmt->bind_param('s', $fcname);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    // If the category already exists, show an alert
    if ($count > 0) {
        echo "<script>alert('Category already exists.');</script>";
    } else {
        // If the category doesn't exist, insert a new one
        $query = "INSERT INTO category (C_Name) VALUES (?)";
        $stmt = $mysqli->prepare($query);
        $rc = $stmt->bind_param('s', $fcname);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('New category added successfully.');</script>";
        header('Refresh:0.1; url=category-details.php');
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
    <title>Add Category</title>
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
                        <h2 class="page-title" style="margin-top:1%">Add New Category</h2>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Add Category</div>
                                    <div class="panel-body">
                                        <form method="post" class="form-horizontal">                                    
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Category Name</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="fn" id="cns" value="" required="required">                         
                                                </div>
                                            </div>
                                            <div class="col-sm-8 col-sm-offset-2">                                            
                                                <input class="btn btn-primary" type="submit" name="submit" value="Add Category">
                                                <a class="btn btn-danger" href="category-details.php" onclick="return confirmCancel();">Cancel</a>
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
</body>
</html>
