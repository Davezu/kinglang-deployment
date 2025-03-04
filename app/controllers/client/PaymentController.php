<?php
require_once "../../../config/database.php";
require_once "../../models/client/PaymentModel.php";

class PaymentController {
    public $payment;

    public function __construct($db) {
        $this->payment = new PaymentModel($db);
    }

    public function addPayment($booking_id, $client_id, $amount, $payment_method) {
        return $this->payment->addPayment($booking_id, $client_id, $amount, $payment_method);
    }
}


if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $booking_id = $_POST["booking_id"];
    $client_id = $_POST["client_id"];
    $amount = $_POST["amount"];
    $payment_method = $_POST["payment_method"];

    $controller = new PaymentController($pdo);

    $result = $controller->addPayment($booking_id, $client_id, $amount, $payment_method);

    if ($result) {
        echo "Payment added successfully";
    } else {
        echo "Adding payment failed";
    }
}

?>