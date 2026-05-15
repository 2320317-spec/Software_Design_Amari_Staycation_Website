<?php include('includes/db-config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Experience | Amari Staycation Alabang</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --amari-tan: #b0885a;
            --amari-navy: #042e47;
            --text-dark: #333333;
            --bg-light: #f9f9f9;
        }

        body { margin: 0; font-family: 'Lato', sans-serif; color: var(--text-dark); background-color: white; overflow-x: hidden; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; font-weight: 400; }

        /* --- UPDATED NAVBAR --- */
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 15px 50px; background: white; border-bottom: 1px solid #eee; position: sticky; top: 0; z-index: 100; }
        .nav-links a { margin: 0 15px; text-decoration: none; color: #555; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; }
        .nav-links a:hover { color: var(--amari-tan); }
        .btn-book-nav { background-color: var(--amari-tan); color: white; padding: 12px 25px; text-decoration: none; font-weight: bold; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; }

        /* Experience Hero */
        .exp-hero {
            height: 70vh;
            background: linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('assets/images/exp-hero.jpg') center/cover;
            display: flex; align-items: center; justify-content: center; color: white; text-align: center;
        }
        .exp-hero h1 { font-size: 4rem; letter-spacing: 10px; text-transform: uppercase; }

        .exp-intro { text-align: center; padding: 100px 20%; background-color: var(--bg-light); }
        .exp-intro h2 { font-size: 2.5rem; color: var(--amari-navy); margin-bottom: 20px; }
        .exp-intro p { line-height: 1.8; color: #666; font-size: 1.1rem; }

        .amenity-row { display: flex; flex-wrap: wrap; align-items: stretch; min-height: 500px; }
        .amenity-text { flex: 1; min-width: 350px; padding: 80px 8%; display: flex; flex-direction: column; justify-content: center; }
        .amenity-image { flex: 1; min-width: 350px; background-size: cover; background-position: center; }
        .amenity-text h3 { font-size: 2.2rem; color: var(--amari-navy); margin-bottom: 20px; }
        .amenity-text p { line-height: 1.8; color: #666; margin-bottom: 30px; }
        .amenity-tag { display: inline-block; padding: 5px 15px; border: 1px solid var(--amari-tan); color: var(--amari-tan); font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 15px; width: fit-content; }

        .fade-slide-up { opacity: 0; transform: translateY(60px); transition: 0.8s all ease-out; }
        .fade-slide-left { opacity: 0; transform: translateX(-60px); transition: 0.8s all ease-out; }
        .fade-slide-right { opacity: 0; transform: translateX(60px); transition: 0.8s all ease-out; }
        .is-visible { opacity: 1; transform: translate(0); }

        .site-footer { background-color: var(--amari-navy); color: white; padding: 60px 10%; text-align: center; }
    </style>
</head>
<body>

<nav class="navbar">
    <div><h2 style="color: var(--amari-navy); margin: 0; font-family: 'Playfair Display', serif;">AMARI ALABANG</h2></div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="index.php#rooms">Rooms</a>
        <a href="experience.php" style="color: var(--amari-tan);">Experience</a>
        <a href="gallery.php">Gallery</a>
    </div>
    <a href="index.php#rooms" class="btn-book-nav">Book Now</a>
</nav>

<section class="exp-hero">
    <h1 class="fade-slide-up">The Lifestyle</h1>
</section>

<section class="exp-intro fade-slide-up">
    <h2>Beyond Just a Stay</h2>
    <p>At Amari Alabang, we curate moments of serenity amidst the city's hustle. Our world-class amenities are designed to rejuvenate your senses and provide the ultimate urban retreat.</p>
</section>

<section class="amenity-row">
    <div class="amenity-text fade-slide-left">
        <span class="amenity-tag">Recreation</span>
        <h3>The Infinity Edge</h3>
        <p>Soak in the sun at our signature infinity pool. Whether you're doing morning laps or enjoying a sunset cocktail by the water, the deck offers a panoramic view of the Alabang skyline.</p>
        <ul style="list-style: none; padding: 0; color: #888; font-size: 0.9rem;">
            <li><i class="fa-solid fa-check" style="color: var(--amari-tan); margin-right: 10px;"></i> Open daily: 6:00 AM - 10:00 PM</li>
            <li><i class="fa-solid fa-check" style="color: var(--amari-tan); margin-right: 10px;"></i> Complimentary towels for guests</li>
        </ul>
    </div>
    <div class="amenity-image fade-slide-right" style="background-image: url('assets/images/pool-amenity.jpg');"></div>
</section>

<section class="amenity-row" style="flex-direction: row-reverse;">
    <div class="amenity-text fade-slide-right">
        <span class="amenity-tag">Wellness</span>
        <h3>State-of-the-Art Fitness</h3>
        <p>Maintain your routine in our fully equipped fitness center. Featuring the latest cardio and strength-training equipment, our space is designed to help you stay at your peak.</p>
        <ul style="list-style: none; padding: 0; color: #888; font-size: 0.9rem;">
            <li><i class="fa-solid fa-check" style="color: var(--amari-tan); margin-right: 10px;"></i> 24/7 Access for Residents</li>
            <li><i class="fa-solid fa-check" style="color: var(--amari-tan); margin-right: 10px;"></i> Professional Personal Training Available</li>
        </ul>
    </div>
    <div class="amenity-image fade-slide-left" style="background-image: url('assets/images/gym-amenity.jpg');"></div>
</section>

<footer class="site-footer">
    <p>&copy; 2024 Amari Staycation Alabang. Experience the Refined City Life.</p>
</footer>

<script>
    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            } else {
                entry.target.classList.remove('is-visible');
            }
        });
    }, { threshold: 0.2 });

    document.querySelectorAll('.fade-slide-up, .fade-slide-left, .fade-slide-right').forEach(el => {
        scrollObserver.observe(el);
    });
</script>

</body>
</html>