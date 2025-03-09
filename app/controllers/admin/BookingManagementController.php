<?php
require_once __DIR__ . "/../../models/admin/BookingManagementModel.php";

class BookingManagementController {
    private $bookingModel;

    public function __construct() {
        $this->bookingModel = new BookingManagementModel();
    }

    public function getAllBookings() {
        $bookings = $this->bookingModel->getAllBookings();
        require_once __DIR__ . "/../../views/admin/booking_management.php";
    }

    public function sendQuote() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $total_cost = $_POST["total_cost"];
            $booking_id = $_POST["booking_id"];
        
            $result = $this->bookingModel->sendQuote($booking_id, $total_cost);
        
            if (!$result) $_SESSION["send_quote_message"] = "Sending quote failed: {$result}";
        
            $_SESSION["send_quote_message"] = "Sending quote successfully!";
            header("Location: /admin/bookings");
            exit();
        }
    } 

}
?>