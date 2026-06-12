<?php
include('includes/db-config.php');

// --- Fetch every bookable unit for the Rooms step ---
$units = [];
$res = $conn->query("SELECT * FROM units WHERE status = 'available' ORDER BY id ASC");
while ($u = $res->fetch_assoc()) {
    $clean  = trim(preg_replace('/[0-9]+/', '', $u['title']));
    $folder = strtolower($clean);
    $img    = 'assets/images/' . $folder . '/hero1.jpg';
    if (!file_exists($img)) { $img = 'assets/images/pool.jpg'; }
    $units[] = [
        'id'          => (int)$u['id'],
        'title'       => $u['title'],
        'clean'       => $clean,
        'price'       => (float)$u['price_per_night'],
        'reservation' => (float)$u['reservation_fee'],
        'description' => $u['description'],
        'amenities'   => $u['amenities'],
        'image'       => $img,
    ];
}

// Optional pre-selected unit (from room-details "Reserve dates")
$preselect = isset($_GET['unit_id']) ? (int)$_GET['unit_id'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve a Stay | Amari Alabang</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Bodoni+Moda:ital,opsz,wght@0,6..96,400;0,6..96,500;1,6..96,400;1,6..96,500&family=Pinyon+Script&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @media (prefers-reduced-motion: reduce) { *, *::before, *::after { transition: none !important; animation: none !important; } }

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
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: var(--espresso);
            background: var(--soft-white);
            min-height: 100vh;
            overflow-x: hidden;
        }
        h1, h2, h3, h4 { font-family: 'Bodoni Moda', serif; font-weight: 400; }
        img { display: block; max-width: 100%; }

        /* === TOP BAR === */
        .funnel-bar {
            background: var(--white);
            border-bottom: 1px solid rgba(86,67,40,0.08);
            box-shadow: 0 4px 18px rgba(80,37,21,0.06);
            position: sticky; top: 0; z-index: 100;
        }
        .funnel-bar__inner {
            max-width: 1080px; margin: 0 auto;
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 32px; gap: 24px;
        }
        .funnel-brand { text-decoration: none; line-height: 1; flex-shrink: 0; }
        .funnel-brand__name { font-family: 'Bodoni Moda', serif; font-style: italic; font-size: 1.3rem; color: var(--mahogany); margin: 0; }
        .funnel-brand__sub { font-size: 0.55rem; color: rgba(86,67,40,0.80); letter-spacing: 3px; text-transform: uppercase; margin: 2px 0 0; }

        /* === STEPPER === */
        .stepper { display: flex; align-items: center; gap: 0; flex: 1; justify-content: center; max-width: 620px; }
        .step { display: flex; align-items: center; gap: 9px; color: rgba(86,67,40,0.40); font-size: 0.72rem; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; white-space: nowrap; }
        .step__num {
            width: 24px; height: 24px; border-radius: 50%;
            border: 1.5px solid rgba(86,67,40,0.25);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.72rem; font-weight: 700; flex-shrink: 0;
            transition: all 0.3s;
        }
        .step.is-active { color: var(--mahogany); }
        .step.is-active .step__num { border-color: var(--mahogany); background: var(--mahogany); color: var(--white); }
        .step.is-done { color: var(--gold); }
        .step.is-done .step__num { border-color: var(--gold); background: var(--gold); color: var(--white); }
        .step__line { flex: 1; height: 1px; background: rgba(86,67,40,0.18); min-width: 18px; margin: 0 12px; }
        @media (max-width: 760px) {
            .step__label { display: none; }
            .step__line { min-width: 12px; margin: 0 6px; }
            .funnel-bar__inner { padding: 14px 18px; }
        }

        /* === STAGE === */
        .stage { max-width: 1080px; margin: 0 auto; padding: 40px 32px 80px; }
        .panel { display: none; animation: fadeIn 0.45s cubic-bezier(0.25,0.1,0.25,1); }
        .panel.is-active { display: block; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(16px); } to { opacity: 1; transform: translateY(0); } }

        /* Range summary header (Rooms/Rates) */
        .range-head { display: flex; align-items: center; justify-content: center; gap: 16px; margin-bottom: 40px; text-align: center; }
        .range-head__back {
            width: 40px; height: 40px; border-radius: 50%;
            border: 1px solid rgba(86,67,40,0.20); background: var(--white);
            color: var(--espresso); font-size: 1.1rem; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0; transition: border-color 0.2s, color 0.2s;
        }
        .range-head__back:hover { border-color: var(--gold); color: var(--mahogany); }
        .range-head__dates { font-family: 'Bodoni Moda', serif; font-style: italic; font-size: 1.35rem; color: var(--mahogany); margin: 0; line-height: 1.2; }
        .range-head__meta { font-size: 0.78rem; font-weight: 300; color: var(--espresso); margin: 4px 0 0; letter-spacing: 0.3px; }

        /* === STEP 1: STAY === */
        .stay-card {
            background: var(--white); max-width: 560px; margin: 0 auto;
            box-shadow: 0 20px 60px var(--shadow-md);
        }
        .stay-card__head { background: var(--mahogany); color: var(--white); padding: 44px 48px 40px; text-align: center; }
        .stay-script { font-family: 'Pinyon Script', cursive; font-size: 1.7rem; color: var(--gold); display: block; line-height: 1; margin-bottom: 6px; }
        .stay-card__head h2 { font-style: italic; font-size: clamp(1.7rem, 3.4vw, 2.3rem); color: var(--white); margin: 0; line-height: 1.1; }
        .stay-card__body { padding: 40px 48px 48px; }
        label { display: block; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 1.5px; color: var(--espresso); margin-bottom: 11px; font-weight: 700; }
        .field { margin-bottom: 28px; }
        .field-row { display: flex; gap: 28px; }
        .field-row .field { flex: 1; }
        input, select, textarea {
            width: 100%; padding: 13px 0; border: none;
            border-bottom: 1.5px solid rgba(86,67,40,0.18);
            font-family: 'Poppins', sans-serif; font-size: 0.95rem;
            color: var(--espresso); background: transparent; border-radius: 0;
            -webkit-appearance: none; appearance: none; transition: border-color 0.25s;
        }
        select { cursor: pointer; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='10' height='6' viewBox='0 0 10 6'%3E%3Cpath d='M1 1l4 4 4-4' stroke='%23564328' stroke-width='1.5' fill='none'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right center; }
        input::placeholder, textarea::placeholder { color: rgba(86,67,40,0.45); }
        input:focus, select:focus, textarea:focus { outline: none; border-bottom-color: var(--gold); }
        textarea { resize: vertical; min-height: 84px; }
        .field-error { color: #a8341f; font-size: 0.78rem; margin-top: 8px; display: none; }
        .field-error.show { display: block; }

        /* Buttons */
        .btn-primary {
            background: var(--mahogany); color: var(--white); border: none;
            padding: 18px; width: 100%; text-transform: uppercase;
            letter-spacing: 2px; font-family: 'Poppins', sans-serif;
            font-weight: 700; font-size: 0.82rem; cursor: pointer;
            transition: background 0.25s; margin-top: 8px;
        }
        .btn-primary:hover { background: var(--espresso); }
        .btn-primary:disabled { background: rgba(86,67,40,0.30); cursor: not-allowed; }

        /* === STEP 2: ROOMS === */
        .rooms-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 28px; }
        .rcard { background: var(--white); box-shadow: 0 10px 34px var(--shadow-sm); display: flex; flex-direction: column; overflow: hidden; transition: box-shadow 0.3s, transform 0.3s; }
        .rcard.is-available:hover { box-shadow: 0 18px 48px var(--shadow-md); transform: translateY(-3px); }
        .rcard__media { position: relative; height: 210px; overflow: hidden; background: var(--espresso); }
        .rcard__media img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.7s cubic-bezier(0.25,0.1,0.25,1); }
        .rcard.is-available:hover .rcard__media img { transform: scale(1.05); }
        .rcard__badge { position: absolute; top: 14px; right: 0; background: #a8341f; color: #fff; font-size: 0.66rem; font-weight: 700; letter-spacing: 1px; text-transform: uppercase; padding: 6px 16px; }
        .rcard.is-unavailable .rcard__media img { filter: grayscale(0.7) brightness(0.82); }
        .rcard__body { padding: 24px 26px 26px; display: flex; flex-direction: column; flex: 1; }
        .rcard__title { font-style: italic; font-size: 1.5rem; color: var(--mahogany); margin: 0 0 6px; line-height: 1.1; }
        .rcard__guests { font-size: 0.78rem; font-weight: 300; color: var(--espresso); margin: 0 0 18px; letter-spacing: 0.3px; }
        .rcard__price { font-family: 'Bodoni Moda', serif; font-size: 1.4rem; color: var(--mahogany); margin: auto 0 4px; line-height: 1; }
        .rcard__price small { font-family: 'Poppins', sans-serif; font-size: 0.72rem; font-weight: 300; color: var(--espresso); }
        .rcard__from { font-size: 0.68rem; text-transform: uppercase; letter-spacing: 1px; color: var(--gold); font-weight: 700; margin: 0 0 2px; }
        .rcard__btn {
            margin-top: 20px; background: var(--mahogany); color: var(--white);
            border: none; padding: 13px; width: 100%; text-transform: uppercase;
            letter-spacing: 1.5px; font-family: 'Poppins', sans-serif;
            font-weight: 700; font-size: 0.76rem; cursor: pointer; transition: background 0.2s;
        }
        .rcard__btn:hover { background: var(--gold); }
        .rcard__unavail { margin-top: auto; padding-top: 20px; font-size: 0.8rem; font-weight: 300; color: rgba(86,67,40,0.55); font-style: italic; }

        /* === STEP 3: RATES === */
        .rates-wrap { display: grid; grid-template-columns: 1.1fr 1fr; gap: 40px; align-items: start; max-width: 960px; margin: 0 auto; }
        .rates-media { box-shadow: 0 14px 44px var(--shadow-md); }
        .rates-media img { width: 100%; height: 320px; object-fit: cover; }
        .rates-media__cap { background: var(--white); padding: 24px 28px; }
        .rates-media__cap h3 { font-style: italic; font-size: 1.6rem; color: var(--mahogany); margin: 0 0 10px; }
        .rates-media__cap p { font-size: 0.88rem; font-weight: 300; line-height: 1.8; color: var(--espresso); margin: 0; }
        .rate-card { background: var(--white); padding: 32px 34px; box-shadow: 0 14px 44px var(--shadow-md); }
        .rate-card__tag { font-size: 0.68rem; text-transform: uppercase; letter-spacing: 1.5px; color: var(--gold); font-weight: 700; margin: 0 0 6px; }
        .rate-card__name { font-style: italic; font-size: 1.5rem; color: var(--mahogany); margin: 0 0 24px; }
        .rate-line { display: flex; justify-content: space-between; align-items: baseline; padding: 13px 0; border-bottom: 1px solid rgba(86,67,40,0.10); font-size: 0.9rem; font-weight: 300; }
        .rate-line span:last-child { font-weight: 600; color: var(--mahogany); }
        .rate-total { display: flex; justify-content: space-between; align-items: baseline; padding-top: 22px; margin-top: 6px; }
        .rate-total__label { font-size: 0.74rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 700; color: var(--espresso); }
        .rate-total__amt { font-family: 'Bodoni Moda', serif; font-size: 1.9rem; color: var(--mahogany); line-height: 1; }
        .rate-card .btn-primary { margin-top: 28px; }
        @media (max-width: 760px) { .rates-wrap { grid-template-columns: 1fr; } .rates-media img { height: 240px; } }

        /* === STEP 4: CHECKOUT === */
        .checkout-wrap { max-width: 760px; margin: 0 auto; display: flex; flex-direction: column; gap: 22px; }
        .co-card { background: var(--white); box-shadow: 0 10px 34px var(--shadow-sm); padding: 30px 34px; }
        .co-card__head { display: flex; align-items: baseline; justify-content: space-between; gap: 14px; border-bottom: 1px solid rgba(86,67,40,0.10); padding-bottom: 18px; margin-bottom: 22px; }
        .co-room-label { font-size: 0.66rem; text-transform: uppercase; letter-spacing: 2px; color: var(--gold); font-weight: 700; margin: 0 0 4px; }
        .co-room-name { font-style: italic; font-size: 1.5rem; color: var(--mahogany); margin: 0; }
        .co-summary { display: grid; grid-template-columns: 1fr 1fr; gap: 22px; }
        .co-summary h4 { font-family: 'Poppins', sans-serif; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1.5px; color: var(--espresso); font-weight: 700; margin: 0 0 12px; }
        .co-summary p { font-size: 0.9rem; font-weight: 300; line-height: 1.7; color: var(--espresso); margin: 0; }
        .co-summary .price-row { display: flex; justify-content: space-between; font-size: 0.9rem; padding: 6px 0; }
        .co-summary .price-row.total { border-top: 1px solid rgba(86,67,40,0.12); margin-top: 8px; padding-top: 12px; font-weight: 700; color: var(--mahogany); }
        .co-summary .price-row.total b { font-family: 'Bodoni Moda', serif; font-size: 1.2rem; font-weight: 400; }
        @media (max-width: 560px) { .co-summary { grid-template-columns: 1fr; } .field-row { flex-direction: column; gap: 0; } }

        .agree { display: flex; align-items: flex-start; gap: 11px; margin: 4px 0 22px; font-size: 0.84rem; font-weight: 300; line-height: 1.5; }
        .agree input { width: auto; padding: 0; margin-top: 3px; flex-shrink: 0; accent-color: var(--mahogany); }
        .total-bar { background: var(--white); box-shadow: 0 10px 34px var(--shadow-sm); padding: 22px 34px; display: flex; align-items: center; justify-content: space-between; }
        .total-bar__label { font-size: 0.74rem; text-transform: uppercase; letter-spacing: 1.5px; font-weight: 700; color: var(--espresso); }
        .total-bar__amt { font-family: 'Bodoni Moda', serif; font-size: 1.8rem; color: var(--mahogany); }

        /* === FLATPICKR — BRAND THEMING === */
        .flatpickr-calendar { border-radius: 0 !important; box-shadow: 0 12px 40px var(--shadow-md) !important; font-family: 'Poppins', sans-serif !important; border: 1px solid rgba(86,67,40,0.12) !important; }
        .flatpickr-months .flatpickr-month, .flatpickr-weekdays { background: var(--mahogany) !important; }
        .flatpickr-current-month, .flatpickr-current-month .flatpickr-monthDropdown-months, .flatpickr-current-month input.cur-year { color: var(--white) !important; }
        .flatpickr-weekday { color: rgba(255,255,255,0.60) !important; font-size: 0.72rem !important; }
        span.flatpickr-next-month svg path, span.flatpickr-prev-month svg path { fill: rgba(255,255,255,0.75) !important; }
        .flatpickr-day { border-radius: 0 !important; font-size: 0.82rem !important; }
        .flatpickr-day.today:not(.selected) { border-color: var(--gold) !important; color: var(--mahogany) !important; font-weight: 600; }
        .flatpickr-day:hover:not(.disabled):not(.selected):not(.prevMonthDay):not(.nextMonthDay) { background: rgba(194,166,110,0.18) !important; border-color: transparent !important; }
        .flatpickr-day.selected, .flatpickr-day.selected:hover, .flatpickr-day.startRange, .flatpickr-day.endRange { background: var(--mahogany) !important; border-color: var(--mahogany) !important; color: var(--white) !important; }
        .flatpickr-day.disabled, .flatpickr-day.disabled:hover { color: rgba(86,67,40,0.22) !important; text-decoration: line-through; }
    </style>
</head>
<body>

<header class="funnel-bar">
    <div class="funnel-bar__inner">
        <a href="index.php" class="funnel-brand">
            <img src="assets/images/logo.png" alt="Amari Staycation Alabang" class="nav-logo" style="height:34px;width:auto;display:block;">
        </a>
        <nav class="stepper" aria-label="Booking progress">
            <div class="step is-active" data-step="1"><span class="step__num">1</span><span class="step__label">Stay</span></div>
            <span class="step__line"></span>
            <div class="step" data-step="2"><span class="step__num">2</span><span class="step__label">Rooms</span></div>
            <span class="step__line"></span>
            <div class="step" data-step="3"><span class="step__num">3</span><span class="step__label">Rates</span></div>
            <span class="step__line"></span>
            <div class="step" data-step="4"><span class="step__num">4</span><span class="step__label">Checkout</span></div>
        </nav>
        <span style="width: 80px;" aria-hidden="true"></span>
    </div>
</header>

<div class="stage">

    <!-- STEP 1: STAY -->
    <section class="panel is-active" id="panel-1">
        <div class="stay-card">
            <div class="stay-card__head">
                <span class="stay-script">reserve a stay</span>
                <h2>When will you arrive?</h2>
            </div>
            <div class="stay-card__body">
                <div class="field-row">
                    <div class="field">
                        <label for="checkin">Arrival</label>
                        <input type="text" id="checkin" placeholder="Select date" readonly>
                    </div>
                    <div class="field">
                        <label for="checkout">Departure</label>
                        <input type="text" id="checkout" placeholder="Select date" readonly>
                    </div>
                </div>
                <p class="field-error" id="dateError">Please choose your arrival and departure dates.</p>
                <div class="field-row">
                    <div class="field">
                        <label for="adults">Adults</label>
                        <select id="adults">
                            <?php for ($i = 1; $i <= 8; $i++): ?>
                            <option value="<?= $i ?>" <?= $i === 2 ? 'selected' : '' ?>><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="field">
                        <label for="children">Children</label>
                        <select id="children">
                            <?php for ($i = 0; $i <= 6; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>
                <button type="button" class="btn-primary" id="toRooms">Next &#8250;</button>
            </div>
        </div>
    </section>

    <!-- STEP 2: ROOMS -->
    <section class="panel" id="panel-2">
        <div class="range-head">
            <button type="button" class="range-head__back" data-back="1" aria-label="Back to dates">&#8249;</button>
            <div>
                <p class="range-head__dates" id="roomsDates">&nbsp;</p>
                <p class="range-head__meta" id="roomsMeta">&nbsp;</p>
            </div>
        </div>
        <div class="rooms-grid" id="roomsGrid"></div>
    </section>

    <!-- STEP 3: RATES -->
    <section class="panel" id="panel-3">
        <div class="range-head">
            <button type="button" class="range-head__back" data-back="2" aria-label="Back to rooms">&#8249;</button>
            <div>
                <p class="range-head__dates" id="ratesDates">&nbsp;</p>
                <p class="range-head__meta" id="ratesMeta">&nbsp;</p>
            </div>
        </div>
        <div class="rates-wrap" id="ratesWrap"></div>
    </section>

    <!-- STEP 4: CHECKOUT -->
    <section class="panel" id="panel-4">
        <div class="range-head">
            <button type="button" class="range-head__back" data-back="3" aria-label="Back to rates">&#8249;</button>
            <div>
                <p class="range-head__dates">Almost there</p>
                <p class="range-head__meta">Confirm your details to complete the reservation</p>
            </div>
        </div>

        <form class="checkout-wrap" id="bookingForm" action="process-booking.php" method="POST" novalidate>
            <input type="hidden" name="unit_id"    id="f_unit_id">
            <input type="hidden" name="checkin"    id="f_checkin">
            <input type="hidden" name="checkout"   id="f_checkout">
            <input type="hidden" name="num_adults" id="f_adults">
            <input type="hidden" name="num_children" id="f_children">

            <div class="co-card">
                <div class="co-card__head">
                    <div>
                        <p class="co-room-label">Your suite</p>
                        <p class="co-room-name" id="coRoomName">&nbsp;</p>
                    </div>
                </div>
                <div class="co-summary">
                    <div>
                        <h4>Booking details</h4>
                        <p id="coDetails">&nbsp;</p>
                    </div>
                    <div>
                        <h4>Price summary</h4>
                        <div class="price-row"><span id="coNightsLabel">&nbsp;</span><span id="coNightsAmt">&nbsp;</span></div>
                        <div class="price-row total"><span>Total</span><b id="coTotal">&nbsp;</b></div>
                        <p style="font-size:0.74rem; color:rgba(86,67,40,0.55); margin:8px 0 0;">Inclusive of taxes &amp; fees</p>
                    </div>
                </div>
            </div>

            <div class="co-card">
                <h4 style="font-family:'Poppins'; font-size:0.7rem; text-transform:uppercase; letter-spacing:1.5px; color:var(--espresso); font-weight:700; margin:0 0 22px;">Contact information</h4>
                <div class="field-row">
                    <div class="field">
                        <label for="title">Title</label>
                        <select id="title" name="guest_title">
                            <option value="">—</option>
                            <option>Mr</option><option>Ms</option><option>Mrs</option><option>Mx</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="first_name">First name *</label>
                        <input type="text" id="first_name" name="first_name" placeholder="Juan" autocomplete="given-name">
                    </div>
                    <div class="field">
                        <label for="last_name">Last name *</label>
                        <input type="text" id="last_name" name="last_name" placeholder="Dela Cruz" autocomplete="family-name">
                    </div>
                </div>
                <div class="field-row">
                    <div class="field">
                        <label for="email">Email *</label>
                        <input type="email" id="email" name="email" placeholder="juan@example.com" autocomplete="email">
                    </div>
                    <div class="field">
                        <label for="phone">Phone *</label>
                        <input type="tel" id="phone" name="phone" placeholder="0917-XXX-XXXX" autocomplete="tel">
                    </div>
                </div>
                <div class="field" style="margin-bottom:0;">
                    <label for="requests">Notes (optional)</label>
                    <textarea id="requests" name="requests" placeholder="Anniversary setup, early check-in, dietary needs..."></textarea>
                </div>
                <p class="field-error" id="formError">Please complete all required fields and accept the policy.</p>
            </div>

            <div class="total-bar">
                <span class="total-bar__label">Total &middot; incl. taxes &amp; fees</span>
                <span class="total-bar__amt" id="barTotal">&nbsp;</span>
            </div>

            <div class="co-card">
                <label class="agree" for="agree" style="text-transform:none; letter-spacing:0; font-weight:300; margin:0;">
                    <input type="checkbox" id="agree">
                    <span>I agree with the property's house rules and reservation policy, and confirm the details above are correct.</span>
                </label>
            </div>

            <button type="submit" class="btn-primary" id="bookBtn">Confirm reservation</button>
        </form>
    </section>

</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    const UNITS = <?= json_encode($units) ?>;
    const PRESELECT = <?= $preselect ?>;
    const peso = n => '₱' + Number(n).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    const fmtDate = s => { const d = new Date(s + 'T00:00:00'); return d.toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }); };

    const state = { checkin: '', checkout: '', nights: 0, adults: 2, children: 0, unit: null, availability: {} };

    // === STEP NAVIGATION ===
    const panels = document.querySelectorAll('.panel');
    const steps  = document.querySelectorAll('.step');
    function goStep(n) {
        panels.forEach(p => p.classList.toggle('is-active', p.id === 'panel-' + n));
        steps.forEach(s => {
            const sn = +s.dataset.step;
            s.classList.toggle('is-active', sn === n);
            s.classList.toggle('is-done', sn < n);
        });
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    document.querySelectorAll('[data-back]').forEach(b => b.addEventListener('click', () => goStep(+b.dataset.back)));

    // === STEP 1: DATE PICKERS ===
    const checkoutPicker = flatpickr('#checkout', { minDate: 'today', dateFormat: 'Y-m-d' });
    flatpickr('#checkin', {
        minDate: 'today', dateFormat: 'Y-m-d',
        onChange: (sel, str) => {
            const next = new Date(str); next.setDate(next.getDate() + 1);
            checkoutPicker.set('minDate', next);
            if (checkoutPicker.selectedDates[0] && checkoutPicker.selectedDates[0] <= sel[0]) checkoutPicker.clear();
            checkoutPicker.open();
        }
    });

    document.getElementById('toRooms').addEventListener('click', loadRooms);

    function loadRooms() {
        const ci = document.getElementById('checkin').value;
        const co = document.getElementById('checkout').value;
        const err = document.getElementById('dateError');
        if (!ci || !co || new Date(co) <= new Date(ci)) { err.classList.add('show'); return; }
        err.classList.remove('show');

        state.checkin = ci; state.checkout = co;
        state.nights = Math.round((new Date(co) - new Date(ci)) / 86400000);
        state.adults = +document.getElementById('adults').value;
        state.children = +document.getElementById('children').value;

        const btn = document.getElementById('toRooms');
        btn.disabled = true; btn.textContent = 'Checking availability...';

        fetch('check-availability.php?checkin=' + ci + '&checkout=' + co)
            .then(r => r.json())
            .then(data => { state.availability = (data && data.ok) ? data.units : {}; renderRooms(); goStep(2); })
            .catch(() => { state.availability = {}; renderRooms(); goStep(2); })
            .finally(() => { btn.disabled = false; btn.innerHTML = 'Next &#8250;'; });
    }

    function rangeMeta() {
        const g = state.adults + ' Adult' + (state.adults > 1 ? 's' : '') + (state.children ? ', ' + state.children + ' Child' + (state.children > 1 ? 'ren' : '') : '');
        return state.nights + ' night' + (state.nights > 1 ? 's' : '') + ' · ' + g;
    }

    // === STEP 2: ROOMS ===
    function renderRooms() {
        document.getElementById('roomsDates').textContent = fmtDate(state.checkin) + ' — ' + fmtDate(state.checkout);
        document.getElementById('roomsMeta').textContent = rangeMeta();
        const grid = document.getElementById('roomsGrid');
        grid.innerHTML = '';
        UNITS.forEach(u => {
            const free = state.availability[u.id] !== false;
            const card = document.createElement('div');
            card.className = 'rcard ' + (free ? 'is-available' : 'is-unavailable');
            card.innerHTML = `
                <div class="rcard__media">
                    <img src="${u.image}" alt="${u.clean} suite" onerror="this.src='assets/images/pool.jpg'">
                    ${free ? '' : '<span class="rcard__badge">Unavailable</span>'}
                </div>
                <div class="rcard__body">
                    <h3 class="rcard__title">${u.clean}</h3>
                    <p class="rcard__guests">Private suite · ${state.adults + state.children} guest${(state.adults + state.children) > 1 ? 's' : ''}</p>
                    ${free ? `
                        <p class="rcard__from">From</p>
                        <p class="rcard__price">${peso(u.price)} <small>per night</small></p>
                        <button type="button" class="rcard__btn" data-unit="${u.id}">Show rates</button>
                    ` : `<p class="rcard__unavail">Not available for the selected dates. Try adjusting your stay.</p>`}
                </div>`;
            grid.appendChild(card);
        });
        grid.querySelectorAll('.rcard__btn').forEach(b => b.addEventListener('click', () => selectUnit(+b.dataset.unit)));
    }

    // === STEP 3: RATES ===
    function selectUnit(id) {
        state.unit = UNITS.find(u => u.id === id);
        renderRates();
        goStep(3);
    }
    function renderRates() {
        const u = state.unit, total = u.price * state.nights;
        document.getElementById('ratesDates').textContent = fmtDate(state.checkin) + ' — ' + fmtDate(state.checkout);
        document.getElementById('ratesMeta').textContent = rangeMeta();
        const desc = (u.description || '').slice(0, 240) + ((u.description || '').length > 240 ? '…' : '');
        document.getElementById('ratesWrap').innerHTML = `
            <div class="rates-media">
                <img src="${u.image}" alt="${u.clean} suite" onerror="this.src='assets/images/pool.jpg'">
                <div class="rates-media__cap">
                    <h3>${u.clean}</h3>
                    <p>${desc}</p>
                </div>
            </div>
            <div class="rate-card">
                <p class="rate-card__tag">Seasonal Rate</p>
                <h3 class="rate-card__name">${u.clean} Suite</h3>
                <div class="rate-line"><span>${peso(u.price)} × ${state.nights} night${state.nights > 1 ? 's' : ''}</span><span>${peso(total)}</span></div>
                <div class="rate-line"><span>Taxes &amp; fees</span><span>Included</span></div>
                <div class="rate-total">
                    <span class="rate-total__label">Total</span>
                    <span class="rate-total__amt">${peso(total)}</span>
                </div>
                <button type="button" class="btn-primary" id="toCheckout">Select &amp; continue</button>
            </div>`;
        document.getElementById('toCheckout').addEventListener('click', renderCheckout);
    }

    // === STEP 4: CHECKOUT ===
    function renderCheckout() {
        const u = state.unit, total = u.price * state.nights;
        document.getElementById('coRoomName').textContent = u.clean + ' Suite';
        document.getElementById('coDetails').innerHTML =
            fmtDate(state.checkin) + ' — ' + fmtDate(state.checkout) + '<br>' +
            state.nights + ' night' + (state.nights > 1 ? 's' : '') + '<br>' +
            rangeMeta().split(' · ')[1];
        document.getElementById('coNightsLabel').textContent = peso(u.price) + ' × ' + state.nights + ' night' + (state.nights > 1 ? 's' : '');
        document.getElementById('coNightsAmt').textContent = peso(total);
        document.getElementById('coTotal').textContent = peso(total);
        document.getElementById('barTotal').textContent = peso(total);

        document.getElementById('f_unit_id').value  = u.id;
        document.getElementById('f_checkin').value   = state.checkin;
        document.getElementById('f_checkout').value  = state.checkout;
        document.getElementById('f_adults').value    = state.adults;
        document.getElementById('f_children').value  = state.children;
        goStep(4);
    }

    // === SUBMIT VALIDATION ===
    document.getElementById('bookingForm').addEventListener('submit', e => {
        const req = ['first_name', 'last_name', 'email', 'phone'];
        const ok = req.every(id => document.getElementById(id).value.trim() !== '') && document.getElementById('agree').checked;
        if (!ok) { e.preventDefault(); document.getElementById('formError').classList.add('show'); return; }
        document.getElementById('formError').classList.remove('show');
        const btn = document.getElementById('bookBtn');
        btn.disabled = true; btn.textContent = 'Reserving...';
    });

    // Pre-select a suite if arriving from a room page (just remembers it for the Rooms step highlight)
    if (PRESELECT) { /* user still picks dates first; suite is available in the Rooms grid */ }
</script>
</body>
</html>
