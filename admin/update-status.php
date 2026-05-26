<?php
// 1. Connect to the plumbing
include('../includes/db-config.php');
include('../includes/mailer.php'); // Include the Mailer Brain we just created

// 2. The "Handshake" Logic
// We look at the URL to see which booking ID and which action (approve/decline) was sent
if (isset($_GET['id']) && isset($_GET['action'])) {
    // Best practice: cast the ID to an integer for security
    $id = intval($_GET['id']);
    $action = $_GET['action'];

    // Determine the new status based on the button clicked
    $new_status = ($action == 'approve') ? 'approved' : 'declined';

    // 3. The "Update" Instruction
    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $id);

    // If the database update is successful...
    if ($stmt->execute()) {
        
        // --- NEW: THE AUTOMATED EMAIL TRIGGER ---
        // Only send the email if the action was an approval
        if ($new_status === 'approved') {
            
            // Fetch the guest's details from the database so we can personalize the email
            $guest_stmt = $conn->prepare("SELECT guest_name, guest_email, check_in, check_out FROM bookings WHERE id = ?");
            $guest_stmt->bind_param("i", $id);
            $guest_stmt->execute();
            $guest_result = $guest_stmt->get_result();
            
            if ($guest_result->num_rows > 0) {
                $guest_data = $guest_result->fetch_assoc();
                
                $email = $guest_data['guest_email'];
                $name = $guest_data['guest_name'];
                
                // Format dates for a luxury feel (e.g., "May 26, 2026")
                $in = date("F j, Y", strtotime($guest_data['check_in']));
                $out = date("F j, Y", strtotime($guest_data['check_out']));

                // Construct the Luxury HTML Email
                $subject = "Reservation Confirmed: Amari Alabang";
                $body = "
                    <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: auto; border: 1px solid #eee; padding: 30px;'>
                        <h2 style='color: #042e47; border-bottom: 2px solid #b0885a; padding-bottom: 10px;'>Amari Alabang</h2>
                        <p>Dear <strong>$name</strong>,</p>
                        <p>We are thrilled to confirm your reservation. We look forward to hosting you!</p>
                        <div style='background: #f9f9f9; padding: 15px; margin: 20px 0; border-left: 4px solid #b0885a;'>
                            <p style='margin: 5px 0;'><strong>Check-in:</strong> $in (3:00 PM)</p>
                            <p style='margin: 5px 0;'><strong>Check-out:</strong> $out (12:00 PM)</p>
                        </div>
                        <p>If you have any special requests prior to arrival, simply reply to this email.</p>
                        <p>Warm regards,<br><strong>The Amari Team</strong></p>
                    </div>
                ";

                // Fire the email
                sendAmariEmail($email, $name, $subject, $body);
            }
            $guest_stmt->close();
        }
        // ----------------------------------------

        // 4. Redirection: Once the work (and emailing) is done, go back to the dashboard
        header("Location: dashboard.php?msg=success");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>