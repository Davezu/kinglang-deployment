<?php
require_once __DIR__ . "/../../../config/database.php";
session_start();

class ClientAuthModel {
    private $conn;

    public function __construct() {
        global $pdo;
        $this->conn = $pdo;
    }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function saveResetToken($email, $token, $expiry) {
        $stmt = $this->conn->prepare("UPDATE users SET reset_token = ?, reset_expiry = ? WHERE email = ?");
        return $stmt->execute([$token, $expiry, $email]);
    }

    public function findByToken($token) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE reset_token = ? AND reset_expiry > NOW()");
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    public function updatePassword($token, $passwordHash) {
        $stmt = $this->conn->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_expiry = NULL WHERE reset_token = ?");
        return $stmt->execute([$passwordHash, $token]);
    }

    public function emailExist($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([":email" => $email]);
        return $stmt->fetch() ? true : false;
    }

    function isValidPassword($password) {
        $pattern = "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    
        return preg_match($pattern, $password);
    }

    public function signup($first_name, $last_name, $email, $contact_number, $password) {
        if ($this->emailExist($email)) {
            return "Email already exists.";
        }

        if ($this->cnotactNumber($contact_number)) {
            return "Contact number already exsits.";
        }

        if (!$this->isValidPassword($password)) {
            return "Invalid password.";
        }   

        try {
            $stmt = $this->conn->prepare("INSERT INTO users (first_name, last_name, email, contact_number, password) VALUES (:first_name, :last_name, :email, :contact_number, :password)");
            $result = $stmt->execute([
                ":first_name" => $first_name,
                ":last_name" => $last_name,
                ":email" => $email,
                ":contact_number" => $contact_number,
                ":password" => $password
            ]);
            
            return "success";
        } catch (PDOException $e) {
            return "Database error.";
        }
    }

    public function login($email, $password) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email AND role = 'client'");
            $stmt->execute([":email" => $email]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return "Email not found.";
            }

            if ($user && password_verify($password, $user["password"])) {
                $_SESSION["email"] = $user["email"];
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["client_name"] = $user["first_name"] . " " . $user["last_name"];
                
                return "success";
            } 
            return "Incorrect password.";
        } catch (PDOException $e) {
            return "Database error";
        }
    }

    public function getClientID($user_id) {
        try {
            $stmt = $this->conn->prepare("SELECT client_id FROM users WHERE user_id = :user_id");
            $stmt->execute([":user_id" => $user_id]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return "Database error";
        }
    }

    public function getClientInformation() {
        $user_id = $_SESSION["user_id"];
        try {
            $stmt = $this->conn->prepare("SELECT first_name, last_name, email, contact_number FROM users WHERE user_id = :user_id");
            $stmt->execute([":user_id" => $user_id]);

            $client = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $client;
        } catch (PDOException $e) {
            return "Database error: $e";
        }
    }

    public function updateClientInformation($first_name, $last_name, $contact_number, $email_address) {
        try {
            $stmt = $this->conn->prepare("UPDATE users SET first_name = :first_name, last_name = :last_name, email = :email, contact_number = :contact_number WHERE user_id = :user_id");
            $stmt->execute([
                ":first_name" => $first_name,
                ":last_name" => $last_name,
                ":email" => $email_address,
                ":contact_number" => $contact_number,
                ":user_id" => $_SESSION["user_id"]
            ]);

            $_SESSION["client_name"] = $first_name . " " . $last_name;    

            return "success";
        } catch (PDOException $e) {
            return "Database error: $e";
        }
    }
}
?>