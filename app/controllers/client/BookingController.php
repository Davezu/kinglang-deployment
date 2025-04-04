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

        $address = urlencode($input["address"]);
        $country = "PH"; // Philippines

        $url = "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=$address&key=$apiKey&types=address&components=country:$country";

        // Fetch data from Google API
        $response = file_get_contents($url);
        echo $response;
    }

    public function getDistance() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");

        $apiKey = "AIzaSyASHotkPROmUL_mheV_L9zXarFIuRAIMRs";

        $input = json_decode(file_get_contents("php://input"), true);

        if (empty($input["origin"] || empty($input["destination"]))) {
            echo json_encode(["error" => "Input is required"]);
            return;
        }

        $origin = urlencode($input["origin"]);
        $destination = urlencode($input["destination"]);

        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$origin&destinations=$destination&key=$apiKey";

        $response = file_get_contents($url);
        echo $response;
    }

    public function getCoordinates($address, $apiKey) {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");

        $address = urlencode($address);
        $geoUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=$apiKey";

        $geoResponse = file_get_contents($geoUrl);
        $geoData = json_decode($geoResponse, true);

        if ($geoData["status"] === "OK") {
            $latitude = $geoData["results"][0]["geometry"]["location"]["lat"];
            $longitude = $geoData["results"][0]["geometry"]["location"]["lng"];
            return ["lat" => $latitude, "lng" => $longitude];
        } 
        return null;
    }

    public function processCoordinates() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");

        $apiKey = "AIzaSyASHotkPROmUL_mheV_L9zXarFIuRAIMRs";

        $input = json_decode(file_get_contents("php://input"), true);

        if (empty($input["pickupPoint"]) || empty($input["destination"])) {
            echo json_encode(["error" => "Input is required"]);
            return;
        }

        $pickup_point = $this->getCoordinates($input["pickupPoint"], $apiKey);
        $destination = $this->getCoordinates($input["destination"], $apiKey);

        $stops = [];

        if (!empty($input["stops"])) {
            foreach ($input["stops"] as $stop) {
                $coordinates = $this->getCoordinates($stop, $apiKey);
                if ($coordinates) {
                    $stops[] = $coordinates;
                }
            }
        }

        if ($pickup_point && $destination) {
            echo json_encode(["pickup_point" => $pickup_point, "destination" => $destination, "stops" => $stops]);
        } else {
            echo json_encode(["error" => "Unable to get coordinates"]);
        }
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

    public function getTotalCost($distance, $diesel_price) {

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