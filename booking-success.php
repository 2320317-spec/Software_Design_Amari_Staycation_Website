<?php
$guest_name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Received | Amari Alabang</title>
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

        @media (prefers-reduced-motion: reduce) {
            .success-card, .check-mark { animation: none !important; opacity: 1 !important; transform: none !important; }
        }

        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: var(--soft-white);
            color: var(--espresso);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 32px 20px;
        }

        /* === BRAND MARK === */
        .page-brand {
            text-align: center;
            margin-bottom: 40px;
        }
        .page-brand__name {
            font-family: 'Bodoni Moda', serif;
            font-style: italic;
            font-size: 1.5rem;
            color: var(--mahogany);
            text-decoration: none;
            display: block;
            line-height: 1;
        }
        .page-brand__sub {
            font-family: 'Poppins', sans-serif;
            font-size: 0.6rem;
            color: var(--espresso);
            letter-spacing: 3px;
            text-transform: uppercase;
            opacity: 0.5;
            margin-top: 4px;
            display: block;
        }

        /* === CARD === */
        .success-card {
            background: var(--white);
            padding: 64px 52px;
            box-shadow: 0 16px 56px var(--shadow-md);
            text-align: center;
            max-width: 480px;
            width: 100%;
            opacity: 0;
            transform: translateY(20px);
            animation: cardIn 0.6s cubic-bezier(0.25,0.1,0.25,1) 0.1s forwards;
        }
        @keyframes cardIn {
            to { opacity: 1; transform: translateY(0); }
        }

        /* === CHECK MARK === */
        .success-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: rgba(194,166,110,0.10);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 32px;
        }
        .check-mark {
            width: 28px;
            height: 28px;
            stroke: var(--gold);
            stroke-width: 2.5;
            fill: none;
            stroke-linecap: round;
            stroke-linejoin: round;
            stroke-dasharray: 40;
            stroke-dashoffset: 40;
            animation: drawCheck 0.5s cubic-bezier(0.25,0.1,0.25,1) 0.55s forwards;
        }
        @keyframes drawCheck {
            to { stroke-dashoffset: 0; }
        }

        /* === DIVIDER === */
        .card-divider {
            width: 40px;
            height: 1px;
            background: var(--gold);
            opacity: 0.5;
            margin: 0 auto 28px;
        }

        /* === TYPOGRAPHY === */
        .thanks-script {
            font-family: 'Pinyon Script', cursive;
            font-size: clamp(1.4rem, 3vw, 1.9rem);
            color: var(--gold);
            display: block;
            margin: 0 0 8px;
            line-height: 1.2;
        }
        h1 {
            font-family: 'Bodoni Moda', serif;
            font-style: italic;
            font-weight: 400;
            color: var(--mahogany);
            font-size: clamp(1.5rem, 3vw, 2rem);
            margin: 0 0 20px;
            text-wrap: balance;
            line-height: 1.15;
        }
        p {
            color: var(--espresso);
            font-weight: 300;
            line-height: 1.85;
            font-size: 0.93rem;
            margin: 0 0 36px;
            text-wrap: pretty;
            max-width: 52ch;
            margin-inline: auto;
            margin-bottom: 36px;
        }

        /* === BUTTON === */
        .btn-home {
            display: inline-block;
            background: var(--mahogany);
            color: var(--white);
            padding: 13px 32px;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.76rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            transition: background 0.2s;
        }
        .btn-home:hover { background: var(--gold); }

        @media (max-width: 540px) {
            .success-card { padding: 48px 28px; }
        }
    </style>
</head>
<body>

    <a href="index.php" class="page-brand">
        <span class="page-brand__name">Amari</span>
        <span class="page-brand__sub">Alabang</span>
    </a>

    <div class="success-card">
        <div class="success-icon">
            <svg class="check-mark" viewBox="0 0 28 28" aria-hidden="true">
                <polyline points="5,15 11,21 23,8"/>
            </svg>
        </div>

        <span class="thanks-script">thank you</span>
        <h1>Reservation received</h1>
        <div class="card-divider"></div>
        <p>Thank you, <strong><?= $guest_name ?></strong>. Your reservation request has been received. Our team will review your dates and send a confirmation to your email shortly.</p>
        <a href="index.php" class="btn-home">Return to home</a>
    </div>

</body>
</html>
