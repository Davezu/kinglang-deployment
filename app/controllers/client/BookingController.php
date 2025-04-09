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
        $stops = $input["stops"] ?? [];
    
        if (count($stops) < 2) {
            echo json_encode(["error" => "At least two stops are required"]);
            return;
        }
    
        // Prepare origin and destination pairs
        $origins = array_slice($stops, 0, -1);
        $destinations = array_slice($stops, 1);
    
        $originStr = implode("|", array_map("urlencode", $origins));
        $destinationStr = implode("|", array_map("urlencode", $destinations));
    
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$originStr&destinations=$destinationStr&key=$apiKey";
    
        $response = file_get_contents($url);
        echo $response;
    }

    public function getCoordinates($address, $apiKey) {
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

    // private $geocodeCache = [];

    // public function getCoordinates($address, $apiKey) {
    //     $address = trim($address);

    //     // Return from cache if available
    //     if (isset($this->geocodeCache[$address])) {
    //         return $this->geocodeCache[$address];
    //     }

    //     $addressEncoded = urlencode($address);
    //     $geoUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=$addressEncoded&key=$apiKey";
    //     $geoResponse = file_get_contents($geoUrl);

    //     if (!$geoResponse) return null;

    //     $geoData = json_decode($geoResponse, true);
    //     if ($geoData["status"] === "OK") {
    //         $coords = $geoData["results"][0]["geometry"]["location"];
    //         $this->geocodeCache[$address] = ["lat" => $coords["lat"], "lng" => $coords["lng"]];
    //         return $this->geocodeCacheNigga[$address];
    //     }   

    //     return null;
    // }

    // public function processCoordinates() {
    //     header("Access-Control-Allow-Origin: *");
    //     header("Content-Type: application/json");

    //     $apiKey = "AIzaSyASHotkPROmUL_mheV_L9zXarFIuRAIMRs";
    //     $input = json_decode(file_get_contents("php://input"), true);

    //     if (empty($input["pickupPoint"]) || empty($input["destination"])) {
    //         echo json_encode(["error" => "Input is required"]);
    //         return;
    //     }

    //     $pickup_point = $this->getCoordinates($input["pickupPoint"], $apiKey);
    //     $destination = $this->getCoordinates($input["destination"], $apiKey);

    //     $stops = [];
    //     if (!empty($input["stops"]) && is_array($input["stops"])) {
    //         foreach ($input["stops"] as $stop) {
    //             $coordinates = $this->getCoordinates($stop, $apiKey);
    //             if ($coordinates) {
    //                 $stops[] = $coordinates;
    //             }
    //         }
    //     }

    //     if ($pickup_point && $destination) {
    //         echo json_encode([
    //             "pickup_point" => $pickup_point,
    //             "destination" => $destination,
    //             "stops" => $stops
    //         ]);
    //     } else {
    //         echo json_encode(["error" => "Unable to get coordinates"]);
    //     }
    // }


    public function requestBooking() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents("php://input"), true);
            $date_of_tour = $data["dateOfTour"];
            $destination = $data["destination"];
            $stops = $data["stops"] ?? [];
            $pickup_point = $data["pickupPoint"];
            $number_of_buses = (int) $data["numberOfBuses"];
            $number_of_days = (int) $data["numberOfDays"];
            $user_id = $_SESSION["user_id"];
            $total_cost = (float) $data["totalCost"];
            $balance = (float) $data["balance"];

            $result = $this->bookingModel->requestBooking($date_of_tour, $destination, $pickup_point, $number_of_days, $number_of_buses, $user_id, $stops, $total_cost, $balance);
            
            header("Content-Type: application/json");

            if ($result === "success") {
                echo json_encode(["success" => true, "message" => "Booking request sent successfully!"]);
            } else {
                echo json_encode(["success" => false, "message" => $result]);
            }
        }
    }

    public function getDieselPrice() {
        return $this->bookingModel->getDieselPrice();
    }

    public function getTotalCost() {
        header("Content-Type: application/json");

        $input = json_decode(file_get_contents("php://input"), true);
        $number_of_buses = (int) $input["numberOfBuses"] ?? 0;
        $number_of_days = (int) $input["numberOfDays"] ?? 0;
        $distance = (float) $input["distance"] ?? 0;
        $diesel_price = (float) $this->getDieselPrice() ?? 0;

        if ($number_of_buses <= 0 || $number_of_days <= 0 || $distance <= 0 || $diesel_price <= 0) {
            echo json_encode(["success" => false, "message" => "Invalid input values."]);
            return;
        }

        $total_cost = $number_of_buses * $number_of_days * $distance * $diesel_price;
        echo json_encode(["success" => true, "total_cost" => $total_cost]);
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

    // public function findAvailableBuses() { // if the system will let the client select their prefered bus
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