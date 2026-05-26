
<?php
// Connect to the database to fetch the featured units
include('includes/db-config.php');

// Fetch 3 available units for the Featured Section
$featured_query = $conn->query("SELECT * FROM units WHERE status = 'available' ORDER BY id DESC LIMIT 3");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amari Staycation | Luxury in Alabang</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    
    <style>
        /* --- CALA LAIYA INSPIRED STYLES --- */
        :root {
            --amari-tan: #b0885a;
            --amari-navy: #042e47;
            --text-dark: #333333;
            --bg-light: #f9f9f9;
        }

        body { margin: 0; font-family: 'Lato', sans-serif; color: var(--text-dark); overflow-x: hidden; }
        h1, h2, h3, h4 { font-family: 'Playfair Display', serif; font-weight: 400; }

        /* Navigation */
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 15px 50px; background: white; border-bottom: 1px solid #eee; position: sticky; top: 0; z-index: 100; }
        .nav-links a { margin: 0 15px; text-decoration: none; color: #555; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; }
        .nav-links a:hover { color: var(--amari-tan); }
        .btn-book-nav { background-color: var(--amari-tan); color: white; padding: 12px 25px; text-decoration: none; font-weight: bold; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; transition: 0.3s; }
        .btn-book-nav:hover { background-color: var(--amari-navy); }

        /* --- HERO CAROUSEL (FIXED FOR ZOOM CONSISTENCY) --- */
        .hero { 
            position: relative; 
            height: 80vh; 
            min-height: 600px; /* FIXED: Prevents collapse on zoom out */
            max-height: 1000px; /* FIXED: Prevents stretching on ultra-wide zoom */
            overflow: hidden; 
            background: #000; 
        }

        /* The Lighting System: Slower, softer flash */
        .flash-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #ffffff;
            opacity: 0; 
            z-index: 15;
            pointer-events: none; 
            transition: opacity 0.5s ease-out; 
        }

        .flash-overlay.is-flashing {
            opacity: 0.08; 
        }
        
        .slider-track {
            display: flex;
            width: 300%; 
            height: 100%;
            will-change: transform; /* FIXED: Optimizes GPU for resizing/zooming */
            transition: transform 1.4s ease-in-out; 
        }

        .slide { 
            width: 33.333%; 
            height: 100%; 
            flex-shrink: 0; /* FIXED: Prevents slide width warping on zoom */
        }
        .slide img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            object-position: center; /* FIXED: Keeps focus centered during zoom crops */
        }

        .hero-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            z-index: 10; display: flex; align-items: center; justify-content: center;
            background: rgba(0,0,0,0.3); pointer-events: none; 
        }

        .hero-text { 
            color: white; 
            font-size: 4rem; 
            letter-spacing: 5px; 
            text-transform: uppercase; 
            text-shadow: 2px 2px 15px rgba(0,0,0,0.5); 
        }

        /* The Typography Engine: Slower float */
        .animate-title {
            animation: textPopUp 1.2s ease-out forwards;
        }

        @keyframes textPopUp {
            0% { opacity: 0; transform: translateY(60px); } 
            100% { opacity: 1; transform: translateY(0); }  
        }
        
        .hero-arrows { 
            position: absolute; width: 100%; display: flex; justify-content: space-between; 
            padding: 0 50px; box-sizing: border-box; pointer-events: auto; 
        }
        .hero-arrows span { cursor: pointer; user-select: none; transition: 0.3s; opacity: 0.7; color: white; font-size: 4rem; font-weight: 300; }
        .hero-arrows span:hover { opacity: 1; transform: scale(1.1); color: var(--amari-tan); }

        /* Welcome Section */
        .welcome-section { text-align: center; padding: 80px 20px; max-width: 800px; margin: 0 auto; }
        .welcome-section h2 { font-size: 2.5rem; color: var(--amari-navy); margin-bottom: 30px; text-transform: uppercase; }
        .welcome-section p { line-height: 1.8; color: #666; margin-bottom: 20px; }

        /* Split Layout */
        .split-layout { display: flex; flex-wrap: wrap; }
        .split-text { flex: 1; min-width: 300px; background-color: var(--amari-tan); color: white; padding: 80px 10%; display: flex; flex-direction: column; justify-content: center; }
        .split-text h2 { font-size: 2.2rem; margin-bottom: 20px; }
        .find-out-link { color: white; text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem; margin-bottom: 15px; text-decoration: none; font-weight: bold; display: inline-block; transition: 0.3s; }
        .find-out-link:hover { color: var(--amari-navy); }
        .split-image { flex: 1; min-width: 300px; min-height: 400px; background: url('assets/images/pool.jpg') center/cover; background-color: #ccc; }

        /* Dynamic Rooms */
        .rooms-section { padding: 80px 5%; background: var(--bg-light); text-align: center; }
        .grid-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px; margin-top: 40px; }
        .room-card { background: white; text-align: left; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: transform 0.3s, box-shadow 0.3s; }
        .room-card:hover { transform: translateY(-10px) !important; box-shadow: 0 15px 30px rgba(0,0,0,0.1); }
        .room-card img { width: 100%; height: 250px; object-fit: cover; }
        .room-details { padding: 25px; }

        /* Guest Reviews */
        .reviews-section { padding: 80px 5%; text-align: center; }
        .reviews-section h2 { font-size: 2.5rem; color: var(--amari-navy); }
        .review-card { padding: 20px; }
        .avatar { width: 100px; height: 100px; border-radius: 50%; background: #ddd; margin: 0 auto 20px; object-fit: cover; }
        .review-card p { font-family: 'Playfair Display', serif; font-style: italic; color: #555; line-height: 1.6; }

        /* Footer */
        .site-footer { background-color: var(--amari-navy); color: white; padding: 60px 5%; display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; }
        .footer-col h4 { color: var(--amari-tan); font-size: 1.2rem; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 2px; }
        .footer-col p, .footer-col a { color: #ccc; font-size: 0.9rem; line-height: 1.8; text-decoration: none; display: block; margin-bottom: 10px; transition: 0.3s; }
        .footer-col a:hover { color: white; text-decoration: underline; }

        /* --- CONTINUOUS SCROLL ANIMATIONS --- */
        .fade-slide-up { opacity: 0; transform: translateY(60px); transition: opacity 0.8s ease-out, transform 0.8s ease-out; }
        .fade-slide-left { opacity: 0; transform: translateX(-60px); transition: opacity 0.8s ease-out, transform 0.8s ease-out; }
        .fade-slide-right { opacity: 0; transform: translateX(60px); transition: opacity 0.8s ease-out, transform 0.8s ease-out; }
        .is-visible { opacity: 1; transform: translate(0); }
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
        <a href="#rooms">Rooms</a>
        <a href="experience.php">Experience</a>
        <a href="gallery.php">Gallery</a>
    </div>
    <a href="#rooms" style="background-color: #b0885a; color: white; padding: 12px 25px; text-decoration: none; font-weight: bold; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; display: inline-block;">BOOK NOW</a>
</nav>

<header class="hero">
    <div class="flash-overlay" id="flashOverlay"></div>

    <div class="slider-track" id="sliderTrack">
        <div class="slide"><img src="assets/images/amari/hero1.jpg" alt="Amari"></div>
        <div class="slide"><img src="assets/images/mariah/hero1.jpg" alt="Mariah"></div>
        <div class="slide"><img src="assets/images/ara/hero1.jpg" alt="Ara"></div>
    </div>

    <div class="hero-overlay">
        <div class="hero-arrows">
            <span onclick="prevSlide()">&lsaquo;</span>
            <span onclick="nextSlide()">&rsaquo;</span>
        </div>
        <h1 class="hero-text animate-title" id="heroTitle">Amari</h1>
    </div>
</header>

<section class="welcome-section fade-slide-up">
    <h2>Welcome Home</h2>
    <p>Amari Alabang is a premium staycation sanctuary designed to accommodate guests seeking a retreat from the city.</p>
    <p>When in Amari, we greet all our guests with a sincere smile and unparalleled service. This courteous greeting is not just done as a welcome; it is our commitment to providing comfort, security, and a truly unforgettable luxury experience.</p>
</section>

<section class="split-layout">
    <div class="split-text fade-slide-left">
        <a href="about.php" class="find-out-link">Find Out More &rarr;</a>
        <h2>Indulge in a Cozy Accommodation</h2>
        <p style="line-height: 1.8;">Nestled in the heart of the city, Amari Alabang is an urban sanctuary that invites you to indulge in cozy accommodations situated in a beautifully designed modern environment left unspoiled for you to discover.</p>
    </div>
    <div class="split-image fade-slide-right"></div>
</section>

<section id="rooms" class="rooms-section">
    <h2 class="fade-slide-up" style="font-size: 2.5rem; color: var(--amari-navy); margin-bottom: 10px;">Our Accommodations</h2>
    <p class="fade-slide-up" style="color: #666;">Choose from our exclusive collection of luxury suites.</p>
    
    <div class="grid-3">
        <?php while($unit = $featured_query->fetch_assoc()): ?>
            <div class="room-card fade-slide-up">
                <?php if(!empty($unit['image_path'])): ?>
                    <img src="assets/images/<?php echo $unit['image_path']; ?>" alt="Room Image">
                <?php else: ?>
                    <div style="height: 250px; background: #eee; display:flex; align-items:center; justify-content:center;">NO IMAGE</div>
                <?php endif; ?>
                
                <div class="room-details">
                    <h3 style="color: var(--amari-navy); margin-top: 0; font-size: 1.5rem;"><?php echo $unit['title']; ?></h3>
                    <p style="font-size: 0.9rem; color: #666; line-height: 1.6; margin-bottom: 25px;">
                        <?php echo substr($unit['description'], 0, 80) . '...'; ?>
                    </p>
                    <a href="room-details.php?id=<?php echo $unit['id']; ?>" style="color: var(--amari-tan); text-decoration: none; font-weight: bold; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px;">Discover More &rarr;</a>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</section>

<section class="reviews-section">
    <p class="fade-slide-up" style="text-transform: uppercase; letter-spacing: 2px; color: #999; font-size: 0.8rem;">Your Opinion Matters</p>
    <h2 class="fade-slide-up">Guest Reviews</h2>
    <div class="grid-3" style="margin-top: 50px;">
        <div class="review-card fade-slide-up">
            <div class="avatar"></div>
            <p>"The resort was so beautiful and well maintained... Great place for relaxation."</p>
            <h4 style="color: var(--amari-navy); text-transform: uppercase; margin-top: 20px;">Jeremiah P.</h4>
        </div>
        <div class="review-card fade-slide-up">
            <div class="avatar"></div>
            <p>"Beautiful space, very serene, & amazing service. The rooms were clean, spacious."</p>
            <h4 style="color: var(--amari-navy); text-transform: uppercase; margin-top: 20px;">Tep S.</h4>
        </div>
        <div class="review-card fade-slide-up">
            <div class="avatar"></div>
            <p>"Highly recommended. The location in Alabang is perfect. Will definitely come back."</p>
            <h4 style="color: var(--amari-navy); text-transform: uppercase; margin-top: 20px;">Elena R.</h4>
        </div>
    </div>
</section>

<footer class="site-footer">
    <div class="footer-col fade-slide-up">
        <h4>About Amari</h4>
        <p>Nestled in Alabang, Amari is an urban sanctuary.</p>
    </div>
    <div class="footer-col fade-slide-up">
        <h4>Discover</h4>
        <a href="about.php">About Us</a>
        <a href="#">FAQs</a>
        <a href="#">Guest Reviews</a>
    </div>
    <div class="footer-col fade-slide-up">
        <h4>Experience</h4>
        <a href="#">Infinity Pool</a>
        <a href="#">Spa & Wellness</a>
        <a href="#">Corporate Events</a>
    </div>
    <div class="footer-col fade-slide-up">
        <h4>Get In Touch</h4>
        <p>Filinvest City, Alabang<br>Philippines</p>
        <p>Globe: (+63) 917-123-4567</p>
    </div>
</footer>

<script>
    // === SLIDER LOGIC ===
    const titles = ["Amari", "Mariah", "Ara"];
    let currentSlide = 0;
    
    const sliderTrack = document.getElementById('sliderTrack');
    const heroTitle = document.getElementById('heroTitle');
    const flashOverlay = document.getElementById('flashOverlay');

    function updateCarousel() {
        const offset = currentSlide * 33.3333;
        sliderTrack.style.transform = `translateX(-${offset}%)`;
        
        flashOverlay.classList.add('is-flashing');
        setTimeout(() => {
            flashOverlay.classList.remove('is-flashing'); 
        }, 400);

        heroTitle.classList.remove('animate-title');
        void heroTitle.offsetWidth; 
        
        heroTitle.innerText = titles[currentSlide];
        heroTitle.classList.add('animate-title'); 
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % titles.length;
        updateCarousel();
    }

    function prevSlide() {
        currentSlide = (currentSlide - 1 + titles.length) % titles.length;
        updateCarousel();
    }
</script>

<script>
    // === CONTINUOUS TWO-WAY SCROLL ANIMATION ENGINE ===
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.15 
    };

    const scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
            } else {
                entry.target.classList.remove('is-visible');
            }
        });
    }, observerOptions);

    const elementsToAnimate = document.querySelectorAll('.fade-slide-left, .fade-slide-right, .fade-slide-up');
    elementsToAnimate.forEach(el => {
        scrollObserver.observe(el);
    });
</script>

</body>
</html>