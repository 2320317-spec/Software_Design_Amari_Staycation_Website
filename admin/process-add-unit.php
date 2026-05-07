<?php
include('auth.php');
include('../includes/db-config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $amenities = $_POST['amenities'];
    $status = $_POST['status'];
    $desc = $_POST['description'];

    // SQL: Create a new record in the units table
    $stmt = $conn->prepare("INSERT INTO units (title, price_per_night, amenities, status, description) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sdsss", $title, $price, $amenities, $status, $desc);

    if ($stmt->execute()) {
        header("Location: manage-units.php?msg=added");
        exit();
    } else {
        echo "Error adding property: " . $conn->error;
    }
}
?>