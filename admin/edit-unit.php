<?php
// 1. Call the Security Guard
include('auth.php');

// 2. Connect to the plumbing
include('../includes/db-config.php');

// 3. Get the ID from the URL (the ?id=X part)
if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // 4. Fetch the specific unit data using secure prepared statements
    $stmt = $conn->prepare("SELECT * FROM units WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $unit = $result->fetch_assoc();

    if(!$unit) { die("Error: Unit not found in the Amari database."); }
} else {
    header("Location: manage-units.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Property | Amari Alabang</title>
    
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

        /* --- FORM ARCHITECTURE --- */
        .form-container { width: 100%; max-width: 700px; background: var(--white); padding: 50px; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); border-top: 4px solid var(--amari-tan); }
        .form-header { margin-bottom: 30px; padding-bottom: 20px; border-bottom: 1px solid #eee; }
        .form-header h2 { font-size: 1.5rem; color: var(--amari-navy); margin-bottom: 5px; }
        .form-header p { color: var(--text-muted); font-size: 0.9rem; }

        .form-group { margin-bottom: 25px; }
        label { display: block; margin-bottom: 10px; font-weight: 700; color: var(--amari-navy); text-transform: uppercase; font-size: 0.75rem; letter-spacing: 1px; }
        
        input, textarea, select { width: 100%; padding: 15px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 0.95rem; background: #fafafa; font-family: 'Lato', sans-serif; transition: 0.3s; color: var(--text-dark); }
        input:focus, textarea:focus, select:focus { border-color: var(--amari-tan); outline: none; background: white; box-shadow: 0 0 0 3px rgba(176, 136, 90, 0.1); }
        
        /* Flexbox for side-by-side inputs */
        .form-row { display: flex; gap: 20px; }
        .form-row .form-group { flex: 1; margin-bottom: 0; }

        .btn-save { background: var(--amari-navy); color: white; border: none; padding: 16px; border-radius: 4px; cursor: pointer; font-weight: bold; width: 100%; text-transform: uppercase; letter-spacing: 2px; font-size: 0.85rem; transition: 0.4s; margin-top: 20px; }
        .btn-save:hover { background: var(--amari-tan); transform: translateY(-2px); box-shadow: 0 10px 20px rgba(176, 136, 90, 0.2); }
        
        .btn-discard { display: block; text-align: center; margin-top: 20px; color: var(--text-muted); text-decoration: none; font-size: 0.8rem; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; }
        .btn-discard:hover { color: #d9534f; }

        /* Animation */
        .fade-slide-up { opacity: 0; transform: translateY(40px); animation: fadeUp 0.8s ease-out forwards; }
        @keyframes fadeUp { to { opacity: 1; transform: translate(0); } }
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
            <h1>Refine Property</h1>
            
            <div class="topbar-right">
                <a href="manage-units.php" class="btn-back"><i class="fa-solid fa-arrow-left"></i> Back to Inventory</a>
            </div>
        </header>

        <div class="content-area">
            
            <div class="form-container fade-slide-up">
                <div class="form-header">
                    <h2>Property Specifications</h2>
                    <p>Updating ID #<?php echo htmlspecialchars($unit['id']); ?> - <?php echo htmlspecialchars($unit['title']); ?></p>
                </div>

                <form action="update-unit-logic.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($unit['id']); ?>">

                    <div class="form-group">
                        <label><i class="fa-solid fa-tag" style="margin-right: 5px; color: var(--amari-tan);"></i> Property Title</label>
                        <input type="text" name="title" value="<?php echo htmlspecialchars($unit['title']); ?>" required>
                    </div>

                    <div class="form-row form-group">
                        <div class="form-group">
                            <label><i class="fa-solid fa-peso-sign" style="margin-right: 5px; color: var(--amari-tan);"></i> Price / Night</label>
                            <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($unit['price_per_night']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fa-solid fa-toggle-on" style="margin-right: 5px; color: var(--amari-tan);"></i> Status</label>
                            <select name="status">
                                <option value="available" <?php if($unit['status'] == 'available') echo 'selected'; ?>>Available</option>
                                <option value="maintenance" <?php if($unit['status'] == 'maintenance') echo 'selected'; ?>>Maintenance</option>
                                <option value="booked" <?php if($unit['status'] == 'booked') echo 'selected'; ?>>Booked</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-top: 25px;">
                        <label><i class="fa-solid fa-star" style="margin-right: 5px; color: var(--amari-tan);"></i> Key Amenities (Comma separated)</label>
                        <input type="text" name="amenities" value="<?php echo htmlspecialchars($unit['amenities']); ?>">
                    </div>

                    <div class="form-group">
                        <label><i class="fa-solid fa-align-left" style="margin-right: 5px; color: var(--amari-tan);"></i> Detailed Description</label>
                        <textarea name="description" rows="6"><?php echo htmlspecialchars($unit['description']); ?></textarea>
                    </div>

                    <button type="submit" class="btn-save"><i class="fa-solid fa-floppy-disk" style="margin-right: 8px;"></i> Save Changes</button>
                    <a href="manage-units.php" class="btn-discard">Discard Changes</a>
                </form>
            </div>

        </div>
    </main>

</body>
</html>