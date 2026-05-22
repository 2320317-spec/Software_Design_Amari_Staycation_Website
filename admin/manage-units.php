<?php
// 1. Call the Security Guard
include('auth.php');

// 2. Connect to the plumbing
include('../includes/db-config.php');

// Fetch all units - Calibrated to use 'title' for sorting
$result = $conn->query("SELECT * FROM units ORDER BY title ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Units | Amari Alabang</title>
    
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
        
        .btn-add-unit { background-color: var(--amari-tan); color: white; padding: 10px 20px; border-radius: 3px; text-decoration: none; font-weight: bold; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-add-unit:hover { background-color: var(--amari-navy); }

        .content-area { padding: 50px; overflow-y: auto; flex: 1; }

        /* --- THE GRID & CARDS (Integrated from user code) --- */
        .unit-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; }
        
        .unit-card { background: var(--white); border-radius: 8px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.02); transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.4s; position: relative; display: flex; flex-direction: column; border: 1px solid #eee; }
        .unit-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.06); }

        /* Image Area */
        .unit-image-container { width: 100%; height: 220px; overflow: hidden; background-color: #f4f4f4; display: flex; align-items: center; justify-content: center; }
        .unit-image-container img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
        .unit-card:hover .unit-image-container img { transform: scale(1.05); }
        .no-image { color: var(--text-muted); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; opacity: 0.6; }

        /* Card Details */
        .unit-details { padding: 25px; flex-grow: 1; }
        .unit-name { color: var(--amari-navy); font-size: 1.5rem; margin-bottom: 5px; font-weight: 700; font-family: 'Playfair Display', serif; }
        .unit-price { color: var(--amari-tan); font-size: 1.1rem; font-weight: 700; margin-bottom: 15px; }
        
        .unit-amenities { font-size: 0.75rem; color: var(--amari-navy); background: rgba(176, 136, 90, 0.1); padding: 8px 12px; margin-bottom: 15px; display: inline-block; font-weight: bold; border-left: 2px solid var(--amari-tan); }
        .unit-desc { font-size: 0.9rem; color: #666; line-height: 1.6; margin-bottom: 10px; }

        /* Status Badge */
        .status-badge { position: absolute; top: 15px; right: 15px; padding: 6px 15px; font-size: 0.65rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; z-index: 10; border: 1px solid rgba(255,255,255,0.5); }
        .status-available { background: rgba(39, 174, 96, 0.9); color: white; }
        .status-maintenance { background: rgba(231, 76, 60, 0.9); color: white; }
        .status-booked { background: rgba(176, 136, 90, 0.9); color: white; }

        /* Actions Footer */
        .card-actions { border-top: 1px solid #f5f5f5; padding: 15px 25px; display: flex; justify-content: space-between; background: #fafafa; }
        .btn-edit { color: var(--amari-navy); text-decoration: none; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; }
        .btn-edit:hover { color: var(--amari-tan); }

        /* --- ANIMATIONS --- */
        .fade-slide-up { opacity: 0; transform: translateY(40px); transition: opacity 0.8s ease-out, transform 0.8s ease-out; }
        .animate-title { animation: textPopUp 1.2s ease-out forwards; }
        @keyframes textPopUp { 0% { opacity: 0; transform: translateY(30px); } 100% { opacity: 1; transform: translateY(0); } }
        .is-visible { opacity: 1; transform: translate(0); }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Amari</h2>
            <p>Management Portal</p>
        </div>
        
        <nav class="sidebar-nav">
            <a href="dashboard.php" class="nav-item"><i class="fa-solid fa-calendar-check"></i> Reservations</a>
            <a href="manage-units.php" class="nav-item active"><i class="fa-solid fa-building"></i> Manage Units</a>
            <a href="../index.php" target="_blank" class="nav-item" style="margin-top: 20px;"><i class="fa-solid fa-globe"></i> Live Site</a>
        </nav>

        <div class="sidebar-footer">
            <a href="logout.php" class="btn-logout">Logout</a>
        </div>
    </aside>

    <main class="main-wrapper">
        
        <header class="topbar">
            <h1 class="animate-title">Luxury Collection</h1>
            
            <div class="topbar-right">
                <a href="add-unit.php" class="btn-add-unit"><i class="fa-solid fa-plus"></i> Add New Property</a>
            </div>
        </header>

        <div class="content-area">
            <div class="unit-grid">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="unit-card fade-slide-up">
                        
                        <div class="status-badge status-<?php echo strtolower($row['status']); ?>">
                            <?php echo htmlspecialchars($row['status']); ?>
                        </div>

                        <div class="unit-image-container">
                            <?php if(!empty($row['image_path'])): ?>
                                <img src="../assets/images/<?php echo htmlspecialchars($row['image_path']); ?>" alt="<?php echo htmlspecialchars($row['title']); ?>">
                            <?php else: ?>
                                <div class="no-image"><i class="fa-regular fa-image" style="margin-right: 5px;"></i> No Image Preview</div>
                            <?php endif; ?>
                        </div>

                        <div class="unit-details">
                            <div class="unit-name"><?php echo htmlspecialchars($row['title']); ?></div>
                            <div class="unit-price">₱<?php echo number_format($row['price_per_night'], 2); ?> <small style="font-size: 0.7rem; color: #999;">/ night</small></div>
                            
                            <?php if(!empty($row['amenities'])): ?>
                                <div class="unit-amenities">
                                    <i class="fa-solid fa-location-dot" style="color: var(--amari-tan); margin-right: 5px;"></i> <?php echo htmlspecialchars($row['amenities']); ?>
                                </div>
                            <?php endif; ?>

                            <p class="unit-desc">
                                <?php echo htmlspecialchars(substr($row['description'], 0, 100)) . '...'; ?>
                            </p>
                        </div>

                        <div class="card-actions">
                            <a href="edit-unit.php?id=<?php echo $row['id']; ?>" class="btn-edit"><i class="fa-solid fa-pen" style="margin-right: 5px;"></i> Edit Details</a>
                            <a href="delete-unit.php?id=<?php echo $row['id']; ?>" class="btn-edit" style="color: #bbb;"><i class="fa-solid fa-box-archive" style="margin-right: 5px;"></i> Archive</a>
                        </div>
                    </div>
                <?php endwhile; ?>
                </div>
        </div>
    </main>

    <script>
        const observerOptions = { threshold: 0.1 };
        const scrollObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                }
            });
        }, observerOptions);

        // Add a slight stagger effect to the cards
        document.querySelectorAll('.fade-slide-up').forEach((el, index) => {
            el.style.transitionDelay = `${index * 0.1}s`;
            scrollObserver.observe(el);
        });
    </script>

</body>
</html>