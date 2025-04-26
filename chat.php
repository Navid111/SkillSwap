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
    <div class="chat-container">
        <div class="chat-header">
            <h1><i class="chat-icon">💬</i> Chat</h1>
            <?php if($receiver_id): ?>
                <h2>Conversation with 
                    <span class="receiver-name"><?php echo htmlspecialchars($receiver['name']); ?></span>
                    <span class="receiver-email"><?php echo htmlspecialchars($receiver['email']); ?></span>
                </h2>
            <?php endif; ?>
        </div>

        <?php if($receiver_id): ?>
            <div class="conversation-wrapper">
                <div class="conversation" id="conversation">
                    <?php if(empty($conversation)): ?>
                        <div class="no-messages">
                            <div class="empty-state">
                                <div class="empty-icon">📩</div>
                                <p>No messages yet. Start the conversation!</p>
                            </div>
                        </div>
                    <?php else: ?>
                        <?php foreach($conversation as $msg): ?>
                            <div class="message <?php echo ($msg['sender_id'] == $_SESSION['user_id']) ? 'sent' : 'received'; ?>">
                                <div class="message-content">
                                    <div class="message-header">
                                        <strong><?php echo htmlspecialchars($msg['sender_name']); ?></strong>
                                    </div>
                                    <div class="message-body">
                                        <?php echo htmlspecialchars($msg['message']); ?>
                                    </div>
                                    <div class="message-footer">
                                        <small><?php echo htmlspecialchars($msg['sent_at']); ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="message-form-container">
                <form action="chat.php?receiver_id=<?php echo $receiver_id; ?>" method="POST" class="message-form">
                    <input type="hidden" name="receiver_id" value="<?php echo $receiver_id; ?>">
                    <div class="form-group">
                        <textarea name="message" placeholder="Type your message here..." rows="3" required></textarea>
                    </div>
                    <button type="submit">
                        <span class="btn-text">Send</span>
                        <span class="btn-icon">➤</span>
                    </button>
                </form>
            </div>
        <?php else: ?>
            <div class="no-receiver">
                <div class="empty-state">
                    <div class="empty-icon">👥</div>
                    <p>Please select a user to chat with by adding ?receiver_id=USER_ID to the URL.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<style>
:root {
    --primary-color: #395b90;
    --primary-dark: #0f5bcd;
    --secondary-color: #34a853;
    --accent-color: #4285f4;
    --background-color: #f5f7fa;
    --card-bg: #ffffff;
    --sent-msg-bg: #e3f2fd;
    --received-msg-bg: #f0f4c3;
    --text-primary: #202124;
    --text-secondary: #5f6368;
    --border-color: #dadce0;
    --shadow-color: rgba(0, 0, 0, 0.1);
    --border-radius: 12px;
    --spacing: 24px;
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: Georgia, serif;
    background: linear-gradient(135deg, #f5f7fa 0%, #e4efe9 100%);
    margin: 0;
    padding: 0;
    color: var(--text-primary);
    line-height: 1.6;
    background: url('images/image4.jpg') no-repeat center center fixed;
    background-size: cover;
}

main {
    max-width: 900px;
    margin: 40px auto;
    padding: 0 20px;
}

.chat-container {
    background: var(--card-bg);
    border-radius: var(--border-radius);
    box-shadow: 0 12px 28px var(--shadow-color);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.chat-container:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.chat-header {
    background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
    color: white;
    padding: var(--spacing);
    text-align: center;
    border-bottom: 1px solid var(--border-color);
    position: relative;
}

.chat-icon {
    font-style: normal;
    margin-right: 10px;
}

h1 {
    margin: 0;
    font-size: 2rem;
    font-weight: 600;
    margin-bottom: 8px;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

h2 {
    font-size: 1.1rem;
    font-weight: 500;
    color: rgba(255, 255, 255, 0.9);
    margin-top: 5px;
}

.receiver-name {
    font-weight: 600;
    background: rgba(255, 255, 255, 0.2);
    padding: 3px 8px;
    border-radius: 50px;
    margin-right: 5px;
}

.receiver-email {
    font-size: 0.9em;
    opacity: 0.8;
}

.conversation-wrapper {
    position: relative;
    background: #f8faff;
    background-image: radial-gradient(circle at center, rgba(255,255,255,0.8) 0%, rgba(245,247,250,0.8) 100%),
                      url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%231a73e8' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
}

.conversation {
    padding: var(--spacing);
    max-height: 400px;
    overflow-y: auto;
    display: flex;
    flex-direction: column;
    gap: 15px;
    scroll-behavior: smooth;
}

.conversation::-webkit-scrollbar {
    width: 8px;
}

.conversation::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.conversation::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 10px;
}

.conversation::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.message {
    display: flex;
    max-width: 70%;
}

.message.sent {
    align-self: flex-end;
}

.message.received {
    align-self: flex-start;
}

.message-content {
    border-radius: 18px;
    padding: 12px 16px;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
    animation: fadeIn 0.3s ease;
    word-break: break-word;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.message.sent .message-content {
    background: var(--sent-msg-bg);
    border-bottom-right-radius: 4px;
}

.message.received .message-content {
    background: var(--received-msg-bg);
    border-bottom-left-radius: 4px;
}

.message-header {
    margin-bottom: 5px;
    font-size: 0.9rem;
}

.sent .message-header strong {
    color: var(--primary-color);
}

.received .message-header strong {
    color: var(--secondary-color);
}

.message-body {
    font-size: 1rem;
    line-height: 1.5;
}

.message-footer {
    text-align: right;
    margin-top: 5px;
}

.message-footer small {
    font-size: 0.75rem;
    color: var(--text-secondary);
    opacity: 0.7;
}

.no-messages, .no-receiver {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 200px;
}

.empty-state {
    text-align: center;
    max-width: 400px;
    padding: 30px;
}

.empty-icon {
    font-size: 3rem;
    margin-bottom: 15px;
    display: inline-block;
    padding: 15px;
    background-color: rgba(26, 115, 232, 0.1);
    border-radius: 50%;
    color: var(--primary-color);
}

.message-form-container {
    padding: var(--spacing);
    background: white;
    border-top: 1px solid var(--border-color);
}

.message-form {
    display: flex;
    gap: 15px;
}

.form-group {
    flex-grow: 1;
}

textarea {
    width: 100%;
    padding: 15px;
    border-radius: 20px;
    border: 1px solid var(--border-color);
    background-color: #f8faff;
    resize: none;
    font-size: 1rem;
    transition: all 0.3s ease;
    box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
}

textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px rgba(26, 115, 232, 0.1);
}

button {
    align-self: flex-end;
    background-color: var(--primary-color);
    color: white;
    border: none;
    border-radius: 50px;
    padding: 12px 24px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

button:hover {
    background-color: var(--primary-dark);
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.btn-icon {
    font-size: 0.9em;
    transition: transform 0.3s ease;
}

button:hover .btn-icon {
    transform: translateX(3px);
}

footer {
    background: linear-gradient(to right, #1a1a1a, #303030);
    color: white;
    text-align: center;
    padding: 5px;
    margin-top: 40px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    font-size: 0.9rem;
}

/* Responsive styles */
@media (max-width: 768px) {
    main {
        margin: 20px auto;
        padding: 0 15px;
    }
    
    .message {
        max-width: 85%;
    }
    
    .chat-header {
        padding: 15px;
    }
    
    h1 {
        font-size: 1.8rem;
    }
    
    h2 {
        font-size: 1rem;
    }
    
    .message-form {
        flex-direction: column;
    }
    
    button {
        width: 100%;
        justify-content: center;
    }
}

/* Add a subtle animation to the chat container on load */
@keyframes slideInUp {
    from {
        transform: translateY(20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.chat-container {
    animation: slideInUp 0.5s ease forwards;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    // Scroll to the bottom of the conversation
    const conversation = document.getElementById("conversation");
    if (conversation) {
        conversation.scrollTop = conversation.scrollHeight;
    }
});
</script>