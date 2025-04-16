<?php
require_once __DIR__ . "/../../models/admin/BookingManagementModel.php";

class BookingManagementController {
    private $bookingModel;

    public function __construct() {
        $this->bookingModel = new BookingManagementModel();
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
        $totalPages = ceil($total / $limit);

        header("Content-Type: application/json");

        if (is_array($bookings)) {
            echo json_encode([
                "success" => true, 
                "bookings" => $bookings,
                "pagination" => [
                    "total" => $total,
                    "totalPages" => $totalPages,
                    "currentPage" => $page,
                    "limit" => $limit
                ]
            ]);
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
        
            $result = $this->bookingModel->confirmBooking($booking_id);
        
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

            $result = $this->bookingModel->confirmRebookingRequest($rebooking_id);
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

        $payment_methods = $this->bookingModel->paymentMethodChart();

        echo json_encode($payment_methods);
    }
}
?>