<?php  
require_once __DIR__ . '/../includes/db.php';

class User {
    private $conn;
    private $table = "users";

    public function __construct($db){
        $this->conn = $db;
    }

    public function register($name, $email, $password, $role) {
        // Check if the user already exists
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if($stmt->rowCount() > 0) {
            return false; // user exists
        }

        // Hash the password before storing
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO " . $this->table . " (name, email, password, role)
                  VALUES (:name, :email, :password, :role)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':role', $role);
        return $stmt->execute();
    }

    public function login($email, $password) {
        $query = "SELECT * FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        if($stmt->rowCount() == 1) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if(password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }

    public function getProfile($user_id) {
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
	
	public function getUserById($user_id) {
    $query = "SELECT name, email FROM users WHERE user_id = :user_id";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
	}

    public function generateResetToken($email) {
        // Check if email exists
        $query = "SELECT user_id FROM " . $this->table . " WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if($stmt->rowCount() == 0) {
            return false;
        }

        // Generate token and expiry
        $token = bin2hex(random_bytes(32));
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Store token in database
        $query = "UPDATE " . $this->table . " 
                 SET reset_token = :token, reset_token_expiry = :expiry 
                 WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->bindParam(':expiry', $expiry);
        $stmt->bindParam(':email', $email);
        
        if($stmt->execute()) {
            return $token;
        }
        return false;
    }

    public function verifyResetToken($token) {
        $query = "SELECT user_id FROM " . $this->table . " 
                 WHERE reset_token = :token 
                 AND reset_token_expiry > NOW()";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        
        if($stmt->rowCount() > 0) {
            return $stmt->fetch(PDO::FETCH_ASSOC)['user_id'];
        }
        return false;
    }

    public function resetPassword($user_id, $new_password) {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        
        $query = "UPDATE " . $this->table . " 
                 SET password = :password, reset_token = NULL, reset_token_expiry = NULL 
                 WHERE user_id = :user_id";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':user_id', $user_id);
        
        return $stmt->execute();
    }

    public function updateProfile($user_id, $name, $email, $current_password = null, $new_password = null) {
        // Start with basic update query
        $query = "UPDATE " . $this->table . " SET name = :name, email = :email";
        $params = [':name' => $name, ':email' => $email, ':user_id' => $user_id];
        
        // If updating password, verify current password first
        if ($new_password && $current_password) {
            // Verify current password
            $user = $this->getProfile($user_id);
            if (!password_verify($current_password, $user['password'])) {
                return ['success' => false, 'message' => 'Current password is incorrect'];
            }
            
            // Add password to update query
            $query .= ", password = :password";
            $params[':password'] = password_hash($new_password, PASSWORD_DEFAULT);
        }
        
        $query .= " WHERE user_id = :user_id";
        
        try {
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute($params);
            if ($result) {
                return ['success' => true, 'message' => 'Profile updated successfully'];
            }
            return ['success' => false, 'message' => 'Failed to update profile'];
        } catch (PDOException $e) {
            if ($e->errorInfo[1] == 1062) { // MySQL duplicate entry error code
                return ['success' => false, 'message' => 'Email address is already in use'];
            }
            return ['success' => false, 'message' => 'An error occurred while updating profile'];
        }
    }
}
?>
