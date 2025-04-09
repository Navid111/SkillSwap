<?php
require_once __DIR__ . '/../includes/db.php';


class Tutorial {
    private $conn;
    private $table = "tutorials";

    public function __construct($db){
        $this->conn = $db;
    }

    public function createTutorial($user_id, $title, $description) {
        $query = "INSERT INTO " . $this->table . " (user_id, title, description)
                  VALUES (:user_id, :title, :description)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        return $stmt->execute();
    }

    public function getAllTutorials() {
        $query = "SELECT t.*, u.name as author FROM " . $this->table . " t 
                  JOIN users u ON t.user_id = u.user_id 
                  ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTutorialById($tutorial_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE tutorial_id = :tutorial_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tutorial_id', $tutorial_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getTutorialsByUser($user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateTutorial($tutorial_id, $title, $description) {
        $query = "UPDATE " . $this->table . " SET title = :title, description = :description
                  WHERE tutorial_id = :tutorial_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':tutorial_id', $tutorial_id);
        return $stmt->execute();
    }

    public function deleteTutorial($tutorial_id) {
        $query = "DELETE FROM " . $this->table . " WHERE tutorial_id = :tutorial_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tutorial_id', $tutorial_id);
        return $stmt->execute();
    }
}
?>
