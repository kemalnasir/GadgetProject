<?php
session_start();
include('includes/config.php');

if(isset($_POST['login']))
{
	$email=$_POST['email'];
	$phonenum=$_POST['pn'];
	$stmt=$mysqli->prepare("SELECT U_Email,U_PhoneNo,U_Password FROM user WHERE (U_Email=? && U_PhoneNo=?) ");
	$stmt->bind_param('ss',$email,$phonenum);
	$stmt->execute();
	$stmt->bind_result($username,$email,$password);
	$rs=$stmt->fetch();
	if($rs)
	{
		header("location:user-resetpassword.php");
	}
	else
	{
		echo "<script>alert('Emel atau Nombor Telefon tidak sah');</script>";
	}
}

if(isset($_POST['back']))
{
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

	<title>Lupa Kata Laluan Pengguna</title>

	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
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
    </style>
</head>
<body>
	
	<?php include('includes/header.php');?>
	<div class="ts-main-content">
		<?php include('includes/sidebar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
                        <div class="form-container">					
								<form action="" class="mt" method="post">
									<label for="" class="text-uppercase text-sm">Emel </label>
									<input type="email" placeholder="example@gmail.com" name="email" class="form-control mb">
									<label for="" class="text-uppercase text-sm">Nombor Telefon </label>
									<input type="text" placeholder="0123456789" name="pn" class="form-control mb">							
									<input type="submit" name="login" class="btn btn-danger btn-block" value="LUPA KATA LALUAN">
									<input type="submit" name="back" class="btn btn-primary btn-block" value="KEMBALI" >
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
