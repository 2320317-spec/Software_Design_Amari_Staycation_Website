<?php
session_start();
session_destroy(); // Clears all session data [cite: 280]
header("Location: login.php");
exit();
?>