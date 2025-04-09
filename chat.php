<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

require_once 'includes/db.php';
require_once 'classes/User.php';
require_once 'classes/Message.php';

$db = (new Database())->getConnection();
$userObj = new User($db);
$messageObj = new Message($db);

$receiver_id = isset($_GET['receiver_id']) ? $_GET['receiver_id'] : null;
$conversation = [];
$receiver = null;

if($receiver_id){
    $conversation = $messageObj->getConversation($_SESSION['user_id'], $receiver_id);
	$receiver = $userObj->getUserById($receiver_id);
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $receiver_id = $_POST['receiver_id'];
    $msg = $_POST['message'];
    if($messageObj->sendMessage($_SESSION['user_id'], $receiver_id, $msg)){
        header("Location: chat.php?receiver_id=" . $receiver_id);
        exit;
    }
}
?>

<?php include 'includes/header.php'; ?>

<main>
    <h1>Chat</h1>
    <?php if($receiver_id): ?>
		<h2>Conversation with: 
			<?php echo htmlspecialchars($receiver['name']) . " (" . htmlspecialchars($receiver['email']) . ")"; ?>
		</h2>
        <div class="conversation">
            <?php foreach($conversation as $msg): ?>
                <div class="message">
                    <p><strong><?php echo htmlspecialchars($msg['sender_name']); ?>:</strong> <?php echo htmlspecialchars($msg['message']); ?></p>
                    <small><?php echo htmlspecialchars($msg['sent_at']); ?></small>
                </div>
            <?php endforeach; ?>
        </div>
        <form action="chat.php?receiver_id=<?php echo $receiver_id; ?>" method="POST">
            <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
            <label>Message:</label>
            <textarea name="message" rows="3" required></textarea>
            <button type="submit">Send</button>
        </form>
    <?php else: ?>
        <p>Please select a user to chat with by adding ?receiver_id=USER_ID to the URL.</p>
    <?php endif; ?>
</main>

<?php include 'includes/footer.php'; ?>
<style>
body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: linear-gradient(to right, #f9f9f9, #e0f7fa);
    margin: 0;
    padding: 0;
}

main {
    max-width: 800px;
    margin: 40px auto;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    padding: 30px;
}

h1 {
    text-align: center;
    color: #00796b;
    margin-bottom: 20px;
}

h2 {
    text-align: center;
    color: #004d40;
    font-size: 20px;
    margin-bottom: 20px;
}

.conversation {
    border: 1px solid #ccc;
    border-radius: 10px;
    padding: 15px;
    max-height: 300px;
    overflow-y: auto;
    background: #fafafa;
    margin-bottom: 20px;
}

.message {
    background: #e0f7fa;
    border-radius: 10px;
    padding: 10px;
    margin-bottom: 10px;
    position: relative;
}

.message strong {
    color: #00796b;
}

.message small {
    display: block;
    font-size: 12px;
    color: #555;
    margin-top: 5px;
}

form textarea {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ccc;
    resize: none;
    font-size: 14px;
    margin-bottom: 15px;
    background-color: #f0f8ff;
}

form button {
    background-color: #00796b;
    color: white;
    padding: 12px 20px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
    transition: background-color 0.3s ease;
}

form button:hover {
    background-color: #004d40;
}

p {
    text-align: center;
    font-size: 16px;
    color: #555;
}

footer {
    background-color: rgba(0, 0, 0, 0.8);
    text-align: center;
    padding: 10px;
    color: #fff;
}
</style>
