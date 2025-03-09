<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../models/client/AuthModel.php';

class ClientAuthController {
    private $authModel;

    public function __construct() {
        $this->authModel = new ClientAuthModel();
    }

    public function loginForm() {
        require_once __DIR__ . "/../../views/client/login.php";
    }

    public function signupForm() {
        require_once __DIR__ . "/../../views/client/signup.php";
    }

    public function signup() {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["signup"])) {
            $username = trim($_POST["username"]);
            $email = trim($_POST["email"]);
            $password = trim($_POST["new_password"]);
            $confirm_password = trim($_POST["confirm_password"]);
            
            if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
                $_SESSION["signup_message"] = "Please fill out all fields";
                header("Location: /home/signup");
                exit();
            }
        
            if ($password !== $confirm_password) {
                $_SESSION["signup_message"] = "Password did not match";
                header("Location: /home/signup");
                exit();
            }
           
            $hashed_password = password_hash($confirm_password, PASSWORD_BCRYPT);

            $message = $this->authModel->signup($username, $email, $hashed_password);
        
            if ($message === "Signup successfully!") {
                $_SESSION["signup_message"] = $message;
                header("Location: /home/signup");
                exit();
            } else {
                $_SESSION["signup_message"] = $message;
                header("Location: /home/signup");
                exit();
            }
        }
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
            $username = trim($_POST["username"]);
            $password = trim($_POST["password"]);
            
            if (empty($username) || empty($password)) {
                header("Location: /home/login");
                exit();
            } 
        
            $message = $this->authModel->login($username, $password);

            if ($message === "Login successfully!") {
                header("Location: /client/home");
                exit();
            } else {
                $_SESSION["entered_username"] = $username;
                $_SESSION["message"] = $message;
                header("Location: /home/login");
                exit();
            }
        }
    }

    public function logout() {
        session_start();
        unset($_SESSION["user_id"]);
        unset($_SESSION["username"]);
        header("Location: /home");
        exit();
    }
}
?>