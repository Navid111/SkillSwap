<?php
session_start();
if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin'){
    header("Location: login.php");
    exit;
}

require_once 'includes/db.php';
require_once 'classes/Admin.php';
require_once 'classes/Message.php';

$db = (new Database())->getConnection();
$adminObj = new Admin($db);
$messageObj = new Message($db);

$users = $adminObj->getAllUsers();
$tutorials = $adminObj->getAllTutorials();
$adminInbox = $messageObj->getMessagesForUser($_SESSION['user_id']);
?>

<?php include __DIR__ . '/includes/header.php'; ?>

<style>
    body {
        font-family: Georgia, serif;
        background: url('images/image4.jpg') no-repeat center center fixed;
        background-size: cover;
    }

    main {
        padding: 20px;
    }

    h1, h2 {
        text-align: center;
        color:rgb(255, 255, 255);
		text-shadow: 2px 3px 3px rgb(0, 0, 0);
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
        background-color: black;
        color: white;
        text-align: center;
        padding: 5px;
        margin-top: 50px;
    }
</style>

<main>
    <h1 style="color: rgb(130, 170, 255);">Admin Dashboard</h1>

    <h2 style="color: rgb(130, 170, 255);">Users</h2>
    <table border="1">
        <tr>
        <th style="color:rgb(0, 0, 0);">User ID</th>
        <th style="color:rgb(0, 0, 0);">Name</th>
        <th style="color:rgb(0, 0, 0);">Email</th>
        <th style="color:rgb(0, 0, 0)">Role</th>
        <th style="color:rgb(0, 0, 0);">Action</th>
        </tr>
        <?php foreach($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['user_id']); ?></td>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td><?php echo htmlspecialchars($user['role']); ?></td>
                <td><a href="delete_user.php?id=<?php echo $user['user_id']; ?>">Delete</a></td>
            </tr>
        <?php endforeach; ?>
    <table border="1">
	
	<h2 style="color: rgb(130, 170, 255);">Inbox</h2>
	<table border="1">
    <tr>
        <th style="color:rgb(0, 0, 0);">Sender Name</th>
        <th style="color:rgb(0, 0, 0);">Sender Email</th>
        <th style="color:rgb(0, 0, 0);">Message</th>
        <th style="color:rgb(0, 0, 0);">Sent At</th>
        <th style="color:rgb(0, 0, 0);">Action</th>
    </tr>
    <?php if (!empty($adminInbox)): ?>
        <?php foreach ($adminInbox as $message): ?>
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
	<table border="1">
	
    <h2 style="color: rgb(130, 170, 255);">Tutorials</h2>
    <table border="1">
        <tr>
        <th style="color:rgb(0, 0, 0);">Tutorial ID</th>
        <th style="color:rgb(0, 0, 0)">Title</th>
        <th style="color:rgb(0, 0, 0);">Description</th>
        <th style="color:rgb(0, 0, 0);">Author</th>
        <th style="color:rgb(0, 0, 0);">Created At</th>
        <th style="color:rgb(0, 0, 0);">Action</th>
        </tr>
        <?php foreach($tutorials as $tutorial): ?>
            <tr>
                <td><?php echo htmlspecialchars($tutorial['tutorial_id']); ?></td>
                <td><?php echo htmlspecialchars($tutorial['title']); ?></td>
                <td><?php echo htmlspecialchars($tutorial['description']); ?></td>
                <td><?php echo htmlspecialchars($tutorial['author']); ?></td>
                <td><?php echo htmlspecialchars($tutorial['created_at']); ?></td>
                <td>
                    <a href="delete_tutorial.php?tutorial_id=<?php echo $tutorial['tutorial_id']; ?>" onclick="return confirm('Delete this tutorial?')">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
	</table>
</main>
<?php include 'includes/footer.php'; ?>
