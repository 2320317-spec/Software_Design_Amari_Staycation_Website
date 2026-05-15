<?php include('includes/db-config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Amari Staycation Alabang</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --amari-tan: #b0885a;
            --amari-navy: #042e47;
            --text-dark: #333333;
            --bg-light: #f9f9f9;
        }

        body { margin: 0; font-family: 'Lato', sans-serif; color: var(--text-dark); background-color: white; overflow-x: hidden; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; font-weight: 400; }

        /* Navigation (Keep identical to index.php) */
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 15px 50px; background: white; border-bottom: 1px solid #eee; position: sticky; top: 0; z-index: 100; }
        .nav-links a { margin: 0 15px; text-decoration: none; color: #555; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; }
        .nav-links a:hover { color: var(--amari-tan); }
        .btn-book-nav { background-color: var(--amari-tan); color: white; padding: 12px 25px; text-decoration: none; font-weight: bold; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; }

        /* Hero Section */
        .about-hero {
            height: 60vh;
            background: linear-gradient(rgba(4, 46, 71, 0.4), rgba(4, 46, 71, 0.4)), url('assets/images/about-hero.jpg') center/cover;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }
        .about-hero h1 { font-size: 3.5rem; letter-spacing: 8px; text-transform: uppercase; }

        /* Content Sections */
        .content-container { padding: 100px 10%; }
        
        .story-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
            margin-bottom: 100px;
        }

        .story-text h2 { color: var(--amari-navy); font-size: 2.5rem; margin-bottom: 25px; }
        .story-text p { line-height: 2; color: #666; font-size: 1.1rem; }

        .story-image {
            width: 100%;
            height: 500px;
            background-color: #ddd;
            background-size: cover;
            background-position: center;
            box-shadow: 20px 20px 0px var(--amari-tan); /* Aesthetic offset shadow */
        }

        /* Stats/Philosophy Section */
        .philosophy {
            background-color: var(--amari-navy);
            color: white;
            padding: 80px 10%;
            text-align: center;
        }
        .philosophy h2 { color: var(--amari-tan); font-size: 2rem; margin-bottom: 20px; }
        .philosophy p { max-width: 800px; margin: 0 auto; font-style: italic; font-size: 1.3rem; font-family: 'Playfair Display', serif; }

        /* Footer (Keep identical to index.php) */
        .site-footer { background-color: var(--amari-navy); color: white; padding: 60px 10%; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; }

        /* Animation Classes */
        .fade-slide-up { opacity: 0; transform: translateY(50px); transition: 0.8s all ease-out; }
        .fade-slide-left { opacity: 0; transform: translateX(-50px); transition: 0.8s all ease-out; }
        .fade-slide-right { opacity: 0; transform: translateX(50px); transition: 0.8s all ease-out; }
        .is-visible { opacity: 1; transform: translate(0); }
    </style>
</head>
<body>

<nav class="navbar">
    <div><h2 style="color: var(--amari-navy); margin: 0; font-family: 'Playfair Display', serif;">AMARI ALABANG</h2></div>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="about.php" style="color: var(--amari-tan);">About</a>
        <a href="index.php#rooms">Rooms</a>
        <a href="experience.php">Experience</a>
        <a href="gallery.php">Gallery</a>
    </div>
    <a href="index.php#rooms" class="btn-book-nav">Book Now</a>
</nav>

<section class="about-hero">
    <h1 class="fade-slide-up">Our Story</h1>
</section>

<section class="content-container">
    <div class="story-grid">
        <div class="story-text fade-slide-left">
            <h2>The Vision</h2>
            <p>Amari Alabang was born from a simple desire: to create a sanctuary where the pulse of the city meets the tranquility of a tropical retreat. Inspired by the raw beauty of coastal architecture, we built a space that celebrates natural light, premium materials, and the art of relaxation.</p>
        </div>
        <div class="story-image fade-slide-right" style="background-image: url('assets/images/vision.jpg');"></div>
    </div>

    <div class="story-grid" style="direction: rtl;">
        <div class="story-text fade-slide-right" style="direction: ltr;">
            <h2>Refined Hospitality</h2>
            <p>Every detail in Amari is curated for comfort. From our hand-selected mahogany finishes to our personalized concierge service, we believe that true luxury is not just seen—it is felt. We are committed to providing a secure, serene environment for families and professionals alike.</p>
        </div>
        <div class="story-image fade-slide-left" style="background-image: url('assets/images/hospitality.jpg');"></div>
    </div>
</section>

<section class="philosophy">
    <div class="fade-slide-up">
        <h2>Our Philosophy</h2>
        <p>"To provide an urban escape that nourishes the soul, combines modern elegance with sincere Filipino hospitality, and creates memories that linger long after the stay."</p>
    </div>
</section>

<footer class="site-footer">
    <div class="footer-col">
        <h4 style="color: var(--amari-tan);">Amari Alabang</h4>
        <p>A premier staycation sanctuary.</p>
    </div>
    </footer>

<script>
    // === RE-USE THE ANIMATION ENGINE ===
    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            } else {
                entry.target.classList.remove('is-visible');
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.fade-slide-up, .fade-slide-left, .fade-slide-right').forEach(el => {
        scrollObserver.observe(el);
    });
</script>

</body>
</html>