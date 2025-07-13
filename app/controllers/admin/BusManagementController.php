<?php
require_once __DIR__ . "/../../models/admin/BusManagementModel.php";

class BusManagementController {
    private $busModel;
    
    public function __construct() {
        $this->busModel = new BusManagementModel();
        
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
            } else if (isset($_SESSION['role']) && ($_SESSION['role'] !== 'Super Admin' && $_SESSION['role'] !== 'Admin')) {
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
    
    /**
     * Show the bus management page
     */
    public function showBusManagement() {
        $buses = $this->busModel->getAllBuses();
        $stats = $this->busModel->getBusUsageStats();
        require_once __DIR__ . "/../../views/admin/bus_management.php";
    }
    
    /**
     * Get all buses as JSON
     */
    public function getAllBuses() {
        $buses = $this->busModel->getAllBuses();
        echo json_encode(['success' => true, 'buses' => $buses]);
    }
    
    /**
     * Add a new bus
     */
    public function addBus() {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        // Get POST data
        $name = trim($_POST['name'] ?? '');
        $capacity = trim($_POST['capacity'] ?? '49');
        $status = trim($_POST['status'] ?? 'Active');
        $licensePlate = trim($_POST['license_plate'] ?? '');
        $model = trim($_POST['model'] ?? '');
        $year = !empty($_POST['year']) ? intval($_POST['year']) : null;
        $lastMaintenance = trim($_POST['last_maintenance'] ?? '');
        
        // Validate input
        if (empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Bus name is required']);
            return;
        }
        
        // Validate capacity (must be numeric and between 1-99)
        if (!is_numeric($capacity) || $capacity < 1 || $capacity > 99) {
            echo json_encode(['success' => false, 'message' => 'Capacity must be a number between 1 and 99']);
            return;
        }
        
        // Validate status
        if (!in_array($status, ['Active', 'Maintenance'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid status']);
            return;
        }
        
        // Validate year if provided
        if ($year !== null && ($year < 1900 || $year > date('Y') + 1)) {
            echo json_encode(['success' => false, 'message' => 'Invalid year']);
            return;
        }
        
        // Validate last maintenance date if provided
        if (!empty($lastMaintenance) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $lastMaintenance)) {
            echo json_encode(['success' => false, 'message' => 'Invalid maintenance date format. Use YYYY-MM-DD']);
            return;
        }
        
        // Add bus
        $result = $this->busModel->addBus($name, $capacity, $status, $licensePlate, $model, $year, $lastMaintenance);
        
        if ($result === true) {
            // Log the action for audit trail
            $this->logAction('add', 'Added new bus: ' . $name);
            
            echo json_encode(['success' => true, 'message' => 'Bus added successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => $result]);
        }
    }
    
    /**
     * Update a bus
     */
    public function updateBus() {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        // Get POST data
        $busId = intval($_POST['bus_id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $capacity = trim($_POST['capacity'] ?? '49');
        $status = trim($_POST['status'] ?? 'Active');
        $licensePlate = trim($_POST['license_plate'] ?? '');
        $model = trim($_POST['model'] ?? '');
        $year = !empty($_POST['year']) ? intval($_POST['year']) : null;
        $lastMaintenance = trim($_POST['last_maintenance'] ?? '');
        
        // Validate input
        if ($busId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid bus ID']);
            return;
        }
        
        if (empty($name)) {
            echo json_encode(['success' => false, 'message' => 'Bus name is required']);
            return;
        }
        
        // Validate capacity (must be numeric and between 1-99)
        if (!is_numeric($capacity) || $capacity < 1 || $capacity > 99) {
            echo json_encode(['success' => false, 'message' => 'Capacity must be a number between 1 and 99']);
            return;
        }
        
        // Validate status
        if (!in_array($status, ['Active', 'Maintenance'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid status']);
            return;
        }
        
        // Validate year if provided
        if ($year !== null && ($year < 1900 || $year > date('Y') + 1)) {
            echo json_encode(['success' => false, 'message' => 'Invalid year']);
            return;
        }
        
        // Validate last maintenance date if provided
        if (!empty($lastMaintenance) && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $lastMaintenance)) {
            echo json_encode(['success' => false, 'message' => 'Invalid maintenance date format. Use YYYY-MM-DD']);
            return;
        }
        
        // Get original bus data for audit trail
        $originalBus = $this->busModel->getBusById($busId);
        if (!$originalBus) {
            echo json_encode(['success' => false, 'message' => 'Bus not found']);
            return;
        }
        
        // Update bus
        $result = $this->busModel->updateBus($busId, $name, $capacity, $status, $licensePlate, $model, $year, $lastMaintenance);
        
        if ($result === true) {
            // Log the action for audit trail
            $changes = [];
            if ($originalBus['name'] != $name) $changes[] = "name from '{$originalBus['name']}' to '{$name}'";
            if ($originalBus['capacity'] != $capacity) $changes[] = "capacity from '{$originalBus['capacity']}' to '{$capacity}'";
            if ($originalBus['status'] != $status) $changes[] = "status from '{$originalBus['status']}' to '{$status}'";
            if ($originalBus['license_plate'] != $licensePlate) $changes[] = "license plate from '{$originalBus['license_plate']}' to '{$licensePlate}'";
            if ($originalBus['model'] != $model) $changes[] = "model from '{$originalBus['model']}' to '{$model}'";
            if ($originalBus['year'] != $year) $changes[] = "year from '{$originalBus['year']}' to '{$year}'";
            if ($originalBus['last_maintenance'] != $lastMaintenance) $changes[] = "last maintenance from '{$originalBus['last_maintenance']}' to '{$lastMaintenance}'";
            
            $this->logAction('update', 'Updated bus #' . $busId . ': ' . implode(', ', $changes));
            
            echo json_encode(['success' => true, 'message' => 'Bus updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => $result]);
        }
    }
    
    /**
     * Delete a bus
     */
    public function deleteBus() {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        // Get POST data
        $busId = intval($_POST['bus_id'] ?? 0);
        
        // Validate input
        if ($busId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid bus ID']);
            return;
        }
        
        // Get bus details for audit trail
        $bus = $this->busModel->getBusById($busId);
        if (!$bus) {
            echo json_encode(['success' => false, 'message' => 'Bus not found']);
            return;
        }
        
        // Delete bus
        $result = $this->busModel->deleteBus($busId);
        
        if ($result === true) {
            // Log the action for audit trail
            $this->logAction('delete', 'Deleted bus #' . $busId . ': ' . $bus['name']);
            
            echo json_encode(['success' => true, 'message' => 'Bus deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => $result]);
        }
    }
    
    /**
     * Get bus availability
     */
    public function getBusAvailability() {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        // Get POST data as JSON
        $data = json_decode(file_get_contents('php://input'), true);
        
        $startDate = $data['start_date'] ?? '';
        $endDate = $data['end_date'] ?? '';
        
        // Validate input
        if (empty($startDate) || empty($endDate)) {
            echo json_encode(['success' => false, 'message' => 'Start date and end date are required']);
            return;
        }
        
        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
            echo json_encode(['success' => false, 'message' => 'Invalid date format. Use YYYY-MM-DD']);
            return;
        }
        
        // Get bus availability
        $availability = $this->busModel->getBusAvailability($startDate, $endDate);
        
        echo json_encode(['success' => true, 'availability' => $availability]);
    }
    
    /**
     * Get bus schedule
     */
    public function getBusSchedule() {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        // Get POST data as JSON
        $data = json_decode(file_get_contents('php://input'), true);
        
        $busId = intval($data['bus_id'] ?? 0);
        $startDate = $data['start_date'] ?? '';
        $endDate = $data['end_date'] ?? '';
        
        // Validate input
        if ($busId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid bus ID']);
            return;
        }
        
        if (empty($startDate) || empty($endDate)) {
            echo json_encode(['success' => false, 'message' => 'Start date and end date are required']);
            return;
        }
        
        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $startDate) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $endDate)) {
            echo json_encode(['success' => false, 'message' => 'Invalid date format. Use YYYY-MM-DD']);
            return;
        }
        
        // Get bus schedule
        $schedule = $this->busModel->getBusSchedule($busId, $startDate, $endDate);
        
        echo json_encode(['success' => true, 'schedule' => $schedule]);
    }
    
    /**
     * Get bus statistics
     */
    public function getBusStats() {
        $stats = $this->busModel->getBusUsageStats();
        echo json_encode(['success' => true, 'stats' => $stats]);
    }
    
    /**
     * Log an action for audit trail
     * @param string $action The action performed
     * @param string $details Details of the action
     */
    private function logAction($action, $details) {
        if (!isset($_SESSION['user_id'])) return;
        
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['role'];
        
        // Use the model's method instead of accessing conn directly
        $this->busModel->logAuditTrail($userId, $userRole, $action, $details);
    }
}
?> 