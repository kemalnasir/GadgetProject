<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

$con  = mysqli_connect("localhost","root","","drivingschool");
if (!$con) 
	{   
    echo "Problem in database connection!" . mysqli_error();
	}
 
else
	{
		$sql ="SELECT License.Type, count(StudentID) as a FROM License 
			   inner join Registration on License.LicenseID = Registration.LicenseID 
			   inner join Payment  on Payment.RegistrationID = Registration.RegistrationID 
			   where Payment.P_Status= 'Paid' && Payment.P_Description = 'Payment 1' group by License.Type";

		$result = mysqli_query($con,$sql);
		$chart_data="";
		while ($row = mysqli_fetch_array($result)) 
		{ 
			$packagecategory[]  = $row['Type'];
			$totalstudent[] = $row['a'];
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
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Graph</title> 
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">
</head>

<body>
<?php include("includes/header.php");?>

	<div class="ts-main-content">
		<?php include("includes/sidebar.php");?>
		<div class="content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<h2  style="margin-top:6%"></h2>							
						<div class="row">
							<div class="col-md-12">
								<div class="row">
									
									<div class="col-md-4">
										<div class="panel panel-default">
											<div class="panel-body bk-primary text-light">
												<div class="stat-panel text-center">
													<?php											
													$result ="SELECT count(*) FROM student";
													$stmt = $mysqli->prepare($result);
													$stmt->execute();
													$stmt->bind_result($count);
													$stmt->fetch();
													$stmt->close();
													?>
												<div class="stat-panel-number h1 "><?php echo $count;?></div>
											<div class="stat-panel-title text-uppercase"> Students</div>
										</div>
									</div>
									<a href="admin-viewallstudent.php" class="block-anchor panel-footer">Full Details<i class="fa fa-arrow-right"></i></a>								
																	
									</div>
									</div>																	
												
									<div class="col-md-4">
										<div class="panel panel-default">
											<div class="panel-body bk-primary text-light">
												<div class="stat-panel text-center">
													<?php											
													$result ="SELECT count(*) FROM teacher";
													$stmt = $mysqli->prepare($result);
													$stmt->execute();
													$stmt->bind_result($count);
													$stmt->fetch();
													$stmt->close();
													?>
												<div class="stat-panel-number h1 "><?php echo $count;?></div>
											<div class="stat-panel-title text-uppercase"> Teachers</div>
										</div>
									</div>
									<a href="admin-viewallteacher.php" class="block-anchor panel-footer">Full Details<i class="fa fa-arrow-right"></i></a>
								
									</div>
									</div>																	
									</div>

								
								
					
						<h2  style="margin-top:2%"></h2>	
						<div style="width:60%;height:10%;text-align:center">						
							<div>Total License Purchased</div>
							<canvas id="chartjs_bar"></canvas> 
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

<script src="//code.jquery.com/jquery-1.9.1.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
<script type="text/javascript">

var ctx = document.getElementById("chartjs_bar").getContext('2d');
var myChart = new Chart(ctx, 
{
   type: 'bar',
   data: 
   {
       labels:<?php echo json_encode($packagecategory); ?>,
       datasets: 
	   [{
			backgroundColor: 
			[
				"#f0ad7d",
				"#eaf07d",
				"#8dafc7",
				"#d3a3f7",							
				"#92c78d",
				"#5969ff",
                "#ff407b",
                "#25d5f2",
                "#ffc750",
                "#2ec551",
                "#7040fa",
                "#ff004e"
            ],
			data:<?php echo json_encode($totalstudent); ?>,			
       }]	  
},

/*options: 
{
scales: {
         yAxes: [{
             ticks: {
             	min: 0,
             	max : 3,
                 beginAtZero: false,
                 stepSize : 1,
             }
         }],
     },
	legend: 
	{
		display: true,
        position: 'bottom',		
        labels: 
		{
            fontColor: '#71748d',
            fontFamily: 'Circular Std Book',
            fontSize: 14,
        }
    },
 }

 });*/

 options: 
{
	     scales: {
	     	 xAxes: [{
         	scaleLabel: {
        display: true,
        labelString: 'License'
      },
         }],
         yAxes: [{
         	scaleLabel: {
        display: true,
        labelString: 'Total Student Each License'
      },
             ticks: {
             	min: 0,
             	max : 10,
                 beginAtZero: false,
                 stepSize : 1,
             }
         }],
     },
	legend: 
	{
		display:false,
        position: 'bottom',
 
        labels: 
		{
            fontColor: '#71748d',
            fontFamily: 'Circular Std Book',
            fontSize: 14,
        }
    },
 
 }
 });
</script>

</html>