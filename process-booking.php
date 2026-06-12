<?php
// 1. Connect to the plumbing
include('includes/db-config.php');

// --- LUXURY ERROR HANDLER ---
// This prevents the "White Screen of Death" if a guest makes a mistake.
function showAmariMessage($title, $message, $link, $linkText) {
    echo "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <title>$title | Amari Alabang</title>
        <link href='https://fonts.googleapis.com/css2?family=Playfair+Display&family=Lato&display=swap' rel='stylesheet'>
        <style>
            body { background: #f9f9f9; font-family: 'Lato', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; color: #333; }
            .card { background: white; padding: 50px; border-radius: 5px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); text-align: center; max-width: 450px; border-top: 4px solid #b0885a; }
            h1 { font-family: 'Playfair Display', serif; color: #042e47; margin-top: 0; font-size: 2rem; }
            p { color: #666; line-height: 1.6; margin-bottom: 30px; }
            .btn { background: #042e47; color: white; padding: 15px 30px; text-decoration: none; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; border-radius: 3px; display: inline-block; transition: 0.3s; font-size: 0.85rem; }
            .btn:hover { background: #b0885a; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(176, 136, 90, 0.2); }
        </style>
    </head>
    <body>
        <div class='card'>
            <h1>$title</h1>
            <p>$message</p>
            <a href='$link' class='btn'>$linkText</a>
        </div>
    </body>
    </html>";
    exit();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // --- STEP 1: DATA CAPTURE ---
    $unit_id = intval($_POST['unit_id']);

    // Combine first and last name from the form into one variable safely
    $guest_name = trim($_POST['first_name']) . " " . trim($_POST['last_name']);

    $guest_title = isset($_POST['guest_title']) ? trim($_POST['guest_title']) : '';
    $guest_email = $_POST['email'];
    $check_in = $_POST['checkin'];
    $check_out = $_POST['checkout'];

    $phone = $_POST['phone'];
    $requests = $_POST['requests'];

    // Guest counts from the wizard (default to sensible minimums)
    $num_adults   = isset($_POST['num_adults'])   ? max(1, intval($_POST['num_adults']))   : 1;
    $num_children = isset($_POST['num_children']) ? max(0, intval($_POST['num_children'])) : 0;

    // --- STEP 2: THE GUARDRAILS ---
    $check_in_time = strtotime($check_in);
    $check_out_time = strtotime($check_out);
    $today = strtotime(date("Y-m-d"));

    // Check 1: Did they put a check-out date before check-in?
    if ($check_out_time <= $check_in_time) {
        showAmariMessage("Invalid Dates", "Your check-out date must be after your check-in date. Please adjust your calendar selection.", "javascript:history.back()", "Go Back");
    }

    // Check 2: Are they trying to book in the past?
    if ($check_in_time < $today) {
        showAmariMessage("Invalid Selection", "You cannot book a stay in the past. Please select future dates.", "javascript:history.back()", "Go Back");
    }

    // --- STEP 3: THE INSPECTOR (Hardened Check) ---
    // We explicitly tell the database: "If the unit has a booking that is 
    // already 'pending' or 'approved', BLOCK this new request."
    $check_sql = "SELECT id FROM bookings 
                  WHERE unit_id = ? 
                  AND status IN ('pending', 'approved') 
                  AND (check_in < ? AND check_out > ?)";
    
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("iss", $unit_id, $check_out, $check_in);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        showAmariMessage("Dates Unavailable", "We're sorry, but another guest has already reserved this unit for those dates. Please try selecting different dates.", "javascript:history.back()", "Choose New Dates");
    } 
    else {
        // --- STEP 3.5: PRICE (computed server-side, never trust the client) ---
        $price_stmt = $conn->prepare("SELECT price_per_night FROM units WHERE id = ?");
        $price_stmt->bind_param("i", $unit_id);
        $price_stmt->execute();
        $unit_row = $price_stmt->get_result()->fetch_assoc();
        $price_stmt->close();
        if (!$unit_row) {
            showAmariMessage("System Error", "We could not find the selected suite. Please start your booking again.", "index.php", "Return Home");
        }
        $nights = max(1, (int)round(($check_out_time - $check_in_time) / 86400));
        $total_price = $nights * (float)$unit_row['price_per_night'];

        // --- STEP 4: THE PACKER (Insert into DB) ---
        $stmt = $conn->prepare("INSERT INTO bookings (unit_id, guest_title, guest_name, guest_email, phone, num_adults, num_children, special_requests, check_in, check_out, total_price, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 'pending')");

        $stmt->bind_param("issssiisssd", $unit_id, $guest_title, $guest_name, $guest_email, $phone, $num_adults, $num_children, $requests, $check_in, $check_out, $total_price);

        if ($stmt->execute()) {
            // Extract just the first name to make the success page feel personal
            $first_name = trim($_POST['first_name']);
            
            // Redirect the guest to the beautiful new Success Page!
            header("Location: booking-success.php?name=" . urlencode($first_name));
            exit();
        } else {
            showAmariMessage("System Error", "We could not process your reservation due to a server error. " . $stmt->error, "index.php", "Return Home");
        }
        
        $stmt->close();
    } // <--- THIS WAS THE MISSING BRACKET!

    $check_stmt->close();
    $conn->close();
} else {
    header("Location: index.php");
    exit();
}
?>