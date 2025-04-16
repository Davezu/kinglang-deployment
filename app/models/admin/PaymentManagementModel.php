<?php
require_once __DIR__ . "/../../../config/database.php";

class PaymentManagementModel {
    private $conn;

    public function __construct() {
        global $pdo;
        $this->conn = $pdo;
    }

    public function getPayments($status = null, $column = 'payment_id', $order = 'DESC', $page = 1, $limit = 10, $search = '') {
        try {
            $offset = ($page - 1) * $limit;
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

            $stmt = $this->conn->prepare("
                SELECT 
                    p.payment_id,
                    p.amount,
                    p.payment_method,
                    p.proof_of_payment,
                    p.status,
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

            // Update payment status
            $sql = "UPDATE payments SET status = 'Confirmed', updated_at = NOW() WHERE payment_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $paymentId]);

            // Update booking payment status
            $sql = "UPDATE bookings b 
                    JOIN payments p ON b.booking_id = p.booking_id 
                    SET b.payment_status = 'Paid', b.status = 'Confirmed'
                    WHERE p.payment_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $paymentId]);

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

            // Update payment status
            $sql = "UPDATE payments SET status = 'Rejected', updated_at = NOW() WHERE payment_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $paymentId]);

            // Update booking status
            $sql = "UPDATE bookings b 
                    JOIN payments p ON b.booking_id = p.booking_id 
                    SET b.status = 'Rejected'
                    WHERE p.payment_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $paymentId]);

            // Record rejection reason
            $sql = "INSERT INTO rejected_trips (reason, type, booking_id, user_id) 
                    SELECT :reason, 'Booking', b.booking_id, p.user_id 
                    FROM payments p 
                    JOIN bookings b ON p.booking_id = b.booking_id 
                    WHERE p.payment_id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $paymentId, ':reason' => $reason]);

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            error_log("Error in rejectPayment: " . $e->getMessage());
            throw new Exception("Failed to reject payment: " . $e->getMessage());
        }
    }   
} 