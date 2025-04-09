<?php
require_once __DIR__ . "/../../models/admin/BookingManagementModel.php";

class BookingManagementController {
    private $bookingModel;

    public function __construct() {
        $this->bookingModel = new BookingManagementModel();
    }

    public function getAllBookings() {
        $data = json_decode(file_get_contents("php://input"), true);
        $staus = $data["status"];
        $column = $data["column"];
        $order = $data["order"];

        $bookings = $this->bookingModel->getAllBookings($staus, $column, $order);

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

    public function showReschedRequestTable() {
        require_once __DIR__ . "/../../views/admin/reschedule_requests.php";
    }

    public function getReschedRequests() {
        $data = json_decode(file_get_contents("php://input"), true);
        $status = $data["status"];
        $order = $data["order"];
        $column = $data["column"];

        $reschedRequests = $this->bookingModel->getReschedRequests($status, $column, $order);

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