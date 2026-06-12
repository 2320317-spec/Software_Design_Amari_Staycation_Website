<?php
// Returns availability for every available unit across a given date range.
// Response: { "ok": true, "units": { "<unit_id>": true|false, ... } }
//   true  = free for the selected dates
//   false = already reserved (pending/approved) overlapping the range
include('includes/db-config.php');
header('Content-Type: application/json');

$checkin  = isset($_GET['checkin'])  ? trim($_GET['checkin'])  : '';
$checkout = isset($_GET['checkout']) ? trim($_GET['checkout']) : '';

// Validate the incoming dates
$in_ts  = strtotime($checkin);
$out_ts = strtotime($checkout);
if (!$in_ts || !$out_ts || $out_ts <= $in_ts) {
    echo json_encode(['ok' => false, 'error' => 'Invalid date range']);
    exit();
}
$checkin  = date('Y-m-d', $in_ts);
$checkout = date('Y-m-d', $out_ts);

// Start by assuming every available unit is free
$availability = [];
$units_res = $conn->query("SELECT id FROM units WHERE status = 'available'");
while ($u = $units_res->fetch_assoc()) {
    $availability[(int)$u['id']] = true;
}

// Flag any unit with an overlapping pending/approved booking as taken
$sql = "SELECT DISTINCT unit_id FROM bookings
        WHERE status IN ('pending','approved')
        AND (check_in < ? AND check_out > ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $checkout, $checkin);
$stmt->execute();
$res = $stmt->get_result();
while ($row = $res->fetch_assoc()) {
    $availability[(int)$row['unit_id']] = false;
}
$stmt->close();

echo json_encode(['ok' => true, 'units' => $availability]);
