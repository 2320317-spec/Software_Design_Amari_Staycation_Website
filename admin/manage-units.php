<?php
include('auth.php');
include('../includes/db-config.php');

// Fetch all units - Calibrated to use 'title' for sorting
$result = $conn->query("SELECT * FROM units ORDER BY title ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Unit Inventory - Amari Alabang</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* The Grid Logic */
        .unit-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        /* The Card Architecture */
        .unit-card {
            background: var(--white);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 20px var(--shadow-tint);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(80, 37, 21, 0.05);
        }

        .unit-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(80, 37, 21, 0.2);
        }

        /* Image Display Logic */
        .unit-image-container {
            width: 100%;
            height: 220px;
            overflow: hidden;
            background-color: #f8f1e9;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .unit-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .no-image {
            color: var(--coffee);
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.5;
        }

        .unit-details {
            padding: 20px;
            flex-grow: 1;
        }

        .unit-name {
            color: var(--mahogany);
            font-size: 1.3rem;
            margin-bottom: 5px;
            font-weight: 700;
        }

        .unit-price {
            color: var(--gold);
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .unit-amenities {
            font-size: 0.75rem;
            color: var(--mahogany);
            background: rgba(194, 166, 110, 0.1);
            padding: 8px 12px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: inline-block;
        }

        .unit-desc {
            font-size: 0.9rem;
            color: #666;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        /* Status Badge */
        .status-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 6px 15px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1px;
            z-index: 10;
        }

        .status-available { background: #48bb78; color: white; }
        .status-maintenance { background: #f56565; color: white; }
        .status-booked { background: var(--gold); color: white; }

        .card-actions {
            border-top: 1px solid #f0f0f0;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            background: #fafafa;
        }

        .btn-edit {
            color: var(--mahogany);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 700;
            text-transform: uppercase;
        }

        .btn-edit:hover { color: var(--gold); }
    </style>
</head>
<body>

<header>
    <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Luxury Collection</h1>
            <p>Manage your premium staycation inventory</p>
        </div>
        <a href="dashboard.php" class="btn" style="background: var(--gold);">Back to Dashboard</a>
    </div>
</header>

<main class="container">
    <div style="margin-top: 30px;">
        <a href="add-unit.php" class="btn" style="background: var(--mahogany);">+ Add New Property</a>
    </div>

    <div class="unit-grid">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="unit-card">
                <div class="status-badge status-<?php echo strtolower($row['status']); ?>">
                    <?php echo $row['status']; ?>
                </div>

                <div class="unit-image-container">
                    <?php if(!empty($row['image_path'])): ?>
                        <img src="../images/<?php echo $row['image_path']; ?>" alt="<?php echo $row['title']; ?>">
                    <?php else: ?>
                        <div class="no-image">No Image Preview</div>
                    <?php endif; ?>
                </div>

                <div class="unit-details">
                    <div class="unit-name"><?php echo $row['title']; ?></div>
                    <div class="unit-price">₱<?php echo number_format($row['price_per_night'], 2); ?> <small style="font-size: 0.7rem; color: #999;">/ night</small></div>
                    
                    <?php if(!empty($row['amenities'])): ?>
                        <div class="unit-amenities">
                            📍 <?php echo $row['amenities']; ?>
                        </div>
                    <?php endif; ?>

                    <p class="unit-desc">
                        <?php echo substr($row['description'], 0, 100) . '...'; ?>
                    </p>
                </div>

                <div class="card-actions">
                    <a href="edit-unit.php?id=<?php echo $row['id']; ?>" class="btn-edit">Edit Details</a>
                    <a href="delete-unit.php?id=<?php echo $row['id']; ?>" class="btn-edit" style="color: #ccc;">Archive</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</main>

</body>
</html>