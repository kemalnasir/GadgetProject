<nav class="ts-sidebar" style="background-color: #2c3e50; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-right: 1px solid #34495e;">
    <ul class="ts-sidebar-menu">
        <li class="ts-label" style="color: #f39c12; font-weight: bold; padding: 15px 20px;">Utama</li>
        
        <?php if(isset($_SESSION['id'])) { ?>
            <li><a href="surah-view.php"><i class="fa fa-book"></i>Surah Ringkas</a></li>
            <li><a href="user-viewprogram.php"><i class="fa fa-info-circle"></i> Maklumat Program</a></li>
            <li><a href="user-programhistory.php"><i class="fa fa-history"></i> Sejarah</a></li>
            <li><a href="user-donationdetails.php"><i class="fa fa-gift"></i> Paparan Derma</a></li> <!-- Changed icon -->
            <li><a href="user-feedbackdetails.php"><i class="fa fa-comments"></i> Paparan Maklum Balas</a></li> <!-- Changed icon -->
            <li><a href="certification.php"><i class="fa fa-download"></i> Muat Turun Sijil</a></li> <!-- Changed icon -->
            <li><a href="user-logout.php"><i class="fa fa-sign-out"></i> Keluar</a></li> <!-- Changed icon -->
        <?php } else { ?>
            <li><a href="../index.php"><i class="fa fa-home"></i> Laman Utama</a></li>
            <li><a href="user-register.php"><i class="fa fa-file-text-o"></i>Maklumat Pendaftaran</a></li>
            <li><a href="user-index.php"><i class="fa fa-user"></i> Log Masuk</a></li>
        <?php } ?>
        
    </ul>
</nav>
