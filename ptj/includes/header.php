<div class="brand clearfix" style="background-color: #34495e;">
    <?php 
    // Check if user is logged in (assuming $_SESSION['id'] indicates login status)
    if(isset($_SESSION['id'])) { 
        ?>
        <a href="#" class="logo" style="font-size:16px; color:#fff">UTeM E-Masjidlite</a>
        <span class="menu-btn"><i class="fa fa-bars"></i></span>
        <ul class="ts-profile-nav">
            <li>
                <a href="ptjfetchnotification.php">
                    <i class="fa fa-bell fa-lg"></i>
                    <?php
                    // Get count of approved programs
                    $approvedCount = 0;
                    $query = "SELECT COUNT(*) AS count FROM program WHERE P_Status = 'dalam semakan'";
                    $result = $mysqli->query($query);
                    if ($result && $result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $approvedCount = $row['count'];
                    }
                    ?>
                    <span class="badge"><?php echo $approvedCount; ?></span>
                </a>
            </li>
            <li class="ts-account">
                <a href="#">
                    <img src="img/admin.png" class="ts-avatar hidden-side" alt="">
                    Akaun <i class="fa fa-angle-down hidden-side"></i>
                </a>
                <ul>
                    <li><a href="ptj-profile.php">Akaun Saya</a></li>
                    <li><a href="ptj-logout.php">Log Keluar</a></li>
                </ul>
            </li>
        </ul>
    <?php } else { ?>
        <a href="#" class="logo" style="font-size:16px;">UTeM E-Masjidlite</a>
        <span class="menu-btn"><i class="fa fa-bars"></i></span>
    <?php } ?>
</div>
