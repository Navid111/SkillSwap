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

<div class="page-background">
    <div class="blur-overlay"></div>
</div>

<main class="main-container">
    <div class="edit-form">
        <div class="form-header">
            <h1>Edit Tutorial</h1>
        </div>
        
        <?php if($error): ?>
            <div class="error-message">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        
        <form action="edit_tutorials.php?tutorial_id=<?php echo $tutorial_id; ?>" method="POST">
            <div class="input-group">
                <label for="title">Tutorial Title</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($tutorial['title']); ?>" required>
            </div>
            
            <div class="input-group">
                <label for="description">Tutorial Description</label>
                <textarea id="description" name="description" rows="6" required><?php echo htmlspecialchars($tutorial['description']); ?></textarea>
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
        --primary-color:rgb(71, 151, 243);
        --primary-light: #757de8;
        --primary-dark: #303f9f;
        --accent-color: #42a5f5;
        --text-color: #2c3e50;
        --text-secondary:rgb(8, 13, 15);
        --error-color: #f44336;
        --border-color: #e0e0e0;
        --input-bg: #f5f8ff;
        --shadow-color: rgba(63, 81, 181, 0.15);
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Arial, sans-serif;
        background-color: var(--bg-color);
        color: var(--text-color);
        line-height: 1.6;
        min-height: 100vh;
    }

    .page-background {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(120deg, #e8f0fe, #bbdefb, #e3f2fd);
        z-index: -2;
    }

    .blur-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: 
            radial-gradient(circle at 25% 25%, rgba(63, 81, 181, 0.05) 0%, transparent 50%),
            radial-gradient(circle at 75% 75%, rgba(66, 165, 245, 0.05) 0%, transparent 50%);
        z-index: -1;
    }

    .main-container {
        max-width: 800px;
        margin: 50px auto;
        padding: 0 20px;
    }

    .edit-form {
        background-color: var(--surface-color);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 8px 30px var(--shadow-color);
        border: 1px solid rgba(63, 81, 181, 0.1);
    }

    .form-header {
        padding: 25px 30px;
        border-bottom: 1px solid var(--border-color);
        background-color: rgba(63, 81, 181, 0.05);
    }

    h1 {
        font-size: 24px;
        font-weight: 500;
        color: var(--primary-color);
        position: relative;
        padding-left: 15px;
    }

    h1::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        background-color: var(--primary-color);
        border-radius: 4px;
    }

    form {
        padding: 30px;
    }

    .input-group {
        margin-bottom: 25px;
    }

    label {
        display: block;
        margin-bottom: 8px;
        font-size: 15px;
        color: var(--primary-color);
        font-weight: 500;
    }

    input, textarea {
        width: 100%;
        padding: 12px 15px;
        background-color: var(--input-bg);
        border: 1px solid var(--border-color);
        border-radius: 8px;
        color: var(--text-color);
        font-size: 15px;
        transition: all 0.3s ease;
    }

    input:focus, textarea:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(63, 81, 181, 0.15);
    }

    textarea {
        resize: vertical;
        min-height: 120px;
    }

    .button-group {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
    }

    .btn-save, .btn-cancel {
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .btn-save {
        background-color: var(--primary-color);
        color: white;
        border: none;
        box-shadow: 0 3px 6px rgba(63, 81, 181, 0.2);
    }

    .btn-save:hover {
        background-color: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 5px 12px rgba(36, 113, 228, 0.3);
    }

    .btn-cancel {
        background-color: transparent;
        color: var(--text-secondary);
        border: 1px solid var(--border-color);
    }

    .btn-cancel:hover {
        background-color: rgba(63, 81, 181, 0.05);
        color: var(--primary-color);
        border-color: var(--primary-light);
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
        .main-container {
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
</style>