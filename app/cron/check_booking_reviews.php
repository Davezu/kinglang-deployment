<?php
/**
 * This script is designed to be run as a CRON job to check for bookings
 * that need review reminders (3 days before tour date) and to auto-cancel
 * bookings that have not been reviewed by their tour date.
 * 
 * Recommended to run daily: 0 0 * * * /usr/bin/php /path/to/app/cron/check_booking_reviews.php
 */

// Prevent execution from browser
if (php_sapi_name() !== 'cli') {
    die('This script can only be executed from the command line.');
}

// Set up environment
define('APP_ROOT', dirname(__DIR__, 2));
require_once APP_ROOT . '/config/database.php';
require_once APP_ROOT . '/app/controllers/admin/BookingReviewReminderController.php';

// Create controller
$controller = new BookingReviewReminderController();

// Process review reminders (3 days before tour date)
$reminderResult = $controller->processReviewReminders();

// Process auto-cancellations (on tour date)
$cancellationResult = $controller->processAutoCancellations();

// Output results for logging
echo date('Y-m-d H:i:s') . ' - ';

if ($reminderResult['success']) {
    echo "REMINDERS: " . $reminderResult['message'] . PHP_EOL;
} else {
    echo "REMINDER ERROR: " . $reminderResult['message'] . PHP_EOL;
}

if ($cancellationResult['success']) {
    echo "CANCELLATIONS: " . $cancellationResult['message'] . PHP_EOL;
    exit(0);
} else {
    echo "CANCELLATION ERROR: " . $cancellationResult['message'] . PHP_EOL;
    exit(1);
} 