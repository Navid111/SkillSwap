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

<main>
    <h1>Create Tutorial</h1>
    <?php if($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="create_tutorials.php" method="POST">
        <div class="form-group">
            <label>Title:</label>
            <input type="text" name="title" required>
        </div>
        <div class="form-group">
            <label>Description:</label>
            <textarea name="description" rows="5" required></textarea>
        </div>
        <button type="submit">Create Tutorial</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>

<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    html, body {
        height: 100%;
        font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        color: #fff;
        scroll-behavior: smooth;
    }

    .background-container {
        position: fixed;
        width: 100%;
        height: 100%;
        z-index: -1;
        filter: brightness(0.8) contrast(1.2);
    }

    .background-layer {
        position: absolute;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        opacity: 0;
        animation: slideShow 25s infinite;
        filter: blur(2px);
    }

    @keyframes slideShow {
        0% { opacity: 0; }
        25% { opacity: 1; }
        50% { opacity: 0; }
        75% { opacity: 1; }
        100% { opacity: 0; }
    }

    .background-layer:nth-child(1) {
        background-image: url('images/pexels-dani.jpg');
        animation-delay: 0s;
    }
    .background-layer:nth-child(2) {
        background-image: url('images/pexels-katl.jpg');
        animation-delay: 8s;
    }
    .background-layer:nth-child(3) {
        background-image: url('images/pexels-nic.jpg');
        animation-delay: 16s;
    }

    main {
        background: transparent;  /* Remove white background */
        backdrop-filter: blur(12px);  /* Retain the blur effect */
        padding: 2.5rem;
        border-radius: 20px;
        max-width: 800px;
        margin: 3rem auto;
        box-shadow: 0 0 40px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    label {
        display: block;
        margin-bottom: 0.5rem;
        color: #4CAF50;
        font-weight: 500;
    }

    input, textarea {
        width: 100%;
        padding: 1rem;
        background: rgba(39, 3, 3, 0.1);
        border: 1px solid rgba(59, 4, 4, 0.2);
        border-radius: 8px;
        color: #fff;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    input:focus, textarea:focus {
        outline: none;
        border-color:rgb(218, 179, 9);
        box-shadow: 0 0 12px rgba(230, 140, 6, 0.3);
    }

    button[type="submit"] {
        width: 100%;
        padding: 1.2rem;
        background: linear-gradient(135deg,rgb(243, 179, 3),rgb(235, 156, 9));
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: transform 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    button[type="submit"]:hover {
        transform: translateY(-2px);
    }

    .error {
        color: #ff4444;
        background: rgba(255, 68, 68, 0.1);
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid #ff4444;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    h1 {
        text-align: center;
        margin-bottom: 2rem;
        font-size: 2.5rem;
        background: linear-gradient(45deg, #4CAF50, #8BC34A);
        -webkit-background-clip: text; 
        -webkit-text-fill-color: transparent;
    }
</style>

<div class="background-container">
    <div class="background-layer"></div>
    <div class="background-layer"></div>
    <div class="background-layer"></div>
</div>


<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: Arial, sans-serif;
        color: #fff;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        position: relative;
        margin: 0;
    }

    .background-image {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: url('images/pexels.jpg') no-repeat center center/cover;
        filter: brightness(0.5);
        z-index: -1;
    }

    main {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .tutorial-container {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(0, 0, 0, 0.7); /* Dark transparent background */
        padding: 30px;
        border-radius: 15px;
        box-shadow: 0 0 15px rgba(76, 33, 231, 0.5);
        width: 350px;
    }

    h1 {
        text-align: center;
        margin-bottom: 20px;
        color: orange;
    }

    .error-msg {
        color: red;
        text-align: center;
        margin-bottom: 10px;
    }

    form input, form textarea {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: none;
        border-radius: 8px;
    }

    form button {
        width: 100%;
        padding: 10px;
        background-color: orange;
        color: white;
        border: none;
        cursor: pointer;
        border-radius: 8px;
        font-size: 18px;
    }

    form button:hover {
        background-color: darkorange;
    }

    .signup {
        text-align: center;
        margin-top: 15px;
    }

    .signup a {
        color: orange;
        text-decoration: none;
    }

    .signup a:hover {
        text-decoration: underline;
    }

    footer {
        background-color: rgba(0, 0, 0, 0.8);
        text-align: center;
        padding: 10px;
        color: #fff;
    }
</style>




<style>
    /* Updated form container styles */
    main {
        background: rgba(0, 0, 0, 0.25);
        backdrop-filter: blur(16px);
        padding: 2.5rem;
        border-radius: 20px;
        max-width: 800px;
        margin: 3rem auto;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.15);
        position: relative;
        overflow: hidden;
    }

    main::before {
        content: '';
        position: absolute;
        top: -2px;
        left: -2px;
        right: -2px;
        bottom: -2px;
        background: linear-gradient(45deg, 
            rgba(76, 175, 80, 0.4),
            rgba(255, 193, 7, 0.4),
            rgba(255, 87, 34, 0.4));
        z-index: -1;
        animation: animateGlow 6s linear infinite;
        background-size: 400%;
    }

    @keyframes animateGlow {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    /* Updated form element styles */
    .form-group {
        margin-bottom: 1.8rem;
    }

    label {
        display: block;
        margin-bottom: 0.8rem;
        color: #FFD700;
        font-weight: 500;
        font-size: 1rem;
        letter-spacing: 0.5px;
    }

    input, textarea {
        width: 100%;
        padding: 1.2rem;
        background: rgba(255, 255, 255, 0.08);
        border: 2px solid rgba(255, 215, 0, 0.3);
        border-radius: 10px;
        color: #fff;
        font-size: 1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    input:focus, textarea:focus {
        outline: none;
        border-color: #4CAF50;
        box-shadow: 0 0 20px rgba(76, 175, 80, 0.3);
        background: rgba(255, 255, 255, 0.12);
    }

    button[type="submit"] {
        width: 100%;
        padding: 1.3rem;
        background: linear-gradient(135deg, 
            rgba(76, 175, 80, 0.9) 0%, 
            rgba(255, 193, 7, 0.9) 100%);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        backdrop-filter: blur(4px);
        position: relative;
        overflow: hidden;
    }

    button[type="submit"]:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(76, 175, 80, 0.4);
    }

    button[type="submit"]::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(
            90deg,
            transparent,
            rgba(255, 255, 255, 0.2),
            transparent
        );
        transition: 0.5s;
    }

    button[type="submit"]:hover::after {
        left: 100%;
    }

    .error {
        color: #ff4444;
        background: rgba(255, 68, 68, 0.15);
        padding: 1.2rem;
        border-radius: 10px;
        border: 1px solid #ff4444;
        margin-bottom: 2rem;
        text-align: center;
        font-size: 0.95rem;
        backdrop-filter: blur(4px);
    }

    h1 {
        text-align: center;
        margin-bottom: 2.5rem;
        font-size: 2.8rem;
        background: linear-gradient(45deg, #FFD700, #4CAF50);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }
</style>
