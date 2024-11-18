<?php
session_start();
include('includes/config.php');

function getCategories($mysqli) {
    $query = "SELECT * FROM category ORDER BY C_RegDate DESC";
    $result = $mysqli->query($query);
    $categories = [];
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
    return $categories;
}

function getProducts($mysqli, $categoryFilter = '') {
    $query = "SELECT product.P_Name, category.C_Name, product.P_Image, product.P_Desc
              FROM product 
              JOIN category ON product.C_ID = category.C_ID";

    if (!empty($categoryFilter)) {
        $query .= " WHERE category.C_Name = ?";
    }

    $query .= " ORDER BY product.P_RegDate DESC";
    $stmt = $mysqli->prepare($query);

    if (!empty($categoryFilter)) {
        $stmt->bind_param("s", $categoryFilter);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $products = [];

    while ($row = $result->fetch_object()) {
        $products[] = $row;
    }
    return $products;
}
