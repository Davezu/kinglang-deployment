<?php
require_once __DIR__ . '/../../models/client/BookingModel.php';

class BookingController {
    private $bookingModel;

    public function __construct() {
        $this->bookingModel = new Booking();
    }

    public function bookingForm() {
        require_once __DIR__ . "/../../views/client/booking.php";
    }

    public function requestBooking() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents("php://input"), true);
            $date_of_tour = $data["dateOfTour"];
            $destination = $data["destination"];
            $pickup_point = $data["pickupPoint"];
            $number_of_buses = $data["numberOfBuses"];
            $number_of_days = $data["numberOfDays"];
            $bus_ids = $data["busIds"];
        
            $client_id = $this->bookingModel->getClientID($_SESSION["user_id"]);
            $result = $this->bookingModel->requestBooking($date_of_tour, $destination, $pickup_point, $number_of_days, $number_of_buses, $client_id, $bus_ids);
            
            header("Content-Type: application/json");

            if ($result === "success") {
                // $_SESSION["booking_message"] = "Booking request sent successfully!";
                echo json_encode(["success" => true, "message" => "Booking request sent successfully!"]);
            } else {
                // $_SESSION["booking_message"] = "Failed to add booking.";
                echo json_encode(["success" => false, "message" => $result]);
            }
        }
    }

    public function findAvailableBuses() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents("php://input"), true);
            $date_of_tour = $data["date_of_tour"];
            $number_of_days = $data["number_of_days"];     

            $buses = $this->bookingModel->findAvailableBuses($date_of_tour, $number_of_days);

            header("Content-Type: application/json");

            if (!empty($buses)) {
                echo json_encode(['success' => true, 'buses' => $buses]);
            } else {
                echo json_encode(['success' => false]);
            }
        }
    }

    public function isClientInfoExists($user_id) {
        $result = $this->bookingModel->checkClientInfo($user_id);
    
        if ($result) {
            header("Location: /home/book");
            exit();
        } else {
            header("Location: /home/contact");
            exit();
        }   
    }

    public function getClientID($user_id) {
        return $this->bookingModel->getClientID($user_id);
    }

    public function getAllBookings() {
        $data = json_decode(file_get_contents("php://input"), true);
        $status = $data["status"];

        $client_id = $this->bookingModel->getClientID($_SESSION["user_id"]);
        $bookings = $this->bookingModel->getAllBookings($client_id, $status);

        header("Content-Type: application/json");

        if (is_array($bookings)) {
            echo json_encode(["success" => true, "bookings" => $bookings]);
        } else {
            echo json_encode(["success" => false, "message" => $bookings]);
        }
    }

    public function showBookingRequestTable() {
        require_once __DIR__ . "/../../views/client/booking_requests.php";
    }

    public function updatePastBookings() {
        return $this->bookingModel->updatePastBookings();
    }

    public function clientInfoForm() {
        require_once __DIR__ . "/../../views/client/client_info_form.php";
    }

    public function addClient() {
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["client_info"])) {
            $first_name = trim($_POST["first_name"]);
            $last_name = trim($_POST["last_name"]);
            $address = trim($_POST["address"]);
            $contact_number = trim($_POST["contact_number"]);
            $company_name = trim($_POST["company_name"]) ? trim($_POST["company_name"]) : "none";
        
            if (empty($first_name) || empty($last_name) || empty($address) || empty($contact_number)) {
                echo "Incomplete information";
                exit();
            }
        
            $message = $this->bookingModel->addClient($first_name, $last_name, $address, $contact_number, $company_name);
        
            if ($message === "Client info added successfully!") {
                header("Location: /home/book");
                exit();
            } else {
                echo "<script>alert('$message')</script>";
            }
        }
    }

    public function addPayment() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $booking_id = $_POST["booking_id"];
            $client_id = $_POST["client_id"];
            $amount = $_POST["amount"];
            $payment_method = $_POST["payment_method"];
        
            $result = $this->bookingModel->addPayment($booking_id, $client_id, $amount, $payment_method);
        
            if ($result) {
                header("Location: /home/bookings/" . $_SESSION["user_id"]);
                exit();
            } else {
                echo "Adding payment failed";
            }
        }
    }
        
}
?>