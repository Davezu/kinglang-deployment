<?php

class BookingManagementModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllBookings() {
        $stmt = $this->conn->prepare("
            SELECT b.booking_id, CONCAT(c.first_name, ' ', c.last_name) AS client_name, c.contact_number, b.destination, b.pickup_point, b.date_of_tour, b.end_of_tour, b.number_of_days, b.number_of_buses, b.status
            FROM bookings b
            JOIN clients c ON b.client_id = c.client_id
            ORDER BY b.date_of_tour DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>