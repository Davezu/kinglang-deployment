<?php
require_once __DIR__ . "/../../../config/database.php";

class BookingManagementModel {
    private $conn;

    public function __construct() {
        global $pdo;
        $this->conn = $pdo;
    }

    public function getAllBookings($status, $column, $order) {

        $allowed_status = ["Pending", "Confirmed", "Canceled", "Rejected", "Completed", "All"];
        $status = in_array($status, $allowed_status) ? $status : "";
        $status == "All" ? $status = "" : $status = " WHERE b.status = '$status'";

        $allowed_columns = ["booking_id", "client_name", "contact_number", "destination", "pickup_point", "date_of_tour", "end_of_tour", "number_of_days", "number_of_buses", "status", "payment_status", "total_cost"];
        $column = in_array($column, $allowed_columns) ? $column : "client_name";
        $order = $order === "asc" ? "ASC" : "DESC";

        try {   
            $stmt = $this->conn->prepare("
                SELECT b.booking_id, CONCAT(u.first_name, ' ', u.last_name) AS client_name, u.contact_number, b.destination, b.pickup_point, b.date_of_tour, b.end_of_tour, b.number_of_days, b.number_of_buses, b.status, b.total_cost, b.payment_status
                FROM bookings b
                JOIN users u ON b.user_id = u.user_id
                $status
                ORDER BY $column $order 
            ");
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }  catch (PDOException $e) {
            return "Database error: $e";
        }
    }

    public function confirmBooking($booking_id) {
        try {
            $stmt = $this->conn->prepare("UPDATE bookings SET status = 'Confirmed' WHERE booking_id = :booking_id");
            $stmt->execute([":booking_id" => $booking_id]);
            return "success";
        } catch (PDOException $e) {
            return "Database error.";
        }
    }

    public function getReschedRequests($status, $column, $order) {
        $allowed_status = ["pending", "confirmed", "canceled", "rejected", "completed", "all"];
        $status = in_array($status, $allowed_status) ? $status : "";
        $status == "all" ? $status = "" : $status = " WHERE r.status = '$status'";

        $allowed_columns = ["booking_id", "client_name", "contact_number", "new_date_of_tour", "new_end_of_tour", "status"];
        $column = in_array($column, $allowed_columns) ? $column : "client_name";
        $order = $order === "asc" ? "ASC" : "DESC";

        try {
            $stmt = $this->conn->prepare("
                SELECT r.request_id, r.booking_id, CONCAT(u.first_name, ' ', u.last_name) AS client_name, u.contact_number, r.new_date_of_tour, r.new_end_of_tour, r.status
                FROM reschedule_requests r
                JOIN users u ON r.user_id = u.user_id
                $status
                ORDER BY $column $order
            ");
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        }  catch (PDOException $e) {
            return "Database error: $e";
        }
    }

    public function confirmReschedRequest($request_id, $booking_id, $new_date_of_tour, $new_end_of_tour) {
        try {
            $stmt = $this->conn->prepare("UPDATE reschedule_requests SET status = 'confirmed' WHERE request_id = :request_id");
            $result = $stmt->execute([":request_id" => $request_id]);

            $stmt = $this->conn->prepare("UPDATE bookings SET date_of_tour = :new_date_of_tour, end_of_tour = :new_end_of_tour WHERE booking_id = :booking_id");
            $result = $stmt->execute([
                ":new_date_of_tour" => $new_date_of_tour,
                ":new_end_of_tour" => $new_end_of_tour,
                ":booking_id" => $booking_id
            ]);

            return "success";
        } catch (PDOException $e) {
            return "Database error: $e";
        }
    }

    public function confirmRebookingRequest() {
        
    }





    public function summaryMetrics() {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) total_bookings FROM bookings");
            $stmt->execute();
            $total_bookings = $stmt->fetchColumn();

            $stmt = $this->conn->prepare("SELECT SUM(amount) as total_revenue FROM payments");
            $stmt->execute();
            $total_revenue = $stmt->fetchColumn() ?? 0;

            $stmt = $this->conn->prepare("SELECT COUNT(*) as upcoming_trips FROM bookings WHERE status = 'Confirmed' AND date_of_tour > CURDATE()");
            $stmt->execute();
            $upcoming_trips = $stmt->fetchColumn();

            $stmt = $this->conn->prepare("SELECT COUNT(*) as pending_bookings FROM bookings WHERE status = 'Pending'");
            $stmt->execute();
            $pending_bookings = $stmt->fetchColumn();

            $stmt = $this->conn->prepare("SELECT COUNT(*) as flagged_bookings FROM bookings WHERE status = 'Confirmed' AND payment_status IN ('Unpaid', 'Partially Paid')");
            $stmt->execute();
            $flagged_bookings = $stmt->fetchColumn();

            return ["total_bookings" => $total_bookings, "total_revenue" => $total_revenue, "upcoming_trips" => $upcoming_trips, "pending_bookings" => $pending_bookings, "flagged_bookings" => $flagged_bookings];

        } catch(PDOException $e) {
            return "Database error. $e";
        }
    }

    function paymentMethodChart() {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) AS cash FROM payments WHERE payment_method = 'Cash'");
            $stmt->execute();
            $cash = $stmt->fetchColumn();
            
            $stmt = $this->conn->prepare("SELECT COUNT(*) AS bank_transfer FROM payments WHERE payment_method = 'Bank Transfer'");
            $stmt->execute();
            $bank_transfer = $stmt->fetchColumn();
            
            $stmt = $this->conn->prepare("SELECT COUNT(*) AS online FROM payments WHERE payment_method = 'Online'");
            $stmt->execute();
            $online = $stmt->fetchColumn();

            return ["Cash" => $cash, "Bank" => $bank_transfer, "Online" => $online];
        } catch (PDOException $e) {
            return "Database error: $e";    
        }
    }
    
}
?>