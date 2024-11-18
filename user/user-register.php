<?php
session_start();
include('includes/config.php');
include('identifier.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Adjust the path as necessary

if (isset($_POST['submit'])) {
    $icnum = $_POST['icnum'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $phonenum = $_POST['phonenum'];
    $gender = $_POST['gender'];
    $password = md5($_POST['password']);
    $roles = 'user'; // Set role to 'user'

    // Check for existing user
    $result = "SELECT count(*) FROM user WHERE U_IC=? OR U_Email=?";
    $stmt = $mysqli->prepare($result);
    $stmt->bind_param('ss', $icnum, $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "<script>alert('Nombor IC atau emel telah didaftarkan.');</script>";
    } else {
        $query = "INSERT INTO user (U_IC, U_FName, U_LName, U_Email, U_PhoneNo, U_Gender, U_Password, U_Roles, U_RegDate) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('ssssssss', $icnum, $fname, $lname, $email, $phonenum, $gender, $password, $roles);

        if ($stmt->execute()) {
            // Send email notification
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
                $mail->SMTPAuth = true;
                $mail->Username = 'syakiraimn2@gmail.com'; // Replace with your SMTP username
                $mail->Password = 'yjoi uies iodo oohj'; // Replace with your SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('syakiraimn2@gmail.com', 'E-Masjidlite');
                $mail->addAddress($email); // Send to the newly registered user's email
                $mail->isHTML(true);
                $mail->Subject = 'Pendaftaran Berjaya!';

                // Customize the email body with user's first name and last name
                $emailBody = "
                    <h2>$fname $lname, Terima kasih kerana membuat pendaftaran di platform E-masjidlite.</h2>
                    <p>Anda telah berjaya mendaftar di platform E-masjidlite.</p>
                    <p>Sekian, terima kasih.</p>
                    <p>Admin E-masjidlite</p>
                ";

                $mail->Body = $emailBody;

                if ($mail->send()) {
                    echo "<script>alert('Terima kasih, Anda telah berjaya didaftarkan dan Email telah dihantar! Sila Log Masuk Terlebih Dahulu.');</script>";
                    header('Refresh:0.1; url=user-index.php');
                } else {
                    throw new Exception('Penghantaran email gagal. Kesalahan: ' . $mail->ErrorInfo);
                }
            } catch (Exception $e) {
                echo "<script>alert('Penghantaran email gagal. Kesalahan: {$e->getMessage()}');</script>";
            }
        } else {
            echo "<script>alert('Ralat: Gagal menjalankan kueri.');</script>";
        }
        $stmt->close();
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

    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
    <link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
    <link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
    
    <title>Pendaftaran</title>
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
    <script type="text/javascript" src="http://code.jquery.com/jquery.min.js"></script>

    <script type="text/javascript">
    function valid() {
        if (document.registration.password.value != document.registration.cpassword.value) {
            alert("Ooops! Kata Laluan dan Sahkan Kata Laluan tidak sepadan.");
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
        .panel {
            background: rgba(255, 255, 255, 0.8); /* Set transparency */
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Add a subtle shadow */
        }
        .form-horizontal {
            width: 600px;
            margin: 0 auto; /* Center the form */
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
                    <div class="col-md-8">                    
                        <h2 class=""></h2>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-primary">
                                    <div class="panel-heading">Isi Semua Maklumat</div>
                                    <div class="panel-body">
                                        <form method="post" action="" name="registration" class="form-horizontal" onSubmit="return valid();">                                            
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label"> No. Kad Pengenalan : </label>
                                            <div class="col-sm-8">
                                                <input type="text" name="icnum" id="icnum"  class="form-control" required="required" onBlur="checkICAvailability()" placeholder="Contoh: 990104075445">
                                                <span id="user-reg-availability" style="font-size:12px;"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Nama Pertama : </label>
                                            <div class="col-sm-8">
                                                <input type="text" name="fname" id="fname" class="form-control" required="required" >
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Nama Akhir : </label>
                                            <div class="col-sm-8">
                                                <input type="text" name="lname" id="lname" class="form-control" required="required">
                                            </div>
                                        </div>        
                                        
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Jantina</label>
                                            <div class="col-sm-8">                                                
                                                <input type="radio" id="male" name="gender" value="Lelaki" required="required">
                                                <label for="male">Lelaki</label><br>
                                                <input type="radio" id="female" name="gender" value="Perempuan" required="required">
                                                <label for="female">Perempuan</label><br>                                            
                                            </div>    
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">No. Telefon : </label>
                                            <div class="col-sm-8">
                                                <input type="text" name="phonenum" id="phonenum"  class="form-control" onBlur="checkPhoneAvailability()" required="required" placeholder="Contoh: 01111482769">
                                                <span id="user-phonenum-status" style="font-size:12px;"></span>
                                            </div>
                                        </div>
                                    
                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Emel : </label>
                                            <div class="col-sm-8">
                                                <input type="email" name="email" id="email"  class="form-control" onBlur="checkEmailAvailability()" required="required" placeholder="Contoh: example@gmail.com">
                                                <span id="user-availability-status" style="font-size:12px;"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Kata Laluan: </label>
                                            <div class="col-sm-8">
                                                <input type="password" name="password" id="password"  class="form-control" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" >
                                           Mesti mengandungi sekurang-kurangnya:
						                    <br>- Satu nombor</br>
						                    - Satu huruf besar dan satu huruf kecil
						                    <br>- 8 atau lebih aksara</br>
                                                </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Sahkan Kata Laluan : </label>
                                            <div class="col-sm-8">
                                                <input type="password" name="cpassword" id="cpassword"  class="form-control" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" >
                                                </div>
                                        </div>

                                        <div class="col-sm-4 col-sm-offset-4">
                                            <button class="btn btn-danger" type="reset">RESET</button>
                                            <input type="submit" name="submit" Value="DAFTAR" class="btn btn-primary">
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

    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/modernizr/modernizr.js"></script>
    <script src="vendor/jquery-cookie/jquery.cookie.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="vendor/switchery/switchery.min.js"></script>
    <script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
    <script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="vendor/autosize/autosize.min.js"></script>
    <script src="vendor/selectFx/classie.js"></script>
    <script src="vendor/selectFx/selectFx.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/form-elements.js"></script>
    <script>
        jQuery(document).ready(function() {
            Main.init();
            FormElements.init();
        });

        $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
        startDate: ''
        });
    </script>
    <script type="text/javascript">
        $('#timepicker1').timepicker();
    </script>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>

    <script>
    var myInput = document.getElementById("password");
    var letter = document.getElementById("letter");
    var capital = document.getElementById("capital");
    var number = document.getElementById("number");
    var length = document.getElementById("length");

    myInput.onfocus = function() {
        document.getElementById("message").style.display = "block";
    }

    myInput.onblur = function() {
        document.getElementById("message").style.display = "none";
    }

    myInput.onkeyup = function() {
        var lowerCaseLetters = /[a-z]/g;
        if(myInput.value.match(lowerCaseLetters)) {
            letter.classList.remove("invalid");
            letter.classList.add("valid");
        } else {
            letter.classList.remove("valid");
            letter.classList.add("invalid");
        }

        var upperCaseLetters = /[A-Z]/g;
        if(myInput.value.match(upperCaseLetters)) {
            capital.classList.remove("invalid");
            capital.classList.add("valid");
        } else {
            capital.classList.remove("valid");
            capital.classList.add("invalid");
        }

        var numbers = /[0-9]/g;
        if(myInput.value.match(numbers)) {
            number.classList.remove("invalid");
            number.classList.add("valid");
        } else {
            number.classList.remove("valid");
            number.classList.add("invalid");
        }

        if(myInput.value.length >= 8) {
            length.classList.remove("invalid");
            length.classList.add("valid");
        } else {
            length.classList.remove("valid");
            length.classList.add("invalid");
        }
    }
    </script>

    <script>
    function checkEmailAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data:'email='+$("#email").val(),
            type: "POST",
            success:function(data) {
                $("#user-availability-status").html(data);
                $("#loaderIcon").hide();
            },
            error:function () {
                event.preventDefault();
                alert('error');
            }
        });
    }
    </script>

    <script>
    function checkPhoneAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data:'phonenum='+$("#phonenum").val(),
            type: "POST",
            success:function(data) {
                $("#user-phonenum-status").html(data);
                $("#loaderIcon").hide();
            },
            error:function () {
                event.preventDefault();
                alert('error');
            }
        });
    }
    </script>

    <script>
    function checkICAvailability() {
        $("#loaderIcon").show();
        jQuery.ajax({
            url: "check_availability.php",
            data:'icnum='+$("#icnum").val(),
            type: "POST",
            success:function(data) {
                $("#user-reg-availability").html(data);
                $("#loaderIcon").hide();
            },
            error:function () {
                event.preventDefault();
                alert('error');
            }
        });
    }
    </script>

</body>
</html>

