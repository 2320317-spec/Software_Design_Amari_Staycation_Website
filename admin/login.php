<?php
session_start();
// If already logged in, bypass the gate
if(isset($_SESSION['admin_id'])) { header('Location: dashboard.php'); exit(); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Amari Alabang</title>
    <link rel="stylesheet" href="../css/style.css">
    <style>
        body {
            background-color: var(--cream); /* Using our soft cream background */
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .login-card {
            width: 100%;
            max-width: 400px;
            background: var(--white);
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 35px var(--shadow-tint);
            border-top: 8px solid var(--mahogany); /* The luxury "brand bar" */
            text-align: center;
        }

        .login-card h2 {
            color: var(--mahogany);
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 2px;
            font-size: 1.5rem;
        }

        .login-card p {
            color: var(--coffee);
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--mahogany);
            text-transform: uppercase;
        }

        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #e1e1e1;
            border-radius: 8px;
            background-color: #fcfcfc;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--gold);
            box-shadow: 0 0 0 3px rgba(194, 166, 110, 0.1);
            background-color: var(--white);
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background-color: var(--gold);
            color: var(--white);
            border: none;
            border-radius: 8px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            background-color: var(--mahogany);
            transform: translateY(-2px);
        }

        .back-link {
            display: block;
            margin-top: 25px;
            color: #aaa;
            text-decoration: none;
            font-size: 0.85rem;
        }

        .back-link:hover {
            color: var(--gold);
        }
    </style>
</head>
<body>

<div class="login-card">
    <h2>Amari Alabang</h2>
    <p>Host Management Portal</p>

    <?php if(isset($_GET['error'])): ?>
        <div style="background: #fdf2f2; color: #9b1c1c; padding: 10px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem;">
            Invalid credentials. Please try again.
        </div>
    <?php endif; ?>

    <form action="process-login.php" method="POST">
        <div class="form-group">
            <label>Employee ID / Email</label>
            <input type="text" name="username" class="form-control" placeholder="Enter your ID" required>
        </div>

        <div class="form-group">
            <label>Security Key</label>
            <input type="password" name="password" class="form-control" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn-login">Unlock Dashboard</button>
    </form>

    <a href="../index.php" class="back-link">← Return to Guest Site</a>
</div>

</body>
</html>