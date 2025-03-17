<?php
require_once __DIR__ . "/../../models/admin/BookingManagementModel.php";

class BookingManagementController {
    private $bookingModel;

    public function __construct() {
        $this->bookingModel = new BookingManagementModel();
    }

    public function getAllBookings() {
        $bookings = $this->bookingModel->getAllBookings();

        header("Content-Type: application/json");

        if (is_array($bookings)) {
            echo json_encode(["success" => true, "bookings" => $bookings]);
        } else {
            echo json_encode(["success" => false, "message" => $bookings]);
        }
    }

    public function showBookingTable() {
        require_once __DIR__ . "/../../views/admin/booking_management.php";
    }

    public function sendQuote() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $total_cost = $_POST["total_cost"];
            $booking_id = $_POST["booking_id"];
        
            $result = $this->bookingModel->sendQuote($booking_id, $total_cost);
        
            if (!$result) $_SESSION["send_quote_message"] = "Sending quote failed: {$result}";
        
            $_SESSION["send_quote_message"] = "Sending quote successfully!";
            header("Location: /admin/bookings");
            exit();
        }
    } 

    public function showReschedRequestTable() {
        require_once __DIR__ . "/../../views/admin/reschedule_requests.php";
    }

    public function getReschedRequests() {
        $reschedRequests = $this->bookingModel->getReschedRequests();

        header("Content-Type: application/json");

        if (is_array($reschedRequests)) {
            echo json_encode(["success" => true, "requests" => $reschedRequests]);
        } else {
            echo json_encode(["success" => false, "message" => $reschedRequests]);
        }
    }

    public function confirmReschedRequest() {
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $data = json_decode(file_get_contents("php://input"), true);
            $request_id = $data["requestId"];
            $booking_id = $data["bookingId"];
            $date_of_tour = $data["dateOfTour"];
            $end_of_tour = $data["endOfTour"];

            $result = $this->bookingModel->confirmReschedRequest($request_id, $booking_id, $date_of_tour, $end_of_tour);
            header("Content-Type: application/json");

            if ($result === "success") {
                echo json_encode(["success" => true, "message" => "Reschedule request confirmed!"]);
            } else {
                echo json_encode(["success" => false, "message" => $result]);
            }
        }   
    }
}
?>