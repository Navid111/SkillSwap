<?php
require_once __DIR__ . '/../includes/db.php';

class RatingComment {
    private $conn;
    private $ratings_table = "ratings";
    private $comments_table = "comments";

    public function __construct($db){
        $this->conn = $db;
    }

    // Add a rating for a tutorial
    public function addRating($tutorial_id, $user_id, $rating) {
        if($rating < 1 || $rating > 5) {
            return false;
        }
        $query = "INSERT INTO " . $this->ratings_table . " (tutorial_id, user_id, rating)
                  VALUES (:tutorial_id, :user_id, :rating)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tutorial_id', $tutorial_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':rating', $rating);
        return $stmt->execute();
    }

    // Add a comment for a tutorial
    public function addComment($tutorial_id, $user_id, $comment) {
        $query = "INSERT INTO " . $this->comments_table . " (tutorial_id, user_id, comment)
                  VALUES (:tutorial_id, :user_id, :comment)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tutorial_id', $tutorial_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':comment', $comment);
        return $stmt->execute();
    }

    // Get average rating for a tutorial
    public function getAverageRating($tutorial_id) {
        $query = "SELECT AVG(rating) as average_rating FROM " . $this->ratings_table . " 
                  WHERE tutorial_id = :tutorial_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tutorial_id', $tutorial_id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return round($result['average_rating'], 2);
    }

    // Get comments for a tutorial
    public function getComments($tutorial_id) {
        $query = "SELECT c.*, u.name as username FROM " . $this->comments_table . " c 
                  JOIN users u ON c.user_id = u.user_id 
                  WHERE tutorial_id = :tutorial_id 
                  ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':tutorial_id', $tutorial_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
