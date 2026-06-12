<?php include('includes/db-config.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery | Amari Staycation Alabang</title>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400;0,6..96,500;1,6..96,400;1,6..96,500&family=Pinyon+Script&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @media (prefers-reduced-motion: reduce) {
            .spread-main img, .spread-side img, .suite-single img, .common-item img,
            .spread-main::after, .spread-side::after, .suite-single::after, .common-item::after,
            #lightbox-img, .nav-hamburger span, .nav-drawer__panel, .nav-drawer__backdrop { transition: none !important; }
        }

        :root {
            --espresso:   #564328;
            --mahogany:   #502515;
            --gold:       #c2a66e;
            --soft-white: #faf6f6;
            --white:      #ffffff;
            --shadow-sm:  rgba(80,37,21,0.08);
            --shadow-md:  rgba(80,37,21,0.14);
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
        h1, h2, h3, h4 { font-family: 'Bodoni Moda', serif; font-weight: 400; }
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

        /* === GALLERY HEADER === */
        .gallery-header {
            text-align: center;
            padding: 96px 10% 72px;
            background: var(--soft-white);
            position: relative;
            z-index: 1;
            overflow: hidden;
        }
        .gallery-header::before {
            content: 'Amari';
            position: absolute; top: 50%; left: 50%;
            transform: translate(-50%, -52%);
            font-family: 'Bodoni Moda', serif; font-style: italic;
            font-size: clamp(6rem, 16vw, 15rem); line-height: 1;
            color: rgba(80,37,21,0.05);
            white-space: nowrap; max-width: 100vw;
            pointer-events: none; z-index: 0; user-select: none;
        }
        .gallery-header > * { position: relative; z-index: 1; }
        .gallery-script {
            font-family: 'Pinyon Script', cursive;
            font-size: clamp(1.5rem, 2.5vw, 2.1rem);
            color: var(--gold);
            display: block;
            margin: 0 0 6px;
            line-height: 1.3;
        }
        .gallery-header h1 {
            font-size: clamp(2.8rem, 5vw, 4.2rem);
            font-style: italic;
            color: var(--mahogany);
            margin: 0 0 16px;
            text-wrap: balance;
            line-height: 1;
        }
        .gallery-header p {
            font-size: 0.9rem;
            font-weight: 300;
            color: var(--espresso);
            margin: 0;
        }

        /* === GALLERY BODY === */
        .gallery-body {
            padding: 60px 5% 100px;
            max-width: 1400px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            box-shadow: 0 -9px 26px -7px rgba(80,37,21,0.20);
        }

        /* === SECTION SEPARATION SHADOW === */
        .site-footer {
            position: relative;
            z-index: 1;
            box-shadow: 0 -9px 26px -7px rgba(80,37,21,0.20);
        }

        /* === SUITE SECTION === */
        .suite-section { margin-top: 80px; }
        .suite-section__header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 10px;
        }
        .suite-section__header h2 {
            font-size: clamp(1.8rem, 3.5vw, 2.8rem);
            font-style: italic;
            color: var(--mahogany);
            margin: 0;
            white-space: nowrap;
            line-height: 1;
        }
        .suite-section__header::after {
            content: '';
            flex: 1;
            height: 1px;
            background: rgba(194,166,110,0.35);
        }

        /* Two-photo editorial spread */
        .suite-spread {
            display: grid;
            grid-template-columns: 1.75fr 1fr;
            gap: 3px;
            height: 520px;
        }
        .spread-main, .spread-side {
            overflow: hidden;
            cursor: pointer;
            position: relative;
            background: var(--soft-white);
        }
        .spread-main img, .spread-side img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.75s cubic-bezier(0.25,0.1,0.25,1);
        }
        .spread-main:hover img, .spread-side:hover img { transform: scale(1.04); }
        .spread-main::after, .spread-side::after {
            content: '';
            position: absolute; inset: 0;
            background: rgba(80,37,21,0.15);
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }
        .spread-main:hover::after, .spread-side:hover::after { opacity: 1; }

        /* Single full-width image */
        .suite-single {
            height: 500px;
            overflow: hidden;
            cursor: pointer;
            position: relative;
            background: var(--soft-white);
        }
        .suite-single img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.75s cubic-bezier(0.25,0.1,0.25,1);
        }
        .suite-single:hover img { transform: scale(1.03); }
        .suite-single::after {
            content: '';
            position: absolute; inset: 0;
            background: rgba(80,37,21,0.15);
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }
        .suite-single:hover::after { opacity: 1; }

        /* Common areas grid */
        .common-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 3px;
        }
        .common-item {
            height: 300px;
            overflow: hidden;
            cursor: pointer;
            position: relative;
            background: var(--soft-white);
        }
        .common-item img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.75s cubic-bezier(0.25,0.1,0.25,1);
        }
        .common-item:hover img { transform: scale(1.04); }
        .common-item::after {
            content: '';
            position: absolute; inset: 0;
            background: rgba(80,37,21,0.15);
            opacity: 0;
            transition: opacity 0.4s ease;
            pointer-events: none;
        }
        .common-item:hover::after { opacity: 1; }

        /* === LIGHTBOX === */
        #lightbox {
            display: none;
            position: fixed;
            z-index: 500;
            inset: 0;
            background: rgba(28,12,4,0.97);
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 32px 24px 24px;
        }
        #lightbox.is-open { display: flex; }

        .lightbox-header-row {
            width: 100%;
            max-width: 1100px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 0 4px;
            flex-shrink: 0;
        }
        .lightbox-title {
            font-family: 'Bodoni Moda', serif;
            font-style: italic;
            font-size: clamp(1.1rem, 2vw, 1.45rem);
            color: var(--white);
            margin: 0;
        }
        .lightbox-right {
            display: flex; align-items: center; gap: 20px;
        }
        .lightbox-counter {
            font-size: 0.72rem; font-weight: 300;
            color: rgba(255,255,255,0.48);
            letter-spacing: 1.5px;
            font-family: 'Poppins', sans-serif;
            min-width: 40px; text-align: right;
        }
        .lightbox-close {
            background: none; border: none;
            color: rgba(255,255,255,0.55); font-size: 1.6rem;
            cursor: pointer; line-height: 1; padding: 0;
            transition: color 0.2s;
        }
        .lightbox-close:hover { color: var(--white); }

        .lightbox-img-wrap {
            width: 100%;
            max-width: 1100px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
            min-height: 0;
        }
        #lightbox-img {
            max-width: 100%;
            max-height: calc(100vh - 220px);
            object-fit: contain;
            transition: opacity 0.22s ease;
            display: block;
        }
        .lightbox-arrow {
            position: absolute; top: 50%; transform: translateY(-50%);
            background: rgba(255,255,255,0.07);
            border: 1px solid rgba(255,255,255,0.16);
            color: var(--white);
            width: 50px; height: 50px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; font-size: 0.9rem;
            transition: background 0.2s, border-color 0.2s;
            z-index: 2;
        }
        .lightbox-arrow:hover { background: rgba(194,166,110,0.3); border-color: rgba(194,166,110,0.5); }
        .lightbox-arrow.prev { left: 16px; }
        .lightbox-arrow.next { right: 16px; }
        .lightbox-arrow[hidden] { display: none !important; }

        .lightbox-dots {
            display: flex; gap: 8px; margin-top: 18px;
            justify-content: center;
            flex-shrink: 0;
        }
        .lightbox-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: rgba(255,255,255,0.22);
            border: none; padding: 0; cursor: pointer;
            transform-origin: center center;
            transition: background 0.2s, transform 0.25s, border-radius 0.25s;
        }
        .lightbox-dot.active { background: var(--gold); transform: scaleX(3.33); border-radius: 3px; }

        /* === FOOTER === */
        .site-footer {
            background: var(--espresso);
            color: rgba(255,255,255,0.75);
            padding: 72px 5% 0;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 48px;
        }
        .footer-brand-name {
            font-family: 'Bodoni Moda', serif;
            font-style: italic;
            font-size: 1.6rem;
            color: var(--white); margin: 0 0 4px; font-weight: 400;
        }
        .footer-brand-loc {
            font-size: 0.72rem; color: var(--gold);
            letter-spacing: 3px; text-transform: uppercase; margin: 0 0 16px;
        }
        .footer-tagline {
            font-size: 0.875rem; font-weight: 300;
            line-height: 1.7; color: rgba(255,255,255,0.55);
            max-width: 28ch; margin: 0;
        }
        .footer-col h5 {
            font-family: 'Poppins', sans-serif;
            font-size: 0.72rem; font-weight: 700;
            letter-spacing: 2.5px; text-transform: uppercase;
            color: var(--gold); margin: 0 0 20px;
        }
        .footer-col a, .footer-col p {
            color: rgba(255,255,255,0.58);
            font-size: 0.875rem; font-weight: 300;
            line-height: 1.9; text-decoration: none;
            display: block; margin-bottom: 4px;
            transition: color 0.2s;
        }
        .footer-col a:hover { color: var(--white); }
        .footer-bottom {
            grid-column: 1 / -1;
            padding: 32px 0 40px;
            margin-top: 16px;
            border-top: 1px solid rgba(255,255,255,0.10);
            font-size: 0.78rem;
            color: rgba(255,255,255,0.32);
        }

        /* === RESPONSIVE === */
        @media (max-width: 960px) {
            .navbar { padding: 16px 28px; }
            .suite-spread { grid-template-columns: 1fr; height: auto; }
            .spread-main { height: 400px; }
            .spread-side { height: 280px; }
            .suite-single { height: 380px; }
            .site-footer { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 640px) {
            .navbar { padding: 14px 20px; }
            .nav-links { display: none; }
            .nav-hamburger { display: flex; }
            .nav-drawer { display: block; }
            .gallery-body { padding: 0 4% 80px; }
            .suite-section { margin-top: 56px; }
            .common-item { height: 220px; }
            .site-footer { grid-template-columns: 1fr; padding: 56px 20px 0; }
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
        <a href="experience.php">Experience</a>
        <a href="gallery.php" class="active">Gallery</a>
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
        <a href="experience.php">Experience</a>
        <a href="gallery.php" class="active">Gallery</a>
        <a href="booking-form.php" class="drawer-book">Book a stay</a>
    </div>
</div>

<header class="gallery-header">
    <span class="gallery-script">a look inside</span>
    <h1>The Gallery</h1>
    <p>Three suites in Alabang. Each one its own space.</p>
</header>

<div class="gallery-body">

    <!-- ============================================================
         AMARI SUITE
         To add photos:
           1. Put the image file in:  assets/images/amari/
           2. Add the path to amariImages[] in the <script> below
           3. To show it in the spread, add a .spread-side or
              expand to a 3-column layout as needed
    ============================================================ -->
    <section class="suite-section" id="amari">
        <div class="suite-section__header">
            <h2>Amari</h2>
        </div>
        <div class="suite-spread">
            <div class="spread-main"
                 onclick="openGallery('Amari Suite', amariImages, 0)"
                 role="button" tabindex="0" aria-label="View Amari Suite gallery">
                <img src="assets/images/amari/hero1.jpg"
                     alt="Amari suite — main living area"
                     loading="lazy"
                     onerror="this.style.opacity='0'">
            </div>
            <div class="spread-side"
                 onclick="openGallery('Amari Suite', amariImages, 1)"
                 role="button" tabindex="0" aria-label="View Amari Suite gallery">
                <img src="assets/images/amari/hero2.jpg"
                     alt="Amari suite — bedroom detail"
                     loading="lazy"
                     onerror="this.style.opacity='0'">
            </div>
        </div>
    </section>

    <!-- ============================================================
         MARIAH SUITE
         To add photos:
           1. Put the image file in:  assets/images/mariah/
           2. Add the path to mariahImages[] in the <script> below
    ============================================================ -->
    <section class="suite-section" id="mariah">
        <div class="suite-section__header">
            <h2>Mariah</h2>
        </div>
        <div class="suite-spread">
            <div class="spread-main"
                 onclick="openGallery('Mariah Suite', mariahImages, 0)"
                 role="button" tabindex="0" aria-label="View Mariah Suite gallery">
                <img src="assets/images/mariah/hero1.jpg"
                     alt="Mariah suite — interior"
                     loading="lazy"
                     onerror="this.style.opacity='0'">
            </div>
            <div class="spread-side"
                 onclick="openGallery('Mariah Suite', mariahImages, 1)"
                 role="button" tabindex="0" aria-label="View Mariah Suite gallery">
                <img src="assets/images/mariah/hero.jpg"
                     alt="Mariah suite — room detail"
                     loading="lazy"
                     onerror="this.style.opacity='0'">
            </div>
        </div>
    </section>

    <!-- ============================================================
         ARA SUITE
         Currently 1 photo. To add more:
           1. Put the image file in:  assets/images/ara/
           2. Add the path to araImages[] in the <script> below
           3. Change .suite-single to .suite-spread and add a
              .spread-side div to show a second photo in the layout
    ============================================================ -->
    <section class="suite-section" id="ara">
        <div class="suite-section__header">
            <h2>Ara</h2>
        </div>
        <div class="suite-single"
             onclick="openGallery('Ara Suite', araImages, 0)"
             role="button" tabindex="0" aria-label="View Ara Suite gallery">
            <img src="assets/images/ara/hero1.jpg"
                 alt="Ara suite — interior"
                 loading="lazy"
                 onerror="this.style.opacity='0'">
        </div>
    </section>

    <!-- ============================================================
         COMMON AREAS
         To add photos:
           1. Put the image file in:  assets/images/
           2. Add a new .common-item div below (copy an existing one)
           3. Add the path to commonImages[] in the <script> below,
              making sure the index matches the onclick parameter
    ============================================================ -->
    <section class="suite-section" id="common">
        <div class="suite-section__header">
            <h2>Common Areas</h2>
        </div>
        <div class="common-grid">
            <div class="common-item"
                 onclick="openGallery('Common Areas', commonImages, 0)"
                 role="button" tabindex="0" aria-label="View infinity pool">
                <img src="assets/images/pool.jpg"
                     alt="Infinity pool overlooking Alabang"
                     loading="lazy"
                     onerror="this.style.opacity='0'">
            </div>
            <div class="common-item"
                 onclick="openGallery('Common Areas', commonImages, 1)"
                 role="button" tabindex="0" aria-label="View hospitality area">
                <img src="assets/images/hospitality.jpg"
                     alt="Amari hospitality and lounge area"
                     loading="lazy"
                     onerror="this.style.opacity='0'">
            </div>
            <div class="common-item"
                 onclick="openGallery('Common Areas', commonImages, 2)"
                 role="button" tabindex="0" aria-label="View interior detail">
                <img src="assets/images/vision1.jpg"
                     alt="Amari property interior"
                     loading="lazy"
                     onerror="this.style.opacity='0'">
            </div>
        </div>
    </section>

</div>

<!-- LIGHTBOX -->
<div id="lightbox" role="dialog" aria-modal="true" aria-label="Photo viewer" onclick="closeLightbox()">
    <div class="lightbox-header-row" onclick="event.stopPropagation()">
        <h2 class="lightbox-title" id="lightbox-title"></h2>
        <div class="lightbox-right">
            <span class="lightbox-counter" id="lightbox-counter"></span>
            <button class="lightbox-close" onclick="closeLightbox()" aria-label="Close photo viewer">&times;</button>
        </div>
    </div>
    <div class="lightbox-img-wrap" onclick="event.stopPropagation()">
        <button class="lightbox-arrow prev" id="arrow-prev" onclick="changeImage(-1)" aria-label="Previous photo">
            <i class="fa-solid fa-chevron-left"></i>
        </button>
        <img id="lightbox-img" src="" alt="">
        <button class="lightbox-arrow next" id="arrow-next" onclick="changeImage(1)" aria-label="Next photo">
            <i class="fa-solid fa-chevron-right"></i>
        </button>
    </div>
    <div class="lightbox-dots" id="lightbox-dots" onclick="event.stopPropagation()" role="tablist"></div>
</div>

<footer class="site-footer">
    <div>
        <p class="footer-brand-name">Amari</p>
        <p class="footer-brand-loc">Alabang</p>
        <p class="footer-tagline">A curated staycation property in Filinvest City, Alabang, Philippines.</p>
    </div>
    <div class="footer-col">
        <h5>Explore</h5>
        <a href="about.php">About</a>
        <a href="experience.php">Experience</a>
        <a href="gallery.php">Gallery</a>
        <a href="index.php#rooms">Accommodations</a>
    </div>
    <div class="footer-col">
        <h5>Spaces</h5>
        <a href="index.php#rooms">Amari Suite</a>
        <a href="index.php#rooms">Mariah Suite</a>
        <a href="index.php#rooms">Ara Suite</a>
    </div>
    <div class="footer-col">
        <h5>Contact</h5>
        <p>Filinvest City, Alabang<br>Philippines</p>
        <p>Globe: (+63) 917-123-4567</p>
    </div>
    <div class="footer-bottom">
        &copy; 2026 Amari Staycation Alabang. All rights reserved.
    </div>
</footer>

<script>
    /* =============================================================
       PHOTO ARRAYS — this is where you add new photos

       Steps to add a new photo to any suite:
         1. Copy the image file into the correct folder:
              Amari   →  assets/images/amari/
              Mariah  →  assets/images/mariah/
              Ara     →  assets/images/ara/
              Common  →  assets/images/
         2. Add the file path to the matching array below.
            Example: add 'assets/images/amari/bedroom.jpg'
            to the amariImages array.
         3. The photo will automatically appear in the lightbox
            carousel when a visitor clicks that suite's section.
    ============================================================= */

    const amariImages = [
        'assets/images/amari/hero1.jpg',
        'assets/images/amari/hero2.jpg',
        /* Add more Amari photos here ↓
        'assets/images/amari/YOUR_PHOTO_NAME.jpg',
        */
    ];

    const mariahImages = [
        'assets/images/mariah/hero1.jpg',
        'assets/images/mariah/hero.jpg',
        /* Add more Mariah photos here ↓
        'assets/images/mariah/YOUR_PHOTO_NAME.jpg',
        */
    ];

    const araImages = [
        'assets/images/ara/hero1.jpg',
        /* Add more Ara photos here ↓
        'assets/images/ara/YOUR_PHOTO_NAME.jpg',
        */
    ];

    const commonImages = [
        'assets/images/pool.jpg',
        'assets/images/hospitality.jpg',
        'assets/images/vision1.jpg',
        /* Add more common area photos here ↓
        'assets/images/YOUR_PHOTO_NAME.jpg',
        */
    ];

    /* ========= LIGHTBOX ENGINE ========= */
    let currentImages = [], currentIndex = 0;

    function openGallery(title, imageArray, startIndex) {
        currentImages = imageArray.filter(Boolean);
        currentIndex  = Math.max(0, Math.min(startIndex, currentImages.length - 1));

        document.getElementById('lightbox-title').textContent = title;
        renderLightbox();

        const lb = document.getElementById('lightbox');
        lb.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function renderLightbox() {
        const img    = document.getElementById('lightbox-img');
        const count  = document.getElementById('lightbox-counter');
        const dots   = document.getElementById('lightbox-dots');
        const prev   = document.getElementById('arrow-prev');
        const next   = document.getElementById('arrow-next');
        const single = currentImages.length <= 1;

        img.style.opacity = '0';
        setTimeout(() => {
            img.src = currentImages[currentIndex];
            img.alt = document.getElementById('lightbox-title').textContent;
            img.style.opacity = '1';
        }, 130);

        count.textContent = single ? '' : `${currentIndex + 1} / ${currentImages.length}`;
        prev.hidden = single;
        next.hidden = single;

        dots.innerHTML = '';
        if (!single) {
            currentImages.forEach((_, i) => {
                const btn = document.createElement('button');
                btn.className = 'lightbox-dot' + (i === currentIndex ? ' active' : '');
                btn.setAttribute('aria-label', `Photo ${i + 1}`);
                btn.setAttribute('role', 'tab');
                btn.onclick = (e) => { e.stopPropagation(); currentIndex = i; renderLightbox(); };
                dots.appendChild(btn);
            });
        }
    }

    function changeImage(step) {
        currentIndex = (currentIndex + step + currentImages.length) % currentImages.length;
        renderLightbox();
    }

    function closeLightbox() {
        const lb = document.getElementById('lightbox');
        lb.classList.remove('is-open');
        lb.style.display = '';
        document.body.style.overflow = '';
    }

    document.addEventListener('keydown', e => {
        const lb = document.getElementById('lightbox');
        if (!lb.classList.contains('is-open')) return;
        if (e.key === 'Escape')     closeLightbox();
        if (e.key === 'ArrowLeft')  changeImage(-1);
        if (e.key === 'ArrowRight') changeImage(1);
    });

    document.querySelectorAll('[role="button"]').forEach(el => {
        el.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); el.click(); }
        });
    });

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
