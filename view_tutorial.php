<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tutorial View</title>
    <style>
        :root {
            --primary-color: #3a6ea5;
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            background-color: var(--card-bg);
            border-radius: 12px;
            box-shadow: 0 8px 16px var(--shadow-color);
            overflow: hidden;
        }
        
        .tutorial-header {
            background: linear-gradient(135deg, var(--primary-color), #2a5a8d);
            color: white;
            padding: 20px 30px;
        }
        
        .tutorial-header h1 {
            font-size: 2.2rem;
            margin-bottom: 10px;
        }
        
        .tutorial-meta {
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.8);
        }
        
        .tutorial-content {
            padding: 30px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            display: none; /* Hide initially, show with JS when needed */
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
        <a href="my_account.php">My Account</a>
        <a href="logout.php">Logout</a>
    </header>

    <main>
        <div class="tutorial-view">
            <div class="tutorial-header">
                <h1>welcome</h1>
                <div class="tutorial-meta">
                    <p>welcome to skill ...</p>
                    <p>Created at: 2025- April 18</p>
                </div>
            </div>
            
            <div class="tutorial-content">
                <p>This is where the tutorial content would go. The content provides valuable information about the topic and helps users learn new skills.</p>
                <p>Multiple paragraphs of content may appear here, with detailed explanations and examples.</p>
            </div>
            
            <div class="rating-section">
                <h2 class="section-heading">Rating</h2>
                
                <div class="current-rating">
                    <div class="rating-value">No ratings yet</div>
                    <div class="stars-display">☆☆☆☆☆</div>
                </div>
                
                <form class="rating-form">
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
                <h2 class="section-heading">Comments</h2>
                
                <form class="comment-form">
                    <textarea placeholder="Add your comment..."></textarea>
                    <button type="submit">Post Comment</button>
                </form>
                
                <div class="comments-list">
                    <div class="no-comments">
                        <p></p>
                    </div>
                    <!-- Comments would be dynamically inserted here -->
                </div>
            </div>
        </div>
    </main>

    <footer>
        © DEV3 2025 Skill-Swap
    </footer>
</body>
</html>