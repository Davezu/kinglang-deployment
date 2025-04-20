<?php
require_once __DIR__ . "/../../../config/database.php";
require_once __DIR__ . "/../client/NotificationModel.php";

class PaymentManagementModel {
    private $conn;
    private $clientNotificationModel;

    public function __construct() {
        global $pdo;
        $this->conn = $pdo;
        $this->clientNotificationModel = new ClientNotificationModel();
    }

    public function getPayments($status = null, $column = 'payment_id', $order = 'DESC', $page = 1, $limit = 10, $search = '') {
        try {
            $offset = ($page - 1) * $limit;
            $params = [];
            $whereClause = "WHERE is_canceled = 0";

            if ($status && $status !== 'all') {
                $whereClause .= " AND p.status = :status";
                $params[':status'] = $status;
            }

            if ($search) {
                $whereClause .= " AND (b.booking_id LIKE :search OR CONCAT(u.first_name, ' ', u.last_name) LIKE :search)";
                $params[':search'] = "%$search%";
            }

            $stmt = $this->conn->prepare("
                SELECT 
                    p.payment_id,
                    p.amount,
                    p.payment_method,
                    p.proof_of_payment,
                    p.status,
                    p.payment_date,
                    p.is_canceled,
                    b.booking_id,
                    b.destination,
                    b.pickup_point,
                    b.date_of_tour,
                    b.total_cost,
                    CONCAT(u.first_name, ' ', u.last_name) AS client_name,
                    u.contact_number
                FROM payments p 
                JOIN bookings b ON p.booking_id = b.booking_id 
                JOIN users u ON p.user_id = u.user_id 
                $whereClause 
                ORDER BY $column $order
                LIMIT :limit OFFSET :offset
            ");
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getPayments: " . $e->getMessage());
            throw new Exception("Failed to retrieve payments: " . $e->getMessage());
        }
    }

    public function getTotalPayments($status = null, $search = '') {
        try {
            $params = [];
            $whereClause = "WHERE 1=1";

            if ($status && $status !== 'all') {
                $whereClause .= " AND p.status = :status";
                $params[':status'] = $status;
            }

            if ($search) {
                $whereClause .= " AND (b.booking_id LIKE :search OR CONCAT(u.first_name, ' ', u.last_name) LIKE :search)";
                $params[':search'] = "%$search%";
            }

            $sql = "SELECT COUNT(*) as total 
                    FROM payments p 
                    JOIN bookings b ON p.booking_id = b.booking_id 
                    JOIN users u ON p.user_id = u.user_id 
                    $whereClause";

            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (PDOException $e) {
            error_log("Error in getTotalPayments: " . $e->getMessage());
            throw new Exception("Failed to get total payments: " . $e->getMessage());
        }
    }

    public function confirmPayment($paymentId) {
        try {
            $this->conn->beginTransaction();

            // Get booking ID from payment
            $sql = "SELECT p.booking_id, p.user_id, p.amount, b.destination 
                    FROM payments p
                    JOIN bookings b ON p.booking_id = b.booking_id 
                    WHERE p.payment_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $paymentId]);
            $paymentData = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$paymentData) {
                throw new Exception("Payment not found");
            }
            
            $bookingId = $paymentData['booking_id'];
            $userId = $paymentData['user_id'];
            $amount = $paymentData['amount'];
            $destination = $paymentData['destination'];

            // Update payment status
            $sql = "UPDATE payments SET status = 'Confirmed', updated_at = NOW() WHERE payment_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $paymentId]);

            // Load the client's BookingModel to use its payment status update logic
            require_once __DIR__ . "/../../models/client/BookingModel.php";
            $bookingModel = new Booking();
            $bookingModel->updatePaymentStatus($bookingId);

            // Add client notification
            $clientMessage = "Your payment of " . number_format($amount, 2) . " for booking to " . $destination . " has been confirmed.";
            $this->clientNotificationModel->addNotification($userId, "payment_confirmed", $clientMessage, $bookingId);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error in confirmPayment: " . $e->getMessage());
            throw new Exception("Failed to confirm payment: " . $e->getMessage());
        }
    }

    public function rejectPayment($paymentId, $reason) {
        try {
            $this->conn->beginTransaction();

            // Get booking ID from payment
            $sql = "SELECT p.booking_id, p.user_id, p.amount, b.destination 
                    FROM payments p
                    JOIN bookings b ON p.booking_id = b.booking_id 
                    WHERE p.payment_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $paymentId]);
            $paymentData = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$paymentData) {
                throw new Exception("Payment not found");
            }
            
            $bookingId = $paymentData['booking_id'];
            $userId = $paymentData['user_id'];
            $amount = $paymentData['amount'];
            $destination = $paymentData['destination'];

            // Update payment status
            $sql = "UPDATE payments SET status = 'Rejected', updated_at = NOW() WHERE payment_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $paymentId]);

            // Check if there are any other pending payments for this booking
            $sql = "SELECT COUNT(*) FROM payments 
                    WHERE booking_id = :booking_id 
                    AND payment_id != :payment_id 
                    AND status = 'PENDING'";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':booking_id' => $bookingId, ':payment_id' => $paymentId]);
            $otherPendingPayments = $stmt->fetchColumn();

            // If this was the only pending payment, revert booking to its previous status
            if ($otherPendingPayments == 0) {
                $sql = "UPDATE bookings SET status = 'Confirmed'
                        WHERE booking_id = :booking_id AND status = 'Processing'";
                $stmt = $this->conn->prepare($sql);
                $stmt->execute([':booking_id' => $bookingId]);
            }

            // Record rejection reason
            $sql = "INSERT INTO rejected_trips (reason, type, booking_id, user_id) 
                    VALUES (:reason, 'Payment', :booking_id, :user_id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                ':reason' => $reason, 
                ':booking_id' => $bookingId, 
                ':user_id' => $userId
            ]);

            // Add client notification
            $clientMessage = "Your payment of " . number_format($amount, 2) . " for booking to " . $destination . " has been rejected. Reason: " . $reason;
            $this->clientNotificationModel->addNotification($userId, "payment_rejected", $clientMessage, $bookingId);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error in rejectPayment: " . $e->getMessage());
            throw new Exception("Failed to reject payment: " . $e->getMessage());
        }
    }   
} 