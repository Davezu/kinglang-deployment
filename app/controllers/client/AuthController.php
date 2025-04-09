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
        
        $message = $this->authModel->signup($first_name, $last_name, $email, $contact_number, $password);
    
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

    public function showForgotForm() {
        include __DIR__ . '/../../views/client/forgot_password.php';
    }

    public function showResetForm($token) {
        $user = $this->authModel->findByToken($token);
        if ($user) {
            include __DIR__ . '/../../views/client/reset_password.php';
        } else {
            echo "Invalid or expired token.";
        }
    }

    public function sendResetLink() {
        header("Content-Type: application/json");
        $data = json_decode(file_get_contents("php://input"), true);
        $email = trim($data["email"]);  

        $user = $this->authModel->findByEmail($email);
        if ($user) {
            $token = bin2hex(random_bytes(16));
            $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
            $this->authModel->saveResetToken($email, $token, $expiry);

            // PHPMailer setup
            require_once __DIR__ . '/../../../vendor/autoload.php';
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'vjericken@gmail.com';
            $mail->Password = 'bhlo vzae uepw ypxl';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // configure mail (host, SMTP, etc.)
            $mail->setFrom('vjericken@gmail.com', 'Booking System');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Reset Password";
            $mail->Body = "Click here to reset your password: 
                <a href='http://localhost:9999/reset-password/$token'>Reset Password</a>";
            $mail->send();
            echo json_encode(["success" => true, "message" => "Reset link sent to your email."]);
        } else {
            echo json_encode(["success" => false, "message" => "Email not found."]);
        }
    }

    public function resetPassword() {
        header("Content-Type: application/json");
        $data = json_decode(file_get_contents("php://input"), true);
        $token = trim($data["token"]);
        $newPassword = trim($data["newPassword"]);

        $validPassword = $this->authModel->isValidPassword($newPassword);
        if (!$validPassword) {
            echo json_encode(["success" => false, "message" => "Invalid password."]);
            return;
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $result = $this->authModel->updatePassword($token, $hashedPassword);
        if ($result) {
            echo json_encode(["success" => true, "message" => "Password reset successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to reset password."]);
        }
    }
}
?>