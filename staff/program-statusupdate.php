<?php
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $programId = intval($_POST['id']);
    $status = $_POST['status'];

    $query = "UPDATE program SET P_Remark = ? WHERE P_ID = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('si', $status, $programId);

    if ($stmt->execute()) {
        echo 'Success';
    } else {
        echo 'Error';
    }
}
?>
