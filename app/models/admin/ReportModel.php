<?php
require_once __DIR__ . "/../../../config/database.php";

class ReportModel {
    private $conn;

    public function __construct() {
        global $pdo;
        $this->conn = $pdo;
    }

    /**
     * Get booking summary for a date range
     */
    public function getBookingSummary($startDate = null, $endDate = null) {
        try {
            $params = [];
            $whereClause = "WHERE 1=1";
            
            if ($startDate) {
                $whereClause .= " AND date_of_tour >= :start_date";
                $params[':start_date'] = $startDate;
            }
            
            if ($endDate) {
                $whereClause .= " AND date_of_tour <= :end_date";
                $params[':end_date'] = $endDate;
            }
            
            $sql = "SELECT 
                COUNT(*) AS total_bookings,
                SUM(CASE WHEN status = 'Confirmed' THEN 1 ELSE 0 END) AS confirmed_bookings,
                SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_bookings,
                SUM(CASE WHEN status = 'Canceled' THEN 1 ELSE 0 END) AS canceled_bookings,
                SUM(CASE WHEN status = 'Rejected' THEN 1 ELSE 0 END) AS rejected_bookings,
                SUM(CASE WHEN status = 'Completed' THEN 1 ELSE 0 END) AS completed_bookings,
                SUM(total_cost) AS total_revenue,
                SUM(CASE WHEN payment_status = 'Paid' THEN total_cost ELSE 0 END) AS collected_revenue,
                SUM(CASE WHEN payment_status = 'Partially Paid' THEN balance ELSE 0 END) AS outstanding_balance,
                AVG(total_cost) AS average_booking_value,
                SUM(number_of_buses) AS total_buses_booked,
                SUM(number_of_days) AS total_days_booked
            FROM bookings 
            $whereClause";
            
            $stmt = $this->conn->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getBookingSummary: " . $e->getMessage());
            throw new Exception("Failed to generate booking summary: " . $e->getMessage());
        }
    }
    
    /**
     * Get monthly booking trend
     */
    public function getMonthlyBookingTrend($year = null) {
        try {
            $currentYear = date('Y');
            $year = $year ?: $currentYear;
            
            $sql = "SELECT 
                MONTH(date_of_tour) AS month,
                COUNT(*) AS total_bookings,
                SUM(total_cost) AS total_revenue
            FROM bookings
            WHERE YEAR(date_of_tour) = :year
            GROUP BY MONTH(date_of_tour)
            ORDER BY month";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':year', $year, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Fill in missing months with zero values
            $monthlyData = [];
            for ($i = 1; $i <= 12; $i++) {
                $monthlyData[$i] = [
                    'month' => $i,
                    'total_bookings' => 0,
                    'total_revenue' => 0
                ];
            }
            
            foreach ($result as $row) {
                $monthlyData[$row['month']] = $row;
            }
            
            return array_values($monthlyData);
        } catch (PDOException $e) {
            error_log("Error in getMonthlyBookingTrend: " . $e->getMessage());
            throw new Exception("Failed to generate monthly booking trend: " . $e->getMessage());
        }
    }
    
    /**
     * Get top destinations by booking count
     */
    public function getTopDestinations($limit = 10, $startDate = null, $endDate = null) {
        try {
            $params = [];
            $whereClause = "WHERE 1=1";
            
            if ($startDate) {
                $whereClause .= " AND date_of_tour >= :start_date";
                $params[':start_date'] = $startDate;
            }
            
            if ($endDate) {
                $whereClause .= " AND date_of_tour <= :end_date";
                $params[':end_date'] = $endDate;
            }
            
            $sql = "SELECT 
                destination,
                COUNT(*) as booking_count,
                SUM(total_cost) as total_revenue
            FROM bookings
            $whereClause
            GROUP BY destination
            ORDER BY booking_count DESC
            LIMIT :limit";
            
            $stmt = $this->conn->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getTopDestinations: " . $e->getMessage());
            throw new Exception("Failed to generate top destinations report: " . $e->getMessage());
        }
    }
    
    /**
     * Get payment method distribution
     */
    public function getPaymentMethodDistribution($startDate = null, $endDate = null) {
        try {
            $params = [];
            $whereClause = "WHERE p.status = 'Confirmed'";
            
            if ($startDate) {
                $whereClause .= " AND p.payment_date >= :start_date";
                $params[':start_date'] = $startDate;
            }
            
            if ($endDate) {
                $whereClause .= " AND p.payment_date <= :end_date";
                $params[':end_date'] = $endDate;
            }
            
            $sql = "SELECT 
                p.payment_method,
                COUNT(*) as payment_count,
                SUM(p.amount) as total_amount
            FROM payments p
            $whereClause
            GROUP BY p.payment_method
            ORDER BY payment_count DESC";
            
            $stmt = $this->conn->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getPaymentMethodDistribution: " . $e->getMessage());
            throw new Exception("Failed to generate payment method distribution: " . $e->getMessage());
        }
    }
    
    /**
     * Get cancellation reasons report
     */
    public function getCancellationReport($startDate = null, $endDate = null) {
        try {
            $params = [];
            $whereClause = "WHERE 1=1";
            
            if ($startDate) {
                $whereClause .= " AND c.created_at >= :start_date";
                $params[':start_date'] = $startDate;
            }
            
            if ($endDate) {
                $whereClause .= " AND c.created_at <= :end_date";
                $params[':end_date'] = $endDate;
            }
            
            $sql = "SELECT 
                c.reason,
                c.canceled_by,
                COUNT(*) as cancellation_count,
                SUM(b.total_cost) as total_value,
                SUM(c.amount_refunded) as total_refunded
            FROM canceled_trips c
            JOIN bookings b ON c.booking_id = b.booking_id
            $whereClause
            GROUP BY c.reason, c.canceled_by
            ORDER BY cancellation_count DESC";
            
            $stmt = $this->conn->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getCancellationReport: " . $e->getMessage());
            throw new Exception("Failed to generate cancellation report: " . $e->getMessage());
        }
    }
    
    /**
     * Get client booking history
     */
    public function getClientBookingHistory($userId) {
        try {
            $sql = "SELECT 
                b.booking_id,
                b.destination,
                b.pickup_point,
                b.date_of_tour,
                b.end_of_tour,
                b.number_of_buses,
                b.number_of_days,
                b.total_cost,
                b.status,
                b.payment_status,
                b.balance
            FROM bookings b
            WHERE b.user_id = :user_id
            ORDER BY b.date_of_tour DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getClientBookingHistory: " . $e->getMessage());
            throw new Exception("Failed to generate client booking history: " . $e->getMessage());
        }
    }
    
    /**
     * Get detailed booking list with search/filter
     */
    public function getDetailedBookingList($filters = [], $page = 1, $limit = 20) {
        try {
            $whereClause = "WHERE 1=1";
            $params = [];
            
            // Apply filters
            if (!empty($filters['start_date'])) {
                $whereClause .= " AND b.date_of_tour >= :start_date";
                $params[':start_date'] = $filters['start_date'];
            }
            
            if (!empty($filters['end_date'])) {
                $whereClause .= " AND b.date_of_tour <= :end_date";
                $params[':end_date'] = $filters['end_date'];
            }
            
            if (!empty($filters['status']) && $filters['status'] !== 'All') {
                $whereClause .= " AND b.status = :status";
                $params[':status'] = $filters['status'];
            }
            
            if (!empty($filters['payment_status']) && $filters['payment_status'] !== 'All') {
                $whereClause .= " AND b.payment_status = :payment_status";
                $params[':payment_status'] = $filters['payment_status'];
            }
            
            if (!empty($filters['search'])) {
                $whereClause .= " AND (b.destination LIKE :search OR CONCAT(u.first_name, ' ', u.last_name) LIKE :search)";
                $params[':search'] = "%{$filters['search']}%";
            }
            
            // Calculate offset for pagination
            $offset = ($page - 1) * $limit;
            
            // Get bookings
            $sql = "SELECT 
                b.booking_id, 
                b.user_id, 
                CONCAT(u.first_name, ' ', u.last_name) AS client_name, 
                u.contact_number, 
                b.destination, 
                b.pickup_point, 
                b.date_of_tour, 
                b.end_of_tour, 
                b.number_of_days, 
                b.number_of_buses, 
                b.status, 
                b.total_cost, 
                b.payment_status,
                b.balance
            FROM bookings b
            JOIN users u ON b.user_id = u.user_id
            $whereClause
            ORDER BY b.date_of_tour DESC
            LIMIT :limit OFFSET :offset";
            
            $stmt = $this->conn->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Get total count for pagination
            $countSql = "SELECT COUNT(*) as total FROM bookings b JOIN users u ON b.user_id = u.user_id $whereClause";
            $countStmt = $this->conn->prepare($countSql);
            
            foreach ($params as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            
            $countStmt->execute();
            $totalCount = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];
            
            return [
                'bookings' => $bookings,
                'total' => $totalCount,
                'page' => $page,
                'limit' => $limit,
                'total_pages' => ceil($totalCount / $limit)
            ];
        } catch (PDOException $e) {
            error_log("Error in getDetailedBookingList: " . $e->getMessage());
            throw new Exception("Failed to generate detailed booking list: " . $e->getMessage());
        }
    }
    
    /**
     * Get financial summary report
     */
    public function getFinancialSummary($year = null, $month = null) {
        try {
            $currentYear = date('Y');
            $year = $year ?: $currentYear;
            $whereClause = "WHERE YEAR(date_of_tour) = :year";
            $params = [':year' => $year];
            
            if ($month) {
                $whereClause .= " AND MONTH(date_of_tour) = :month";
                $params[':month'] = $month;
            }
            
            $sql = "SELECT 
                SUM(total_cost) AS total_revenue,
                SUM(CASE WHEN payment_status = 'Paid' THEN total_cost ELSE 0 END) AS collected_revenue,
                SUM(CASE WHEN payment_status IN ('Partially Paid', 'Unpaid') THEN balance ELSE 0 END) AS outstanding_balance,
                COUNT(DISTINCT user_id) AS unique_clients,
                AVG(total_cost) AS average_booking_value
            FROM bookings
            $whereClause";
            
            $stmt = $this->conn->prepare($sql);
            
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getFinancialSummary: " . $e->getMessage());
            throw new Exception("Failed to generate financial summary: " . $e->getMessage());
        }
    }
} 