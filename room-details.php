<?php
include('includes/db-config.php');

// 1. Intercept the ID from the URL
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // 2. Fetch the specific unit's full details securely
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

// 3. Set the correct image path to your assets folder
$hero_image = !empty($unit['image_path']) ? 'assets/images/' . $unit['image_path'] : 'assets/images/exp-hero.jpg';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($unit['title']); ?> | Amari Alabang</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* --- SIGNATURE AMARI BRANDING --- */
        :root {
            --amari-tan: #b0885a;
            --amari-navy: #042e47;
            --text-dark: #333333;
            --bg-light: #f9f9f9;
        }
        body { margin: 0; font-family: 'Lato', sans-serif; color: var(--text-dark); background: var(--bg-light); overflow-x: hidden; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; font-weight: 400; }
        
        /* --- SYNCED NAVBAR --- */
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 15px 50px; background: white; border-bottom: 1px solid #eee; position: sticky; top: 0; z-index: 100; }
        .nav-links a { margin: 0 15px; text-decoration: none; color: #555; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; }
        .nav-links a:hover { color: var(--amari-tan); }
        .btn-book-nav { background-color: var(--amari-tan); color: white; padding: 12px 25px; text-decoration: none; font-weight: bold; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; transition: 0.3s; }
        .btn-book-nav:hover { background-color: var(--amari-navy); }
        
        /* --- IMMERSIVE HERO --- */
        .room-hero {
            height: 75vh;
            background: url('<?php echo $hero_image; ?>') center/cover;
            position: relative;
            display: flex;
            align-items: flex-end;
            padding: 80px 10%;
        }
        .room-hero::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; 
            background: linear-gradient(to top, rgba(4, 46, 71, 0.95), transparent, rgba(0,0,0,0.3));
        }
        .hero-content { position: relative; z-index: 2; color: white; max-width: 800px; }
        .hero-content h1 { font-size: 4.5rem; margin: 0 0 10px 0; letter-spacing: 3px; text-transform: uppercase; text-shadow: 2px 2px 15px rgba(0,0,0,0.5); }
        .price-tag { font-size: 1.8rem; color: var(--amari-tan); font-weight: bold; font-family: 'Lato', sans-serif; letter-spacing: 1px; }

        /* --- THE DETAILS SECTION --- */
        .details-container {
            max-width: 1200px; margin: -60px auto 80px auto; background: white; padding: 60px; 
            border-radius: 5px; box-shadow: 0 20px 50px rgba(0,0,0,0.08); position: relative; z-index: 10; 
            display: flex; flex-wrap: wrap; gap: 60px; border-top: 5px solid var(--amari-tan);
        }
        
        .description { flex: 2; min-width: 350px; }
        .description h2 { color: var(--amari-navy); font-size: 2.5rem; margin-top: 0; margin-bottom: 30px; }
        .description p { line-height: 2; color: #555; font-size: 1.1rem; margin-bottom: 20px; }
        
        /* Sidebar Panel */
        .sidebar-panel { flex: 1; min-width: 300px; background: #fdfcfb; padding: 40px; border: 1px solid #eee; border-radius: 5px; }
        .sidebar-panel h3 { color: var(--amari-navy); margin-top: 0; font-size: 1.5rem; border-bottom: 1px solid #ddd; padding-bottom: 15px; text-transform: uppercase; letter-spacing: 2px; }
        
        .amenity-list { list-style: none; padding: 0; margin: 25px 0; }
        .amenity-list li { margin-bottom: 15px; color: #555; display: flex; align-items: center; font-size: 0.95rem; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .amenity-list li i { color: var(--amari-tan); font-size: 1.2rem; margin-right: 15px; width: 20px; text-align: center; }

        /* Action Button */
        .btn-book-large {
            display: block; width: 100%; text-align: center; background: var(--amari-navy); color: white; padding: 20px; text-decoration: none; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; margin-top: 40px; transition: 0.4s; font-size: 0.9rem; border-radius: 3px;
        }
        .btn-book-large:hover { background: var(--amari-tan); transform: translateY(-3px); box-shadow: 0 10px 20px rgba(176, 136, 90, 0.2); }

        /* --- CONTINUOUS SCROLL ANIMATIONS --- */
        .fade-slide-up { opacity: 0; transform: translateY(50px); transition: opacity 0.8s ease-out, transform 0.8s ease-out; }
        .fade-slide-left { opacity: 0; transform: translateX(-50px); transition: opacity 0.8s ease-out, transform 0.8s ease-out; }
        .fade-slide-right { opacity: 0; transform: translateX(50px); transition: opacity 0.8s ease-out, transform 0.8s ease-out; }
        
        .animate-title { animation: textPopUp 1.2s ease-out forwards; }
        @keyframes textPopUp { 0% { opacity: 0; transform: translateY(40px); } 100% { opacity: 1; transform: translateY(0); } }
        
        .is-visible { opacity: 1; transform: translate(0); }

        /* Site Footer */
        .site-footer { background-color: var(--amari-navy); color: white; padding: 40px 10%; text-align: center; margin-top: 50px; }
    </style>
</head>
<body>

<nav class="navbar">
    <div>
        <h2 style="color: var(--amari-navy); margin: 0; font-family: 'Playfair Display', serif;">AMARI ALABANG</h2>
    </div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="index.php#rooms" style="color: var(--amari-tan);">Rooms</a>
        <a href="experience.php">Experience</a>
        <a href="gallery.php">Gallery</a>
    </div>
    <a href="booking-form.php?unit_name=<?php echo urlencode($unit['title']); ?>" class="btn-book-nav">Book Now</a>
</nav>

<header class="room-hero">
    <div class="hero-content">
        <h1 class="animate-title"><?php echo htmlspecialchars($unit['title']); ?></h1>
        <div class="price-tag animate-title" style="animation-delay: 0.2s; opacity: 0;">
            ₱<?php echo number_format($unit['price_per_night'], 2); ?> <span style="font-size: 1rem; color: #ddd; font-weight: normal; text-transform: uppercase;">/ night</span>
        </div>
    </div>
</header>

<main class="details-container fade-slide-up">
    
    <div class="description fade-slide-left" style="transition-delay: 0.2s;">
        <h2>The Experience</h2>
        <p><?php echo nl2br(htmlspecialchars($unit['description'])); ?></p>
        
        <p style="font-family: 'Playfair Display', serif; font-style: italic; color: var(--amari-tan); font-size: 1.3rem; margin-top: 40px;">
            "Designed for those who seek tranquility amidst the vibrant energy of the city."
        </p>
    </div>

    <div class="sidebar-panel fade-slide-right" style="transition-delay: 0.4s;">
        <h3>Room Amenities</h3>
        <ul class="amenity-list">
            <?php 
                if(!empty($unit['amenities'])) {
                    $amenities_array = explode(',', $unit['amenities']);
                    foreach($amenities_array as $item) {
                        // Dynamically assigning icons based on keywords
                        $icon = 'fa-check';
                        if(stripos($item, 'wifi') !== false) $icon = 'fa-wifi';
                        if(stripos($item, 'pool') !== false) $icon = 'fa-person-swimming';
                        if(stripos($item, 'gym') !== false) $icon = 'fa-dumbbell';
                        if(stripos($item, 'view') !== false) $icon = 'fa-city';
                        if(stripos($item, 'tv') !== false) $icon = 'fa-tv';
                        if(stripos($item, 'kitchen') !== false) $icon = 'fa-sink';
                        
                        echo "<li><i class='fa-solid {$icon}'></i> " . htmlspecialchars(trim($item)) . "</li>";
                    }
                } else {
                    echo "<li><i class='fa-solid fa-star'></i> Premium Accommodations</li>";
                }
            ?>
        </ul>

        <a href="booking-form.php?unit_name=<?php echo urlencode($unit['title']); ?>" class="btn-book-large">Reserve Dates</a>
    </div>
</main>

<footer class="site-footer fade-slide-up">
    <p>&copy; 2026 Amari Staycation Alabang. Experience the Refined City Life.</p>
</footer>

<script>
    const observerOptions = { root: null, rootMargin: '0px', threshold: 0.15 };
    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            }
        });
    }, observerOptions);

    document.querySelectorAll('.fade-slide-up, .fade-slide-left, .fade-slide-right').forEach(el => {
        scrollObserver.observe(el);
    });
</script>

</body>
</html>