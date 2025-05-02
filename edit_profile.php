<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'includes/db.php';
require_once 'classes/User.php';

$db = (new Database())->getConnection();
$userObj = new User($db);
$message = '';
$messageType = '';

// Get current user data
$userProfile = $userObj->getProfile($_SESSION['user_id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $current_password = !empty($_POST['current_password']) ? $_POST['current_password'] : null;
    $new_password = !empty($_POST['new_password']) ? $_POST['new_password'] : null;

    // Validate inputs
    if (empty($name) || empty($email)) {
        $message = "Name and email are required fields.";
        $messageType = "error";
    } else {
        // Update profile
        $result = $userObj->updateProfile(
            $_SESSION['user_id'],
            $name,
            $email,
            $current_password,
            $new_password
        );

        if ($result['success']) {
            $message = $result['message'];
            $messageType = "success";
            // Refresh user data after successful update
            $userProfile = $userObj->getProfile($_SESSION['user_id']);
        } else {
            $message = $result['message'];
            $messageType = "error";
        }
    }
}
?>

<?php include 'includes/header.php'; ?>

<main class="container">
    <div class="form-container">
        <h1 class="form-header">Edit Profile</h1>
        
        <?php if ($message): ?>
            <div class="<?php echo $messageType; ?>-message">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($userProfile['name']); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($userProfile['email']); ?>" required>
            </div>

            <div class="password-section">
                <h3>Change Password (Optional)</h3>
                <div class="form-group">
                    <label for="current_password">Current Password</label>
                    <input type="password" id="current_password" name="current_password">
                </div>

                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password">
                </div>
            </div>

            <div class="button-group">
                <a href="users.php" class="btn-cancel">Cancel</a>
                <button type="submit" class="btn-save">Save Changes</button>
            </div>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<style>
    :root {
        --bg-color: #e8f0fe;
        --surface-color: #ffffff;
        --primary-color: rgb(71, 151, 243);
        --primary-light: #757de8;
        --primary-dark: #303f9f;
        --accent-color: #42a5f5;
        --text-color: #2c3e50;
        --text-secondary: rgb(8, 13, 15);
        --error-color: #f44336;
        --success-color: #4CAF50;
        --border-color: #e0e0e0;
        --input-bg: #f5f8ff;
        --shadow-color: rgba(63, 81, 181, 0.15);
    }
    
    body {
        font-family: 'Georgia', serif;
        line-height: 1.6;
        color: var(--text);
        background: url('images/image4.jpg') no-repeat center center fixed;
        background-size: cover;
    }
    
    .container {
        max-width: 800px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .form-container {
        background-color: var(--surface-color);
        border-radius: 8px;
        box-shadow: 0 4px 6px var(--shadow-color);
        overflow: hidden;
    }

    .form-header {
        background-color: var(--primary-color);
        color: white;
        padding: 30px;
        margin: 0;
        text-align: center;
        font-size: 24px;
        text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
    }

    form {
        padding: 30px;
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        color: var(--text-color);
        font-weight: 500;
    }

    input {
        width: 100%;
        padding: 12px;
        border: 1px solid var(--border-color);
        border-radius: 4px;
        background-color: var(--input-bg);
        color: var(--text-color);
        transition: border-color 0.3s, box-shadow 0.3s;
    }

    input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(63, 81, 181, 0.1);
    }

    .password-section {
        border-top: 1px solid var(--border-color);
        margin-top: 30px;
        padding-top: 20px;
    }

    .password-section h3 {
        color: var(--text-color);
        margin-bottom: 20px;
        font-size: 18px;
    }

    .button-group {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
    }

    .btn-save, .btn-cancel {
        padding: 12px 24px;
        border-radius: 4px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
    }

    .btn-save {
        background-color: var(--primary-color);
        color: white;
        border: none;
    }

    .btn-cancel {
        background-color: transparent;
        color: var(--text-color);
        border: 1px solid var(--border-color);
    }

    .btn-save:hover {
        background-color: var(--primary-dark);
    }

    .btn-cancel:hover {
        background-color: rgba(63, 81, 181, 0.05);
        color: var(--primary-color);
        border-color: var(--primary-light);
    }

    .success-message {
        background-color: rgba(76, 175, 80, 0.05);
        border-left: 4px solid var(--success-color);
        color: var(--success-color);
        padding: 15px;
        margin: 0 30px;
        margin-top: 20px;
        border-radius: 4px;
    }

    .error-message {
        background-color: rgba(244, 67, 54, 0.05);
        border-left: 4px solid var(--error-color);
        color: var(--error-color);
        padding: 15px;
        margin: 0 30px;
        margin-top: 20px;
        border-radius: 4px;
    }

    @media (max-width: 576px) {
        .container {
            margin: 20px auto;
        }
        
        .form-header {
            padding: 20px;
        }
        
        form {
            padding: 20px;
        }
        
        .button-group {
            flex-direction: column-reverse;
        }
        
        .btn-save, .btn-cancel {
            width: 100%;
        }
    }
    footer {
        background-color: black;
        color: white;
        text-align: center;
        padding: 5px;
        margin-top: 50px;
    }
</style>