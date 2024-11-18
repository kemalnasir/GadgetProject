<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

if (isset($_POST['submit'])) {
    // Sanitize and fetch form data
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $methodPay = $_POST['methodPay'];
    $programId = $_POST['programId']; // Penambahan baru
    $userId = $_SESSION['id']; // Menganggap ID pengguna disimpan dalam sesi

    // Validate inputs (server-side validation)
    if (empty($amount) || empty($description) || empty($methodPay) || empty($programId)) {
        echo "<script>alert('Semua medan diperlukan.');</script>";
    } elseif (!is_numeric($amount)) {
        echo "<script>alert('Maaf, sila masukkan nilai dalam nombor pada ruangan jumlah sumbangan.');</script>";
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
            // Masukkan sumbangan ke dalam jadual sumbangan
            $stmt = $mysqli->prepare("INSERT INTO donation (D_Amount, D_Description, D_Date, D_MethodPay) VALUES (?, ?, CURRENT_TIMESTAMP, ?)");
            if (!$stmt) {
                echo "<script>alert('Ralat penyediaan kenyataan: " . $mysqli->error . "');</script>";
                exit();
            }
            $stmt->bind_param("sss", $amount, $description, $methodPay);
            if ($stmt->execute()) {
                // Dapatkan ID sumbangan yang dimasukkan terakhir
                $donationId = $stmt->insert_id;

                // Masukkan ke dalam jadual user_donation
                $stmt->close();
                $stmt = $mysqli->prepare("INSERT INTO user_donation (D_ID, UP_ID) VALUES (?, ?)");
                if (!$stmt) {
                    echo "<script>alert('Ralat penyediaan kenyataan: " . $mysqli->error . "');</script>";
                    exit();
                }
                $stmt->bind_param("ii", $donationId, $upId);
                if ($stmt->execute()) {
                    echo "<script>alert('Terima kasih atas sumbangan anda!');</script>";
                    echo "<script>window.location.href='user-donationdetails.php';</script>";
                    exit();
                } else {
                    echo "<script>alert('Gagal menghubungkan sumbangan. Sila cuba lagi.');</script>";
                }
            } else {
                echo "<script>alert('Gagal membuat sumbangan. Sila cuba lagi.');</script>";
            }
            $stmt->close();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="ms" class="no-js">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Buat Sumbangan</title>
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
    <?php include('includes/header.php'); ?>
    <div class="ts-main-content">
        <?php include('includes/sidebar.php'); ?>
        <div class="content-wrapper">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <h2 class="page-title" style="margin-top:4%"></h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Butiran Sumbangan</div>
                            <div class="panel-body">
                                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="form-horizontal">
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Carian Program:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" id="programSearch" placeholder="Carian mengikut Nama Program">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Nama Program:</label>
                                        <div class="col-sm-8">
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
                                        <label class="col-sm-2 control-label">Jumlah Sumbangan:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="amount" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Catatan:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" name="description" required>
                                        </div>
                                    </div>
                                  <div class="form-group">
                                            <label class="col-sm-2 control-label">Bank QR:</label>
                                            <div class="col-sm-8">
                                                <img src="../image/masjidutemqr.jpeg" width="330" height="310" alt="Bank QR Code">
                                            </div>
                                        </div>
                              <div class="form-group">
                                    <label class="col-sm-2 control-label">Pembayaran Online:</label>
                                    <div class="col-sm-8" style="padding-top: 13px;">
                                        <a href="https://epayment.utem.edu.my/sumbangan/BSumbangan.php?fbclid=IwZXh0bgNhZW0CMTAAAR3BHP9HkKjwbHH9lgBtO5vJK3OX00y7i-3LC3iCgtYAhdWGat6hUCtUxKQ_aem_AT5fj820a0KkXohZj7bixZUZJLHFVAhKUbd6-ji6L7qUq8i4u56O6jsGxrC7QuBKsb4iXq7simSUFPW7taNUc9Ue" target="_blank">Klik disini untuk membuat sumbangan secara online</a>
                                    </div>
                                </div>

                                   <div class="form-group">
                                        <label class="col-sm-2 control-label">Kaedah Pembayaran:</label>
                                        <div class="col-sm-8">
                                            <label class="radio-inline">
                                                <input type="radio" name="methodPay" value="Tunai" required> Tunai
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="methodPay" value="Pembayaran Online" required> Pembayaran Online
                                            </label>
                                            <label class="radio-inline">
                                                <input type="radio" name="methodPay" value="E-wallet/Bank QR" required>E-wallet/Bank QR
                                            </label>
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

    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap-select.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/dataTables.bootstrap.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/fileinput.js"></script>
    <script src="js/chartData.js"></script>
    <script src="js/main.js"></script>

    <script>
    $(document).ready(function() {
        $('#programSearch').on('input', function() {
            var searchText = $(this).val().toLowerCase();
            $('#programId option').each(function() {
                var optionText = $(this).text().toLowerCase();
                if (optionText.includes(searchText)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
    </script>
</body>
</html>
