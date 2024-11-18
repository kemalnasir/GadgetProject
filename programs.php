<?php
session_start();
include('includes/config.php');


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


	
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
	<link rel="stylesheet" href="assets/css/style.css" type="text/css">
	<link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
	<link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
	<link href="assets/css/slick.css" rel="stylesheet">
	<link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
	<link href="assets/css/font-awesome.min.css" rel="stylesheet">


	<link rel="stylesheet" id="switcher-css" type="text/css" href="assets/switcher/css/switcher.css" media="all" />
	<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/red.css" title="red" media="all" data-default-color="true" />
	<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/orange.css" title="orange" media="all" />
	<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/blue.css" title="blue" media="all" />
	<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/pink.css" title="pink" media="all" />
	<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/green.css" title="green" media="all" />
	<link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/purple.css" title="purple" media="all" />

	<link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet"> 


	<title>List Subject</title>
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

<?php include('includes/headermain.php');?>

	<div class="ts-main-content">
			
		<div class="">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<h2 class="" style="margin-top:1%"></h2>
						<div class="panel panel-default">
							<div class="panel-heading">All Subject Details</div>
							<div class="panel-body">
								<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									  <thead>
                                    <tr>
    <th>No.</th>
    <th>Program Name</th>
    <th>Category</th>
    <th>Image</th>
    <th>Description</th>
    <th>Date & Time</th>
    <th>Location</th>
</tr>
</thead>
<tbody>
    <?php
    $query = "SELECT * from program JOIN category ON program.C_ID = category.C_ID";
    $result = $mysqli->query($query);
    if ($result && $result->num_rows > 0) {
        $count = 1;
        while ($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $row['P_Name']; ?></td>
                <td><?php echo $row['C_Name']; ?></td>
                <td><img src="staff/image/<?php echo $row['P_Image']; ?>" width="180px" height="80px"></td>
                <td><?php echo $row['P_Description']; ?></td>
                <td><?php echo $row['P_DateTime']; ?></td>
                <td><?php echo $row['P_Location']; ?></td>
            </tr>
            <?php
            $count++;
        }
        $result->free();
    } else {
        echo "<tr><td colspan='8'>No programs found.</td></tr>";
    }
    ?>
</tbody>

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



	<?php include('includes/footer.php');?>
	<div id="back-top" class="back-top"> <a href="#top"><i class="fa fa-angle-up" aria-hidden="true"></i> </a> </div>


</body>

</html>
