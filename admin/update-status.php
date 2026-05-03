<?php
// 1. Connect to the plumbing
include('../includes/db-config.php');

// 2. The "Handshake" Logic
// We look at the URL to see which booking ID and which action (approve/decline) was sent
if (isset($_GET['id']) && isset($_GET['action'])) {
    $id = $_GET['id'];
    $action = $_GET['action'];

    // Determine the new status based on the button clicked
    $new_status = ($action == 'approve') ? 'approved' : 'declined';

    // 3. The "Update" Instruction (SRS 2.1.2: Update status to Approve/Decline)
    $stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $id);

    if ($stmt->execute()) {
        // 4. Redirection: Once the work is done, go back to the dashboard
        header("Location: dashboard.php?msg=success");
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
}
$conn->close();
?>