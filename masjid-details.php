<?php
session_start();
error_reporting(0);
include('includes/config.php');
?>
<!DOCTYPE HTML>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="description" content="Sistem Pengurusan Masjid">
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

    <title>E-Masjidlite</title>
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
            background-image: url('image/masjidutem.jpg'); /* Replace with your image path */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
            color: white; /* Set text color to white */
        }
        .section-header h2,
        .section-header p,
        .image-description {
            color: black; /* Additional text color styling */
        }
        .image-container img {
            width: 100%;
            height: 250px; /* Set a uniform height */
            object-fit: cover; /* Maintain aspect ratio and cover the area */
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<?php include('includes/headermain.php'); ?>  

<div class="container">
    <div class="section-header text-center">
        <h2><strong>Informasi</strong></h2>
        <p>Masjid Sayyidina Abu Bakar bukan sekadar tempat ibadah tetapi juga simbol warisan dan nilai-nilai Islam yang dipegang oleh Universiti Teknikal Malaysia Melaka. Ia berdiri sebagai mercu tanda iman, pengetahuan, dan semangat komuniti, mencerminkan komitmen universiti terhadap pendidikan holistik yang meliputi keunggulan akademik dan pembangunan moral.</p>
        <p>Kesimpulannya, Masjid Sayyidina Abu Bakar di UTeM Melaka adalah mercu tanda keagamaan dan budaya yang penting. Keindahan seninya, fasilitas yang lengkap, dan penglibatan komuniti yang aktif menjadikannya institusi penting dalam universiti dan seluruh wilayah Melaka.</p>
        
        <div class="section-header text-center">
            <h2><strong>Galeri</strong></h2>
            <div class="row">
                <div class="col-md-4 col-sm-12">
                    <div class="image-container">
                        <img src="image/gambar1.jpg" alt="Pemandangan Luar Masjid" class="img-fluid">
                        <p class="image-description">Pemandangan Perkarangan Masjid</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="image-container">
                        <img src="image/gambar2.jpeg" alt="Pemandangan Luar Masjid" class="img-fluid">
                        <p class="image-description">Pemandangan Sudut Tepi Masjid</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="image-container">
                        <img src="image/gambar3.jpeg" alt="Pemandangan Luar Masjid" class="img-fluid">
                        <p class="image-description">Pemandangan Belakang Masjid</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="image-container">
                        <img src="image/gambar4.jpg" alt="Dewan Solat Masjid" class="img-fluid">
                        <p class="image-description">Pemandangan Dalam Masjid</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="image-container">
                        <img src="image/gambar5.jpg" alt="Kubah Masjid" class="img-fluid">
                        <p class="image-description">Pemandangan Astaka Masjid</p>
                    </div>
                </div>
                <div class="col-md-4 col-sm-12">
                    <div class="image-container">
                        <img src="image/gambar6.jpg" alt="Kawasan Dalaman Masjid" class="img-fluid">
                        <p class="image-description">Pemandangan Luar Masjid</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section-header text-center">
        <h2><strong>Lokasi Masjid</strong></h2>
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d7962.034002319987!2d102.2495043240526!3d2.313902163235774!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d1ef0ffb1d99a1%3A0x438d405b41ec8eb!2sMasjid%20Sayidina%20Abu%20Bakar%20As-Siddiq!5e0!3m2!1sen!2smy!4v1625294365462!5m2!1sen!2smy" width="600" height="450" style="border:0; width: 100%; height: 400px;" allowfullscreen="" loading="lazy"></iframe>
    </div>
</div>

<?php include('includes/footer.php'); ?>
<div id="back-top" class="back-top"> <a href="#top"><i class="fa fa-angle-up" aria-hidden="true"></i> </a> </div>

<script src="assets/js/jquery.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script> 
<script src="assets/js/interface.js"></script> 
<script src="assets/switcher/js/switcher.js"></script>
<script src="assets/js/bootstrap-slider.min.js"></script> 
<script src="assets/js/slick.min.js"></script> 
<script src="assets/js/owl.carousel.min.js"></script>

</body>
</html>

