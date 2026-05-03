<?php 
// 1. Connect the plumbing
include('includes/db-config.php'); 

// 2. Identify which unit the guest clicked on
// If no unit_id is provided, we send them back to the home page (Logic Safety)
if(!isset($_GET['unit_id'])) {
    header("Location: index.php");
    exit();
}

$unit_id = $_GET['unit_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Book Your Stay - Amari Alabang</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Request a Reservation</h1>
        <p>Please provide your details to check for availability [cite: 202]</p>
    </header>

    <main class="form-container">
        <form action="process-booking.php" method="POST">
            <input type="hidden" name="unit_id" value="<?php echo $unit_id; ?>">

            <fieldset>
                <legend>Guest Information</legend>
                
                <label for="guest_title">Title:</label>
                <select name="guest_title" id="guest_title" required>
                    <option value="Mr.">Mr.</option>
                    <option value="Ms.">Ms.</option>
                    <option value="Mrs.">Mrs.</option>
                </select>

                <label for="guest_name">Full Name:</label>
                <input type="text" name="guest_name" id="guest_name" placeholder="Juan Dela Cruz" required>

                <label for="guest_email">Email Address:</label>
                <input type="email" name="guest_email" id="guest_email" placeholder="juan@example.com" required>
            </fieldset>

            <fieldset>
                <legend>Stay Details</legend>
                <label for="check_in">Check-in Date:</label>
                <input type="date" name="check_in" id="check_in" required>

                <label for="check_out">Check-out Date:</label>
                <input type="date" name="check_out" id="check_out" required>
            </fieldset>

            [cite_start]<button type="submit" class="btn">Submit Booking Request [cite: 355]</button>
        </form>
    </main>
</body>
</html>