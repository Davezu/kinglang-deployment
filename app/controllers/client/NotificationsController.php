<?php
require_once __DIR__ . "/../../models/client/NotificationModel.php";

class ClientNotificationsController {
    private $notificationModel;
    
    public function __construct() {
        $this->notificationModel = new ClientNotificationModel();
        
        // Check if the user is logged in as a client
        $requestUri = $_SERVER['REQUEST_URI'];
        if (strpos($requestUri, '/home') === 0 && 
            strpos($requestUri, '/home/login') === false && 
            strpos($requestUri, '/home/signup') === false) {
            
            if (!isset($_SESSION["user_id"])) {
                header("Location: /home/login");
                exit();
            }
        }
    }
    
    public function getNotifications() {
        if (!isset($_SESSION["user_id"])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            exit;
        }
        
        $user_id = $_SESSION["user_id"];
        $notifications = $this->notificationModel->getAllNotifications($user_id, 10);
        $unreadCount = $this->notificationModel->getNotificationCount($user_id);
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true, 
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }
    
    public function getAllNotificationsWithPagination() {
        if (!isset($_SESSION["user_id"])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            exit;
        }
        
        $user_id = $_SESSION["user_id"];
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
        
        // Validate page and limit
        if ($page < 1) $page = 1;
        if ($limit < 1 || $limit > 100) $limit = 20;
        
        // Calculate offset
        $offset = ($page - 1) * $limit;
        
        // Get total count for pagination
        $total = $this->notificationModel->getTotalNotificationCount($user_id);
        $total_pages = ceil($total / $limit);
        
        // Get notifications for this page
        $notifications = $this->notificationModel->getAllNotificationsWithPagination($user_id, $limit, $offset);
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true, 
            'notifications' => $notifications,
            'pagination' => [
                'total_records' => $total,
                'total_pages' => $total_pages,
                'current_page' => $page,
                'limit' => $limit
            ]
        ]);
    }
    
    public function markAsRead() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['notification_id'])) {
            $notification_id = $_POST['notification_id'];
            $success = $this->notificationModel->markAsRead($notification_id);
            
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
            if (!isset($_SESSION["user_id"])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Not authenticated']);
                exit;
            }
            
            $user_id = $_SESSION["user_id"];
            $success = $this->notificationModel->markAllAsRead($user_id);
            
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