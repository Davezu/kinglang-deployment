<?php
require_once "../../models/admin/BookingManagementModel.php";
require_once "../../../config/database.php";

class BookingManagementController {
    private $booking;

    public function __construct($db) {
        $this->booking = new BookingManagementModel($db);
    }

    public function getAllBookings() {
        return $this->booking->getAllBookings();
    }

    public function sendQuote($booking_id, $total_cost) {
        return $this->booking->sendQuote($booking_id, $total_cost);
    } 

}

$controller = new BookingManagementController($pdo);
$bookings = $controller->getAllBookings();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $total_cost = $_POST["total_cost"];
    $booking_id = $_POST["booking_id"];

    $result = $controller->sendQuote($booking_id, $total_cost);

    if (!$result) $_SESSION["send_quote_message"] = "Sending quote failed: {$result}";

    $_SESSION["send_quote_message"] = "Sending quote successfully!";
    header("Location: ../../views/admin/booking_management.php");
    exit();
}
?>