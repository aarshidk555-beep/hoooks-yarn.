<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: signin.php");
    exit();
}

// Database connection
$con = mysqli_connect('localhost', 'root', '', 'hooksyarns');

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$success = "";
$error = "";
$user_id = $_SESSION['user_id'];

// Get current user data
$query = "SELECT * FROM usr_delts WHERE id='$user_id'";
$result = mysqli_query($con, $query);
$user = mysqli_fetch_assoc($result);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $age = mysqli_real_escape_string($con, $_POST['age']);
    $country = mysqli_real_escape_string($con, $_POST['country']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $acc_type = mysqli_real_escape_string($con, $_POST['accountType']);
    
    // Check if email is being changed and if new email already exists
    if ($email != $user['email']) {
        $check_email = mysqli_query($con, "SELECT * FROM usr_delts WHERE email='$email' AND id!='$user_id'");
        if (mysqli_num_rows($check_email) > 0) {
            $error = "This email is already registered by another user!";
        }
    }
    
    // If no email conflict, update the profile
    if (empty($error)) {
        $stmt = mysqli_prepare($con, "UPDATE usr_delts SET name=?, age=?, country=?, email=?, acc_type=? WHERE id=?");
        mysqli_stmt_bind_param($stmt, "sisssi", $name, $age, $country, $email, $acc_type, $user_id);
        
        if (mysqli_stmt_execute($stmt)) {
            // Update session variables
            $_SESSION['user_name'] = $name;
            $_SESSION['user_email'] = $email;
            $_SESSION['user_country'] = $country;
            $_SESSION['user_age'] = $age;
            $_SESSION['account_type'] = $acc_type;
            
            $success = "Profile updated successfully!";
            
            // Refresh user data
            $result = mysqli_query($con, "SELECT * FROM usr_delts WHERE id='$user_id'");
            $user = mysqli_fetch_assoc($result);
        } else {
            $error = "Error updating profile: " . mysqli_error($con);
        }
        
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile - Hooks & Yarns</title>
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
        .edit-container {
            max-width: 600px;
            margin: 60px auto;
            background-color: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(139, 75, 95, 0.2);
        }
        .edit-container h2 {
            color: #8b4b5f;
            text-align: center;
            margin-bottom: 10px;
            font-size: 32px;
        }
        .subtitle {
            text-align: center;
            color: #8b4b5f;
            margin-bottom: 30px;
            font-size: 16px;
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
        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        .btn {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            text-align: center;
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
        .profile-preview {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #f5d5d5;
        }
        .profile-pic-preview {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background-color: #8b4b5f;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            color: white;
            font-weight: bold;
            margin: 0 auto 15px;
        }
        .current-email {
            color: #8b4b5f;
            font-size: 14px;
            font-style: italic;
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
        
        <div class="edit-container">
            <div class="profile-preview">
                <div class="profile-pic-preview" id="profilePic">
                    <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
                </div>
                <p class="subtitle">Update your profile information</p>
            </div>
            
            <h2>Edit Profile</h2>
            
            <?php if ($success): ?>
                <div class="message success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="age">Age</label>
                    <input type="number" id="age" name="age" min="1" max="120" value="<?php echo htmlspecialchars($user['age']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="country">Country</label>
                    <input type="text" id="country" name="country" value="<?php echo htmlspecialchars($user['country']); ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    <p class="current-email">Current: <?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                
                <div class="radio-group">
                    <label>Account Type</label>
                    <div class="radio-options">
                        <div class="radio-option">
                            <input type="radio" id="buyer" name="accountType" value="buyer" 
                                <?php echo ($user['acc_type'] == 'buyer') ? 'checked' : ''; ?> required>
                            <span>Buyer</span>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="seller" name="accountType" value="seller" 
                                <?php echo ($user['acc_type'] == 'seller') ? 'checked' : ''; ?> required>
                            <span>Seller</span>
                        </div>
                    </div>
                </div>
                
                <div class="button-group">
                    <button type="submit" name="update" class="btn btn-primary">Save Changes</button>
                    <a href="account.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        // Update profile picture preview when name changes
        document.getElementById('name').addEventListener('input', function() {
            const firstLetter = this.value.charAt(0).toUpperCase();
            document.getElementById('profilePic').textContent = firstLetter || '?';
        });
    </script>
</body>
</html>