<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'includes/db.php';
require_once 'classes/Tutorial.php';

$db = (new Database())->getConnection();
$tutorialObj = new Tutorial($db);
$error = '';

if(!isset($_GET['tutorial_id'])) {
    header("Location: users.php");
    exit;
}

$tutorial_id = $_GET['tutorial_id'];
$tutorial = $tutorialObj->getTutorialById($tutorial_id);

// Ensure the logged-in user owns this tutorial
if($tutorial['user_id'] != $_SESSION['user_id']){
    header("Location: users.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $title = $_POST['title'];
    $description = $_POST['description'];
    if($tutorialObj->updateTutorial($tutorial_id, $title, $description)){
        header("Location: users.php");
        exit;
    } else {
        $error = "Failed to update tutorial.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<main>
    <h1>Edit Tutorial</h1>
    <?php if($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    <form action="edit_tutorials.php?tutorial_id=<?php echo $tutorial_id; ?>" method="POST">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($tutorial['title']); ?>" required>
        <label>Description:</label>
        <textarea name="description" rows="5" required><?php echo htmlspecialchars($tutorial['description']); ?></textarea>
        <button type="submit">Update Tutorial</button>
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
        color: #ffffff;
        scroll-behavior: smooth;
    }

    .background-container {
        position: fixed;
        width: 100%;
        height: 100%;
        z-index: -1;
    }

    .background-layer {
        position: absolute;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        opacity: 0;
        animation: slideShow 30s infinite;
    }

    @keyframes slideShow {
        0%, 100% { opacity: 0; }
        10%, 90% { opacity: 1; }
    }

    .background-layer:nth-child(1) {
        background-image: url('desktop.jpg');
        animation-delay: 0s;
    }
    .background-layer:nth-child(2) {
        background-image: url('sun.jpg');
        animation-delay: 10s;
    }
    .background-layer:nth-child(3) {
        background-image: url('light.jpg');
        animation-delay: 20s;
    }

    main {
        background: linear-gradient(145deg, rgba(0,0,0,0.85), rgba(40,40,40,0.9));
        backdrop-filter: blur(10px);
        padding: 2.5rem;
        border-radius: 20px;
        max-width: 800px;
        margin: 3rem auto;
        box-shadow: 0 12px 40px rgba(0,0,0,0.4);
        border: 1px solid rgba(255,255,255,0.15);
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    label {
        font-size: 1.1rem;
        color: #4CAF50;
        font-weight: 500;
        text-shadow: 0 1px 2px rgba(0,0,0,0.2);
    }

    input, textarea {
        padding: 1rem;
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 8px;
        color: white;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    input:focus, textarea:focus {
        outline: none;
        border-color: #4CAF50;
        background: rgba(76,175,80,0.05);
        box-shadow: 0 0 8px rgba(76,175,80,0.2);
    }

    button[type="submit"] {
        align-self: flex-start;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #4CAF50, #45a049);
        border: none;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.15);
    }

    button[type="submit"]:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.25);
    }

    .error {
        color: #ff4444;
        background: rgba(255,68,68,0.1);
        padding: 1rem;
        border-radius: 8px;
        border: 1px solid #ff4444;
        margin-bottom: 1.5rem;
        animation: errorShake 0.4s ease;
    }

    @keyframes errorShake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(8px); }
        75% { transform: translateX(-8px); }
    }

    h1 {
        text-align: center;
        margin-bottom: 2rem;
        font-size: 2.2rem;
        background: linear-gradient(45deg, #4CAF50, #8BC34A);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        text-shadow: 0 2px 4px rgba(0,0,0,0.15);
    }

    footer {
        background: linear-gradient(to right, rgba(0,0,0,0.9), rgba(30,30,30,0.95));
        padding: 1.5rem;
        border-top: 1px solid rgba(255,255,255,0.1);
        margin-top: 3rem;
    }
</style>
<div class="background-container">
    <div class="background-layer" style="background-image: url('images/pexels-natal.jpg');"></div>
    <div class="background-layer" style="background-image: url('images/pknop-3760323.jpg');"></div>
    <div class="background-layer" style="background-image: url('images/pexe.jpg');"></div>
</div>
<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'includes/db.php';
require_once 'classes/Tutorial.php';

$db = (new Database())->getConnection();
$tutorialObj = new Tutorial($db);
$error = '';

if(!isset($_GET['tutorial_id'])) {
    header("Location: users.php");
    exit;
}

$tutorial_id = $_GET['tutorial_id'];
$tutorial = $tutorialObj->getTutorialById($tutorial_id);

if($tutorial['user_id'] != $_SESSION['user_id']){
    header("Location: users.php");
    exit;
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $title = $_POST['title'];
    $description = $_POST['description'];
    if($tutorialObj->updateTutorial($tutorial_id, $title, $description)){
        header("Location: users.php");
        exit;
    } else {
        $error = "Failed to update tutorial.";
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="background-container">
    <div class="background-layer"></div>
    <div class="background-layer"></div>
    <div class="background-layer"></div>
</div>

<main>
    <div class="form-container">
        <h1>Edit Tutorial</h1>
        <?php if($error): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="edit_tutorials.php?tutorial_id=<?php echo $tutorial_id; ?>" method="POST">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($tutorial['title']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="5" required><?php echo htmlspecialchars($tutorial['description']); ?></textarea>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn-primary">Update Tutorial</button>
                <a href="users.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<style>
    .form-container {
        background: rgba(0, 0, 0, 0.85);
        padding: 2rem;
        border-radius: 12px;
        backdrop-filter: blur(8px);
        max-width: 700px;
        margin: 2rem auto;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.1);
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
        padding: 0.8rem;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: 6px;
        color: #fff;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    input:focus, textarea:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 8px rgba(76, 175, 80, 0.3);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, #4CAF50, #45a049);
        padding: 0.8rem 1.5rem;
        border: none;
        border-radius: 6px;
        color: white;
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .btn-secondary {
        background: rgba(255, 255, 255, 0.1);
        padding: 0.8rem 1.5rem;
        border-radius: 6px;
        color: #fff;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(76, 175, 80, 0.4);
    }

    .btn-secondary:hover {
        background: rgba(255, 255, 255, 0.2);
    }

    @keyframes backgroundCycle {
        0%, 100% { opacity: 0; }
        20% { opacity: 1; }
        33% { opacity: 1; }
        53% { opacity: 0; }
    }

    .background-layer:nth-child(1) {
        background-image: url('images/tech1.jpg');
        animation: backgroundCycle 24s infinite;
    }
    
    .background-layer:nth-child(2) {
        background-image: url('images/tech2.jpg');
        animation: backgroundCycle 24s infinite 8s;
    }
    
    .background-layer:nth-child(3) {
        background-image: url('images/tech3.jpg');
        animation: backgroundCycle 24s infinite 16s;
    }
</style>