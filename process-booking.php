<?php
// 1. Connect to the plumbing
include('includes/db-config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- STEP 1: DATA CAPTURE (Wired to the NEW form) ---
    $unit_id = $_POST['unit_id'];
    
    // Combine first and last name from the form into one variable
    $guest_name = trim($_POST['first_name'] . " " . $_POST['last_name']); 
    
    $guest_email = $_POST['email'];
    $check_in = $_POST['checkin']; // Matches name="checkin" in HTML
    $check_out = $_POST['checkout']; // Matches name="checkout" in HTML
    
    // We are capturing these, but you may need to add them to your DB!
    $phone = $_POST['phone'];
    $requests = $_POST['requests'];

    // --- STEP 2: GUARDRAILS (Your excellent validation) ---
    $check_in_time = strtotime($check_in);
    $check_out_time = strtotime($check_out);
    $today = strtotime(date("Y-m-d"));

    if ($check_out_time <= $check_in_time) {
        die("<h2>Error: Invalid Dates</h2><p>Your check-out date must be after your check-in date.</p><a href='booking-form.php?unit_id=$unit_id'>Try Again</a>");
    }

    if ($check_in_time < $today) {
        die("<h2>Error: Past Date</h2><p>You cannot book a stay in the past!</p><a href='booking-form.php?unit_id=$unit_id'>Try Again</a>");
    }

    // --- STEP 3: THE INSPECTOR (Your Double-Booking Check) ---
    $check_sql = "SELECT id FROM bookings 
                  WHERE unit_id = ? 
                  AND status != 'declined' 
                  AND (check_in < ? AND check_out > ?)";
    
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("iss", $unit_id, $check_out, $check_in);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        die("<h2>Dates Unavailable</h2><p>Someone already booked these dates.</p><a href='room-details.php?id=$unit_id'>Return to Room</a>");
    } 
    else {
        // --- STEP 4: THE PACKER (Insert into DB) ---
        // 🚨 IMPORTANT: I added 'phone' and 'special_requests' to this SQL command. 
        // If your database table does NOT have these columns, this will crash!
        $stmt = $conn->prepare("INSERT INTO bookings (unit_id, guest_name, guest_email, phone, special_requests, check_in, check_out, status) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')");
        
        $stmt->bind_param("issssss", $unit_id, $guest_name, $guest_email, $phone, $requests, $check_in, $check_out);

        if ($stmt->execute()) {
            // Grab the ID of this specific booking
            $new_booking_id = $stmt->insert_id;
            
            // Redirect to the Receipt page!
            header("Location: view-invoice.php?id=" . $new_booking_id);
            exit();
        } else {
            echo "System Error: " . $stmt->error;
        }
        $stmt->close();
    }

    $check_stmt->close();
    $conn->close();
} else {
    header("Location: index.php");
    exit();
}
?>