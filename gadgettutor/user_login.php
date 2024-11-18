<?php
session_start();
include('includes/checklogin.php');
include('includes/config.php');

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hash the password using MD5

    $stmt = $mysqli->prepare("SELECT U_Email, U_Password, U_ID, U_Roles FROM user WHERE U_Email=? and U_Password=? ");
    $stmt->bind_param('ss', $email, $password);
    $stmt->execute();
    $stmt->bind_result($email, $password, $userId, $userRole);
    $stmt->fetch();

    if ($userId) {
        $_SESSION['id'] = $userId;
        
        // Check user role and redirect accordingly
        if ($userRole == 'admin') {
            header("location: admin/dashboard.php");
        } else {
            header("location: gadget/gadgetview.php");
        }
        exit();
    } else {
        echo "<script>alert('Invalid Email or Password');</script>";
    }
}

if(isset($_POST['back'])) {
    header("location: index.php");
    exit();
}

if(isset($_POST['resetpassword'])) {
    header("location: user-resetpassword.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Login</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-image: url('photo/okay.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: 'Lato', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .login-page {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .form-content {
            background: rgba(255, 255, 255, 0.8);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .form-content h2 {
            margin-bottom: 20px;
            font-weight: bold;
            color: #333;
            text-align: center;
        }
        .form-content .form-group {
            margin-bottom: 20px;
        }
        .form-content label {
            font-weight: bold;
            color: #333;
        }
        .form-content .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .form-content .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        .form-content .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .form-content .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .form-content .btn-block {
            padding: 10px 0;
            font-size: 16px;
        }
    </style>
</head>
<body>
    <div class="login-page">
        <div class="form-content">
            <h2>Login</h2>
            <form action="" method="post" onsubmit="return validateForm()">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="Enter Your Email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                </div>
                <input type="submit" class="btn btn-primary btn-block" name="login" value="Login">
                <input type="submit" class="btn btn-danger btn-block" name="back" value="Back">
                <p class="text-center mt-3"><a href="user-resetpassword.php">Forgot Password?</a></p>
            </form>
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

     <script>
        function validateForm() {
            var email = document.getElementById("email").value;
            var password = document.getElementById("password").value;
            if (email == "" || password == "") {
                alert("Both fields must be filled out");
                return false;
            }
            return true;
        }
    </script>
</body>
</html>
