<?php
session_start();
error_reporting(0);
include('includes/config.php');

// Function to fetch prayer times from the database
function getPrayerTimes($date) {
    global $mysqli;

    $prayerTimes = array();
    $query = "SELECT PT_Name, PT_Time FROM prayertime";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->execute();
        $stmt->bind_result($PT_Name, $PT_Time);

        while ($stmt->fetch()) {
            $prayerTimes[$PT_Name] = date("g:i A", strtotime($PT_Time));
        }

        $stmt->close();
    }

    return $prayerTimes;
}

// Fetch current date and time in Melaka, Malaysia timezone
date_default_timezone_set('Asia/Kuala_Lumpur'); // Set timezone to Malaysia

$currentDateTime = new DateTime();
$currentDateTime->setTimezone(new DateTimeZone('Asia/Kuala_Lumpur')); // Adjust timezone

$currentDate = $currentDateTime->format('l, d F Y'); // Format date (e.g., Monday, 05 June 2023)
$currentDateTimeFormatted = $currentDateTime->format('h:i:s A'); // Format time (e.g., 12:00:00 PM)
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
            background-image: url('image/masjidutem.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            color: #333;
        }
        .section-header {
            margin-bottom: 30px;
        }
        .section-heading {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
        }
        .prayer-time {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }
        .prayer-time-item {
            text-align: center;
            background: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 150px;
            margin-bottom: 20px;
            transition: box-shadow 0.3s;
        }

        .prayer-time-item:hover {
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .prayer-name {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 5px;
            color: #007bff; /* Adjust text color as needed */
        }

        .prayer-clock {
            font-size: 1.5rem;
            font-weight: bold;
            color: #666; /* Adjust text color as needed */
        }
        .program-list {
            margin-top: 30px;
        }
.program-card {
    width: 100%;
    background: rgba(255, 255, 255, 0.8); /* Semi-transparent white background */
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin-bottom: 20px;
    transition: box-shadow 0.3s;
}

.program-card:hover {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.program-card img {
    width: 100%;
    height: 200px; /* Set a fixed height for the image */
    object-fit: cover; /* Ensure the image covers the entire space */
    border-radius: 10px 10px 0 0;
}

.program-card-body {
    padding: 15px;
}

.program-date {
    background-color: #007bff;
    color: #fff;
    padding: 5px 10px;
    border-radius: 5px;
    display: inline-block;
    margin-bottom: 10px;
    font-weight: bold;
}

.program-title {
    font-size: 1.2rem;
    font-weight: bold;
    color: #333;
    margin-top: 10px;
    margin-bottom: 5px;
}

.program-description {
    font-size: 0.9rem;
    color: #666;
    margin-bottom: 10px;
}

.btn-primary {
    background-color: #007bff;
    border: none;
    transition: background-color 0.3s ease;
}

.btn-primary:hover {
    background-color: #0056b3;
}
        .form-inline .form-control {
            width: auto;
        }
        .location-info {
            font-size: 1.2rem;
            margin-bottom: 5px;
            color: black; /* Adjust text color as needed */
        }
        @media (max-width: 768px) {
            .prayer-time {
                flex-direction: column;
                align-items: center;
            }
            .prayer-time-item {
                width: auto;
            }
        }
        /* Title styles */
        .modern-title {
            font-size: 3rem;
            font-weight: 900;
            color: dark grey; /* Blue color */
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5); /* Text shadow for better readability */
            margin-bottom: 20px;
            text-align: center; /* Center align the title */
            letter-spacing: 1px; /* Adjust letter spacing */
        }

        /* Adjustments for smaller screens */
        @media (max-width: 768px) {
            .modern-title {
                font-size: 2.5rem;
            }
        }
    </style>
</head>
<body>

<?php include('includes/headermain.php'); ?>  

  <div class="container">
    <div class="section-header text-center">
        <h1 class="modern-title">SISTEM PENGURUSAN PROGRAM MASJID SAYYIDINA ABU BAKAR <br>UTeM</h1> <!-- Updated modern title -->
        <h2 class="section-heading prayer-time-heading"><strong>Waktu Solat</strong></h2>
        <p class="location-info">Melaka, Malaysia</p>
        <p id="currentDateTime"><?php echo $currentDate; ?>, <?php echo $currentDateTimeFormatted; ?></p>
        
        
        <div class="row prayer-time">
            <?php
            // Fetch prayer times
            $prayerTimes = getPrayerTimes(date('Y-m-d'));

            foreach ($prayerTimes as $prayer => $time) {
                ?>
                <div class="col-md-4">
                    <div class="prayer-time-item">
                        <p class="prayer-name"><?php echo htmlspecialchars($prayer); ?></p>
                        <div class="prayer-clock"><?php echo htmlspecialchars($time); ?></div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>



    <div class="section-header text-center">
        <h2 class="section-heading"><strong>Aktiviti & Program</strong></h2>
        
        <!-- Search Form -->
        <form method="GET" action="" class="form-inline mb-4">
            <div class="form-group mr-2">
                <input type="text" name="search" class="form-control w-90" placeholder="Cari Program" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            </div>
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
        <br>
        <div class="row">
            <?php
            // Fetch programs
            $search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
            $query = "SELECT P_Name, P_Description, P_Image, P_DateTime, P_Remark FROM program WHERE P_Remark = 'published' AND P_Name LIKE ? ORDER BY P_DateTime ASC";
            $stmt = $mysqli->prepare($query);
            $stmt->bind_param('s', $search);
            $stmt->execute();
            $stmt->bind_result($P_Name, $P_Description, $P_Image, $P_DateTime, $P_Remarks);

            while ($stmt->fetch()) {
                $date = date("d M Y", strtotime($P_DateTime));
                ?>
                <div class="col-md-4 col-sm-12 mb-4">
                    <div class="program-card">
                        <img src="staff/<?php echo htmlspecialchars($P_Image); ?>" alt="<?php echo htmlspecialchars($P_Name); ?>">
                        <div class="program-card-body">
                            <p class="program-date"><?php echo htmlspecialchars($date); ?></p>
                            <h5 class="program-title"><?php echo htmlspecialchars($P_Name); ?></h5>
                            <p class="program-description"><?php echo htmlspecialchars($P_Description); ?></p>
                            <a href="user/user-index.php" class="btn btn-primary">Mahu Sertai?</a>
                        </div>
                    </div>
                </div>
                <?php
            }
            $stmt->close();
            ?>
        </div>
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

<script>
    function updateDateTime() {
        const now = new Date();
        const options = { 
            weekday: 'long', year: 'numeric', month: 'long', day: 'numeric', 
            hour: 'numeric', minute: 'numeric', second: 'numeric', hour12: true 
        };
        const formattedDateTime = new Intl.DateTimeFormat('en-US', options).format(now);
        document.getElementById('currentDateTime').textContent = formattedDateTime;
    }

    setInterval(updateDateTime, 1000);
    updateDateTime();
</script>

</body>
</html>
