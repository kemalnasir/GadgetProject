<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if(isset($_GET['del']))
{   
    $id=intval($_GET['del']);
    $query="DELETE FROM category WHERE C_ID=?";
    $stmt = $mysqli->prepare($query);
    $rc=$stmt->bind_param('i',$id);
    $result = $stmt->execute();
    if($result == true){
        echo"<script>alert('Kategori telah berjaya dihapuskan.');</script>";
    }
    else {
        echo "<script>alert('Kategori tidak boleh dihapuskan. Ia dirujuk dalam jadual lain.');</script>";
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
    <title>Padam Kategori</title>
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
                        <h2 class="page-title" style="margin-top:4%"></h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Semua Butiran Kategori</div>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Bil.</th>
                                            <th>Nama Kategori</th>    
                                            <th>Tindakan</th>
                                        </tr>
                                    </thead>                                    
                                    <tbody>
                                        <?php   
                                            $aid=$_SESSION['id'];
                                            $ret="SELECT * FROM category Order by C_RegDate DESC";
                                            $stmt= $mysqli->prepare($ret) ;
                                            $stmt->execute() ;//ok
                                            $res=$stmt->get_result();
                                            $cnt=1;
                                            while($row=$res->fetch_object())
                                            {
                                                ?>
                                                    <tr>
                                                        <td><?php echo $cnt;?></td>               
                                                        <td><?php echo $row->C_Name;?></td>
                                                        <td>
                                                        <a href="category-remove.php?del=<?php echo $row->C_ID;?>" onclick="return confirm('Adakah anda pasti untuk memadam?');">
                                                          <i class="fa fa-trash fa-lg"></i> <!-- Sesuaikan saiz di sini: fa-lg, fa-2x, fa-3x, fa-4x, fa-5x -->
                                                        </a>                            
                                                        </td>
                                                    </tr>
                                                <?php
                                                $cnt=$cnt+1;
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