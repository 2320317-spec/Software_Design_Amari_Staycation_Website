<?php include('includes/db-config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Experience | Amari Staycation Alabang</title>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400;0,6..96,500;1,6..96,400;1,6..96,500&family=Pinyon+Script&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --espresso:   #564328;
            --mahogany:   #502515;
            --gold:       #c2a66e;
            --soft-white: #faf6f6;
            --white:      #ffffff;
            --shadow-sm:  rgba(80,37,21,0.08);
            --shadow-md:  rgba(80,37,21,0.14);
        }

        @media (prefers-reduced-motion: reduce) {
            .reveal, .reveal-left, .reveal-right,
            .nav-hamburger span, .nav-drawer__panel, .nav-drawer__backdrop {
                opacity: 1 !important; transform: none !important; transition: none !important; animation: none !important;
            }
        }

        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0; font-family: 'Poppins', sans-serif; color: var(--espresso);
            background: radial-gradient(120% 80% at 50% 0%, #fdfbf9 0%, var(--soft-white) 55%, #f6f0ee 100%);
            overflow-x: hidden;
        }
        body::before {
            content: ''; position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 0.5;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.045'/%3E%3C/svg%3E");
        }
        .exp-hero { position: relative; z-index: 1; }
        h1, h2, h3 { font-family: 'Bodoni Moda', serif; font-weight: 400; }
        img { display: block; max-width: 100%; }

        /* === NAVBAR === */
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 18px 56px; background: var(--white); border-bottom: 1px solid rgba(86,67,40,0.10); position: sticky; top: 0; z-index: 200; }
        .nav-brand { text-decoration: none; line-height: 1; }
        .nav-brand__name { font-family: 'Bodoni Moda', serif; font-style: italic; font-size: 1.4rem; color: var(--mahogany); margin: 0; line-height: 1.1; }
        .nav-brand__sub { font-size: 0.58rem; color: rgba(86, 67, 40, 0.80); letter-spacing: 3px; text-transform: uppercase; margin: 2px 0 0; }
        .nav-links { display: flex; align-items: center; gap: 28px; }
        .nav-links a { text-decoration: none; color: var(--espresso); font-size: 0.8rem; font-weight: 600; transition: color 0.2s; }
        .nav-links a:hover, .nav-links a.active { color: var(--gold); }
        .btn-book-nav { background: var(--mahogany) !important; color: var(--white) !important; padding: 11px 24px; font-size: 0.76rem !important; font-weight: 700 !important; letter-spacing: 1px; text-transform: uppercase; transition: background 0.2s !important; }
        .btn-book-nav:hover { background: var(--espresso) !important; }

        /* === HAMBURGER === */
        .nav-hamburger { display: none; flex-direction: column; justify-content: center; gap: 5px; width: 36px; height: 36px; background: none; border: none; cursor: pointer; padding: 4px; z-index: 300; }
        .nav-hamburger span { display: block; width: 22px; height: 1.5px; background: var(--mahogany); transition: transform 0.3s cubic-bezier(0.25,0.1,0.25,1), opacity 0.3s; transform-origin: center; }
        .nav-hamburger.is-open span:nth-child(1) { transform: translateY(6.5px) rotate(45deg); }
        .nav-hamburger.is-open span:nth-child(2) { opacity: 0; }
        .nav-hamburger.is-open span:nth-child(3) { transform: translateY(-6.5px) rotate(-45deg); }
        .nav-drawer { display: none; position: fixed; inset: 0; z-index: 250; pointer-events: none; }
        .nav-drawer__backdrop { position: absolute; inset: 0; background: rgba(80,37,21,0.35); opacity: 0; transition: opacity 0.3s; pointer-events: none; }
        .nav-drawer__panel { position: absolute; top: 0; right: 0; bottom: 0; width: min(280px, 80vw); background: var(--white); padding: 88px 36px 48px; display: flex; flex-direction: column; gap: 4px; transform: translateX(100%); transition: transform 0.35s cubic-bezier(0.25,0.1,0.25,1); pointer-events: none; }
        .nav-drawer.is-open { pointer-events: auto; }
        .nav-drawer.is-open .nav-drawer__backdrop { opacity: 1; pointer-events: auto; }
        .nav-drawer.is-open .nav-drawer__panel { transform: translateX(0); pointer-events: auto; }
        .nav-drawer__panel a { font-family: 'Bodoni Moda', serif; font-style: italic; font-size: 1.5rem; color: var(--mahogany); text-decoration: none; padding: 10px 0; border-bottom: 1px solid rgba(86,67,40,0.08); transition: color 0.2s; }
        .nav-drawer__panel a:hover { color: var(--gold); }
        .nav-drawer__panel .drawer-book { margin-top: 28px; font-family: 'Poppins', sans-serif; font-style: normal; font-size: 0.78rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; color: var(--white); background: var(--mahogany); padding: 13px 20px; text-align: center; transition: background 0.2s; border: none; }
        .nav-drawer__panel .drawer-book:hover { background: var(--espresso); }

        /* === EXPERIENCE HERO === */
        .exp-hero {
            height: 70vh; min-height: 440px;
            background: linear-gradient(rgba(80,37,21,0.35), rgba(80,37,21,0.35)),
                        url('assets/images/pool.jpg') center/cover;
            background-color: var(--espresso);
            display: flex; align-items: center; justify-content: center;
            color: var(--white); text-align: center;
        }
        .exp-hero h1 { font-size: clamp(3rem, 7vw, 5rem); font-style: italic; letter-spacing: 0.01em; line-height: 1; margin: 0; text-wrap: balance; }

        /* === SECTION SEPARATION SHADOW === */
        /* Soft top-edge shadow so each section boundary reads like the navbar's. */
        .exp-intro,
        .amenity-row,
        .site-footer {
            position: relative;
            z-index: 1;
            box-shadow: 0 -9px 26px -7px rgba(80,37,21,0.20);
        }

        /* === INTRO === */
        .exp-intro {
            text-align: center; padding: 96px 20%;
            background: var(--soft-white);
            overflow: hidden;
        }
        .exp-intro::before {
            content: 'Amari';
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -52%);
            font-family: 'Bodoni Moda', serif; font-style: italic;
            font-size: clamp(6rem, 16vw, 15rem); line-height: 1;
            color: rgba(80,37,21,0.05);
            white-space: nowrap; max-width: 100vw;
            pointer-events: none; z-index: 0; user-select: none;
        }
        .exp-intro > * { position: relative; z-index: 1; }
        .exp-intro h2 { font-size: clamp(1.9rem, 3.5vw, 2.8rem); font-style: italic; color: var(--mahogany); margin: 0 0 20px; text-wrap: balance; line-height: 1.15; }
        .exp-intro p { line-height: 1.9; color: var(--espresso); font-size: 1rem; font-weight: 300; max-width: 62ch; margin: 0 auto; text-wrap: pretty; }

        /* === AMENITY ROWS === */
        .amenity-row { display: flex; flex-wrap: wrap; align-items: stretch; min-height: 480px; }
        .amenity-text { flex: 1; min-width: 320px; padding: 80px 8%; display: flex; flex-direction: column; justify-content: center; background: var(--white); }
        .amenity-image { flex: 1; min-width: 320px; background-size: cover; background-position: center; min-height: 380px; }
        .amenity-row:nth-child(even) .amenity-text { background: var(--soft-white); }

        .amenity-tag {
            display: inline-block;
            color: var(--gold);
            font-size: 0.78rem;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            margin-bottom: 12px;
        }
        .amenity-text h3 { font-size: clamp(1.6rem, 2.5vw, 2.3rem); font-style: italic; color: var(--mahogany); margin: 0 0 16px; text-wrap: balance; line-height: 1.1; }
        .amenity-text p { line-height: 1.85; color: var(--espresso); font-weight: 300; margin: 0 0 24px; max-width: 52ch; text-wrap: pretty; }

        .amenity-list { list-style: none; padding: 0; margin: 0; }
        .amenity-list li { display: flex; align-items: center; gap: 12px; margin-bottom: 10px; font-size: 0.875rem; color: var(--espresso); font-weight: 300; }
        .amenity-list li i { color: var(--gold); width: 16px; text-align: center; flex-shrink: 0; }

        /* === FOOTER === */
        .site-footer { background: var(--espresso); color: rgba(255,255,255,0.72); padding: 56px 5% 40px; }
        .footer-inner { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; }
        .footer-col h5 { font-family: 'Poppins', sans-serif; font-size: 0.72rem; font-weight: 700; letter-spacing: 2.5px; text-transform: uppercase; color: var(--gold); margin: 0 0 18px; }
        .footer-col a, .footer-col p { color: rgba(255,255,255,0.58); font-size: 0.875rem; font-weight: 300; line-height: 1.9; text-decoration: none; display: block; margin-bottom: 4px; transition: color 0.2s; }
        .footer-col a:hover { color: var(--white); }
        .footer-bottom { padding: 28px 0 0; margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.10); font-size: 0.78rem; color: rgba(255,255,255,0.30); }

        /* === REVEALS === */
        .js-ready .reveal      { opacity: 0; transform: translateY(36px); transition: opacity 0.7s cubic-bezier(0.25,0.1,0.25,1), transform 0.7s cubic-bezier(0.25,0.1,0.25,1); }
        .js-ready .reveal-left  { opacity: 0; transform: translateX(-44px); transition: opacity 0.7s cubic-bezier(0.25,0.1,0.25,1), transform 0.7s cubic-bezier(0.25,0.1,0.25,1); }
        .js-ready .reveal-right { opacity: 0; transform: translateX(44px); transition: opacity 0.7s cubic-bezier(0.25,0.1,0.25,1), transform 0.7s cubic-bezier(0.25,0.1,0.25,1); }
        .reveal.in-view, .reveal-left.in-view, .reveal-right.in-view { opacity: 1 !important; transform: translate(0) !important; }

        @media (max-width: 640px) {
            .navbar { padding: 14px 20px; }
            .nav-links { display: none; }
            .nav-hamburger { display: flex; }
            .nav-drawer { display: block; }
            .exp-intro { padding: 72px 6%; }
        }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="index.php" class="nav-brand">
        <img src="assets/images/logo.png" alt="Amari Staycation Alabang" class="nav-logo" style="height:36px;width:auto;display:block;">
    </a>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="index.php#rooms">Rooms</a>
        <a href="experience.php" class="active">Experience</a>
        <a href="gallery.php">Gallery</a>
        <a href="booking-form.php" class="btn-book-nav">Book a stay</a>
    </div>
    <button class="nav-hamburger" id="navHamburger" aria-label="Open navigation" aria-expanded="false">
        <span></span><span></span><span></span>
    </button>
</nav>

<div class="nav-drawer" id="navDrawer" aria-hidden="true">
    <div class="nav-drawer__backdrop" id="navBackdrop"></div>
    <div class="nav-drawer__panel" role="dialog" aria-label="Navigation">
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="index.php#rooms">Rooms</a>
        <a href="experience.php" class="active">Experience</a>
        <a href="gallery.php">Gallery</a>
        <a href="booking-form.php" class="drawer-book">Book a stay</a>
    </div>
</div>

<section class="exp-hero">
    <h1 class="reveal">The Lifestyle</h1>
</section>

<section class="exp-intro reveal">
    <h2>Beyond just a stay</h2>
    <p>At Amari Alabang, every amenity is designed to make your time here feel like yours. No memberships, no queues. Just the spaces and services that come with being a guest.</p>
</section>

<section class="amenity-row">
    <div class="amenity-text reveal-left">
        <span class="amenity-tag">Recreation</span>
        <h3>The Infinity Edge</h3>
        <p>Our signature pool is open from early morning to late evening, with panoramic views of the Alabang skyline. Towels provided for all guests.</p>
        <ul class="amenity-list">
            <li><i class="fa-solid fa-check"></i> Open daily: 6:00 AM to 10:00 PM</li>
            <li><i class="fa-solid fa-check"></i> Complimentary towels for guests</li>
        </ul>
    </div>
    <div class="amenity-image reveal-right" style="background-image: url('assets/images/pool.jpg');"></div>
</section>

<section class="amenity-row">
    <div class="amenity-image reveal-left" style="background-image: url('assets/images/vision1.jpg');"></div>
    <div class="amenity-text reveal-right">
        <span class="amenity-tag">Wellness</span>
        <h3>Fitness Center</h3>
        <p>A fully equipped gym available to all guests around the clock. Cardio and strength equipment, with personal training available on request.</p>
        <ul class="amenity-list">
            <li><i class="fa-solid fa-check"></i> 24/7 access for guests</li>
            <li><i class="fa-solid fa-check"></i> Personal training available on request</li>
        </ul>
    </div>
</section>

<footer class="site-footer">
    <div class="footer-inner">
        <div class="footer-col reveal">
            <h5>Explore</h5>
            <a href="about.php">About</a>
            <a href="experience.php">Experience</a>
            <a href="gallery.php">Gallery</a>
            <a href="index.php#rooms">Accommodations</a>
        </div>
        <div class="footer-col reveal">
            <h5>Spaces</h5>
            <a href="index.php#rooms">Amari Suite</a>
            <a href="index.php#rooms">Mariah Suite</a>
            <a href="index.php#rooms">Ara Suite</a>
        </div>
        <div class="footer-col reveal">
            <h5>Contact</h5>
            <p>Filinvest City, Alabang<br>Philippines</p>
            <p>Globe: (+63) 917-123-4567</p>
        </div>
    </div>
    <div class="footer-bottom">&copy; 2026 Amari Staycation Alabang. All rights reserved.</div>
</footer>

<script>
    document.documentElement.classList.add('js-ready');
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in-view'); obs.unobserve(e.target); } });
    }, { threshold: 0.10 });
    document.querySelectorAll('.reveal, .reveal-left, .reveal-right').forEach(el => obs.observe(el));

    const hamburger = document.getElementById('navHamburger');
    const drawer    = document.getElementById('navDrawer');
    const backdrop  = document.getElementById('navBackdrop');
    function openDrawer() {
        hamburger.classList.add('is-open'); hamburger.setAttribute('aria-expanded', 'true');
        drawer.classList.add('is-open'); drawer.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }
    function closeDrawer() {
        hamburger.classList.remove('is-open'); hamburger.setAttribute('aria-expanded', 'false');
        drawer.classList.remove('is-open'); drawer.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }
    hamburger.addEventListener('click', () => drawer.classList.contains('is-open') ? closeDrawer() : openDrawer());
    backdrop.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });
    drawer.querySelectorAll('a').forEach(a => a.addEventListener('click', closeDrawer));
</script>
</body>
</html>
