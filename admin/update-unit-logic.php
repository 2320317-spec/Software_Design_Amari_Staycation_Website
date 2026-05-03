<?php
include('auth.php');
include('../includes/db-config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['unit_id'];
    $title = $_POST['title'];
    $price = $_POST['price'];
    $desc = $_POST['description'];

    // SQL UPDATE Logic [cite: 170]
    $stmt = $conn->prepare("UPDATE units SET title = ?, price_per_night = ?, description = ? WHERE id = ?");
    $stmt->bind_param("sdsi", $title, $price, $desc, $id);

    if ($stmt->execute()) {
        header("Location: manage-units.php?status=updated");
    } else {
        echo "Error: " . $conn->error;
    }
}
?>