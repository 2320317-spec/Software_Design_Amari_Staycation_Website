<?php
// 1. Security & Plumbing
include('auth.php');
include('../includes/db-config.php');

// 2. Logic: Fetch the 3 units allowed by the SRS
$sql = "SELECT * FROM units LIMIT 3";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Units - Amari Host</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .unit-edit-box { background: white; padding: 20px; margin-bottom: 20px; border-radius: 8px; border-left: 5px solid #2c3e50; }
        label { font-weight: bold; display: block; margin-top: 10px; }
        input, textarea { width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <header>
        <nav><a href="dashboard.php" style="color:white;">← Back to Dashboard</a></nav>
        <h1>Property Management</h1>
        <p>Update pricing and details for your Alabang units</p>
    </header>

    <main style="padding: 20px; max-width: 800px; margin: auto;">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="unit-edit-box">
                <form action="update-unit-logic.php" method="POST">
                    <input type="hidden" name="unit_id" value="<?php echo $row['id']; ?>">
                    
                    <h3>Unit: <?php echo $row['title']; ?></h3>
                    
                    <label>Display Title:</label>
                    <input type="text" name="title" value="<?php echo $row['title']; ?>" required>

                    <label>Price per Night (₱):</label>
                    <input type="number" name="price" value="<?php echo $row['price_per_night']; ?>" step="0.01" required>

                    <label>Description:</label>
                    <textarea name="description" rows="3"><?php echo $row['description']; ?></textarea>

                    <button type="submit" class="btn" style="margin-top:15px; border:none; cursor:pointer;">Update Unit Details</button>
                </form>
            </div>
        <?php endwhile; ?>
    </main>
</body>
</html>

<nav>
    <a href="dashboard.php">← Back to Reservations</a>
</nav>