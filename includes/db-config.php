<?php
// Database Credentials — loaded from includes/secrets.php when present
// (production / Hostinger), otherwise falls back to local XAMPP defaults.
$secrets_file = __DIR__ . '/secrets.php';
$secrets = file_exists($secrets_file) ? require $secrets_file : [];

$db = isset($secrets['db']) ? $secrets['db'] : [
    'host' => 'localhost', // Default XAMPP host
    'user' => 'root',      // Default XAMPP username
    'pass' => '',          // Default XAMPP password is empty
    'name' => 'amari_db',  // Your database name
];

$servername = $db['host'];
$username   = $db['user'];
$password   = $db['pass'];
$dbname     = $db['name'];

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