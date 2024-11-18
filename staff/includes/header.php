<div class="brand clearfix" style="background-color: #34495e;">
    <?php if($_SESSION['id']) { ?>
        <ul class="ts-profile-nav">
            <li>
                <a href="fetchnotification.php">
                    <i class="fa fa-bell fa-lg"></i> <!-- Gunakan 'fa-lg' untuk saiz besar standard -->
                    <?php
                    // Dapatkan kiraan program yang diluluskan
                    $approvedCount = 0;
                    $query = "SELECT COUNT(*) AS count FROM program WHERE P_Status = 'Diluluskan'";
                    $result = $mysqli->query($query);
                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $approvedCount = $row['count'];
                    }
                    ?>
                    <span class="badge"><?php echo $approvedCount; ?></span> <!-- Lencana kiraan pemberitahuan -->
                </a>
            </li>
            <li class="ts-account">
                <a href="#">
                    <img src="img/admin.png" class="ts-avatar hidden-side" alt="">
                    Akaun <i class="fa fa-angle-down hidden-side"></i>
                </a>
                <ul>
                    <li><a href="staff-profile.php">Akaun Saya</a></li>
                    <li><a href="staff-logout.php">Log Keluar</a></li>
                </ul>
            </li>
        </ul>
    <?php } ?>
    <a href="#" class="logo" style="font-size:16px; <?php echo $_SESSION['id'] ? 'color:#fff' : ''; ?>">
        <?php echo $_SESSION['id'] ? 'UTeM E-Masjidlite' : ''; ?>
    </a>
    <span class="menu-btn"><i class="fa fa-bars"></i></span>
</div>
