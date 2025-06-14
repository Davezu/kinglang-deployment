<?php
// Define controller classes
$controllerClasses = [
    'client' => [
        'AuthController' => __DIR__ . "/../app/controllers/client/AuthController.php",
        'BookingController' => __DIR__ . "/../app/controllers/client/BookingController.php",
        'NotificationsController' => __DIR__ . "/../app/controllers/client/NotificationsController.php",
    ],
    'admin' => [
        'BookingManagementController' => __DIR__ . "/../app/controllers/admin/BookingManagementController.php",
        'AuthController' => __DIR__ . "/../app/controllers/admin/AuthController.php",   
        'PaymentManagementController' => __DIR__ . "/../app/controllers/admin/PaymentManagementController.php",
        'ReportController' => __DIR__ . "/../app/controllers/admin/ReportController.php",
        'UserManagementController' => __DIR__ . "/../app/controllers/admin/UserManagementController.php",
        'SettingsController' => __DIR__ . "/../app/controllers/admin/SettingsController.php",
        'NotificationsController' => __DIR__ . "/../app/controllers/admin/NotificationsController.php",
        'AuditTrailController' => __DIR__ . "/../app/controllers/admin/AuditTrailController.php",
        'BusManagementController' => __DIR__ . "/../app/controllers/admin/BusManagementController.php",
        'DriverManagementController' => __DIR__ . "/../app/controllers/admin/DriverManagementController.php",
    ]
];

// Create lazy loading controllers
$controllers = [];

// Get current request
$request = $_SERVER["REQUEST_URI"];

// Remove query string for route matching
$requestPath = parse_url($request, PHP_URL_PATH);

// Special case for reset password
if (preg_match("/reset-password\/([a-zA-Z0-9]+)/", $requestPath, $matches)) {
    $token = $matches[1];
    require_once $controllerClasses['client']['AuthController'];
    $clientAuthController = new ClientAuthController();
    $clientAuthController->showResetForm($token);
    exit();
}

// Determine which controller we need for this request
$controllerType = null;
$controllerName = null;

if (strpos($requestPath, '/admin') === 0) {
    $controllerType = 'admin';
} else {
    $controllerType = 'client';
}

// Now handle the route
switch ($requestPath) {
    // user
    case "/":
    case "/home":
        require_once __DIR__ . "/../public/home.php";
        break;
    case "/home/login":
        require_once $controllerClasses['client']['AuthController'];
        $controller = new ClientAuthController();
        $controller->loginForm();
        break;
    case "/home/signup":
        require_once $controllerClasses['client']['AuthController'];
        $controller = new ClientAuthController();
        $controller->signupForm();
        break;
    case "/client/login":
        require_once $controllerClasses['client']['AuthController'];
        $controller = new ClientAuthController();
        $controller->login();
        break;
    case "/client/signup":
        require_once $controllerClasses['client']['AuthController'];
        $controller = new ClientAuthController();
        $controller->signup();
        break;
    case "/client/home":
        require_once __DIR__ . "/../app/views/client/home.php";
        break;
    case "/my-account":
        require_once $controllerClasses['client']['AuthController'];
        $controller = new ClientAuthController();
        $controller->manageAccountForm();
        break;
    case "/get-client-information":
        require_once $controllerClasses['client']['AuthController'];
        $controller = new ClientAuthController();
        $controller->getClientInformation();
        break;
    case "/update-client-information":
        require_once $controllerClasses['client']['AuthController'];
        $controller = new ClientAuthController();
        $controller->updateClientInformation();
        break;
    case "/logout":
        require_once $controllerClasses['client']['AuthController'];
        $controller = new ClientAuthController();
        $controller->logout();
        break;
    case "/update-client-password":
        require_once $controllerClasses['client']['AuthController'];
        $controller = new ClientAuthController();
        $controller->updateClientPassword();
        break;

    // forgot password
    case "/fogot-password":
        require_once $controllerClasses['client']['AuthController'];
        $controller = new ClientAuthController();
        $controller->showForgotForm();
        break;  
    case "/send-reset-link":
        require_once $controllerClasses['client']['AuthController'];
        $controller = new ClientAuthController();
        $controller->sendResetLink();
        break;
    case "/update-password":
        require_once $controllerClasses['client']['AuthController'];
        $controller = new ClientAuthController();
        $controller->resetPassword();
        break;

    // bookings
    case "/home/contact":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->clientInfoForm();
        break;
    case "/contact/submit":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->addClient();
        break;
    case "/get-available-buses":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->findAvailableBuses();
        break;
    case "/home/book":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->bookingForm();
        break;

    case "/get-address":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->getAddress();
        break;   
    case "/get-distance":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->getDistance();
        break;
    case "/get-route":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->processCoordinates();
        break;
    case "/get-bus-availability":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->getAvailableBuses();
        break;
    case "/get-total-cost":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->getTotalCost();
        break;
        
    case "/request-booking":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->requestBooking();
        break;
    case "/home/booking-requests":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->showBookingRequestTable();
        break;
    case "/home/booking-request":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->showBookingDetail();
        break;
    case "/home/get-booking-requests":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->getAllBookings();
        break;
    case "/request-rebooking":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->requestRebooking();
        break;
    case "/cancel-booking":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->cancelBooking();
        break;

    case "/get-booking":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->getBooking();
        break;

    case "/home/booking-statistics":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->getBookingStatistics();
        break;
        
    case "/home/get-booking-details":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->getBookingDetails();
        break;
    case "/home/get-payment-settings":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->getPaymentSettings();
        break;
    case "/home/calendar-events":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->getCalendarEvents();
        break;
        
    case "/home/export-bookings":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->exportBookings();
        break;
        
    case "/home/print-invoice":
    case preg_match('|^/home/print-invoice/([0-9]+)$|', $requestPath, $matches) ? $requestPath : "":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->printInvoice($matches[1] ?? null);
        break;

    case "/home/print-contract":
    case preg_match('|^/home/print-contract/([0-9]+)$|', $requestPath, $matches) ? $requestPath : "":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->printContract($matches[1] ?? null);
        break;

    case "/payment/process":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->addPayment();
        break;

    // admin routes
    case "/admin/bookings":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $adminController = new BookingManagementController();
        $adminController->getAllBookings();
        
        require_once $controllerClasses['client']['BookingController'];
        $clientController = new BookingController();
        $clientController->updatePastBookings();
        break;
    case "/admin/login":
        require_once $controllerClasses['admin']['AuthController'];
        $controller = new AuthController();
        $controller->loginForm();
        break;
    case "/admin/submit-login":
        require_once $controllerClasses['admin']['AuthController'];
        $controller = new AuthController();
        $controller->login();
        break;
    case "/admin/logout":
        require_once $controllerClasses['admin']['AuthController'];
        $controller = new AuthController();
        $controller->logout();
        break;
    case "/admin/dashboard":
        require_once $controllerClasses['admin']['AuthController'];
        $controller = new AuthController();
        $controller->adminDashBoard();
        break;
    case "/admin/summary-metrics":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->summaryMetrics();
        break;
    case "/admin/payment-method-data":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->paymentMethodChart();
        break;

    case "/admin/monthly-booking-trends":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->monthlyBookingTrends();
        break;
        
    case "/admin/top-destinations":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->topDestinations();
        break;
        
    case "/admin/booking-status-distribution":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->bookingStatusDistribution();
        break;
        
    case "/admin/revenue-trends":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->revenueTrends();
        break;

    case "/admin/booking-requests":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->showBookingTable();
        break;
    case "/admin/confirm-booking":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->confirmBooking();
        break;
    case "/admin/reject-booking":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->rejectBooking();
        break;
    case "/admin/reject-rebooking":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->rejectRebooking();
        break;
    case "/admin/cancel-booking":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->cancelBooking();
        break;
    case "/admin/rebooking-requests":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->showReschedRequestTable();
        break;
    case "/admin/get-rebooking-requests":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->getRebookingRequests();
        break;
    case "/admin/confirm-rebooking-request":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->confirmRebookingRequest();
        break;
    case "/admin/booking-request":
    case "/admin/rebooking-request":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->showBookingDetail();
        break;
    case "/admin/get-booking":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->getBooking();
        break;
    case "/admin/get-booking-details":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->getBookingDetails();
        break;
    case "/admin/print-invoice":
    case preg_match('|^/admin/print-invoice/([0-9]+)$|', $requestPath, $matches) ? $requestPath : "":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->printInvoice($matches[1] ?? null);
        break;

    case "/admin/print-contract":
    case preg_match('|^/admin/print-contract/([0-9]+)$|', $requestPath, $matches) ? $requestPath : "":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->printContract($matches[1] ?? null);
        break;

    case "/admin/booking-stats":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->getBookingStats();
        break;

    case "/admin/calendar-bookings":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->getCalendarBookings();
        break;

    case "/admin/search-bookings":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->searchBookings();
        break;

    case "/admin/unpaid-bookings":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->getUnpaidBookings();
        break;

    case "/admin/partially-paid-bookings":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->getPartiallyPaidBookings();
        break;

    case "/admin/export-bookings":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->exportBookings();
        break;

    case "/admin/create-booking":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->showCreateBookingForm();
        break;
        
    case "/admin/submit-booking":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->createBooking();
        break;

    case "/admin/get-address":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->getAddress();
        break;   
    case "/admin/get-distance":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->getDistance();
        break;
    case "/admin/get-route":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->processCoordinates();
        break;
    case "/admin/get-total-cost":
        require_once $controllerClasses['admin']['BookingManagementController'];
        $controller = new BookingManagementController();
        $controller->getTotalCost();
        break;

    case "/getDieselPrice":
        require_once $controllerClasses['client']['BookingController'];
        $controller = new BookingController();
        $controller->getDieselPrice();
        break;

    case "/admin/get-users":
        require_once $controllerClasses['admin']['UserManagementController'];
        $controller = new UserManagementController();
        $controller->getUserListing();
        break;
        
    case "/admin/users":
        require_once $controllerClasses['admin']['UserManagementController'];
        $controller = new UserManagementController();
        $controller->showUserManagement();
        break;
        
    case "/admin/add-user":
        require_once $controllerClasses['admin']['UserManagementController'];
        $controller = new UserManagementController();
        $controller->addUser();
        break;
        
    case "/admin/update-user":
        require_once $controllerClasses['admin']['UserManagementController'];
        $controller = new UserManagementController();
        $controller->updateUser();
        break;
        
    case "/admin/delete-user":
        require_once $controllerClasses['admin']['UserManagementController'];
        $controller = new UserManagementController();
        $controller->deleteUser();
        break;

    case "/admin/get-user-details":
        require_once $controllerClasses['admin']['UserManagementController'];
        $controller = new UserManagementController();
        $controller->getUserDetails();
        break;

    case "/admin/get-user-stats":
        require_once $controllerClasses['admin']['UserManagementController'];
        $controller = new UserManagementController();
        $controller->getUserStats();
        break;

    case "/admin/payment-management":
        require_once $controllerClasses['admin']['PaymentManagementController'];
        $controller = new PaymentManagementController();
        $controller->index();
        break;
    case "/admin/payments/get":
        require_once $controllerClasses['admin']['PaymentManagementController'];
        $controller = new PaymentManagementController();
        $controller->getPayments();
        break;
    case "/admin/payments/stats":
        require_once $controllerClasses['admin']['PaymentManagementController'];
        $controller = new PaymentManagementController();
        $controller->getPaymentStats();
        break;
    case "/admin/payments/view-proof":
        require_once $controllerClasses['admin']['PaymentManagementController'];
        $controller = new PaymentManagementController();
        $controller->viewProof();
        break;
    case "/admin/payments/confirm":
        require_once $controllerClasses['admin']['PaymentManagementController'];
        $controller = new PaymentManagementController();
        $controller->confirmPayment();
        break;
    case "/admin/payments/reject":
        require_once $controllerClasses['admin']['PaymentManagementController'];
        $controller = new PaymentManagementController();
        $controller->rejectPayment();
        break;
    case "/admin/payments/record-manual":
        require_once $controllerClasses['admin']['PaymentManagementController'];
        $controller = new PaymentManagementController();
        $controller->recordManualPayment();
        break;
    case "/admin/payments/search-bookings":
        require_once $controllerClasses['admin']['PaymentManagementController'];
        $controller = new PaymentManagementController();
        $controller->searchBookings();
        break;
    case "/admin/payments/search-clients":
        require_once $controllerClasses['admin']['PaymentManagementController'];
        $controller = new PaymentManagementController();
        $controller->searchClients();
        break;
    case "/admin/payments/get-booking-details":
        require_once $controllerClasses['admin']['PaymentManagementController'];
        $controller = new PaymentManagementController();
        $controller->getBookingDetails();
        break;

    case "/admin/reports":
        require_once $controllerClasses['admin']['ReportController'];
        $controller = new ReportController();
        $controller->index();
        break;
    case "/admin/reports/booking-summary":
        require_once $controllerClasses['admin']['ReportController'];
        $controller = new ReportController();
        $controller->getBookingSummary();
        break;
    case "/admin/reports/monthly-trend":
        require_once $controllerClasses['admin']['ReportController'];
        $controller = new ReportController();
        $controller->getMonthlyBookingTrend();
        break;
    case "/admin/reports/top-destinations":
        require_once $controllerClasses['admin']['ReportController'];
        $controller = new ReportController();
        $controller->getTopDestinations();
        break;
    case "/admin/reports/payment-methods":
        require_once $controllerClasses['admin']['ReportController'];
        $controller = new ReportController();
        $controller->getPaymentMethodDistribution();
        break;
    case "/admin/reports/cancellations":
        require_once $controllerClasses['admin']['ReportController'];
        $controller = new ReportController();
        $controller->getCancellationReport();
        break;
    case "/admin/reports/detailed-bookings":
        require_once $controllerClasses['admin']['ReportController'];
        $controller = new ReportController();
        $controller->getDetailedBookingList();
        break;
    case "/admin/reports/financial-summary":
        require_once $controllerClasses['admin']['ReportController'];
        $controller = new ReportController();
        $controller->getFinancialSummary();
        break;
    case "/admin/reports/export-bookings":
        require_once $controllerClasses['admin']['ReportController'];
        $controller = new ReportController();
        $controller->getDetailedBookingList();
        break;
    case "/admin/bus-management":
        require_once $controllerClasses['admin']['BusManagementController'];
        $controller = new BusManagementController();
        $controller->showBusManagement();
        break;
    case "/admin/get-all-buses":
        require_once $controllerClasses['admin']['BusManagementController'];
        $controller = new BusManagementController();
        $controller->getAllBuses();
        break;
    case "/admin/add-bus":
        require_once $controllerClasses['admin']['BusManagementController'];
        $controller = new BusManagementController();
        $controller->addBus();
        break;
    case "/admin/update-bus":
        require_once $controllerClasses['admin']['BusManagementController'];
        $controller = new BusManagementController();
        $controller->updateBus();
        break;
    case "/admin/delete-bus":
        require_once $controllerClasses['admin']['BusManagementController'];
        $controller = new BusManagementController();
        $controller->deleteBus();
        break;
    case "/admin/get-bus-availability":
        require_once $controllerClasses['admin']['BusManagementController'];
        $controller = new BusManagementController();
        $controller->getBusAvailability();
        break;
    case "/admin/get-bus-schedule":
        require_once $controllerClasses['admin']['BusManagementController'];
        $controller = new BusManagementController();
        $controller->getBusSchedule();
        break;
    case "/admin/get-bus-stats":
        require_once $controllerClasses['admin']['BusManagementController'];
        $controller = new BusManagementController();
        $controller->getBusStats();
        break;

    case "/admin/driver-management":
        require_once $controllerClasses['admin']['DriverManagementController'];
        $controller = new DriverManagementController();
        $controller->showDriverManagement();
        break;
    case "/admin/api/drivers/all":
        require_once $controllerClasses['admin']['DriverManagementController'];
        $controller = new DriverManagementController();
        $controller->getAllDrivers();
        break;
    case "/admin/api/drivers/get":
        require_once $controllerClasses['admin']['DriverManagementController'];
        $controller = new DriverManagementController();
        $controller->getDriverById();
        break;
    case "/admin/api/drivers/add":
        require_once $controllerClasses['admin']['DriverManagementController'];
        $controller = new DriverManagementController();
        $controller->addDriver();
        break;
    case "/admin/api/drivers/update":
        require_once $controllerClasses['admin']['DriverManagementController'];
        $controller = new DriverManagementController();
        $controller->updateDriver();
        break;
    case "/admin/api/drivers/delete":
        require_once $controllerClasses['admin']['DriverManagementController'];
        $controller = new DriverManagementController();
        $controller->deleteDriver();
        break;
    case "/admin/api/drivers/statistics":
        require_once $controllerClasses['admin']['DriverManagementController'];
        $controller = new DriverManagementController();
        $controller->getDriverStatistics();
        break;
    case "/admin/api/drivers/most-active":
        require_once $controllerClasses['admin']['DriverManagementController'];
        $controller = new DriverManagementController();
        $controller->getMostActiveDrivers();
        break;
    case "/admin/api/drivers/expiring-licenses":
        require_once $controllerClasses['admin']['DriverManagementController'];
        $controller = new DriverManagementController();
        $controller->getDriversWithExpiringLicenses();
        break;

    case "/admin/settings":
        require_once $controllerClasses['admin']['SettingsController'];
        $controller = new SettingsController();
        $controller->index();
        break;
    case "/admin/get-all-settings":
        require_once $controllerClasses['admin']['SettingsController'];
        $controller = new SettingsController();
        $controller->getAllSettings();
        break;
    case "/admin/get-settings-by-group":
        require_once $controllerClasses['admin']['SettingsController'];
        $controller = new SettingsController();
        $controller->getSettingsByGroup();
        break;
    case "/admin/update-settings":
        require_once $controllerClasses['admin']['SettingsController'];
        $controller = new SettingsController();
        $controller->updateSettings();
        break;
    case "/admin/add-setting":
        require_once $controllerClasses['admin']['SettingsController'];
        $controller = new SettingsController();
        $controller->addSetting();
        break;
    case "/admin/delete-setting":
        require_once $controllerClasses['admin']['SettingsController'];
        $controller = new SettingsController();
        $controller->deleteSetting();
        break;
        
    case "/admin/notifications":
        require_once $controllerClasses['admin']['NotificationsController'];
        $controller = new NotificationsController();
        $controller->index();
        break;
    case "/admin/notifications/mark-read":
        require_once $controllerClasses['admin']['NotificationsController'];
        $controller = new NotificationsController();
        $controller->markAsRead();
        break;
    case "/admin/notifications/mark-all-read":
        require_once $controllerClasses['admin']['NotificationsController'];
        $controller = new NotificationsController();
        $controller->markAllAsRead();
        break;
        
    case "/client/notifications":
        require_once $controllerClasses['client']['NotificationsController'];
        $controller = new ClientNotificationsController();
        $controller->index();
        break;
    case "/client/notifications/get":
        require_once $controllerClasses['client']['NotificationsController'];
        $controller = new ClientNotificationsController();
        $controller->getNotifications();
        break;
    case "/client/notifications/mark-read":
        require_once $controllerClasses['client']['NotificationsController'];
        $controller = new ClientNotificationsController();
        $controller->markAsRead();
        break;
    case "/client/notifications/mark-all-read":
        require_once $controllerClasses['client']['NotificationsController'];
        $controller = new ClientNotificationsController();
        $controller->markAllAsRead();
        break;
    case "/client/notifications/add-test":
        require_once $controllerClasses['client']['NotificationsController'];
        $controller = new ClientNotificationsController();
        $controller->addTestNotification();
        break;
        
    // Payment deadline check routes
    case "/admin/check-payment-deadlines":
        require_once __DIR__ . "/../app/controllers/admin/BookingDeadlineController.php";
        $controller = new BookingDeadlineController();
        $controller->checkDeadlines();
        break;

    // Booking completion check routes
    case "/admin/check-booking-completions":
        require_once __DIR__ . "/../app/controllers/admin/BookingCompletionController.php";
        $controller = new BookingCompletionController();
        $controller->checkCompletions();
        break;

    // Audit Trail Management
    case "/admin/audit-trail":
        require_once $controllerClasses['admin']['AuditTrailController'];
        $controller = new AuditTrailController();
        $controller->index();
        break;
    case "/admin/get-audit-trails":
        require_once $controllerClasses['admin']['AuditTrailController'];
        $controller = new AuditTrailController();
        $controller->getAuditTrails();
        break;
    case "/admin/get-audit-details":
        require_once $controllerClasses['admin']['AuditTrailController'];
        $controller = new AuditTrailController();
        $controller->getAuditDetails();
        break;
    case "/admin/get-entity-history":
        require_once $controllerClasses['admin']['AuditTrailController'];
        $controller = new AuditTrailController();
        $controller->getEntityHistory();
        break;
    case "/admin/export-audit-trails":
        require_once $controllerClasses['admin']['AuditTrailController'];
        $controller = new AuditTrailController();
        $controller->exportAuditTrails();
        break;

    default:
        // 404 Not Found
        header("HTTP/1.0 404 Not Found");
        require_once __DIR__ . "/../404.php";
        break;
}
?>