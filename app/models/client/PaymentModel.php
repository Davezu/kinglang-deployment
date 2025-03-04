<?php
class PaymentModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addPayment($booking_id, $client_id, $amount, $payment_method) {
        try {
            $stmt = $this->conn->prepare("INSERT INTO payments (booking_id, client_id, amount, payment_method) VALUES (:booking_id, :client_id, :amount, :payment_method)");
            $stmt->execute([
                ":booking_id" => $booking_id,
                ":client_id" => $client_id,
                ":amount" => $amount,
                ":payment_method" => $payment_method
            ]);
            $this->updatePaymentStatus($booking_id);
            return true;
        } catch (PDOException $e) {
            return "Database error";
        }
    }

    public function updatePaymentStatus($booking_id) {
        try {
            $stmt = $this->conn->prepare("SELECT SUM(amount) AS total_paid FROM payments WHERE booking_id = :booking_id");
            $stmt->execute([":booking_id" => $booking_id]);
            $total_paid = $stmt->fetch(PDO::FETCH_ASSOC)["total_paid"] ?? 0;

            $stmt = $this->conn->prepare("SELECT total_cost FROM bookings WHERE booking_id = :booking_id");
            $stmt->execute([":booking_id" => $booking_id]);
            $total_cost = $stmt->fetch(PDO::FETCH_ASSOC)["total_cost"] ?? 0;

            $new_status = "Unpaid";
            if ($total_paid > 0 && $total_paid < $total_cost) {
                $new_status = "partially paid";
            } elseif ($total_paid >= $total_cost) {
                $new_status = "paid";
            }

            $stmt = $this->conn->prepare("UPDATE bookings SET payment_status = :payment_status, status = 'confirmed' WHERE booking_id = :booking_id");
            $stmt->execute([
                ":payment_status" => $new_status,
                ":booking_id" => $booking_id 
            ]);

        } catch (PDOException $e) {
            return "Database error";
        }
    }
}

?>