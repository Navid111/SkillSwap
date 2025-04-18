<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'includes/db.php';
require_once 'classes/User.php';
require_once 'classes/Tutorial.php';

$db = (new Database())->getConnection();
$userObj = new User($db);
$tutorialObj = new Tutorial($db);

$userProfile = $userObj->getProfile($_SESSION['user_id']);
$selfTutorials = $tutorialObj->getTutorialsByUser($_SESSION['user_id']);
$allTutorials = $tutorialObj->getAllTutorials();
$otherTutorials = array_filter($allTutorials, function($tutorial) {
    return $tutorial['user_id'] != $_SESSION['user_id'];
});
?>

<?php include 'includes/header.php'; ?>
<main>
    <h1>My Account</h1>
    <div class="profile">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($userProfile['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($userProfile['email']); ?></p>
		<p><strong>Role:</strong> <?php echo htmlspecialchars($userProfile['role']); ?></p>
    </div>

    <h2>My Tutorials</h2>
    <a href="create_tutorials.php" class="btn">Create New Tutorial</a>
    <?php if(count($selfTutorials) > 0): ?>
        <?php foreach($selfTutorials as $tutorial): ?>
            <div class="tutorial">
                <h3><?php echo htmlspecialchars($tutorial['title']); ?></h3>
                <p><?php echo htmlspecialchars($tutorial['description']); ?></p>
                <div class="tutorial-actions">
                    <a href="view_tutorial.php?tutorial_id=<?php echo $tutorial['tutorial_id']; ?>" class="btn">View</a>
                    <a href="edit_tutorials.php?tutorial_id=<?php echo $tutorial['tutorial_id']; ?>" class="btn">Edit</a>
                    <a href="delete_tutorial.php?tutorial_id=<?php echo $tutorial['tutorial_id']; ?>" class="btn delete" onclick="return confirm('Are you sure?')">Delete</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>You have not created any tutorials yet.</p>
    <?php endif; ?>

    <h2>Other Tutorials</h2>
    <?php if(count($otherTutorials) > 0): ?>
        <?php foreach($otherTutorials as $tutorial): ?>
            <div class="tutorial">
                <h3><?php echo htmlspecialchars($tutorial['title']); ?></h3>
                <p><?php echo htmlspecialchars($tutorial['description']); ?></p>
                <p><strong>Author:</strong> <?php echo htmlspecialchars($tutorial['author']); ?></p>
                <div class="tutorial-actions">
                    <a href="view_tutorial.php?tutorial_id=<?php echo $tutorial['tutorial_id']; ?>" class="btn">View Details</a>
                    <a href="chat.php?receiver_id=<?php echo $tutorial['user_id']; ?>" class="btn">Contact Author</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No tutorials available.</p>
    <?php endif; ?>
</main>
<?php include 'includes/footer.php'; ?>

<style>
    .tutorial-actions {
        margin-top: 15px;
    }
    
    .btn {
        display: inline-block;
        padding: 8px 12px;
        background: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        margin-right: 10px;
    }
    
    .btn:hover {
        background: #45a049;
    }
    
    .btn.delete {
        background: #f44336;
    }
    
    .btn.delete:hover {
        background: #d32f2f;
    }
	footer {
		background-color: rgba(0, 0, 0, 0.8);
		text-align: center;
		padding: 10px;
		color: #fff;
	}

    body {
        background-image: url('images/pexels-dani.jpg');
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-position: center;
        font-family: Arial, sans-serif;
        color: #fff; /* Makes text readable on dark backgrounds */
    }

    main {
        background-color: rgba(0, 0, 0, 0.6); /* Optional: adds contrast for readability */
        padding: 20px;
        border-radius: 10px;
        max-width: 1000px;
        margin: 20px auto;
    }

    .tutorial-actions {
        margin-top: 15px;
    }
    
    .btn {
        display: inline-block;
        padding: 8px 12px;
        background: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        margin-right: 10px;
    }
    
    .btn:hover {
        background: #45a049;
    }
    
    .btn.delete {
        background: #f44336;
    }
    
    .btn.delete:hover {
        background: #d32f2f;
    }

    footer {
        background-color: rgba(0, 0, 0, 0.8);
        text-align: center;
        padding: 10px;
        color: #fff;
    }

    body {
    margin: 0;
    padding: 0;
    background: url('images/pexels-dani.jpg') no-repeat center center fixed;
    background-size: cover;
    font-family: Arial, sans-serif;
    color: #fff;
}



</style>

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
        background-image: url('images/pexels-atypi.jpg');
        animation-delay: 0s;
    }
    .background-layer:nth-child(2) {
        background-image: url('images/pexels-katl.jpg');
        animation-delay: 10s;
    }
    .background-layer:nth-child(3) {
        background-image: url('images/pexels-nic.jpg');
        animation-delay: 20s;
    }

    main {
        background: linear-gradient(145deg, rgba(0,0,0,0.8), rgba(20,20,20,0.9));
        backdrop-filter: blur(8px);
        padding: 2rem;
        border-radius: 16px;
        max-width: 1200px;
        margin: 2rem auto;
        position: relative;
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        padding: 0.8rem 1.5rem;
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        margin: 0.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 500;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.2);
    }

    .btn.delete {
        background: linear-gradient(135deg, #f44336, #d32f2f);
    }

    footer {
        background: linear-gradient(to right, rgba(0,0,0,0.9), rgba(20,20,20,0.95));
        text-align: center;
        padding: 1.5rem;
        color: #fff;
        border-top: 1px solid rgba(255,255,255,0.1);
        margin-top: 3rem;
    }

    h1, h2 {
        text-align: center;
        margin-bottom: 2rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    h1 {
        font-size: 2.5rem;
        letter-spacing: 1px;
        background: linear-gradient(45deg, #4CAF50, #45a049);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .profile, .tutorial {
        background: rgba(255,255,255,0.05);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-radius: 12px;
        transition: transform 0.3s ease;
        border: 1px solid rgba(255,255,255,0.05);
    }

    .profile:hover, .tutorial:hover {
        transform: translateY(-3px);
        background: rgba(255,255,255,0.08);
    }

    .tutorial-actions {
        margin-top: 1rem;
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .section-header {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        background: linear-gradient(90deg, rgba(76,175,80,0.15), rgba(76,175,80,0.05));
        border-left: 4px solid #4CAF50;
    }
</style>
<style>
    /* Container for background slideshow */
    body {
        margin: 0;
        padding: 0;
        font-family: Arial, sans-serif;
        color: #fff;
        position: relative;
        min-height: 100vh;
        overflow-x: hidden;
    }

    /* Slideshow background wrapper */
    body::before, body::after {
        content: '';
        position: fixed;
        top: 0; left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        animation: slideShow 30s infinite ease-in-out;
        opacity: 0;
        transition: opacity 1s ease-in-out;
    }

    body::before {
        background-image: url('images/pexels-atypi.jpg');
        animation-delay: 0s;
    }

    body::after {
        background-image: url('images/pexels-katl.jpg');
        animation-delay: 15s;
    }

    @keyframes slideShow {
        0% { opacity: 1; }
        45% { opacity: 1; }
        50% { opacity: 0; }
        95% { opacity: 0; }
        100% { opacity: 1; }
    }

    /* Optional third layer with JS or static fallback */
    main {
        position: relative;
        z-index: 1;
        background-color: rgba(0, 0, 0, 0.6);
        padding: 20px;
        border-radius: 10px;
        max-width: 1000px;
        margin: 40px auto;
    }

    .btn {
        display: inline-block;
        padding: 8px 12px;
        background: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        margin-right: 10px;
    }

    .btn:hover {
        background: #45a049;
    }

    .btn.delete {
        background: #f44336;
    }

    .btn.delete:hover {
        background: #d32f2f;
    }

    .tutorial-actions {
        margin-top: 15px;
    }

    footer {
        background-color: rgba(0, 0, 0, 0.8);
        text-align: center;
        padding: 10px;
        color: #fff;
        position: relative;
        z-index: 1;
    }
</style>

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
        background-image: url('images/pexels-atypi.jpg'); /* Ensure the path is correct */
        animation-delay: 0s;
    }
    .background-layer:nth-child(2) {
        background-image: url('images/pexels-katl.jpg'); /* Ensure the path is correct */
        animation-delay: 10s;
    }
    .background-layer:nth-child(3) {
        background-image: url('images/pexels-nic.jpg'); /* Ensure the path is correct */
        animation-delay: 20s;
    }

    main {
        background: linear-gradient(145deg, rgba(0,0,0,0.8), rgba(20,20,20,0.9));
        backdrop-filter: blur(8px);
        padding: 2rem;
        border-radius: 16px;
        max-width: 1200px;
        margin: 2rem auto;
        position: relative;
        box-shadow: 0 8px 32px rgba(0,0,0,0.3);
        border: 1px solid rgba(255,255,255,0.1);
    }

    .btn {
        display: inline-flex;
        align-items: center;
        padding: 0.8rem 1.5rem;
        background: linear-gradient(135deg, #4CAF50, #45a049);
        color: white;
        text-decoration: none;
        border-radius: 8px;
        margin: 0.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-weight: 500;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(0,0,0,0.2);
    }

    .btn.delete {
        background: linear-gradient(135deg, #f44336, #d32f2f);
    }

    footer {
        background: linear-gradient(to right, rgba(0,0,0,0.9), rgba(20,20,20,0.95));
        text-align: center;
        padding: 1.5rem;
        color: #fff;
        border-top: 1px solid rgba(255,255,255,0.1);
        margin-top: 3rem;
    }

    h1, h2 {
        text-align: center;
        margin-bottom: 2rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }

    h1 {
        font-size: 2.5rem;
        letter-spacing: 1px;
        background: linear-gradient(45deg, #4CAF50, #45a049);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .profile, .tutorial {
        background: rgba(255,255,255,0.05);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-radius: 12px;
        transition: transform 0.3s ease;
        border: 1px solid rgba(255,255,255,0.05);
    }

    .profile:hover, .tutorial:hover {
        transform: translateY(-3px);
        background: rgba(255,255,255,0.08);
    }

    .tutorial-actions {
        margin-top: 1rem;
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .section-header {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 2rem;
        background: linear-gradient(90deg, rgba(76,175,80,0.15), rgba(76,175,80,0.05));
        border-left: 4px solid #4CAF50;
    }
</style>

<div class="background-container">
    <div class="background-layer"></div>
    <div class="background-layer"></div>
    <div class="background-layer"></div>
</div>
