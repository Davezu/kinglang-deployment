<?php
require_once __DIR__ . "/../../../config/database.php";

class Booking {
    public $conn;
    
    public function __construct() {
        global $pdo;
        $this->conn = $pdo;
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

    public function requestBooking($date_of_tour, $destination, $pickup_point, $number_of_days, $number_of_buses, $user_id, $stops, $total_cost, $balance, $trip_distances, $addresses, $is_rebooking, $rebooking_id, $base_cost = null, $diesel_cost = null, $base_rate = null, $diesel_price = null, $total_distance = null, $pickup_time = null) {
        $end_of_tour = date("Y-m-d", strtotime($date_of_tour . " + $number_of_days days"));

        try {
            $available_buses = $this->findAvailableBuses($date_of_tour, $end_of_tour, $number_of_buses);

            if (!$available_buses) {
                return ["success" => false, "message" => "Not enough buses available."];
            }

            if ($is_rebooking && $this->bookingIsNotConfirmed($rebooking_id)) {
                $this->updateBooking($rebooking_id, $date_of_tour, $destination, $pickup_point, $number_of_days, $number_of_buses, $user_id, $stops, $total_cost, $balance, $trip_distances, $addresses, $base_cost, $diesel_cost, $base_rate, $diesel_price, $total_distance, $pickup_time);
                return ["success" => true, "message" => "Booking updated successfully!"];
            }

            $stmt = $this->conn->prepare("INSERT INTO bookings (date_of_tour, end_of_tour, destination, pickup_point, pickup_time, number_of_days, number_of_buses, user_id, balance, is_rebooking) VALUES (:date_of_tour, :end_of_tour, :destination, :pickup_point, :pickup_time, :number_of_days, :number_of_buses, :user_id, :balance, :is_rebooking)");
            $stmt->execute([
                ":date_of_tour" => $date_of_tour,
                ":end_of_tour" => $end_of_tour,
                ":destination" => $destination,
                ":pickup_point" => $pickup_point,
                ":pickup_time" => $pickup_time,
                ":number_of_days" => $number_of_days,       
                ":number_of_buses" => $number_of_buses,
                ":user_id" => $user_id,
                ":balance" => $balance,
                ":is_rebooking" => $is_rebooking,
            ]);

            $booking_id = $this->conn->lastInsertID(); // get the added booking id to insert it in booking buses table

            if ($is_rebooking) {
                $this->requestRebooking($rebooking_id, $booking_id, $_SESSION["user_id"]);
            }

            $stmt = $this->conn->prepare("INSERT INTO booking_costs (booking_id, base_rate, base_cost, diesel_price, diesel_cost, total_cost, total_distance) VALUES (:booking_id, :base_rate, :base_cost, :diesel_price, :diesel_cost, :total_cost, :total_distance)");
            $stmt->execute([
                ":booking_id" => $booking_id,
                ":base_rate" => $base_rate,
                ":base_cost" => $base_cost,
                ":diesel_price" => $diesel_price,
                ":diesel_cost" => $diesel_cost,
                ":total_cost" => $total_cost,
                ":total_distance" => $total_distance,
            ]);
            
            foreach ($available_buses as $bus_id) {
                $stmt = $this->conn->prepare("INSERT INTO booking_buses (booking_id, bus_id) VALUES (:booking_id, :bus_id)");
                $stmt->execute([":booking_id" => $booking_id, ":bus_id" => $bus_id]);
            }

            // insert stops into booking_stops
            foreach ($stops as $index => $stop) {            
                $stmt = $this->conn->prepare("INSERT INTO booking_stops (booking_id, location, stop_order) VALUES (:booking_id, :location, :stop_order)");
                $stmt->execute([
                    ":booking_id" => $booking_id,
                    ":location" => $stop,
                    ":stop_order" => $index + 1
                ]);
            }

            foreach ($trip_distances["rows"] as $i => $row) {
                $distance_value = $row["elements"][$i]["distance"]["value"] ?? 0; // in km
                $origin = $addresses[$i];
                $destination = $addresses[$i + 1] ?? $addresses[0]; // round trip fallback

                $stmt = $this->conn->prepare("INSERT INTO trip_distances (origin, destination, distance, booking_id) VALUES (:origin, :destination, :distance, :booking_id)");
                $stmt->execute([
                    ":origin" => $origin, 
                    ":destination" => $destination, 
                    ":distance" => $distance_value,     
                    ":booking_id" => $booking_id
                ]);
            }

            return ["success" => true, "message" => "Booking request submitted successfully!"];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Database error: " . $e->getMessage()];
        }
    }

    public function getBooking($booking_id, $user_id) {
        try {
            $stmt = $this->conn->prepare("
                SELECT b.*, u.first_name, u.last_name, u.contact_number, u.email, c.base_cost, c.diesel_cost, c.total_cost, c.base_rate, c.diesel_price
                FROM bookings b
                JOIN users u ON b.user_id = u.user_id
                JOIN booking_costs c ON b.booking_id = c.booking_id
                WHERE b.booking_id = :booking_id AND b.user_id = :user_id
            ");
            $stmt->execute([
                ":booking_id" => $booking_id,
                ":user_id" => $user_id
            ]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return "Database error";
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

    public function requestRebooking($booking_id, $rebooking_id, $user_id) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO rebooking_request (booking_id, rebooking_id, user_id) VALUES (:booking_id, :rebooking_id, :user_id)");
            $stmt->execute([":booking_id" => $booking_id, ":rebooking_id" => $rebooking_id, ":user_id" => $user_id]);
            return true;
        } catch (PDOException $e) {
            return "Databse error";
        }   
    }

    public function bookingExistsInReschedRequests($booking_id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM reschedule_requests WHERE booking_id = :booking_id AND status != 'Confirmed' ORDER BY booking_id DESC");
            $stmt->execute([":booking_id" => $booking_id]);
            $resched_request = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($resched_request) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            return "Database error: $e";
        }
    }

    public function bookingIsNotConfirmed($booking_id) { 
        try {
            $stmt = $this->conn->prepare("SELECT * FROM bookings WHERE booking_id = :booking_id");
            $stmt->execute([":booking_id" => $booking_id]);
            $booking = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($booking["status"] === "Confirmed") {
                return false;
            } else {
                return true;
            }
        } catch (PDOException $e) {
            return "Database error: $e";
        }
    }

    public function findAvailableBuses($date_of_tour, $end_of_tour, $number_of_buses) {
        try {
            $stmt = $this->conn->prepare("
                SELECT bus_id
                FROM buses
                WHERE status = 'active'
                AND bus_id NOT IN (
                    SELECT bb.bus_id
                    FROM booking_buses bb
                    JOIN bookings bo ON bb.booking_id = bo.booking_id
                    WHERE bo.status = 'confirmed' 
                    AND (
                        (bo.date_of_tour <= :date_of_tour AND bo.end_of_tour >= :date_of_tour)
                        OR
                        (bo.date_of_tour <= :end_of_tour    AND bo.end_of_tour >= :end_of_tour)
                        OR
                        (bo.date_of_tour >= :date_of_tour AND bo.end_of_tour <= :end_of_tour)
                    )
                )
                LIMIT :number_of_buses;
            ");
            $stmt->bindParam(":date_of_tour", $date_of_tour);
            $stmt->bindParam(":end_of_tour", $end_of_tour);
            $stmt->bindParam(":number_of_buses", $number_of_buses, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_COLUMN);       
        } catch (PDOException $e) {
            return "Database error: $e";
        }
    }

    public function getAllBookings($user_id, $status, $column, $order, $page = 1, $limit = 10) {
        $allowed_status = ["pending", "confirmed", "canceled", "rejected", "completed", "processing", "all"];
        $status = in_array($status, $allowed_status) ? $status : "all";
        $status = $status === "all" ? "" : " AND status = '$status'";

        $allowed_columns = ["destination", "date_of_tour", "end_of_tour", "number_of_days", "number_of_buses", "total_cost", "balance", "status", "payment_status"];
        $column = in_array($column, $allowed_columns) ? $column : "date_of_tour";
        $order = $order === "asc" ? "ASC" : "DESC";
        
        // Calculate offset for pagination
        $offset = ($page - 1) * $limit;
        
        try {
            // Get total count for pagination
            $countStmt = $this->conn->prepare("
                SELECT COUNT(*) FROM bookings 
                WHERE user_id = :user_id AND is_rebooking = 0 AND is_rebooked = 0
                $status
            ");
            $countStmt->execute([ ":user_id" => $user_id ]);
            $total_records = $countStmt->fetchColumn();
            
            // Get paginated results
            $stmt = $this->conn->prepare("
                SELECT b.booking_id, b.date_of_tour, b.end_of_tour, b.destination, b.pickup_point, b.number_of_days, b.number_of_buses, b.user_id, b.balance, b.status, b.payment_status, b.is_rebooking, b.is_rebooked, c.base_cost, c.diesel_cost, c.total_cost, c.base_rate, c.diesel_price
                FROM bookings b
                LEFT JOIN booking_costs c ON b.booking_id = c.booking_id
                WHERE b.user_id = :user_id AND b.is_rebooking = 0 AND b.is_rebooked = 0
                $status
                ORDER BY $column $order
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":limit", $limit, PDO::PARAM_INT);
            $stmt->bindParam(":offset", $offset, PDO::PARAM_INT);
            $stmt->execute();

            $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Calculate total pages
            $total_pages = ceil($total_records / $limit);
            
            return [
                "bookings" => $bookings ?: [],
                "total_records" => $total_records,
                "total_pages" => $total_pages,
                "current_page" => $page
            ];
        } catch (PDOException $e) {
            return "Database error: $e";
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
            $stmt->execute([":reason" => $reason, ":booking_id" => $booking_id, ":user_id" => $user_id, ":amount_refunded" => $amount_refunded, ":canceled_by" => "Client"]);

            return ["success" => true];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Database error: " . $e->getMessage()];
        }
    }

    public function updatePastBookings() {
        try {
            $stmt = $this->conn->prepare("UPDATE bookings SET status = 'Completed' WHERE end_of_tour < CURDATE() AND status != 'completed' AND balance = 0");
            $stmt->execute();
            return "Updated successfully!";
        } catch (PDOException $e) {
            return "Database error: $e";
        }
    }

    // payment
    public function isPaymentRequested($booking_id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM payments WHERE booking_id = :booking_id AND status = 'Pending'");
            $stmt->execute([":booking_id" => $booking_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ? true : false;
        } catch (PDOException $e) {
            return "Database error: $e";
        }
    }

    public function addPayment($booking_id, $user_id, $amount, $payment_method, $proof_of_payment = null) {
        try {
            if ($this->isPaymentRequested($booking_id)) {
                return ["success" => false, "message" => "Payment already requested for this booking."];
            }
            
            $stmt = $this->conn->prepare("INSERT INTO payments (booking_id, user_id, amount, payment_method, proof_of_payment) VALUES (:booking_id, :user_id, :amount, :payment_method, :proof_of_payment)");
            $stmt->execute([
                ":booking_id" => $booking_id,
                ":user_id" => $user_id,
                ":amount" => $amount,
                ":payment_method" => $payment_method,
                ":proof_of_payment" => $proof_of_payment
            ]);
            
            // Update booking status to Processing without changing payment status or balance
            $stmt = $this->conn->prepare("UPDATE bookings SET status = 'Processing' WHERE booking_id = :booking_id");
            $stmt->execute([":booking_id" => $booking_id]);
            
            return ["success" => true, "message" => "Payment request submitted successfully!"];
        } catch (PDOException $e) {
            return ["success" => false, "message" => "Database error: " . $e->getMessage()];
        }
    }

    public function updateBooking($rebooking_id, $date_of_tour, $destination, $pickup_point, $number_of_days, $number_of_buses, $user_id, $stops, $total_cost, $balance, $trip_distances, $addresses, $base_cost = null, $diesel_cost = null, $base_rate = null, $diesel_price = null, $total_distance = null, $pickup_time = null) {
        $end_of_tour = date("Y-m-d", strtotime($date_of_tour . " + $number_of_days days"));

        try {
            $available_buses = $this->findAvailableBuses($date_of_tour, $end_of_tour, $number_of_buses);

            if (!$available_buses) {
                return "Not enough buses available.";
            }

            // Update the booking
            $stmt = $this->conn->prepare("UPDATE bookings SET date_of_tour = :date_of_tour, end_of_tour = :end_of_tour, destination = :destination, pickup_point = :pickup_point, pickup_time = :pickup_time, number_of_days = :number_of_days, number_of_buses = :number_of_buses, balance = :balance WHERE booking_id = :booking_id AND user_id = :user_id");
            $stmt->execute([
                ":date_of_tour" => $date_of_tour,
                ":end_of_tour" => $end_of_tour,
                ":destination" => $destination,
                ":pickup_point" => $pickup_point,
                ":pickup_time" => $pickup_time,
                ":number_of_days" => $number_of_days,       
                ":number_of_buses" => $number_of_buses,
                ":balance" => $balance,
                ":booking_id" => $rebooking_id,
                ":user_id" => $user_id
            ]);

            // Update the booking costs
            $stmt = $this->conn->prepare("UPDATE booking_costs SET base_rate = :base_rate, base_cost = :base_cost, diesel_price = :diesel_price, diesel_cost = :diesel_cost, total_cost = :total_cost, total_distance = :total_distance WHERE booking_id = :booking_id");
            $stmt->execute([
                ":base_rate" => $base_rate,
                ":base_cost" => $base_cost,
                ":diesel_price" => $diesel_price,
                ":diesel_cost" => $diesel_cost,
                ":total_cost" => $total_cost,
                ":total_distance" => $total_distance,
                ":booking_id" => $rebooking_id
            ]);

            // Delete existing stops
            $stmt = $this->conn->prepare("DELETE FROM booking_stops WHERE booking_id = :booking_id");
            $stmt->execute([":booking_id" => $rebooking_id]);

            // Insert new stops
            foreach ($stops as $index => $stop) {            
                $stmt = $this->conn->prepare("INSERT INTO booking_stops (booking_id, location, stop_order) VALUES (:booking_id, :location, :stop_order)");
                $stmt->execute([
                    ":booking_id" => $rebooking_id,
                    ":location" => $stop,
                    ":stop_order" => $index + 1
                ]);
            }

            // Delete existing trip distances
            $stmt = $this->conn->prepare("DELETE FROM trip_distances WHERE booking_id = :booking_id");
            $stmt->execute([":booking_id" => $rebooking_id]);

            // Insert new trip distances
            foreach ($trip_distances["rows"] as $i => $row) {
                $distance_value = $row["elements"][$i]["distance"]["value"] ?? 0; // in km
                $origin = $addresses[$i];
                $destination = $addresses[$i + 1] ?? $addresses[0]; // round trip fallback

                $stmt = $this->conn->prepare("INSERT INTO trip_distances (origin, destination, distance, booking_id) VALUES (:origin, :destination, :distance, :booking_id)");
                $stmt->execute([
                    ":origin" => $origin, 
                    ":destination" => $destination, 
                    ":distance" => $distance_value,     
                    ":booking_id" => $rebooking_id
                ]);
            }

            // Delete existing booking buses
            $stmt = $this->conn->prepare("DELETE FROM booking_buses WHERE booking_id = :booking_id");
            $stmt->execute([":booking_id" => $rebooking_id]);

            // Insert new booking buses
            foreach ($available_buses as $bus_id) {
                $stmt = $this->conn->prepare("INSERT INTO booking_buses (booking_id, bus_id) VALUES (:booking_id, :bus_id)");
                $stmt->execute([":booking_id" => $rebooking_id, ":bus_id" => $bus_id]);
            }

            return "success";
        } catch (PDOException $e) {
            return "Database error: $e";
        }
    }

    
}


?>