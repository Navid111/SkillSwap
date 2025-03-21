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
        <label>Title:</label>
        <input type="text" name="title" required>
        <label>Description:</label>
        <textarea name="description" rows="5" required></textarea>
        <button type="submit">Create Tutorial</button>
    </form>
</main>

<?php include 'includes/footer.php'; ?>
