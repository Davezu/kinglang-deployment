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
        $status == "All" ? $status = "" : $status = " AND b.status = '$status'";

        $allowed_columns = ["booking_id", "client_name", "contact_number", "destination", "pickup_point", "date_of_tour", "end_of_tour", "number_of_days", "number_of_buses", "status", "payment_status", "total_cost"];
        $column = in_array($column, $allowed_columns) ? $column : "client_name";
        $order = $order === "asc" ? "ASC" : "DESC";

        try {   
            $stmt = $this->conn->prepare("
                SELECT b.booking_id, b.user_id, CONCAT(u.first_name, ' ', u.last_name) AS client_name, u.contact_number, b.destination, b.pickup_point, b.date_of_tour, b.end_of_tour, b.number_of_days, b.number_of_buses, b.status, b.total_cost, b.payment_status
                FROM bookings b
                JOIN users u ON b.user_id = u.user_id
                WHERE is_rebooking = 0 AND is_rebooked = 0
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

    public function rejectBooking($reason, $booking_id, $user_id) {
        $type = "Booking";

        try {            
            $stmt = $this->conn->prepare("UPDATE bookings SET status = 'Rejected' WHERE booking_id = :booking_id");
            $stmt->execute([":booking_id" => $booking_id]);

            $stmt = $this->conn->prepare("INSERT INTO rejected_trips (reason, type, booking_id, user_id) VALUES (:reason, :type, :booking_id, :user_id)");
            $stmt->execute([
                ":reason" => $reason,
                ":type" => $type,
                ":booking_id" => $booking_id,
                ":user_id" => $user_id
            ]);


            return ["success" => true];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Database error: " . $e->getMessage()];
        }
    }

    public function getRebookingRequests($status, $column, $order) {
        $allowed_status = ["Pending", "Confirmed", "Canceled", "Rejected", "Completed", "All"];
        $status = in_array($status, $allowed_status) ? $status : "";
        $status == "All" ? $status = "" : $status = " WHERE r.status = '$status'";

        $allowed_columns = ["booking_id", "client_name", "contact_number", "new_date_of_tour", "new_end_of_tour", "status"];
        $column = in_array($column, $allowed_columns) ? $column : "client_name";
        $order = $order === "asc" ? "ASC" : "DESC";

        try {
            $stmt = $this->conn->prepare("
                SELECT b.booking_id, r.request_id, b.user_id, CONCAT(u.first_name, ' ', u.last_name) AS client_name, u.contact_number, u.email, b.destination, b.pickup_point, b.number_of_days, b.number_of_buses, r.status, b.payment_status, b.total_cost, b.balance, b.date_of_tour, b.end_of_tour
                FROM rebooking_request r
                JOIN users u ON r.user_id = u.user_id
                JOIN bookings b ON r.rebooking_id = b.booking_id
                $status
                ORDER BY $column $order
            ");
            $stmt->execute();
        
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        }  catch (PDOException $e) {
            return "Database error: $e";
        }
    }

    public function getBookingIdFromRebookingRequest($rebooking_id) {
        try {
            $stmt = $this->conn->prepare("SELECT booking_id FROM rebooking_request WHERE rebooking_id = :rebooking_id");
            $stmt->execute([ ":rebooking_id" => $rebooking_id ]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return "Databse error: $e";
        }
    }

    public function confirmRebookingRequest($rebooking_id) {
        $booking_id = $this->getBookingIdFromRebookingRequest($rebooking_id) ?? 0;

        if ($booking_id === 0) {
            return ["success" => false, "message" => "Unable to get booking ID."];
        }

        try {
            $stmt = $this->conn->prepare("UPDATE rebooking_request SET status = 'Confirmed' WHERE rebooking_id = :rebooking_id");
            $stmt->execute([":rebooking_id" => $rebooking_id]);

            $stmt = $this->conn->prepare("UPDATE bookings SET is_rebooked = 1 WHERE booking_id = :booking_id");
            $stmt->execute([ ":booking_id" => $booking_id]);

            $stmt = $this->conn->prepare("UPDATE bookings SET is_rebooking = 0 WHERE booking_id = :booking_id");
            $stmt->execute([ ":booking_id" => $rebooking_id]);

            return ["success" => true];
        } catch (PDOException $e) {
            return ["success" => false, "message" =>  "Database error: " . $e->getMessage()];
        }
    }

    public function rejectRebooking($reason, $booking_id, $user_id) {
        $type = "Rebooking";
        
        try {
            $stmt = $this->conn->prepare("UPDATE rebooking_request SET status = 'Rejected' WHERE rebooking_id = :rebooking_id");
            $stmt->execute([":rebooking_id" => $booking_id]);

            $stmt = $this->conn->prepare("INSERT INTO rejected_trips (reason, type, booking_id, user_id) VALUES (:reason, :type, :booking_id, :user_id)");
            $stmt->execute([
                ":reason" => $reason,
                ":type" => $type,
                ":booking_id" => $booking_id,
                ":user_id" => $user_id
            ]);


            return ["success" => true];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Database error: " . $e->getMessage()];
        }
    }


    public function getBooking($booking_id) {
        try {
            $stmt = $this->conn->prepare("
                SELECT b.booking_id, u.user_id, CONCAT(u.first_name, ' ', u.last_name) AS client_name, u.email, u.contact_number, b.pickup_point, b.destination, b.number_of_days, b.number_of_buses, b.date_of_tour, b.end_of_tour, b.status, b.payment_status, b.payment_status, b.total_cost, b.balance
                FROM bookings b
                JOIN users u ON b.user_id = u.user_id
                WHERE booking_id = :booking_id
            ");
            $stmt->execute([":booking_id" => $booking_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Database error";
        }
    }






    public function getBookingStops($booking_id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM booking_stops WHERE booking_id = :booking_id ORDER BY stop_order");
            $stmt->execute([":booking_id" => $booking_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
        } catch (PDOException $e) {
            return "Database error.";
        }
    }

    public function getTripDistances($booking_id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM trip_distances WHERE booking_id = :booking_id");
            $stmt->execute([":booking_id" => $booking_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Database error";
        }
    }

    public function getDieselPrice() {
        try {
            $stmt = $this->conn->prepare("SELECT price FROM diesel_per_liter ORDER BY date DESC LIMIT 1");
            $stmt->execute();
            $diesel_price = $stmt->fetchColumn() ?? 0;
            return $diesel_price;
        } catch (PDOException $e) {
            return "Database error: $e";
        }
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