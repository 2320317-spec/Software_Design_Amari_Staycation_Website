<?php
include('auth.php');
include('../includes/db-config.php');

// 1. Get the ID from the URL (the ?id=X part)
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // 2. Fetch the specific unit data using the 'title' column we found earlier
    $stmt = $conn->prepare("SELECT * FROM units WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $unit = $result->fetch_assoc();

    if(!$unit) { die("Error: Unit not found in the Amari database."); }
} else {
    header("Location: manage-units.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Property - Amari Alabang</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px var(--shadow-tint);
            border-top: 5px solid var(--gold);
        }
        .form-group { margin-bottom: 25px; }
        label { display: block; margin-bottom: 8px; font-weight: 700; color: var(--mahogany); text-transform: uppercase; font-size: 0.8rem; }
        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            font-size: 1rem;
            background: #fcfcfc;
        }
        input:focus { border-color: var(--gold); outline: none; background: white; }
        .btn-save {
            background: var(--mahogany);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: 0.3s;
        }
        .btn-save:hover { background: var(--gold); transform: translateY(-2px); }
    </style>
</head>
<body>

<header>
    <div class="container">
        <h1>Refine Property</h1>
        <p>Updating: <?php echo $unit['title']; ?></p>
    </div>
</header>

<main class="container">
    <div class="form-container">
        <form action="update-unit-logic.php" method="POST">
            <input type="hidden" name="id" value="<?php echo $unit['id']; ?>">

            <div class="form-group">
                <label>Property Title</label>
                <input type="text" name="title" value="<?php echo $unit['title']; ?>" required>
            </div>

            <div class="form-group" style="display: flex; gap: 20px;">
                <div style="flex: 1;">
                    <label>Price / Night (₱)</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $unit['price_per_night']; ?>" required>
                </div>
                <div style="flex: 1;">
                    <label>Availability Status</label>
                    <select name="status">
                        <option value="available" <?php if($unit['status'] == 'available') echo 'selected'; ?>>Available</option>
                        <option value="maintenance" <?php if($unit['status'] == 'maintenance') echo 'selected'; ?>>Maintenance</option>
                        <option value="booked" <?php if($unit['status'] == 'booked') echo 'selected'; ?>>Booked</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Key Amenities (WiFi, Pool, etc.)</label>
                <input type="text" name="amenities" value="<?php echo $unit['amenities']; ?>">
            </div>

            <div class="form-group">
                <label>Detailed Description</label>
                <textarea name="description" rows="5"><?php echo $unit['description']; ?></textarea>
            </div>

            <button type="submit" class="btn-save">Save Changes</button>
            <a href="manage-units.php" style="display:block; text-align:center; margin-top:20px; color:#aaa; text-decoration:none; font-size: 0.9rem;">Discard Changes</a>
        </form>
    </div>
</main>

</body>
</html>