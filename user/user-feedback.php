<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if (isset($_POST['submit'])) {
    // Sanitize and fetch form data
    $comment = $_POST['comment'];
    $programId = $_POST['programId']; // Menganggap anda mendapat ini dari menu pilihan
    $userId = $_SESSION['id']; // Menganggap ID pengguna disimpan dalam sesi

    // Validate inputs (server-side validation)
    if (empty($comment) || empty($programId)) {
        echo "<script>alert('Semua medan diperlukan.');</script>";
    } else {
        // Semak jika pengguna mempunyai akses ke program yang ditetapkan
        $stmt = $mysqli->prepare("SELECT UP_ID FROM user_program WHERE U_ID = ? AND P_ID = ? AND UP_Status = 'Hadir'");
        if (!$stmt) {
            echo "<script>alert('Ralat penyediaan kenyataan: " . $mysqli->error . "');</script>";
            exit();
        }
        $stmt->bind_param("ii", $userId, $programId);
        $stmt->execute();
        $stmt->bind_result($upId);
        $stmt->fetch();
        $stmt->close();

        if (!$upId) {
            echo "<script>alert('Ralat: Pengguna tidak mempunyai akses ke program yang ditetapkan atau status tidak hadir.');</script>";
        } else {
            // Masukkan maklum balas ke dalam jadual maklum balas
            $stmt = $mysqli->prepare("INSERT INTO feedback (FB_Comment, UP_ID) VALUES (?, ?)");
            if (!$stmt) {
                echo "<script>alert('Ralat penyediaan kenyataan: " . $mysqli->error . "');</script>";
                exit();
            }
            $stmt->bind_param("si", $comment, $upId);
            if ($stmt->execute()) {
                echo "<script>alert('Maklum balas anda telah berjaya dihantar. Terima kasih!');</script>";
                echo "<script>window.location.href='user-feedbackdetails.php';</script>";
                exit();
            } else {
                echo "<script>alert('Anda telah menghantar maklum balas sebelum ini.');</script>";
            }
            $stmt->close();
        }
    }
}
?>

<!doctype html>
<html lang="ms" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Hantar Maklum Balas</title>
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Pilihan: CSS untuk input carian */
        #searchProgram {
            margin-bottom: 10px;
        }
    </style>
</head>

<body>
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title" style="margin-top:4%"></h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Borang Maklum Balas</div>
                            <div class="panel-body">
                                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Nama Program:</label>
                                        <div class="col-sm-8">
                                            <input type="text" id="searchProgram" onkeyup="filterPrograms()" class="form-control" placeholder="Cari program...">
                                            <select class="form-control" name="programId" id="programId" required>
                                                <option value="">Pilih Program</option>
                                                <?php
                                                $userId = $_SESSION['id'];
                                                $query = "SELECT p.P_ID, p.P_Name 
                                                          FROM program p 
                                                          INNER JOIN user_program up ON p.P_ID = up.P_ID 
                                                          WHERE up.U_ID = ? AND up.UP_Status = 'Hadir'
                                                          ORDER BY up.UP_RegDate DESC";
                                                $stmt = $mysqli->prepare($query);
                                                $stmt->bind_param("i", $userId);
                                                $stmt->execute();
                                                $result = $stmt->get_result();
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value='{$row['P_ID']}'>{$row['P_Name']}</option>";
                                                }
                                                $stmt->close();
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Komen:</label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control" name="comment" required></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-8">
                                            <button type="submit" class="btn btn-primary" name="submit">Hantar</button>
                                            <a href="user-programhistory.php" class="btn btn-danger" onclick="return confirm('Adakah anda pasti untuk membatalkan?');">Batal</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Skrip JavaScript untuk menapis program -->
    <script>
    function filterPrograms() {
        var input, filter, select, options, option, txtValue;
        input = document.getElementById("searchProgram");
        filter = input.value.toUpperCase();
        select = document.getElementById("programId");
        options = select.getElementsByTagName("option");

        for (var i = 0; i < options.length; i++) {
            option = options[i];
            txtValue = option.textContent || option.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                option.style.display = "";
            } else {
                option.style.display = "none";
            }
        }
    }
    </script>

    <!-- Pilihan: Skrip tambahan dan pustaka -->
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
</body>
</html>
