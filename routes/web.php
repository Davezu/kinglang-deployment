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

if (preg_match("/reset-password\/([a-zA-Z0-9]+)/", $request, $matches)) {
    $token = $matches[1];
    $clientAuthController->showResetForm($token);
    exit();
}

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
    case "/my-account":
        $clientAuthController->manageAccountForm();
        break;
    case "/get-client-information":
        $clientAuthController->getClientInformation();
        break;
    case "/update-client-information":
        $clientAuthController->updateClientInformation();
        break;
    case "/logout":
        $clientAuthController->logout();
        break;

    // forgot password
    case "/fogot-password":
        $clientAuthController->showForgotForm();
        break;  
    case "/send-reset-link":
        $clientAuthController->sendResetLink();
        break;
    case "/update-password":
        $clientAuthController->resetPassword();
        break;

    // bookings
    case "/home/contact":
        $bookingController->clientInfoForm();
        break;
    case "/contact/submit":
        $bookingController->addClient();
        break;
    case "/get-available-buses":
        $bookingController->findAvailableBuses();
        break;
    case "/home/book":
        $bookingController->bookingForm();
        break;


    case "/get-address":
        $bookingController->getAddress();
        break;   
    case "/get-distance":
        $bookingController->getDistance();
        break;
    case "/get-route":
        $bookingController->processCoordinates();
        break;
    case "/get-total-cost":
        $bookingController->getTotalCost();
        break;
        
    case "/request-booking":
        $bookingController->requestBooking();
        break;
    case "/home/booking-requests":
        $bookingController->showBookingRequestTable();
        break;
    case "/home/get-booking-requests":
        $bookingController->getAllBookings();
        break;
    case "/request-resched-booking":
        $bookingController->requestReschedBooking();
        break;

    case "/payment/process":
        $bookingController->addPayment();
        break;

    // admin
    case "/admin/bookings":
        $adminBookingController->getAllBookings();
        $bookingController->updatePastBookings();
        break;
    case "/admin/login":
        $adminAuthController->loginForm();
        break;
    case "/admin/submit-login":
        $adminAuthController->login();
        break;
    case "/admin/logout":
        $adminAuthController->logout();
        break;
    case "/admin/dashboard":
        $adminAuthController->adminDashBoard();
        break;
    case "/admin/summary-metrics":
        $adminBookingController->summaryMetrics();
        break;
    case "/admin/payment-method-data":
        $adminBookingController->paymentMethodChart();
        break;


    case "/admin/booking-requests":
        $adminBookingController->showBookingTable();
        break;
    case "/admin/confirm-booking":
        $adminBookingController->confirmBooking();
        break;
    case "/admin/resched-requests":
        $adminBookingController->showReschedRequestTable();
        break;
    case "/admin/get-resched-requests":
        $adminBookingController->getReschedRequests();
        break;
    case "/admin/confirm-resched-request":
        $adminBookingController->confirmReschedRequest();
        break;


    case "/favicon.ico":
        http_response_code(204); // No Content (prevents errors)
        exit();
        break;

    default:
        require_once __DIR__ . "/../404.php";
        break;
}

?>