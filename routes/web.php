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

// admin payment management
require_once __DIR__ . "/../app/controllers/admin/PaymentManagementController.php";
$paymentManagementController = new PaymentManagementController();

// admin reports
require_once __DIR__ . "/../app/controllers/admin/ReportController.php";
$reportController = new ReportController();

// amdin user management
require_once __DIR__ . "/../app/controllers/admin/UserManagementController.php";
$userManagementController = new UserManagementController();

// admin settings
require_once __DIR__ . "/../app/controllers/admin/SettingsController.php";
$settingsController = new SettingsController();


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
    case "/home/booking-request":
        $bookingController->showBookingDetail();
        break;
    case "/home/get-booking-requests":
        $bookingController->getAllBookings();
        break;
    case "/request-rebooking":
        $bookingController->requestRebooking();
        break;
    case "/cancel-booking":
        $bookingController->cancelBooking();
        break;

    case "/get-booking":
        $bookingController->getBooking();
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

    case "/admin/monthly-booking-trends":
        $adminBookingController->monthlyBookingTrends();
        break;
        
    case "/admin/top-destinations":
        $adminBookingController->topDestinations();
        break;
        
    case "/admin/booking-status-distribution":
        $adminBookingController->bookingStatusDistribution();
        break;
        
    case "/admin/revenue-trends":
        $adminBookingController->revenueTrends();
        break;

    case "/admin/booking-requests":
        $adminBookingController->showBookingTable();
        break;
    case "/admin/confirm-booking":
        $adminBookingController->confirmBooking();
        break;
    case "/admin/reject-booking":
        $adminBookingController->rejectBooking();
        break;
    case "/admin/reject-rebooking":
        $adminBookingController->rejectRebooking();
        break;
    case "/admin/cancel-booking":
        $adminBookingController->cancelBooking();
        break;
    case "/admin/rebooking-requests":
        $adminBookingController->showReschedRequestTable();
        break;
    case "/admin/get-rebooking-requests":
        $adminBookingController->getRebookingRequests();
        break;
    case "/admin/confirm-rebooking-request":
        $adminBookingController->confirmRebookingRequest();
        break;
    case "/admin/booking-request":
    case "/admin/rebooking-request":
        $adminBookingController->showBookingDetail();
        break;
    case "/admin/get-booking":
        $adminBookingController->getBooking();
        break;

    case "/admin/get-users":
        $userManagementController->getUserListing();
        break;
        
    case "/admin/users":
        $userManagementController->showUserManagement();
        break;
        
    case "/admin/add-user":
        $userManagementController->addUser();
        break;
        
    case "/admin/update-user":
        $userManagementController->updateUser();
        break;
        
    case "/admin/delete-user":
        $userManagementController->deleteUser();
        break;

    case "/admin/get-user-details":
        $userManagementController->getUserDetails();
        break;

    // Payment Management Routes
    case "/admin/payment-management":
        $paymentManagementController->index();
        break;
    case "/admin/payments/get":
        $paymentManagementController->getPayments();
        break;
    case "/admin/payments/confirm":
        $paymentManagementController->confirmPayment();
        break;
    case "/admin/payments/reject":
        $paymentManagementController->rejectPayment();
        break;

    // Reports Module Routes
    case "/admin/reports":
        $reportController->index();
        break;
    case "/admin/reports/booking-summary":
        $reportController->getBookingSummary();
        break;
    case "/admin/reports/monthly-trend":
        $reportController->getMonthlyBookingTrend();
        break;
    case "/admin/reports/top-destinations":
        $reportController->getTopDestinations();
        break;
    case "/admin/reports/payment-methods":
        $reportController->getPaymentMethodDistribution();
        break;
    case "/admin/reports/cancellations":
        $reportController->getCancellationReport();
        break;
    case "/admin/reports/detailed-bookings":
        $reportController->getDetailedBookingList();
        break;
    case "/admin/reports/financial-summary":
        $reportController->getFinancialSummary();
        break;
    case "/admin/reports/export-bookings":
        $reportController->getDetailedBookingList(); // Reuse the same endpoint for CSV export
        break;

    // Settings Module Routes
    case "/admin/settings":
        $settingsController->index();
        break;
    case "/admin/get-all-settings":
        $settingsController->getAllSettings();
        break;
    case "/admin/get-settings-by-group":
        $settingsController->getSettingsByGroup();
        break;
    case "/admin/update-settings":
        $settingsController->updateSettings();
        break;
    case "/admin/add-setting":
        $settingsController->addSetting();
        break;
    case "/admin/delete-setting":
        $settingsController->deleteSetting();
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