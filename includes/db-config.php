<?php
// Database Credentials - Back to Standard Defaults
$servername = "localhost"; // Default port 3306
$username = "root";        // Default XAMPP username
$password = "";            // Default XAMPP password is empty
$dbname = "amari_db";      // Your database name

// 1. Create the Connection
$conn = new mysqli($servername, $username, $password, $dbname);

// 2. The Handshake Test (Check Connection)
if ($conn->connect_error) {
    // If localhost fails, some systems prefer 127.0.0.1
    // We die and show the error so you know exactly what happened.
    die("Database Connection Failed: " . $conn->connect_error);
}

// 3. Set Encoding (Important for Philippine Peso symbols)
$conn->set_charset("utf8mb4");

// No closing tag needed for pure PHP files to prevent "Header already sent" errors