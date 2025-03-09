<?php
require_once __DIR__ . "/../../../config/database.php";

class AuthModel {
    private $conn;

    public function __construct() {
        global $pdo;
        $this->conn = $pdo;
    }

    public function login($username, $password) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->execute([":username" => $username]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return false;
            }

            if ($user && password_verify($password, $user["password"])) {
                $_SESSION["role"] = $user["role"];
                return true;
            } 
            
            return false;
        } catch (PDOException $e) {
            return "Database error: $e";
        }
    }
}
?>