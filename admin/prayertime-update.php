<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if(isset($_POST['submit'])) {
    try {
        $pt_name = $_POST['pt_name'];
        $pt_time = $_POST['pt_time'];
        $id = $_GET['id'];

        // Check if a prayer time with the same name already exists
        $checkQuery = "SELECT PT_ID FROM prayertime WHERE PT_Name = ? AND PT_ID != ?";
        $checkStmt = $mysqli->prepare($checkQuery);
        $checkStmt->bind_param('si', $pt_name, $id);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            echo "<script>alert('Waktu solat dengan nama yang sama sudah wujud.');</script>";
        } else {
            // Update prayer time if it doesn't conflict with an existing one
            $updateQuery = "UPDATE prayertime SET PT_Name=?, PT_Time=? WHERE PT_ID=?";
            $updateStmt = $mysqli->prepare($updateQuery);
            $updateStmt->bind_param('ssi', $pt_name, $pt_time, $id);
            
            if ($updateStmt->execute()) {
                echo "<script>alert('Waktu solat telah berjaya dikemaskini.');</script>";
                header('Refresh:0.1; url=prayertime-manage.php');
            } else {
                echo "<script>alert('Ralat semasa mengemaskini waktu solat.');</script>";
            }
        }
    } catch(Exception $e) {
        echo $e->getMessage();
    }
}
?>

<!doctype html>
<html lang="ms" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">

    <title>Kemaskini Waktu Solat</title>
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
                                    <div class="panel-heading">Kemaskini Waktu Solat</div>
                                    <div class="panel-body">
                                        <form method="post" class="form-horizontal">
    
    <?php                                            
    $id=$_GET['id'];
    $ret="SELECT * FROM prayertime WHERE PT_ID=?";
    $stmt= $mysqli->prepare($ret) ;
    $stmt->bind_param('i',$id);
    $stmt->execute() ;
    $res=$stmt->get_result();
    
    while($row=$res->fetch_object())
      {
        ?>    
            <div class="hr-dashed"></div>                    
            
            <div class="form-group">
                <label class="col-sm-2 control-label">Nama Solat</label>
                <div class="col-sm-8">
                    <input type="text" class="form-control" name="pt_name" value="<?php echo $row->PT_Name;?>" required>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Masa Solat</label>
                <div class="col-sm-8">
                    <input type="time" class="form-control" name="pt_time" value="<?php echo $row->PT_Time;?>" required>
                </div>
            </div>
            
        <?php 
      } 
    ?>
    <script>
    function confirmCancel() {
        return confirm("Adakah anda pasti untuk membatalkan? Segala perubahan yang belum disimpan akan hilang.");
    }
    </script>
    <div class="col-sm-8 col-sm-offset-2">                                            
        <input class="btn btn-primary" type="submit" name="submit" value="Kemaskini Waktu Solat">
        <a class="btn btn-danger" href="prayertime-manage.php" onclick="return confirmCancel();">Batal</a>
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
