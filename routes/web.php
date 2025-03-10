<?php
// client
require_once __DIR__ . "/../app/controllers/client/AuthController.php";
$clientAuthController = new ClientAuthController();

require_once __DIR__ . "/../app/controllers/client/BookingController.php";
$bookingController = new BookingController();


// admin
require_once __DIR__ . "/../app/controllers/admin/BookingManagementController.php";
$adminBookingController = new BookingManagementController();

require_once __DIR__ . "/../app/controllers/admin/AuthController.php";
$adminAuthController = new AuthController();

$request = $_SERVER["REQUEST_URI"];

$segments = explode("/", trim($request, "/"));

switch ($request) {
    // user
    case "/":
    case "/home":
        require_once __DIR__ . "/../public/home.php";
        break;
    case "/home/login":
        $clientAuthController->loginForm();
        break;
    case "/home/signup":
        $clientAuthController->signupForm();
        break;
    case "/client/login":
        $clientAuthController->login();
        break;
    case "/client/signup":
        $clientAuthController->signup();
        break;
    case "/client/home":
        require_once __DIR__ . "/../app/views/client/home.php";
        break;
    case "/logout":
        $clientAuthController->logout();
        break;

    // bookings
    case "/home/contact":
        $bookingController->clientInfoForm();
        break;
    case "/contact/submit":
        $bookingController->addClient();
        break;
    case "/home/book":
        $bookingController->bookingForm();
        $bookingController->updatePastBookings();
        break;
    case (preg_match("#^/home/book/\d+$#", $request) ? true : false):
        $user_id = intval($segments[2]);
        $bookingController->isClientInfoExists($user_id);
        break;
    case "/request-booking":
        $bookingController->requestBooking();
        break;
    case (preg_match("#^/home/bookings/(\d+)(?:/([a-zA-Z0-9]+))?$#", $request, $matches) ? true : false):
        $user_id = intval($matches[1]); 
        $client_id = $bookingController->getClientID($user_id);
        $status = $matches[2] ?? "";
        $bookingController->getAllBookings($client_id, $status);
        break;

    case "/payment/process":
        $bookingController->addPayment();
        break;

    // admin
    case "/admin/bookings":
        $adminBookingController->getAllBookings();
        break;
    case "/admin/login":
        $adminAuthController->loginForm();
        break;
    case "/admin/login/process":
        $adminAuthController->login();
        break;
    case "/admin/logout":
        $adminAuthController->logout();
        break;
    case "/admin/dashboard":
        // $adminAuthController->adminDashBoard(); 
        $adminBookingController->getAllBookings(); // ito muna
        break;
    case "/send-quote":
        $adminBookingController->sendQuote();
        break;
        


    default:
        require_once __DIR__ . "/../404.php";
        break;
}

?>