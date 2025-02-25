<?php
class Booking {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }

    public function createBooking($date_of_tour, $destination, $pickup_point, $number_of_days, $number_of_buses) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO bookings (date_of_tour, destination, pickup_point, number_of_days, number_of_buses) VALUES (:date_of_tour, :destination, :pickup_point, :number_of_days, :number_of_buses)");
            return $stmt->execute([
                ":date_of_tour" => $date_of_tour,
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