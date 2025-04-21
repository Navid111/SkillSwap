<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'includes/db.php';
require_once 'classes/Tutorial.php';
require_once 'classes/RatingComment.php';

$db = (new Database())->getConnection();
$tutorialObj = new Tutorial($db);
$ratingCommentObj = new RatingComment($db);
$tutorials = $tutorialObj->getAllTutorials();

// Get total tutorials count for statistics
$tutorialCount = count($tutorials);
// You would typically get these from your database
$userCount = 500; // Placeholder value
$categoryCount = 8; // Placeholder value
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skill-Swap Tutorials</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        /* Material Design Inspired Styles */
        :root {
            --primary: #1a73e8;
            --primary-light: #e8f0fe;
            --primary-dark: #174ea6;
            --secondary: #34a853;
            --error: #ea4335;
            --warning: #fbbc04;
            --surface: #ffffff;
            --background: #f8f9fa;
            --on-primary: #ffffff;
            --on-surface: #202124;
            --on-background: #202124;
            --on-surface-medium: #5f6368;
            --elevation-1: 0 1px 2px 0 rgba(60, 64, 67, 0.3), 0 1px 3px 1px rgba(60, 64, 67, 0.15);
            --elevation-2: 0 1px 2px 0 rgba(60, 64, 67, 0.3), 0 2px 6px 2px rgba(60, 64, 67, 0.15);
            --elevation-3: 0 1px 3px 0 rgba(60, 64, 67, 0.3), 0 4px 8px 3px rgba(60, 64, 67, 0.15);
            --radius: 8px;
            --transition: all 0.2s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            color: var(--on-background);
            background-color: var(--background);
            line-height: 1.5;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 16px;
        }

        /* Header */
        .app-bar {
            background-color: var(--surface);
            box-shadow: var(--elevation-1);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 12px 0;
        }

        .app-bar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .app-title {
            font-size: 20px;
            font-weight: 500;
            color: var(--primary);
            display: flex;
            align-items: center;
        }

        .app-title .material-icons {
            margin-right: 8px;
            font-size: 24px;
        }

        .nav-buttons {
            display: flex;
            gap: 8px;
        }

        .btn {
            background-color: transparent;
            color: var(--primary);
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            transition: var(--transition);
            font-weight: 500;
            border: none;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            letter-spacing: 0.25px;
        }

        .btn-contained {
            background-color: var(--primary);
            color: var(--on-primary);
        }

        .btn:hover {
            background-color: rgba(26, 115, 232, 0.08);
        }

        .btn-contained:hover {
            background-color: var(--primary-dark);
            box-shadow: var(--elevation-1);
        }

        .btn .material-icons {
            font-size: 18px;
            margin-right: 8px;
        }

        /* Hero Section */
        .hero {
            background-color: var(--primary-light);
            padding: 48px 0;
            margin-bottom: 32px;
            text-align: center;
        }

        .hero-title {
            font-size: 32px;
            font-weight: 400;
            color: var(--primary-dark);
            margin-bottom: 16px;
        }

        .hero-subtitle {
            font-size: 16px;
            color: var(--on-surface-medium);
            max-width: 600px;
            margin: 0 auto 24px;
        }

        .search-container {
            max-width: 600px;
            margin: 0 auto;
            position: relative;
        }

        .search-input {
            width: 100%;
            padding: 12px 20px 12px 48px;
            border: 1px solid #dadce0;
            border-radius: 24px;
            font-size: 16px;
            transition: var(--transition);
            box-shadow: var(--elevation-1);
        }

        .search-input:focus {
            outline: none;
            box-shadow: var(--elevation-2);
            border-color: var(--primary);
        }

        .search-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--on-surface-medium);
        }

        /* Stats Section */
        .stats-container {
            display: flex;
            justify-content: center;
            gap: 16px;
            margin-bottom: 32px;
            flex-wrap: wrap;
        }

        .stat-card {
            background-color: var(--surface);
            border-radius: var(--radius);
            padding: 16px;
            box-shadow: var(--elevation-1);
            display: flex;
            flex-direction: column;
            align-items: center;
            min-width: 120px;
            flex: 1;
            transition: var(--transition);
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--elevation-2);
        }

        .stat-icon {
            background-color: var(--primary-light);
            color: var(--primary);
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 8px;
        }

        .stat-value {
            font-size: 24px;
            font-weight: 500;
            color: var(--primary);
            margin-bottom: 4px;
        }

        .stat-label {
            font-size: 14px;
            color: var(--on-surface-medium);
        }

        /* Tutorials Section */
        .section-title {
            font-size: 24px;
            font-weight: 400;
            margin-bottom: 16px;
            color: var(--on-surface);
            display: flex;
            align-items: center;
        }

        .section-title .material-icons {
            margin-right: 8px;
            color: var(--primary);
        }

        .tutorial-filter {
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
            overflow-x: auto;
            padding-bottom: 8px;
        }

        .filter-chip {
            background-color: var(--surface);
            border: 1px solid #dadce0;
            border-radius: 16px;
            padding: 4px 12px;
            font-size: 14px;
            cursor: pointer;
            white-space: nowrap;
            transition: var(--transition);
        }

        .filter-chip:hover, .filter-chip.active {
            background-color: var(--primary-light);
            color: var(--primary);
            border-color: var(--primary-light);
        }

        .filter-chip.active {
            font-weight: 500;
        }

        .tutorial-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
            margin-bottom: 40px;
        }

        .tutorial-card {
            background-color: var(--surface);
            border-radius: var(--radius);
            box-shadow: var(--elevation-1);
            overflow: hidden;
            transition: var(--transition);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .tutorial-card:hover {
            box-shadow: var(--elevation-2);
            transform: translateY(-2px);
        }

        .tutorial-content {
            padding: 16px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
			word-wrap: break-word;   /* Breaks long words */
			overflow-wrap: break-word; /* Handles overflow in modern browsers */
			white-space: normal;     /* Allows text to wrap */		
        }

        .tutorial-title {
            font-size: 18px;
            font-weight: 500;
            color: var(--on-surface);
            margin-bottom: 8px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .tutorial-description {
            color: var(--on-surface-medium);
            font-size: 14px;
            margin-bottom: 16px;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            flex-grow: 1;	
        }

        .tutorial-meta {
            font-size: 12px;
            color: var(--on-surface-medium);
            margin-bottom: 16px;
        }

        .tutorial-meta p {
            margin-bottom: 4px;
            display: flex;
            align-items: center;
        }

        .tutorial-meta .material-icons {
            font-size: 16px;
            margin-right: 4px;
        }

        .tutorial-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 16px;
            border-top: 1px solid #f1f3f4;
        }

        .rating {
            display: flex;
            align-items: center;
        }

        .rating-stars {
            color: var(--warning);
            margin-right: 4px;
            font-size: 14px;
        }

        .rating-value {
            font-size: 14px;
            font-weight: 500;
        }

        .view-btn {
            background-color: transparent;
            color: var(--primary);
            padding: 8px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            font-weight: 500;
        }

        .view-btn:hover {
            background-color: var(--primary-light);
        }

        .view-btn .material-icons {
            font-size: 18px;
            margin-left: 4px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-bottom: 40px;
        }

        .page-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: transparent;
            color: var(--on-surface);
            transition: var(--transition);
            text-decoration: none;
            font-size: 14px;
        }

        .page-btn:hover {
            background-color: rgba(0, 0, 0, 0.04);
        }

        .page-btn.active {
            background-color: var(--primary);
            color: var(--on-primary);
        }

        /* Footer */
        footer {
            background-color: var(--surface);
            padding: 40px 0;
            box-shadow: 0 -1px 3px rgba(0, 0, 0, 0.1);
        }

        .footer-content {
            display: flex;
            flex-wrap: wrap;
            gap: 32px;
        }

        .footer-section {
            flex: 1;
            min-width: 200px;
        }

        .footer-title {
            font-size: 16px;
            font-weight: 500;
            margin-bottom: 16px;
            color: var(--on-surface);
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 8px;
        }

        .footer-links a {
            color: var(--on-surface-medium);
            text-decoration: none;
            transition: var(--transition);
            font-size: 14px;
            display: inline-flex;
            align-items: center;
        }

        .footer-links a:hover {
            color: var(--primary);
        }

        .footer-links a .material-icons {
            font-size: 16px;
            margin-right: 8px;
        }

        .copyright {
            text-align: center;
            margin-top: 32px;
            padding-top: 16px;
            border-top: 1px solid #f1f3f4;
            color: var(--on-surface-medium);
            font-size: 14px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-title {
                font-size: 28px;
            }

            .tutorial-grid {
                grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            }

            .stats-container {
                flex-direction: row;
                flex-wrap: wrap;
            }

            .stat-card {
                min-width: 100px;
            }
        }

        @media (max-width: 576px) {
            .app-title span {
                display: none;
            }

            .hero-title {
                font-size: 24px;
            }

            .tutorial-grid {
                grid-template-columns: 1fr;
            }

            .footer-section {
                flex: 100%;
            }
        }
    </style>
</head>
<body>
    <!-- App Bar (Header) -->
    <header class="app-bar">
        <div class="container">
            <div class="app-bar-content">
                <div class="app-title">
                    <span class="material-icons">school</span>
                    <span>Skill-Swap Tutorials</span>
                </div>
                <div class="nav-buttons">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="users.php" class="btn">
                            <span class="material-icons">account_circle</span>
                            My Account
                        </a>
                        <a href="logout.php" class="btn">
                            <span class="material-icons">logout</span>
                            Logout
                        </a>
                    <?php else: ?>
                        <a href="login.php" class="btn">
                            <span class="material-icons">login</span>
                            Login
                        </a>
                        <a href="register.php" class="btn btn-contained">
                            <span class="material-icons">person_add</span>
                            Register
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1 class="hero-title">Find the Perfect Tutorial to Learn New Skills</h1>
            
            <div class="search-container">
                <span class="material-icons search-icon">search</span>
                <input type="text" class="search-input" placeholder="Search for tutorials...">
            </div>
        </div>
    </section>

   


        <!-- Tutorials Grid -->
        <div class="tutorial-grid">
            <?php foreach($tutorials as $tutorial): ?>
                <?php 
                    // Get average rating for each tutorial
                    $rating = $ratingCommentObj->getAverageRating($tutorial['tutorial_id']);
                    $rating_display = $rating ? number_format($rating, 1) : "N/A";
                    
                    // Create star display based on rating
                    $full_stars = floor($rating);
                    $half_star = ($rating - $full_stars) >= 0.5;
                    $empty_stars = 5 - $full_stars - ($half_star ? 1 : 0);
                    $stars = str_repeat('★', $full_stars) . ($half_star ? '½' : '') . str_repeat('☆', $empty_stars);
                ?>
                <div class="tutorial-card">
                    <div class="tutorial-content">
                        <h3 class="tutorial-title"><?php echo htmlspecialchars($tutorial['title']); ?></h3>
                        <p class="tutorial-description"><?php echo htmlspecialchars($tutorial['description']); ?></p>
                        <div class="tutorial-meta">
                            <p>
                                <span class="material-icons">person</span>
                                <?php echo htmlspecialchars($tutorial['author']); ?>
                            </p>
                            <p>
                                <span class="material-icons">calendar_today</span>
                                <?php echo htmlspecialchars($tutorial['created_at']); ?>
                            </p>
                        </div>
                        <div class="tutorial-actions">
                            <div class="rating" aria-label="Rating: <?php echo $rating_display; ?> out of 5">
                                <span class="rating-stars"><?php echo $stars; ?></span>
                                <span class="rating-value"><?php echo $rating_display; ?></span>
                            </div>
                            <a href="view_tutorial.php?tutorial_id=<?php echo (int)$tutorial['tutorial_id']; ?>" class="view-btn">
                                View
                                <span class="material-icons">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <a href="#" class="page-btn">
                <span class="material-icons">chevron_left</span>
            </a>
            <a href="#" class="page-btn active">1</a>
            <a href="#" class="page-btn">2</a>
            <a href="#" class="page-btn">3</a>
            <a href="#" class="page-btn">
                <span class="material-icons">chevron_right</span>
            </a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                   
                   
                </div>
                <div class="footer-section">
                    
                    
                </div>
                <div class="footer-section">
                   
                    
                </div>
            </div>
            <div class="copyright">
                <p>&copy; <?php echo date('Y'); ?> Skill-Swap Tutorials. All rights reserved.</p>
            </div>
        </div>
    </footer>
</body>
</html>