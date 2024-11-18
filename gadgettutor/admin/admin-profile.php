<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if(isset($_POST['update']))
{
    $Email = $_POST['email'];
    $Aid = $_SESSION['id'];

    $result = "SELECT count(*) FROM user WHERE U_Email=?";
    $stmt = $mysqli->prepare($result);
    $stmt->bind_param('s', $Email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    if($count > 0)
    {
        echo "<script>alert('Email already exists. Please use another email.');</script>";
    }
    else
    {
        $query = "UPDATE user SET U_Email=? WHERE U_ID=?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('si', $Email, $Aid);
        $stmt->execute();
        echo "<script>alert('Email has been updated successfully!');</script>";
    }
}

if(isset($_POST['updatephone']))
{
    $PhoneNum = $_POST['phonenum'];
    $Aid = $_SESSION['id'];

    $result = "SELECT count(*) FROM user WHERE U_PhoneNo=?";
    $stmt = $mysqli->prepare($result);
    $stmt->bind_param('s', $PhoneNum);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    if($count > 0)
    {
        echo "<script>alert('Phone number already exists. Please use another phone number.');</script>";
    }
    else
    {
        $query = "UPDATE user SET U_PhoneNo=? WHERE U_ID=?";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param('si', $PhoneNum, $Aid);
        $stmt->execute();
        echo "<script>alert('Phone number has been updated successfully!');</script>";
    }
}

if(isset($_POST['changepwd']))
{
    $op = md5($_POST['oldpassword']);
    $np = md5($_POST['newpassword']);
    $ai = $_SESSION['id'];

    $sql = "SELECT U_Password FROM user WHERE U_Password=? AND U_ID=?";
    $chngpwd = $mysqli->prepare($sql);
    $chngpwd->bind_param('si', $op, $ai);
    $chngpwd->execute();
    $chngpwd->store_result();
    $row_cnt = $chngpwd->num_rows;
    if($row_cnt > 0)
    {
        $con = "UPDATE user SET U_Password=? WHERE U_ID=?";
        $chngpwd1 = $mysqli->prepare($con);
        $chngpwd1->bind_param('si', $np, $ai);
        $chngpwd1->execute();
        $_SESSION['msg'] = "Password has been changed successfully!";
    }
    else
    {
        $_SESSION['msg'] = "Uh-oh! Old password does not match.";
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
    <title>Admin Profile</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap-select.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/fileinput.js"></script>
    <script src="js/chartData.js"></script>
    <script src="js/main.js"></script>
    <script src="js/validation.min.js"></script>
    <style>
        .help-block {
            font-size: 12px;
        }
    </style>
    <script type="text/javascript">
        function valid() {
            if(document.changepwd.newpassword.value != document.changepwd.cpassword.value) {
                alert("New Password and Confirm Password do not match!");
                document.changepwd.cpassword.focus();
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
<?php include('includes/header.php');?>
<div class="ts-main-content">
    <?php include('includes/sidebar.php');?>
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2></h2>
                    <?php
                    $aid = $_SESSION['id'];
                    $ret = "SELECT * FROM user WHERE U_ID=?";
                    $stmt = $mysqli->prepare($ret);
                    $stmt->bind_param('i', $aid);
                    $stmt->execute();
                    $res = $stmt->get_result();

                    while($row = $res->fetch_object()) {
                    ?>
                    <div class="row">
                    <br>
                    <br>
                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">Admin profile details</div>
                                <div class="panel-body">
                                    <form method="post" class="form-horizontal">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Name </label>
                                            <div class="col-sm-10">
                                                <input type="text" value="<?php echo $row->U_Name;?>" disabled class="form-control">
                                                <span class="help-block m-b-none">Name can't be changed.</span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Phone No</label>
                                            <div class="col-sm-10">
                                                <input type="tel" class="form-control" name="phonenum" id="phonenum" value="<?php echo $row->U_PhoneNo;?>" onBlur="checkAvailability()" required="required">
                                                <span id="phone-availability-status" class="help-block"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Email</label>
                                            <div class="col-sm-10">
                                                <input type="email" class="form-control" name="email" id="email" value="<?php echo $row->U_Email;?>" onBlur="checkAvailability()" required="required">
                                                <span id="email-availability-status" class="help-block"></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-8 col-sm-offset-2">
                                            <button class="btn btn-default" type="reset">Cancel</button>
                                            <input class="btn btn-primary" type="submit" name="update" value="Update Profile">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="panel panel-default">
                                <div class="panel-heading">Change Password</div>
                                <div class="panel-body">
                                    <form method="post" class="form-horizontal" name="changepwd" id="change-pwd" onSubmit="return valid();">
                                        <?php if(isset($_SESSION['msg'])): ?>
                                            <p style="color: green;"><?php echo htmlentities($_SESSION['msg']); ?><?php echo htmlentities($_SESSION['msg'] = ""); ?></p>
                                        <?php endif; ?>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Old Password</label>
                                            <div class="col-sm-8">
                                                <input type="password" name="oldpassword" id="oldpassword" class="form-control" onBlur="checkpass()" required="required">
                                                <span id="password-availability-status" class="help-block"></span>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">New Password</label>
                                            <div class="col-sm-8">
                                                <input type="password" class="form-control" name="newpassword" id="newpassword" required="required" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}">
                                                Must contain at least:
                                                <br>- One number</br>
                                                - One uppercase and one lowercase letter
                                                <br>- 8 or more characters</br>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-sm-4 control-label">Confirm Password</label>
                                            <div                                             class="col-sm-8">
                                                <input type="password" class="form-control" name="cpassword" id="cpassword" required="required">
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-sm-offset-4">
                                            <button class="btn btn-default" type="reset">Cancel</button>
                                            <input type="submit" name="changepwd" value="Change Password" class="btn btn-primary">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function checkAvailability() {
        $("#loaderIcon").show();
        var email = $("#email").val();
        var phonenum = $("#phonenum").val();

        if (email !== '') {
            jQuery.ajax({
                url: "check_availability.php",
                data: {email: email},
                type: "POST",
                success:function(data) {
                    $("#email-availability-status").html(data);
                    $("#loaderIcon").hide();
                },
                error:function() {
                    alert('Error checking email availability');
                }
            });
        }

        if (phonenum !== '') {
            jQuery.ajax({
                url: "check_availability.php",
                data: {phonenum: phonenum},
                type: "POST",
                success:function(data) {
                    $("#phone-availability-status").html(data);
                    $("#loaderIcon").hide();
                },
                error:function() {
                    alert('Error checking phone number availability');
                }
            });
        }
    }

    function checkpass() {
        $("#loaderIcon").show();
        var oldpassword = $("#oldpassword").val();

        if (oldpassword !== '') {
            jQuery.ajax({
                url: "check_availability.php",
                data: {oldpassword: oldpassword},
                type: "POST",
                success:function(data) {
                    $("#password-availability-status").html(data);
                    $("#loaderIcon").hide();
                },
                error:function() {
                    alert('Error checking old password');
                }
            });
        }
    }
</script>

</body>
</html>

