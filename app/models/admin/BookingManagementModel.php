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
                SELECT b.booking_id, b.user_id, CONCAT(u.first_name, ' ', u.last_name) AS client_name, u.contact_number, b.destination, b.pickup_point, b.date_of_tour, b.end_of_tour, b.number_of_days, b.number_of_buses, b.status, b.payment_status, c.total_cost, b.balance
                FROM bookings b
                JOIN users u ON b.user_id = u.user_id
                JOIN booking_costs c ON b.booking_id = c.booking_id
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

    public function getPaymentHistory($booking_id) {
        try {   
            $sql = "
                SELECT p.payment_id, p.booking_id, p.user_id, p.amount, p.payment_method, 
                       p.proof_of_payment, p.status, p.payment_date, p.updated_at, p.is_canceled
                FROM payments p
                WHERE p.booking_id = :booking_id
                ORDER BY p.payment_date DESC
            ";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(":booking_id", $booking_id);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
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

    public function confirmBooking($booking_id, $discount = 0) {
        try {
            $stmt = $this->conn->prepare("UPDATE bookings SET status = 'Confirmed', confirmed_at = NOW() WHERE booking_id = :booking_id");
            $stmt->execute([":booking_id" => $booking_id]);
            
            // Get booking information
            $bookingInfo = $this->getBooking($booking_id);
            
            // Apply discount if provided
            if ($discount > 0) {
                // Get current booking cost
                $stmt = $this->conn->prepare("SELECT total_cost FROM booking_costs WHERE booking_id = :booking_id");
                $stmt->execute([":booking_id" => $booking_id]);
                $originalCost = (float)$stmt->fetchColumn();
                
                // Calculate new discounted cost
                $discountMultiplier = (100 - $discount) / 100;
                $discountedCost = round($originalCost * $discountMultiplier, 2);
                
                // Update the booking costs with discount
                $stmt = $this->conn->prepare("UPDATE booking_costs SET gross_price = :gross_price, total_cost = :total_cost, discount = :discount WHERE booking_id = :booking_id");
                $stmt->execute([
                    ":gross_price" => $originalCost,
                    ":total_cost" => $discountedCost,
                    ":discount" => $discount,
                    ":booking_id" => $booking_id
                ]);
                
                // Also update the balance in the bookings table
                $stmt = $this->conn->prepare("SELECT SUM(amount) AS total_paid FROM payments WHERE booking_id = :booking_id AND status = 'Confirmed'");
                $stmt->execute([":booking_id" => $booking_id]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $totalPaid = isset($result["total_paid"]) ? $result["total_paid"] : 0;
                
                // Calculate balance with proper rounding
                $balance = round($discountedCost - $totalPaid, 2);
                
                // Handle tiny negative balances
                if ($balance > -0.1 && $balance < 0) {
                    $balance = 0;
                }
                
                // Update payment status
                $newStatus = "Unpaid";
                if ($totalPaid > 0 && $totalPaid < $discountedCost) {
                    $newStatus = "Partially Paid";
                } elseif ($totalPaid >= $discountedCost) {
                    $newStatus = "Paid";
                }
                
                $stmt = $this->conn->prepare("UPDATE bookings SET balance = :balance, payment_status = :payment_status WHERE booking_id = :booking_id");
                $stmt->execute([
                    ":balance" => $balance,
                    ":payment_status" => $newStatus,
                    ":booking_id" => $booking_id
                ]);
            }
            
            // Add admin notification
            // $message = "New booking confirmed for " . $bookingInfo['client_name'] . " to " . $bookingInfo['destination'];
            // $this->notificationModel->addNotification("booking_confirmed", $message, $booking_id);
            
            // // Add client notification
            // $clientMessage = "Your booking to " . $bookingInfo['destination'] . " has been confirmed.";
            // $this->clientNotificationModel->addNotification($bookingInfo['user_id'], "booking_confirmed", $clientMessage, $booking_id);
            
            return "success";
        } catch (PDOException $e) {
            return "Database error: " . $e->getMessage();
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
                SELECT b.booking_id, r.request_id, b.user_id, CONCAT(u.first_name, ' ', u.last_name) AS client_name, u.contact_number, u.email, b.destination, b.pickup_point, b.number_of_days, b.number_of_buses, r.status, b.payment_status, c.total_cost, b.balance, b.date_of_tour, b.end_of_tour
                FROM rebooking_request r
                JOIN users u ON r.user_id = u.user_id
                JOIN bookings b ON r.rebooking_id = b.booking_id
                JOIN booking_costs c ON r.booking_id = c.booking_id
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
            $result = $stmt->fetchColumn();
            
            if ($result === false) {
                return null; // No booking ID found
            }
            
            return $result;
        } catch (PDOException $e) {
            return "Database error: " . $e->getMessage();
        }
    }

    public function confirmRebookingRequest($rebooking_id, $discount = 0) {
        try {
            // First, let's verify the rebooking request exists
            $stmt = $this->conn->prepare("SELECT * FROM rebooking_request WHERE rebooking_id = :rebooking_id");
            $stmt->execute([":rebooking_id" => $rebooking_id]);
            $request = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$request) {
                return ["success" => false, "message" => "Rebooking request not found."];
            }
            
            // Get the original booking ID (we're getting it directly from the query result)
            $booking_id = $request['booking_id'];
            
            if (!$booking_id) {
                return ["success" => false, "message" => "Original booking ID not found."];
            }
            
            // Update rebooking request status
            $stmt = $this->conn->prepare("UPDATE rebooking_request SET status = 'Confirmed' WHERE rebooking_id = :rebooking_id");
            $stmt->execute([":rebooking_id" => $rebooking_id]);

            // Mark original booking as rebooked
            $stmt = $this->conn->prepare("UPDATE bookings SET is_rebooked = 1 WHERE booking_id = :booking_id");
            $stmt->execute([":booking_id" => $booking_id]);

            // Update the rebooking record to be a normal booking
            $stmt = $this->conn->prepare("UPDATE bookings SET is_rebooking = 0, status = 'Confirmed' WHERE booking_id = :booking_id");
            $stmt->execute([":booking_id" => $rebooking_id]);

            // Update payments booking_id to the new booking_id based on the rebooking_id
            $stmt = $this->conn->prepare("UPDATE payments SET booking_id = :rebooking_id WHERE booking_id = :booking_id");
            $stmt->execute([":rebooking_id" => $rebooking_id, ":booking_id" => $booking_id]);

            // Apply discount if provided
            if ($discount > 0) {
                // Get current booking cost
                $stmt = $this->conn->prepare("SELECT total_cost FROM booking_costs WHERE booking_id = :booking_id");
                $stmt->execute([":booking_id" => $rebooking_id]);
                $originalCost = (float)$stmt->fetchColumn();
                
                // Calculate new discounted cost
                $discountMultiplier = (100 - $discount) / 100;
                $discountedCost = round($originalCost * $discountMultiplier, 2);
                
                // Update the booking costs with discount
                $stmt = $this->conn->prepare("UPDATE booking_costs SET gross_price = :gross_price, total_cost = :total_cost, discount = :discount WHERE booking_id = :booking_id");
                $stmt->execute([
                    ":gross_price" => $originalCost,
                    ":total_cost" => $discountedCost,
                    ":discount" => $discount,
                    ":booking_id" => $rebooking_id
                ]);
                
                // Use discounted cost for further calculations
                $total_cost = $discountedCost;
            } else {
                // Get total cost for the new booking
                $stmt = $this->conn->prepare("SELECT c.total_cost FROM booking_costs c WHERE c.booking_id = :booking_id");
                $stmt->execute([":booking_id" => $rebooking_id]); 
                $total_cost = (float)$stmt->fetchColumn();
            }

            // Get total paid amount from payments from the new booking
            $stmt = $this->conn->prepare("SELECT SUM(amount) AS total_paid FROM payments WHERE booking_id = :booking_id AND status = 'Confirmed'");
            $stmt->execute([":booking_id" => $rebooking_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $total_paid = isset($result["total_paid"]) ? $result["total_paid"] : 0;

            // Calculate balance with proper rounding
            $balance = round($total_cost - $total_paid, 2);
            
            // Handle tiny negative balances
            if ($balance > -0.1 && $balance < 0) {
                $balance = 0;
            }

            $new_status = "Unpaid";
            if ($total_paid > 0 && $total_paid < $total_cost) {
                $new_status = "Partially Paid";
            } elseif ($total_paid >= $total_cost) {
                $new_status = "Paid";
            }

            $stmt = $this->conn->prepare("UPDATE bookings SET payment_status = :payment_status, status = 'Confirmed', balance = :balance, confirmed_at = NOW() WHERE booking_id = :booking_id");
            $stmt->execute([
                ":payment_status" => $new_status,
                ":booking_id" => $rebooking_id,
                ":balance" => $balance
            ]);

            // Get booking information
            $bookingInfo = $this->getBooking($rebooking_id);
            
            // Add admin notification
            $message = "Rebooking confirmed for " . $bookingInfo['client_name'] . " to " . $bookingInfo['destination'];
            $this->notificationModel->addNotification("rebooking_confirmed", $message, $rebooking_id);
            
            // Add client notification
            $clientMessage = "Your rebooking request for the trip to " . $bookingInfo['destination'] . " has been confirmed.";
            $this->clientNotificationModel->addNotification($bookingInfo['user_id'], "rebooking_confirmed", $clientMessage, $rebooking_id);

            return ["success" => true, "message" => "Rebooking request confirmed successfully."];
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
            $bookingInfo = $this->getBooking($rebooking_id);
            
            // Only proceed with notifications if bookingInfo is an array
            if (is_array($bookingInfo)) {
                // Add admin notification
                $message = "Rebooking rejected for " . $bookingInfo['client_name'] . " to " . $bookingInfo['destination'];
                $this->notificationModel->addNotification("rebooking_rejected", $message, $booking_id);
                
                // Add client notification
                $clientMessage = "Your rebooking request for the trip to " . $bookingInfo['destination'] . " has been rejected. Reason: " . $reason;
                $this->clientNotificationModel->addNotification($bookingInfo['user_id'], "rebooking_rejected", $clientMessage, $booking_id);
            }

            return ["success" => true, "message" => "Rebooking request rejected successfully."];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Database error: " . $e->getMessage()];
        }
    }


    public function getBooking($booking_id) {
        try {
            $stmt = $this->conn->prepare("
                SELECT b.*, u.user_id, CONCAT(u.first_name, ' ', u.last_name) AS client_name, u.email, u.contact_number, c.*
                FROM bookings b
                JOIN users u ON b.user_id = u.user_id
                JOIN booking_costs c ON b.booking_id = c.booking_id
                WHERE b.booking_id = :booking_id
            ");
            $stmt->execute([":booking_id" => $booking_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                return null; // No booking found
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Error in getBooking: " . $e->getMessage());
            return ["error" => "Database error: " . $e->getMessage()];
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
            $message = "Booking canceled for " . $bookingInfo['client_name'] . " to " . $bookingInfo['destination'];
            $this->notificationModel->addNotification("booking_canceled", $message, $booking_id);
            
            // Add client notification
            $clientMessage = "Your booking to " . $bookingInfo['destination'] . " has been canceled. ";
            if ($amount_refunded > 0) {
                $clientMessage .= "Refunded amount: " . $amount_refunded;
            }
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
                    MONTH(b.date_of_tour) as month,
                    COUNT(b.booking_id) as booking_count,
                    SUM(CASE WHEN p.status = 'Confirmed' AND p.is_canceled = 0 THEN p.amount ELSE 0 END) as total_revenue
                FROM bookings b
                LEFT JOIN payments p ON b.booking_id = p.booking_id
                WHERE YEAR(b.date_of_tour) = :year
                AND b.is_rebooking = 0
                AND b.is_rebooked = 0
                GROUP BY MONTH(b.date_of_tour)
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
                    b.destination,
                    COUNT(b.booking_id) as trip_count,
                    SUM(CASE WHEN b.payment_status IN ('Paid', 'Partially Paid') AND b.status IN ('Confirmed', 'Completed') THEN c.total_cost ELSE 0 END) as total_revenue
                FROM bookings b
                JOIN booking_costs c ON b.booking_id = c.booking_id
                WHERE b.status IN ('Confirmed', 'Completed')
                AND b.is_rebooked = 0
                GROUP BY b.destination
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
                    b.status,
                    COUNT(b.booking_id) as count,
                    SUM(c.total_cost) as total_value
                FROM bookings b
                JOIN booking_costs c ON b.booking_id = c.booking_id
                WHERE b.is_rebooked = 0
                AND b.is_rebooking = 0
                GROUP BY b.status
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
                    DATE_FORMAT(b.date_of_tour, '%Y-%m') as month_year,
                    MONTH(b.date_of_tour) as month,
                    YEAR(b.date_of_tour) as year,
                    COUNT(b.booking_id) as booking_count,
                    SUM(c.total_cost) as total_revenue
                FROM bookings b
                JOIN booking_costs c ON b.booking_id = c.booking_id
                WHERE b.status IN ('Confirmed', 'Completed')
                AND b.payment_status IN ('Paid', 'Partially Paid')
                AND b.is_rebooked = 0
                AND b.is_rebooking = 0
                AND b.date_of_tour >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                GROUP BY month_year, YEAR(b.date_of_tour), MONTH(b.date_of_tour)
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

    // New method for getting booking stats for the dashboard
    public function getBookingStats() {
        try {
            // Get total bookings
            $stmt = $this->conn->prepare("
                SELECT COUNT(*) AS total 
                FROM bookings 
                WHERE is_rebooking = 0 AND is_rebooked = 0
            ");
            $stmt->execute();
            $total = $stmt->fetchColumn();
            
            // Get confirmed bookings
            $stmt = $this->conn->prepare("
                SELECT COUNT(*) AS confirmed 
                FROM bookings 
                WHERE status = 'Confirmed' AND is_rebooking = 0 AND is_rebooked = 0
            ");
            $stmt->execute();
            $confirmed = $stmt->fetchColumn();
            
            // Get pending bookings
            $stmt = $this->conn->prepare("
                SELECT COUNT(*) AS pending 
                FROM bookings 
                WHERE status = 'Pending' AND is_rebooking = 0 AND is_rebooked = 0
            ");
            $stmt->execute();
            $pending = $stmt->fetchColumn();
            
            // Get upcoming tours (future dates with confirmed status)
            $stmt = $this->conn->prepare("
                SELECT COUNT(*) AS upcoming 
                FROM bookings 
                WHERE status = 'Confirmed' 
                AND date_of_tour >= CURDATE() 
                AND is_rebooking = 0 AND is_rebooked = 0
            ");
            $stmt->execute();
            $upcoming = $stmt->fetchColumn();
            
            return [
                'total' => $total,
                'confirmed' => $confirmed,
                'pending' => $pending,
                'upcoming' => $upcoming
            ];
        } catch (PDOException $e) {
            return "Database error: " . $e->getMessage();
        }
    }
    
    // New method for getting calendar bookings
    public function getCalendarBookings($start, $end) {
        try {
            $stmt = $this->conn->prepare("
                SELECT b.booking_id, b.user_id, CONCAT(u.first_name, ' ', u.last_name) AS client_name, 
                u.contact_number, u.email, b.destination, b.pickup_point, 
                b.date_of_tour, b.end_of_tour, b.number_of_days, b.number_of_buses, 
                b.status, b.payment_status, c.total_cost, b.balance,
                b.created_at, b.updated_at
                FROM bookings b
                JOIN users u ON b.user_id = u.user_id
                JOIN booking_costs c ON b.booking_id = c.booking_id
                WHERE b.is_rebooking = 0 AND b.is_rebooked = 0
                AND ((b.date_of_tour BETWEEN :start AND :end) 
                    OR (b.end_of_tour BETWEEN :start AND :end)
                    OR (b.date_of_tour <= :start AND b.end_of_tour >= :end))
                ORDER BY b.date_of_tour ASC
            ");
            $stmt->bindParam(':start', $start);
            $stmt->bindParam(':end', $end);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Database error: " . $e->getMessage();
        }
    }
    
    // New method for searching bookings
    public function searchBookings($searchTerm, $status, $page = 1, $limit = 10) {
        $allowed_status = ["Pending", "Confirmed", "Canceled", "Rejected", "Completed", "All"];
        $status = in_array($status, $allowed_status) ? $status : "";
        $status_condition = ($status == "All") ? "" : " AND b.status = :status";
        
        // Calculate offset for pagination
        $offset = ($page - 1) * $limit;
        
        try {
            $stmt = $this->conn->prepare("
                SELECT b.booking_id, b.user_id, CONCAT(u.first_name, ' ', u.last_name) AS client_name, 
                u.contact_number, b.destination, b.pickup_point, b.date_of_tour, b.end_of_tour, 
                b.number_of_days, b.number_of_buses, b.status, b.payment_status, c.total_cost, b.balance
                FROM bookings b
                JOIN users u ON b.user_id = u.user_id
                JOIN booking_costs c ON b.booking_id = c.booking_id
                WHERE is_rebooking = 0 AND is_rebooked = 0
                AND (
                    CONCAT(u.first_name, ' ', u.last_name) LIKE :search
                    OR u.contact_number LIKE :search
                    OR b.destination LIKE :search
                    OR b.pickup_point LIKE :search
                )
                $status_condition
                ORDER BY b.booking_id DESC
                LIMIT :limit OFFSET :offset
            ");
            
            $searchParam = "%" . $searchTerm . "%";
            $stmt->bindParam(':search', $searchParam);
            
            if ($status != "All") {
                $stmt->bindParam(':status', $status);
            }
            
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Database error: " . $e->getMessage();
        }
    }
    
    // New method for counting search results
    public function getTotalSearchResults($searchTerm, $status) {
        $allowed_status = ["Pending", "Confirmed", "Canceled", "Rejected", "Completed", "All"];
        $status = in_array($status, $allowed_status) ? $status : "";
        $status_condition = ($status == "All") ? "" : " AND b.status = :status";
        
        try {
            $stmt = $this->conn->prepare("
                SELECT COUNT(*) as total
                FROM bookings b
                JOIN users u ON b.user_id = u.user_id
                WHERE is_rebooking = 0 AND is_rebooked = 0
                AND (
                    CONCAT(u.first_name, ' ', u.last_name) LIKE :search
                    OR u.contact_number LIKE :search
                    OR b.destination LIKE :search
                    OR b.pickup_point LIKE :search
                )
                $status_condition
            ");
            
            $searchParam = "%" . $searchTerm . "%";
            $stmt->bindParam(':search', $searchParam);
            
            if ($status != "All") {
                $stmt->bindParam(':status', $status);
            }
            
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }
    
    // New method for getting unpaid bookings
    public function getUnpaidBookings($page = 1, $limit = 10) {
        // Calculate offset for pagination
        $offset = ($page - 1) * $limit;
        
        try {
            $stmt = $this->conn->prepare("
                SELECT b.booking_id, b.user_id, CONCAT(u.first_name, ' ', u.last_name) AS client_name, 
                u.contact_number, b.destination, b.pickup_point, b.date_of_tour, b.end_of_tour, 
                b.number_of_days, b.number_of_buses, b.status, b.payment_status, c.total_cost, b.balance
                FROM bookings b
                JOIN users u ON b.user_id = u.user_id
                JOIN booking_costs c ON b.booking_id = c.booking_id
                WHERE is_rebooking = 0 AND is_rebooked = 0
                AND (b.payment_status = 'Unpaid' OR b.payment_status = 'Partially Paid')
                ORDER BY b.booking_id DESC
                LIMIT :limit OFFSET :offset
            ");
            
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Database error: " . $e->getMessage();
        }
    }
    
    // New method for counting total unpaid bookings
    public function getTotalUnpaidBookings() {
        try {
            $stmt = $this->conn->prepare("
                SELECT COUNT(*) as total
                FROM bookings b
                WHERE is_rebooking = 0 AND is_rebooked = 0
                AND (b.payment_status = 'Unpaid' OR b.payment_status = 'Partially Paid')
            ");
            
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'];
        } catch (PDOException $e) {
            return 0;
        }
    }
    
    // New method for getting all bookings for export
    public function getAllBookingsForExport($status) {
        $allowed_status = ["Pending", "Confirmed", "Canceled", "Rejected", "Completed", "All"];
        $status = in_array($status, $allowed_status) ? $status : "";
        $status_condition = ($status == "All") ? "" : " AND b.status = :status";
        
        try {
            $stmt = $this->conn->prepare("
                SELECT b.booking_id, b.user_id, CONCAT(u.first_name, ' ', u.last_name) AS client_name, 
                u.contact_number, u.email, b.destination, b.pickup_point, 
                b.date_of_tour, b.end_of_tour, b.number_of_days, b.number_of_buses, 
                b.status, b.payment_status, c.total_cost, b.balance
                FROM bookings b
                JOIN users u ON b.user_id = u.user_id
                JOIN booking_costs c ON b.booking_id = c.booking_id
                WHERE b.is_rebooking = 0 AND b.is_rebooked = 0
                $status_condition
                ORDER BY b.booking_id DESC
            ");
            
            if ($status != "All") {
                $stmt->bindParam(':status', $status);
            }
            
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
    
    // New method for creating bookings by admin
    public function createBookingByAdmin($data) {
        try {
            $this->conn->beginTransaction();
            
            // Check if client already exists based on email
            $existingUserId = null;
            $stmt = $this->conn->prepare("SELECT user_id FROM users WHERE email = :email LIMIT 1");
            $stmt->execute([":email" => $data['email']]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result) {
                $existingUserId = $result['user_id'];
            }
            
            // Create or update user record
            if ($existingUserId) {
                // Update existing user
                $stmt = $this->conn->prepare("
                    UPDATE users SET 
                    first_name = :first_name,
                    last_name = :last_name,
                    contact_number = :contact_number,
                    address = :address
                    WHERE user_id = :user_id
                ");
                
                // Split the client name into first and last name
                $nameParts = explode(" ", $data['client_name'], 2);
                $firstName = $nameParts[0];
                $lastName = isset($nameParts[1]) ? $nameParts[1] : "";
                
                $stmt->execute([
                    ":first_name" => $firstName,
                    ":last_name" => $lastName,
                    ":contact_number" => $data['contact_number'],
                    ":address" => $data['address'] ?? '',
                    ":user_id" => $existingUserId
                ]);
                
                $userId = $existingUserId;
            } else {
                // Create new user
                $stmt = $this->conn->prepare("
                    INSERT INTO users (
                        first_name, last_name, email, contact_number, address,
                        role, created_at, status, created_by
                    ) VALUES (
                        :first_name, :last_name, :email, :contact_number, :address,
                        'Client', NOW(), 'Active', 'Admin'
                    )
                ");
                
                // Split the client name into first and last name
                $nameParts = explode(" ", $data['client_name'], 2);
                $firstName = $nameParts[0];
                $lastName = isset($nameParts[1]) ? $nameParts[1] : "";
                
                $stmt->execute([
                    ":first_name" => $firstName,
                    ":last_name" => $lastName,
                    ":email" => $data['email'],
                    ":contact_number" => $data['contact_number'],
                    ":address" => $data['address'] ?? ''
                ]);
                
                $userId = $this->conn->lastInsertId();
            }
            
            // Create booking record
            $stmt = $this->conn->prepare("
                INSERT INTO bookings (
                    user_id, destination, pickup_point, date_of_tour, end_of_tour,
                    number_of_days, number_of_buses, status, payment_status, 
                    is_rebooking, is_rebooked, created_at, booked_at, balance,
                    estimated_pax, notes, created_by
                ) VALUES (
                    :user_id, :destination, :pickup_point, :date_of_tour, :end_of_tour,
                    :number_of_days, :number_of_buses, :status, :payment_status,
                    0, 0, NOW(), NOW(), :balance, :estimated_pax, :notes, :created_by
                )
            ");
            
            // Set status based on data or default to confirmed for admin-created bookings
            $status = isset($data['status']) ? $data['status'] : 'Confirmed';
            $paymentStatus = isset($data['payment_status']) ? $data['payment_status'] : 'Unpaid';
            
            $stmt->execute([
                ":user_id" => $userId,
                ":destination" => $data['destination'],
                ":pickup_point" => $data['pickup_point'],
                ":date_of_tour" => $data['date_of_tour'],
                ":end_of_tour" => $data['end_of_tour'] ?? null,
                ":number_of_days" => $data['number_of_days'],
                ":number_of_buses" => $data['number_of_buses'],
                ":status" => $status,
                ":payment_status" => $paymentStatus,
                ":balance" => $data['total_cost'] ?? 0,
                ":estimated_pax" => $data['estimated_pax'] ?? 0,
                ":notes" => $data['notes'] ?? '',
                ":created_by" => $data['created_by'] ?? 'admin'
            ]);
            
            $bookingId = $this->conn->lastInsertId();
            
            // Create booking cost record
            $stmt = $this->conn->prepare("
                INSERT INTO booking_costs (
                    booking_id, gross_price, total_cost, discount, calculated_at
                ) VALUES (
                    :booking_id, :gross_price, :total_cost, :discount, NOW()
                )
            ");
            
            $totalCost = (float)$data['total_cost'];
            $discount = (float)($data['discount'] ?? 0);
            
            // Calculate gross price (total before discount)
            $grossPrice = $totalCost;
            if ($discount > 0) {
                $grossPrice = $totalCost / (1 - ($discount / 100));
            }
            
            $stmt->execute([
                ":booking_id" => $bookingId,
                ":gross_price" => $grossPrice,
                ":total_cost" => $totalCost,
                ":discount" => $discount
            ]);
            
            // If stops are provided, insert them
            if (isset($data['stops']) && !empty($data['stops'])) {
                $stmt = $this->conn->prepare("
                    INSERT INTO booking_stops (
                        booking_id, location, stop_order
                    ) VALUES (
                        :booking_id, :location, :stop_order
                    )
                ");
                
                foreach ($data['stops'] as $index => $stopLocation) {
                    $stmt->execute([
                        ":booking_id" => $bookingId,
                        ":location" => $stopLocation,
                        ":stop_order" => $index + 1
                    ]);
                }
            }
            
            // If initial payment is provided, record it
            if (isset($data['initial_payment']) && !empty($data['initial_payment'])) {
                $amountPaid = (float)$data['initial_payment']['amount_paid'];
                $paymentMethod = $data['initial_payment']['payment_method'];
                $paymentReference = $data['initial_payment']['payment_reference'] ?? 'Admin recorded';
                
                $stmt = $this->conn->prepare("
                    INSERT INTO payments (
                        booking_id, user_id, amount, payment_method,
                        reference_number, proof_of_payment, status, payment_date, created_at
                    ) VALUES (
                        :booking_id, :user_id, :amount, :payment_method,
                        :reference_number, :proof_of_payment, 'Confirmed', NOW(), NOW()
                    )
                ");
                
                $stmt->execute([
                    ":booking_id" => $bookingId,
                    ":user_id" => $userId,
                    ":amount" => $amountPaid,
                    ":payment_method" => $paymentMethod,
                    ":reference_number" => $paymentReference,
                    ":proof_of_payment" => 'Admin created'
                ]);
                
                // Update payment status and balance
                $balance = $totalCost - $amountPaid;
                $newPaymentStatus = 'Unpaid';
                
                if ($balance <= 0) {
                    $newPaymentStatus = 'Paid';
                    $balance = 0;
                } elseif ($amountPaid > 0) {
                    $newPaymentStatus = 'Partially Paid';
                }
                
                $stmt = $this->conn->prepare("
                    UPDATE bookings SET 
                    payment_status = :payment_status,
                    balance = :balance,
                    amount_paid = :amount_paid
                    WHERE booking_id = :booking_id
                ");
                
                $stmt->execute([
                    ":payment_status" => $newPaymentStatus,
                    ":balance" => $balance,
                    ":amount_paid" => $amountPaid,
                    ":booking_id" => $bookingId
                ]);
            }
            
            // If booking is confirmed, set confirmed_at
            if ($status === 'Confirmed') {
                $stmt = $this->conn->prepare("
                    UPDATE bookings SET 
                    confirmed_at = NOW()
                    WHERE booking_id = :booking_id
                ");
                
                $stmt->execute([":booking_id" => $bookingId]);
                
                // Add notification for client
                $clientMessage = "Your booking to " . $data['destination'] . " has been created and confirmed by admin.";
                $this->clientNotificationModel->addNotification($userId, "booking_created", $clientMessage, $bookingId);
            }
            
            $this->conn->commit();
            
            return [
                'success' => true,
                'booking_id' => $bookingId,
                'message' => 'Booking created successfully'
            ];
            
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ];
        }
    }
}
?>