<?php
// 1. Call the Security Guard
include('auth.php');

// 2. Connect to the plumbing
include('../includes/db-config.php');

// 3. Fetch all "Pending" bookings for review 
$sql = "SELECT * FROM bookings WHERE status = 'pending' ORDER BY check_in ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Host Dashboard | Amari Alabang</title>
    
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
        .topbar-right { display: flex; align-items: center; gap: 20px; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; }

        .content-area { padding: 50px; overflow-y: auto; flex: 1; }

        /* --- DASHBOARD WIDGETS --- */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-bottom: 50px; }
        .stat-card { background: var(--white); padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.02); display: flex; align-items: center; border-left: 2px solid var(--amari-tan); transition: 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        .stat-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.06); }
        .stat-icon { width: 60px; height: 60px; color: var(--amari-tan); display: flex; justify-content: center; align-items: center; font-size: 2rem; margin-right: 25px; }
        .stat-info h3 { font-size: 2.5rem; color: var(--amari-navy); margin-bottom: 5px; font-family: 'Playfair Display', serif; }
        .stat-info p { color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; letter-spacing: 2px; font-weight: bold; }

        /* --- DATA TABLES --- */
        .data-panel { background: var(--white); box-shadow: 0 10px 30px rgba(0,0,0,0.02); }
        .panel-header { padding: 30px 40px; border-bottom: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .panel-header h2 { font-size: 1.5rem; color: var(--amari-navy); }
        .badge { background: var(--amari-tan); color: white; padding: 6px 15px; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }

        table { width: 100%; border-collapse: collapse; }
        th { background-color: var(--white); color: var(--text-muted); font-weight: 700; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 2px; padding: 20px 40px; border-bottom: 1px solid #eee; text-align: left; }
        td { padding: 25px 40px; border-bottom: 1px solid #f9f9f9; vertical-align: middle; transition: 0.3s; }
        tr:hover td { background-color: #fafafa; }
        
        .status-pill { background: rgba(176, 136, 90, 0.1); color: var(--amari-tan); padding: 6px 15px; font-size: 0.65rem; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; border: 1px solid rgba(176, 136, 90, 0.3); }
        
        .btn-action { padding: 10px 18px; color: white; text-decoration: none; font-size: 0.75rem; font-weight: bold; margin-right: 8px; transition: 0.4s; display: inline-block; cursor: pointer; text-transform: uppercase; letter-spacing: 1px; }
        .btn-approve { background-color: var(--amari-navy); border: 1px solid var(--amari-navy); }
        .btn-approve:hover { background-color: var(--amari-tan); border-color: var(--amari-tan); }
        .btn-decline { background-color: transparent; border: 1px solid #ccc; color: #888; }
        .btn-decline:hover { border-color: #d9534f; color: #d9534f; }

        /* Empty State */
        .empty-state { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 80px 0; color: var(--text-muted); }
        .empty-state i { font-size: 3.5rem; color: #eee; margin-bottom: 25px; }
        .empty-state p { font-family: 'Playfair Display', serif; font-style: italic; font-size: 1.2rem; }

        /* --- CONTINUOUS SCROLL ANIMATIONS (From Index.php) --- */
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
            <h1 class="animate-title">Dashboard Overview</h1>
            <div class="topbar-right">
                <span><i class="fa-regular fa-calendar"></i> <?php echo date('F j, Y'); ?></span>
                <span style="margin-left: 30px; font-weight: bold; color: var(--amari-navy);">
                    <i class="fa-solid fa-circle-user" style="color: var(--amari-tan); font-size: 1.2rem; vertical-align: middle; margin-right: 5px;"></i> Host Admin
                </span>
            </div>
        </header>

        <div class="content-area">
            
            <div class="stats-grid">
                <div class="stat-card fade-slide-up" style="transition-delay: 0.1s;">
                    <div class="stat-icon"><i class="fa-solid fa-bell"></i></div>
                    <div class="stat-info">
                        <h3><?php echo $result->num_rows; ?></h3>
                        <p>Pending Requests</p>
                    </div>
                </div>
                
                <div class="stat-card fade-slide-up" style="transition-delay: 0.2s;">
                    <div class="stat-icon"><i class="fa-solid fa-key"></i></div>
                    <div class="stat-info">
                        <h3>3</h3>
                        <p>Active Units</p>
                    </div>
                </div>
                
                <div class="stat-card fade-slide-up" style="transition-delay: 0.3s;">
                    <div class="stat-icon"><i class="fa-solid fa-calendar-days"></i></div>
                    <div class="stat-info">
                        <h3>12</h3>
                        <p>Upcoming Stays</p>
                    </div>
                </div>
            </div>

            <div class="data-panel fade-slide-up" style="transition-delay: 0.4s;">
                <div class="panel-header">
                    <h2>Action Required</h2>
                    <span class="badge"><?php echo $result->num_rows; ?> waiting</span>
                </div>
                
                <div>
                    <?php if ($result->num_rows > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Guest Details</th>
                                    <th>Stay Dates</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td>
                                            <span style="font-family: 'Playfair Display', serif; font-size: 1.3rem; color: var(--amari-navy); display: block; margin-bottom: 5px;">
                                                <?php echo htmlspecialchars($row['guest_title'] . " " . $row['guest_name']); ?>
                                            </span>
                                            <small style="color: #999; letter-spacing: 1px; font-size: 0.75rem;"><i class="fa-regular fa-envelope"></i> <?php echo htmlspecialchars($row['guest_email']); ?></small>
                                        </td>
                                        <td>
                                            <span style="font-weight: 700; color: #444; font-size: 0.9rem;">
                                                <?php echo date("M d", strtotime($row['check_in'])); ?> <i class="fa-solid fa-arrow-right" style="font-size: 0.7rem; color: var(--amari-tan); margin: 0 8px;"></i> 
                                                <?php echo date("M d", strtotime($row['check_out'])); ?>
                                            </span>
                                            <small style="display: block; color: #aaa; font-weight: bold; font-size: 0.65rem; margin-top: 8px; letter-spacing: 2px;">2026 SEASON</small>
                                        </td>
                                        <td>
                                            <span class="status-pill"><i class="fa-solid fa-hourglass-half"></i> Pending</span>
                                        </td>
                                        <td>
                                            <a href="update-status.php?id=<?php echo $row['id']; ?>&action=approve" class="btn-action btn-approve"><i class="fa-solid fa-check"></i> Approve</a>
                                            <a href="update-status.php?id=<?php echo $row['id']; ?>&action=decline" class="btn-action btn-decline"><i class="fa-solid fa-xmark"></i></a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="empty-state">
                            <i class="fa-solid fa-mug-hot"></i>
                            <p>No pending requests. You're all caught up!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>

    <script>
        const observerOptions = {
            root: null,
            rootMargin: '0px',
            threshold: 0.1 
        };

        const scrollObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-slide-up').forEach(el => {
            scrollObserver.observe(el);
        });
    </script>

</body>
</html>