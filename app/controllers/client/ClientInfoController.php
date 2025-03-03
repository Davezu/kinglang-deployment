<?php
require_once "../../../config/database.php";
require_once "../../models/client/ClientInfoModel.php";

class ClientInfoController {
    private $info;

    public function __construct($db) {
        $this->info = new ClientInfoModel($db);
    } 

    public function addClient($first_name, $last_name, $address, $contact_number, $company_name) {
        return $this->info->addClient($first_name, $last_name, $address, $contact_number, $company_name);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["client_info"])) {
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $address = trim($_POST["address"]);
    $contact_number = trim($_POST["contact_number"]);
    $company_name = trim($_POST["company_name"]) ? trim($_POST["company_name"]) : "none";

    if (empty($first_name) || empty($last_name) || empty($address) || empty($contact_number)) {
        echo "Incomplete information";
        exit();
    }

    $controller = new ClientInfoController($pdo);
    $message = $controller->addClient($first_name, $last_name, $address, $contact_number, $company_name);

    if ($message === "Client info added successfully!") {
        header("Location: ../../views/client/booking.php");
        exit();
    } else {
        echo "<script>alert('$message')</script>";
    }
}
?>