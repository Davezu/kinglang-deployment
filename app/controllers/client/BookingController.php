<?php
require_once '../../../config/database.php';
require_once '../../models/client/BookingModel.php';
session_start();

class BookingController {
    private $bookingModel;

    public function __construct($db) {
        $this->bookingModel = new Booking($db);
    }

    public function addBooking($date_of_tour, $end_of_tour, $destination, $pickup_point, $number_of_days, $number_of_buses, $client_id) {
        return $this->bookingModel->createBooking($date_of_tour, $end_of_tour, $destination, $pickup_point, $number_of_days, $number_of_buses, $client_id);
    }

    public function checkClientInfo($user_id) {
        return $this->bookingModel->checkClientInfo($user_id);
    }

    public function getClientID($user_id) {
        return $this->bookingModel->getClientID($user_id);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $user_id = $_GET["user_id"];

    $controller = new BookingController($pdo);
    $result = $controller->checkClientInfo($user_id);

    if ($result) {
        header("Location: ../../views/client/booking.php");
        exit();
    } else {
        header("Location: ../../views/client/clientInfo.php");
        exit();
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

        $client_id = $controller->getClientID($_SESSION["user_id"]);
        $result = $controller->addBooking($date_of_tour, $end_of_tour, $destination, $pickup_point, $number_of_days, $number_of_buses, $client_id);

        if ($result) {
            $_SESSION["booking_message"] = "Request booking successfully!";
            header("Location: ../../views/client/booking.php");
            exit();
        } else {
            $_SESSION["booking_message"] = "Failed to add booking.";
            header("Location: ../../views/client/booking.php");
            exit();
        }
    } else {
        echo "All fields are required.";
    }
} 
?>