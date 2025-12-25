<?php
session_start();

// If already logged in, redirect to account page
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header("Location: account.php");
    exit();
}

// Database connection
$con = mysqli_connect('localhost', 'root', '', 'hooksyarns');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$error = "";

// Handle sign in
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signin'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    
    // Check if user exists
    $query = "SELECT * FROM usr_delts WHERE email='$email'";
    $result = mysqli_query($con, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        
        // Set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_country'] = $user['country'];
        $_SESSION['user_age'] = $user['age'];
        $_SESSION['account_type'] = $user['acc_type'];
        $_SESSION['logged_in'] = true;
        
        // Redirect to account page
        header("Location: account.php");
        exit();
    } else {
        $error = "Email not found. Please create an account first.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In - Hooks & Yarns</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f5d5d5;
            position: relative;
            overflow-x: hidden;
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
        .login-container {
            max-width: 500px;
            margin: 100px auto;
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(139, 75, 95, 0.2);
        }
        .login-container h2 {
            color: #8b4b5f;
            text-align: center;
            margin-bottom: 30px;
            font-size: 32px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            color: #8b4b5f;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 16px;
        }
        .form-group input[type="email"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #f5d5d5;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        .form-group input[type="email"]:focus {
            outline: none;
            border-color: #8b4b5f;
        }
        .submit-btn {
            width: 100%;
            padding: 14px;
            background-color: #8b4b5f;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .submit-btn:hover {
            background-color: #6d3a4a;
        }
        .message {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            font-weight: bold;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .signup-link {
            text-align: center;
            margin-top: 20px;
            color: #8b4b5f;
        }
        .signup-link a {
            color: #8b4b5f;
            font-weight: bold;
            text-decoration: none;
        }
        .signup-link a:hover {
            text-decoration: underline;
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
        
        <div class="login-container">
            <h2>Sign In</h2>
            
            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <button type="submit" name="signin" class="submit-btn">Sign In</button>
            </form>
            
            <div class="signup-link">
                Don't have an account? <a href="login.php">Create Account</a>
            </div>
        </div>
    </div>
</body>
</html>