<?php
session_start();
include('includes/config.php');

if(isset($_POST['resetpass'])) {
    $op = $_POST['icnum'];
    $np = md5($_POST['newpassword']);

    $sql = "SELECT U_IC FROM USER WHERE U_IC=?";
    $chngpwd = $mysqli->prepare($sql);
    $chngpwd->bind_param('s', $op);
    $chngpwd->execute();
    $chngpwd->store_result(); 
    $row_cnt = $chngpwd->num_rows;
    
    if($row_cnt > 0) {
        $con = "UPDATE USER SET U_Password=? WHERE U_IC=?";
        $chngpwd1 = $mysqli->prepare($con);
        $chngpwd1->bind_param('ss', $np, $op);
        $chngpwd1->execute();
        echo "<script>alert('Kata Laluan telah berjaya ditukar! Sila Log Masuk Semula.');</script>";
        header('Refresh:0.1; url=user-index.php');
    } else {
        echo "<script>alert('Uh-Oh! Tiada akaun ditemui.');</script>";
    }	
}

if(isset($_POST['back'])) {
    header("location:user-index.php");
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

    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
    <link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
    <link href="assets/css/slick.css" rel="stylesheet">
    <link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">

    <title>Set Semula Kata Laluan Pengguna</title>

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
    <script type="text/javascript">
        function valid() {
            if (document.resetpass.newpassword.value != document.resetpass.conpassword.value) {
                alert("Uh-Oh! Kata Laluan dan Sahkan Kata Laluan tidak sepadan.");
                document.resetpass.conpassword.focus();
                return false;
            }
            return true;
        }
    </script>
    <style>
        body {
            background-image: url('../image/masjidutem.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #333;
        }
        .form-container {
            background-color: rgba(255, 255, 255, 0.8); /* White background with 80% opacity */
            border: none;
            box-shadow: none;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            margin: auto;
            margin-top: 100px;
        }
        .form-control {
            margin-bottom: 15px;
        }
        .signup-link {
            display: block;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>	
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-container">	
                            <form method="post" action="" name="resetpass" class="form-horizontal" onSubmit="return valid();">
                                <label for="" class="text-uppercase text-sm">No. Kad Pengenalan (IC)</label>
                                <input type="text" placeholder="" name="icnum" class="form-control mb" required>

                                <label for="" class="text-uppercase text-sm">Kata Laluan Baru</label>
                                <input type="password" placeholder="" name="newpassword" class="form-control mb" required 
                                       pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" 
                                       title="Mesti mengandungi sekurang-kurangnya satu nombor, satu huruf besar dan satu huruf kecil, serta 8 atau lebih aksara">

                                <label for="" class="text-uppercase text-sm">Sahkan Kata Laluan</label>
                                <input type="password" placeholder="" name="conpassword" class="form-control mb" required>							
                                
                                <input type="submit" name="resetpass" class="btn btn-primary btn-block" value="SET SEMULA KATA LALUAN">
                                <button type="button" onclick="location.href='user-index.php'" class="btn btn-danger btn-block">KEMBALI KE LOG MASUK</button>
                            </form>
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
