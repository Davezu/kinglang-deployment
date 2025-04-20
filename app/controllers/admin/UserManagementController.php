<?php
require_once __DIR__ . "/../../models/admin/UserManagementModel.php";

class UserManagementController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new UserManagementModel();
        
        // Check if session is started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Only check authentication if this is an admin route
        if (!$this->isAdminLoginPage()) {
            // Only redirect if not on login paths
            if (!isset($_SESSION['role'])) {
                header('Location: /admin/login');
                exit();
            } else if (isset($_SESSION['role']) && 
                      ($_SESSION['role'] !== 'Super Admin' && $_SESSION['role'] !== 'Admin')) {
                header('Location: /admin/login');
                exit();
            }
        }
    }
    
    // Helper method to check if current page is admin login
    private function isAdminLoginPage() {
        $requestUri = $_SERVER['REQUEST_URI'];
        return strpos($requestUri, '/admin/login') !== false || 
               strpos($requestUri, '/admin/submit-login') !== false ||
               strpos($requestUri, '/home') === 0 ||
               $requestUri === '/';
    }
    
    public function showUserManagement() {
        require_once __DIR__ . "/../../views/admin/user_management.php";
    }
    
    public function getUserListing() {
        // Check if it's a POST request with JSON data
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
            // Get JSON data
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            $page = isset($data['page']) ? (int)$data['page'] : 1;
            $limit = isset($data['limit']) ? (int)$data['limit'] : 10;
            $searchTerm = isset($data['search']) ? $data['search'] : '';
        } else {
            // Handle regular GET requests for backward compatibility
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
        }
        
        $offset = ($page - 1) * $limit;
        
        $users = $this->userModel->getAllUsers($offset, $limit, $searchTerm);
        $totalUsers = $this->userModel->getTotalUsersCount($searchTerm);
        
        $totalPages = ceil($totalUsers / $limit);
        
        header('Content-Type: application/json');
        echo json_encode([
            'users' => $users,
            'totalUsers' => $totalUsers,
            'totalPages' => $totalPages,
            'currentPage' => $page
        ]);
    }
    
    public function getUserDetails() {
        // Check if it's a POST request with JSON data
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
            // Get JSON data
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            if (!isset($data['userId'])) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'User ID is required']);
                return;
            }
            
            $userId = (int)$data['userId'];
        } else {
            // Handle regular GET requests for backward compatibility
            if (!isset($_GET['userId'])) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'User ID is required']);
                return;
            }
            
            $userId = (int)$_GET['userId'];
        }
        
        $user = $this->userModel->getUserById($userId);
        
        header('Content-Type: application/json');
        echo json_encode($user);
    }
    
    public function addUser() {
        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }
        
        // Check if it's a JSON request
        if (strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
            // Get JSON data
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            $firstName = isset($data['firstName']) ? trim($data['firstName']) : '';
            $lastName = isset($data['lastName']) ? trim($data['lastName']) : '';
            $email = isset($data['email']) ? trim($data['email']) : '';
            $contactNumber = isset($data['contactNumber']) ? trim($data['contactNumber']) : '';
            $password = isset($data['password']) ? $data['password'] : '';
            $role = isset($data['role']) ? $data['role'] : 'Client';
        } else {
            // Get traditional POST data
            $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
            $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $contactNumber = isset($_POST['contactNumber']) ? trim($_POST['contactNumber']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $role = isset($_POST['role']) ? $_POST['role'] : 'Client';
        }
        
        // Validate input
        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'All fields are required']);
            return;
        }
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid email format']);
            return;
        }
        
        // Validate contact number (if provided)
        if (!empty($contactNumber) && !preg_match('/^[0-9]{11}$/', $contactNumber)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Contact number must be 11 digits']);
            return;
        }
        
        // Validate password (minimum 6 characters)
        if (strlen($password) < 6) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Password must be at least 6 characters']);
            return;
        }
        
        // Validate role
        $validRoles = ['Client', 'Admin', 'Super Admin'];
        if (!in_array($role, $validRoles)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid role']);
            return;
        }
        
        // Create user
        $result = $this->userModel->createUser($firstName, $lastName, $email, $contactNumber, $password, $role);
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    public function updateUser() {
        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }
        
        // Check if it's a JSON request
        if (strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
            // Get JSON data
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            $userId = isset($data['userId']) ? (int)$data['userId'] : 0;
            $firstName = isset($data['firstName']) ? trim($data['firstName']) : '';
            $lastName = isset($data['lastName']) ? trim($data['lastName']) : '';
            $email = isset($data['email']) ? trim($data['email']) : '';
            $contactNumber = isset($data['contactNumber']) ? trim($data['contactNumber']) : '';
            $password = isset($data['password']) && !empty($data['password']) ? $data['password'] : null;
            $role = isset($data['role']) ? $data['role'] : 'Client';
        } else {
            // Get traditional POST data
            $userId = isset($_POST['userId']) ? (int)$_POST['userId'] : 0;
            $firstName = isset($_POST['firstName']) ? trim($_POST['firstName']) : '';
            $lastName = isset($_POST['lastName']) ? trim($_POST['lastName']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $contactNumber = isset($_POST['contactNumber']) ? trim($_POST['contactNumber']) : '';
            $password = isset($_POST['password']) && !empty($_POST['password']) ? $_POST['password'] : null;
            $role = isset($_POST['role']) ? $_POST['role'] : 'Client';
        }
        
        // Validate input
        if ($userId <= 0 || empty($firstName) || empty($lastName) || empty($email)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'User ID, first name, last name, and email are required']);
            return;
        }
        
        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid email format']);
            return;
        }
        
        // Validate contact number (if provided)
        if (!empty($contactNumber) && !preg_match('/^[0-9]{11}$/', $contactNumber)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Contact number must be 11 digits']);
            return;
        }
        
        // Validate password (if provided)
        if ($password !== null && strlen($password) < 6) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Password must be at least 6 characters']);
            return;
        }
        
        // Validate role
        $validRoles = ['Client', 'Admin', 'Super Admin'];
        if (!in_array($role, $validRoles)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid role']);
            return;
        }
        
        // Update user
        $result = $this->userModel->updateUser($userId, $firstName, $lastName, $email, $contactNumber, $role, $password);
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }
    
    public function deleteUser() {
        // Check if it's a POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request method']);
            return;
        }
        
        // Check if it's a JSON request
        if (strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
            // Get JSON data
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            $userId = isset($data['userId']) ? (int)$data['userId'] : 0;
        } else {
            // Get traditional POST data
            $userId = isset($_POST['userId']) ? (int)$_POST['userId'] : 0;
        }
        
        if ($userId <= 0) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'User ID is required']);
            return;
        }
        
        // Delete user
        $result = $this->userModel->deleteUser($userId);
        
        header('Content-Type: application/json');
        echo json_encode($result);
    }
}
?> 