<?php
session_start();
include('../includes/db-config.php'); // Connecting to the plumbing

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Capture the "Signals" from the form
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // 2. Prepare the Query (Security Guard Protocol)
    // We use "?" to prevent SQL Injection
    $stmt = $conn->prepare("SELECT id, username, password FROM admins WHERE username = ? LIMIT 1");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // 3. Compare the "Key" (Password Verification)
        // Note: For a school project, you might be using plain text. 
        // If you used password_hash() earlier, use password_verify($pass, $row['password'])
        if ($pass === $row['password']) {
            
            // 4. Access Granted: Set the Session
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['admin_name'] = $row['username'];
            
            // Send them to the Dashboard
            header("Location: dashboard.php");
            exit();
        }
    }

    // 5. Access Denied: Send them back with an error flag
    header("Location: login.php?error=1");
    exit();
} else {
    // If someone tries to access this file directly without the form
    header("Location: login.php");
    exit();
}
?>