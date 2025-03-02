<?php
require_once '../../../config/database.php';
require_once '../../models/client/auth.php';

class AuthController {
    private $auth;

    public function __construct($db) {
        $this->auth = new AuthModel($db);
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

    if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $_SESSION["signup_message"] = "Please fill out all fields";
        header("Location: ../../views/client/signup.php");
        exit();
    }

    if ($password !== $confirm_password) {
        $_SESSION["signup_message"] = "Password did not match";
        header("Location: ../../views/client/signup.php");
        exit();
    }
   
    $hashed_password = password_hash($confirm_password, PASSWORD_BCRYPT);

    $controller = new AuthController($pdo);
    $message = $controller->register($username, $email, $hashed_password);

    if ($message === "Signup successfully!") {
        $_SESSION["signup_message"] = $message;
        header("Location: ../../views/client/signup.php");
        exit();
    } else {
        $_SESSION["signup_message"] = $message;
        header("Location: ../../views/client/signup.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["login"])) {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);
    
    if (empty($username) || empty($password)) {
        header("Location: ../../views/client/login.php");
        exit();
    } 

    $controller = new AuthController($pdo);
    $message = $controller->login($username, $password);
    if ($message === "Login successfully!") {
        header("Location: ../../views/client/home.php");
        exit();
    } else {
        $_SESSION["entered_username"] = $username;
        $_SESSION["message"] = $message;
        header("Location: ../../views/client/login.php");
        exit();
    }
}
?>