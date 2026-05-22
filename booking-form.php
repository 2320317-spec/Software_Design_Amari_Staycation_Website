<?php
include('includes/db-config.php');

$unit = null;
if (isset($_GET['unit_id'])) {
    $unit_id = $_GET['unit_id'];
    $stmt = $conn->prepare("SELECT * FROM units WHERE id = ?");
    $stmt->bind_param("i", $unit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $unit = $result->fetch_assoc();
} elseif (isset($_GET['unit_name'])) {
    $unit_name = urldecode($_GET['unit_name']); 
    $stmt = $conn->prepare("SELECT * FROM units WHERE title = ?");
    $stmt->bind_param("s", $unit_name);
    $stmt->execute();
    $result = $stmt->get_result();
    $unit = $result->fetch_assoc();
}

if (!$unit) { header("Location: index.php"); exit(); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Booking | <?php echo htmlspecialchars($unit['title']); ?></title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root { --amari-tan: #b0885a; --amari-navy: #042e47; --bg-body: #f4f4f4; }
        
        .booking-container { max-width: 900px; margin: 80px auto; background: white; border-radius: 20px; box-shadow: 0 30px 60px rgba(0,0,0,0.1); overflow: hidden; display: flex; flex-direction: column; }

        .form-header { background: var(--amari-navy); padding: 60px; color: white; text-align: center; }
        .form-header h2 { font-family: 'Playfair Display', serif; font-size: 2.5rem; margin-bottom: 10px; color: var(--amari-tan); }

        .booking-form { padding: 50px; }
        .form-group { margin-bottom: 30px; }
        label { display: block; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 2px; color: #888; margin-bottom: 10px; font-weight: 700; }
        
        input, textarea { width: 100%; padding: 15px 0; border: none; border-bottom: 1px solid #ddd; font-family: 'Lato', sans-serif; font-size: 1rem; transition: 0.3s; }
        input:focus, textarea:focus { outline: none; border-bottom: 2px solid var(--amari-tan); }

        .btn-submit { background: var(--amari-navy); color: white; border: none; padding: 20px; width: 100%; text-transform: uppercase; letter-spacing: 3px; font-weight: bold; cursor: pointer; border-radius: 50px; transition: 0.4s; margin-top: 20px; }
        .btn-submit:hover { background: var(--amari-tan); transform: translateY(-5px); }
    </style>
</head>
<body>

<div class="booking-container">
    <div class="form-header">
        <h2>Secure Your Stay</h2>
        <p style="letter-spacing: 2px; text-transform: uppercase; font-size: 0.8rem; opacity: 0.8;">
            <?php echo htmlspecialchars($unit['title']); ?> | ₱<?php echo number_format($unit['price_per_night']); ?> per night
        </p>
    </div>

    <div class="booking-form">
        <form action="process-booking.php" method="POST">
            <input type="hidden" name="unit_id" value="<?php echo htmlspecialchars($unit['id']); ?>">

            <div style="display: flex; gap: 40px;">
                <div class="form-group" style="flex:1;">
                    <label>First Name</label>
                    <input type="text" name="first_name" required placeholder="Juan">
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Last Name</label>
                    <input type="text" name="last_name" required placeholder="Dela Cruz">
                </div>
            </div>

            <div style="display: flex; gap: 40px;">
                <div class="form-group" style="flex:1;">
                    <label>Email Address</label>
                    <input type="email" name="email" required placeholder="juan@example.com">
                </div>
                <div class="form-group" style="flex:1;">
                    <label>Phone Number</label>
                    <input type="tel" name="phone" required placeholder="0917-XXX-XXXX">
                </div>
            </div>

            <div class="form-group">
                <label>Check-in Date</label>
                <input type="text" id="checkin" name="checkin" required placeholder="Select arrival date...">
            </div>
            
            <div class="form-group">
                <label>Check-out Date</label>
                <input type="text" id="checkout" name="checkout" required placeholder="Select departure date...">
            </div>

            <div class="form-group">
                <label>Special Requests</label>
                <textarea name="requests" rows="3" placeholder="Anniversary setup, early check-in..."></textarea>
            </div>

            <button type="submit" class="btn-submit">Confirm Reservation</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    const unitId = <?php echo $unit['id']; ?>;

    fetch('get-booked-dates.php?unit_id=' + unitId)
        .then(response => response.json())
        .then(disabledDates => {
            const checkoutPicker = flatpickr("#checkout", {
                minDate: "today",
                dateFormat: "Y-m-d"
            });

            flatpickr("#checkin", {
                minDate: "today",
                dateFormat: "Y-m-d",
                disable: disabledDates,
                onChange: function(selectedDates, dateStr, instance) {
                    checkoutPicker.set('minDate', dateStr);
                    checkoutPicker.open();
                }
            });
        });
</script>

</body>
</html>