<?php
session_start(); // Start the session to remember the user 
include('../includes/db-config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    // Search for the admin in the database [cite: 143, 179]
    $stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        // Check if password matches (Using simple check for now as per our seed data) [cite: 143]
        if ($pass === $row['password']) {
            $_SESSION['admin_id'] = $row['id']; // Create the session "Key" 
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Admin user not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login - Amari</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body style="display: flex; justify-content: center; align-items: center; height: 100vh;">
    <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); width: 300px;">
        <h2 style="text-align: center;">Host Login</h2>
        <?php if(isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
        <form method="POST">
            <label>Username:</label><br>
            <input type="text" name="username" required style="width: 100%; margin-bottom: 15px;"><br>
            <label>Password:</label><br>
            <input type="password" name="password" required style="width: 100%; margin-bottom: 15px;"><br>
            <button type="submit" class="btn" style="width: 100%; border: none; cursor: pointer;">Login</button>
        </form>
    </div>
</body>
</html>