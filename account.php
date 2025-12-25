<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: signin.php");
    exit();
}

// Get user info from session
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];
$user_country = $_SESSION['user_country'];
$user_age = $_SESSION['user_age'];
$account_type = $_SESSION['account_type'];

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: home.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - Hooks & Yarns</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f5d5d5;
            position: relative;
            overflow-x: hidden;
            font-family: Arial, sans-serif;
        }
        .nav-link:hover {        
            color: #ffffff !important;
        }
        .background-icons {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 0;
            opacity: 0.4;
        }
        .icon {
            position: absolute;
        }
        .content {
            position: relative;
            z-index: 1;
        }
        .header {
            width: 100%;
            background-color: #f5d5d5;
            border-bottom: 3px solid #8b4b5f;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            box-sizing: border-box;
        }
        .logo h1 {
            color: #8b4b5f;
            margin: 0;
        }
        .logo p {
            color: #8b4b5f;
            font-style: italic;
            margin: 5px 0 10px 0;
        }
        .nav {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .nav-link {
            color: #8b4b5f;
            text-decoration: none;
            font-weight: bold;
            font-size: 30px;
            padding: 0 20px;
        }
        .separator {
            color: #8b4b5f;
            font-size: 30px;
            font-weight: bold;
        }
        .account-container {
            max-width: 900px;
            margin: 40px auto;
            padding: 20px;
        }
        .welcome-section {
            background-color: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(139, 75, 95, 0.2);
            margin-bottom: 30px;
            text-align: center;
        }
        .welcome-section h2 {
            color: #8b4b5f;
            font-size: 36px;
            margin: 0 0 10px 0;
        }
        .welcome-section p {
            color: #8b4b5f;
            font-size: 18px;
            margin: 5px 0;
        }
        .profile-section {
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(139, 75, 95, 0.2);
            margin-bottom: 30px;
        }
        .profile-header {
            display: flex;
            align-items: center;
            gap: 30px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f5d5d5;
        }
        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background-color: #8b4b5f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 50px;
            color: white;
            font-weight: bold;
        }
        .profile-info h3 {
            color: #8b4b5f;
            margin: 0 0 10px 0;
            font-size: 28px;
        }
        .profile-info p {
            color: #8b4b5f;
            margin: 5px 0;
            font-size: 16px;
        }
        .badge {
            display: inline-block;
            padding: 5px 15px;
            background-color: #8b4b5f;
            color: white;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 10px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }
        .info-card {
            background-color: #f5d5d5;
            padding: 20px;
            border-radius: 10px;
        }
        .info-card h4 {
            color: #8b4b5f;
            margin: 0 0 10px 0;
            font-size: 16px;
            font-weight: bold;
        }
        .info-card p {
            color: #8b4b5f;
            margin: 0;
            font-size: 18px;
        }
        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        .btn {
            padding: 12px 30px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background-color: #8b4b5f;
            color: white;
        }
        .btn-primary:hover {
            background-color: #6d3a4a;
        }
        .btn-secondary {
            background-color: #f5d5d5;
            color: #8b4b5f;
        }
        .btn-secondary:hover {
            background-color: #e5c5c5;
        }
        .btn-danger {
            background-color: #d9534f;
            color: white;
        }
        .btn-danger:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="background-icons">
        <span class="icon" style="top: 10%; left: 5%; font-size: 40px;">🧶</span>
        <span class="icon" style="top: 25%; left: 85%; font-size: 35px;">🪡</span>
        <span class="icon" style="top: 40%; left: 15%; font-size: 30px;">🧵</span>
        <span class="icon" style="top: 15%; left: 70%; font-size: 45px;">🧶</span>
        <span class="icon" style="top: 55%; left: 80%; font-size: 38px;">🪡</span>
        <span class="icon" style="top: 70%; left: 10%; font-size: 42px;">🧶</span>
        <span class="icon" style="top: 35%; left: 90%; font-size: 33px;">🧵</span>
        <span class="icon" style="top: 80%; left: 75%; font-size: 36px;">🧶</span>
        <span class="icon" style="top: 60%; left: 50%; font-size: 40px;">🪡</span>
        <span class="icon" style="top: 20%; left: 40%; font-size: 35px;">🧵</span>
        <span class="icon" style="top: 85%; left: 30%; font-size: 38px;">🧶</span>
        <span class="icon" style="top: 45%; left: 65%; font-size: 32px;">🪡</span>
        <span class="icon" style="top: 75%; left: 55%; font-size: 37px;">🧵</span>
        <span class="icon" style="top: 30%; left: 25%; font-size: 34px;">🧶</span>
        <span class="icon" style="top: 90%; left: 60%; font-size: 39px;">🪡</span>
    </div>
    
    <div class="content">
        <div class="header">
            <div class="logo">
                <h1>
                    Hooks & Yarns<span style="font-size: 55px; margin-left: 10px;">🧶</span>
                </h1>
                <p>Handcrafted with Love</p>
            </div>
            <div class="nav">
                <a href="home.html" class="nav-link">Home</a>
                <span class="separator">|</span>
                <a href="products.html" class="nav-link">Products</a>
                <span class="separator">|</span>
                <a href="about.html" class="nav-link">About</a>
                <span class="separator">|</span>
                <a href="account.php" class="nav-link">Account</a>
            </div>
        </div>
        
        <div class="account-container">
            <div class="welcome-section">
                <h2>Welcome Back, <?php echo htmlspecialchars($user_name); ?>! 👋</h2>
                <p>Manage your account and explore your crafting journey</p>
            </div>
            
            <div class="profile-section">
                <div class="profile-header">
                    <div class="profile-pic">
                        <?php echo strtoupper(substr($user_name, 0, 1)); ?>
                    </div>
                    <div class="profile-info">
                        <h3><?php echo htmlspecialchars($user_name); ?></h3>
                        <p><?php echo htmlspecialchars($user_email); ?></p>
                        <span class="badge"><?php echo ucfirst(htmlspecialchars($account_type)); ?></span>
                    </div>
                </div>
                
                <div class="info-grid">
                    <div class="info-card">
                        <h4>📧 Email</h4>
                        <p><?php echo htmlspecialchars($user_email); ?></p>
                    </div>
                    <div class="info-card">
                        <h4>🌍 Country</h4>
                        <p><?php echo htmlspecialchars($user_country); ?></p>
                    </div>
                    <div class="info-card">
                        <h4>🎂 Age</h4>
                        <p><?php echo htmlspecialchars($user_age); ?> years old</p>
                    </div>
                    <div class="info-card">
                        <h4>👤 Account Type</h4>
                        <p><?php echo ucfirst(htmlspecialchars($account_type)); ?></p>
                    </div>
                </div>
                
                <div class="action-buttons">
                    <a href="edit-profile.php" class="btn btn-primary">Edit Profile</a>
                    <a href="products.html" class="btn btn-secondary">Browse Products</a>
                    <a href="account.php?logout=true" class="btn btn-danger">Logout</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>