<?php
require_once __DIR__ . "/../../models/admin/PaymentManagementModel.php";

class PaymentManagementController {
    private $paymentModel;
    
    public function __construct() {
        $this->paymentModel = new PaymentManagementModel();
    }
    
    public function index() {
        // Load the payment management view
        require_once __DIR__ . "/../../views/admin/payment_management.php";
    }
    
    public function getPayments() {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        
        $status = $data['filter'] ?? 'all';
        $column = $data['sort'] ?? 'payment_id';
        $order = $data['order'] ?? 'DESC';
        $page = (int)($data['page'] ?? 1);
        $limit = (int)($data['limit'] ?? 10);
        $search = $data['search'] ?? '';

        try {

            $payments = $this->paymentModel->getPayments($status, $column, $order, $page, $limit, $search);
            $total = $this->paymentModel->getTotalPayments($status, $search);

            echo json_encode([
                'success' => true,
                'payments' => $payments,
                'total' => $total
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function confirmPayment() {
        header('Content-Type: application/json');
        
        try {
            if (!isset($_POST['payment_id'])) {
                throw new Exception('Payment ID is required');
            }

            $paymentId = (int)$_POST['payment_id'];
            $this->paymentModel->confirmPayment($paymentId);

            echo json_encode([
                'success' => true,
                'message' => 'Payment confirmed successfully'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
    
    public function rejectPayment() {
        header('Content-Type: application/json');
        
        try {
            if (!isset($_POST['payment_id']) || !isset($_POST['reason'])) {
                throw new Exception('Payment ID and reason are required');
            }

            $paymentId = (int)$_POST['payment_id'];
            $reason = $_POST['reason'];
            $this->paymentModel->rejectPayment($paymentId, $reason);

            echo json_encode([
                'success' => true,
                'message' => 'Payment rejected successfully'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }
} 