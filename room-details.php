<?php
include('includes/db-config.php');

// 1. Intercept the ID from the URL
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // 2. Fetch the specific unit's full details
    $stmt = $conn->prepare("SELECT * FROM units WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $unit = $result->fetch_assoc();

    // If someone types a fake ID, send them back to home
    if(!$unit) {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

// 3. Set the image path (fallback if missing)
$hero_image = !empty($unit['image_path']) ? 'images/' . $unit['image_path'] : 'images/split-bg.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $unit['title']; ?> - Amari Alabang</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --amari-tan: #b0885a;
            --amari-navy: #042e47;
            --text-dark: #333333;
        }
        body { margin: 0; font-family: 'Lato', sans-serif; color: var(--text-dark); background: #fafafa; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; }
        
        /* Navigation (Simplified for sub-pages) */
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 15px 50px; background: white; border-bottom: 1px solid #eee; }
        .navbar a { text-decoration: none; color: var(--amari-navy); font-weight: bold; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem;}
        
        /* The Immersive Hero */
        .room-hero {
            height: 65vh;
            background: url('<?php echo $hero_image; ?>') center/cover;
            position: relative;
            display: flex;
            align-items: flex-end;
            padding: 50px;
        }
        .room-hero::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to top, rgba(4, 46, 71, 0.9), transparent);
        }
        .hero-content {
            position: relative; z-index: 2; color: white; max-width: 800px;
        }
        .hero-content h1 { font-size: 4rem; margin: 0 0 10px 0; letter-spacing: 2px; }
        .price-tag { font-size: 1.5rem; color: var(--amari-tan); font-weight: bold; font-family: 'Lato', sans-serif; }

        /* The Details Section */
        .details-container {
            max-width: 1000px; margin: -50px auto 50px auto; background: white; padding: 50px; border-radius: 10px; box-shadow: 0 15px 40px rgba(0,0,0,0.08); position: relative; z-index: 10; display: flex; flex-wrap: wrap; gap: 50px;
        }
        .description { flex: 2; min-width: 300px; }
        .description p { line-height: 1.8; color: #555; font-size: 1.1rem; margin-bottom: 20px;}
        
        .sidebar { flex: 1; min-width: 250px; background: #f4f6f8; padding: 30px; border-radius: 8px; border-top: 4px solid var(--amari-tan); }
        .sidebar h3 { color: var(--amari-navy); margin-top: 0; font-size: 1.5rem; border-bottom: 1px solid #ddd; padding-bottom: 15px;}
        .amenity-list { list-style: none; padding: 0; margin: 20px 0;}
        .amenity-list li { margin-bottom: 12px; color: #555; display: flex; align-items: center; }
        .amenity-list li::before { content: '✓'; color: var(--amari-tan); font-weight: bold; margin-right: 10px; }

        .btn-book-large {
            display: block; width: 100%; text-align: center; background: var(--amari-navy); color: white; padding: 18px; text-decoration: none; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; margin-top: 30px; transition: 0.3s;
        }
        .btn-book-large:hover { background: var(--amari-tan); }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="index.php">&lsaquo; Back to Home</a>
    <h2 style="margin: 0; font-family: 'Playfair Display', serif; color: var(--amari-navy);">AMARI ALABANG</h2>
</nav>

<header class="room-hero">
    <div class="hero-content">
        <h1><?php echo $unit['title']; ?></h1>
        <div class="price-tag">₱<?php echo number_format($unit['price_per_night'], 2); ?> <span style="font-size: 1rem; color: #ccc;">/ night</span></div>
    </div>
</header>

<div class="details-container">
    <div class="description">
        <h2 style="color: var(--amari-navy); font-size: 2rem;">The Experience</h2>
        <p><?php echo nl2br($unit['description']); ?></p>
    </div>

    <div class="sidebar">
        <h3>Room Amenities</h3>
        <ul class="amenity-list">
            <?php 
                if(!empty($unit['amenities'])) {
                    $amenities_array = explode(',', $unit['amenities']);
                    foreach($amenities_array as $item) {
                        echo "<li>" . trim($item) . "</li>";
                    }
                } else {
                    echo "<li>Premium Accommodations</li>";
                }
            ?>
        </ul>

        <a href="booking-form.php?unit_id=<?php echo $unit['id']; ?>" class="btn-book-large">Reserve Dates</a>
    </div>
</div>

</body>
</html>