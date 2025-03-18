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
    case (preg_match("#^/home/book/\d+$#", $request) ? true : false):
        $user_id = intval($segments[2]);
        $bookingController->isClientInfoExists($user_id);
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
    case "/admin/order-bookings":
        $adminBookingController->orderBookings();
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
        $adminBookingController->showBookingTable(); // ito muna
        break;
    case "/admin/booking-requests":
        $adminBookingController->showBookingTable();
        break;
    case "/admin/send-quote":
        $adminBookingController->sendQuote();
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