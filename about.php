<?php include('includes/db-config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | Amari Staycation Alabang</title>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400;0,6..96,500;1,6..96,400;1,6..96,500&family=Pinyon+Script&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
                opacity: 1 !important; transform: none !important;
                transition: none !important; animation: none !important;
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
        .about-hero { position: relative; z-index: 1; }
        h1, h2, h3, h4 { font-family: 'Bodoni Moda', serif; font-weight: 400; }
        img { display: block; max-width: 100%; }

        /* === NAVBAR === */
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 18px 56px; background: var(--white); border-bottom: 1px solid rgba(86,67,40,0.10); position: sticky; top: 0; z-index: 200; }
        .nav-brand { text-decoration: none; line-height: 1; }
        .nav-brand__name { font-family: 'Bodoni Moda', serif; font-style: italic; font-size: 1.4rem; color: var(--mahogany); margin: 0; line-height: 1.1; }
        .nav-brand__sub { font-size: 0.58rem; color: rgba(86, 67, 40, 0.80); letter-spacing: 3px; text-transform: uppercase; margin: 2px 0 0; }
        .nav-links { display: flex; align-items: center; gap: 28px; }
        .nav-links a { text-decoration: none; color: var(--espresso); font-size: 0.8rem; font-weight: 600; letter-spacing: 0.3px; transition: color 0.2s; }
        .nav-links a:hover, .nav-links a.active { color: var(--gold); }
        .btn-book-nav { background: var(--mahogany) !important; color: var(--white) !important; padding: 11px 24px; font-size: 0.76rem !important; font-weight: 700 !important; letter-spacing: 1px; text-transform: uppercase; transition: background 0.2s !important; }
        .btn-book-nav:hover { background: var(--espresso) !important; }

        /* === HAMBURGER === */
        .nav-hamburger { display: none; flex-direction: column; justify-content: center; gap: 5px; width: 36px; height: 36px; background: none; border: none; cursor: pointer; padding: 4px; z-index: 300; }
        .nav-hamburger span { display: block; width: 22px; height: 1.5px; background: var(--mahogany); transition: transform 0.3s cubic-bezier(0.25,0.1,0.25,1), opacity 0.3s; transform-origin: center; }
        .nav-hamburger.is-open span:nth-child(1) { transform: translateY(6.5px) rotate(45deg); }
        .nav-hamburger.is-open span:nth-child(2) { opacity: 0; }
        .nav-hamburger.is-open span:nth-child(3) { transform: translateY(-6.5px) rotate(-45deg); }

        /* === NAV DRAWER === */
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

        /* === ABOUT HERO === */
        .about-hero {
            height: 60vh; min-height: 400px;
            background: linear-gradient(rgba(80,37,21,0.50), rgba(80,37,21,0.50)),
                        url('assets/images/about-hero.jpg') center/cover;
            background-color: var(--espresso);
            display: flex; align-items: center; justify-content: center;
            color: var(--white); text-align: center;
        }
        .about-hero h1 {
            font-size: clamp(3rem, 7vw, 5rem);
            font-style: italic;
            letter-spacing: 0.01em;
            margin: 0;
            text-wrap: balance;
            line-height: 1;
        }

        /* === SECTION SEPARATION SHADOW === */
        /* Soft top-edge shadow so each section boundary reads like the navbar's. */
        .content-container,
        .philosophy,
        .site-footer {
            z-index: 1;
            box-shadow: 0 -9px 26px -7px rgba(80,37,21,0.20);
        }

        /* === CONTENT === */
        .content-container {
            padding: 120px 10%;
            background-color: var(--soft-white);
            background-image: linear-gradient(to right, rgba(194,166,110,0.07) 1px, transparent 1px);
            background-size: 20% 100%;
            position: relative;
        }

        .story-grid:nth-child(1) {
            display: grid;
            grid-template-columns: 1fr 520px;
            gap: 80px; align-items: center; margin-bottom: 140px;
        }
        .story-grid:nth-child(2) {
            display: grid;
            grid-template-columns: 520px 1fr;
            gap: 80px; align-items: center; margin-bottom: 60px;
        }

        .story-text {
            background: var(--white);
            padding: 60px 52px;
            position: relative; z-index: 5;
            box-shadow:
                0 1px 2px rgba(80,37,21,0.06),
                0 20px 48px -14px rgba(80,37,21,0.22);
        }
        .story-text::before {
            content: ''; display: block;
            width: 40px; height: 2px;
            background: var(--gold); margin-bottom: 28px;
        }
        .story-text h2 {
            color: var(--mahogany);
            font-size: clamp(1.9rem, 3vw, 3.2rem);
            font-style: italic;
            margin: 0 0 20px;
            text-wrap: balance;
            line-height: 1.1;
        }
        .story-text p {
            line-height: 2; color: var(--espresso);
            font-size: 0.975rem; font-weight: 300; margin: 0;
            text-wrap: pretty;
        }
        .story-text::after {
            content: ''; position: absolute; width: 100%; height: 100%;
            border: 1px solid rgba(194,166,110,0.30);
            z-index: -1;
            transition: transform 0.55s cubic-bezier(0.165,0.84,0.44,1);
        }
        .story-grid:nth-child(1) .story-text::after { top: -18px; left: -18px; }
        .story-grid:nth-child(2) .story-text::after { bottom: -18px; right: -18px; }
        .story-text:hover::after { transform: scale(1.025); }

        .story-image {
            position: relative;
            width: 520px; height: 520px; flex-shrink: 0;
            background-size: cover; background-position: center;
            background-color: var(--soft-white);
            z-index: 2;
            box-shadow: 0 24px 56px -12px rgba(80,37,21,0.22);
            transition: transform 0.55s cubic-bezier(0.165,0.84,0.44,1);
        }
        .story-image::after {
            content: ''; position: absolute; width: 100%; height: 100%;
            border: 2px solid var(--gold); z-index: -1;
            transition: transform 0.55s cubic-bezier(0.165,0.84,0.44,1);
        }
        .story-grid:nth-child(1) .story-image::after { bottom: -28px; right: -28px; }
        .story-grid:nth-child(2) .story-image::after { top: -28px; left: -28px; }
        .story-image:hover { transform: translateY(-8px); }
        .story-grid:nth-child(1) .story-image:hover::after { transform: translate(12px, 12px); }
        .story-grid:nth-child(2) .story-image:hover::after { transform: translate(-12px, -12px); }

        /* === PHILOSOPHY === */
        .philosophy {
            background: var(--mahogany);
            color: var(--white);
            padding: 108px 10%;
            text-align: center;
            overflow: hidden;
            position: relative;
            z-index: 1;
        }
        .philosophy::before {
            content: 'Amari';
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -52%);
            font-family: 'Bodoni Moda', serif; font-style: italic;
            font-size: clamp(6rem, 16vw, 15rem); line-height: 1;
            color: rgba(255,255,255,0.05);
            white-space: nowrap; max-width: 100vw;
            pointer-events: none; z-index: 0; user-select: none;
        }
        .philosophy > * { position: relative; z-index: 1; }
        .philosophy-intro {
            font-family: 'Pinyon Script', cursive;
            font-size: clamp(1.5rem, 2.5vw, 2.1rem);
            color: var(--gold);
            margin: 0 0 4px;
            line-height: 1.3;
            display: block;
        }
        .philosophy blockquote {
            font-family: 'Bodoni Moda', serif;
            font-style: italic;
            font-size: clamp(1.2rem, 2.2vw, 1.65rem);
            line-height: 1.85;
            color: rgba(255,255,255,0.88);
            max-width: 780px;
            margin: 0 auto;
            text-wrap: balance;
            padding: 0;
        }

        /* === FOOTER === */
        .site-footer {
            background: var(--espresso); color: rgba(255,255,255,0.72);
            padding: 56px 5% 40px;
            display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px;
        }
        .footer-col h5 { font-family: 'Poppins', sans-serif; font-size: 0.72rem; font-weight: 700; letter-spacing: 2.5px; text-transform: uppercase; color: var(--gold); margin: 0 0 18px; }
        .footer-col a, .footer-col p { color: rgba(255,255,255,0.58); font-size: 0.875rem; font-weight: 300; line-height: 1.9; text-decoration: none; display: block; margin-bottom: 4px; transition: color 0.2s; }
        .footer-col a:hover { color: var(--white); }
        .footer-bottom { grid-column: 1/-1; padding: 28px 0 0; border-top: 1px solid rgba(255,255,255,0.10); font-size: 0.78rem; color: rgba(255,255,255,0.30); }

        /* === SCROLL REVEALS === */
        .js-ready .reveal      { opacity: 0; transform: translateY(36px); transition: opacity 0.7s cubic-bezier(0.25,0.1,0.25,1), transform 0.7s cubic-bezier(0.25,0.1,0.25,1); }
        .js-ready .reveal-left  { opacity: 0; transform: translateX(-44px); transition: opacity 0.7s cubic-bezier(0.25,0.1,0.25,1), transform 0.7s cubic-bezier(0.25,0.1,0.25,1); }
        .js-ready .reveal-right { opacity: 0; transform: translateX(44px); transition: opacity 0.7s cubic-bezier(0.25,0.1,0.25,1), transform 0.7s cubic-bezier(0.25,0.1,0.25,1); }
        .reveal.in-view, .reveal-left.in-view, .reveal-right.in-view { opacity: 1 !important; transform: translate(0) !important; }

        /* === RESPONSIVE === */
        @media (max-width: 1100px) {
            .story-grid:nth-child(1), .story-grid:nth-child(2) { grid-template-columns: 1fr; gap: 48px; }
            .story-image { width: 100%; height: 380px; }
            .story-grid:nth-child(2) { direction: initial; }
        }
        @media (max-width: 640px) {
            .navbar { padding: 14px 20px; }
            .nav-links { display: none; }
            .nav-hamburger { display: flex; }
            .nav-drawer { display: block; }
            .content-container { padding: 72px 6%; }
            .story-text { padding: 40px 32px; }
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
        <a href="about.php" class="active">About</a>
        <a href="index.php#rooms">Rooms</a>
        <a href="experience.php">Experience</a>
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
        <a href="about.php" class="active">About</a>
        <a href="index.php#rooms">Rooms</a>
        <a href="experience.php">Experience</a>
        <a href="gallery.php">Gallery</a>
        <a href="booking-form.php" class="drawer-book">Book a stay</a>
    </div>
</div>

<section class="about-hero">
    <h1 class="reveal">Our Story</h1>
</section>

<section class="content-container">
    <div class="story-grid">
        <div class="story-text reveal-left">
            <h2>The Vision</h2>
            <p>Amari Alabang was built around a simple idea: a place in the city that feels like it's outside of it. Inspired by the interplay of natural light, warm materials, and quiet design, each space at Amari is an invitation to slow down and settle in.</p>
        </div>
        <div class="story-image reveal-right" style="background-image: url('assets/images/vision.jpg');"></div>
    </div>

    <div class="story-grid">
        <div class="story-image reveal-left" style="background-image: url('assets/images/hospitality.jpg');"></div>
        <div class="story-text reveal-right">
            <h2>Refined Hospitality</h2>
            <p>Every detail at Amari is considered in advance of your arrival. From hand-selected finishes to personalized service, the goal is a stay that feels cared for without feeling managed. Comfortable for families, professionals, and those who just need a proper rest.</p>
        </div>
    </div>
</section>

<section class="philosophy">
    <div class="reveal">
        <span class="philosophy-intro">our philosophy</span>
        <blockquote>
            &ldquo;To provide an urban escape that nourishes the soul, combines considered design with sincere Filipino hospitality, and creates memories that linger long after the stay.&rdquo;
        </blockquote>
    </div>
</section>

<footer class="site-footer">
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
    <div class="footer-bottom">&copy; 2026 Amari Staycation Alabang. All rights reserved.</div>
</footer>

<script>
    document.documentElement.classList.add('js-ready');
    const obs = new IntersectionObserver((entries) => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('in-view'); obs.unobserve(e.target); } });
    }, { threshold: 0.10 });
    document.querySelectorAll('.reveal, .reveal-left, .reveal-right').forEach(el => obs.observe(el));

    // === HAMBURGER MENU ===
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
