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

        // Check if we have a cached result
        $cacheFile = __DIR__ . "/../../cache/address_" . md5($address) . ".json";
        $cacheExpiry = 60 * 60 * 24; // 24 hours in seconds
        
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheExpiry)) {
            // Return cached result
            echo file_get_contents($cacheFile);
            return;
        }

        // Modified URL to include more location types and session token for better results
        // Removed the types=address restriction to get all possible location types
        // Added sessiontoken parameter for better results
        $sessionToken = md5(uniqid(rand(), true));
        $url = "https://maps.googleapis.com/maps/api/place/autocomplete/json?input=$address&key=$apiKey&components=country:$country&sessiontoken=$sessionToken";

        // Use cURL instead of file_get_contents for better performance
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 second timeout
        $response = curl_exec($ch);
        curl_close($ch);

        // Cache the result
        if (!is_dir(__DIR__ . "/../../cache")) {
            mkdir(__DIR__ . "/../../cache", 0755, true);
        }
        file_put_contents($cacheFile, $response);

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
    
        // Prepare all legs of the trip
        $origins = array_slice($stops, 0, -1);
        $destinations = array_slice($stops, 1);

        // Add the final leg to return to origin (round trip)
        $origins[] = end($stops);   // last stop
        $destinations[] = $stops[0]; // back to origin

        // Create URL-safe strings
        $originStr = implode("|", array_map("urlencode", $origins));
        $destinationStr = implode("|", array_map("urlencode", $destinations));
    
        // Check if we have a cached result
        $cacheKey = md5($originStr . $destinationStr);
        $cacheFile = __DIR__ . "/../../cache/distance_" . $cacheKey . ".json";
        $cacheExpiry = 60 * 60 * 24; // 24 hours in seconds
        
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheExpiry)) {
            // Return cached result
            echo file_get_contents($cacheFile);
            return;
        }
    
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=$originStr&destinations=$destinationStr&key=$apiKey";
    
        // Use cURL instead of file_get_contents for better performance
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 second timeout
        $response = curl_exec($ch);
        curl_close($ch);
        
        // Cache the result
        if (!is_dir(__DIR__ . "/../../cache")) {
            mkdir(__DIR__ . "/../../cache", 0755, true);
        }
        file_put_contents($cacheFile, $response);
        
        echo $response;
    }

    public function getCoordinates($address, $apiKey) {
        // Check if we have a cached result
        $cacheKey = md5($address);
        $cacheFile = __DIR__ . "/../../cache/coordinates_" . $cacheKey . ".json";
        $cacheExpiry = 60 * 60 * 24; // 24 hours in seconds
        
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheExpiry)) {
            // Return cached result
            $cachedData = json_decode(file_get_contents($cacheFile), true);
            return $cachedData;
        }
        
        $address = urlencode($address);
        $geoUrl = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=$apiKey";

        // Use cURL instead of file_get_contents for better performance
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $geoUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5); // 5 second timeout
        $geoResponse = curl_exec($ch);
        curl_close($ch);
        
        $geoData = json_decode($geoResponse, true);

        if ($geoData["status"] === "OK") {
            $latitude = $geoData["results"][0]["geometry"]["location"]["lat"];
            $longitude = $geoData["results"][0]["geometry"]["location"]["lng"];
            $result = ["lat" => $latitude, "lng" => $longitude];
            
            // Cache the result
            if (!is_dir(__DIR__ . "/../../cache")) {
                mkdir(__DIR__ . "/../../cache", 0755, true);
            }
            file_put_contents($cacheFile, json_encode($result));
            
            return $result;
        } 
        return null;
    }

    public function processCoordinates() {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json");

        $apiKey = "AIzaSyASHotkPROmUL_mheV_L9zXarFIuRAIMRs";

        $input = json_decode(file_get_contents("php://input"), true);

        if (empty($input["pickupPoint"]) || empty($input["destination"])) {
            echo json_encode(["error" => "Both pickup and destination points are required"]);
            return;
        }

        // Check if we have a cached result for the entire route
        $cacheKey = md5($input["pickupPoint"] . $input["destination"] . json_encode($input["stops"] ?? []));
        $cacheFile = __DIR__ . "/../../cache/route_" . $cacheKey . ".json";
        $cacheExpiry = 60 * 60 * 24; // 24 hours in seconds
        
        if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheExpiry)) {
            // Return cached result
            echo file_get_contents($cacheFile);
            return;
        }

        // Get coordinates for pickup point
        $pickup_point = $this->getCoordinates($input["pickupPoint"], $apiKey);
        if (!$pickup_point) {
            echo json_encode(["error" => "Could not find coordinates for pickup point: " . $input["pickupPoint"]]);
            return;
        }

        // Get coordinates for destination
        $destination = $this->getCoordinates($input["destination"], $apiKey);
        if (!$destination) {
            echo json_encode(["error" => "Could not find coordinates for destination: " . $input["destination"]]);
            return;
        }

        $stops = [];
        $invalidStops = [];

        if (!empty($input["stops"])) {
            foreach ($input["stops"] as $index => $stop) {
                $coordinates = $this->getCoordinates($stop, $apiKey);
                if ($coordinates) {
                    $stops[] = $coordinates;
                } else {
                    $invalidStops[] = $stop;
                }
            }
        }

        // If there are any invalid stops, return an error
        if (!empty($invalidStops)) {
            echo json_encode([
                "error" => "Could not find coordinates for the following stops: " . implode(", ", $invalidStops)
            ]);
            return;
        }

        if ($pickup_point && $destination) {
            $result = ["pickup_point" => $pickup_point, "destination" => $destination, "stops" => $stops];
            
            // Cache the result
            if (!is_dir(__DIR__ . "/../../cache")) {
                mkdir(__DIR__ . "/../../cache", 0755, true);
            }
            file_put_contents($cacheFile, json_encode($result));
            
            echo json_encode($result);
        } else {
            echo json_encode(["error" => "Unable to get coordinates for the specified locations"]);
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
            $trip_distances = $data["tripDistances"];
            $addresses = $data["addresses"];
            $is_rebooking = $data["isRebooking"];
            $rebooking_id = $data["rebookingId"]; 

            $result = $this->bookingModel->requestBooking($date_of_tour, $destination, $pickup_point, $number_of_days, $number_of_buses, $user_id, $stops, $total_cost, $balance, $trip_distances, $addresses, $is_rebooking, $rebooking_id);
            
            header("Content-Type: application/json");

            echo json_encode([
                "success" => $result["success"], 
                "message" => $result["message"]
            ]);
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
        $page = isset($data["page"]) ? (int)$data["page"] : 1;
        $limit = isset($data["limit"]) ? (int)$data["limit"] : 10;

        $user_id = $_SESSION["user_id"];
        $result = $this->bookingModel->getAllBookings($user_id, $status, $column, $order, $page, $limit);

        header("Content-Type: application/json");

        if (is_array($result)) {
            echo json_encode([
                "success" => true, 
                "bookings" => $result["bookings"],
                "pagination" => [
                    "total_records" => $result["total_records"],
                    "total_pages" => $result["total_pages"],
                    "current_page" => $result["current_page"]
                ]
            ]);
        } else {
            echo json_encode(["success" => false, "message" => $result]);
        }
    }

    public function getBooking() {
        header("Content-Type: application/json");

        $data = json_decode(file_get_contents("php://input"), true);

        $booking_id = $data["bookingId"];
        $user_id = $_SESSION["user_id"];

        $booking = $this->bookingModel->getBooking($booking_id, $user_id);
        $stops = $this->bookingModel->getBookingStops($booking_id);
        $distances = $this->bookingModel->getTripDistances($booking_id);
        $diesel = $this->bookingModel->getDieselPrice();

        if ($booking) {
            echo json_encode(["success" => true, "booking" => $booking, "stops" => $stops, "distances" =>  $distances, "diesel" => $diesel]);
        } else {
            echo json_encode(["success" => false, "message" => $booking]);
        }
    }

    public function showBookingRequestTable() {
        require_once __DIR__ . "/../../views/client/booking_requests.php";
    }
    
    public function showBookingDetail() {
        require_once __DIR__ . "/../../views/client/booking_request.php";
    }

    public function cancelBooking() {
        header("Content-Type: application/json");

        $data = json_decode(file_get_contents("php://input"), true);

        $booking_id = $data["bookingId"];
        $user_id = $_SESSION["user_id"];
        $reason = $data["reason"];
        $amount_paid = 0;

        if ($this->bookingModel->isClientPaid($booking_id)) {
            $amount_paid = $this->bookingModel->getAmountPaid($booking_id, $user_id);
            $this->bookingModel->cancelPayment($booking_id, $user_id);
        }

        $amount_refunded = $amount_paid * 0.80;

        $result = $this->bookingModel->cancelBooking($reason, $booking_id, $user_id, $amount_refunded);

        echo json_encode([
            "success" => $result["success"], 
            "message" => $result["success"] 
                ? "Booking Canceled Successfully." 
                : $result["message"]
        ]);

    }

    public function updatePastBookings() {
        return $this->bookingModel->updatePastBookings();
    }

    public function addPayment() {
        header("Content-Type: application/json");
        
        // Check if it's a regular form submission or JSON
        if (!empty($_POST)) {
            $booking_id = $_POST["booking_id"];
            $client_id = $_POST["user_id"];
            $amount = $_POST["amount"];
            $payment_method = $_POST["payment_method"];
        } else {
            // Fallback to JSON input if no POST data
            $data = json_decode(file_get_contents("php://input"), true);
            $booking_id = $data["bookingId"] ?? null;
            $client_id = $data["userId"] ?? null;
            $amount = $data["amount"] ?? null;
            $payment_method = $data["paymentMethod"] ?? null;
        }
        
        // Validate required data
        if (!$booking_id || !$client_id || !$amount || !$payment_method) {
            echo json_encode([
                "success" => false,
                "message" => "Missing required payment information"
            ]);
            return;
        }
        
        // Handle proof of payment upload
        $proof_of_payment = null;
        if (isset($_FILES["proof_of_payment"]) && $_FILES["proof_of_payment"]["error"] === UPLOAD_ERR_OK) {
            $upload_dir = __DIR__ . "/../../uploads/payments/";
            
            // Create directory if it doesn't exist
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            $file_extension = pathinfo($_FILES["proof_of_payment"]["name"], PATHINFO_EXTENSION);
            $file_name = "payment_" . $booking_id . "_" . time() . "." . $file_extension;
            $target_file = $upload_dir . $file_name;
            
            // Check file type
            $allowed_types = ["jpg", "jpeg", "png", "pdf"];
            if (!in_array(strtolower($file_extension), $allowed_types)) {
                echo json_encode([
                    "success" => false,
                    "message" => "Only JPG, PNG, and PDF files are allowed."
                ]);
                return;
            }
            
            // Move uploaded file
            if (move_uploaded_file($_FILES["proof_of_payment"]["tmp_name"], $target_file)) {
                $proof_of_payment = $file_name;
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Failed to upload payment proof."
                ]);
                return;
            }
        }
    
        $result = $this->bookingModel->addPayment($booking_id, $client_id, $amount, $payment_method, $proof_of_payment);
    
        if ($result === true) {
            echo json_encode([
                "success" => true,
                "message" => "Payment submitted successfully!"
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => is_string($result) ? $result : "Payment submission failed. Please try again."
            ]);
        }
    }
        
}
?>