<?php
/**
 * This script is designed to be run as a CRON job to check for bookings
 * that are past their payment deadline and cancel them automatically.
 * 
 * Recommended to run daily: 0 0 * * * /usr/bin/php /path/to/app/cron/check_payment_deadlines.php
 */

// Prevent execution from browser
if (php_sapi_name() !== 'cli') {
    die('This script can only be executed from the command line.');
}

// Set up environment
define('APP_ROOT', dirname(__DIR__, 2));
require_once APP_ROOT . '/config/database.php';
require_once APP_ROOT . '/app/controllers/admin/BookingDeadlineController.php';

// Run the deadline check
$controller = new BookingDeadlineController();
$result = $controller->processPastDueBookings();

// Output result for logging
echo date('Y-m-d H:i:s') . ' - ';
if ($result['success']) {
    echo "SUCCESS: " . $result['message'] . PHP_EOL;
    exit(0);
} else {
    echo "ERROR: " . $result['message'] . PHP_EOL;
    exit(1);
} 