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
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit_booking"])) {
            $date_of_tour = trim($_POST["date_of_tour"]);
            $destination = trim($_POST["destination"]);
            $pickup_point = trim($_POST["pickup_point"]);
            $number_of_days = trim($_POST["number_of_days"]);
            $number_of_buses = trim($_POST["number_of_buses"]);
            $bus_id = $_POST["bus_id"];
            $end_of_tour = date("Y-m-d", strtotime($date_of_tour . " + $number_of_days days"));
        
            if (!empty($date_of_tour) && !empty($destination) && !empty($pickup_point) && !empty($number_of_days) && !empty($number_of_buses)) {
                $client_id = $this->bookingModel->getClientID($_SESSION["user_id"]);
                $result = $this->bookingModel->requestBooking($date_of_tour, $end_of_tour, $destination, $pickup_point, $number_of_days, $number_of_buses, $client_id, $bus_id);
        
                if ($result) {
                    $_SESSION["booking_message"] = "Request booking successfully!";
                    header("Location: /home/book");
                    exit();
                } else {
                    $_SESSION["booking_message"] = "Failed to add booking.";
                    header("Location: /home/book");
                    exit();
                }
            } else {
                $_SESSION["booking_message"] = "All fields are required.";
                header("Location: /home/book");
                exit();
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

    public function getAllBookings($client_id, $status) {
        $bookings = $this->bookingModel->getAllBookings($client_id, $status);
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