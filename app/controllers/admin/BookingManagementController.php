<?php
require_once __DIR__ . "/../../models/admin/BookingManagementModel.php";

class BookingManagementController {
    private $bookingModel;

    public function __construct() {
        $this->bookingModel = new BookingManagementModel();
        
        // Check if the user is logged in and is an admin
        $requestUri = $_SERVER['REQUEST_URI'];
        if (strpos($requestUri, '/admin') === 0 && 
            strpos($requestUri, '/admin/login') === false && 
            strpos($requestUri, '/admin/submit-login') === false) {
            
            if (!isset($_SESSION["role"]) || ($_SESSION["role"] !== "Super Admin" && $_SESSION["role"] !== "Admin")) {
                header("Location: /admin/login");
                exit();
            }
        }
    }

    // New method for booking stats dashboard
    public function getBookingStats() {
        header("Content-Type: application/json");
        
        $stats = $this->bookingModel->getBookingStats();
        
        if (is_array($stats)) {
            echo json_encode(["success" => true, "stats" => $stats]);
        } else {
            echo json_encode(["success" => false, "message" => $stats]);
        }
    }
    
    // New method for calendar bookings
    public function getCalendarBookings() {
        $data = json_decode(file_get_contents("php://input"), true);
        $start = isset($data["start"]) ? $data["start"] : date('Y-m-01'); // First day of current month
        $end = isset($data["end"]) ? $data["end"] : date('Y-m-t'); // Last day of current month
        
        $bookings = $this->bookingModel->getCalendarBookings($start, $end);
        
        header("Content-Type: application/json");
        
        if (is_array($bookings)) {
            echo json_encode(["success" => true, "bookings" => $bookings]);
        } else {
            echo json_encode(["success" => false, "message" => $bookings]);
        }
    }
    
    // New method for search bookings
    public function searchBookings() {
        $data = json_decode(file_get_contents("php://input"), true);
        $searchTerm = $data["searchTerm"];
        $status = $data["status"];
        $page = isset($data["page"]) ? (int)$data["page"] : 1;
        $limit = isset($data["limit"]) ? (int)$data["limit"] : 10;
        
        $bookings = $this->bookingModel->searchBookings($searchTerm, $status, $page, $limit);
        $total = $this->bookingModel->getTotalSearchResults($searchTerm, $status);
        
        // Fix for pagination bug when total == limit
        // We need to make sure totalPages is at least 1, and correctly calculated
        $totalPages = max(1, ceil((int)$total / (int)$limit));
        
        $response = [
            "success" => true, 
            "bookings" => $bookings,
            "pagination" => [
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page,
                "limit" => $limit
            ]
        ];
        
        header("Content-Type: application/json");
        
        if (is_array($bookings)) {
            echo json_encode($response);
        } else {
            echo json_encode(["success" => false, "message" => $bookings]);
        }
    }
    
    // New method for unpaid bookings filter
    public function getUnpaidBookings() {
        $data = json_decode(file_get_contents("php://input"), true);
        $page = isset($data["page"]) ? (int)$data["page"] : 1;
        $limit = isset($data["limit"]) ? (int)$data["limit"] : 10;
        
        $bookings = $this->bookingModel->getUnpaidBookings($page, $limit);
        $total = $this->bookingModel->getTotalUnpaidBookings();
        
        // Fix for pagination bug when total == limit
        // We need to make sure totalPages is at least 1, and correctly calculated
        $totalPages = max(1, ceil((int)$total / (int)$limit));
        
        $response = [
            "success" => true, 
            "bookings" => $bookings,
            "pagination" => [
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page,
                "limit" => $limit
            ]
        ];
        
        header("Content-Type: application/json");
        
        if (is_array($bookings)) {
            echo json_encode($response);
        } else {
            echo json_encode(["success" => false, "message" => $bookings]);
        }
    }
    
    // New method for export bookings
    public function exportBookings() {
        $format = $_GET["format"] ?? "csv";
        $status = $_GET["status"] ?? "All";
        
        $bookings = $this->bookingModel->getAllBookingsForExport($status);
        
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=bookings_" . strtolower($status) . "_" . date("Y-m-d") . "." . $format);
        header("Pragma: no-cache");
        header("Expires: 0");
        
        if ($format === "csv") {
            $this->exportToCSV($bookings);
        } else {
            // For simplicity, we'll just export CSV for now
            // PDF export would require additional libraries
            $this->exportToCSV($bookings);
        }
        
        exit;
    }
    
    // Helper method for CSV export
    private function exportToCSV($bookings) {
        $output = fopen("php://output", "w");
        
        // Write headers
        fputcsv($output, [
            "ID", "Client Name", "Contact Number", "Email", "Destination", 
            "Date of Tour", "Number of Days", "Number of Buses", "Total Cost",
            "Payment Status", "Status"
        ]);
        
        // Write data
        foreach ($bookings as $booking) {
            fputcsv($output, [
                $booking["booking_id"],
                $booking["client_name"],
                $booking["contact_number"],
                $booking["email"] ?? "N/A",
                $booking["destination"],
                $booking["date_of_tour"],
                $booking["number_of_days"],
                $booking["number_of_buses"],
                $booking["total_cost"],
                $booking["payment_status"],
                $booking["status"]
            ]);
        }
        
        fclose($output);
    }

    public function showBookingDetail() {
        include_once __DIR__ . "/../../views/admin/booking_request.php";
    }

    public function getAllBookings() {
        $data = json_decode(file_get_contents("php://input"), true);
        $status = $data["status"];
        $column = $data["column"];
        $order = $data["order"];
        $page = isset($data["page"]) ? (int)$data["page"] : 1;
        $limit = isset($data["limit"]) ? (int)$data["limit"] : 10;

        $bookings = $this->bookingModel->getAllBookings($status, $column, $order, $page, $limit);
        $total = $this->bookingModel->getTotalBookings($status);
        
        // Fix for pagination bug when total == limit
        // We need to make sure totalPages is at least 1, and correctly calculated
        $totalPages = max(1, ceil((int)$total / (int)$limit));
        
        // Debug info
        error_log("Debug - Total records: $total, Limit: $limit, Total Pages Calculated: $totalPages");
        
        $response = [
            "success" => true, 
            "bookings" => $bookings,
            "pagination" => [
                "total" => $total,
                "totalPages" => $totalPages,
                "currentPage" => $page,
                "limit" => $limit
            ]
        ];
        
        error_log("Response: " . json_encode($response));

        header("Content-Type: application/json");

        if (is_array($bookings)) {
            echo json_encode($response);
        } else {
            echo json_encode(["success" => false, "message" => $bookings]);
        }
    }

    public function showBookingTable() {
        require_once __DIR__ . "/../../views/admin/booking_management.php";
    }

    public function confirmBooking() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents("php://input"), true);
            $booking_id = $data["bookingId"];
            $discount = isset($data["discount"]) ? (float)$data["discount"] : 0;
        
            $result = $this->bookingModel->confirmBooking($booking_id, $discount);
        
            header("Content-Type: application/json");
            
            if ($result === "success") {
                echo json_encode(["success" => true, "message" => "Booking request confirmed successfully!"]);
            } else {
                echo json_encode(["success" => false, "message" => $result]);
            }
        }
    } 

    public function rejectBooking() {
        header("Content-Type: application/json");

        $data = json_decode(file_get_contents("php://input"), true);
        $reason = $data["reason"];
        $booking_id = (int) $data["bookingId"];
        $user_id = (int) $data["userId"];

        $result = $this->bookingModel->rejectBooking($reason, $booking_id, $user_id);

        echo json_encode(["success" => $result["success"], "message" => $result["message"]]);
    }

    public function showReschedRequestTable() {
        require_once __DIR__ . "/../../views/admin/rebooking_requests.php";
    }

    public function getRebookingRequests() {
        $data = json_decode(file_get_contents("php://input"), true);
        $status = $data["status"];
        $order = $data["order"];
        $column = $data["column"];

        $reschedRequests = $this->bookingModel->getRebookingRequests($status, $column, $order);

        header("Content-Type: application/json");

        if (is_array($reschedRequests)) {
            echo json_encode(["success" => true, "requests" => $reschedRequests]);
        } else {
            echo json_encode(["success" => false, "message" => $reschedRequests]);
        }
    }

    public function confirmRebookingRequest() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents("php://input"), true);
            $rebooking_id = $data["bookingId"];
            $discount = isset($data["discount"]) ? (float)$data["discount"] : 0;

            $result = $this->bookingModel->confirmRebookingRequest($rebooking_id, $discount);
            header("Content-Type: application/json");

            echo json_encode([
                "success" => $result["success"],
                "message" => $result["success"] 
                    ? "Booking confirmed successfully." 
                    : $result["message"]
            ]);
        }   
    }

    public function rejectRebooking() {
        header("Content-Type: application/json");

        $data = json_decode(file_get_contents("php://input"), true);
        $reason = $data["reason"];
        $booking_id = (int) $data["bookingId"];
        $user_id = (int) $data["userId"];

        $result = $this->bookingModel->rejectRebooking($reason, $booking_id, $user_id);

        echo json_encode([
            "success" => $result["success"],
            "message" => $result["success"] 
                ? "Booking rejected successfully." 
                : $result["message"]
        ]);
    }

    public function getBooking() {
        header("Content-Type: application/json");

        $data = json_decode(file_get_contents("php://input"), true);

        $booking_id = $data["bookingId"];

        $booking = $this->bookingModel->getBooking($booking_id);
        $stops = $this->bookingModel->getBookingStops($booking_id);
        $distances = $this->bookingModel->getTripDistances($booking_id);
        $diesel = $this->bookingModel->getDieselPrice();

        if ($booking) {
            echo json_encode(["success" => true, "booking" => $booking, "stops" => $stops, "distances" =>  $distances, "diesel" => $diesel]);
        } else {
            echo json_encode(["success" => false, "message" => $booking]);
        }
    }

    // This is the endpoint for the admin booking details modal
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
        
        // Get booking details
        $booking = $this->bookingModel->getBooking($booking_id);
        
        // Get stops
        $stops = $this->bookingModel->getBookingStops($booking_id);

        $payments = $this->bookingModel->getPaymentHistory($booking_id);
        
        if ($booking) {
            // Add stops to booking object
            $booking['stops'] = $stops;
            $booking['payments'] = $payments;
            echo json_encode([
                "success" => true,
                "booking" => $booking
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Booking not found"
            ]);
        }
    }

    public function printInvoice($booking_id = null) {
        if (!$booking_id) {
            // Redirect to bookings page if no ID provided
            header("Location: /admin/booking-requests");
            exit();
        }
        
        // Get booking details
        $booking = $this->bookingModel->getBooking($booking_id);
        
        if (!$booking) {
            // Booking not found
            header("Location: /admin/booking-requests");
            exit();
        }
        
        // Get booking stops
        $stops = $this->bookingModel->getBookingStops($booking_id);
        
        // Get payment history
        $payments = $this->bookingModel->getPaymentHistory($booking_id);
        
        // Load the invoice template view
        require_once __DIR__ . "/../../views/admin/invoice.php";
    }

    public function printContract($booking_id = null) {
        if (!$booking_id) {
            // Redirect to bookings page if no ID provided
            header("Location: /admin/booking-requests");
            exit();
        }
        
        // Get booking details
        $booking = $this->bookingModel->getBooking($booking_id);
        
        if (!$booking) {
            // Booking not found
            header("Location: /admin/booking-requests");
            exit();
        }
        
        // Get booking stops
        $stops = $this->bookingModel->getBookingStops($booking_id);
        
        // Load the contract template view
        require_once __DIR__ . "/../../views/admin/contract.php";
    }

    public function cancelBooking() {
        header("Content-Type: application/json");

        $data = json_decode(file_get_contents("php://input"), true);

        $booking_id = $data["bookingId"];
        $user_id = $data["userId"];
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

    public function summaryMetrics() {
        header("Content-Type: application/json");

        $summary_metrics = $this->bookingModel->summaryMetrics();

        echo json_encode($summary_metrics);
    }

    public function paymentMethodChart() {
        header("Content-Type: application/json");

        try {
            $payment_methods = $this->bookingModel->paymentMethodChart();
            
            // Check if we received an error message instead of the expected data
            if (is_string($payment_methods) && strpos($payment_methods, "Database error") !== false) {
                echo json_encode(["error" => $payment_methods]);
                return;
            }
            
            echo json_encode($payment_methods);
        } catch (Exception $e) {
            echo json_encode(["error" => "Failed to retrieve payment method data: " . $e->getMessage()]);
        }
    }

    public function monthlyBookingTrends() {
        header("Content-Type: application/json");
        
        $trends = $this->bookingModel->getMonthlyBookingTrends();
        
        echo json_encode($trends);
    }
    
    public function topDestinations() {
        header("Content-Type: application/json");
        
        $destinations = $this->bookingModel->getTopDestinations();
        
        echo json_encode($destinations);
    }
    
    public function bookingStatusDistribution() {
        header("Content-Type: application/json");
        
        $statuses = $this->bookingModel->getBookingStatusDistribution();
        
        echo json_encode($statuses);
    }
    
    public function revenueTrends() {
        header("Content-Type: application/json");
        
        $revenue = $this->bookingModel->getRevenueTrends();
        
        echo json_encode($revenue);
    }
    
    // New method for showing the create booking form
    public function showCreateBookingForm() {
        include_once __DIR__ . "/../../views/admin/create_booking.php";
    }
    
    // New method for processing admin-created bookings
    public function createBooking() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents("php://input"), true);
            
            // Validate required fields
            $requiredFields = [
                'clientName', 'contactNumber', 'email', 
                'destination', 'pickupPoint', 'dateOfTour', 
                'numberOfDays', 'numberOfBuses', 'totalCost'
            ];
            
            foreach ($requiredFields as $field) {
                if (!isset($data[$field]) || $data[$field] === '') {
                    header("Content-Type: application/json");
                    echo json_encode([
                        "success" => false, 
                        "message" => "Please fill in all required fields.",
                        "field" => $field
                    ]);
                    return;
                }
            }
            
            // Prepare booking data for the model
            $bookingData = [
                'client_name' => $data['clientName'],
                'contact_number' => $data['contactNumber'],
                'email' => $data['email'],
                'address' => $data['address'],
                'destination' => $data['destination'],
                'pickup_point' => $data['pickupPoint'],
                'date_of_tour' => $data['dateOfTour'],
                'number_of_days' => (int)$data['numberOfDays'],
                'number_of_buses' => (int)$data['numberOfBuses'],
                'estimated_pax' => isset($data['estimatedPax']) ? (int)$data['estimatedPax'] : 0,
                'total_cost' => (float)$data['totalCost'],
                'discount' => isset($data['discount']) ? (float)$data['discount'] : 0,
                'notes' => isset($data['notes']) ? $data['notes'] : '',
                'status' => 'Confirmed', // Admin-created bookings are auto-confirmed
                'created_by' => 'admin', // Mark as created by admin
                'stops' => isset($data['stops']) ? $data['stops'] : [],
            ];
            
            // Calculate the end date based on start date and number of days
            $startDate = new DateTime($data['dateOfTour']);
            $endDate = clone $startDate;
            $endDate->modify('+' . ((int)$data['numberOfDays'] - 1) . ' days');
            $bookingData['end_of_tour'] = $endDate->format('Y-m-d');
            
            // Add initial payment if provided
            if (isset($data['initialPayment']) && $data['initialPayment']) {
                $bookingData['initial_payment'] = [
                    'amount_paid' => (float)$data['initialPayment']['amountPaid'],
                    'payment_method' => $data['initialPayment']['paymentMethod'],
                    'payment_reference' => $data['initialPayment']['paymentReference'] ?? null
                ];
            }
            
            // Process the booking creation
            $result = $this->bookingModel->createBookingByAdmin($bookingData);
            
            header("Content-Type: application/json");
            if ($result['success']) {
                echo json_encode([
                    "success" => true, 
                    "message" => "Booking created successfully!",
                    "booking_id" => $result['booking_id']
                ]);
            } else {
                echo json_encode([
                    "success" => false, 
                    "message" => $result['message']
                ]);
            }
        }
    }
}
?>