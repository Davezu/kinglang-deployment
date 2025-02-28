<?php
class Booking {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }

    public function checkClientInfo($user_id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE user_id = :user_id AND client_id IS NOT NULL");
            $stmt->execute([":user_id" => $user_id]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                return true;
            } else {
                return false;
            };
        } catch (PDOException $e) {
            return "Database error";
        }
    }

    public function createBooking($date_of_tour, $end_of_tour, $destination, $pickup_point, $number_of_days, $number_of_buses) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO bookings (date_of_tour, end_of_tour, destination, pickup_point, number_of_days, number_of_buses) VALUES (:date_of_tour, :end_of_tour, :destination, :pickup_point, :number_of_days, :number_of_buses)");
            return $stmt->execute([
                ":date_of_tour" => $date_of_tour,
                ":end_of_tour" => $end_of_tour,
                ":destination" => $destination,
                ":pickup_point" => $pickup_point,
                ":number_of_days" => $number_of_days,
                ":number_of_buses" => $number_of_buses
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }
}


?>