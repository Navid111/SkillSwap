<?php
require_once __DIR__ . '/../includes/db.php';

class Message {
    private $conn;
    private $table = "messages";

    public function __construct($db){
        $this->conn = $db;
    }

    public function sendMessage($sender_id, $receiver_id, $message) {
        $query = "INSERT INTO " . $this->table . " (sender_id, receiver_id, message)
                  VALUES (:sender_id, :receiver_id, :message)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':sender_id', $sender_id);
        $stmt->bindParam(':receiver_id', $receiver_id);
        $stmt->bindParam(':message', $message);
        return $stmt->execute();
    }

    // Get messages exchanged between two users
    public function getConversation($user1, $user2) {
        $query = "SELECT m.*, u.name as sender_name FROM " . $this->table . " m 
                  JOIN users u ON m.sender_id = u.user_id 
                  WHERE (sender_id = :user1 AND receiver_id = :user2)
                     OR (sender_id = :user2 AND receiver_id = :user1)
                  ORDER BY sent_at ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user1', $user1);
        $stmt->bindParam(':user2', $user2);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all messages for a user (inbox view)
    public function getMessagesForUser($user_id) {
        $query = "SELECT m.*, u.name as sender_name FROM " . $this->table . " m 
                  JOIN users u ON m.sender_id = u.user_id 
                  WHERE receiver_id = :user_id
                  ORDER BY sent_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
