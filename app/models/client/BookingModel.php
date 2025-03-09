<?php
require_once __DIR__ . "/../../../config/database.php";

class Booking {
    private $conn;
    
    public function __construct() {
        global $pdo;
        $this->conn = $pdo;
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

    public function getClientID($user_id) {
        try {
            $stmt = $this->conn->prepare("SELECT client_id FROM users WHERE user_id = :user_id");
            $stmt->execute([":user_id" => $user_id]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return "Database error";
        }
    }

    public function requestBooking($date_of_tour, $end_of_tour, $destination, $pickup_point, $number_of_days, $number_of_buses, $client_id) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO bookings (date_of_tour, end_of_tour, destination, pickup_point, number_of_days, number_of_buses, client_id) VALUES (:date_of_tour, :end_of_tour, :destination, :pickup_point, :number_of_days, :number_of_buses, :client_id)");
            return $stmt->execute([
                ":date_of_tour" => $date_of_tour,
                ":end_of_tour" => $end_of_tour,
                ":destination" => $destination,
                ":pickup_point" => $pickup_point,
                ":number_of_days" => $number_of_days,
                ":number_of_buses" => $number_of_buses,
                ":client_id" => $client_id
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function getAllBookings($client_id, $status = "") {
        try {
            if (!empty($status)) {
                $stmt = $this->conn->prepare("SELECT * FROM bookings WHERE client_id = :client_id AND status = :status ORDER BY date_of_tour DESC");
                $stmt->execute([
                    ":client_id" => $client_id,
                    ":status" => $status
                ]);
            } else {
                $stmt = $this->conn->prepare("SELECT * FROM bookings WHERE client_id = :client_id ORDER BY date_of_tour DESC");
                $stmt->execute([ ":client_id" => $client_id ]);
            }

            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $bookings ?: [];
        } catch (PDOException $e) {
            return "Database error: $e";
        }
    }

    public function updatePastBookings() {
        try {
            $current_date = date("Y-m-d");

            $stmt = $this->conn->prepare("UPDATE bookings SET status = 'completed' WHERE end_of_tour < :current_date AND status != 'completed' AND balance = 0");
            $stmt->execute([":current_date" => $current_date]);
            return "Updated successfully!";
        } catch (PDOException $e) {
            return "Error";
        }
    }

    public function addClient($first_name, $last_name, $address, $contact_number, $company_name) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO clients (first_name, last_name, address, contact_number, company_name) VALUES (:first_name, :last_name, :address, :contact_number, :company_name)");
            $result = $stmt->execute([
                ":first_name" => $first_name,
                ":last_name" => $last_name,
                ":address" => $address,
                ":contact_number" => $contact_number,
                ":company_name" => $company_name
            ]);

            if (!$result) return "Inserting client info failed";
    
            $client_id = $this->conn->lastInsertID();   
            $user_id = $_SESSION["user_id"];
            
            $stmt = $this->conn->prepare("UPDATE users SET client_id = :client_id WHERE user_id = :user_id");
            $result = $stmt->execute([
                ":client_id" => $client_id,
                ":user_id" => $user_id
            ]);

            if (!$result) return "Inserting client_id into users failed";
    
            return "Client info added successfully!";
        } catch (PDOException $e) {
            return "Database error";
        }
    }
}


?>