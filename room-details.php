<?php
include('includes/db-config.php');

if (isset($_GET['id'])) {
    $id   = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM units WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $unit = $stmt->get_result()->fetch_assoc();
    if (!$unit) { header("Location: index.php"); exit(); }
} else {
    header("Location: index.php"); exit();
}

$clean_title = trim(preg_replace('/[0-9]+/', '', $unit['title']));
$folder_name = strtolower($clean_title);
$hero_image  = 'assets/images/' . $folder_name . '/hero1.jpg';

// Build gallery — use hero2 if it exists alongside hero1
$gallery = [];
foreach (['hero1.jpg', 'hero2.jpg', 'hero.jpg'] as $f) {
    $p = 'assets/images/' . $folder_name . '/' . $f;
    if (file_exists($p)) $gallery[] = $p;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($clean_title) ?> Suite | Amari Alabang</title>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400;0,6..96,500;1,6..96,400;1,6..96,500&family=Pinyon+Script&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* === BRAND TOKENS === */
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
            .reveal, .reveal-left, .reveal-right, .animate-title,
            .nav-hamburger span, .nav-drawer__panel, .nav-drawer__backdrop {
                opacity: 1 !important; transform: none !important;
                transition: none !important; animation: none !important;
            }
        }

        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: var(--espresso);
            background: radial-gradient(120% 80% at 50% 0%, #fdfbf9 0%, var(--soft-white) 55%, #f6f0ee 100%);
            overflow-x: hidden;
        }
        body::before {
            content: ''; position: fixed; inset: 0; z-index: 0; pointer-events: none; opacity: 0.5;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.045'/%3E%3C/svg%3E");
        }
        .room-hero { z-index: 1; }
        h1, h2, h3, h4 { font-family: 'Bodoni Moda', serif; font-weight: 400; }
        img { display: block; max-width: 100%; }

        /* === NAVBAR === */
        .navbar {
            display: flex; justify-content: space-between; align-items: center;
            padding: 18px 56px;
            background: var(--white);
            border-bottom: 1px solid rgba(86,67,40,0.10);
            position: sticky; top: 0; z-index: 200;
        }
        .nav-brand { text-decoration: none; line-height: 1; }
        .nav-brand__name {
            font-family: 'Bodoni Moda', serif; font-style: italic; font-size: 1.4rem;
            color: var(--mahogany); margin: 0; line-height: 1.1;
        }
        .nav-brand__sub {
            font-size: 0.58rem; color: rgba(86, 67, 40, 0.80);
            letter-spacing: 3px; text-transform: uppercase; margin: 2px 0 0;
        }
        .nav-links { display: flex; align-items: center; gap: 28px; }
        .nav-links a {
            text-decoration: none; color: var(--espresso);
            font-size: 0.8rem; font-weight: 600;
            letter-spacing: 0.3px; transition: color 0.2s;
        }
        .nav-links a:hover, .nav-links a.active { color: var(--gold); }
        .btn-book-nav {
            background: var(--mahogany) !important; color: var(--white) !important;
            padding: 11px 24px; font-size: 0.76rem !important;
            font-weight: 700 !important; letter-spacing: 1px;
            text-transform: uppercase; transition: background 0.2s !important;
        }
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

        /* === HERO === */
        .room-hero {
            height: 78vh; min-height: 520px;
            position: relative;
            display: flex; align-items: flex-end;
            overflow: hidden;
        }
        .room-hero__bg {
            position: absolute; inset: 0;
            background: url('<?= htmlspecialchars($hero_image) ?>') center/cover no-repeat;
            background-color: var(--espresso);
        }
        .room-hero::before {
            content: '';
            position: absolute; inset: 0; z-index: 1;
            background: linear-gradient(
                to top,
                rgba(80,37,21,0.88) 0%,
                rgba(80,37,21,0.20) 40%,
                rgba(0,0,0,0.18) 100%
            );
        }
        .room-hero__content {
            position: relative; z-index: 2;
            color: var(--white);
            padding: 0 10% 72px;
            max-width: 860px;
        }
        .breadcrumb {
            font-size: 0.75rem; font-weight: 500;
            color: rgba(255,255,255,0.55); margin: 0 0 20px;
            display: flex; align-items: center; gap: 8px;
        }
        .breadcrumb a { color: rgba(255,255,255,0.55); text-decoration: none; transition: color 0.2s; }
        .breadcrumb a:hover { color: var(--gold); }
        .breadcrumb span { color: rgba(255,255,255,0.3); }
        .room-hero__title {
            font-size: clamp(3rem, 6.5vw, 5.5rem);
            font-style: italic;
            margin: 0 0 14px;
            letter-spacing: 0.01em;
            line-height: 1;
            text-wrap: balance;
        }
        .room-hero__price {
            font-family: 'Bodoni Moda', serif;
            font-style: italic;
            font-size: 1.65rem;
            color: var(--gold);
            margin: 0;
            line-height: 1;
        }
        .room-hero__price span {
            font-family: 'Poppins', sans-serif;
            font-size: 0.85rem;
            font-weight: 300;
            color: rgba(255,255,255,0.55);
        }
        @keyframes titleIn {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-title { animation: titleIn 0.85s cubic-bezier(0.25,0.1,0.25,1) forwards; }

        /* === GALLERY STRIP (only when multiple images exist) === */
        .gallery-strip {
            display: flex; gap: 3px;
            height: 160px; overflow: hidden;
        }
        .gallery-strip img {
            flex: 1; object-fit: cover;
            cursor: pointer; opacity: 0.78;
            transition: opacity 0.35s cubic-bezier(0.25,0.1,0.25,1),
                        flex 0.5s cubic-bezier(0.25,0.1,0.25,1);
        }
        .gallery-strip img:hover { opacity: 1; flex: 1.8; }

        /* === DETAIL LAYOUT === */
        .detail-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 5% 100px;
            display: flex;
            gap: 56px;
            flex-wrap: wrap;
            align-items: flex-start;
        }
        .detail-main { flex: 2; min-width: 320px; padding-top: 72px; }
        .detail-sidebar { flex: 0 0 340px; min-width: 280px; }

        /* Floating sidebar card */
        .sidebar-card {
            background: var(--white);
            padding: 40px 36px;
            box-shadow:
                0 1px 2px rgba(80,37,21,0.06),
                0 22px 50px -14px rgba(80,37,21,0.26);
            position: sticky;
            top: 100px;
            margin-top: -80px;
        }
        .sidebar-price {
            font-family: 'Bodoni Moda', serif;
            font-style: italic;
            font-size: 2.1rem;
            color: var(--mahogany);
            margin: 0 0 4px;
            line-height: 1;
        }
        .sidebar-price-sub {
            font-size: 0.78rem; font-weight: 300;
            color: var(--espresso); margin: 0 0 32px;
        }
        .sidebar-card h3 {
            font-family: 'Poppins', sans-serif;
            font-size: 0.8rem; font-weight: 600;
            color: var(--gold); margin: 0 0 20px;
            padding-top: 24px;
            border-top: 1px solid rgba(86,67,40,0.10);
        }

        /* Amenities */
        .amenity-list { list-style: none; padding: 0; margin: 0 0 32px; }
        .amenity-list li {
            display: flex; align-items: center; gap: 14px;
            margin-bottom: 14px;
            font-size: 0.875rem; font-weight: 500;
            color: var(--espresso);
        }
        .amenity-list li i {
            color: var(--gold); width: 18px; text-align: center; flex-shrink: 0;
        }

        .btn-book-large {
            display: block; width: 100%; text-align: center;
            background: var(--mahogany); color: var(--white);
            padding: 18px; text-decoration: none;
            font-weight: 700; font-size: 0.82rem;
            text-transform: uppercase; letter-spacing: 1.5px;
            transition: background 0.2s, transform 0.2s;
        }
        .btn-book-large:hover {
            background: var(--espresso);
        }
        .btn-inquire {
            display: block; width: 100%; text-align: center;
            border: 1.5px solid rgba(86,67,40,0.25); color: var(--espresso);
            padding: 14px; text-decoration: none;
            font-weight: 600; font-size: 0.78rem;
            text-transform: uppercase; letter-spacing: 1px;
            margin-top: 10px;
            transition: border-color 0.2s, color 0.2s;
        }
        .btn-inquire:hover { border-color: var(--gold); color: var(--mahogany); }

        /* Main content typography */
        .detail-main h2 {
            font-size: clamp(1.6rem, 2.5vw, 2.2rem);
            font-style: italic;
            color: var(--mahogany); margin: 0 0 24px;
            line-height: 1.15;
        }
        .detail-main p {
            font-size: 1rem; line-height: 1.9;
            color: var(--espresso); font-weight: 300;
            margin: 0 0 20px; max-width: 62ch;
            text-wrap: pretty;
        }
        .detail-quote {
            font-family: 'Bodoni Moda', serif;
            font-style: italic;
            font-size: 1.2rem;
            color: var(--mahogany);
            line-height: 1.75;
            margin: 44px 0;
            padding: 0;
        }
        .detail-quote::before {
            content: '\201C';
            color: var(--gold);
            font-size: 2.6rem;
            line-height: 0;
            vertical-align: -0.4em;
            margin-right: 4px;
        }

        /* === SECTION SEPARATION SHADOW === */
        /* Soft top-edge shadow so each section boundary reads like the navbar's. */
        .detail-wrapper,
        .site-footer {
            position: relative;
            z-index: 1;
            box-shadow: 0 -9px 26px -7px rgba(80,37,21,0.20);
        }

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

        /* Scroll reveals */
        .js-ready .reveal {
            opacity: 0; transform: translateY(32px);
            transition: opacity 0.65s cubic-bezier(0.25,0.1,0.25,1),
                        transform 0.65s cubic-bezier(0.25,0.1,0.25,1);
        }
        .js-ready .reveal-left {
            opacity: 0; transform: translateX(-40px);
            transition: opacity 0.65s cubic-bezier(0.25,0.1,0.25,1),
                        transform 0.65s cubic-bezier(0.25,0.1,0.25,1);
        }
        .reveal.in-view, .reveal-left.in-view { opacity: 1 !important; transform: translate(0) !important; }

        /* Responsive */
        @media (max-width: 960px) {
            .navbar { padding: 16px 28px; }
            .detail-wrapper { flex-direction: column; padding-top: 0; }
            .detail-main { padding-top: 40px; }
            .detail-sidebar { flex: 0 0 auto; width: 100%; }
            .sidebar-card { position: static; margin-top: 0; box-shadow: 0 6px 24px var(--shadow-sm); }
            .room-hero__content { padding: 0 7% 56px; }
            .site-footer { grid-template-columns: 1fr 1fr; }
        }
        @media (max-width: 640px) {
            .navbar { padding: 14px 20px; }
            .nav-links { display: none; }
            .nav-hamburger { display: flex; }
            .nav-drawer { display: block; }
            .room-hero { height: 65vh; min-height: 420px; }
            .room-hero__title { font-size: clamp(2.4rem, 9vw, 3.5rem); }
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
        <a href="index.php#rooms" class="active">Rooms</a>
        <a href="experience.php">Experience</a>
        <a href="gallery.php">Gallery</a>
        <a href="booking-form.php?unit_id=<?= (int)$unit['id'] ?>" class="btn-book-nav">Book this suite</a>
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
        <a href="gallery.php">Gallery</a>
        <a href="booking-form.php?unit_id=<?= (int)$unit['id'] ?>" class="drawer-book">Book this suite</a>
    </div>
</div>

<header class="room-hero">
    <div class="room-hero__bg"></div>
    <div class="room-hero__content">
        <nav class="breadcrumb" aria-label="Breadcrumb">
            <a href="index.php">Home</a>
            <span>&#8250;</span>
            <a href="index.php#rooms">Rooms</a>
            <span>&#8250;</span>
            <span><?= htmlspecialchars($clean_title) ?></span>
        </nav>
        <h1 class="room-hero__title animate-title"><?= htmlspecialchars($clean_title) ?></h1>
        <p class="room-hero__price animate-title" style="animation-delay:0.18s; opacity:0;">
            &#8369;<?= number_format($unit['price_per_night'], 2) ?>
            <span>&nbsp;per night</span>
        </p>
    </div>
</header>

<?php if (count($gallery) > 1): ?>
<div class="gallery-strip">
    <?php foreach ($gallery as $gi => $gp): ?>
    <img src="<?= htmlspecialchars($gp) ?>"
         alt="<?= htmlspecialchars($clean_title) ?> suite — view <?= $gi + 1 ?>"
         loading="lazy">
    <?php endforeach; ?>
</div>
<?php endif; ?>

<div class="detail-wrapper">

    <main class="detail-main">
        <div class="reveal">
            <h2>The Experience</h2>
            <p><?= nl2br(htmlspecialchars($unit['description'])) ?></p>
            <blockquote class="detail-quote">
                Designed for those who want the comforts of home with none of the obligations of it.
            </blockquote>
        </div>
    </main>

    <aside class="detail-sidebar">
        <div class="sidebar-card reveal-left">
            <p class="sidebar-price">&#8369;<?= number_format($unit['price_per_night'], 2) ?></p>
            <p class="sidebar-price-sub">per night &middot; all inclusive</p>

            <h3>Amenities</h3>
            <ul class="amenity-list">
                <?php
                if (!empty($unit['amenities'])) {
                    foreach (explode(',', $unit['amenities']) as $item) {
                        $item = trim($item);
                        $icon = 'fa-check';
                        if (stripos($item, 'wifi')    !== false) $icon = 'fa-wifi';
                        if (stripos($item, 'pool')    !== false) $icon = 'fa-person-swimming';
                        if (stripos($item, 'gym')     !== false) $icon = 'fa-dumbbell';
                        if (stripos($item, 'view')    !== false) $icon = 'fa-city';
                        if (stripos($item, 'tv')      !== false) $icon = 'fa-tv';
                        if (stripos($item, 'kitchen') !== false) $icon = 'fa-utensils';
                        if (stripos($item, 'parking') !== false) $icon = 'fa-square-parking';
                        if (stripos($item, 'ac')      !== false || stripos($item, 'air') !== false) $icon = 'fa-wind';
                        echo "<li><i class='fa-solid {$icon}'></i> " . htmlspecialchars($item) . "</li>";
                    }
                } else {
                    echo "<li><i class='fa-solid fa-star'></i> Premium furnishings</li>";
                    echo "<li><i class='fa-solid fa-wifi'></i> High-speed WiFi</li>";
                    echo "<li><i class='fa-solid fa-wind'></i> Air conditioning</li>";
                }
                ?>
            </ul>

            <a href="booking-form.php?unit_id=<?= (int)$unit['id'] ?>" class="btn-book-large">
                Reserve dates
            </a>
            <a href="index.php#rooms" class="btn-inquire">
                Browse all suites
            </a>
        </div>
    </aside>

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
    document.documentElement.classList.add('js-ready');

    const revealObs = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                revealObs.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px', threshold: 0.10 });

    document.querySelectorAll('.reveal, .reveal-left')
            .forEach(el => revealObs.observe(el));

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
