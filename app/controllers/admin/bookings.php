<?php
require_once "../../models/admin/bookings.php";
require_once "../../../config/database.php";

class BookingManagementController {
    private $booking;

    public function __construct($db) {
        $this->booking = new BookingManagementModel($db);
    }

    public function getAllBookings() {
        return $this->booking->getAllBookings();
    }

}

$controller = new BookingManagementController($pdo);
$bookings = $controller->getAllBookings();
?>