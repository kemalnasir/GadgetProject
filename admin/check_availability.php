<?php
session_start();
//$aid=$_SESSION['id'];
require_once("includes/config.php");
include('identifier.php');
//For Email
if(!empty($_POST["email"])) 
{
	$email= $_POST["email"];
	if (filter_var($email, FILTER_VALIDATE_EMAIL)===false) 
	{
		echo "<span style='color:red'> Error: You did not enter a valid email.</span>";
		//echo "Error: You did not enter a valid email.";
	}
	else 
	{
		$result ="SELECT count(*) FROM user WHERE U_Email=?";
		$stmt = $mysqli->prepare($result);
		$stmt->bind_param('s',$email);
		$stmt->execute();
		$stmt->bind_result($count);
		$stmt->fetch();
		$stmt->close();
		if($count>0)
		{
			echo "<span style='color:red'> Email already exist. Use other email.</span>";
		}
	}
}

//For IC Number
if(!empty($_POST["icnum"])) 
{
	$icnum= $_POST["icnum"];
	$result ="SELECT count(*) FROM user WHERE U_IC=?";
	$stmt = $mysqli->prepare($result);
	$stmt->bind_param('s',$icnum);
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();

	if($count>0)
	{
		//echo"<script>alert('IC already exist. Please use other than that.');</script>";
		echo "<span style='color:red'> IC number already exist. Use other IC number.</span>";
		
	}
}


if(!empty($_POST["phonenum"])) 
{
	$phonenum= $_POST["phonenum"];
	$result ="SELECT count(*) FROM user WHERE U_PhoneNo=?";
	$stmt = $mysqli->prepare($result);
	$stmt->bind_param('s',$phonenum);
	$stmt->execute();
	$stmt->bind_result($count);
	$stmt->fetch();
	$stmt->close();
	if($count>0)
	{
		echo "<span style='color:red'> Phone number already exist. Please use other than that.</span>";
	}
}


if(!empty ($_POST["oldpassword"])) 
{
	$pass= md5(($_POST["oldpassword"]));
	$ai=$_SESSION['id'];

	$result ="SELECT U_Password FROM user WHERE U_Password=? && U_ID=?";
	$stmt = $mysqli->prepare($result);
	$stmt->bind_param('si',$pass,$ai);
	$stmt->execute();
	$stmt -> bind_result($result);
	$stmt -> fetch();
	$opass=$result;

	if($opass==$pass) 
		echo "<span style='color:green'> Password matched.</span>";
	else 
		echo "<span style='color:red'> Password do not matched.</span>";
}
?>