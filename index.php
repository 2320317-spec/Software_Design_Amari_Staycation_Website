<?php
include('includes/db-config.php');
$featured_query = $conn->query("SELECT * FROM units WHERE status = 'available' ORDER BY id DESC LIMIT 3");
$rooms = [];
while ($r = $featured_query->fetch_assoc()) { $rooms[] = $r; }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amari Staycation | Alabang</title>
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400;0,6..96,500;1,6..96,400;1,6..96,500&family=Pinyon+Script&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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

        /* === REDUCED MOTION — set before any reveal classes === */
        @media (prefers-reduced-motion: reduce) {
            .reveal, .reveal-left, .reveal-right,
            .animate-title, .hero-title,
            .nav-hamburger span,
            .nav-drawer__panel,
            .nav-drawer__backdrop,
            .hero-dot {
                opacity: 1 !important;
                transform: none !important;
                transition: none !important;
                animation: none !important;
            }
        }

        /* === BASE === */
        *, *::before, *::after { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: var(--espresso);
            background:
                radial-gradient(120% 80% at 50% 0%, #fdfbf9 0%, var(--soft-white) 55%, #f6f0ee 100%);
            overflow-x: hidden;
        }
        /* Faint grain so flat backgrounds have a whisper of texture (sits behind content) */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            z-index: 0;
            pointer-events: none;
            opacity: 0.5;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.045'/%3E%3C/svg%3E");
        }
        h1, h2, h3, h4 { font-family: 'Bodoni Moda', serif; font-weight: 400; }
        img { display: block; max-width: 100%; }

        /* === NAVBAR === */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 56px;
            background: var(--white);
            border-bottom: 1px solid rgba(86,67,40,0.08);
            box-shadow: 0 4px 18px rgba(80,37,21,0.07);
            position: sticky;
            top: 0;
            z-index: 200;
        }
        .nav-brand { text-decoration: none; line-height: 1; }
        .nav-brand__name {
            font-family: 'Bodoni Moda', serif;
            font-style: italic;
            font-size: 1.4rem;
            color: var(--mahogany);
            margin: 0;
            line-height: 1.1;
        }
        .nav-brand__sub {
            font-family: 'Poppins', sans-serif;
            font-size: 0.58rem;
            color: rgba(86, 67, 40, 0.80);
            letter-spacing: 3px;
            text-transform: uppercase;
            margin: 2px 0 0;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 28px;
        }
        .nav-links a {
            text-decoration: none;
            color: var(--espresso);
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.3px;
            transition: color 0.2s;
        }
        .nav-links a:hover { color: var(--gold); }
        .btn-book-nav {
            background: var(--mahogany) !important;
            color: var(--white) !important;
            padding: 11px 24px;
            font-size: 0.76rem !important;
            font-weight: 700 !important;
            letter-spacing: 1px;
            text-transform: uppercase;
            transition: background 0.2s !important;
        }
        .btn-book-nav:hover { background: var(--espresso) !important; color: var(--white) !important; }

        /* === HAMBURGER === */
        .nav-hamburger {
            display: none;
            flex-direction: column;
            justify-content: center;
            gap: 5px;
            width: 36px;
            height: 36px;
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            z-index: 300;
        }
        .nav-hamburger span {
            display: block;
            width: 22px;
            height: 1.5px;
            background: var(--mahogany);
            transition: transform 0.3s cubic-bezier(0.25,0.1,0.25,1),
                        opacity 0.3s;
            transform-origin: center;
        }
        .nav-hamburger.is-open span:nth-child(1) { transform: translateY(6.5px) rotate(45deg); }
        .nav-hamburger.is-open span:nth-child(2) { opacity: 0; }
        .nav-hamburger.is-open span:nth-child(3) { transform: translateY(-6.5px) rotate(-45deg); }

        /* Mobile drawer */
        .nav-drawer {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 250;
            pointer-events: none;
        }
        .nav-drawer__backdrop {
            position: absolute;
            inset: 0;
            background: rgba(80,37,21,0.35);
            opacity: 0;
            transition: opacity 0.3s;
            pointer-events: none;
        }
        .nav-drawer__panel {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: min(280px, 80vw);
            background: var(--white);
            padding: 88px 36px 48px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            transform: translateX(100%);
            transition: transform 0.35s cubic-bezier(0.25,0.1,0.25,1);
            pointer-events: none;
        }
        .nav-drawer.is-open {
            pointer-events: auto;
        }
        .nav-drawer.is-open .nav-drawer__backdrop {
            opacity: 1;
            pointer-events: auto;
        }
        .nav-drawer.is-open .nav-drawer__panel {
            transform: translateX(0);
            pointer-events: auto;
        }
        .nav-drawer__panel a {
            font-family: 'Bodoni Moda', serif;
            font-style: italic;
            font-size: 1.5rem;
            color: var(--mahogany);
            text-decoration: none;
            padding: 10px 0;
            border-bottom: 1px solid rgba(86,67,40,0.08);
            transition: color 0.2s;
        }
        .nav-drawer__panel a:hover { color: var(--gold); }
        .nav-drawer__panel .drawer-book {
            margin-top: 28px;
            font-family: 'Poppins', sans-serif;
            font-style: normal;
            font-size: 0.78rem;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            color: var(--white);
            background: var(--mahogany);
            padding: 13px 20px;
            text-align: center;
            border-bottom: none;
        }
        .nav-drawer__panel .drawer-book:hover { background: var(--espresso); color: var(--white); }

        /* === HERO === */
        .hero {
            position: relative;
            height: 88vh;
            min-height: 580px;
            max-height: 1000px;
            overflow: hidden;
            background: var(--espresso);
        }
        .flash-overlay {
            position: absolute; inset: 0;
            background: #fff; opacity: 0;
            z-index: 15; pointer-events: none;
            transition: opacity 0.5s ease-out;
        }
        .flash-overlay.is-flashing { opacity: 0.06; }
        .slider-track {
            display: flex; width: 300%; height: 100%;
            will-change: transform;
            transition: transform 1.4s cubic-bezier(0.77,0,0.175,1);
        }
        .slide { width: 33.333%; height: 100%; flex-shrink: 0; }
        .slide img { width: 100%; height: 100%; object-fit: cover; object-position: center; }
        .hero-overlay {
            position: absolute; inset: 0; z-index: 10;
            background: linear-gradient(
                to top,
                rgba(80,37,21,0.72) 0%,
                rgba(0,0,0,0.15) 45%,
                rgba(0,0,0,0.22) 100%
            );
            display: flex;
            align-items: flex-end;
            padding: 0 10% 9%;
        }
        .hero-content { color: var(--white); max-width: 680px; }
        .hero-title {
            font-size: clamp(3.5rem, 8.5vw, 6rem);
            font-weight: 400;
            font-style: italic;
            margin: 0 0 10px;
            letter-spacing: 0.01em;
            text-wrap: balance;
            line-height: 1;
        }
        .hero-sub {
            font-family: 'Poppins', sans-serif;
            font-size: 0.78rem;
            font-weight: 300;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.78);
            margin: 0 0 36px;
        }
        .btn-hero {
            display: inline-block;
            background: transparent;
            border: 1.5px solid rgba(255,255,255,0.65);
            color: var(--white);
            padding: 13px 32px;
            text-decoration: none;
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            font-size: 0.78rem;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            transition: background 0.2s, border-color 0.2s;
        }
        .btn-hero:hover {
            background: var(--gold);
            border-color: var(--gold);
        }
        @keyframes titleIn {
            from { opacity: 0; transform: translateY(28px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .animate-title { animation: titleIn 0.85s cubic-bezier(0.25,0.1,0.25,1) forwards; }
        .hero-arrows {
            position: absolute; inset: 0; z-index: 20;
            display: flex; align-items: center;
            justify-content: space-between;
            padding: 0 28px; pointer-events: none;
        }
        .hero-arrow {
            width: 46px; height: 46px;
            border-radius: 50%;
            background: rgba(250,246,246,0.12);
            border: 1px solid rgba(255,255,255,0.25);
            color: var(--white); font-size: 1.3rem;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; pointer-events: auto;
            transition: background 0.2s;
        }
        .hero-arrow:hover { background: rgba(194,166,110,0.65); }
        .hero-dots {
            position: absolute; bottom: 28px;
            left: 10%; z-index: 20;
            display: flex; gap: 8px;
        }
        .hero-dot {
            width: 6px; height: 6px; border-radius: 50%;
            background: rgba(255,255,255,0.35);
            border: none; padding: 0; cursor: pointer;
            transform-origin: left center;
            transition: background 0.25s, transform 0.25s cubic-bezier(0.25,0.1,0.25,1), border-radius 0.25s;
        }
        .hero-dot.active { background: var(--gold); transform: scaleX(3.67); border-radius: 3px; }

        /* === SECTION SEPARATION SHADOW === */
        /* Soft top-edge shadow so each section boundary reads like the navbar's.
           Only on full-width sections — narrow centered blocks would get side shadows. */
        .split-layout,
        .rooms-section,
        .reviews-section,
        .site-footer {
            position: relative;
            z-index: 1;
            box-shadow: 0 -9px 26px -7px rgba(80,37,21,0.20);
        }

        /* === WELCOME === */
        .welcome-section {
            text-align: center;
            padding: 108px 24px 100px;
            max-width: 700px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        /* Oversized faint watermark filling the empty space behind the heading */
        .welcome-section::before {
            content: 'Amari';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -54%);
            font-family: 'Bodoni Moda', serif;
            font-style: italic;
            font-size: clamp(6rem, 16vw, 15rem);
            line-height: 1;
            color: rgba(80, 37, 21, 0.05);
            white-space: nowrap;
            max-width: 100vw;
            pointer-events: none;
            z-index: -1;
            user-select: none;
        }
        .welcome-section > * { position: relative; }
        .welcome-script {
            font-family: 'Pinyon Script', cursive;
            font-size: clamp(1.6rem, 2.8vw, 2.2rem);
            color: var(--gold);
            display: block;
            margin: 0 0 6px;
            line-height: 1.2;
            font-weight: 400;
        }
        .welcome-section h2 {
            font-size: clamp(1.9rem, 3.5vw, 2.9rem);
            font-style: italic;
            color: var(--mahogany);
            margin: 0 0 28px;
            text-wrap: balance;
            line-height: 1.15;
        }
        .welcome-divider {
            width: 48px;
            height: 1px;
            background: var(--gold);
            margin: 0 auto 28px;
            opacity: 0.6;
        }
        .welcome-section p {
            line-height: 1.9;
            color: var(--espresso);
            font-size: 0.975rem;
            margin: 0 0 16px;
            font-weight: 300;
            max-width: 62ch;
            margin-inline: auto;
            text-wrap: pretty;
        }

        /* === SPLIT === */
        .split-layout { display: flex; flex-wrap: wrap; min-height: 460px; }
        .split-text {
            flex: 1; min-width: 300px;
            background: var(--mahogany);
            color: var(--white);
            padding: 80px 10%;
            display: flex; flex-direction: column; justify-content: center;
        }
        .split-text h2 {
            font-size: clamp(1.7rem, 3vw, 2.4rem);
            font-style: italic;
            color: var(--white); margin: 0 0 18px;
            text-wrap: balance;
            line-height: 1.2;
        }
        .split-text p {
            font-size: 0.93rem;
            line-height: 1.9;
            color: rgba(255,255,255,0.75);
            font-weight: 300;
            margin: 0 0 32px;
            max-width: 46ch;
            text-wrap: pretty;
        }
        .split-link {
            color: var(--gold); text-decoration: none;
            font-size: 0.78rem; font-weight: 700;
            letter-spacing: 1.5px; text-transform: uppercase;
            display: inline-flex; align-items: center; gap: 8px;
            transition: gap 0.25s;
        }
        .split-link:hover { gap: 14px; }
        .split-image {
            flex: 1; min-width: 300px; min-height: 400px;
            background: url('assets/images/pool.jpg') center/cover;
        }

        /* === ROOMS === */
        .rooms-section { padding: 100px 5%; }
        .rooms-header { margin-bottom: 52px; }
        .rooms-header h2 {
            font-size: clamp(1.9rem, 3.5vw, 2.9rem);
            font-style: italic;
            color: var(--mahogany); margin: 0 0 10px;
            text-wrap: balance;
        }
        .rooms-header p {
            color: var(--espresso); font-weight: 300;
            font-size: 0.93rem; margin: 0;
        }

        /* Featured strip */
        .room-feature {
            display: flex;
            margin-bottom: 24px;
            overflow: hidden;
            background: var(--white);
            min-height: 440px;
            box-shadow:
                0 1px 2px rgba(80,37,21,0.06),
                0 18px 40px -12px rgba(80,37,21,0.22);
            transition: box-shadow 0.4s cubic-bezier(0.25,0.1,0.25,1);
        }
        .room-feature:hover {
            box-shadow:
                0 1px 2px rgba(80,37,21,0.08),
                0 30px 60px -14px rgba(80,37,21,0.30);
        }
        .room-feature__image {
            flex: 0 0 58%;
            overflow: hidden;
        }
        .room-feature__image img {
            width: 100%; height: 100%;
            object-fit: cover;
            transition: transform 0.7s cubic-bezier(0.25,0.1,0.25,1);
        }
        .room-feature:hover .room-feature__image img { transform: scale(1.04); }
        .room-feature__details {
            flex: 1;
            padding: 52px 56px;
            display: flex; flex-direction: column; justify-content: center;
        }
        .room-tag {
            display: block;
            width: 40px;
            height: 1px;
            background: var(--gold);
            margin-bottom: 22px;
            opacity: 0.7;
        }
        .room-feature__details h3 {
            font-size: clamp(1.8rem, 2.6vw, 2.8rem);
            font-style: italic;
            color: var(--mahogany); margin: 0 0 16px;
            line-height: 1.1;
        }
        .room-feature__details p {
            color: var(--espresso); font-weight: 300;
            line-height: 1.85; font-size: 0.93rem;
            margin: 0 0 28px; max-width: 46ch;
            text-wrap: pretty;
        }
        .room-price {
            font-family: 'Bodoni Moda', serif;
            font-size: 1.5rem;
            color: var(--mahogany);
            margin-bottom: 28px;
            line-height: 1;
        }
        .room-price span {
            font-family: 'Poppins', sans-serif;
            font-size: 0.78rem;
            color: var(--espresso);
            font-weight: 300;
        }
        .btn-room {
            display: inline-block;
            border: 1.5px solid var(--gold);
            color: var(--mahogany);
            padding: 12px 28px;
            text-decoration: none;
            font-size: 0.76rem; font-weight: 700;
            letter-spacing: 1px; text-transform: uppercase;
            transition: background 0.2s, color 0.2s;
            align-self: flex-start;
        }
        .btn-room:hover { background: var(--gold); color: var(--white); }

        /* Secondary grid */
        .rooms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }
        .room-card {
            background: var(--white);
            overflow: hidden;
            box-shadow:
                0 1px 2px rgba(80,37,21,0.06),
                0 14px 32px -12px rgba(80,37,21,0.20);
            transition: box-shadow 0.4s cubic-bezier(0.25,0.1,0.25,1);
        }
        .room-card:hover {
            box-shadow:
                0 1px 2px rgba(80,37,21,0.08),
                0 26px 52px -14px rgba(80,37,21,0.28);
        }
        .room-card__image { height: 260px; overflow: hidden; }
        .room-card__image img {
            width: 100%; height: 100%; object-fit: cover;
            transition: transform 0.7s cubic-bezier(0.25,0.1,0.25,1);
        }
        .room-card:hover .room-card__image img { transform: scale(1.05); }
        .room-card__details { padding: 28px 30px 32px; }
        .room-card__details h3 {
            font-size: 1.5rem;
            font-style: italic;
            color: var(--mahogany); margin: 0 0 10px;
        }
        .room-card__details p {
            color: var(--espresso); font-size: 0.875rem;
            line-height: 1.8; font-weight: 300;
            margin: 0 0 20px; text-wrap: pretty;
        }
        .room-card-footer {
            display: flex; align-items: center;
            justify-content: space-between; flex-wrap: wrap; gap: 10px;
        }
        .btn-room-sm {
            text-decoration: none; color: var(--gold);
            font-size: 0.75rem; font-weight: 700;
            letter-spacing: 1px; text-transform: uppercase;
            display: inline-flex; align-items: center; gap: 6px;
            transition: gap 0.2s;
        }
        .btn-room-sm:hover { gap: 12px; }

        /* === REVIEWS === */
        .reviews-section {
            padding: 100px 5%;
            background: var(--mahogany);
        }
        .reviews-section h2 {
            font-size: clamp(1.9rem, 3.5vw, 2.9rem);
            font-style: italic;
            color: var(--white); margin: 0 0 64px;
            text-align: center; text-wrap: balance;
        }
        .reviews-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 48px;
        }
        .review-quote {
            font-family: 'Bodoni Moda', serif;
            font-style: italic;
            font-size: 1.08rem;
            line-height: 1.85;
            color: rgba(255,255,255,0.85);
            margin: 0 0 20px;
            text-wrap: pretty;
        }
        .review-quote::before {
            content: '\201C';
            color: var(--gold);
            font-size: 2.4rem;
            line-height: 0;
            vertical-align: -0.42em;
            margin-right: 3px;
        }
        .review-author {
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            color: var(--gold);
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

        /* === ADVISORY MODAL === */
        .advisory {
            position: fixed;
            inset: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            background: rgba(80,37,21,0.55);
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }
        .advisory.is-open { opacity: 1; visibility: visible; }
        .advisory__card {
            position: relative;
            background: var(--soft-white);
            max-width: 620px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            padding: 52px 56px 44px;
            text-align: center;
            box-shadow: 0 24px 70px rgba(80,37,21,0.30);
            transform: translateY(24px);
            transition: transform 0.45s cubic-bezier(0.25,0.1,0.25,1);
        }
        .advisory.is-open .advisory__card { transform: translateY(0); }
        .advisory__close {
            position: absolute;
            top: 18px;
            right: 18px;
            background: var(--mahogany);
            color: var(--white);
            border: none;
            font-family: 'Poppins', sans-serif;
            font-size: 0.66rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            padding: 8px 16px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .advisory__close:hover { background: var(--espresso); }
        .advisory__title {
            font-family: 'Bodoni Moda', serif;
            font-style: italic;
            font-size: clamp(1.6rem, 3vw, 2.1rem);
            color: var(--mahogany);
            margin: 4px 0 22px;
        }
        .advisory__lead {
            font-size: 0.9rem;
            font-weight: 300;
            line-height: 1.8;
            color: var(--espresso);
            margin: 0 0 14px;
            max-width: 46ch;
            margin-inline: auto;
            text-wrap: pretty;
        }
        .advisory__rule {
            width: 48px;
            height: 1px;
            background: var(--gold);
            opacity: 0.7;
            margin: 26px auto;
        }
        .advisory__channels {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 22px 28px;
            text-align: left;
            margin: 0 auto 8px;
            max-width: 440px;
        }
        .advisory__channel h4 {
            font-family: 'Poppins', sans-serif;
            font-size: 0.64rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: var(--gold);
            margin: 0 0 5px;
        }
        .advisory__channel p {
            font-size: 0.85rem;
            font-weight: 300;
            line-height: 1.6;
            color: var(--espresso);
            margin: 0;
        }
        .advisory__note {
            font-size: 0.82rem;
            font-weight: 400;
            line-height: 1.7;
            color: var(--mahogany);
            margin: 6px auto 0;
            max-width: 44ch;
            text-wrap: pretty;
        }
        .advisory__signoff {
            font-family: 'Pinyon Script', cursive;
            font-size: 1.5rem;
            color: var(--gold);
            margin: 26px 0 0;
            line-height: 1.2;
        }
        @media (max-width: 560px) {
            .advisory__card { padding: 48px 28px 36px; }
            .advisory__channels { grid-template-columns: 1fr; gap: 18px; text-align: center; }
        }

        /* === SCROLL REVEALS (JS-guarded) === */
        .js-ready .reveal {
            opacity: 0;
            transform: translateY(36px);
            transition: opacity 0.7s cubic-bezier(0.25,0.1,0.25,1),
                        transform 0.7s cubic-bezier(0.25,0.1,0.25,1);
        }
        .js-ready .reveal-left {
            opacity: 0; transform: translateX(-44px);
            transition: opacity 0.7s cubic-bezier(0.25,0.1,0.25,1),
                        transform 0.7s cubic-bezier(0.25,0.1,0.25,1);
        }
        .js-ready .reveal-right {
            opacity: 0; transform: translateX(44px);
            transition: opacity 0.7s cubic-bezier(0.25,0.1,0.25,1),
                        transform 0.7s cubic-bezier(0.25,0.1,0.25,1);
        }
        .reveal.in-view,
        .reveal-left.in-view,
        .reveal-right.in-view {
            opacity: 1 !important; transform: translate(0) !important;
        }
        .rooms-grid .room-card:nth-child(2) { transition-delay: 0.1s; }

        /* === RESPONSIVE === */
        @media (max-width: 960px) {
            .navbar { padding: 16px 28px; }
            .room-feature { flex-direction: column; min-height: auto; }
            .room-feature__image { flex: 0 0 320px; }
            .room-feature__details { padding: 36px 32px; }
            .split-layout { flex-direction: column; }
            .split-image { min-height: 300px; }
            .site-footer { grid-template-columns: 1fr 1fr; }
        }
        /* === ROOMS EMPTY STATE === */
        .rooms-empty {
            padding: 60px 0 20px;
            max-width: 520px;
        }
        .rooms-empty p {
            color: var(--espresso);
            font-size: 0.93rem;
            font-weight: 300;
            line-height: 1.85;
            margin: 0 0 28px;
        }

        @media (max-width: 640px) {
            .navbar { padding: 14px 20px; }
            .nav-links a:not(.btn-book-nav) { display: none; }
            .btn-book-nav { display: none !important; }
            .nav-hamburger { display: flex; }
            .nav-drawer { display: block; }
            .welcome-section { padding: 72px 24px; }
            .rooms-section { padding: 72px 20px; }
            .reviews-section { padding: 72px 20px; }
            .site-footer { grid-template-columns: 1fr; padding: 56px 20px 0; }
            .hero-overlay { padding: 0 6% 10%; }
        }
    </style>
</head>
<body>

<div class="advisory" id="advisory" role="dialog" aria-modal="true" aria-labelledby="advisoryTitle" aria-hidden="true">
    <div class="advisory__card">
        <button class="advisory__close" id="advisoryClose" aria-label="Close advisory">Close</button>
        <h2 class="advisory__title" id="advisoryTitle">Important Advisory</h2>
        <p class="advisory__lead">To ensure a safe and secure booking experience, please verify that you are booking through our official channels only.</p>

        <div class="advisory__rule"></div>

        <div class="advisory__channels">
            <div class="advisory__channel">
                <h4>Official Instagram</h4>
                <p>@amaristaycation</p>
            </div>
            <div class="advisory__channel">
                <h4>Official Facebook</h4>
                <p>facebook.com/amaristaycation</p>
            </div>
            <div class="advisory__channel">
                <h4>Official Email</h4>
                <p>amaristaycation08@gmail.com</p>
            </div>
            <div class="advisory__channel">
                <h4>Official Phone</h4>
                <p>(+63) 917-123-4567</p>
            </div>
        </div>

        <div class="advisory__rule"></div>

        <p class="advisory__note">Confirmations and estimates are provided via email only. We do not send bank details through social media or private messages. Please do not make payments to any personal or individual accounts.</p>
        <p class="advisory__signoff">we look forward to welcoming you</p>
    </div>
</div>

<nav class="navbar">
    <a href="index.php" class="nav-brand">
        <img src="assets/images/logo.png" alt="Amari Staycation Alabang" class="nav-logo" style="height:36px;width:auto;display:block;">
        <span class="visually-hidden" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);">Amari Staycation Alabang — Home</span>
    </a>
    <div class="nav-links">
        <a href="index.php">Home</a>
        <a href="about.php">About</a>
        <a href="#rooms">Rooms</a>
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
        <a href="about.php">About</a>
        <a href="#rooms" id="drawerRooms">Rooms</a>
        <a href="experience.php">Experience</a>
        <a href="gallery.php">Gallery</a>
        <a href="booking-form.php" class="drawer-book">Book a stay</a>
    </div>
</div>

<header class="hero">
    <div class="flash-overlay" id="flashOverlay"></div>
    <div class="slider-track" id="sliderTrack">
        <div class="slide">
            <img src="assets/images/amari/hero1.jpg" alt="Amari suite, warm interior with natural light">
        </div>
        <div class="slide">
            <img src="assets/images/mariah/hero1.jpg" alt="Mariah suite, curated furnishings">
        </div>
        <div class="slide">
            <img src="assets/images/ara/hero1.jpg" alt="Ara suite, serene private space">
        </div>
    </div>
    <div class="hero-overlay">
        <div class="hero-content">
            <h1 class="hero-title animate-title" id="heroTitle">Amari</h1>
            <p class="hero-sub">Alabang &middot; Staycation</p>
            <a href="#rooms" class="btn-hero">View accommodations</a>
        </div>
    </div>
    <div class="hero-arrows">
        <button class="hero-arrow" onclick="prevSlide()" aria-label="Previous room">&#8249;</button>
        <button class="hero-arrow" onclick="nextSlide()" aria-label="Next room">&#8250;</button>
    </div>
    <div class="hero-dots" id="heroDots">
        <button class="hero-dot active" onclick="goToSlide(0)" aria-label="Show Amari"></button>
        <button class="hero-dot" onclick="goToSlide(1)" aria-label="Show Mariah"></button>
        <button class="hero-dot" onclick="goToSlide(2)" aria-label="Show Ara"></button>
    </div>
</header>

<section class="welcome-section reveal">
    <span class="welcome-script">welcome to Amari</span>
    <h2>A place to come home to</h2>
    <div class="welcome-divider"></div>
    <p>Amari Alabang is a curated staycation property in Filinvest City. Three distinct spaces, each designed for comfort and rest, with every detail considered before you arrive.</p>
    <p>Warm service, clean rooms, and a quiet corner of the city that is entirely yours for the duration of your stay.</p>
</section>

<section class="split-layout">
    <div class="split-text reveal-left">
        <h2>Nestled in the city,<br>far from it</h2>
        <p>Designed around light, texture, and natural materials, each unit at Amari feels like a private residence. You set the pace of your stay.</p>
        <a href="about.php" class="split-link">Our story &#8250;</a>
    </div>
    <div class="split-image reveal-right"></div>
</section>

<section id="rooms" class="rooms-section">
    <div class="rooms-header reveal">
        <h2>Our Accommodations</h2>
        <p>Three spaces in Alabang, available for short stays and extended visits.</p>
    </div>

    <?php if (!empty($rooms)):
        $featured = $rooms[0];
        $ftitle   = trim(preg_replace('/[0-9]+/', '', $featured['title']));
        $fimg     = 'assets/images/' . strtolower($ftitle) . '/hero1.jpg';
    ?>
    <div class="room-feature reveal">
        <div class="room-feature__image">
            <img src="<?= htmlspecialchars($fimg) ?>"
                 alt="<?= htmlspecialchars($ftitle) ?> suite interior"
                 loading="lazy"
                 onerror="this.src='assets/images/pool.jpg'">
        </div>
        <div class="room-feature__details">
            <span class="room-tag" aria-hidden="true"></span>
            <h3><?= htmlspecialchars($ftitle) ?></h3>
            <p><?= htmlspecialchars(mb_substr($featured['description'], 0, 200)) ?>...</p>
            <div class="room-price">
                &#8369;<?= number_format($featured['price_per_night'], 0) ?>
                <span>&nbsp;per night</span>
            </div>
            <a href="room-details.php?id=<?= (int)$featured['id'] ?>" class="btn-room">View this suite</a>
        </div>
    </div>
    <?php else: ?>
    <div class="rooms-empty reveal">
        <p>Our suites are temporarily unavailable for online browsing. Please reach out directly and we will get back to you shortly.</p>
        <a href="booking-form.php" class="btn-room">Enquire about a stay</a>
    </div>
    <?php endif; ?>

    <?php if (count($rooms) > 1): ?>
    <div class="rooms-grid">
        <?php for ($i = 1; $i < count($rooms); $i++):
            $u      = $rooms[$i];
            $utitle = trim(preg_replace('/[0-9]+/', '', $u['title']));
            $uimg   = 'assets/images/' . strtolower($utitle) . '/hero1.jpg';
        ?>
        <div class="room-card reveal">
            <div class="room-card__image">
                <img src="<?= htmlspecialchars($uimg) ?>"
                     alt="<?= htmlspecialchars($utitle) ?> suite"
                     loading="lazy"
                     onerror="this.src='assets/images/pool.jpg'">
            </div>
            <div class="room-card__details">
                <h3><?= htmlspecialchars($utitle) ?></h3>
                <p><?= htmlspecialchars(mb_substr($u['description'], 0, 130)) ?>...</p>
                <div class="room-card-footer">
                    <div class="room-price" style="margin-bottom:0; font-size:1.2rem;">
                        &#8369;<?= number_format($u['price_per_night'], 0) ?>
                        <span>/ night</span>
                    </div>
                    <a href="room-details.php?id=<?= (int)$u['id'] ?>" class="btn-room-sm">View suite &#8250;</a>
                </div>
            </div>
        </div>
        <?php endfor; ?>
    </div>
    <?php endif; ?>
</section>

<section class="reviews-section">
    <h2 class="reveal">What guests say</h2>
    <div class="reviews-grid">
        <div class="reveal">
            <p class="review-quote">The property was so beautiful and well maintained. Great place for real relaxation. We will definitely come back.</p>
            <p class="review-author">Jeremiah P.</p>
        </div>
        <div class="reveal">
            <p class="review-quote">Beautiful space, very serene, and amazing service. The rooms were clean and spacious. Exactly what we needed.</p>
            <p class="review-author">Tep S.</p>
        </div>
        <div class="reveal">
            <p class="review-quote">The location in Alabang is perfect. Quiet, comfortable, and easy to get to. We will book again for sure.</p>
            <p class="review-author">Elena R.</p>
        </div>
    </div>
</section>

<footer class="site-footer">
    <div class="reveal">
        <p class="footer-brand-name">Amari</p>
        <p class="footer-brand-loc">Alabang</p>
        <p class="footer-tagline">A curated staycation property in Filinvest City, Alabang, Philippines.</p>
    </div>
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
    <div class="footer-bottom">
        &copy; 2026 Amari Staycation Alabang. All rights reserved.
    </div>
</footer>

<script>
    // Guard: only apply opacity-0 reveals when JS is active
    document.documentElement.classList.add('js-ready');

    // === ADVISORY MODAL ===
    const advisory      = document.getElementById('advisory');
    const advisoryClose = document.getElementById('advisoryClose');

    function openAdvisory() {
        advisory.classList.add('is-open');
        advisory.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        advisoryClose.focus();
    }
    function closeAdvisory() {
        advisory.classList.remove('is-open');
        advisory.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }
    // Show only on a fresh first visit OR a manual refresh —
    // not when navigating back to Home from another page in the same session.
    function shouldShowAdvisory() {
        // Detect how the page was reached (reload vs link navigation)
        let navType = '';
        const navEntries = performance.getEntriesByType && performance.getEntriesByType('navigation');
        if (navEntries && navEntries.length) {
            navType = navEntries[0].type;                 // 'navigate' | 'reload' | 'back_forward'
        } else if (performance.navigation) {
            navType = performance.navigation.type === 1 ? 'reload' : 'navigate'; // legacy fallback
        }

        if (navType === 'reload') return true;            // manual refresh → always show
        if (navType === 'back_forward') return false;     // returning via back/forward → skip
        // 'navigate': show only the first time this browser session
        return !sessionStorage.getItem('amariAdvisorySeen');
    }

    window.addEventListener('load', () => {
        if (shouldShowAdvisory()) {
            sessionStorage.setItem('amariAdvisorySeen', '1');
            setTimeout(openAdvisory, 450);
        }
    });
    advisoryClose.addEventListener('click', closeAdvisory);
    advisory.addEventListener('click', e => { if (e.target === advisory) closeAdvisory(); });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAdvisory(); });

    // === HERO CAROUSEL ===
    const titles       = ['Amari', 'Mariah', 'Ara'];
    let currentSlide   = 0;
    const sliderTrack  = document.getElementById('sliderTrack');
    const heroTitle    = document.getElementById('heroTitle');
    const flashOverlay = document.getElementById('flashOverlay');
    const dots         = document.querySelectorAll('.hero-dot');

    function updateCarousel() {
        sliderTrack.style.transform = `translateX(-${currentSlide * 33.3333}%)`;
        flashOverlay.classList.add('is-flashing');
        setTimeout(() => flashOverlay.classList.remove('is-flashing'), 420);
        heroTitle.classList.remove('animate-title');
        void heroTitle.offsetWidth;
        heroTitle.textContent = titles[currentSlide];
        heroTitle.classList.add('animate-title');
        dots.forEach((d, i) => d.classList.toggle('active', i === currentSlide));
    }
    function nextSlide()    { currentSlide = (currentSlide + 1) % titles.length; updateCarousel(); }
    function prevSlide()    { currentSlide = (currentSlide - 1 + titles.length) % titles.length; updateCarousel(); }
    function goToSlide(n)   { currentSlide = n; updateCarousel(); }

    // Swipe support
    let txStart = 0;
    sliderTrack.addEventListener('touchstart', e => { txStart = e.touches[0].clientX; }, { passive: true });
    sliderTrack.addEventListener('touchend',   e => {
        const dx = e.changedTouches[0].clientX - txStart;
        if (Math.abs(dx) > 50) dx < 0 ? nextSlide() : prevSlide();
    });

    // === SCROLL REVEALS (fire once, not two-way) ===
    const revealObs = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('in-view');
                revealObs.unobserve(entry.target);
            }
        });
    }, { rootMargin: '0px', threshold: 0.10 });

    document.querySelectorAll('.reveal, .reveal-left, .reveal-right')
            .forEach(el => revealObs.observe(el));

    // === HAMBURGER MENU ===
    const hamburger  = document.getElementById('navHamburger');
    const drawer     = document.getElementById('navDrawer');
    const backdrop   = document.getElementById('navBackdrop');
    const drawerRooms = document.getElementById('drawerRooms');

    function openDrawer() {
        hamburger.classList.add('is-open');
        hamburger.setAttribute('aria-expanded', 'true');
        drawer.classList.add('is-open');
        drawer.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
    }
    function closeDrawer() {
        hamburger.classList.remove('is-open');
        hamburger.setAttribute('aria-expanded', 'false');
        drawer.classList.remove('is-open');
        drawer.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
    }

    hamburger.addEventListener('click', () => {
        drawer.classList.contains('is-open') ? closeDrawer() : openDrawer();
    });
    backdrop.addEventListener('click', closeDrawer);
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });
    if (drawerRooms) drawerRooms.addEventListener('click', closeDrawer);
</script>
</body>
</html>
