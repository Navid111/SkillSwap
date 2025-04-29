<?php
session_start();
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'includes/db.php';
require_once 'classes/User.php';
require_once 'classes/Tutorial.php';
require_once 'classes/Message.php';

$db = (new Database())->getConnection();
$userObj = new User($db);
$tutorialObj = new Tutorial($db);
$messageObj = new Message($db);

$userProfile = $userObj->getProfile($_SESSION['user_id']);
$userInbox = $messageObj->getMessagesForUser($_SESSION['user_id']);
$selfTutorials = $tutorialObj->getTutorialsByUser($_SESSION['user_id']);

$allTutorials = $tutorialObj->getAllTutorials();
$otherTutorials = array_filter($allTutorials, function($tutorial) {
    return $tutorial['user_id'] != $_SESSION['user_id'];
});
?>

<?php include 'includes/header.php'; ?>
<main class="container">
    <section class="profile-section">
        <h1 class="page-title">My Account</h1>
        <div class="profile-card">
            <h2>Profile Information</h2>
            <div class="profile-info">
                <p><span class="label">Name:</span> <?php echo htmlspecialchars($userProfile['name']); ?></p>
                <p><span class="label">Email:</span> <?php echo htmlspecialchars($userProfile['email']); ?></p>
                <p><span class="label">Role:</span> <?php echo htmlspecialchars($userProfile['role']); ?></p>
                <div class="profile-actions">
                    <a href="edit_profile.php" class="btn btn-edit">Edit Profile</a>
                    <?php if ($userProfile['role'] != 'admin'): ?>
                        <a href="chat.php?receiver_id=1" class="btn btn-primary">Contact Admin</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

	<?php if ($userProfile['role'] != 'admin'): ?>
    <div>
		<h2 style="color: rgb(130, 170, 255); text-shadow: 2px 3px 3px rgb(0, 0, 0); border-bottom: 2px solid var(--primary); padding-bottom: var(--space-sm);">My Inbox</h2>
			<table>
				<tr>
					<th style="color:rgb(0, 0, 0);">Sender Name</th>
					<th style="color:rgb(0, 0, 0);">Sender Email</th>
					<th style="color:rgb(0, 0, 0);">Message</th>
					<th style="color:rgb(0, 0, 0);">Sent At</th>
					<th style="color:rgb(0, 0, 0);">Action</th>
				</tr>
			<?php if (!empty($userInbox)): ?>
			<?php foreach ($userInbox as $message): ?>
				<tr>
					<td><?php echo htmlspecialchars($message['sender_name']); ?></td>
					<td><?php echo htmlspecialchars($message['sender_email']); ?></td>
					<td><?php echo nl2br(htmlspecialchars($message['message'])); ?></td>
					<td><?php echo htmlspecialchars($message['sent_at']); ?></td>
                <td>
                    <a href="chat.php?receiver_id=<?php echo $message['sender_id']; ?>">Reply</a>
                </td>
				</tr>
			<?php endforeach; ?>
			<?php else: ?>
				<tr><td colspan="5" style="text-align:center;">No new messages.</td></tr>
			<?php endif; ?>
			</table>
    </div>
    <?php endif; ?>
	
    <section class="tutorials-section">
        <div class="section-header">
            <h2>My Tutorials</h2>
            <a href="create_tutorials.php" class="btn btn-primary">Create New Tutorial</a>
        </div>
        
        <div class="tutorials-grid">
            <?php if(count($selfTutorials) > 0): ?>
                <?php foreach($selfTutorials as $tutorial): ?>
                    <div class="tutorial-card">
                        <h3 class="tutorial-title"><?php echo htmlspecialchars($tutorial['title']); ?></h3>
                        <p class="tutorial-description"><?php echo htmlspecialchars($tutorial['description']); ?></p>
                        <div class="tutorial-actions">
                            <a href="view_tutorial.php?tutorial_id=<?php echo $tutorial['tutorial_id']; ?>" class="btn btn-view">View</a>
                            <a href="edit_tutorials.php?tutorial_id=<?php echo $tutorial['tutorial_id']; ?>" class="btn btn-edit">Edit</a>
                            <a href="delete_tutorial.php?tutorial_id=<?php echo $tutorial['tutorial_id']; ?>" class="btn btn-delete" onclick="return confirm('Are you sure you want to delete this tutorial?')">Delete</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <p>You haven't created any tutorials yet.</p>
                    <a href="create_tutorials.php" class="btn btn-primary">Get Started</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <section class="tutorials-section">
        <div class="section-header">
            <h2>Explore Other Tutorials</h2>
        </div>
        
        <div class="tutorials-grid">
            <?php if(count($otherTutorials) > 0): ?>
                <?php foreach($otherTutorials as $tutorial): ?>
                    <div class="tutorial-card">
                        <h3 class="tutorial-title"><?php echo htmlspecialchars($tutorial['title']); ?></h3>
                        <p class="tutorial-description"><?php echo htmlspecialchars($tutorial['description']); ?></p>
                        <p class="tutorial-author"><span class="label">By:</span> <?php echo htmlspecialchars($tutorial['author']); ?></p>
                        <div class="tutorial-actions">
                            <a href="view_tutorial.php?tutorial_id=<?php echo $tutorial['tutorial_id']; ?>" class="btn btn-view">View Details</a>
                            <a href="chat.php?receiver_id=<?php echo $tutorial['user_id']; ?>" class="btn btn-contact">Contact Author</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="empty-state">
                    <p>No other tutorials available at the moment.</p>
                </div>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'includes/footer.php'; ?>

<style>
    /* ----- Reset & Base Styles ----- */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }

    :root {
        --primary:rgb(130, 170, 255);
        --primary-dark: #395b90;
        --secondary: #395b90;
        --accent: #6883BC;
        --danger: #E63946;
        --danger-dark: #c9323f;
        --success: #4CAF50;
        --success-dark: #43A047;
        --text: #333;
        --text-light: #666;
        --bg-light:rgb(87, 120, 154);
        --bg-dark: #343a40;
        --card-bg: #fff;
        --border: #dee2e6;
        --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        --radius: 8px;
        --space-sm: 0.5rem;
        --space: 1rem;
        --space-md: 1.5rem;
        --space-lg: 2rem;
    }

    body {
        font-family: 'Georgia', serif;
        line-height: 1.6;
        color: var(--text);
        background: url('images/image4.jpg') no-repeat center center fixed;
        background-size: cover;
    }

    /* ----- Layout ----- */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: var(--space-lg);
    }


    .page-title {
        font-size: 2.2rem;
        color: var(--primary);
		text-shadow: 2px 3px 3px rgb(0, 0, 0);
        margin-bottom: var(--space-lg);
        text-align: center;
        font-weight: 700;
    }

    section {
        margin-bottom: var(--space-lg);
    }

    /* ----- Profile Section ----- */
    .profile-card {
        background-color: var(--card-bg);
        border-radius: var(--radius);
        padding: var(--space-lg);
        box-shadow: var(--shadow);
        margin-bottom: var(--space-lg);
    }

    .profile-card h2 {
        color: #084a7c;
        margin-bottom: var(--space-md);
        padding-bottom: var(--space-sm);
        border-bottom: 1px solid var(--border);
    }

    .profile-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space);
    }

    .label {
        font-weight: 600;
        color: black;
    }

    /* ----- Section Headers ----- */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: var(--space-md);
        padding-bottom: var(--space-sm);
        border-bottom: 2px solid var(--primary);
    }

    .section-header h2 {
        color: var(--primary);
		text-shadow: 2px 3px 3px rgb(0, 0, 0);
        font-weight: 600;
    }

    /* ----- Tutorial Cards ----- */
    .tutorials-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: var(--space-md);
    }

    .tutorial-card {
        background-color: var(--card-bg);
        border-radius: var(--radius);
        padding: var(--space-md);
        box-shadow: var(--shadow);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
		word-wrap: break-word;   /* Breaks long words */
		overflow-wrap: break-word; /* Handles overflow in modern browsers */
		white-space: normal;     /* Allows text to wrap */
    }

    .tutorial-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
    }

    .tutorial-title {
        color: #084a7c;
        margin-bottom: var(--space-sm);
        font-size: 1.25rem;
    }

    .tutorial-description {
        color: var(--text);
        margin-bottom: var(--space);
        flex-grow: 1;
    }

    .tutorial-author {
        margin-bottom: var(--space);
        font-size: 0.9rem;
    }

    /* ----- Buttons ----- */
    .tutorial-actions {
        display: flex;
        gap: var(--space-sm);
        margin-top: auto;
        flex-wrap: wrap;
    }

    .btn {
        display: inline-block;
        padding: 0.6rem 1rem;
        border-radius: var(--radius);
        text-decoration: none;
        font-weight: 500;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        border: none;
    }

    .btn-primary {
        background-color: var(--primary-dark);
        color: white;
    }

    .btn-primary:hover {
        background-color: var(--primary);
    }

    .btn-view {
        background-color: var(--secondary);
        color: white;
    }

    .btn-view:hover {
        background-color: var(--accent);
    }

    .btn-edit {
        background-color: var(--success);
        color: white;
    }

    .btn-edit:hover {
        background-color: var(--success-dark);
    }

    .btn-delete {
        background-color: var(--danger);
        color: white;
    }

    .btn-delete:hover {
        background-color: var(--danger-dark);
    }

    .btn-contact {
        background-color: var(--accent);
        color: white;
    }

    .btn-contact:hover {
        opacity: 0.9;
    }

    /* ----- Empty States ----- */
    .empty-state {
        background-color: var(--card-bg);
        border-radius: var(--radius);
        padding: var(--space-lg);
        text-align: center;
        grid-column: 1 / -1;
        box-shadow: var(--shadow);
    }

    .empty-state p {
        margin-bottom: var(--space);
        color: var(--text-light);
    }

    /* ----- Responsive Adjustments ----- */
    @media (max-width: 768px) {
        .section-header {
            flex-direction: column;
            gap: var(--space-sm);
            align-items: flex-start;
        }
        
        .tutorials-grid {
            grid-template-columns: 1fr;
        }
        
        .profile-info {
            grid-template-columns: 1fr;
        }
    }
	
	table {
        width: 90%;
        margin: 20px auto;
        border-collapse: collapse;
        background-color: #1c1c1c;
        box-shadow: 0 4px 8px rgba(0,0,0,0.5);
        border-radius: 8px;
        overflow: hidden;
        table-layout: fixed; /* Important for wrapping */
        word-wrap: break-word;		
    }
	
    th, td {
        padding: 12px 15px;
        text-align: left;
		color: #ffffff;
        vertical-align: top;
        word-wrap: break-word;
        overflow-wrap: break-word;
        white-space: normal;
        max-width: 200px; /* Adjust as needed */
    }

    th {
        background-color:rgb(255, 255, 255);
        color: #ffffff;
    }

    tr:nth-child(even) {
        background-color: #395b90;
    }

    tr:nth-child(odd) {
        background-color:rgb(92, 119, 155);
    }

    tr:hover {
        background-color:rgb(34, 48, 78);
        transition: 0.3s ease-in-out;
    }
    a {
        color: yellow;
        text-decoration: none;
    }

    a:hover {
        text-decoration: underline;
    }
	
	footer {
		background-color: rgba(0, 0, 0, 0.8);
		text-align: center;
		padding: 10px;
		color: #fff;
	}
</style>
