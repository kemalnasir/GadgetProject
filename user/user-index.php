<?php
session_start();
include('includes/config.php');

if (isset($_POST['login'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = md5($_POST['password']);
    
    $stmt = $mysqli->prepare("SELECT U_Email, U_ID, U_Roles FROM user WHERE (U_Email=? OR U_IC=?) AND U_Password=?");
    $stmt->bind_param('sss', $email, $email, $password);
    $stmt->execute();
    $stmt->bind_result($userEmail, $userId, $userRole);
    $stmt->fetch();
    
    if ($userId) {
        $_SESSION['id'] = $userId;
        if ($userRole == 'admin') {
            header("Location: ../admin/dashboard-view.php");
        } elseif ($userRole == 'staff') {
            header("Location: ../staff/dashboard.php");
        } elseif ($userRole == 'ptj') {
            header("Location: ../ptj/dashboard.php");
        } else {
            header("Location: user-viewprogram.php");
        }
        exit();
    } else {
        echo "<script>alert('No. Kad Pengenalan/Emel atau Kata Laluan tidak sah');</script>";
    }

    $stmt->close();
}

if (isset($_POST['back'])) {
    header("Location: user-forgotpassword.php");
    exit();
}
?>

<!doctype html>
<html lang="ms" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">

    <title>Log Masuk</title>

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
            if (document.registration.password.value != document.registration.cpassword.value) {
                alert("Uh-Oh! Kata Laluan dan Pengesahan Kata Laluan tidak sepadan.");
                document.registration.cpassword.focus();
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
                            <form action="" method="post" class="mt">
                                <label for="" class="text-uppercase text-sm">No. Kad Pengenalan/Emel</label>
                                <input type="text" placeholder="Emel/Nombor IC" name="email" class="form-control" required>
                                <label for="" class="text-uppercase text-sm">Kata Laluan</label>
                                <input type="password" placeholder="Kata Laluan" name="password" class="form-control" required>
                                <input type="submit" name="login" class="btn btn-primary btn-block" value="LOG MASUK">
                                <input type="submit" name="back" class="btn btn-danger btn-block" value="LUPA KATA LALUAN">
                                &nbsp
                                <a href="user-register.php" class="signup-link">Belum ada akaun? Daftar di sini</a>
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
