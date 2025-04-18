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
        require_once __DIR__ . "/../../views/admin/dashboard.php";
    }

    public function login() {
        header("Content-Type: application/json");

        $data = json_decode(file_get_contents("php://input"), true);

        $email = trim($data["email"]);
        $password = trim($data["password"]);

        if (empty($email) || empty($password)) {
            echo json_encode(["success" => false, "message" => "Please fill out all fields"]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["success" => false, "message" => "Invalid email."]);
            return;
        }

        $message = $this->authModel->login($email, $password);

        if ($message === "success") {
            echo json_encode(["success" => true, "redirect" => "/admin/dashboard"]);
        } else {
            echo json_encode(["success" => false, "message" => $message]);
        }      
    }

    public function logout() {
        // Only unset admin-specific session variables
        unset($_SESSION["role"]);
        unset($_SESSION["admin_name"]);
        // Don't destroy the entire session as it affects client login
        // $_SESSION = array();
        // session_destroy();
        header("Location: /admin/login");
        exit();
    }
}


?>