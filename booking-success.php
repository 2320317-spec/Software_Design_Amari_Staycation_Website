<?php
// Catch the guest's name from the URL so we can personalize the thank you message
$guest_name = isset($_GET['name']) ? htmlspecialchars($_GET['name']) : 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Received | Amari Alabang</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --amari-tan: #b0885a;
            --amari-navy: #042e47;
            --bg-light: #f9f9f9;
        }
        body { margin: 0; font-family: 'Lato', sans-serif; background-color: var(--bg-light); color: #333; display: flex; align-items: center; justify-content: center; height: 100vh; overflow: hidden; }
        
        .success-card {
            background: white;
            padding: 60px 50px;
            border-radius: 8px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.08);
            text-align: center;
            max-width: 500px;
            border-top: 5px solid var(--amari-tan);
            /* A beautiful pop-up animation */
            animation: popUp 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
            opacity: 0;
            transform: scale(0.9) translateY(30px);
        }

        .success-icon {
            width: 80px; height: 80px; background: rgba(176, 136, 90, 0.1); color: var(--amari-tan);
            border-radius: 50%; display: flex; align-items: center; justify-content: center;
            font-size: 2.5rem; margin: 0 auto 30px auto;
        }

        h1 { font-family: 'Playfair Display', serif; color: var(--amari-navy); font-size: 2.2rem; margin-top: 0; margin-bottom: 15px; }
        p { color: #666; line-height: 1.8; margin-bottom: 35px; font-size: 1.05rem; }

        .btn-home {
            display: inline-block; background: var(--amari-navy); color: white; padding: 16px 35px;
            text-decoration: none; font-weight: bold; text-transform: uppercase; letter-spacing: 2px;
            font-size: 0.85rem; transition: 0.4s; border-radius: 4px; border: 1px solid var(--amari-navy);
        }
        .btn-home:hover { background: white; color: var(--amari-navy); transform: translateY(-2px); box-shadow: 0 10px 20px rgba(4, 46, 71, 0.1); }

        @keyframes popUp {
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
    </style>
</head>
<body>

    <div class="success-card">
        <div class="success-icon"><i class="fa-solid fa-check"></i></div>
        <h1>Request Received</h1>
        <p>Thank you, <strong><?php echo $guest_name; ?></strong>. We have successfully received your reservation request. Our concierge team will review your dates and send a confirmation email shortly.</p>
        
        <a href="index.php" class="btn-home">Return to Home</a>
    </div>

</body>
</html>