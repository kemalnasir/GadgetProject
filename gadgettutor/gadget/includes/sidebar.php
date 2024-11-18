<nav class="ts-sidebar">
	<ul class="ts-sidebar-menu">
		<li class="ts-label">Main</li>
		<?PHP 
		if(isset($_SESSION['id']))
			{ 
			?>
				<li><a href="student-packagemanage.php"><i class="fa fa-desktop"></i>Package Info</a></li>
			

				<li><a href="#"><i class="fa fa-files-o"></i>Manage Booking</a>
					<ul>
						<li><a href="student-bookpackage.php">Book Package</a></li>
						<li><a href="package-manage.php">Update Booking</a></li>
						<li><a href="package-remove.php">Remove Booking</a></li>
					</ul>
				</li>
				<li><a href="room-details.php"><i class="fa fa-file-o"></i> Payment Details</a></li>
				<li><a href="student-viewteacherdetail.php"><i class="fa fa-desktop"></i>Allocated Teacher</a></li>
				<li><a href="dashboard.php"><i class="fa fa-desktop"></i>Progress Lesson</a></li>
				<li><a href="student-manualbook.php"><i class="fa fa-files-o"></i>Manual Book</a></li>
				
			<?php 
			} 				
			else 
			{ 
			?>
				<li><a href="../index.php"><i class="fa fa-users"></i> Main Page</a></li>
				<li><a href="student-register.php"><i class="fa fa-files-o"></i> Student Registration</a></li>
				<li><a href="student-index.php"><i class="fa fa-users"></i> Student Login</a></li>

			<?php 
			} 
		?>
	</ul>
</nav>