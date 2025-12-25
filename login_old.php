<?php
// Database connection at the top
$con = mysqli_connect('localhost', 'root', '', 'hooksyarns');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$success = "";
$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Get form data and sanitize
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $age = mysqli_real_escape_string($con, $_POST['age']);
    $country = mysqli_real_escape_string($con, $_POST['country']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $acc_type = mysqli_real_escape_string($con, $_POST['accountType']);

    // Better: Use prepared statements to prevent SQL injection
    $stmt = mysqli_prepare($con, "INSERT INTO usr_delts (name, age, country, email, acc_type) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sisss", $name, $age, $country, $email, $acc_type);
    
    if (mysqli_stmt_execute($stmt)) {
        $success = "Account created successfully!";
    } else {
        $error = "Error: " . mysqli_error($con);
    }
    
    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hooks & Yarns</title>
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
            margin: 60px auto;
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
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="number"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #f5d5d5;
            border-radius: 8px;
            font-size: 16px;
            box-sizing: border-box;
            transition: border-color 0.3s;
        }
        .form-group input[type="text"]:focus,
        .form-group input[type="email"]:focus,
        .form-group input[type="number"]:focus {
            outline: none;
            border-color: #8b4b5f;
        }
        .radio-group {
            margin-bottom: 25px;
        }
        .radio-group label {
            display: block;
            color: #8b4b5f;
            font-weight: bold;
            margin-bottom: 12px;
            font-size: 16px;
        }
        .radio-options {
            display: flex;
            gap: 30px;
        }
        .radio-option {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .radio-option input[type="radio"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }
        .radio-option span {
            color: #8b4b5f;
            font-size: 16px;
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
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
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
                    <a href="login.html" class="nav-link">Account</a>
            </div>
        </div>
        
        <div class="login-container">
            <h2>Create Account</h2>
            
            <?php if ($success): ?>
                <div class="message success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form id="loginForm" method="POST" action="">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" min="1" max="120" required>
                </div>
                
                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="radio-group">
                    <label>Account Type</label>
                    <div class="radio-options">
                        <div class="radio-option">
                            <input type="radio" id="buyer" name="accountType" value="buyer" required>
                            <span>Buyer</span>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="seller" name="accountType" value="seller" required>
                            <span>Seller</span>
                        </div>
                    </div>
                </div>
                
                <button type="submit" name="submit" class="submit-btn">Create Account</button>
            </form>
        </div>
    </div>
</body>
</html>