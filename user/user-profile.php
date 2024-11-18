<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
//include('identifier.php');
check_login();

if (isset($_POST['update'])) {
    $Email = $_POST['email'];
    $PhoneNum = $_POST['phonenum'];
    $Aid = $_SESSION['id'];

    // Semak jika emel sudah wujud
    $result = "SELECT count(*) FROM user WHERE U_Email=?";
    $stmt = $mysqli->prepare($result);
    $stmt->bind_param('s', $Email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    if ($count > 0) {
        echo "<script>alert('Emel sudah wujud. Sila gunakan emel lain.');</script>";
    } else {
        // Kemaskini emel
        $query = "UPDATE user SET U_Email=? WHERE U_ID=?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('si', $Email, $Aid);
        $stmt->execute();
        echo "<script>alert('Emel telah berjaya dikemaskini!');</script>";
    }

    // Semak jika nombor telefon sudah wujud
    $result = "SELECT count(*) FROM user WHERE U_PhoneNo=?";
    $stmt = $mysqli->prepare($result);
    $stmt->bind_param('s', $PhoneNum);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    if ($count > 0) {
        echo "<script>alert('Nombor telefon sudah wujud. Sila gunakan nombor telefon lain.');</script>";
    } else {
        // Kemaskini nombor telefon
        $query = "UPDATE user SET U_PhoneNo=? WHERE U_ID=?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('si', $PhoneNum, $Aid);
        $stmt->execute();
        echo "<script>alert('Nombor telefon telah berjaya dikemaskini!');</script>";
    }
}

// Kod untuk menukar kata laluan
if (isset($_POST['changepwd'])) {
    $op = md5($_POST['oldpassword']);
    $np = md5($_POST['newpassword']);
    $ai = $_SESSION['id'];

    $sql = "SELECT U_Password FROM user WHERE U_Password=? AND U_ID=?";
    $chngpwd = $mysqli->prepare($sql);
    $chngpwd->bind_param('si', $op, $ai);
    $chngpwd->execute();
    $chngpwd->store_result();
    $row_cnt = $chngpwd->num_rows;
    if ($row_cnt > 0) {
        $con = "UPDATE user SET U_Password=? WHERE U_ID=?";
        $chngpwd1 = $mysqli->prepare($con);
        $chngpwd1->bind_param('si', $np, $ai);
        $chngpwd1->execute();
        $_SESSION['msg'] = "Kata laluan telah berjaya ditukar!";
    } else {
        $_SESSION['msg'] = "Ops! Kata laluan lama tidak sepadan.";
    }
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
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery-1.11.3-jquery.min.js"></script>
    <script src="js/validation.min.js"></script>
    <script type="text/javascript">
        function valid() {
            if (document.changepwd.newpassword.value != document.changepwd.cpassword.value) {
                alert("Kata Laluan Baru dan Pengesahan Kata Laluan tidak sepadan!");
                document.changepwd.cpassword.focus();
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                    <br>
                        <h2 class="page-title"></h2>
                        <?php
                        $aid = $_SESSION['id'];
                        $ret = "SELECT * FROM user WHERE U_ID=?";
                        $stmt = $mysqli->prepare($ret);
                        $stmt->bind_param('i', $aid);
                        $stmt->execute();
                        $res = $stmt->get_result();
                        while ($row = $res->fetch_object()) {
                        ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Butiran Profil</div>
                                        <div class="panel-body">
                                            <form method="post" class="form-horizontal">
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">No. Kad Pengenalan</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" value="<?php echo $row->U_IC; ?>" disabled class="form-control"><span class="help-block m-b-none">No. Kad Pengenalan tidak boleh diubah.</span>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Nama</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" value="<?php echo $row->U_FName . ' ' . $row->U_LName; ?>" disabled class="form-control"><span class="help-block m-b-none">Nama tidak boleh diubah.</span>
                                                    </div>
                                                </div>

                                                 <div class="form-group">
                                                    <label class="col-sm-2 control-label">Jantina</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" value="<?php echo $row->U_Gender; ?>" disabled class="form-control"><span class="help-block m-b-none">Jantina tidak boleh diubah.</span>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Nombor Telefon</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" name="phonenum" id="phonenum" value="<?php echo $row->U_PhoneNo; ?>" required>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-sm-2 control-label">Emel</label>
                                                    <div class="col-sm-10">
                                                        <input type="email" class="form-control" name="email" id="email" value="<?php echo $row->U_Email; ?>" required>
                                                    </div>
                                                </div>
                                                <div class="col-sm-8 col-sm-offset-2">
                                                    <button class="btn btn-default" type="reset">Batal</button>
                                                    <input class="btn btn-primary" type="submit" name="update" value="Kemaskini Profil">
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <?php
                        }
                                ?>
                                <div class="col-md-6">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">Tukar Kata Laluan</div>
                                        <div class="panel-body">
                                            <form method="post" class="form-horizontal" name="changepwd" id="change-pwd" onSubmit="return valid();">
                                                <?php
                                                if (isset($_SESSION['msg'])) {
                                                ?>
                                                    <p style="color: green"><?php echo htmlentities($_SESSION['msg']); ?></p>
                                                    <p style="color: red"><?php echo htmlentities($_SESSION['msg'] = ""); ?></p>
                                                <?php
                                                }
                                                ?>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Kata Laluan Lama</label>
                                                    <div class="col-sm-8">
                                                        <input type="password" name="oldpassword" id="oldpassword" class="form-control" required>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Kata Laluan Baru</label>
                                                    <div class="col-sm-8">
                                                    <input type="password" class="form-control" name="newpassword" id="newpassword" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                                                        Mesti mengandungi sekurang-kurangnya:
                                                        <br>- Satu nombor</br>
	                                                    - Satu huruf besar dan huruf kecil
	                                                    <br>- 8 atau lebih aksara</br>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-sm-4 control-label">Pengesahan Kata Laluan</label>
                                                    <div class="col-sm-8">
                                                        <input type="password" class="form-control" name="cpassword" id="cpassword" required pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                                                    </div>
                                                </div>
                                                <div class="col-sm-6 col-sm-offset-4">
                                                    <button class="btn btn-default" type="reset">Batal</button>
                                                    <input type="submit" name="changepwd" value="Tukar Kata Laluan" class="btn btn-primary">
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
    <script>
        function checkAvailability() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "check_availability.php",
                data:'email='+$("#email").val(),
                type: "POST",
                success:function(data) {
                    $("#user-availability-status").html(data);
                    $("#loaderIcon").hide();
                },
                error:function() {
                    event.preventDefault();
                    alert('Ralat');
                }
            });
        }

        function checkpass() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "check_availability.php",
                data:'oldpassword='+$("#oldpassword").val(),
                type: "POST",
                success:function(data) {
                    $("#password-availability-status").html(data);
                    $("#loaderIcon").hide();
                },
                error:function () {
                    // Handle error
                }
            });
        }

        function checkemail() {
            $("#loaderIcon").show();
            jQuery.ajax({
                url: "check_availability.php",
                data:'email='+$("#email").val(),
                type: "POST",
                success:function(data) {
                    $("#email-availability-status").html(data);
                    $("#loaderIcon").hide();
                },
                error:function () {
                    // Handle error
                }
            });
        }
    </script>

    <script>
        var myInput = document.getElementById("psw");
        var letter = document.getElementById("letter");
        var capital = document.getElementById("capital");
        var number = document.getElementById("number");
        var length = document.getElementById("length");

        // Apabila pengguna klik pada medan kata laluan, paparkan kotak mesej
        myInput.onfocus = function() {
            document.getElementById("message").style.display = "block";
        }

        // Apabila pengguna klik di luar medan kata laluan, sembunyikan kotak mesej
        myInput.onblur = function() {
            document.getElementById("message").style.display = "none";
        }

        // Apabila pengguna mula menaip sesuatu dalam medan kata laluan
        myInput.onkeyup = function() {
            // Sahkan huruf kecil
            var lowerCaseLetters = /[a-z]/g;
            if(myInput.value.match(lowerCaseLetters)) {
                letter.classList.remove("invalid");
                letter.classList.add("valid");
            } else {
                letter.classList.remove("valid");
                letter.classList.add("invalid");
            }

            // Sahkan huruf besar
            var upperCaseLetters = /[A-Z]/g;
            if(myInput.value.match(upperCaseLetters)) {
                capital.classList.remove("invalid");
                capital.classList.add("valid");
            } else {
                capital.classList.remove("valid");
                capital.classList.add("invalid");
            }

            // Sahkan nombor
            var numbers = /[0-9]/g;
            if(myInput.value.match(numbers)) {
                number.classList.remove("invalid");
                number.classList.add("valid");
            } else {
                number.classList.remove("valid");
                number.classList.add("invalid");
            }

            // Sahkan panjang
            if(myInput.value.length >= 8) {
                length.classList.remove("invalid");
                length.classList.add("valid");
            } else {
                length.classList.remove("valid");
                length.classList.add("invalid");
            }
        }
    </script>

</body>
</html>

                                                       
