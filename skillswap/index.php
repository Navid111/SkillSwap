<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'includes/db.php';
require_once 'classes/Tutorial.php';

$db = (new Database())->getConnection();
$tutorialObj = new Tutorial($db);
$tutorials = $tutorialObj->getAllTutorials();
?>

<?php include 'includes/header.php'; ?>

<main>
    <h1>Welcome to Skill-Swap Tutorials</h1>
    <?php foreach($tutorials as $tutorial): ?>
        <div class="tutorial">
            <h2><?php echo htmlspecialchars($tutorial['title']); ?></h2>
            <p><?php echo htmlspecialchars($tutorial['description']); ?></p>
            <p><strong>Author:</strong> <?php echo htmlspecialchars($tutorial['author']); ?></p>
            <p><strong>Created:</strong> <?php echo htmlspecialchars($tutorial['created_at']); ?></p>
        </div>
    <?php endforeach; ?>
</main>

<?php include 'includes/footer.php'; ?>
