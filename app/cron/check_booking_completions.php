<?php
/**
 * This script is designed to be run as a CRON job to check for bookings
 * that have completed tours and mark them as completed.
 * It also notifies admins about partially paid completed bookings.
 * 
 * Recommended to run daily: 0 0 * * * /usr/bin/php /path/to/app/cron/check_booking_completions.php
 */

// Prevent execution from browser
if (php_sapi_name() !== 'cli') {
    die('This script can only be executed from the command line.');
}

// Set up environment
define('APP_ROOT', dirname(__DIR__, 2));
require_once APP_ROOT . '/config/database.php';
require_once APP_ROOT . '/app/controllers/admin/BookingCompletionController.php';

// Run the completion check
$controller = new BookingCompletionController();
$result = $controller->processCompletedBookings();

// Output result for logging
echo date('Y-m-d H:i:s') . ' - ';
if ($result['success']) {
    echo "SUCCESS: " . $result['message'] . PHP_EOL;
    exit(0);
} else {
    echo "ERROR: " . $result['message'] . PHP_EOL;
    exit(1);
} 