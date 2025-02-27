<?php
require_once '../../../config/database.php';
require_once '../../models/client/auth.php';

class AuthController {
    private $auth;

    public function __construct($db) {
        $this->auth = new Auth($db);
    }

    public function register($username, $email, $password) {
        return $this->auth->register($username, $email, $password);
    }

    public function login($username, $password) {
        return $this->auth->login($username, $password);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["signup"])) {
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["new_password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    if ($password !== $confirm_password) {
        echo "Password did not match";
        exit();
    }

    if (!empty($username) && !empty($email) && !empty($password)) {
        $hashed_password = password_hash($confirm_password, PASSWORD_BCRYPT);

        $controller = new AuthController($pdo);
        $message = $controller->register($username, $email, $hashed_password);

        if ($message === "Signup successfully!") {
            echo "Signup successfully!";
        } else {
            echo "<script>alert('$message');</script>";
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    $controller = new AuthController($pdo);
    $message = $controller->login($username, $password);

    if (!empty($username) && !empty($password)) {
        if ($message === "Login successfully!") {
            echo "Login successfully!";
            header("Location: ../../views/client/booking.php");
            exit();
        } else {
            echo "<script>alert('$message')</script>";
        }
    } else {
        echo "<script>alert('Username and password cannot be empty!')</script>";
        exit();
    }
    
}
?>