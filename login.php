<?php
session_start();
require_once 'includes/db.php';
require_once 'classes/User.php';

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $db = (new Database())->getConnection();
    $userObj = new User($db);
    
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $user = $userObj->login($email, $password);
    
    if($user) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_role'] = $user['role'];
        header("Location: users.php");
        exit;
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tutorial Platform</title>
    <style>
        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Georgia', serif; /* Updated to Georgia */
        }

        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #395b90;
        }

        .login-container {
            width: 960px;
            max-width: 95%;
            height: auto;
            display: flex;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .form-side {
            width: 60%;
            padding: 40px;
            background-color: rgba(0, 0, 0, 0.9);
            display: flex;
            flex-direction: column;
        }

        .login-title {
            font-size: 28px;
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
        padding: 14px 18px;
        border: 1px solid #ddd;
        background-color: white; /* changed to white */
        border-radius: 6px;
        font-size: 16px;
        color: black; /* changed to black for better contrast */
        transition: border-color 0.3s, background-color 0.3s;
    }

    .form-input:focus {
        outline: none;
        border-color: #395b90;
        background-color: #f9f9f9;
    }

        .login-btn {
            width: 100%;
            padding: 16px;
            background-color: #395b90;
            color: black;
            border: none;
            border-radius: 6px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            margin-top: 15px;
        }

        .login-btn:hover {
            background-color: #395b90;
        }

        .form-footer {
            display: flex;
            justify-content: flex-start;
            margin-top: 20px;
            font-size: 16px;
            color: #555;
        }

        .remember-me {
            display: flex;
            align-items: center;
        }

        .remember-me input {
            margin-right: 8px;
            width: 18px;
            height: 18px;
        }

        .welcome-side {
            width: 40%;
            background-color: rgba(57, 91, 144, 1);
            color: black;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 40px;
        }


        .welcome-title {
            font-size: 32px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .welcome-text {
            font-size: 18px;
            margin-bottom: 40px;
            opacity: 0.9;
        }

        .signup-btn {
        padding: 14px 30px;
        background-color: white; /* changed to white */
        color: black;
        border: 2px solid black;
        border-radius: 6px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
    }

    .home-link {
  display: block;             /* ensure it appears on its own line */
  margin-top: 15px;           /* space it from the Sign Up button */
  color: black;              /* plain black text */
  text-decoration: none;     /* no underline */
  font-size: 16px;           /* same font size as other text */
  font-weight: 500;          /* consistent font weight */
  text-align: center;        /* center under the button */
  transition: color 0.3s;
}

.home-link:hover {
  color: #ffffff;               /* a darker shade of black when hovered */
}

    .signup-btn:hover {
        background-color: black;
        color: white;
    }

        @media (max-width: 768px) {
            .login-container {
                flex-direction: column-reverse;
            }

            .form-side, .welcome-side {
                width: 100%;
            }

            .welcome-side {
                padding: 30px 20px;
            }

            .form-side {
                padding: 30px 20px;
            }
        }

        .error-message {
            color: red;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }
    </style>
</head>
<body style="background-image: url('images/image3.jpg'); background-size: cover; background-repeat: no-repeat; background-position: center;">
    <div class="login-container">
        <!-- Left side with form -->
        <div class="form-side">
            <h1 class="login-title">Log In</h1>
            
            <?php if($error): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form action="login.php" method="POST">
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="Enter your email" required>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-input" placeholder="Enter your password" required>
                </div>
                
                <button type="submit" class="login-btn">Log In</button>
                
                <div class="form-footer">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Remember Me</label>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Right side with welcome message -->
        <div class="welcome-side">
            <h2 class="welcome-title">Welcome Back</h2>
            <p class="welcome-text">Don't have an account yet?</p>
            <a href="register.php" class="signup-btn">Sign Up</a>
            <a href="index.php" class="home-link">Home</a>
        </div>
    </div>
</body>
</html>
