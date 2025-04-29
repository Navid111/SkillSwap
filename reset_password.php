<?php
session_start();
require_once 'includes/db.php';
require_once 'classes/User.php';

$db = (new Database())->getConnection();
$userObj = new User($db);
$error = '';
$success = '';

// Domain configuration
$domain = "http://skillswap.infy.uk"; // Update this with your actual domain

// Handle request for password reset
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'])) {
        $email = $_POST['email'];
        $token = $userObj->generateResetToken($email);
        
        if ($token) {
            // Create reset link
            $resetLink = $domain . "/reset_password.php?token=" . $token;
            
            // Email headers
            $to = $email;
            $subject = "Password Reset Request - SkillSwap";
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: SkillSwap <noreply@skillswap.infy.uk>' . "\r\n";
            
            // Email message
            $message = "
            <html>
            <head>
                <title>Reset Your Password</title>
            </head>
            <body>
                <h2>Password Reset Request</h2>
                <p>We received a request to reset your password. Click the link below to set a new password:</p>
                <p><a href='{$resetLink}'>{$resetLink}</a></p>
                <p>This link will expire in 1 hour.</p>
                <p>If you didn't request this, please ignore this email.</p>
                <br>
                <p>Best regards,</p>
                <p>The SkillSwap Team</p>
            </body>
            </html>
            ";
            
            // Send email
            if(mail($to, $subject, $message, $headers)) {
                $success = "A password reset link has been sent to your email address. Please check your inbox and spam folder.";
            } else {
                $error = "Failed to send reset email. Please try again later.";
            }
        } else {
            $error = "Email address not found.";
        }
    } else if (isset($_POST['password']) && isset($_POST['token'])) {
        $token = $_POST['token'];
        $password = $_POST['password'];
        $user_id = $userObj->verifyResetToken($token);
        
        if ($user_id && $userObj->resetPassword($user_id, $password)) {
            $success = "Password has been reset successfully. You can now <a href='login.php'>login</a> with your new password.";
        } else {
            $error = "Invalid or expired reset token.";
        }
    }
}

// Check if there's a reset token in the URL
$token = isset($_GET['token']) ? $_GET['token'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Tutorial Platform</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Georgia', serif;
        }

        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: url('images/image3.jpg') no-repeat center center fixed;
            background-size: cover;
        }

        .reset-container {
            width: 400px;
            padding: 40px;
            background-color: rgba(0, 0, 0, 0.9);
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .reset-title {
            font-size: 24px;
            font-weight: 600;
            color: #ffffff;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-size: 16px;
            font-weight: 500;
            color: #ffffff;
            margin-bottom: 10px;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            background-color: white;
            border-radius: 6px;
            font-size: 16px;
            color: black;
            transition: border-color 0.3s;
        }

        .form-input:focus {
            outline: none;
            border-color: #395b90;
        }

        .reset-btn {
            width: 100%;
            padding: 14px;
            background-color: #395b90;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .reset-btn:hover {
            background-color: #2c4a80;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #ffffff;
            text-decoration: none;
        }

        .back-link:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #ff4444;
            margin-bottom: 20px;
            text-align: center;
            padding: 10px;
            background-color: rgba(255, 68, 68, 0.1);
            border-radius: 4px;
        }

        .success-message {
            color: #00C851;
            margin-bottom: 20px;
            text-align: center;
            padding: 10px;
            background-color: rgba(0, 200, 81, 0.1);
            border-radius: 4px;
        }

        .success-message a {
            color: #00C851;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <h1 class="reset-title">
            <?php echo $token ? 'Reset Your Password' : 'Forgot Password'; ?>
        </h1>
        
        <?php if($error): ?>
            <div class="error-message"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="success-message"><?php echo $success; ?></div>
        <?php else: ?>
            <?php if(!$token): ?>
                <!-- Request Reset Form -->
                <form action="reset_password.php" method="POST">
                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input" 
                               placeholder="Enter your email" required>
                    </div>
                    <button type="submit" class="reset-btn">Request Password Reset</button>
                </form>
            <?php else: ?>
                <!-- Reset Password Form -->
                <form action="reset_password.php" method="POST">
                    <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <input type="password" id="password" name="password" class="form-input" 
                               placeholder="Enter new password" required>
                    </div>
                    <button type="submit" class="reset-btn">Reset Password</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>
        
        <a href="login.php" class="back-link">Back to Login</a>
    </div>
</body>
</html>