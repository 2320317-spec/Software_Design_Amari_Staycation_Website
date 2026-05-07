<?php
include('auth.php');
include('../includes/db-config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the data from the form
    $id = $_POST['id'];
    $title = $_POST['title'];
    $price = $_POST['price'];
    $amenities = $_POST['amenities'];
    $status = $_POST['status'];
    $desc = $_POST['description'];

    // SQL Engineering: Update the record matching the ID
    $stmt = $conn->prepare("UPDATE units SET title=?, price_per_night=?, amenities=?, status=?, description=? WHERE id=?");
    $stmt->bind_param("sdsssi", $title, $price, $amenities, $status, $desc, $id);

    if ($stmt->execute()) {
        // Redirect back to inventory with a success flag
        header("Location: manage-units.php?msg=success");
        exit();
    } else {
        echo "Hardware/Database Error: " . $conn->error;
    }
} else {
    header("Location: manage-units.php");
    exit();
}
?>