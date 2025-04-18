<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'includes/db.php';
require_once 'classes/Tutorial.php';

$error = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $db = (new Database())->getConnection();
    $tutorialObj = new Tutorial($db);
    $user_id = $_SESSION['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];

    if($tutorialObj->createTutorial($user_id, $title, $description)) {
        header("Location: users.php");
        exit;
    } else {
        $error = "Failed to create tutorial.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="page-container">
    <main class="form-container">
        <div class="form-header">
            <h1>Create Tutorial</h1>
            <div class="underline"></div>
        </div>
        
        <?php if($error): ?>
            <div class="error-container">
                <p class="error"><?php echo $error; ?></p>
            </div>
        <?php endif; ?>
        
        <form action="create_tutorials.php" method="POST">
            <div class="form-group">
                <label for="title">Tutorial Title</label>
                <input type="text" name="title" id="title" required placeholder="Enter tutorial title">
                <div class="focus-border"></div>
            </div>
            
            <div class="form-group">
                <label for="description">Tutorial Description</label>
                <textarea name="description" id="description" rows="5" required placeholder="Enter detailed description of your tutorial"></textarea>
                <div class="focus-border"></div>
            </div>
            
            <div class="button-container">
                <button type="submit">Create Tutorial</button>
            </div>
        </form>
    </main>
</div>

<?php include 'includes/footer.php'; ?>

<style>
    :root {
        --primary-color: #3a6ea5;
        --accent-color:rgb(28, 135, 235);
        --text-color: #333;
        --light-text: #666;
        --lightest-text: #999;
        --error-color: #d9534f;
        --success-color: #5cb85c;
        --border-color: #e0e0e0;
        --bg-color: #f8f9fa;
        --card-bg: #ffffff;
        --shadow-color: rgba(0, 0, 0, 0.05);
        --transition: all 0.3s ease;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    html, body {
        height: 100%;
        font-family: 'Poppins', 'Segoe UI', system-ui, -apple-system, sans-serif;
        color: var(--text-color);
        background-color: var(--bg-color);
        line-height: 1.6;
    }

    .page-container {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    .form-container {
        background: var(--card-bg);
        padding: 2.5rem;
        border-radius: 16px;
        width: 100%;
        max-width: 700px;
        box-shadow: 0 15px 35px var(--shadow-color), 0 5px 15px var(--shadow-color);
        transform: translateY(0);
        transition: transform 0.5s ease, box-shadow 0.5s ease;
    }

    .form-container:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px var(--shadow-color), 0 10px 20px var(--shadow-color);
    }

    .form-header {
        text-align: center;
        margin-bottom: 2rem;
        position: relative;
    }

    .form-header h1 {
        font-size: 2.5rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .underline {
        height: 4px;
        width: 70px;
        background: linear-gradient(to right, var(--primary-color), var(--accent-color));
        margin: 0 auto;
        border-radius: 2px;
    }

    .form-group {
        margin-bottom: 1.8rem;
        position: relative;
    }

    label {
        display: block;
        margin-bottom: 0.8rem;
        color: var(--light-text);
        font-weight: 500;
        font-size: 0.95rem;
        transition: var(--transition);
    }

    input, textarea {
        width: 100%;
        padding: 1rem 1.2rem;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-color);
        font-size: 1rem;
        transition: var(--transition);
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
    }

    input::placeholder, textarea::placeholder {
        color: var(--lightest-text);
        opacity: 0.7;
    }

    input:focus, textarea:focus {
        outline: none;
        border-color: var(--accent-color);
        box-shadow: 0 0 0 3px rgba(111, 168, 220, 0.2);
    }

    .focus-border {
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background: linear-gradient(to right, var(--primary-color), var(--accent-color));
        transition: 0.4s;
    }

    input:focus ~ .focus-border,
    textarea:focus ~ .focus-border {
        width: 100%;
        left: 0;
    }

    .button-container {
        margin-top: 2.5rem;
    }

    button[type="submit"] {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        font-size: 1.1rem;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 4px 10px rgba(58, 110, 165, 0.3);
        position: relative;
        overflow: hidden;
    }

    button[type="submit"]:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 15px rgba(58, 110, 165, 0.4);
    }

    button[type="submit"]::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: 0.5s;
    }

    button[type="submit"]:hover::after {
        left: 100%;
    }

    .error-container {
        margin-bottom: 2rem;
    }

    .error {
        color: var(--error-color);
        background: rgba(217, 83, 79, 0.1);
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid var(--error-color);
        font-size: 0.95rem;
        text-align: left;
    }

    /* Optional: Add a subtle animation for form elements */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-group {
        animation: fadeIn 0.5s ease forwards;
    }

    .form-group:nth-child(1) {
        animation-delay: 0.1s;
    }

    .form-group:nth-child(2) {
        animation-delay: 0.2s;
    }

    .button-container {
        animation: fadeIn 0.5s ease forwards;
        animation-delay: 0.3s;
    }

    /* Add subtle hover effect to inputs */
    input:hover, textarea:hover {
        border-color: #c1d3e8;
    }

    /* Add responsive adjustments */
    @media (max-width: 768px) {
        .form-container {
            padding: 2rem;
        }
        
        .form-header h1 {
            font-size: 2rem;
        }
    }

    @media (max-width: 480px) {
        .page-container {
            padding: 1rem;
        }
        
        .form-container {
            padding: 1.5rem;
        }
        
        .form-header h1 {
            font-size: 1.8rem;
        }
    }
</style>