<?php
include('includes/db-config.php');

// 1. Catch the Booking ID from the URL
if(isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    /* 2. THE SQL JOIN COMMAND
     * This is advanced SQL! We need data from the 'bookings' table AND the 'units' table.
     * We join them together based on the unit_id so we have all the info for the receipt.
     */
    $stmt = $conn->prepare("
        SELECT b.*, u.title, u.price_per_night, u.image_path 
        FROM bookings b 
        JOIN units u ON b.unit_id = u.id 
        WHERE b.id = ?
    ");
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $invoice = $result->fetch_assoc();

    if(!$invoice) {
        header("Location: index.php");
        exit();
    }

    // 3. THE ENGINEERING MATH (Calculate Total Price)
    $checkin_date = new DateTime($invoice['check_in']);
    $checkout_date = new DateTime($invoice['check_out']);
    
    // Calculate difference in days
    $nights = $checkin_date->diff($checkout_date)->days;
    if ($nights == 0) $nights = 1; // Failsafe: minimum 1 night

    $total_price = $nights * $invoice['price_per_night'];

} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation - Amari Alabang</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        /* Your Custom Luxury Palette */
        :root {
            --amari-mahogany: #502515;
            --amari-oak: #564328;
            --amari-gold: #c2a66e;
            --amari-bg: #faf6f6;
            --amari-white: #ffffff;
        }

        body { 
            margin: 0; 
            font-family: 'Lato', sans-serif; 
            background-color: var(--amari-bg); 
            color: var(--amari-oak); 
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }

        h1, h2, h3 { font-family: 'Playfair Display', serif; margin: 0; }

        /* The Receipt Card */
        .invoice-card {
            background: var(--amari-white);
            width: 100%;
            max-width: 700px;
            box-shadow: 0 15px 35px rgba(80, 37, 21, 0.08); /* Tinted shadow */
            border-radius: 4px;
            overflow: hidden;
            border-top: 6px solid var(--amari-gold);
        }

        /* Header Section */
        .invoice-header {
            text-align: center;
            padding: 40px 30px;
            border-bottom: 1px solid #f0eaea;
        }
        .invoice-header h1 {
            color: var(--amari-mahogany);
            font-size: 2rem;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .invoice-header p {
            color: var(--amari-gold);
            font-style: italic;
            font-size: 1.1rem;
            margin-top: 10px;
            font-family: 'Playfair Display', serif;
        }

        /* Body Section */
        .invoice-body { padding: 40px 50px; }
        
        .booking-ref {
            text-align: center;
            background: var(--amari-bg);
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 30px;
            color: var(--amari-oak);
            font-size: 0.9rem;
            letter-spacing: 1px;
            border: 1px dashed var(--amari-gold);
        }
        .booking-ref strong { color: var(--amari-mahogany); font-size: 1.2rem; display: block; margin-top: 5px; }

        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .detail-box label {
            display: block;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #999;
            margin-bottom: 5px;
        }
        .detail-box div {
            color: var(--amari-mahogany);
            font-weight: bold;
            font-size: 1.1rem;
        }

        /* Line Items */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .invoice-table th {
            text-align: left;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--amari-bg);
            color: var(--amari-oak);
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
        }
        .invoice-table td {
            padding: 20px 0;
            border-bottom: 1px solid #f0eaea;
            color: var(--amari-mahogany);
        }
        .item-name { font-weight: bold; font-size: 1.1rem; }
        .item-desc { font-size: 0.85rem; color: #888; display: block; margin-top: 5px; }
        .item-price { text-align: right; font-weight: bold; }

        /* Total Section */
        .invoice-total {
            text-align: right;
            padding: 30px 0 10px 0;
            font-size: 1.5rem;
            color: var(--amari-mahogany);
            font-family: 'Playfair Display', serif;
        }
        .invoice-total span {
            font-size: 2rem;
            color: var(--amari-gold);
            font-weight: bold;
            font-family: 'Lato', sans-serif;
            margin-left: 20px;
        }

        /* Footer Actions */
        .invoice-actions {
            background: var(--amari-bg);
            padding: 20px 50px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .btn-print {
            color: var(--amari-oak);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: bold;
            cursor: pointer;
            background: none;
            border: none;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .btn-print:hover { color: var(--amari-gold); }
        .btn-home {
            background: var(--amari-mahogany);
            color: var(--amari-white);
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 4px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.85rem;
            transition: 0.3s;
        }
        .btn-home:hover { background: var(--amari-oak); }

    </style>
</head>
<body>

<div class="invoice-card">
    <div class="invoice-header">
        <h1>Amari Alabang</h1>
        <p>Your sanctuary awaits.</p>
    </div>

    <div class="invoice-body">
        <div class="booking-ref">
            Confirmation Reference
            <strong>#AMARI-<?php echo str_pad($invoice['id'], 5, '0', STR_PAD_LEFT); ?></strong>
        </div>

        <div class="details-grid">
            <div class="detail-box">
                <label>Guest Name</label>
                <div><?php echo htmlspecialchars($invoice['guest_name']); ?></div>
            </div>
            <div class="detail-box">
                <label>Status</label>
                <div style="color: var(--amari-gold); text-transform: uppercase;"><?php echo $invoice['status']; ?></div>
            </div>
            <div class="detail-box">
                <label>Check-in</label>
                <div><?php echo date("F j, Y", strtotime($invoice['check_in'])); ?></div>
            </div>
            <div class="detail-box">
                <label>Check-out</label>
                <div><?php echo date("F j, Y", strtotime($invoice['check_out'])); ?></div>
            </div>
        </div>

        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Accommodation Details</th>
                    <th style="text-align: right;">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <span class="item-name"><?php echo $invoice['title']; ?></span>
                        <span class="item-desc"><?php echo $nights; ?> Night(s) @ ₱<?php echo number_format($invoice['price_per_night'], 2); ?>/night</span>
                    </td>
                    <td class="item-price">₱<?php echo number_format($total_price, 2); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="invoice-total">
            Total Balance: <span>₱<?php echo number_format($total_price, 2); ?></span>
        </div>
    </div>

    <div class="invoice-actions">
        <button class="btn-print" onclick="window.print()">🖨️ Print Receipt</button>
        <a href="index.php" class="btn-home">Return to Home</a>
    </div>
</div>

</body>
</html>