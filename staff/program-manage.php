<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();

// Check if a category filter is set in the URL
$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
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
    <title>Maklumat Program</title>
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
<?php include('includes/header.php');?>

<div class="ts-main-content">
    <?php include('includes/sidebar.php');?>
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="page-title" style="margin-top:4%">Senarai Maklumat Program</h2>
                    <div class="panel panel-default">
                        <div class="panel-heading">Senarai Maklumat Program</div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="programFilter">Pilih Kategori Program:</label>
                                <div class="input-group">
                                    <select id="programFilter" class="form-control">
                                        <option value="">Semua Kategori</option>
                                        <?php
                                        // Fetch distinct program names for the dropdown
                                        $program_stmt = $mysqli->prepare("SELECT DISTINCT C_Name FROM category");
                                        $program_stmt->execute();
                                        $program_res = $program_stmt->get_result();
                                        while ($program_row = $program_res->fetch_assoc()) {
                                            $selected = ($program_row['C_Name'] === $category_filter) ? 'selected' : '';
                                            echo '<option value="' . htmlspecialchars($program_row['C_Name']) . '" ' . $selected . '>' . htmlspecialchars($program_row['C_Name']) . '</option>';
                                        }
                                        ?>
                                    </select>
                                    <span class="input-group-btn">
                                        <button id="filterButton" class="btn btn-primary" type="button">Cari</button>
                                    </span>
                                </div>
                            </div>
                            <table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>Bil.</th>
                                        <th>Nama Program</th>
                                        <th>Gambar</th>
                                        <th>Fail</th>
                                        <th>Penerangan</th>
                                        <th>Tarikh & Masa Program</th>
                                        <th>Kapasiti Program</th>
                                        <th>Lokasi</th>
                                        <th>Penyelaras</th>
                                        <th>Sijil</th>
                                        <th>Tindakan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // SQL query to fetch program details based on selected category
                                    $query = "SELECT p.P_ID, p.P_Name, p.P_Description, p.P_Image, p.P_File, p.P_DateTime, p.P_Capacity, p.P_Location, p.P_Person, c.C_Name, p.P_Cert
                                              FROM program p
                                              INNER JOIN category c ON p.C_ID = c.C_ID";
                                    if ($category_filter) {
                                        $query .= " WHERE c.C_Name = ?";
                                    }
                                    $query .= " ORDER BY p.P_RegDate DESC";

                                    $stmt = $mysqli->prepare($query);
                                    if ($category_filter) {
                                        $stmt->bind_param("s", $category_filter);
                                    }
                                    $stmt->execute();
                                    $res = $stmt->get_result();
                                    $cnt = 1;
                                    while ($row = $res->fetch_object()) {
                                        // Convert the date to day-month format
                                        $formattedDate = date('d M, Y h:i A', strtotime($row->P_DateTime));
                                     ?>
                                            <tr>
                                                <td><?= $cnt ?></td>
                                                <td><?= htmlspecialchars($row->P_Name) ?></td>
                                                <td><img src="../staff/<?= htmlspecialchars($row->P_Image) ?>" width="200px" height="120px"></td>
                                                <td><a href="../staff/<?= htmlspecialchars($row->P_File) ?>" target="_blank"><i class="fa fa-download"></i></a></td>
                                                <td><?= htmlspecialchars($row->P_Description) ?></td>
                                                <td><?= $formattedDate ?></td>
                                                <td><?= htmlspecialchars($row->P_Capacity) ?></td>
                                                <td><?= htmlspecialchars($row->P_Location) ?></td>
                                                <td><?= htmlspecialchars($row->P_Person) ?></td>
                                                <td><a href="../staff/<?= htmlspecialchars($row->P_Cert) ?>" target="_blank"><i class="fa fa-download"></i></a></td>
                                                <td>
                                                    <a href="program-update.php?id=<?= htmlspecialchars($row->P_ID);?>" class="btn btn-primary btn-sm" style="width: 100%; display: block;">Kemaskini</a>
                                                    <a href="program-remove.php?id=<?= htmlspecialchars($row->P_ID);?>" class="btn btn-danger btn-sm" style="width: 100%; display: block; margin-top: 5px;">Padam</a>
                                                </td>
                                            </tr>
                                            <?php
                                            $cnt++;
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

<script>
    document.getElementById('filterButton').addEventListener('click', function() {
        var selectedCategory = document.getElementById('programFilter').value;
        var url = window.location.href.split('?')[0] + '?category=' + encodeURIComponent(selectedCategory);
        window.location.href = url;
    });
</script>

</body>
</html>
