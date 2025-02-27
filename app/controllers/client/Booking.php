<?php
require_once '../../../config/database.php';
require_once '../../models/client/Booking.php';

class BookingController {
    private $bookingModel;

    public function __construct($db) {
        $this->bookingModel = new Booking($db);
    }

    public function addBooking($date_of_tour, $end_of_tour, $destination, $pickup_point, $number_of_days, $number_of_buses) {
        return $this->bookingModel->createBooking($date_of_tour, $end_of_tour, $destination, $pickup_point, $number_of_days, $number_of_buses);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_booking"])) {
    $date_of_tour = trim($_POST["date_of_tour"]);
    $destination = trim($_POST["destination"]);
    $pickup_point = trim($_POST["pickup_point"]);
    $number_of_days = trim($_POST["number_of_days"]);
    $number_of_buses = trim($_POST["number_of_buses"]);
    $end_of_tour = date("Y-m-d", strtotime($date_of_tour . " + $number_of_days days"));

    if (!empty($date_of_tour) && !empty($destination) && !empty($pickup_point) && !empty($number_of_days) && !empty($number_of_buses)) {
        $controller = new BookingController($pdo);
        $result = $controller->addBooking($date_of_tour, $end_of_tour, $destination, $pickup_point, $number_of_days, $number_of_buses);

        if ($result) {
            echo "Booking successfully added!";
        } else {
            echo "Failed to add booking.";
        }
    } else {
        echo "All fields are required.";
    }
} 
?>