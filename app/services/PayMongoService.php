<?php
require_once __DIR__ . '/../../PayMongo/src/PayMongoGCash.php';
require_once __DIR__ . '/../models/client/BookingModel.php';
require_once __DIR__ . '/../models/admin/NotificationModel.php';

use GCashGateway\PayMongoGCash;

class PayMongoService {
    private $payMongoGateway;
    private $config;
    private $bookingModel;
    private $notificationModel;
    
    public function __construct() {
        $this->config = require __DIR__ . '/../../PayMongo/config.php';
        $this->payMongoGateway = new PayMongoGCash($this->config);
        $this->bookingModel = new Booking();
        $this->notificationModel = new NotificationModel();
    }
    
    /**
     * Create a PayMongo checkout session for a booking payment
     * 
     * @param int $booking_id
     * @param int $user_id
     * @param float $amount
     * @param string $description
     * @return array
     */
    public function createCheckoutSession($booking_id, $user_id, $amount, $description = null) {
        try {
            // Get booking details for description
            $booking = $this->bookingModel->getBooking($booking_id, $user_id);
            if (!$booking) {
                return ['success' => false, 'message' => 'Booking not found'];
            }
            
            // Create description if not provided
            if (!$description) {
                $description = "Payment for booking to " . $booking['destination'] . " (Booking #" . $booking_id . ")";
            }
            
            // Create success and cancel URLs with booking context
            $baseUrl = $this->config['app']['url'];
            $successUrl = $baseUrl . "/paymongo/success?booking_id=" . $booking_id . "&session_id={CHECKOUT_SESSION_ID}";
            $cancelUrl = $baseUrl . "/paymongo/cancel?booking_id=" . $booking_id . "&session_id={CHECKOUT_SESSION_ID}";
            
            // Create checkout session
            $response = $this->payMongoGateway->createCheckoutSession(
                $amount,
                $description,
                $successUrl,
                $cancelUrl
            );
            
            if (isset($response['data'])) {
                // Store the checkout session info in database
                $this->storePaymentRecord($booking_id, $user_id, $amount, 'GCash', $response['data']['id']);
                
                return [
                    'success' => true,
                    'checkout_url' => $response['data']['attributes']['checkout_url'],
                    'checkout_session_id' => $response['data']['id'],
                    'amount' => $amount,
                    'description' => $description
                ];
            } else {
                return ['success' => false, 'message' => 'Failed to create checkout session'];
            }
            
        } catch (Exception $e) {
            error_log("PayMongo checkout session creation failed: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Handle successful payment callback
     * 
     * @param string $checkout_session_id
     * @param int $booking_id
     * @return array
     */
    public function handleSuccessfulPayment($checkout_session_id, $booking_id) {
        try {
            // Retrieve checkout session details from PayMongo
            $sessionData = $this->payMongoGateway->getCheckoutSession($checkout_session_id);
            
            if (!isset($sessionData['data'])) {
                return ['success' => false, 'message' => 'Invalid checkout session'];
            }
            
            $session = $sessionData['data']['attributes'];
            
            // Check if payment was successful
            if ($session['status'] !== 'paid') {
                return ['success' => false, 'message' => 'Payment not completed'];
            }
            
            // Find the payment record in our database
            $payment = $this->findPaymentByCheckoutSession($checkout_session_id);
            if (!$payment) {
                return ['success' => false, 'message' => 'Payment record not found'];
            }
            
            // Update payment status to confirmed
            $this->confirmPayment($payment['payment_id'], $sessionData);
            
            // Update booking payment status and balance
            $this->updateBookingPaymentStatus($booking_id);
            
            // Send notification to admin
            $booking = $this->bookingModel->getBooking($booking_id, $payment['user_id']);
            if ($booking) {
                $message = "PayMongo payment of PHP " . number_format($payment['amount'], 2) . 
                          " confirmed for booking #{$booking_id} to " . $booking['destination'];
                $this->notificationModel->addNotification("payment_confirmed", $message, $booking_id);
            }
            
            return [
                'success' => true,
                'message' => 'Payment confirmed successfully',
                'payment_id' => $payment['payment_id'],
                'amount' => $payment['amount']
            ];
            
        } catch (Exception $e) {
            error_log("PayMongo success handler failed: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Handle cancelled payment callback
     * 
     * @param string $checkout_session_id
     * @param int $booking_id
     * @return array
     */
    public function handleCancelledPayment($checkout_session_id, $booking_id) {
        try {
            // Find the payment record
            $payment = $this->findPaymentByCheckoutSession($checkout_session_id);
            if ($payment) {
                // Mark payment as cancelled/rejected
                $this->cancelPayment($payment['payment_id']);
            }
            
            return [
                'success' => true,
                'message' => 'Payment cancelled',
                'booking_id' => $booking_id
            ];
            
        } catch (Exception $e) {
            error_log("PayMongo cancel handler failed: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Handle PayMongo webhook events
     * 
     * @param string $payload
     * @param string $signature
     * @return array
     */
    public function handleWebhook($payload, $signature = '') {
        try {
            // Parse webhook event
            $event = $this->payMongoGateway->handleWebhook($payload, $signature);
            
            // Store webhook event for audit trail
            $this->storeWebhookEvent($event, $payload);
            
            // Process the event based on type
            $eventType = $event['data']['attributes']['type'] ?? 'unknown';
            
            switch ($eventType) {
                case 'checkout_session.payment.paid':
                    return $this->processPaymentPaidEvent($event);
                case 'checkout_session.payment.failed':
                    return $this->processPaymentFailedEvent($event);
                default:
                    return ['success' => true, 'message' => 'Event received but not processed'];
            }
            
        } catch (Exception $e) {
            error_log("PayMongo webhook handler failed: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Store payment record in database
     */
    private function storePaymentRecord($booking_id, $user_id, $amount, $payment_method, $checkout_session_id) {
        try {
            global $pdo;
            
            $stmt = $pdo->prepare("
                INSERT INTO payments (
                    booking_id, user_id, amount, payment_method, 
                    paymongo_checkout_session_id, payment_gateway, 
                    status, payment_date
                ) VALUES (
                    :booking_id, :user_id, :amount, :payment_method,
                    :checkout_session_id, 'paymongo',
                    'Pending', NOW()
                )
            ");
            
            $stmt->execute([
                ':booking_id' => $booking_id,
                ':user_id' => $user_id,
                ':amount' => $amount,
                ':payment_method' => $payment_method,
                ':checkout_session_id' => $checkout_session_id
            ]);
            
            return $pdo->lastInsertId();
            
        } catch (PDOException $e) {
            error_log("Failed to store payment record: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Find payment by checkout session ID
     */
    private function findPaymentByCheckoutSession($checkout_session_id) {
        try {
            global $pdo;
            
            $stmt = $pdo->prepare("
                SELECT * FROM payments 
                WHERE paymongo_checkout_session_id = :checkout_session_id
            ");
            $stmt->execute([':checkout_session_id' => $checkout_session_id]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Failed to find payment by checkout session: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Confirm payment in database
     */
    private function confirmPayment($payment_id, $sessionData) {
        try {
            global $pdo;
            
            // Extract payment intent ID and other details
            $paymentIntentId = $sessionData['data']['attributes']['payment_intent_id'] ?? null;
            $sourceId = $sessionData['data']['attributes']['payments'][0]['attributes']['source']['id'] ?? null;
            $referenceNumber = $sessionData['data']['attributes']['payments'][0]['attributes']['source']['attributes']['reference_number'] ?? null;
            
            $stmt = $pdo->prepare("
                UPDATE payments SET 
                    status = 'Confirmed',
                    paymongo_payment_intent_id = :payment_intent_id,
                    paymongo_source_id = :source_id,
                    paymongo_reference_number = :reference_number,
                    gateway_response = :gateway_response,
                    updated_at = NOW()
                WHERE payment_id = :payment_id
            ");
            
            $stmt->execute([
                ':payment_id' => $payment_id,
                ':payment_intent_id' => $paymentIntentId,
                ':source_id' => $sourceId,
                ':reference_number' => $referenceNumber,
                ':gateway_response' => json_encode($sessionData)
            ]);
            
        } catch (PDOException $e) {
            error_log("Failed to confirm payment: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Cancel payment in database
     */
    private function cancelPayment($payment_id) {
        try {
            global $pdo;
            
            $stmt = $pdo->prepare("
                UPDATE payments SET 
                    status = 'Rejected',
                    notes = 'Payment cancelled by user',
                    updated_at = NOW()
                WHERE payment_id = :payment_id
            ");
            
            $stmt->execute([':payment_id' => $payment_id]);
            
        } catch (PDOException $e) {
            error_log("Failed to cancel payment: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Update booking payment status and balance
     */
    private function updateBookingPaymentStatus($booking_id) {
        try {
            global $pdo;
            
            // Calculate total paid amount for this booking
            $stmt = $pdo->prepare("
                SELECT SUM(amount) AS total_paid 
                FROM payments 
                WHERE booking_id = :booking_id AND status = 'Confirmed'
            ");
            $stmt->execute([':booking_id' => $booking_id]);
            $total_paid = $stmt->fetch(PDO::FETCH_ASSOC)['total_paid'] ?? 0;
            
            // Get booking total cost
            $stmt = $pdo->prepare("
                SELECT c.total_cost 
                FROM bookings b 
                JOIN booking_costs c ON b.booking_id = c.booking_id 
                WHERE b.booking_id = :booking_id
            ");
            $stmt->execute([':booking_id' => $booking_id]);
            $total_cost = $stmt->fetch(PDO::FETCH_ASSOC)['total_cost'] ?? 0;
            
            // Calculate balance
            $balance = round($total_cost - $total_paid, 2);
            if ($balance < 0) $balance = 0;
            
            // Determine payment status
            $payment_status = "Unpaid";
            if ($total_paid > 0 && $total_paid < $total_cost) {
                $payment_status = "Partially Paid";
            } elseif ($total_paid >= $total_cost) {
                $payment_status = "Paid";
            }
            
            // Update booking
            $stmt = $pdo->prepare("
                UPDATE bookings SET 
                    payment_status = :payment_status,
                    balance = :balance,
                    status = CASE 
                        WHEN status = 'Pending' THEN 'Processing'
                        ELSE status
                    END
                WHERE booking_id = :booking_id
            ");
            
            $stmt->execute([
                ':payment_status' => $payment_status,
                ':balance' => $balance,
                ':booking_id' => $booking_id
            ]);
            
        } catch (PDOException $e) {
            error_log("Failed to update booking payment status: " . $e->getMessage());
            throw $e;
        }
    }
    
    /**
     * Store webhook event for audit trail
     */
    private function storeWebhookEvent($event, $payload) {
        try {
            global $pdo;
            
            $eventId = $event['data']['id'] ?? uniqid();
            $eventType = $event['data']['attributes']['type'] ?? 'unknown';
            $checkoutSessionId = $event['data']['attributes']['checkout_session_id'] ?? null;
            $paymentIntentId = $event['data']['attributes']['payment_intent_id'] ?? null;
            
            $stmt = $pdo->prepare("
                INSERT INTO paymongo_webhook_events (
                    event_id, event_type, checkout_session_id, 
                    payment_intent_id, raw_payload
                ) VALUES (
                    :event_id, :event_type, :checkout_session_id,
                    :payment_intent_id, :raw_payload
                )
            ");
            
            $stmt->execute([
                ':event_id' => $eventId,
                ':event_type' => $eventType,
                ':checkout_session_id' => $checkoutSessionId,
                ':payment_intent_id' => $paymentIntentId,
                ':raw_payload' => $payload
            ]);
            
        } catch (PDOException $e) {
            error_log("Failed to store webhook event: " . $e->getMessage());
        }
    }
    
    /**
     * Process payment paid webhook event
     */
    private function processPaymentPaidEvent($event) {
        try {
            $checkoutSessionId = $event['data']['attributes']['checkout_session_id'] ?? null;
            if (!$checkoutSessionId) {
                return ['success' => false, 'message' => 'No checkout session ID in event'];
            }
            
            // Find payment record
            $payment = $this->findPaymentByCheckoutSession($checkoutSessionId);
            if (!$payment) {
                return ['success' => false, 'message' => 'Payment record not found'];
            }
            
            // Confirm payment if not already confirmed
            if ($payment['status'] !== 'Confirmed') {
                $sessionData = $this->payMongoGateway->getCheckoutSession($checkoutSessionId);
                $this->confirmPayment($payment['payment_id'], $sessionData);
                $this->updateBookingPaymentStatus($payment['booking_id']);
            }
            
            return ['success' => true, 'message' => 'Payment confirmed via webhook'];
            
        } catch (Exception $e) {
            error_log("Failed to process payment paid event: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Process payment failed webhook event
     */
    private function processPaymentFailedEvent($event) {
        try {
            $checkoutSessionId = $event['data']['attributes']['checkout_session_id'] ?? null;
            if (!$checkoutSessionId) {
                return ['success' => false, 'message' => 'No checkout session ID in event'];
            }
            
            // Find payment record
            $payment = $this->findPaymentByCheckoutSession($checkoutSessionId);
            if ($payment) {
                $this->cancelPayment($payment['payment_id']);
            }
            
            return ['success' => true, 'message' => 'Payment marked as failed via webhook'];
            
        } catch (Exception $e) {
            error_log("Failed to process payment failed event: " . $e->getMessage());
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
    
    /**
     * Get payment details by checkout session ID
     */
    public function getPaymentByCheckoutSession($checkout_session_id) {
        return $this->findPaymentByCheckoutSession($checkout_session_id);
    }
    
    /**
     * Format amount for display
     */
    public static function formatAmount($amount) {
        return PayMongoGCash::formatAmount($amount);
    }
    
    /**
     * Validate amount
     */
    public static function validateAmount($amount) {
        return PayMongoGCash::validateAmount($amount);
    }
}
?>
