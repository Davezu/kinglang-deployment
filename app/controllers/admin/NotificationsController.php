<?php
require_once __DIR__ . "/../../../app/models/admin/NotificationModel.php";

class NotificationsController {
    private $notificationModel;
    
    public function __construct() {
        $this->notificationModel = new NotificationModel();
        
        // Check if the user is logged in and is an admin
        $requestUri = $_SERVER['REQUEST_URI'];
        if (strpos($requestUri, '/admin') === 0 && 
            strpos($requestUri, '/admin/login') === false && 
            strpos($requestUri, '/admin/submit-login') === false) {
            
            if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Super Admin") {
                header("Location: /admin/login");
                exit();
            }
        }
    }
    
    public function index() {
        // Get all notifications for the notifications page
        $notifications = $this->notificationModel->getAllNotifications(50); // Show last 50 notifications
        
        require_once __DIR__ . "/../../views/admin/notifications/index.php";
    }
    
    public function markAsRead() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notification_id'])) {
            $notification_id = $_POST['notification_id'];
            $success = $this->notificationModel->markAsRead($notification_id);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit;
        }
        
        // Invalid request
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit;
    }
    
    public function markAllAsRead() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $success = $this->notificationModel->markAllAsRead();
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode(['success' => $success]);
            exit;
        }
        
        // Invalid request
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
        exit;
    }
} 