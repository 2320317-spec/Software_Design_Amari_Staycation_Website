<?php
include('auth.php');
include('../includes/db-config.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Property - Amari Alabang</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        .form-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px var(--shadow-tint);
            border-top: 5px solid var(--mahogany);
        }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 700; color: var(--mahogany); text-transform: uppercase; font-size: 0.8rem; }
        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            font-size: 1rem;
        }
        .btn-add {
            background: var(--gold);
            color: white;
            border: none;
            padding: 15px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
            width: 100%;
            text-transform: uppercase;
            transition: 0.3s;
        }
        .btn-add:hover { background: var(--mahogany); }
    </style>
</head>
<body>

<header>
    <div class="container">
        <h1>New Listing</h1>
        <p>Adding a premium unit to the collection</p>
    </div>
</header>

<main class="container">
    <div class="form-container">
        <form action="process-add-unit.php" method="POST">
            <div class="form-group">
                <label>Unit Title</label>
                <input type="text" name="title" placeholder="e.g. Sapphire Executive Suite" required>
            </div>

            <div class="form-group" style="display: flex; gap: 20px;">
                <div style="flex: 1;">
                    <label>Price / Night (₱)</label>
                    <input type="number" step="0.01" name="price" placeholder="0.00" required>
                </div>
                <div style="flex: 1;">
                    <label>Initial Status</label>
                    <select name="status">
                        <option value="available">Available</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Amenities</label>
                <input type="text" name="amenities" placeholder="e.g. Smart TV, Balcony, King Bed">
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="5" placeholder="Describe the luxury experience..."></textarea>
            </div>

            <button type="submit" class="btn-add">Launch Listing</button>
            <a href="manage-units.php" style="display:block; text-align:center; margin-top:20px; color:#aaa; text-decoration:none;">Cancel</a>
        </form>
    </div>
</main>

</body>
</html>