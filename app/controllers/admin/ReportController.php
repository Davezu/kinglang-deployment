<?php
require_once __DIR__ . "/../../models/admin/ReportModel.php";

class ReportController {
    private $reportModel;
    
    public function __construct() {
        $this->reportModel = new ReportModel();
        
        // Check if the user is logged in and is an admin
        if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Super Admin") {
            header("Location: /admin/login");
            exit();
        }
    }
    
    /**
     * Display the main reports page
     */
    public function index() {
        require_once __DIR__ . "/../../views/admin/reports.php";
    }
    
    /**
     * Generate booking summary report
     */
    public function getBookingSummary() {
        try {
            $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : null;
            $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : null;
            
            $result = $this->reportModel->getBookingSummary($startDate, $endDate);
            
            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Generate monthly booking trend report
     */
    public function getMonthlyBookingTrend() {
        try {
            $year = isset($_GET['year']) ? intval($_GET['year']) : null;
            
            $result = $this->reportModel->getMonthlyBookingTrend($year);
            
            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Generate top destinations report
     */
    public function getTopDestinations() {
        try {
            $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
            $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : null;
            $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : null;
            
            $result = $this->reportModel->getTopDestinations($limit, $startDate, $endDate);
            
            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Generate payment method distribution report
     */
    public function getPaymentMethodDistribution() {
        try {
            $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : null;
            $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : null;
            
            $result = $this->reportModel->getPaymentMethodDistribution($startDate, $endDate);
            
            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Generate cancellation report
     */
    public function getCancellationReport() {
        try {
            $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : null;
            $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : null;
            
            $result = $this->reportModel->getCancellationReport($startDate, $endDate);
            
            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Generate detailed booking list
     */
    public function getDetailedBookingList() {
        try {
            $filters = [
                'start_date' => isset($_GET['start_date']) ? $_GET['start_date'] : null,
                'end_date' => isset($_GET['end_date']) ? $_GET['end_date'] : null,
                'status' => isset($_GET['status']) ? $_GET['status'] : null,
                'payment_status' => isset($_GET['payment_status']) ? $_GET['payment_status'] : null,
                'search' => isset($_GET['search']) ? $_GET['search'] : null
            ];
            
            $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
            $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 20;
            
            $result = $this->reportModel->getDetailedBookingList($filters, $page, $limit);
            
            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
    
    /**
     * Generate financial summary report
     */
    public function getFinancialSummary() {
        try {
            $year = isset($_GET['year']) ? intval($_GET['year']) : null;
            $month = isset($_GET['month']) ? intval($_GET['month']) : null;
            
            $result = $this->reportModel->getFinancialSummary($year, $month);
            
            header('Content-Type: application/json');
            echo json_encode($result);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            http_response_code(500);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }
} 