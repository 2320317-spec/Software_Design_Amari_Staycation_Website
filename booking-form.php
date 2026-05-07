<?php
include('includes/db-config.php');

// 1. Intercept the Unit ID from the URL
if(isset($_GET['unit_id'])) {
    $unit_id = $_GET['unit_id'];
    
    // 2. Fetch the specific unit data so the guest knows what they are booking
    $stmt = $conn->prepare("SELECT * FROM units WHERE id = ?");
    $stmt->bind_param("i", $unit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $unit = $result->fetch_assoc();

    if(!$unit) {
        header("Location: index.php");
        exit();
    }
} else {
    // If they try to access the form without picking a room, send them back
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Booking - Amari Alabang</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --amari-tan: #b0885a;
            --amari-navy: #042e47;
            --bg-light: #f4f6f8;
        }
        body { margin: 0; font-family: 'Lato', sans-serif; background-color: var(--bg-light); color: #333; }
        h1, h2, h3 { font-family: 'Playfair Display', serif; }

        /* Minimal Nav */
        .navbar { display: flex; justify-content: space-between; align-items: center; padding: 15px 50px; background: white; border-bottom: 1px solid #eee; }
        .navbar a { text-decoration: none; color: var(--amari-navy); font-weight: bold; text-transform: uppercase; letter-spacing: 1px; font-size: 0.85rem;}

        .booking-container {
            max-width: 900px;
            margin: 50px auto;
            display: flex;
            flex-wrap: wrap;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border-radius: 10px;
            overflow: hidden;
        }

        /* Left Side: Order Summary */
        .order-summary {
            flex: 1;
            min-width: 300px;
            background: var(--amari-navy);
            color: white;
            padding: 40px;
        }
        .order-summary h2 { color: var(--amari-tan); border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px; }
        .summary-price { font-size: 2rem; font-family: 'Lato', sans-serif; font-weight: bold; margin: 20px 0; }
        .summary-img { width: 100%; height: 200px; object-fit: cover; border-radius: 5px; margin-top: 20px; opacity: 0.9; }

        /* Right Side: The Form */
        .booking-form {
            flex: 2;
            min-width: 350px;
            padding: 40px;
        }
        .booking-form h2 { color: var(--amari-navy); font-size: 2rem; margin-top: 0; }
        
        .form-group { margin-bottom: 20px; }
        .form-row { display: flex; gap: 20px; }
        .form-row .form-group { flex: 1; }
        
        label { display: block; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: #666; margin-bottom: 8px; font-weight: bold; }
        input, select, textarea {
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; font-family: 'Lato', sans-serif; font-size: 1rem; background: #fafafa; box-sizing: border-box;
        }
        input:focus, textarea:focus { outline: none; border-color: var(--amari-tan); background: white; }

        .btn-submit {
            background: var(--amari-tan); color: white; border: none; padding: 18px; width: 100%; font-size: 1.1rem; text-transform: uppercase; font-weight: bold; letter-spacing: 2px; cursor: pointer; border-radius: 5px; transition: 0.3s; margin-top: 10px;
        }
        .btn-submit:hover { background: var(--amari-navy); }
    </style>
</head>
<body>

<nav class="navbar">
    <a href="room-details.php?id=<?php echo $unit['id']; ?>">&lsaquo; Back to Details</a>
    <h2 style="margin: 0; color: var(--amari-navy);">AMARI ALABANG</h2>
</nav>

<div class="booking-container">
    
    <div class="order-summary">
        <h2>Reservation Summary</h2>
        <h3 style="font-size: 1.5rem; margin-bottom: 5px;"><?php echo $unit['title']; ?></h3>
        <p style="color: #aaa; font-size: 0.9rem; line-height: 1.6; margin-top: 0;"><?php echo substr($unit['description'], 0, 100) . '...'; ?></p>
        
        <div class="summary-price">₱<?php echo number_format($unit['price_per_night'], 2); ?> <span style="font-size: 1rem; color: #aaa; font-weight: normal;">/ night</span></div>
        
        <?php if(!empty($unit['image_path'])): ?>
            <img src="images/<?php echo $unit['image_path']; ?>" class="summary-img" alt="Room View">
        <?php else: ?>
            <div class="summary-img" style="background: #333; display: flex; align-items: center; justify-content: center; color: #666;">Room Preview</div>
        <?php endif; ?>
    </div>

    <div class="booking-form">
        <h2>Guest Details</h2>
        <form action="process-booking.php" method="POST">
            <input type="hidden" name="unit_id" value="<?php echo $unit['id']; ?>">

            <div class="form-row">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" required>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" required>
                </div>
            </div>

            <h3 style="color: var(--amari-navy); border-bottom: 1px solid #eee; padding-bottom: 10px; margin-top: 30px;">Stay Information</h3>

            <div class="form-row">
                <div class="form-group">
                    <label>Check-in Date</label>
                    <input type="date" id="checkin" name="checkin" required>
                </div>
                <div class="form-group">
                    <label>Check-out Date</label>
                    <input type="date" id="checkout" name="checkout" required>
                </div>
            </div>

            <div class="form-group">
                <label>Special Requests (Optional)</label>
                <textarea name="requests" rows="3" placeholder="e.g., Anniversary setup, early check-in request..."></textarea>
            </div>

            <button type="submit" class="btn-submit">Confirm Reservation</button>
        </form>
    </div>
</div>

<script>
    const today = new Date().toISOString().split('T')[0];
    document.getElementById("checkin").setAttribute('min', today);
    
    // When check-in changes, make sure check-out can't be before check-in
    document.getElementById("checkin").addEventListener('change', function() {
        document.getElementById("checkout").setAttribute('min', this.value);
    });
</script>

</body>
</html>