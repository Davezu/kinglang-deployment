<?php
require_once __DIR__ . "/../../models/admin/AuthModel.php";

class AuthController {
    public $authModel;

    public function __construct() {
        $this->authModel = new AuthModel();
    }

    public function loginForm() {
        require_once __DIR__ . "/../../views/admin/login.php";
    }

    public function adminDashBoard() {
        require_once __DIR__ . "/../../views/admin/booking_management.php"; // home dapat to
    }

    public function login() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $username = trim($_POST["username"]);
            $password = trim($_POST["password"]);

            if (empty($username) || empty($password)) return "Please fill out required fields";

            $result = $this->authModel->login($username, $password);

            if ($result) {
                header("Location: /admin/dashboard");
                exit();
            } else {
                echo "incorrect password or username";  
                exit();
            }
            
        }
    }

    public function logout() {
        session_start();
        unset($_SESSION["role"]);
        header("Location: /admin/login");
        exit();
    }
}


?>