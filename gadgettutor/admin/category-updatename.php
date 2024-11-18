<?php 
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if(isset($_POST['submit'])) {
    try {
        $ft = $_POST['ft'];
        $id = $_GET['id'];

        // Check if a category with the same name already exists
        $checkQuery = "SELECT C_ID FROM category WHERE C_Name = ? AND C_ID != ?";
        $checkStmt = $mysqli->prepare($checkQuery);
        $checkStmt->bind_param('si', $ft, $id);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            echo "<script>alert('Category with the same name already exists.');</script>";
        } else {
            // Update category if it doesn't conflict with an existing one
            $updateQuery = "UPDATE category SET C_Name=? WHERE C_ID=?";
            $updateStmt = $mysqli->prepare($updateQuery);
            $rc = $updateStmt->bind_param('si', $ft, $id);
            
            if ($updateStmt->execute()) {
                echo "<script>alert('Category updated successfully.');</script>";
                header('Refresh:0.1; url=category-manage.php');
            } else {
                echo "<script>alert('Error updating category.');</script>";
            }
        }
    } catch(PDOException $e) {
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
    <title>Update Category</title>
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
                        <h2 class="page-title">Update Category</h2>
    
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">Update Category</div>
                                    <div class="panel-body">
                                        <form method="post" class="form-horizontal">
    
    <?php                                           
    $id=$_GET['id'];
    $ret="select * from category where C_ID=?";
    $stmt= $mysqli->prepare($ret) ;
    $stmt->bind_param('i',$id);
    $stmt->execute() ;
    $res=$stmt->get_result();
    
    while($row=$res->fetch_object())
      {
        ?>  
            <div class="hr-dashed"></div>                    
            
            <div class="form-group">
                <label class="col-sm-2 control-label">Category Name</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="ft" value="<?php echo $row->C_Name;?>" >
                </div>
            </div>

            
        <?php 
      } 
    ?>
    <script>
    function confirmCancel() {
        return confirm("Are you sure you want to cancel? Any unsaved changes will be lost.");
    }
    </script>
    <div class="col-sm-8 col-sm-offset-2">                                            
        <input class="btn btn-primary" type="submit" name="submit" value="Update Category">
        <a class="btn btn-danger" href="category-manage.php" onclick="return confirmCancel();">Cancel</a>
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

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap-select.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/fileinput.js"></script>
    <script src="js/chartData.js"></script>
    <script src="js/main.js"></script>

</script>
</body>

</html>
