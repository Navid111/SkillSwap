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
</style>
