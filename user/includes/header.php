<?php 
if($_SESSION['id'])
{ 
	?>
<div class="brand clearfix" style="background-color: #34495e;">
    <a href="#" class="logo" style="font-size:16px; color: #ecf0f1;">UTeM E-Masjidlite</a>
    <span class="menu-btn"><i class="fa fa-bars" style="color: #ecf0f1;"></i></span>
		<ul class="ts-profile-nav">
			<li class="ts-account">
				<a href="#"><img src="img/dp.png" class="ts-avatar hidden-side" alt=""> Akaun <i class="fa fa-angle-down hidden-side"></i></a>
				<ul>
					<li><a href="user-profile.php">Profil Saya</a></li>
					<li><a href="user-logout.php">Log Keluar</a></li>
				</ul>
			</li>
		</ul>
	</div>
	<?php
} 
else 
{ 
	?>
	<div class="brand clearfix" style="background-color: #34495e;">
    <a href="#" class="logo" style="font-size:16px; color: #ecf0f1;">UTeM E-Masjidlite</a>
    <span class="menu-btn"><i class="fa fa-bars" style="color: #ecf0f1;"></i></span>
	</div>
	<?php 
} 
?>
