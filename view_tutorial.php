<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

require_once 'includes/db.php';
require_once 'classes/Tutorial.php';
require_once 'classes/RatingComment.php';

$db = (new Database())->getConnection();
$tutorialObj = new Tutorial($db);
$ratingCommentObj = new RatingComment($db);

if(!isset($_GET['tutorial_id'])){
    header("Location: users.php");
    exit;
}

$tutorial_id = $_GET['tutorial_id'];
$tutorial = $tutorialObj->getTutorialById($tutorial_id);

if(!$tutorial){
    header("Location: users.php");
    exit;
}

// Get the average rating
$average_rating = $ratingCommentObj->getAverageRating($tutorial_id);

// Get all comments
$comments = $ratingCommentObj->getComments($tutorial_id);

// Process rating and comment submission
$message = '';
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    if(isset($_POST['rating'])){
        $rating = $_POST['rating'];
        if($ratingCommentObj->addRating($tutorial_id, $_SESSION['user_id'], $rating)){
            $message = 'Rating submitted successfully!';
            // Refresh the average rating
            $average_rating = $ratingCommentObj->getAverageRating($tutorial_id);
        } else {
            $message = 'Failed to submit rating.';
        }
    }
    
    if(isset($_POST['comment']) && !empty($_POST['comment'])){
        $comment = $_POST['comment'];
        if($ratingCommentObj->addComment($tutorial_id, $_SESSION['user_id'], $comment)){
            $message = 'Comment posted successfully!';
            // Refresh comments
            $comments = $ratingCommentObj->getComments($tutorial_id);
        } else {
            $message = 'Failed to post comment.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutorial View</title>
    <style>  
        :root {
            --primary-color:#395b90; 
            --accent-color: #4CAF50;
            --bg-color: #f8f9fa;   
            --card-bg: #ffffff;
            --text-color: #333;
            --light-text: #666;
            --border-color: #e0e0e0;
            --shadow-color: rgba(0, 0, 0, 0.1);
        }
        
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Georgia', serif;
        }
        
        body {
            background: linear-gradient(to right, #f5f7fa, #e0f7fa);
            color: var(--text-color);
            line-height: 1.6;
        }
        
        header {
            background-color: #333;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: flex-start;
            align-items: center;
        }
        
        header a {
            color: white;
            text-decoration: none;
            margin-right: 15px;
            font-weight: 500;
        }
        
        main {
            max-width: 900px;
            margin: 30px auto;
            padding: 0 20px;
        }
        
		.tutorial-view {
			max-width: 800px;
			margin: 0 auto;
			padding: 20px;
			text-align: center;
			background: #fff;
			border-radius: 8px;
			box-shadow: 0 2px 10px rgba(0,0,0,0.1);
		}
        
        .tutorial-header {
            background: linear-gradient(135deg, var(--primary-color), #395b90);
            color: white;
            padding: 20px 30px;
        }
        
        .tutorial-header h1 {
            font-size: 2.2rem;
            margin-bottom: 10px;
        }
        
        .tutorial-meta {
            font-size: 0.9rem;
        }
        
        .tutorial-content {
			word-wrap: break-word;   /* Breaks long words */
			overflow-wrap: break-word; /* Handles overflow in modern browsers */
			white-space: normal;     /* Allows text to wrap */
            padding: 30px;
            border-bottom: 1px solid var(--border-color);
        }

		.message {
			padding: 10px;
			margin-bottom: 20px;
			background: #d4edda;
			color: #155724;
			border-radius: 4px;
		}
        
        .section-heading {
            color: var(--primary-color);
            font-size: 1.5rem;
            margin: 0 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--border-color);
        }
        
        .rating-section, .comments-section {
            padding: 30px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .current-rating {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .rating-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--primary-color);
            margin-right: 15px;
        }
        
        .stars-display {
            color: #f8d448;
            font-size: 1.5rem;
        }
        
        .rating-form {
            margin-top: 25px;
        }
        
        .star-rating {
            display: inline-flex;
            flex-direction: row-reverse;
            font-size: 1.8rem;
        }
        
        .star-rating input {
            display: none;
        }
        
        .star-rating label {
            color: #ddd;
            cursor: pointer;
            margin-right: 5px;
        }
        
        .star-rating label:before {
            content: '★';
        }
        
        .star-rating input:checked ~ label {
            color: #f8d448;
        }
        
        .star-rating:hover input ~ label {
            color: #ddd;
        }
        
        .star-rating input:hover ~ label {
            color: #f8d448;
        }
        
        button {
            background-color: var(--accent-color);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            transition: background-color 0.3s, transform 0.2s;
        }
        
        button:hover {
            background-color: #3d9140;
            transform: translateY(-2px);
        }
        
        textarea {
            width: 100%;
            padding: 15px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            resize: vertical;
            min-height: 120px;
            margin-bottom: 15px;
            font-size: 1rem;
        }
        
        textarea:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 2px rgba(58, 110, 165, 0.2);
        }
        
        .comments-list {
            margin-top: 30px;
        }
        
        .no-comments {
            text-align: center;
            color: var(--light-text);
            padding: 30px 0;
            font-style: italic;
            background-color: rgba(0, 0, 0, 0.02);
            border-radius: 6px;
        }
        
        .comment {
            background-color: #f9f9f9;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }
        
        .comment-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .commenter {
            font-weight: bold;
            color: var(--primary-color);
        }
        
        .comment-date {
            color: var(--light-text);
            font-size: 0.9rem;
        }
        
        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px;
            margin-top: 50px;
        }
    </style>
</head>
<body>
    <header>
        <a href="index.php">Home</a>
        <a href="users.php">My Account</a>
        <a href="logout.php">Logout</a>
    </header>

    <main>
    <div class="tutorial-view">
        <h1><?php echo htmlspecialchars($tutorial['title']); ?></h1>
        
        <?php if($message): ?>
            <div class="message"><?php echo $message; ?></div>
        <?php endif; ?>
        
        <div class="tutorial-content">
            <p><?php echo nl2br(htmlspecialchars($tutorial['description'])); ?></p>
            <div class="tutorial-meta">
            <p><strong>Created at:</strong> <?php echo htmlspecialchars($tutorial['created_at']); ?></p>
            </div>
        </div>
        
        <div class="rating-section">
            <h2>Rating</h2>
            <p>Average Rating: <span class="rating-value"><?php echo $average_rating ? $average_rating : 'No ratings yet'; ?></span> / 5</p>
            
            <form action="view_tutorial.php?tutorial_id=<?php echo $tutorial_id; ?>" method="POST" class="rating-form">
                <div class="star-rating">
                    <input type="radio" id="star5" name="rating" value="5"><label for="star5"></label>
                    <input type="radio" id="star4" name="rating" value="4"><label for="star4"></label>
                    <input type="radio" id="star3" name="rating" value="3"><label for="star3"></label>
                    <input type="radio" id="star2" name="rating" value="2"><label for="star2"></label>
                    <input type="radio" id="star1" name="rating" value="1"><label for="star1"></label>
                </div>
                <button type="submit" class="rate-btn">Submit Rating</button>
            </form>
        </div>
        
        <div class="comments-section">
            <h2>Comments</h2>
            
            <form action="view_tutorial.php?tutorial_id=<?php echo $tutorial_id; ?>" method="POST" class="comment-form">
                <textarea name="comment" placeholder="Add your comment..." required></textarea>
                <button type="submit">Post Comment</button>
            </form>
            
            <div class="comments-list">
                <?php if(count($comments) > 0): ?>
                    <?php foreach($comments as $comment): ?>
                        <div class="comment">
                            <div class="comment-header">
                                <span class="commenter"><?php echo htmlspecialchars($comment['username']); ?></span>
                                <span class="comment-date"><?php echo htmlspecialchars($comment['created_at']); ?></span>
                            </div>
                            <div class="comment-body">
                                <?php echo nl2br(htmlspecialchars($comment['comment'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No comments yet. Be the first to comment!</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

    <footer>
        © DEV3 2025 Skill-Swap
    </footer>
</body>
</html>
