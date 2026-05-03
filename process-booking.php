<?php
// 1. Connect to the plumbing
include('includes/db-config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- STEP 1: DATA CAPTURE (Move this up!) ---
    // We must "catch" the data from the form first so the variables exist.
    $unit_id = $_POST['unit_id'];
    $guest_title = $_POST['guest_title'];
    $guest_name = $_POST['guest_name'];
    $guest_email = $_POST['guest_email'];
    $check_in = $_POST['check_in'];
    $check_out = $_POST['check_out'];

    // --- STEP 2: NEW GUARDRAIL (Validation) ---
    // Now that $check_in and $check_out are defined, we can inspect them.
    $check_in_time = strtotime($check_in);
    $check_out_time = strtotime($check_out);
    $today = strtotime(date("Y-m-d"));

    if ($check_out_time <= $check_in_time) {
        die("<h2>Error: Invalid Dates</h2><p>Your check-out date must be after your check-in date.</p><a href='index.php'>Try Again</a>");
    }

    if ($check_in_time < $today) {
        die("<h2>Error: Past Date</h2><p>You cannot book a stay in the past!</p><a href='index.php'>Try Again</a>");
    }

    // --- STEP 3: THE INSPECTOR (Database Overlap Check) ---
    $check_sql = "SELECT id FROM bookings 
                  WHERE unit_id = ? 
                  AND status != 'declined' 
                  AND (check_in < ? AND check_out > ?)";
    
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("iss", $unit_id, $check_out, $check_in);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<h2>Dates Unavailable</h2><p>Someone already booked these dates.</p><a href='index.php'>Return</a>";
    } 
    else {
        // --- STEP 4: THE PACKER (Insert into DB) ---
        $stmt = $conn->prepare("INSERT INTO bookings (unit_id, guest_title, guest_name, guest_email, check_in, check_out, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param("isssss", $unit_id, $guest_title, $guest_name, $guest_email, $check_in, $check_out);

        if ($stmt->execute()) {
            echo "<h2>Booking Request Sent!</h2><a href='index.php'>Back to Home</a>";
        } else {
            echo "System Error: " . $stmt->error;
        }
        $stmt->close();
    }

    $check_stmt->close();
    $conn->close();
}
?>