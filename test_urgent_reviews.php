<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/controllers/admin/BookingReviewReminderController.php';

// Create controller
$controller = new BookingReviewReminderController();

// Get urgent review bookings
$result = $controller->getUrgentReviewBookings();

// Display results
echo "<h1>Urgent Review Bookings Test</h1>";

if ($result['success']) {
    echo "<p>Found {$result['count']} bookings that need urgent review.</p>";
    
    if ($result['count'] > 0) {
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Client</th><th>Destination</th><th>Date</th><th>Days Remaining</th></tr>";
        
        foreach ($result['bookings'] as $booking) {
            echo "<tr>";
            echo "<td>{$booking['booking_id']}</td>";
            echo "<td>{$booking['client_name']}</td>";
            echo "<td>{$booking['destination']}</td>";
            echo "<td>{$booking['formatted_date']}</td>";
            echo "<td>{$booking['days_remaining']}</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    }
} else {
    echo "<p>Error: {$result['message']}</p>";
} 