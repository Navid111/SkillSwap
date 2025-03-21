<?php
require_once __DIR__ . '/../includes/db.php';

class Admin {
    private $conn;
    private $users_table = "users";
    private $tutorials_table = "tutorials";

    public function __construct($db){
        $this->conn = $db;
    }

    public function getAllUsers() {
        $query = "SELECT * FROM " . $this->users_table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllTutorials() {
        $query = "SELECT t.*, u.name as author FROM " . $this->tutorials_table . " t 
                  JOIN users u ON t.user_id = u.user_id
                  ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteUser($user_id) {
        $query = "DELETE FROM " . $this->users_table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        return $stmt->execute();
    }

    public function deleteTutorial($tutorial_id) {
        $query = "DELETE FROM " . $this->tutorials_table . " WHERE tutorial_id = :tutorial_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tutorial_id', $tutorial_id);
        return $stmt->execute();
    }
}
?>
