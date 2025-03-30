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

    public function getAddress() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");

        $apiKey = "AIzaSyASHotkPROmUL_mheV_L9zXarFIuRAIMRs";

        $input = json_decode(file_get_contents("php://input"), true);

        if (empty($input["address"])) {
            echo json_encode(["error" => "Input is required"]);
            return;
        }

        $address = $input["address"];
        $country = "PH"; // Philippines

        // Define a bounding box that covers Luzon (Southwest to Northeast)
        $sw_lat = "12.0";  // Southwest latitude (near Mindoro)
        $sw_lng = "119.0"; // Southwest longitude
        $ne_lat = "19.0";  // Northeast latitude (near Batanes)
        $ne_lng = "123.0"; // Northeast longitude (Cagayan Valley)

        $url = "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=$address&key=$apiKey&types=address&components=country:$country&locationbias=rectangle:$sw_lat,$sw_lng|$ne_lat,$ne_lng";

        // Fetch data from Google API
        $response = file_get_contents($url);
        echo $response;
    }

    public function requestBooking() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents("php://input"), true);
            $date_of_tour = $data["dateOfTour"];
            $destination = $data["destination"];
            $pickup_point = $data["pickupPoint"];
            $number_of_buses = (int) $data["numberOfBuses"];
            $number_of_days = (int) $data["numberOfDays"];
            $user_id = $_SESSION["user_id"];

            $result = $this->bookingModel->requestBooking($date_of_tour, $destination, $pickup_point, $number_of_days, $number_of_buses, $user_id);
            
            header("Content-Type: application/json");

            if ($result === "success") {
                echo json_encode(["success" => true, "message" => "Booking request sent successfully!"]);
            } else {
                echo json_encode(["success" => false, "message" => $result]);
            }
        }
    }

    public function requestReschedBooking() {
        $data = json_decode(file_get_contents("php://input"), true);
        $number_of_days = (int) $data["numberOfDays"];
        $number_of_buses = (int) $data["numberOfBuses"];
        $date_of_tour = $data["dateOfTour"];
        $booking_id = (int) $data["bookingId"];
        $user_id = (int) $_SESSION["user_id"];

        $result = $this->bookingModel->requestReschedBooking($number_of_days, $number_of_buses, $date_of_tour, $booking_id, $user_id);

        header("Content-Type: application/json");

        if ($result === "success") {
            echo json_encode(["success" => true, "message" => "Booking reschedule request sent successfully."]);
        } elseif ($result === "rescheduled") {
            echo json_encode(["success" => true, "message" => "Booking rescheduled successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => $result]);
        }
    }

    public function reschedBooking() {

        $result = $this->authModel->reschedBooking($number_of_days, $date_of_tour);

        if ($result === "success") {
            echo json_encode(["success" => true, "message" => "Booking reschedule request sent successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => $result]);
        }
    }

    // public function findAvailableBuses() { if the system will let the client select their prefered bus
    //     if ($_SERVER["REQUEST_METHOD"] === "POST") {
    //         $data = json_decode(file_get_contents("php://input"), true);
    //         $date_of_tour = $data["date_of_tour"];
    //         $number_of_days = $data["number_of_days"];     

    //         $buses = $this->bookingModel->findAvailableBuses($date_of_tour, $number_of_days);

    //         header("Content-Type: application/json");

    //         if (!empty($buses)) {
    //             echo json_encode(['success' => true, 'buses' => $buses]);
    //         } else {
    //             echo json_encode(['success' => false]);
    //         }
    //     }
    // }

    // public function isClientInfoExists($user_id) {
    //     $result = $this->bookingModel->checkClientInfo($user_id);
    
    //     if ($result) {
    //         header("Location: /home/book");
    //         exit();
    //     } else {
    //         header("Location: /home/contact");
    //         exit();
    //     }   
    // }

    // public function getClientID($user_id) {
    //     return $this->bookingModel->getClientID($user_id);
    // }

    public function getAllBookings() {
        $data = json_decode(file_get_contents("php://input"), true);
        $status = $data["status"];
        $column = $data["column"];
        $order = $data["order"];

        $user_id = $_SESSION["user_id"];
        $bookings = $this->bookingModel->getAllBookings($user_id, $status, $column, $order);

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

    // public function clientInfoForm() {
    //     require_once __DIR__ . "/../../views/client/client_info_form.php";
    // }

    // public function addClient() {
    //     if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["client_info"])) {
    //         $first_name = trim($_POST["first_name"]);
    //         $last_name = trim($_POST["last_name"]);
    //         $address = trim($_POST["address"]);
    //         $contact_number = trim($_POST["contact_number"]);
    //         $company_name = trim($_POST["company_name"]) ? trim($_POST["company_name"]) : "none";
        
    //         if (empty($first_name) || empty($last_name) || empty($address) || empty($contact_number)) {
    //             echo "Incomplete information";
    //             exit();
    //         }
        
    //         $message = $this->bookingModel->addClient($first_name, $last_name, $address, $contact_number, $company_name);
        
    //         if ($message === "Client info added successfully!") {
    //             header("Location: /home/book");
    //             exit();
    //         } else {
    //             echo "<script>alert('$message')</script>";
    //         }
    //     }
    // }

    public function addPayment() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $booking_id = $_POST["booking_id"];
            $client_id = $_POST["user_id"];
            $amount = $_POST["amount"];
            $payment_method = $_POST["payment_method"];
        
            $result = $this->bookingModel->addPayment($booking_id, $client_id, $amount, $payment_method);
        
            if ($result) {
                header("Location: /home/booking-requests");
                exit();
            } else {
                echo "Adding payment failed";
            }
        }
    }
        
}
?>