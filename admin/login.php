<?php
session_start();
// If already logged in, bypass the gate
if(isset($_SESSION['admin_id'])) { 
    header('Location: dashboard.php'); 
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Amari Alabang</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        :root {
            --amari-tan: #b0885a;
            --amari-navy: #042e47;
        }

        body { 
            margin: 0; 
            font-family: 'Lato', sans-serif; 
            /* Immersive full-screen property background */
            background: linear-gradient(rgba(4, 46, 71, 0.85), rgba(4, 46, 71, 0.95)), url('../assets/images/amari/hero1.jpg') center/cover;
            height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
        }

        .login-card {
            background: white;
            width: 100%;
            max-width: 420px;
            padding: 50px 40px;
            border-radius: 8px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.5);
            text-align: center;
            position: relative;
            animation: fadeIn 0.8s ease-out forwards;
        }

        /* The Gold Accent Top */
        .login-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 5px; background: var(--amari-tan); border-radius: 8px 8px 0 0;
        }

        .login-card h2 { font-family: 'Playfair Display', serif; color: var(--amari-navy); margin-top: 0; font-size: 2.2rem; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 5px; }
        .login-card p { color: #888; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 35px; font-weight: bold; }

        .form-group { margin-bottom: 25px; text-align: left; }
        .form-group label { display: block; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; color: var(--amari-navy); margin-bottom: 8px; letter-spacing: 1px; }
        
        .input-wrapper { position: relative; }
        .input-wrapper i { position: absolute; left: 15px; top: 16px; color: #ccc; transition: 0.3s; }
        
        .form-control { 
            width: 100%; padding: 15px 15px 15px 45px; border: 1px solid #e0e0e0; border-radius: 4px; font-family: 'Lato', sans-serif; font-size: 1rem; box-sizing: border-box; transition: 0.3s; background: #fafafa;
        }
        
        .form-control:focus { border-color: var(--amari-tan); outline: none; background: white; box-shadow: 0 0 0 3px rgba(176, 136, 90, 0.1); }
        .form-control:focus + i, .input-wrapper:focus-within i { color: var(--amari-tan); }

        .btn-login {
            background: var(--amari-navy); color: white; border: none; padding: 16px; width: 100%; border-radius: 4px; font-weight: bold; text-transform: uppercase; letter-spacing: 2px; cursor: pointer; transition: 0.4s; font-size: 0.85rem; margin-top: 10px;
        }
        .btn-login:hover { background: var(--amari-tan); transform: translateY(-2px); box-shadow: 0 10px 20px rgba(176, 136, 90, 0.3); }

        .error-msg { background: #fff5f5; color: #c62828; padding: 12px; border-radius: 4px; font-size: 0.8rem; margin-bottom: 25px; border-left: 4px solid #c62828; text-align: left; font-weight: bold; }

        .back-link { display: block; margin-top: 30px; color: #aaa; text-decoration: none; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; }
        .back-link:hover { color: var(--amari-tan); }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Amari</h2>
    <p>Host Management Portal</p>

    <?php if(isset($_GET['error'])): ?>
        <div class="error-msg">
            <i class="fa-solid fa-triangle-exclamation" style="margin-right: 5px;"></i> Invalid credentials. Please try again.
        </div>
    <?php endif; ?>

    <form action="process-login.php" method="POST">
        <div class="form-group">
            <label>Employee ID / Email</label>
            <div class="input-wrapper">
                <input type="text" name="username" class="form-control" placeholder="Enter your ID" required>
                <i class="fa-solid fa-user"></i>
            </div>
        </div>

        <div class="form-group">
            <label>Security Key</label>
            <div class="input-wrapper">
                <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                <i class="fa-solid fa-lock"></i>
            </div>
        </div>

        <button type="submit" class="btn-login">Unlock Dashboard</button>
    </form>

    <a href="../index.php" class="back-link"><i class="fa-solid fa-arrow-left" style="margin-right: 5px;"></i> Return to Guest Site</a>
</div>

</body>
</html>