<?php
// 1. Call the Security Guard
include('auth.php');

// 2. Connect to the plumbing
include('../includes/db-config.php');

// 3. Fetch all "Pending" bookings for review 
$sql = "SELECT * FROM bookings WHERE status = 'pending' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Host Dashboard - Amari Alabang</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        /* Table Specific UX Polish */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background-color: #f8f9fa; color: #555; font-weight: 600; text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; padding: 15px; border-bottom: 2px solid #eee; text-align: left; }
        td { padding: 15px; border-bottom: 1px solid #f1f1f1; vertical-align: middle; }
        tr:hover { background-color: #fafafa; }
        
        .status-pill { background: #fff8e1; color: #f57f17; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: bold; text-transform: uppercase; }
        .nav-admin { background: white; padding: 10px 0; border-bottom: 1px solid #eee; margin-bottom: 30px; }
        .nav-admin a { margin-right: 20px; text-decoration: none; color: #666; font-size: 0.9rem; font-weight: 500; }
        .nav-admin a:hover { color: var(--primary-color); }
        .nav-admin a.active { color: var(--primary-color); border-bottom: 2px solid var(--primary-color); padding-bottom: 5px; }
    </style>
</head>
<body>

<header>
    <div class="container" style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Host Administration</h1>
            <p>Amari Alabang Management Portal</p>
        </div>
        <a href="logout.php" class="btn" style="background: #e74c3c; padding: 8px 20px;">Logout</a>
    </div>
</header>

<nav class="nav-admin">
    <div class="container">
        <a href="dashboard.php" class="active">📅 Reservations</a>
        <a href="manage-units.php">🏠 Manage Units</a>
        <a href="../index.php" target="_blank">🌐 View Live Site</a>
    </div>
</nav>

<main class="container">
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="margin: 0;">Pending Requests</h2>
            <span style="color: #888; font-size: 0.9rem;"><?php echo $result->num_rows; ?> requests waiting</span>
        </div>
        
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
                                <span style="font-weight: 600; color: var(--primary-color); display: block;">
                                    <?php echo $row['guest_title'] . " " . $row['guest_name']; ?>
                                </span>
                                <small style="color: #888;"><?php echo $row['guest_email']; ?></small>
                            </td>
                            <td>
                                <span style="font-weight: 500;">
                                    <?php echo date("M d", strtotime($row['check_in'])); ?> - 
                                    <?php echo date("M d", strtotime($row['check_out'])); ?>
                                </span>
                                <small style="display: block; color: #aaa; font-size: 0.7rem;">2026 Season</small>
                            </td>
                            <td>
                                <span class="status-pill">Pending</span>
                            </td>
                            <td>
                                <a href="update-status.php?id=<?php echo $row['id']; ?>&action=approve" class="btn" style="background: var(--success); font-size: 0.75rem; padding: 6px 12px; margin-right: 5px;">Approve</a>
                                <a href="update-status.php?id=<?php echo $row['id']; ?>&action=decline" class="btn" style="background: #e74c3c; font-size: 0.75rem; padding: 6px 12px;">Decline</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="text-align: center; padding: 50px 0;">
                <p style="color: #aaa; font-style: italic;">No pending requests. You're all caught up!</p>
            </div>
        <?php endif; ?>
    </div>
</main>

</body>
</html>