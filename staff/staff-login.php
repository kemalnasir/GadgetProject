<?php
session_start();
include('includes/config.php');
if(isset($_POST['login']))
{
	$username=$_POST['username'];
	$password=$_POST['password'];
	$stmt=$mysqli->prepare("SELECT S_IC,S_Email,S_password,S_ID FROM staff WHERE (S_IC=?|| S_Email=?) and S_Password=? ");
	$stmt->bind_param('sss',$username,$username,$password);
	$stmt->execute();
	$stmt -> bind_result($username,$username,$password,$id);
	$rs=$stmt->fetch();
	$_SESSION['id']=$id;	
	if($rs)
	{  
		header("location: dashboard.php");
	}
	else
	{
		echo "<script>alert('Invalid IC/Email or Password');</script>";
	}
}

if(isset($_POST['back']))
{
	header("location:staff-forgotpassword.php");
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


	<title>Staff login</title>

	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
</head
<body>
	<?php include('includes/headermain.php');?>
	<div class="login-page bk-img" style="background-image: url(img/24.jpg);">
		<div class="form-content">
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-6" style="margin-top:1%">
						<h2 class="text-center text-bold mt-4x"></h2>
						<div class="well row pt-2x pb-3x bk-light">
							<div class="col-md-8 col-md-offset-2">
							
								<form action="" class="mt" method="post">
									<label for="" class="text-uppercase text-sm">Your IC or Email</label>
									<input type="text" placeholder="IC Or Email" name="username" class="form-control mb">
									<label for="" class="text-uppercase text-sm">Password</label>
									<input type="password" placeholder="Password" name="password" class="form-control mb">
									

									<input type="submit" name="login" class="btn btn-primary btn-block" value="LOGIN">
									<input type="submit" name="back" class="btn btn-primary btn-block" value="FORGET PASSWORD">
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