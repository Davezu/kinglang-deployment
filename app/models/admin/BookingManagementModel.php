<?php
require_once __DIR__ . "/../../../config/database.php";

class BookingManagementModel {
    private $conn;

    public function __construct() {
        global $pdo;
        $this->conn = $pdo;
    }

    public function getAllBookings($status, $column, $order) {

        $allowed_status = ["pending", "confirmed", "canceled", "rejected", "completed", "all"];
        $status = in_array($status, $allowed_status) ? $status : "";
        $status == "all" ? $status = "" : $status = " WHERE b.status = '$status'";

        $allowed_columns = ["client_name", "contact_number", "destination", "pickup_point", "date_of_tour", "end_of_tour", "number_of_days", "number_of_buses", "status", "payment_status", "total_cost"];
        $column = in_array($column, $allowed_columns) ? $column : "client_name";
        $order = $order === "asc" ? "ASC" : "DESC";

        try {   
            $stmt = $this->conn->prepare("
                SELECT b.booking_id, CONCAT(c.first_name, ' ', c.last_name) AS client_name, c.contact_number, b.destination, b.pickup_point, b.date_of_tour, b.end_of_tour, b.number_of_days, b.number_of_buses, b.status, b.total_cost, b.payment_status
                FROM bookings b
                JOIN clients c ON b.client_id = c.client_id
                $status
                ORDER BY $column $order 
            ");
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        }  catch (PDOException $e) {
            return "Database error: $e";
        }
    }

    // public function orderBookings($column, $order) {
    //     try {
    //         $stmt = $this->conn->prepare("
    //         SELECT b.booking_id, CONCAT(c.first_name, ' ', c.last_name) AS client_name, c.contact_number, b.destination, b.pickup_point, b.date_of_tour, b.end_of_tour, b.number_of_days, b.number_of_buses, b.status, b.payment_status, b.total_cost
    //         FROM bookings b
    //         JOIN clients c ON b.client_id = c.client_id
    //         ORDER BY $column $order
    //         ");
    //         $stmt->execute();
            
    //         return $stmt->fetchAll(PDO::FETCH_ASSOC); 
    //     }  catch (PDOException $e) {
    //         return "Database error";
    //     }
    // }

    public function sendQuote($booking_id, $total_cost) {
        try {
            $stmt = $this->conn->prepare("UPDATE bookings SET total_cost = :total_cost, balance = :total_cost WHERE booking_id = :booking_id");
            $stmt->execute([
                ":total_cost" => $total_cost,
                ":booking_id" => $booking_id
            ]);
            return "success";
        } catch (PDOException $e) {
            return "Database error: $e";
        }
    }

    public function getReschedRequests() {
        try {
            $stmt = $this->conn->prepare("
                SELECT r.request_id, r.booking_id, CONCAT(c.first_name, ' ', c.last_name) AS client_name, c.contact_number, r.new_date_of_tour, r.new_end_of_tour, r.status
                FROM reschedule_requests r
                JOIN clients c ON r.client_id = c.client_id
                ORDER BY r.new_date_of_tour DESC
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
}
?>