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

    public function manageAccountForm() {
        require_once __DIR__ . "/../../views/client/user_account.php";
    }

    public function signup() {
        header("Content-Type: application/json");

        $data = json_decode(file_get_contents("php://input"), true);
        
        $first_name = trim($data["firstName"]);
        $last_name = trim($data["lastName"]);
        $email = trim($data["email"]);
        $contact_number = trim($data["contactNumber"]);
        $password = trim($data["password"]);
        $confirm_password = trim($data["confirmPassword"]);
        
        if (empty($first_name) || empty($last_name) || empty($email) || empty($contact_number) || empty($password) || empty($confirm_password)) {
            echo json_encode(["success" => false, "message" => "Please fill out all fields."]);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode(["success" => false, "message" => "Invalid email address."]);
            return;
         }
    
        if ($password !== $confirm_password) {
            echo json_encode(["success" => false, "message" => "Password did not match."]);
            return;
        }
        
        $hashed_password = password_hash($confirm_password, PASSWORD_BCRYPT);

        $message = $this->authModel->signup($first_name, $last_name, $email, $contact_number, $hashed_password);
    
        if ($message === "success") {
            echo json_encode(["success" => true, "message" => "Sign up successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => $message]);
        }
        
    }

    public function login() {
        header("Content-Type: application/json");

        $data = json_decode(file_get_contents("php://input"), true);

        $email = trim($data["email"]);
        $password = trim($data["password"]);

        if (empty($email) || empty($password)) {
            echo json_encode(["success" => false, "message" => "Please fill out required fields."]);
            return;
        } 

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {   
           echo json_encode(["success" => false, "message" => "Invalid email address."]);
           return;
        }
    
        $result = $this->authModel->login($email, $password);

        if ($result === "success") {
            echo json_encode(["success" => true, "redirect" => "/home/booking-requests"]);
        } else {
            echo json_encode(["success" => false, "message" => $result]);
        }
    }

    public function getClientInformation() {
        $client = $this->authModel->getClientInformation();

        header("Content-Type: application/json");

        if (is_array($client)) {
            echo json_encode(['success' => true, 'client' => $client]);
        } else {
            echo json_encode(['success' => false, 'message' => $client]);
        }
    }

    public function updateClientInformation() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents("php://input"), true);

            $first_name = $data["firstName"];
            $last_name = $data["lastName"];
            $contact_number = $data["contactNumber"];
            $email_address = $data["email"];

            $result = $this->authModel->updateClientInformation($first_name, $last_name, $contact_number, $email_address);

            header("Content-Type: application/json");

            if ($result === "success") {
                echo json_encode(['success' => true, 'message' => 'Updated successfully!']);
            } else {
                echo json_encode(['success' => false, 'message' => $result]);
            }
        }
    }

    public function logout() {
        session_start();
        unset($_SESSION["user_id"]);
        unset($_SESSION["email"]);
        unset($_SESSION["client_name"]);
        header("Location: /home");
        exit();
    }
}
?>