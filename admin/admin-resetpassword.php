<?php
session_start();
include('includes/config.php');

if(isset($_POST['resetpass']))
{
	$op=$_POST['icnum'];
	$np=$_POST['newpassword'];
	$sql="SELECT A_IC FROM ADMIN where A_IC=?";
	$chngpwd = $mysqli->prepare($sql);
	$chngpwd->bind_param('s',$op);
	$chngpwd->execute();
	$chngpwd->store_result(); 
    $row_cnt=$chngpwd->num_rows;;
	if($row_cnt>0)
	{
		$con="UPDATE ADMIN SET A_Password=? where A_IC=?";
		$chngpwd1 = $mysqli->prepare($con);
		$chngpwd1->bind_param('si',$np,$op);
		$chngpwd1->execute();
		echo "<script>alert('Password has been changed successfully! Please Login First.');</script>";
		//header("location:admin-login.php");
		header('Refresh:0.1; url=admin-login.php');
	}
	else
	{
		echo "<script>alert('Uh-Oh! No account found.');</script>";
	}	
}

if(isset($_POST['back']))
{
	header("location:admin-login.php");
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

	<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="assets/css/style.css" type="text/css">
	<link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
	<link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
	<link href="assets/css/slick.css" rel="stylesheet">
	<link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
	<link href="assets/css/font-awesome.min.css" rel="stylesheet">

	<title>Admin Reset Password</title>

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
	function valid()
	{
		if(document.resetpass.newpassword.value!= document.resetpass.conpassword.value)
		{
			alert("Uh-Oh! Password and Re-Type Password Field Do Not Match.");
			document.resetpass.conpassword.focus();
			return false;
		}
		return true;
	}
	</script>
</head>

<body>
<?php include('includes/headermain.php');?>
	<div class="login-page bk-img" style="background-image: url(image/adminpage.png);">
		<div class="form-content">
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-6" style="margin-top:1%">
						<h2 class="text-center text-bold mt-4x"></h2>
						<div class="well row pt-2x pb-3x bk-light">
							<div class="col-md-8 col-md-offset-2">	
								<form method="post" action="" name="resetpass" class="form-horizontal" onSubmit="return valid();">
									<label for="" class="text-uppercase text-sm">IC Number</label>
									<input type="text" placeholder="" name="icnum" class="form-control mb">
									<label for="" class="text-uppercase text-sm">New Password</label>
									<input type="password" placeholder="" name="newpassword" class="form-control mb">
									<label for="" class="text-uppercase text-sm"> Confirm Password</label>
									<input type="password" placeholder="" name="conpassword" class="form-control mb">							
									<input type="submit" name="resetpass" class="btn btn-primary btn-block" value="RESET PASSWORD">
									<input type="submit" name="resetpass" class="btn btn-primary btn-block" value="BACK TO LOGIN" >
								</form>
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
</body>
</html>