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

                // Construct the Luxury HTML Email Invoice
                $subject = "Your Amari Reservation is Confirmed";
                
                $body = '
                <div style="font-family: Arial, Helvetica, sans-serif; background-color: #f4f4f4; padding: 40px 0; margin: 0; width: 100%;">
                    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                        
                        <div style="background-color: #042e47; padding: 50px 20px; text-align: center;">
                            <h1 style="color: #b0885a; margin: 0; font-family: \'Playfair Display\', Georgia, serif; font-size: 38px; letter-spacing: 8px; text-transform: uppercase; font-weight: 400;">Amari</h1>
                            
                            <hr style="border: none; border-top: 1px solid #b0885a; width: 80px; margin: 10px auto;">
                            
                            <p style="color: #c4cdd2; margin: 0; font-family: \'Lato\', Arial, sans-serif; font-size: 10px; letter-spacing: 6px; text-transform: uppercase; font-weight: bold;">Alabang Staycation</p>
                        </div>
                        
                        <div style="padding: 40px 40px 20px 40px; color: #333333;">
                            <h2 style="font-family: Georgia, serif; font-size: 22px; color: #042e47; margin-top: 0;">Reservation Confirmed</h2>
                            <p style="font-size: 15px; line-height: 1.6; color: #555;">Dear <strong>' . $name . '</strong>,</p>
                            <p style="font-size: 15px; line-height: 1.6; color: #555;">Your reservation has been successfully processed. We are delighted to host you, and we have prepared your official itinerary below.</p>
                            
                            <div style="margin: 35px 0; border: 1px solid #eeeeee; border-radius: 8px; overflow: hidden;">
                                <div style="background-color: #fafafa; padding: 15px 20px; border-bottom: 1px solid #eeeeee;">
                                    <h3 style="margin: 0; font-size: 12px; color: #888888; text-transform: uppercase; letter-spacing: 2px;">Itinerary Details</h3>
                                </div>
                                <div style="padding: 25px 20px;">
                                    <table style="width: 100%; font-size: 15px; line-height: 1.6; border-collapse: collapse;">
                                        <tr>
                                            <td style="padding-bottom: 15px; color: #888; border-bottom: 1px solid #f0f0f0;">Check-in Date</td>
                                            <td style="padding-bottom: 15px; font-weight: bold; text-align: right; color: #333; border-bottom: 1px solid #f0f0f0;">' . $in . ' <br><span style="font-size: 12px; color: #b0885a; font-weight: normal;">From 3:00 PM</span></td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 15px; padding-bottom: 15px; color: #888; border-bottom: 1px solid #f0f0f0;">Check-out Date</td>
                                            <td style="padding-top: 15px; padding-bottom: 15px; font-weight: bold; text-align: right; color: #333; border-bottom: 1px solid #f0f0f0;">' . $out . ' <br><span style="font-size: 12px; color: #b0885a; font-weight: normal;">By 12:00 PM</span></td>
                                        </tr>
                                        <tr>
                                            <td style="padding-top: 15px; color: #888;">Status</td>
                                            <td style="padding-top: 15px; font-weight: bold; text-align: right; color: #27ae60;">CONFIRMED</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
            
                            <p style="font-size: 14px; line-height: 1.6; color: #666; text-align: center; margin-top: 30px;">
                                Prior to your arrival, our concierge will send you your digital door code and check-in guidelines. 
                            </p>
                        </div>
                        
                        <div style="background-color: #f9f9f9; padding: 25px; text-align: center; border-top: 1px solid #eeeeee;">
                            <p style="margin: 0; font-size: 12px; color: #999; letter-spacing: 1px; text-transform: uppercase;">Amari Alabang Management</p>
                            <p style="margin: 8px 0 0 0; font-size: 12px; color: #b0885a;">Need assistance? Reply directly to this email.</p>
                        </div>
                        
                    </div>
                </div>';

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