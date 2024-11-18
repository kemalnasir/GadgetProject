<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if(isset($_GET['del']))
{   
    $id=intval($_GET['del']);
    $query="DELETE FROM surah WHERE S_ID=?";
    $stmt = $mysqli->prepare($query);
    $rc=$stmt->bind_param('i',$id);
    $result = $stmt->execute();
    if($result == true){
        echo"<script>alert('Surah telah berjaya dihapuskan.');</script>";
    }
    else {
        echo "<script>alert('Surah tidak boleh dihapuskan. Ia dirujuk dalam jadual lain.');</script>";
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
    <title>Padam Surah Ringkas</title>
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
                            <div class="panel-heading">Semua Butiran Surah Ringkas</div>
                            <div class="panel-body">
                                <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>Bil.</th>
                                             <th>Nama Surah</th>
                                            <th>Jumlah Ayat</th>
                                            <th>Audio</th>
                                            <th>Potongan Ayat (Bahasa Arab)</th>   
                                            <th>Tindakan</th>
                                        </tr>
                                    </thead>                                    
                                    <tbody>
                                        <?php   
                                            $aid=$_SESSION['id'];
                                            $ret="SELECT * FROM surah Order by S_RegDate DESC";
                                            $stmt= $mysqli->prepare($ret) ;
                                            $stmt->execute() ;//ok
                                            $res=$stmt->get_result();
                                            $cnt=1;
                                            while($row=$res->fetch_object())
                                            {
                                                ?>
                                                        <td><?php echo $cnt;?></td>               
                                                         <td><?php echo $row->S_Name;?></td>
                                                <td><?php echo $row->S_Verse . " ayat";?></td>
                                                <td>
                                                    <?php
                                                    // Check if audio file exists
                                                    if (!empty($row->S_Audio)) {
                                                        ?>
                                                        <audio controls>
                                                            <source src="<?php echo $row->S_Audio;?>" type="audio/mpeg">
                                                            Your browser does not support the audio element.
                                                        </audio>
                                                    <?php } else {
                                                        echo "Tiada audio tersedia";
                                                    }
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    // Check if file exists
                                                    if (!empty($row->S_File)) {
                                                        // Assuming S_File is a path to the file
                                                        $file_path = $row->S_File;
                                                        // Construct the link using S_Name
                                                        $file_name = basename($file_path);
                                                        ?>
                                                        <a href="<?php echo $file_path;?>" target="_blank">Surah <?php echo $row->S_Name;?></a>
                                                    <?php } else {
                                                        echo "Tiada fail tersedia";
                                                    }
                                                    ?>
                                                </td>
                                                        <td>
                                                        <a href="surah-remove.php?del=<?php echo $row->S_ID;?>" onclick="return confirm('Adakah anda pasti untuk memadam?');">
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
