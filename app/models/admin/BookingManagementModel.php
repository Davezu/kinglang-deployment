<?php
require_once __DIR__ . "/../../../config/database.php";
require_once __DIR__ . "/NotificationModel.php";
require_once __DIR__ . "/../client/NotificationModel.php";

class BookingManagementModel {
    public $conn;
    private $notificationModel;
    private $clientNotificationModel;

    public function __construct() {
        global $pdo;
        $this->conn = $pdo;
        $this->notificationModel = new NotificationModel();
        $this->clientNotificationModel = new ClientNotificationModel();
    }

    public function getAllBookings($status, $column, $order, $page = 1, $limit = 10) {

        $allowed_status = ["Pending", "Confirmed", "Canceled", "Rejected", "Completed", "All"];
        $status = in_array($status, $allowed_status) ? $status : "";
        $status == "All" ? $status = "" : $status = " AND b.status = '$status'";

        $allowed_columns = ["booking_id", "client_name", "contact_number", "destination", "pickup_point", "date_of_tour", "end_of_tour", "number_of_days", "number_of_buses", "status", "payment_status", "total_cost"];
        $column = in_array($column, $allowed_columns) ? $column : "client_name";
        $order = $order === "asc" ? "ASC" : "DESC";

        // Calculate offset for pagination
        $offset = ($page - 1) * $limit;

        try {   
            $stmt = $this->conn->prepare("
                SELECT b.booking_id, b.user_id, CONCAT(u.first_name, ' ', u.last_name) AS client_name, u.contact_number, b.destination, b.pickup_point, b.date_of_tour, b.end_of_tour, b.number_of_days, b.number_of_buses, b.status, b.total_cost, b.payment_status
                FROM bookings b
                JOIN users u ON b.user_id = u.user_id
                WHERE is_rebooking = 0 AND is_rebooked = 0
                $status
                ORDER BY $column $order 
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }  catch (PDOException $e) {
            return "Database error: $e";
        }
    }

    public function getTotalBookings($status) {
        $allowed_status = ["Pending", "Confirmed", "Canceled", "Rejected", "Completed", "All"];
        $status = in_array($status, $allowed_status) ? $status : "";
        $status == "All" ? $status = "" : $status = " AND b.status = '$status'";

        try {
            $query = "
                SELECT COUNT(*) as total
                FROM bookings b
                WHERE is_rebooking = 0 AND is_rebooked = 0
                $status
            ";
            
            error_log("Query for counting: " . $query);
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            error_log("Count result: " . print_r($result, true));
            return $result['total'];
        } catch (PDOException $e) {
            error_log("Error in getTotalBookings: " . $e->getMessage());
            return 0;
        }
    }

    public function confirmBooking($booking_id) {
        try {
            $stmt = $this->conn->prepare("UPDATE bookings SET status = 'Confirmed' WHERE booking_id = :booking_id");
            $stmt->execute([":booking_id" => $booking_id]);
            
            // Get booking information
            $bookingInfo = $this->getBooking($booking_id);
            
            // Add admin notification
            // $message = "New booking confirmed for " . $bookingInfo['client_name'] . " to " . $bookingInfo['destination'];
            // $this->notificationModel->addNotification("booking_confirmed", $message, $booking_id);
            
            // // Add client notification
            // $clientMessage = "Your booking to " . $bookingInfo['destination'] . " has been confirmed.";
            // $this->clientNotificationModel->addNotification($bookingInfo['user_id'], "booking_confirmed", $clientMessage, $booking_id);
            
            return "success";
        } catch (PDOException $e) {
            return "Database error.";
        }
    }

    public function rejectBooking($reason, $booking_id, $user_id) {
        $type = "Booking";

        try {            
            $stmt = $this->conn->prepare("UPDATE bookings SET status = 'Rejected' WHERE booking_id = :booking_id");
            $stmt->execute([":booking_id" => $booking_id]);

            $stmt = $this->conn->prepare("INSERT INTO rejected_trips (reason, type, booking_id, user_id) VALUES (:reason, :type, :booking_id, :user_id)");
            $stmt->execute([
                ":reason" => $reason,
                ":type" => $type,
                ":booking_id" => $booking_id,
                ":user_id" => $user_id
            ]);

            // Get booking information
            $bookingInfo = $this->getBooking($booking_id);
            
            // // Add admin notification
            // $message = "Booking rejected for " . $bookingInfo['client_name'] . " to " . $bookingInfo['destination'];
            // $this->notificationModel->addNotification("booking_rejected", $message, $booking_id);
            
            // Add client notification
            $clientMessage = "Your booking to " . $bookingInfo['destination'] . " has been rejected. Reason: " . $reason;
            $this->clientNotificationModel->addNotification($bookingInfo['user_id'], "booking_rejected", $clientMessage, $booking_id);

            return ["success" => true, "message" => "Booking rejected successfully."];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Database error: " . $e->getMessage()];
        }
    }

    public function getRebookingRequests($status, $column, $order) {
        $allowed_status = ["Pending", "Confirmed", "Canceled", "Rejected", "Completed", "All"];
        $status = in_array($status, $allowed_status) ? $status : "";
        $status == "All" ? $status = "" : $status = " WHERE r.status = '$status'";

        $allowed_columns = ["booking_id", "client_name", "contact_number", "new_date_of_tour", "new_end_of_tour", "status"];
        $column = in_array($column, $allowed_columns) ? $column : "client_name";
        $order = $order === "asc" ? "ASC" : "DESC";

        try {
            $stmt = $this->conn->prepare("
                SELECT b.booking_id, r.request_id, b.user_id, CONCAT(u.first_name, ' ', u.last_name) AS client_name, u.contact_number, u.email, b.destination, b.pickup_point, b.number_of_days, b.number_of_buses, r.status, b.payment_status, b.total_cost, b.balance, b.date_of_tour, b.end_of_tour
                FROM rebooking_request r
                JOIN users u ON r.user_id = u.user_id
                JOIN bookings b ON r.rebooking_id = b.booking_id
                $status
                ORDER BY $column $order
            ");
            $stmt->execute();
        
            return $stmt->fetchAll(PDO::FETCH_ASSOC); 
        }  catch (PDOException $e) {
            return "Database error: $e";
        }
    }

    public function getBookingIdFromRebookingRequest($rebooking_id) {
        try {
            $stmt = $this->conn->prepare("SELECT booking_id FROM rebooking_request WHERE rebooking_id = :rebooking_id");
            $stmt->execute([ ":rebooking_id" => $rebooking_id ]);
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            return "Databse error: $e";
        }
    }

    public function confirmRebookingRequest($rebooking_id) {
        $booking_id = $this->getBookingIdFromRebookingRequest($rebooking_id) ?? 0;

        if ($booking_id === 0) {
            return ["success" => false, "message" => "Unable to get booking ID."];
        }

        try {
            $stmt = $this->conn->prepare("UPDATE rebooking_request SET status = 'Confirmed' WHERE rebooking_id = :rebooking_id");
            $stmt->execute([":rebooking_id" => $rebooking_id]);

            $stmt = $this->conn->prepare("UPDATE bookings SET is_rebooked = 1 WHERE booking_id = :booking_id");
            $stmt->execute([ ":booking_id" => $booking_id]);

            $stmt = $this->conn->prepare("UPDATE bookings SET is_rebooking = 0, status = 'Confirmed' WHERE booking_id = :booking_id");
            $stmt->execute([ ":booking_id" => $rebooking_id]);

            // Get booking information
            $bookingInfo = $this->getBooking($rebooking_id);
            
            // Add admin notification
            $message = "Rebooking confirmed for " . $bookingInfo['client_name'] . " to " . $bookingInfo['destination'];
            $this->notificationModel->addNotification("rebooking_confirmed", $message, $rebooking_id);
            
            // Add client notification
            $clientMessage = "Your rebooking request for the trip to " . $bookingInfo['destination'] . " has been confirmed.";
            $this->clientNotificationModel->addNotification($bookingInfo['user_id'], "rebooking_confirmed", $clientMessage, $rebooking_id);

            return ["success" => true];
        } catch (PDOException $e) {
            return ["success" => false, "message" =>  "Database error: " . $e->getMessage()];
        }
    }

    public function rejectRebooking($reason, $booking_id, $user_id) {
        $type = "Rebooking";
        
        try {
            $stmt = $this->conn->prepare("UPDATE rebooking_request SET status = 'Rejected' WHERE rebooking_id = :rebooking_id");
            $stmt->execute([":rebooking_id" => $booking_id]);

            $stmt = $this->conn->prepare("INSERT INTO rejected_trips (reason, type, booking_id, user_id) VALUES (:reason, :type, :booking_id, :user_id)");
            $stmt->execute([
                ":reason" => $reason,
                ":type" => $type,
                ":booking_id" => $booking_id,
                ":user_id" => $user_id
            ]);

            // Get booking information
            $bookingInfo = $this->getBooking($booking_id);
            
            // Add admin notification
            $message = "Rebooking rejected for " . $bookingInfo['client_name'] . " to " . $bookingInfo['destination'];
            $this->notificationModel->addNotification("rebooking_rejected", $message, $booking_id);
            
            // Add client notification
            $clientMessage = "Your rebooking request for the trip to " . $bookingInfo['destination'] . " has been rejected. Reason: " . $reason;
            $this->clientNotificationModel->addNotification($bookingInfo['user_id'], "rebooking_rejected", $clientMessage, $booking_id);

            return ["success" => true];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Database error: " . $e->getMessage()];
        }
    }


    public function getBooking($booking_id) {
        try {
            $stmt = $this->conn->prepare("
                SELECT b.booking_id, u.user_id, CONCAT(u.first_name, ' ', u.last_name) AS client_name, u.email, u.contact_number, b.pickup_point, b.destination, b.number_of_days, b.number_of_buses, b.date_of_tour, b.end_of_tour, b.status, b.payment_status, b.payment_status, b.total_cost, b.balance
                FROM bookings b
                JOIN users u ON b.user_id = u.user_id
                WHERE booking_id = :booking_id
            ");
            $stmt->execute([":booking_id" => $booking_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Database error";
        }
    }

    public function isClientPaid($booking_id) {
        try {
            $stmt = $this->conn->prepare("SELECT payment_status FROM bookings WHERE booking_id = :booking_id");
            $stmt->execute([":booking_id" => $booking_id]);
            $payment_status = $stmt->fetchColumn();
            return $payment_status === "Partially Paid" || $payment_status === "Paid";
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Database error: " . $e->getMessage()];
        }
    }

    public function cancelPayment($booking_id, $user_id) {
        try {
            $stmt = $this->conn->prepare("UPDATE payments SET is_canceled = 1 WHERE booking_id = :booking_id AND user_id = :user_id");
            $stmt->execute([":booking_id" => $booking_id, ":user_id" => $user_id]);
            return ["success" => true];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Database error: " . $e->getMessage()];
        }
    }

    public function getAmountPaid($booking_id, $user_id) {
        try {
            $stmt = $this->conn->prepare("SELECT SUM(amount) AS total_amount FROM payments WHERE status = 'Confirmed' AND booking_id = :booking_id AND user_id = :user_id");
            $stmt->execute([":booking_id" => $booking_id, ":user_id" => $user_id]);
            return (float) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Database error: " . $e->getMessage()];
        }
    }

    public function cancelBooking($reason, $booking_id, $user_id, $amount_refunded) {
        try {
            $stmt = $this->conn->prepare("UPDATE bookings SET status = 'Canceled' WHERE booking_id = :booking_id");
            $stmt->execute([":booking_id" => $booking_id]);

            $stmt = $this->conn->prepare("INSERT INTO canceled_trips (reason, booking_id, user_id, amount_refunded, canceled_by) VALUES (:reason, :booking_id, :user_id, :amount_refunded, :canceled_by)");
            $stmt->execute([":reason" => $reason, ":booking_id" => $booking_id, ":user_id" => $user_id, ":amount_refunded" => $amount_refunded, ":canceled_by" => $_SESSION["role"]]);

            // Get booking information
            $bookingInfo = $this->getBooking($booking_id);
            
            // Add admin notification
            // $message = "Booking canceled for " . $bookingInfo['client_name'] . " to " . $bookingInfo['destination'];
            // $this->notificationModel->addNotification("booking_canceled", $message, $booking_id);
            
            // // Add client notification
            // $clientMessage = "Your booking to " . $bookingInfo['destination'] . " has been canceled. ";
            // if ($amount_refunded > 0) {
            //     $clientMessage .= "Refunded amount: " . $amount_refunded;
            // }
            $this->clientNotificationModel->addNotification($bookingInfo['user_id'], "booking_canceled", $clientMessage, $booking_id);

            return ["success" => true];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Database error: " . $e->getMessage()];
        }
    }






    public function getBookingStops($booking_id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM booking_stops WHERE booking_id = :booking_id ORDER BY stop_order");
            $stmt->execute([":booking_id" => $booking_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC) ?? [];
        } catch (PDOException $e) {
            return "Database error.";
        }
    }

    public function getTripDistances($booking_id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM trip_distances WHERE booking_id = :booking_id");
            $stmt->execute([":booking_id" => $booking_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Database error";
        }
    }

    public function getDieselPrice() {
        try {
            $stmt = $this->conn->prepare("SELECT price FROM diesel_per_liter ORDER BY date DESC LIMIT 1");
            $stmt->execute();
            $diesel_price = $stmt->fetchColumn() ?? 0;
            return $diesel_price;
        } catch (PDOException $e) {
            return "Database error: $e";
        }
    }





    public function summaryMetrics() {
        try {
            $stmt = $this->conn->prepare("SELECT COUNT(*) total_bookings FROM bookings WHERE is_rebooking = 0 AND is_rebooked = 0");
            $stmt->execute();
            $total_bookings = $stmt->fetchColumn();

            $stmt = $this->conn->prepare("SELECT SUM(amount) as total_revenue FROM payments WHERE is_canceled = 0 AND status = 'Confirmed'");
            $stmt->execute();
            $total_revenue = $stmt->fetchColumn() ?? 0;

            $stmt = $this->conn->prepare("SELECT COUNT(*) as upcoming_trips FROM bookings WHERE status = 'Confirmed' AND date_of_tour > CURDATE() AND is_rebooking = 0 AND is_rebooked = 0 AND payment_status IN ('Paid', 'Partially Paid')");
            $stmt->execute();
            $upcoming_trips = $stmt->fetchColumn();

            $stmt = $this->conn->prepare("SELECT COUNT(*) as pending_bookings FROM bookings WHERE status = 'Pending' AND is_rebooking = 0 AND is_rebooked = 0");
            $stmt->execute();
            $pending_bookings = $stmt->fetchColumn();

            $stmt = $this->conn->prepare("SELECT COUNT(*) as processing_payments FROM bookings WHERE status = 'Processing' AND payment_status IN ('Unpaid', 'Partially Paid') AND is_rebooking = 0 AND is_rebooked = 0");
            $stmt->execute();
            $processing_payments = $stmt->fetchColumn();

            $stmt = $this->conn->prepare("SELECT COUNT(*) as flagged_bookings FROM bookings WHERE status = 'Confirmed' AND payment_status IN ('Unpaid', 'Partially Paid') AND is_rebooking = 0 AND is_rebooked = 0 AND date_of_tour < CURDATE()");
            $stmt->execute();
            $flagged_bookings = $stmt->fetchColumn();

            return [
                "total_bookings" => $total_bookings, 
                "total_revenue" => $total_revenue, 
                "upcoming_trips" => $upcoming_trips, 
                "pending_bookings" => $pending_bookings,
                "processing_payments" => $processing_payments,
                "flagged_bookings" => $flagged_bookings
            ];

        } catch(PDOException $e) {
            return "Database error. $e";
        }
    }

    public function getMonthlyBookingTrends() {
        try {
            // Get current year
            $year = date('Y');
            
            $stmt = $this->conn->prepare("
                SELECT 
                    MONTH(date_of_tour) as month,
                    COUNT(booking_id) as booking_count,
                    SUM(CASE WHEN payment_status IN ('Paid', 'Partially Paid') AND status IN ('Confirmed', 'Completed') THEN total_cost ELSE 0 END) as total_revenue
                FROM bookings 
                WHERE YEAR(date_of_tour) = :year
                AND is_rebooking = 0
                AND is_rebooked = 0
                GROUP BY MONTH(date_of_tour)
                ORDER BY month
            ");
            $stmt->bindValue(':year', $year);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Initialize data for all months
            $monthlyData = [];
            for ($i = 1; $i <= 12; $i++) {
                $monthlyData[$i] = [
                    'month' => $i,
                    'booking_count' => 0,
                    'total_revenue' => 0
                ];
            }
            
            // Fill in actual data
            foreach ($results as $row) {
                $monthlyData[$row['month']] = [
                    'month' => (int)$row['month'],
                    'booking_count' => (int)$row['booking_count'],
                    'total_revenue' => (float)$row['total_revenue']
                ];
            }
            
            // Format for chart
            $months = [];
            $counts = [];
            $revenues = [];
            
            foreach ($monthlyData as $monthData) {
                $months[] = date('F', mktime(0, 0, 0, $monthData['month'], 1));
                $counts[] = $monthData['booking_count'];
                $revenues[] = $monthData['total_revenue'];
            }
            
            return [
                'year' => $year,
                'labels' => $months,
                'counts' => $counts,
                'revenues' => $revenues
            ];
        } catch (PDOException $e) {
            error_log("Error in getMonthlyBookingTrends: " . $e->getMessage());
            return "Database error: $e";
        }
    }
    
    public function getTopDestinations() {
        try {
            $stmt = $this->conn->prepare("
                SELECT 
                    destination,
                    COUNT(*) as trip_count,
                    SUM(total_cost) as total_revenue
                FROM bookings
                WHERE status IN ('Confirmed', 'Completed')
                AND is_rebooked = 0
                GROUP BY destination
                ORDER BY trip_count DESC
                LIMIT 5
            ");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Check if we have results
            if (empty($results)) {
                return [
                    'labels' => ['No Data Available'],
                    'counts' => [0],
                    'revenues' => [0]
                ];
            }
            
            $destinations = [];
            $counts = [];
            $revenues = [];
            
            foreach ($results as $row) {
                // Trim long destination names for better display
                $destinationName = strlen($row['destination']) > 20 
                    ? substr($row['destination'], 0, 18) . '...' 
                    : $row['destination'];
                $destinations[] = $destinationName;
                $counts[] = (int)$row['trip_count'];
                $revenues[] = (float)$row['total_revenue'];
            }
            
            return [
                'labels' => $destinations,
                'counts' => $counts,
                'revenues' => $revenues
            ];
        } catch (PDOException $e) {
            error_log("Error in getTopDestinations: " . $e->getMessage());
            return "Database error: $e";
        }
    }
    
    public function getBookingStatusDistribution() {
        try {
            $stmt = $this->conn->prepare("
                SELECT 
                    status,
                    COUNT(*) as count,
                    SUM(total_cost) as total_value
                FROM bookings
                WHERE is_rebooked = 0
                AND is_rebooking = 0
                GROUP BY status
                ORDER BY count DESC
            ");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($results)) {
                return [
                    'labels' => [],
                    'counts' => [],
                    'values' => []
                ];
            }
            
            // Define the order of statuses to ensure consistent colors
            $statusOrder = ['Confirmed', 'Pending', 'Completed', 'Canceled', 'Rejected'];
            $orderedData = [];
            
            // Initialize all statuses with 0 to ensure they all appear even if empty
            foreach ($statusOrder as $status) {
                $orderedData[$status] = [
                    'count' => 0,
                    'value' => 0
                ];
            }
            
            // Fill in actual data
            foreach ($results as $row) {
                if (isset($orderedData[$row['status']])) {
                    $orderedData[$row['status']] = [
                        'count' => (int)$row['count'],
                        'value' => (float)$row['total_value']
                    ];
                }
            }
            
            // Format for chart
            $labels = [];
            $counts = [];
            $values = [];
            
            foreach ($orderedData as $status => $data) {
                if ($data['count'] > 0) {
                    $labels[] = $status;
                    $counts[] = $data['count'];
                    $values[] = $data['value'];
                }
            }
            
            return [
                'labels' => $labels,
                'counts' => $counts,
                'values' => $values
            ];
        } catch (PDOException $e) {
            error_log("Error in getBookingStatusDistribution: " . $e->getMessage());
            return "Database error: $e";
        }
    }
    
    public function getRevenueTrends() {
        try {
            // Get data for last 6 months
            $stmt = $this->conn->prepare("
                SELECT 
                    DATE_FORMAT(date_of_tour, '%Y-%m') as month_year,
                    MONTH(date_of_tour) as month,
                    YEAR(date_of_tour) as year,
                    COUNT(*) as booking_count,
                    SUM(total_cost) as total_revenue
                FROM bookings
                WHERE status IN ('Confirmed', 'Completed')
                AND payment_status IN ('Paid', 'Partially Paid')
                AND is_rebooked = 0
                AND is_rebooking = 0
                AND date_of_tour >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY month_year, YEAR(date_of_tour), MONTH(date_of_tour)
                ORDER BY year, month
            ");
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            if (empty($results)) {
                return [
                    'labels' => [],
                    'counts' => [],
                    'revenues' => []
                ];
            }
            
            // Format for chart
            $labels = [];
            $counts = [];
            $revenues = [];
            
            foreach ($results as $row) {
                $monthName = date("M Y", mktime(0, 0, 0, $row['month'], 1, $row['year']));
                $labels[] = $monthName;
                $counts[] = (int)$row['booking_count'];
                $revenues[] = (float)$row['total_revenue'];
            }
            
            return [
                'labels' => $labels,
                'counts' => $counts,
                'revenues' => $revenues
            ];
        } catch (PDOException $e) {
            error_log("Error in getRevenueTrends: " . $e->getMessage());
            return "Database error: $e";
        }
    }

    public function paymentMethodChart() {
        try {
            $sql = "SELECT 
                p.payment_method,
                COUNT(*) as payment_count,
                SUM(p.amount) as total_amount
            FROM payments p
            WHERE p.status = 'Confirmed'
            AND p.is_canceled = 0
            GROUP BY p.payment_method
            ORDER BY payment_count DESC";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Check if there's any data
            if (empty($results)) {
                // Return default data with zero counts
                return [
                    'labels' => ['Cash', 'Bank Transfer'],
                    'counts' => [0, 0],
                    'amounts' => [0, 0]
                ];
            }
            
            // Process the results into chart-friendly format
            $labels = [];
            $counts = [];
            $amounts = [];
            
            foreach ($results as $row) {
                $labels[] = $row['payment_method'];
                $counts[] = (int)$row['payment_count'];
                $amounts[] = (float)$row['total_amount'];
            }
            
            return [
                'labels' => $labels,
                'counts' => $counts,
                'amounts' => $amounts
            ];
        } catch (PDOException $e) {
            error_log("Error in paymentMethodChart: " . $e->getMessage());
            return "Database error: $e";    
        }
    }
}
?>