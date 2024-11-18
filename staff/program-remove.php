<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if(isset($_GET['del']))
{   
    $id=intval($_GET['del']);
    $query="DELETE FROM program WHERE P_ID=?";
    $stmt = $mysqli->prepare($query);
    $rc=$stmt->bind_param('i',$id);
    $result = $stmt->execute();
    if($result == true){
        echo"<script>alert('Program berjaya dipadamkan.');</script>"; // Success message in Malay
    }
    else {
        echo "<script>alert('Program tidak dapat dipadam. Ia dirujuk dalam jadual lain.');</script>"; // Failure message in Malay
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
    <title>Padam Program</title> <!-- Remove Inventory in Malay -->
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
                        <h2 class="page-title" style="margin-top:4%">Senarai Maklumat Program</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Senarai Maklumat Program</div> <!-- All Program Details in Malay -->
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Program</th> <!-- Program Name in Malay -->
                                            <th>Kategori</th> <!-- Category in Malay -->
                                            <th>Gambar</th> <!-- Image in Malay -->
                                            <th>Fail</th> <!-- File in Malay -->
                                            <th>Penerangan</th> <!-- Description in Malay -->
                                            <th>Tarikh & Masa Program</th> <!-- Date & Time in Malay -->
                                            <th>Kapasiti Program</th> <!-- Capacity in Malay -->
                                            <th>Lokasi</th> <!-- Location in Malay -->
                                            <th>PIC</th> <!-- Person In Charge in Malay -->
                                            <th>Sijil</th> <!-- Certification in Malay -->
                                            <th>Tindakan</th> <!-- Action in Malay -->
                                        </tr>
                                    </thead>                                    
                                    <tbody>
                                 <?php    
                                            $aid=$_SESSION['id'];
                                            $ret= "SELECT p.*, c.C_Name FROM program p INNER JOIN category c ON p.C_ID = c.C_ID ORDER BY p.P_RegDate DESC";
                                            $stmt= $mysqli->prepare($ret);
                                            $stmt->execute();
                                            $res=$stmt->get_result();
                                            $cnt=1;
                                            while($row=$res->fetch_object())
                                            {
                                                ?>
                                                <tr>
                                                    <td><?php echo $cnt; ?></td>                
                                                    <td><?php echo $row->P_Name; ?></td>
                                                    <td><?php echo $row->C_Name; ?></td>
                                                    <td><img src="../staff/<?php echo $row->P_Image; ?>" width="200px" height="120px"></td>
                                                    <td><a href="../staff/<?= $row->P_File ?>" target="_blank"><i class="fa fa-download"></i></a></td>
                                                    <td><?php echo $row->P_Description; ?></td>
                                                    <td><?php echo date('d M, Y h:i A', strtotime($row->P_DateTime)); ?></td>
                                                    <td><?php echo $row->P_Capacity; ?></td>
                                                    <td><?php echo $row->P_Location; ?></td>
                                                    <td><?php echo $row->P_Person; ?></td>
                                                    <td><a href="../staff/<?= $row->P_Cert ?>" target="_blank"><i class="fa fa-download"></i></a></td>

                                                    <td>&nbsp;&nbsp;
                                                        <a href="program-remove.php?del=<?php echo $row->P_ID; ?>" onclick="return confirm('Adakah anda pasti ingin padam?');"><i class="fa fa-trash fa-lg"></i></a>
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
