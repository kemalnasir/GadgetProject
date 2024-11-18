<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
?>

<!doctype html>
<html lang="en" class="no-js">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="theme-color" content="#3e454c">
    <title>Status Program</title>
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
                        <h2 class="page-title" style="margin-top:4%">Senarai Status Program</h2>
                        <div class="panel panel-default">
                            <div class="panel-heading">Senarai Status Program</div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label for="programFilter">Pilih Nama Program:</label>
                                    <div class="input-group">
                                        <select id="programFilter" class="form-control">
                                            <option value="">Semua Program</option>
                                            <?php
                                            // Fetch distinct program names for the dropdown
                                            $program_stmt = $mysqli->prepare("SELECT DISTINCT P_Name FROM program");
                                            $program_stmt->execute();
                                            $program_res = $program_stmt->get_result();
                                            while ($program_row = $program_res->fetch_assoc()) {
                                                echo '<option value="' . htmlspecialchars($program_row['P_Name']) . '">' . htmlspecialchars($program_row['P_Name']) . '</option>';
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
                                            <th>Kategori</th>
                                            <th>Gambar</th>
                                            <th>Fail</th>
                                            <th>Penerangan</th>
                                            <th>Tarikh & Masa Program</th>
                                            <th>Kapasiti Program</th>
                                            <th>Lokasi</th>
                                            <th>Penyelaras</th>
                                            <th>Status</th>
                                            <th>Komen oleh PTJ</th>
                                            <th>Diterbitkan</th>
                                            <th>Tindakan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $ret = "SELECT program.P_ID, program.P_Name, category.C_Name, program.P_Description, program.P_Image, program.P_File, program.P_DateTime, program.P_Capacity, program.P_Location, program.P_Person, program.P_Status, program.P_Remark, program.P_Comment
                                                FROM program 
                                                JOIN category ON program.C_ID = category.C_ID
                                                ORDER BY program.P_RegDate DESC";
                                        $stmt = $mysqli->prepare($ret);
                                        $stmt->execute();
                                        $res = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $res->fetch_object()) {
                                            // Format date and time to include date first, followed by month
                                            $formattedDate = date('d M, Y h:i A', strtotime($row->P_DateTime));
                                        ?>
                                            <tr class="programRow" data-program="<?php echo htmlspecialchars($row->P_Name); ?>">
                                                <td class="counter"><?php echo $cnt; ?></td>
                                                <td><?php echo $row->P_Name; ?></td>
                                                <td><?php echo $row->C_Name; ?></td>
                                                <td><img src="../staff/<?php echo $row->P_Image; ?>" width="200px" height="120px"></td>
                                                <td><a href="../staff/<?php echo $row->P_File; ?>" target="_blank"><i class="fa fa-download"></i></a></td>
                                                <td><?php echo $row->P_Description; ?></td>
                                                <td><?php echo $formattedDate; ?></td>
                                                <td><?php echo $row->P_Capacity; ?></td>
                                                <td><?php echo $row->P_Location; ?></td>
                                                <td><?php echo $row->P_Person; ?></td>
                                                <td><?php echo $row->P_Status; ?></td>
                                                <td><?php echo $row->P_Comment; ?></td>
                                                <td>
                                                    <?php if ($row->P_Status == 'Diluluskan'): ?>
                                                        <input type="checkbox" class="publish-checkbox" data-id="<?php echo $row->P_ID; ?>" <?php echo ($row->P_Remark == 'Published') ? 'checked' : ''; ?>>
                                                    <?php else: ?>
                                                        <input type="checkbox" class="publish-checkbox" data-id="<?php echo $row->P_ID; ?>" disabled>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($row->P_Status == 'Meminta Semakan / Kemaskini'): ?>
                                                        <a href="programresubmit.php?id=<?php echo $row->P_ID; ?>"><i class="fa fa-edit"></i></a>
                                                    <?php endif; ?>
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
        $(document).ready(function() {
            $('#filterButton').click(function() {
                var selectedProgram = $('#programFilter').val().toLowerCase();
                var count = 1; // Initialize counter

                $('.programRow').each(function() {
                    var programName = $(this).data('program').toLowerCase();
                    if (selectedProgram === "" || programName === selectedProgram) {
                        $(this).show();
                        $(this).find('.counter').text(count); // Update the "Bil." column with the counter
                        count++; // Increment counter
                    } else {
                        $(this).hide();
                    }
                });
            });

            $('.publish-checkbox').on('change', function() {
                var programId = $(this).data('id');
                var isChecked = $(this).is(':checked');
                var status = isChecked ? 'Published' : 'Not Published';

                $.ajax({
                    url: 'program-statusupdate.php',
                    method: 'POST',
                    data: {
                        id: programId,
                        status: status
                    },
                    success: function(response) {
                        alert('Status berjaya dikemaskini');
                    },
                    error: function() {
                        alert('Gagal mengemas kini status');
                    }
                });
            });
        });
    </script>
</body>
</html>
