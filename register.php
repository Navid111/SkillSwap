<?php
session_start();
require_once 'includes/db.php';
require_once 'classes/User.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $db = (new Database())->getConnection();
    $userObj = new User($db);
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    
    if($userObj->register($name, $email, $password, $role)) {
        // Retrieve the user and start session
        $user = $userObj->login($email, $password);
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        header("Location: users.php");
        exit;
    } else {
        $error = "User Already Exists!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Tutorial Platform</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary: #395b90;
            --primary-dark: #395b90;
            --accent: #395b90;
            --text: #ffffff;
            --text-light: #395b90;
            --bg-light: black;
            --error: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Georgia, serif;
        }

        body { background-image: url('image2.jpg'); background-size: cover; background-repeat: no-repeat; background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #395b90;
            overflow: hidden;
        }

        .auth-container {
            display: flex;
            width: 900px;
            height: 600px;
            background: rgba(0, 0, 0, 0.90);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .illustration-side {
            width: 45%;
            background: linear-gradient(135deg, #395b90, #395b90);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: black;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .night-scene {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('images/night-illustration.svg') no-repeat center;
            background-size: cover;
            z-index: 1;
        }

        .welcome-content {
            position: relative;
            z-index: 2;
            text-align: center;
        }

        .welcome-content h2 {
            font-size: 38px;
            margin-bottom: 20px;
        }

        .welcome-content p {
            font-size: 16px;
            line-height: 1.6;
            opacity: 0.9;
        }

        .social-icons {
            margin-top: 40px;
            display: flex;
            gap: 15px;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .social-icon:hover {
            background: rgba(255, 255, 255, 0.4);
            transform: translateY(-3px);
        }

        .social-icon i {
            color: black;
            font-size: 18px;
        }

        .form-side {
            width: 55%;
            padding: 40px;
            display: flex;
            flex-direction: column;
        }

        
.form-links {
  display: flex;
  gap: 10px;
}

/* Base link styles (unvisited & visited) */
.form-links a:link,
.form-links a:visited {
  color: var(--text);
  text-decoration: none !important;
  font-weight: 500;
  transition: color 0.3s ease;
}

/* Hover & active states */
.form-links a:hover,
.form-links a:active {
  color: var(--primary-dark);
}


        .form-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .form-header h1 {
            font-size: 28px;
            color: var(--text);
            font-weight: 600;
        }

        .sign-in-link {
            color: var(--text);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .sign-in-link:hover {
            color: var(--primary-dark);
        }

        .register-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 6px;
            font-size: 14px;
            color: var(--text);
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            padding: 12px 16px;
            border: 1px solid #395b90;
            border-radius: 8px;
            font-size: 15px;
            transition: border 0.3s ease;
        }

        .form-group input:focus,
        .form-group select:focus {
            border-color: var(--accent);
            outline: none;
        }

        .error-message {
            color: var(--error);
            font-size: 14px;
            margin-bottom: 15px;
        }

        .submit-btn {
            background-color: var(--primary);
            color: #ffffff;
            border: none;
            padding: 14px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .submit-btn:hover {
            background-color: var(--primary-dark);
        }

        @media (max-width: 900px) {
            .auth-container {
                width: 95%;
                height: auto;
                flex-direction: column;
            }

            .illustration-side, .form-side {
                width: 100%;
            }

            .illustration-side {
                height: 250px;
                padding: 20px;
            }

            .form-side {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body style="background-image: url('images/image3.jpg'); background-size: cover; background-repeat: no-repeat; background-position: center;">
    <div class="auth-container">
        <div class="illustration-side">
            <div class="night-scene"></div>
            <div class="welcome-content">
                <h2>WELCOME<br/>TO<br/>SKILL-SWAP!</h2>
                <p></p>
            </div>
        </div>
        
        <div class="form-side">
            <div class="form-header">
                <h1>Register</h1>
            <div class="form-links">
                <a href="index.php" class="sing-in-link">Home</a>
				<br/>
                <a href="login.php" class="sign-in-link">Sign In</a>
        </div>
    </div>
            <?php if($error): ?>
                <p class="error-message"><?php echo $error; ?></p>
            <?php endif; ?>
            
            <form action="register.php" method="POST" class="register-form">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" placeholder="Create a password" required>
                </div>
                
                <div class="form-group">
                    <label for="role">Role</label>
                    <select id="role" name="role" required>
                        <option value="" disabled selected>Select your role</option>
                        <option value="teacher">Teacher</option>
                        <option value="student">Student</option>
                    </select>
                </div>

                <button type="submit" class="submit-btn">Sign Up</button>
            </form>
        </div>
    </div>
</body>
</html>