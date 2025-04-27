<?php
require_once __DIR__ . "/../../../config/database.php";

class UserManagementModel {
    private $conn;

    public function __construct() {
        global $pdo;
        $this->conn = $pdo;
    }

    public function getAllUsers($offset = 0, $limit = 10, $searchTerm = '') {
        try {
            $query = "SELECT user_id, first_name, last_name, email, contact_number, role, created_at 
                      FROM users WHERE 1=1";
            
            if (!empty($searchTerm)) {
                $searchTerm = "%$searchTerm%";
                $query .= " AND (first_name LIKE :searchTerm OR last_name LIKE :searchTerm OR 
                           email LIKE :searchTerm OR contact_number LIKE :searchTerm)";
            }
            
            $query .= " ORDER BY created_at DESC LIMIT :offset, :limit";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            
            if (!empty($searchTerm)) {
                $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
            }
            
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Database error: " . $e->getMessage()];
        }
    }

    public function getTotalUsersCount($searchTerm = '') {
        try {
            $query = "SELECT COUNT(*) as total FROM users WHERE 1=1";
            
            if (!empty($searchTerm)) {
                $searchTerm = "%$searchTerm%";
                $query .= " AND (first_name LIKE :searchTerm OR last_name LIKE :searchTerm OR 
                           email LIKE :searchTerm OR contact_number LIKE :searchTerm)";
            }
            
            $stmt = $this->conn->prepare($query);
            
            if (!empty($searchTerm)) {
                $stmt->bindParam(':searchTerm', $searchTerm, PDO::PARAM_STR);
            }
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getUserById($userId) {
        try {
            $stmt = $this->conn->prepare("SELECT user_id, first_name, last_name, email, contact_number, role, created_at, company_name
                                         FROM users WHERE user_id = :userId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return ["error" => "Database error: " . $e->getMessage()];
        }
    }

    public function createUser($firstName, $lastName, $email, $contactNumber, $password, $role) {
        try {
            // Check if email already exists
            $checkStmt = $this->conn->prepare("SELECT user_id FROM users WHERE email = :email");
            $checkStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $checkStmt->execute();
            
            if ($checkStmt->fetch()) {
                return ["error" => "Email already exists"];
            }
            
            // Check if contact number already exists
            if (!empty($contactNumber)) {
                $checkStmt = $this->conn->prepare("SELECT user_id FROM users WHERE contact_number = :contact_number");
                $checkStmt->bindParam(':contact_number', $contactNumber, PDO::PARAM_STR);
                $checkStmt->execute();
                
                if ($checkStmt->fetch()) {
                    return ["error" => "Contact number already exists"];
                }
            }
            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $this->conn->prepare("INSERT INTO users (first_name, last_name, email, contact_number, password, role) 
                                        VALUES (:firstName, :lastName, :email, :contactNumber, :password, :role)");
            
            $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
            $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':contactNumber', $contactNumber, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            
            $stmt->execute();
            return ["success" => "User created successfully", "user_id" => $this->conn->lastInsertId()];
        } catch (PDOException $e) {
            return ["error" => "Database error: " . $e->getMessage()];
        }
    }

    public function updateUser($userId, $firstName, $lastName, $email, $contactNumber, $role, $password = null) {
        try {
            // Check if email already exists for another user
            $checkStmt = $this->conn->prepare("SELECT user_id FROM users WHERE email = :email AND user_id != :userId");
            $checkStmt->bindParam(':email', $email, PDO::PARAM_STR);
            $checkStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $checkStmt->execute();
            
            if ($checkStmt->fetch()) {
                return ["error" => "Email already exists"];
            }
            
            // Check if contact number already exists for another user
            if (!empty($contactNumber)) {
                $checkStmt = $this->conn->prepare("SELECT user_id FROM users WHERE contact_number = :contact_number AND user_id != :userId");
                $checkStmt->bindParam(':contact_number', $contactNumber, PDO::PARAM_STR);
                $checkStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
                $checkStmt->execute();
                
                if ($checkStmt->fetch()) {
                    return ["error" => "Contact number already exists"];
                }
            }
            
            if ($password) {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $this->conn->prepare("UPDATE users SET first_name = :firstName, last_name = :lastName, 
                                            email = :email, contact_number = :contactNumber, 
                                            password = :password, role = :role 
                                            WHERE user_id = :userId");
                $stmt->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
            } else {
                $stmt = $this->conn->prepare("UPDATE users SET first_name = :firstName, last_name = :lastName, 
                                            email = :email, contact_number = :contactNumber, role = :role 
                                            WHERE user_id = :userId");
            }
            
            $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR);
            $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':contactNumber', $contactNumber, PDO::PARAM_STR);
            $stmt->bindParam(':role', $role, PDO::PARAM_STR);
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            
            $stmt->execute();
            return ["success" => "User updated successfully"];
        } catch (PDOException $e) {
            return ["error" => "Database error: " . $e->getMessage()];
        }
    }

    public function deleteUser($userId) {
        try {
            // Check if user has related bookings
            $checkStmt = $this->conn->prepare("SELECT COUNT(*) as bookingCount FROM bookings WHERE user_id = :userId");
            $checkStmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $checkStmt->execute();
            $result = $checkStmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result['bookingCount'] > 0) {
                return ["error" => "Cannot delete user with existing bookings"];
            }
            
            $stmt = $this->conn->prepare("DELETE FROM users WHERE user_id = :userId");
            $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                return ["success" => "User deleted successfully"];
            } else {
                return ["error" => "User not found"];
            }
        } catch (PDOException $e) {
            return ["error" => "Database error: " . $e->getMessage()];
        }
    }
}
?> 