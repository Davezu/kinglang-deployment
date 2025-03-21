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
        require_once __DIR__ . "/../../views/admin/booking_management.php"; // dashboard dapat to
    }

    public function login() {
        header("Content-Type: application/json");

        $data = json_decode(file_get_contents("php://input"), true);

        $email = trim($data["email"]);
        $password = trim($data["password"]);

        $_SESSION["email"] = $email;
        $_SESSION["password"] = $password;

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
            echo json_encode(["success" => true, "redirect" => "/admin/booking-requests"]);
        } else {
            echo json_encode(["success" => false, "message" => $message]);
        }      
    }

    public function logout() {
        session_start();
        unset($_SESSION["role"]);
        unset($_SESSION["admin_name"]);
        header("Location: /admin/login");
        exit();
    }
}


?>