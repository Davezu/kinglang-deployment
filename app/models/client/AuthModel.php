<?php
require_once __DIR__ . "/../../../config/database.php";
session_start();

class ClientAuthModel {
    private $conn;

    public function __construct() {
        global $pdo;
        $this->conn = $pdo  ;
    }

    public function emailExist($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([":email" => $email]);
        return $stmt->fetch() ? true : false;
    }

    public function usernameExist($username) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute([":username" => $username]);
        return $stmt->fetch() ? true : false;
    }

    // function isValidPassword($password) {
    //     $pattern = "/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";
    
    //     return preg_match($pattern, $password);
    // }

    public function signup($username, $email, $password) {
        if ($this->usernameExist($username)) {
            return "Username already exsits!";
        }

        if ($this->emailExist($email)) {
            return "Email already exists!";
        }

        // if (!$this->isValidPassword($password)) {
        //     return "Invalid password.";
        // }   

        try {
            $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (:username, :email, :password)");
            $result = $stmt->execute([
                ":username" => $username,
                ":email" => $email,
                ":password" => $password
            ]);

            if ($result) {
                return "Signup successfully!";
            }
            return "Error signing up.";
        } catch (PDOException $e) {
            return "Database error.";
        }
    }

    public function login($username, $password) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->execute([":username" => $username]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user) {
                return "Username not found!";
            }

            if ($user && password_verify($password, $user["password"])) {

                $_SESSION["username"] = $user["username"];
                $_SESSION["user_id"] = $user["user_id"];
                
                return "Login successfully!";
            } 
            return "Incorrect password!";
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>