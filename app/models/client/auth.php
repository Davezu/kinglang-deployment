<?php
class Auth {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function emailExist($email) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([":email" => $email]);
        return $stmt->fetch() ? true : false;
    }

    public function register($username, $email, $password) {
        if ($this->emailExist($email)) {
            return "Email already exists!";
        }

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
            return false;
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
                session_start();

                $_SESSION["username"] = $user["username"];
                $_SESSION["user_id"] = $user["user_id"];
                return "Login successfully!";
            } 
            return "Incorrect password";
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>