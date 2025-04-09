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

<?php include 'includes/header.php'; ?>

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

<?php include 'includes/footer.php'; ?>

<style>
    .tutorial-view {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .tutorial-content {
        margin-bottom: 30px;
        line-height: 1.6;
    }
    
    .message {
        padding: 10px;
        margin-bottom: 20px;
        background: #d4edda;
        color: #155724;
        border-radius: 4px;
    }
    
    .rating-section, .comments-section {
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #eee;
    }
    
    .rating-value {
        font-weight: bold;
        font-size: 1.2em;
    }
    
    .star-rating {
        display: flex;
        flex-direction: row-reverse;
        font-size: 1.5em;
        justify-content: flex-end;
        padding: 0 0.2em;
        text-align: center;
        width: 5em;
    }

    .star-rating input {
        display: none;
    }

    .star-rating label {
        color: #ccc;
        cursor: pointer;
    }

    .star-rating label:before {
        content: '★';
    }

    .star-rating input:checked ~ label {
        color: #f90;
    }

    .star-rating:hover input ~ label {
        color: #ccc;
    }

    .star-rating input:hover ~ label {
        color: #f90;
    }
    
    .rate-btn {
        margin-top: 10px;
        padding: 8px 15px;
        background: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    
    .comment-form textarea {
        width: 100%;
        min-height: 100px;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        resize: vertical;
    }
    
    .comment-form button {
        padding: 8px 15px;
        background: #4CAF50;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    
    .comments-list {
        margin-top: 20px;
    }
    
    .comment {
        margin-bottom: 15px;
        padding: 15px;
        background: #f9f9f9;
        border-radius: 4px;
    }
    
    .comment-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    
    .commenter {
        font-weight: bold;
    }
    
    .comment-date {
        color: #777;
        font-size: 0.9em;
    }
	footer {
		background-color: rgba(0, 0, 0, 0.8);
		text-align: center;
		padding: 10px;
		color: #fff;
	}
</style>
