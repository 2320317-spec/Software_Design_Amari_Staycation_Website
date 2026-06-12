<?php
// 1. Call the Security Guard (Only Hosts can view this)
include('auth.php');

// 2. Connect to the plumbing
include('../includes/db-config.php');

// 3. Catch the Booking ID from the URL
if(isset($_GET['id'])) {
    $booking_id = $_GET['id'];

    // 4. THE SQL JOIN COMMAND
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
        header("Location: dashboard.php");
        exit();
    }

    // 5. THE ENGINEERING MATH (Calculate Total Price)
    $checkin_date = new DateTime($invoice['check_in']);
    $checkout_date = new DateTime($invoice['check_out']);
    
    // Calculate difference in days
    $nights = $checkin_date->diff($checkout_date)->days;
    if ($nights == 0) $nights = 1; // Failsafe: minimum 1 night

    $total_price = $nights * $invoice['price_per_night'];

} else {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details | #<?php echo str_pad($invoice['id'], 5, '0', STR_PAD_LEFT); ?></title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* --- SIGNATURE AMARI BRANDING --- */
        :root {
            --amari-tan: #b0885a;
            --amari-navy: #042e47;
            --bg-body: #f9f9f9;
            --text-dark: #333333;
            --text-muted: #888888;
            --white: #ffffff;
            --sidebar-width: 260px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Lato', sans-serif; background-color: var(--bg-body); color: var(--text-dark); display: flex; height: 100vh; overflow: hidden; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; font-weight: 400; }

        /* --- SIDEBAR --- */
        .sidebar { width: var(--sidebar-width); background-color: var(--amari-navy); color: var(--white); display: flex; flex-direction: column; height: 100vh; position: fixed; }
        .sidebar-header { padding: 40px 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.05); }
        .sidebar-header h2 { color: var(--amari-tan); font-size: 1.8rem; letter-spacing: 3px; text-transform: uppercase; margin-bottom: 5px; }
        .sidebar-header p { font-size: 0.75rem; color: rgba(255,255,255,0.5); letter-spacing: 2px; text-transform: uppercase; }
        
        .sidebar-nav { flex: 1; padding: 30px 0; }
        .nav-item { display: flex; align-items: center; padding: 18px 30px; color: rgba(255,255,255,0.7); text-decoration: none; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; transition: 0.4s; border-left: 3px solid transparent; font-weight: 700; }
        .nav-item i { width: 30px; font-size: 1.1rem; }
        .nav-item:hover, .nav-item.active { background-color: rgba(255,255,255,0.02); color: var(--amari-tan); border-left-color: var(--amari-tan); padding-left: 35px; }
        
        .sidebar-footer { padding: 30px; border-top: 1px solid rgba(255,255,255,0.05); }
        .btn-logout { display: block; width: 100%; text-align: center; background-color: transparent; border: 1px solid rgba(255,255,255,0.2); color: white; padding: 12px; text-decoration: none; font-weight: bold; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 2px; transition: 0.4s; }
        .btn-logout:hover { background-color: var(--amari-tan); border-color: var(--amari-tan); }

        /* --- MAIN CONTENT AREA --- */
        .main-wrapper { margin-left: var(--sidebar-width); width: calc(100% - var(--sidebar-width)); display: flex; flex-direction: column; height: 100vh; }
        
        .topbar { background: var(--white); padding: 25px 50px; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #eee; z-index: 10; }
        .topbar h1 { font-size: 1.8rem; color: var(--amari-navy); text-transform: uppercase; letter-spacing: 2px; }
        
        .btn-back { color: var(--text-muted); text-decoration: none; font-size: 0.8rem; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; display: flex; align-items: center; gap: 8px; }
        .btn-back:hover { color: var(--amari-tan); }

        .content-area { padding: 50px; overflow-y: auto; flex: 1; display: flex; justify-content: center; align-items: flex-start; }

        /* --- INVOICE CARD --- */
        .invoice-card { background: var(--white); width: 100%; max-width: 800px; box-shadow: 0 15px 35px rgba(0,0,0,0.03); border-radius: 4px; border-top: 4px solid var(--amari-tan); opacity: 0; transform: translateY(30px); animation: fadeUp 0.8s ease-out forwards; }
        @keyframes fadeUp { to { opacity: 1; transform: translate(0); } }

        /* Header Section */
        .invoice-header { text-align: center; padding: 50px 40px; border-bottom: 1px solid #f0f0f0; background: url('../assets/images/exp-hero.jpg') center/cover; position: relative; }
        .invoice-header::before { content: ''; position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: rgba(4, 46, 71, 0.9); }
        .header-content { position: relative; z-index: 2; color: white; }
        .header-content h1 { font-size: 2.5rem; letter-spacing: 3px; text-transform: uppercase; }
        .header-content p { color: var(--amari-tan); font-style: italic; font-size: 1.1rem; margin-top: 10px; font-family: 'Playfair Display', serif; }

        /* Body Section */
        .invoice-body { padding: 50px; }
        
        .booking-ref { text-align: center; background: #fdfcfb; padding: 20px; border-radius: 4px; margin-bottom: 40px; color: var(--amari-navy); font-size: 0.9rem; letter-spacing: 1px; border: 1px dashed var(--amari-tan); }
        .booking-ref strong { color: var(--amari-navy); font-size: 1.5rem; display: block; margin-top: 5px; font-family: 'Playfair Display', serif; }

        .details-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 40px; }
        .detail-box label { display: block; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; color: #999; margin-bottom: 5px; font-weight: bold; }
        .detail-box div { color: var(--amari-navy); font-weight: bold; font-size: 1.1rem; }
        
        .status-pill { display: inline-block; padding: 5px 12px; font-size: 0.75rem; text-transform: uppercase; border-radius: 20px; font-weight: bold; margin-top: 5px; }
        .status-pending { background: #fff8e1; color: #f57f17; }
        .status-approved { background: #e8f5e9; color: #2e7d32; }
        .status-declined { background: #ffebee; color: #c62828; }

        /* Line Items */
        .invoice-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .invoice-table th { text-align: left; padding-bottom: 15px; border-bottom: 2px solid #eee; color: var(--text-muted); text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; }
        .invoice-table td { padding: 25px 0; border-bottom: 1px solid #f0f0f0; color: var(--amari-navy); }
        .item-name { font-weight: bold; font-size: 1.2rem; font-family: 'Playfair Display', serif; }
        .item-desc { font-size: 0.85rem; color: #888; display: block; margin-top: 5px; font-family: 'Lato', sans-serif; }
        .item-price { text-align: right; font-weight: bold; font-size: 1.1rem; }

        /* Total Section */
        .invoice-total { text-align: right; padding: 30px 0 10px 0; font-size: 1.2rem; color: var(--amari-navy); text-transform: uppercase; letter-spacing: 1px; }
        .invoice-total span { font-size: 2.5rem; color: var(--amari-tan); font-weight: bold; font-family: 'Playfair Display', serif; margin-left: 20px; }

        /* Action Buttons */
        .admin-actions { border-top: 1px solid #eee; padding-top: 30px; margin-top: 30px; display: flex; justify-content: flex-end; gap: 15px; }
        .btn-action { padding: 12px 25px; border-radius: 3px; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; font-size: 0.8rem; text-decoration: none; transition: 0.3s; display: flex; align-items: center; gap: 8px; cursor: pointer; border: none; }
        .btn-approve { background: var(--amari-navy); color: white; }
        .btn-approve:hover { background: var(--amari-tan); transform: translateY(-2px); }
        .btn-print { background: transparent; color: var(--text-muted); border: 1px solid #ddd; }
        .btn-print:hover { border-color: var(--amari-navy); color: var(--amari-navy); }

        /* Print Styles */
        @media print {
            .sidebar, .topbar, .admin-actions, .btn-back { display: none !important; }
            .main-wrapper { margin: 0; width: 100%; }
            .content-area { padding: 0; }
            .invoice-card { box-shadow: none; border: none; }
        }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Amari</h2>
            <p>Management Portal</p>
        </div>
        
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="nav-item active"><i class="fa-solid fa-calendar-check"></i> Reservations</a>
            <a href="manage-units.php" class="nav-item"><i class="fa-solid fa-building"></i> Manage Units</a>
            <a href="../index.php" target="_blank" class="nav-item" style="margin-top: 20px;"><i class="fa-solid fa-globe"></i> Live Site</a>
        </nav>

        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </aside>

    <main class="main-wrapper">
        
        <header class="topbar">
            <h1>Booking Details</h1>
            <div class="topbar-right">
                <a href="dashboard.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back to Dashboard</a>
            </div>
        </header>

        <div class="content-area">
            
            <div class="invoice-card">
                <div class="invoice-header">
                    <div class="header-content">
                        <h1>Amari Alabang</h1>
                        <p>Reservation Details</p>
                    </div>
                </div>

                <div class="invoice-body">
                    <div class="booking-ref">
                        Confirmation Reference
                        <strong>#AMARI-<?php echo str_pad($invoice['id'], 5, '0', STR_PAD_LEFT); ?></strong>
                    </div>

                    <div class="details-grid">
                        <div class="detail-box">
                            <label><i class="fa-regular fa-user" style="margin-right: 5px;"></i> Guest Name</label>
                            <div><?php echo htmlspecialchars($invoice['guest_name']); ?></div>
                            <div style="font-size: 0.85rem; color: #666; font-weight: normal; margin-top: 3px;"><i class="fa-regular fa-envelope"></i> <?php echo htmlspecialchars($invoice['guest_email']); ?></div>
                            <div style="font-size: 0.85rem; color: #666; font-weight: normal;"><i class="fa-solid fa-phone"></i> <?php echo htmlspecialchars($invoice['phone']); ?></div>
                        </div>
                        <div class="detail-box">
                            <label><i class="fa-solid fa-toggle-on" style="margin-right: 5px;"></i> Booking Status</label>
                            <?php 
                                $statusClass = 'status-pending';
                                if($invoice['status'] == 'approved') $statusClass = 'status-approved';
                                if($invoice['status'] == 'declined') $statusClass = 'status-declined';
                            ?>
                            <div class="status-pill <?php echo $statusClass; ?>"><?php echo htmlspecialchars($invoice['status']); ?></div>
                        </div>
                        <div class="detail-box">
                            <label><i class="fa-regular fa-calendar-check" style="margin-right: 5px;"></i> Check-in</label>
                            <div><?php echo date("F j, Y", strtotime($invoice['check_in'])); ?></div>
                        </div>
                        <div class="detail-box">
                            <label><i class="fa-regular fa-calendar-xmark" style="margin-right: 5px;"></i> Check-out</label>
                            <div><?php echo date("F j, Y", strtotime($invoice['check_out'])); ?></div>
                        </div>
                        <?php if (isset($invoice['num_adults'])): ?>
                        <div class="detail-box">
                            <label><i class="fa-solid fa-users" style="margin-right: 5px;"></i> Guests</label>
                            <div><?php
                                $a = (int)$invoice['num_adults'];
                                $c = (int)($invoice['num_children'] ?? 0);
                                echo $a . ' Adult' . ($a > 1 ? 's' : '');
                                if ($c > 0) echo ', ' . $c . ' Child' . ($c > 1 ? 'ren' : '');
                            ?></div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <?php if(!empty($invoice['special_requests'])): ?>
                    <div class="detail-box" style="margin-bottom: 30px; background: #fafafa; padding: 15px; border-left: 3px solid var(--amari-tan);">
                        <label><i class="fa-regular fa-comment-dots" style="margin-right: 5px;"></i> Special Requests</label>
                        <div style="font-size: 0.95rem; font-weight: normal; font-family: 'Lato', sans-serif; line-height: 1.6;"><?php echo htmlspecialchars($invoice['special_requests']); ?></div>
                    </div>
                    <?php endif; ?>

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
                                    <span class="item-name"><?php echo htmlspecialchars($invoice['title']); ?></span>
                                    <span class="item-desc"><?php echo $nights; ?> Night(s) @ ₱<?php echo number_format($invoice['price_per_night'], 2); ?>/night</span>
                                </td>
                                <td class="item-price">₱<?php echo number_format($total_price, 2); ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="invoice-total">
                        Total Balance <span>₱<?php echo number_format($total_price, 2); ?></span>
                    </div>

                    <div class="admin-actions">
                        <button class="btn-action btn-print" onclick="window.print()"><i class="fa-solid fa-print"></i> Print</button>
                        
                        <?php if($invoice['status'] == 'pending'): ?>
                            <a href="update-status.php?id=<?php echo $invoice['id']; ?>&action=approve" class="btn-action btn-approve"><i class="fa-solid fa-check"></i> Approve Booking</a>
                        <?php endif; ?>
                    </div>

                </div>
            </div>

        </div>
    </main>

</body>
</html>