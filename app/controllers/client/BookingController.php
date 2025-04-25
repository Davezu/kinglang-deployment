<?php
require_once __DIR__ . '/../../models/client/BookingModel.php';
require_once __DIR__ . '/../../models/admin/NotificationModel.php';

class BookingController {
    private $bookingModel;
    private $notificationModel;

    public function __construct() {
        $this->bookingModel = new Booking();
        $this->notificationModel = new NotificationModel();
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
        $distance = (float) number_format($input["distance"], 2) ?? 0;
        $diesel_price = (float) $this->getDieselPrice() ?? 0;
        $locations = $input["locations"] ?? [];
        $destination = $input["destination"] ?? "";
        $pickupPoint = $input["pickupPoint"] ?? "";

        if ($number_of_buses <= 0 || $number_of_days <= 0 || $distance <= 0 || $diesel_price <= 0) {
            echo json_encode(["success" => false, "message" => "Invalid input values."]);
            return;
        }

        // Define rates per region
        $regional_rates = [
            'NCR' => 19560, // Metro Manila
            'CAR' => 71040, // Cordillera Administrative Region
            'Region 1' => 117539, // Ilocos Region
            'Region 2' => 71040, // Cagayan Valley
            'Region 3' => 45020, // Central Luzon
            'Region 4A' => 20772, // Calabarzon
        ];

        // Get distances between locations
        $all_locations = [];
        if (!empty($pickupPoint)) $all_locations[] = $pickupPoint;
        if (!empty($locations)) $all_locations = array_merge($all_locations, $locations);
        
        // Determine region for each location
        $location_regions = [];
        $farthest_region = null;
        $farthest_distance = 0;
        $highest_rate = 0;
        
        // First, identify the region of each location
        foreach ($all_locations as $location) {
            $region = $this->determineRegionFromLocations([$location]);
            $location_regions[$location] = [
                'region' => $region,
                'rate' => $regional_rates[$region] ?? $regional_rates['Region 4A']
            ];
        }
        
        // Find the region with the highest rate
        foreach ($location_regions as $location => $info) {
            if ($info['rate'] > $highest_rate) {
                $highest_rate = $info['rate'];
                $farthest_region = $info['region'];
            }
        }
        
        if (!$farthest_region) {
            $farthest_region = 'Region 4A'; // Default to CALABARZON if no region found
        }

        $base_rate = $regional_rates[$farthest_region] ?? $regional_rates['Region 4A'];

        // Calculate base cost using regional rate
        $base_cost = round($base_rate * $number_of_buses * $number_of_days, 2);
        
        // Calculate fuel cost based on distance and diesel price
        $diesel_cost = round($distance * $diesel_price, 2);
        
        // Total cost is base cost plus fuel cost
        $total_cost = round($base_cost + $diesel_cost, 2);

        echo json_encode([
            "success" => true, 
            "total_cost" => $total_cost,
            "base_rate" => $base_rate,
            "base_cost" => $base_cost,
            "diesel_price" => $diesel_price,
            "diesel_cost" => $diesel_cost,
            "region" => $farthest_region,
            "location_regions" => $location_regions // Include this for debugging
        ]);
    }

    /**
     * Determine which region a set of locations belongs to
     * 
     * @param array $locations Array of location strings
     * @return string The determined region code
     */
    private function determineRegionFromLocations($locations) {
        if (empty($locations)) {
            return 'Region 4A'; // Default to CALABARZON if no locations
        }

        // Keywords for each region with more comprehensive listings
        $region_keywords = [
            'NCR' => [
                'metro manila', 'ncr', 'manila', 'quezon city', 'makati', 'pasig', 'taguig', 'mandaluyong',
                'pasay', 'parañaque', 'caloocan', 'marikina', 'muntinlupa', 'las piñas', 'malabon',
                'valenzuela', 'navotas', 'san juan', 'pateros', 'maynila', 'intramuros', 'malate', 'ermita',
                'binondo', 'quiapo', 'santa cruz', 'sampaloc', 'tondo', 'port area', 'paco', 'pandacan',
                'sta. mesa', 'sta. ana', 'san andres', 'san nicolas', 'commonwealth', 'fairview', 'novaliches',
                'cubao', 'ortigas', 'greenhills', 'eastwood', 'bgc', 'bonifacio global city', 'mckinley hill',
                'rockwell', 'poblacion', 'magallanes', 'moa', 'mall of asia', 'alabang'
            ],
            'CAR' => [
                'cordillera', 'car', 'baguio', 'benguet', 'mountain province', 'mt. province', 'ifugao', 'abra',
                'kalinga', 'apayao', 'banaue', 'sagada', 'la trinidad', 'itogon', 'kibungan', 'bakun', 'kabayan',
                'atok', 'tuba', 'tublay', 'bokod', 'buguias', 'mankayan', 'kapangan', 'sablan', 'bontoc',
                'barlig', 'bauko', 'besao', 'natonin', 'paracelis', 'sadanga', 'sagada', 'tadian', 'banaue',
                'aguinaldo', 'asipulo', 'hingyon', 'hungduan', 'kiangan', 'lagawe', 'lamut', 'mayoyao', 'tinoc'
            ],
            'Region 1' => [
                'ilocos region', 'region 1', 'ilocos norte', 'ilocos sur', 'la union', 'pangasinan',
                'laoag', 'vigan', 'san fernando', 'dagupan', 'batac', 'candon', 'alaminos', 'urdaneta',
                'san carlos', 'pagudpud', 'bangui', 'burgos', 'pasuquin', 'bacarra', 'vintar', 'paoay',
                'currimao', 'badoc', 'pinili', 'marcos', 'nueva era', 'sarrat', 'piddig', 'carasi', 'solsona',
                'dingras', 'san nicolas', 'cabugao', 'sinait', 'santa catalina', 'santa lucia', 'santa cruz',
                'san vicente', 'santa', 'narvacan', 'santa maria', 'san esteban', 'santiago', 'bantay',
                'caoayan', 'magsingal', 'santo domingo', 'san ildefonso', 'san juan', 'san vicente', 'aringay',
                'agoo', 'bauang', 'caba', 'santo tomas', 'rosario', 'pugo', 'tubao', 'naguilian', 'bagulin',
                'burgos', 'san gabriel', 'santol', 'sudipen', 'luna', 'bangar', 'balaoan', 'bacnotan',
                'lingayen', 'bolinao', 'san fabian', 'manaoag', 'binmaley', 'calasiao', 'santa barbara',
                'malasiqui', 'bayambang', 'basista', 'bautista', 'alcala', 'santo tomas', 'mangaldan',
                'mangatarem', 'aguilar', 'bugallon', 'labrador', 'infanta', 'mabini', 'burgos', 'dasol',
                'agno', 'bani', 'alaminos', 'sual', 'san manuel', 'binalonan', 'laoac', 'pozorrubio',
                'san jacinto', 'san nicolas', 'tayug', 'natividad', 'san quintin', 'umingan', 'balungao',
                'rosales', 'asingan', 'santa maria', 'villasis', 'anda', 'sison', 'san carlos'
            ],
            'Region 2' => [
                'cagayan valley', 'region 2', 'cagayan', 'isabela', 'nueva vizcaya', 'quirino', 'batanes',
                'tuguegarao', 'ilagan', 'cauayan', 'santiago', 'alaminos', 'alicia', 'angadanan', 'aurora',
                'bambang', 'bayombong', 'cabagan', 'cabarroguis', 'calayan', 'camalaniugan', 'cauayan', 'cordon',
                'diffun', 'dinapigue', 'divilacan', 'dumaran', 'echague', 'enrile', 'gamu', 'gattaran', 'ilagan',
                'jones', 'lal-lo', 'laoag', 'maconacon', 'maddela', 'mallig', 'nagtipunan', 'naguilian',
                'palanan', 'peñablanca', 'quezon', 'quirino', 'ramon', 'reina mercedes', 'roxas', 'san isidro',
                'santiago', 'santo tomas', 'solano', 'tuguegarao', 'tumauini', 'basco', 'ivana', 'mahatao', 'sabtang'
            ],
            'Region 3' => [
                'central luzon', 'region 3', 'bulacan', 'pampanga', 'tarlac', 'zambales', 'nueva ecija',
                'bataan', 'aurora', 'angeles', 'san fernando', 'malolos', 'cabanatuan', 'tarlac city', 'baler',
                'iba', 'balanga', 'olongapo', 'subic', 'clark', 'bacolor', 'guagua', 'lubao', 'san jose del monte',
                'meycauayan', 'bustos', 'baliwag', 'plaridel', 'pulilan', 'calumpit', 'hagonoy', 'obando',
                'san ildefonso', 'san miguel', 'san rafael', 'bocaue', 'marilao', 'sta. maria', 'guiguinto',
                'angat', 'norzagaray', 'dona remedios trinidad', 'candaba', 'arayat', 'mabalacat',
                'concepcion', 'gerona', 'paniqui', 'camiling', 'capas', 'bamban', 'cabanatuan', 'gapan',
                'palayan', 'san jose', 'munoz', 'talavera'
            ],
            'Region 4A' => [
                'calabarzon', 'region 4a', 'cavite', 'laguna', 'batangas', 'rizal', 'quezon', 'lucena',
                'tagaytay', 'calamba', 'santa rosa', 'lipa', 'batangas city', 'antipolo', 'taytay', 'cainta',
                'biñan', 'san pedro', 'cabuyao', 'tanauan', 'bacoor', 'dasmariñas', 'imus', 'general trias',
                'trece martires', 'kawit', 'alfonso', 'amadeo', 'carmona', 'cavite city', 'general mariano alvarez',
                'indang', 'magallanes', 'maragondon', 'mendez', 'naic', 'noveleta', 'rosario', 'silang', 'tanza',
                'ternate', 'alaminos', 'bay', 'cabuyao', 'calauan', 'famy', 'kalayaan', 'liliw', 'los baños',
                'luisiana', 'lumban', 'mabitac', 'magdalena', 'majayjay', 'nagcarlan', 'paete', 'pagsanjan',
                'pakil', 'pangil', 'pila', 'rizal', 'san pablo', 'santa cruz', 'santa maria', 'siniloan',
                'victoria', 'agoncillo', 'alitagtag', 'balete', 'balayan', 'bauan', 'calaca', 'calatagan', 'cuenca',
                'ibaan', 'laurel', 'lemery', 'lian', 'lobo', 'mabini', 'malvar', 'mataas na kahoy', 'nasugbu',
                'padre garcia', 'rosario', 'san jose', 'san juan', 'san luis', 'san nicolas', 'san pascual',
                'santa teresita', 'santo tomas', 'taal', 'talisay', 'taysan', 'tingloy', 'angono', 'baras',
                'binangonan', 'cardona', 'jala-jala', 'morong', 'pililla', 'rodriguez', 'san mateo', 'tanay', 'teresa',
                'agdangan', 'alabat', 'atimonan', 'buenavista', 'burdeos', 'calauag', 'candelaria', 'catanauan',
                'dolores', 'general luna', 'general nakar', 'guinayangan', 'gumaca', 'infanta', 'jomalig', 'lopez',
                'lucban', 'macalelon', 'mauban', 'mulanay', 'padre burgos', 'pagbilao', 'panukulan', 'patnanungan',
                'perez', 'pitogo', 'plaridel', 'polillo', 'quezon', 'real', 'sampaloc', 'san andres', 'san antonio',
                'san francisco', 'san narciso', 'sariaya', 'tagkawayan', 'tiaong', 'unisan'
            ]
        ];

        // Count matches for each region
        $region_matches = [
            'NCR' => 0,
            'CAR' => 0,
            'Region 1' => 0,
            'Region 2' => 0,
            'Region 3' => 0,
            'Region 4A' => 0
        ];

        // Look for region keywords in each location
        foreach ($locations as $location) {
            $location = strtolower($location);
            foreach ($region_keywords as $region => $keywords) {
                foreach ($keywords as $keyword) {
                    if (strpos($location, $keyword) !== false) {
                        $region_matches[$region]++;
                        break; // Found a match for this region in this location
                    }
                }
            }
        }

        // Find the region with the most matches
        $max_matches = 0;
        $matched_region = 'Region 4A'; // Default
        
        foreach ($region_matches as $region => $matches) {
            if ($matches > $max_matches) {
                $max_matches = $matches;
                $matched_region = $region;
            }
        }

        // If no matches were found, try to determine region using more advanced geolocation methods
        if ($max_matches === 0 && !empty($locations[0])) {
            // Log the unmatched location for future improvements
            error_log("Could not determine region for location: " . $locations[0]);
        }

        return $matched_region;
    }

    public function requestBooking() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents("php://input"), true);
            $date_of_tour = $data["dateOfTour"];
            $destination = $data["destination"];
            $stops = $data["stops"] ?? [];
            $pickup_point = $data["pickupPoint"];
            $pickup_time = $data["pickupTime"] ?? null;
            $number_of_buses = (int) $data["numberOfBuses"];
            $number_of_days = (int) $data["numberOfDays"];
            $user_id = $_SESSION["user_id"];
            $total_cost = (float) $data["totalCost"];
            $balance = (float) $data["balance"];
            $trip_distances = $data["tripDistances"];
            $addresses = $data["addresses"];
            $is_rebooking = $data["isRebooking"];
            $rebooking_id = $data["rebookingId"];
            
            // Optional new fields for cost breakdown
            $region = $data["region"] ?? null;

            $base_cost = $data["baseCost"] ?? null;
            $diesel_cost = $data["dieselCost"] ?? null;
            $base_rate = $data["baseRate"] ?? null;
            $diesel_price = $data["dieselPrice"] ?? null;
            $total_distance = $data["totalDistance"] ?? null;

            // If we received region info but didn't calculate it properly, recalculate
            if (!$region) {
                // Get all locations
                $all_locations = [$pickup_point];
                if (!empty($stops)) {
                    $all_locations = array_merge($all_locations, $stops);
                }
                $all_locations[] = $destination;
                
                // Define rates per region
                $regional_rates = [
                    'NCR' => 19560, // Metro Manila
                    'CAR' => 117539, // Cordillera Administrative Region
                    'Region 2' => 71040, // Cagayan Valley
                    'Region 3' => 45020, // Central Luzon
                    'Region 4A' => 20772, // Calabarzon
                ];
                
                // Determine region for each location
                $highest_rate = 0;
                $farthest_region = 'Region 4A'; // Default
                
                foreach ($all_locations as $location) {
                    $location_region = $this->determineRegionFromLocations([$location]);
                    $location_rate = $regional_rates[$location_region] ?? $regional_rates['Region 4A'];
                    
                    if ($location_rate > $highest_rate) {
                        $highest_rate = $location_rate;
                        $farthest_region = $location_region;
                    }
                }
                
                $region = $farthest_region;
            }

            $result = $this->bookingModel->requestBooking(
                $date_of_tour, 
                $destination, 
                $pickup_point, 
                $number_of_days, 
                $number_of_buses, 
                $user_id, 
                $stops, 
                $total_cost, 
                $balance, 
                $trip_distances, 
                $addresses, 
                $is_rebooking, 
                $rebooking_id,
                
                $base_cost, 
                $diesel_cost,
                $base_rate,
                $diesel_price,
                $total_distance,
                $pickup_time
            );
            
            // Create notification for admin if booking was successful
            if (isset($result["success"]) && $result["success"]) {
                // Get the last inserted booking ID
                $booking_id = $this->bookingModel->conn->lastInsertId();
                $user_name = $_SESSION["client_name"];
                $message = "New booking request from {$user_name} to {$destination}";
                $this->notificationModel->addNotification("booking_request", $message, $booking_id);
            }

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
        $search = isset($data["search"]) ? $data["search"] : "";
        $date_filter = isset($data["date_filter"]) ? $data["date_filter"] : null;
        $balance_filter = isset($data["balance_filter"]) ? $data["balance_filter"] : null;

        $user_id = $_SESSION["user_id"];
        $result = $this->bookingModel->getAllBookings($user_id, $status, $column, $order, $page, $limit, $search, $date_filter, $balance_filter);

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

        if ($booking) {
            echo json_encode(["success" => true, "booking" => $booking, "stops" => $stops, "distances" =>  $distances]);
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

        // Get booking details before cancelling to include in notification
        $booking = $this->bookingModel->getBooking($booking_id, $user_id);
        
        $result = $this->bookingModel->cancelBooking($reason, $booking_id, $user_id, $amount_refunded);

        if ($result["success"] && $booking) {
            // Create notification for admin about booking cancellation
            $clientName = $_SESSION["client_name"];
            $destination = $booking["destination"] ?? "Unknown destination";
            $message = "Booking #{$booking_id} to {$destination} cancelled by {$clientName}. Reason: {$reason}";
            $this->notificationModel->addNotification("booking_cancelled_by_client", $message, $booking_id);
        }

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
            // Create notification for admin about new payment
            // Get booking details to include in notification
            $booking = $this->bookingModel->getBooking($booking_id, $client_id);
            if ($booking) {
                $clientName = $_SESSION["user_first_name"] . " " . $_SESSION["user_last_name"];
                $formattedAmount = number_format($amount, 2);
                $message = "New payment of PHP {$formattedAmount} submitted by {$clientName} for booking #{$booking_id}";
                $this->notificationModel->addNotification("payment_submitted", $message, $booking_id);
            }
            
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
        
    public function getBookingStatistics() {
        header("Content-Type: application/json");
        
        $user_id = $_SESSION["user_id"];
        
        // Get total bookings count
        $total = $this->bookingModel->getBookingsCount($user_id, "all");
        
        // Get confirmed bookings count
        $confirmed = $this->bookingModel->getBookingsCount($user_id, "confirmed");
        
        // Get pending bookings count
        $pending = $this->bookingModel->getBookingsCount($user_id, "pending");
        
        // Get upcoming tours count (confirmed bookings with future dates)
        $upcoming = $this->bookingModel->getUpcomingToursCount($user_id);
        
        echo json_encode([
            "success" => true,
            "statistics" => [
                "total" => $total,
                "confirmed" => $confirmed,
                "pending" => $pending,
                "upcoming" => $upcoming
            ]
        ]);
    }
    
    public function getCalendarEvents() {
        header("Content-Type: application/json");
        
        $data = json_decode(file_get_contents("php://input"), true);
        $start = isset($data["start"]) ? $data["start"] : null;
        $end = isset($data["end"]) ? $data["end"] : null;
        
        if (!$start || !$end) {
            echo json_encode([
                "success" => false,
                "message" => "Start and end dates are required"
            ]);
            return;
        }
        
        $user_id = $_SESSION["user_id"];
        $events = $this->bookingModel->getBookingsForCalendar($user_id, $start, $end);
        
        echo json_encode([
            "success" => true,
            "events" => $events
        ]);
    }
    
    public function getBookingDetails() {
        header("Content-Type: application/json");
        
        $data = json_decode(file_get_contents("php://input"), true);
        $booking_id = isset($data["bookingId"]) ? $data["bookingId"] : null;
        
        if (!$booking_id) {
            echo json_encode([
                "success" => false,
                "message" => "Booking ID is required"
            ]);
            return;
        }

        $user_id = $_SESSION["user_id"];    
        $payments = $this->bookingModel->getPaymentHistory($booking_id);
        $booking = $this->bookingModel->getBooking($booking_id, $user_id);
        
        if ($booking) {
            echo json_encode([
                "success" => true,
                "booking" => $booking,
                "payments" => $payments
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Booking not found or access denied"
            ]);
        }
    }
    
    public function exportBookings() {
        header("Content-Type: application/json");
        
        $data = json_decode(file_get_contents("php://input"), true);
        $format = isset($data["format"]) ? $data["format"] : "pdf";
        $status = isset($data["status"]) ? $data["status"] : "all";
        $search = isset($data["search"]) ? $data["search"] : "";
        $date_filter = isset($data["date_filter"]) ? $data["date_filter"] : null;
        $balance_filter = isset($data["balance_filter"]) ? $data["balance_filter"] : null;
        
        $user_id = $_SESSION["user_id"];
        
        // Get bookings data
        $result = $this->bookingModel->getAllBookings($user_id, $status, "date_of_tour", "asc", 1, 1000, $search, $date_filter, $balance_filter);
        
        if (!is_array($result) || empty($result["bookings"])) {
            echo json_encode([
                "success" => false,
                "message" => "No bookings found to export"
            ]);
            return;
        }
        
        $bookings = $result["bookings"];
        
        // Generate export file based on format
        if ($format === "pdf") {
            // Generate PDF file
            // Implementation will depend on PDF library used in the project
            echo json_encode([
                "success" => true,
                "url" => "/exports/bookings_" . $user_id . "_" . time() . ".pdf"
            ]);
        } else if ($format === "csv") {
            // Generate CSV file
            $filename = "bookings_" . $user_id . "_" . time() . ".csv";
            $filepath = __DIR__ . "/../../exports/" . $filename;
            
            // Create exports directory if it doesn't exist
            if (!file_exists(__DIR__ . "/../../exports/")) {
                mkdir(__DIR__ . "/../../exports/", 0777, true);
            }
            
            // Create CSV file
            $csv = fopen($filepath, "w");
            
            // Add headers
            fputcsv($csv, ["Booking ID", "Destination", "Date of Tour", "End of Tour", "Days", "Buses", "Total Cost", "Balance", "Status"]);
            
            // Add data
            foreach ($bookings as $booking) {
                fputcsv($csv, [
                    $booking["booking_id"],
                    $booking["destination"],
                    $booking["date_of_tour"],
                    $booking["end_of_tour"],
                    $booking["number_of_days"],
                    $booking["number_of_buses"],
                    $booking["total_cost"],
                    $booking["balance"],
                    $booking["status"]
                ]);
            }
            
            fclose($csv);
            
            echo json_encode([
                "success" => true,
                "url" => "/exports/" . $filename
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Invalid export format"
            ]);
        }
    }

    public function printInvoice($booking_id = null) {
        if (!$booking_id) {
            // Redirect to bookings page if no ID provided
            header("Location: /home/booking-requests");
            exit();
        }
        
        $user_id = $_SESSION["user_id"];
        
        // Get booking details
        $booking = $this->bookingModel->getBooking($booking_id, $user_id);
        
        if (!$booking) {
            // Booking not found or doesn't belong to this user
            header("Location: /home/booking-requests");
            exit();
        }
        
        // Get booking stops
        $stops = $this->bookingModel->getBookingStops($booking_id);
        
        // Get payment history
        $payments = $this->bookingModel->getPaymentHistory($booking_id);
        
        // Load the invoice template view
        require_once __DIR__ . "/../../views/client/invoice.php";
    }
}
?>