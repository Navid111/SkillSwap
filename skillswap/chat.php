<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}

require_once 'includes/db.php';
require_once 'classes/Message.php';

$db = (new Database())->getConnection();
$messageObj = new Message($db);

$receiver_id = isset($_GET['receiver_id']) ? $_GET['receiver_id'] : null;
$conversation = [];

if($receiver_id){
    $conversation = $messageObj->getConversation($_SESSION['user_id'], $receiver_id);
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
        <h2>Conversation with User ID: <?php echo htmlspecialchars($receiver_id); ?></h2>
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
