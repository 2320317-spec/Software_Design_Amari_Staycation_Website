<?php
include('includes/db-config.php');

$unit_id = isset($_GET['unit_id']) ? intval($_GET['unit_id']) : 0;

// Fetch bookings
$stmt = $conn->prepare("SELECT check_in, check_out FROM bookings WHERE unit_id = ? AND status IN ('pending', 'approved')");
$stmt->bind_param("i", $unit_id);
$stmt->execute();
$result = $stmt->get_result();

$disabled_dates = [];
while ($row = $result->fetch_assoc()) {
    // 1. Convert the checkout date to a DateTime object
    $checkoutDate = new DateTime($row['check_out']);
    
    // 2. Subtract 1 day because checkout is at 12pm, making it free for a new check-in
    $checkoutDate->modify('-1 day');
    $effective_checkout = $checkoutDate->format('Y-m-d');

    // 3. Set the disabled range
    $disabled_dates[] = [
        'from' => $row['check_in'],
        'to'   => $effective_checkout
    ];
}

header('Content-Type: application/json');
echo json_encode($disabled_dates);
?>