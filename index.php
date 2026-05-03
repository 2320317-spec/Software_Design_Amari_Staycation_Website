<?php 
// 1. Load the database connection (The Plumbing)
include('includes/db-config.php'); 

// 2. Fetch the units (The Grocery List)
$sql = "SELECT * FROM units LIMIT 3"; 
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amari Staycation - Alabang</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Amari Staycation - Alabang</h1>
    </header>

    <main>
        <?php
        // 3. Logic: If there is data in the database, show it! [cite: 108, 109]
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<div class='unit-card'>";
                echo "<h2>" . $row['title'] . "</h2>";
                echo "<p>" . $row['description'] . "</p>";
                echo "<strong>Price: ₱" . number_format($row['price_per_night'], 2) . "</strong>";
                echo "<br><a href='booking.php?unit_id=" . $row['id'] . "'>Book This Unit</a>";
                echo "</div><hr>";
            }
        } else {
            echo "No units available at the moment.";
        }
        ?>
    </main>
</body>
</html>