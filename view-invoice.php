<?php
include('includes/db-config.php');

// Get the booking ID from the URL
if (!isset($_GET['id'])) { header("Location: index.php"); exit(); }
$booking_id = $_GET['id'];

// Fetch booking and unit details together using a JOIN (Logic efficiency)
$sql = "SELECT b.*, u.title as unit_name, u.price_per_night, u.reservation_fee 
        FROM bookings b 
        JOIN units u ON b.unit_id = u.id 
        WHERE b.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data || $data['status'] !== 'approved') {
    die("Invoice not available or booking not yet approved.");
}

// Calculation Logic (SRS 2.1.2: Appending mandatory fee)
$days = (strtotime($data['check_out']) - strtotime($data['check_in'])) / 86400;
$room_total = $days * $data['price_per_night'];
$grand_total = $room_total + $data['reservation_fee'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - Amari Staycation</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body style="background:#eee; padding:50px;">
    <div style="max-width:600px; margin:auto; background:white; padding:30px; border:1px solid #ddd;">
        <h2 style="text-align:center;">AMARI STAYCATION INVOICE</h2>
        <hr>
        <p><strong>Guest:</strong> <?php echo $data['guest_title'] . " " . $data['guest_name']; ?></p>
        <p><strong>Unit:</strong> <?php echo $data['unit_name']; ?></p>
        <p><strong>Dates:</strong> <?php echo $data['check_in']; ?> to <?php echo $data['check_out']; ?> (<?php echo $days; ?> nights)</p>
        
        <table style="width:100%; margin-top:20px; border-top:2px solid #333;">
            <tr><td>Room Total:</td><td style="text-align:right;">₱<?php echo number_format($room_total, 2); ?></td></tr>
            <tr><td>Reservation Fee:</td><td style="text-align:right;">₱<?php echo number_format($data['reservation_fee'], 2); ?></td></tr>
            <tr style="font-weight:bold; font-size:1.2em;"><td>Total Amount:</td><td style="text-align:right;">₱<?php echo number_format($grand_total, 2); ?></td></tr>
        </table>

        <div style="margin-top:30px; text-align:center; border:2px dashed #e67e22; padding:20px;">
            <h3>Payment Instructions</h3>
            <p>Please scan the QR code below via GCash or Maya to settle your reservation fee.</p>
            <img src="assets/images/payment_qr.png" alt="Payment QR Code" style="width:200px; height:200px; background:#ccc;">
            <p style="font-size:0.8em; color:#666;">Reference ID: AMRI-<?php echo $data['id']; ?></p>
        </div>
    </div>
</body>
</html>