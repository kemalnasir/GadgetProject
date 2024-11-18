<?php
include('includes/config.php');

if (isset($_POST['gadget_name'])) {
    $gadgetName = $_POST['gadget_name'];

    // Prepare statement to select gadget details by name
    $stmt = $mysqli->prepare("SELECT product.P_Name, product.P_Image, product.P_Video, product.P_Audio, category.C_Name 
                              FROM product 
                              INNER JOIN category ON product.C_ID = category.C_ID 
                              WHERE product.P_Name LIKE ?");
    $gadgetName = '%' . $gadgetName .'%'; // Using LIKE to allow partial matches
    $stmt->bind_param("s", $gadgetName);
    $stmt->execute();
    $result = $stmt->get_result();

    $response = array();
    if ($result->num_rows > 0) {
        $response['success'] = true;
        $response['gadget_details'] = array();
        while ($row = $result->fetch_assoc()) {
            $response['gadget_details'][] = array(
                'P_Name' => $row['P_Name'],
                'C_Name' => $row['C_Name'],
                'P_Image' => '../admin/' . $row['P_Image'],
                'P_Video' => '../admin/' . $row['P_Video'],
                'P_Audio' => '../admin/' . $row['P_Audio']
            );
        }
    } else {
        $response['success'] = false;
        $response['error'] = 'No gadgets found';
    }

    echo json_encode($response);
    $stmt->close();
} else {
    $response['success'] = false;
    $response['error'] = 'Invalid request';
    echo json_encode($response);
}
?>