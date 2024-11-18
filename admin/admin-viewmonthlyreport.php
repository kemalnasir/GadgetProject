<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
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
	<title>Monthly Report</title>
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
	<?php include('includes/header.php');?>

	<div class="ts-main-content">
			<?php include('includes/sidebar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<h2 class="page-title" style="margin-top:2%"></h2>
						<div class="panel panel-default">
							<div class="panel-heading">Report By Month</div>
							<div class="panel-body">
															
									
									<form action="" class="form-inline" method="GET">
										

										<div class="input-group">											
											<select name="subject" id="subject" class="form-control" required> 													
												<option value="">Select Subject</option>
												<?php 
															$query ="SELECT * FROM SubjectType";
															$stmt2 = $mysqli->prepare($query);
															$stmt2->execute();
															$res=$stmt2->get_result();
															while($row=$res->fetch_object())
															{
																?>
																<option value="<?php echo $row->Name;?>"> 
																	<?php 																		
																		echo $row->Name;
																	?>
																</option>
																<?php 
															} 
															echo "</select>";
														?>
											</select>
										</div>

										<div class="input-group">											
											<select name="month" id="month" class="form-control" > 													
												<option value="">Select Month</option>
													<option value="January">January</option>
													<option value="February">February</option>
													<option value="March">March</option>
													<option value="April">April</option>
													<option value="May">May</option>
													<option value="June">June</option>
													<option value="July">July</option>
													<option value="August">August</option>
													<option value="September">September</option>
													<option value="October">October</option>
													<option value="November">November</option>
													<option value="December">December</option>	
													
											</select>
										</div>

										<div class="input-group">											
											<select name="date_1" id="year" class="form-control" required> 													
												<option value="">Select Year</option>
												<?php 
													for($i=0;$i<=1;$i++)
													{
														$month=date('Y',strtotime("Y, last day of -$i year"));
														echo "<option name='$month'>$month</option>";																
													}
													echo "</select>";
												?>
											</select>
										</div>
			
										<input type="submit" value="Review" name="Search" class="btn btn-primary" style="margin-top: 4px;"><br><br>
										
										
											<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">							
											<thead>
												<tr>
													<th>No.</th>
													<th>IC No.</th>
													<th>Name</th>											
													<th>Phone No.</th>
													<th>Email</th>	
													<th>Subject Enrolled</th>
													<th>Month Enrolled</th>
													<th>Fee</th>										
												</tr>
											</thead>
											<tbody>
											<?php 												

												if(isset($_GET['Search']))
												{
													$date_1 = $_GET['date_1'];	
													$subject = $_GET['subject'];
													$month = $_GET['month'];

													echo " Review For: $subject : $month, $date_1";

													$sql= "SELECT * FROM student s 
														   inner join registrationsubject r on s.studentid=r.studentid 																		 													
													       inner join subject b on b.subjectid=r.subjectid 
													       inner join subjecttype t on t.subjectcode=b.subjectcode																		  
													       inner join payment p on p.registrationid=r.registrationid 																		  	
													       WHERE p.R_Date=? && t.Name=? && p.R_Date LIKE '%$month'
													       order by s.S_FName";

													$stmt= $mysqli->prepare($sql);
													$stmt->bind_param('is',$date_1,$subject);
													$stmt->execute();
													$res=$stmt->get_result();
													$cnt=1;
													while($row=$res->fetch_object())
													{	 
																						
	  													?>
															
															<tr>
																<td><?php echo $cnt;;?></td>				
																<td><?php echo $row->S_ICNum;?></td>
																<td><?php echo $row->S_FName;?> <?php echo $row->S_LName;?></td>				
																<td><?php echo $row->S_PhoneNum;?></td>
																<td><?php echo $row->S_Email;?></td>
																<td><?php echo $row->Name;?> - <?php echo $row->Age;?> Years Old</td>
																<td><?php echo $row->R_Date;?></td>
																<td>RM<?php echo $row->P_Amount;?></td>				
																</td>
															</tr>
														<?php
														$cnt=$cnt+1;
													}
												}
											?>
											</tbody>
											</table>
										</div>
									</form>
									<div class="panel-body">
								
										<?php 													
											//$ret="SELECT sum(P_Amount) as a FROM Payment inner join  WHERE R_Date=?";
											$ret= "SELECT p.R_Date, sum(P_Amount) as a, count(s.StudentID) as b FROM student s 
														   inner join registrationsubject r on s.studentid=r.studentid 																		 													
													       inner join subject b on b.subjectid=r.subjectid 
													       inner join subjecttype t on t.subjectcode=b.subjectcode																		  
													       inner join payment p on p.registrationid=r.registrationid 																		  	
													       WHERE p.R_Date=? && t.Name=? && p.R_Date LIKE '%$month%'
													       order by s.S_FName";
											$stmt= $mysqli->prepare($ret);
											$stmt->bind_param('is',$date_1,$subject);
											$stmt->execute();
											$res=$stmt->get_result();										
											while($row=$res->fetch_object())
											{
	  											?>											
													<tr>
														------------------------------------------------------------------------------------------------------------
														<br><td> Total Student: <b><?php echo $row->b;?></b> student(s)</td></br>
													
														<br><td> Total Sales For <b><?php echo $subject;?></b> In <b><?php echo $month;?>, <?php echo $date_1;?></b>: <b>RM<?php echo $row->a;?></b></td></br>	
														-------------------------------------------------------------------------------------------------------------
														
													</tr>			
												<?php											
											} 											
										?>		
								</table>	
							</div>
						</div>
					</div>
				</div>			
			</div>
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

</body>

</html>
