<?php
/**
 * Migration script to add payment_deadline column to bookings table
 */

require_once __DIR__ . "/../../config/database.php";

// Check if the column already exists
$sql = "SHOW COLUMNS FROM bookings LIKE 'payment_deadline'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$column_exists = $stmt->rowCount() > 0;

if (!$column_exists) {
    try {
        // Add the payment_deadline column
        $sql = "ALTER TABLE bookings ADD COLUMN payment_deadline DATETIME NULL AFTER confirmed_at";
        $pdo->exec($sql);
        echo "Successfully added payment_deadline column to bookings table.";
        
        // Update existing confirmed bookings with a payment deadline of 2 days from now
            $sql = "UPDATE bookings SET payment_deadline = DATE_ADD(NOW(), INTERVAL 2 DAY) 
                    WHERE status = 'Confirmed' AND payment_status IN ('Unpaid', 'Partially Paid') 
                    AND payment_deadline IS NULL";
        $affected = $pdo->exec($sql);
        echo "\nUpdated $affected existing confirmed bookings with payment deadlines.";
    } catch (PDOException $e) {
        echo "Error adding payment_deadline column: " . $e->getMessage();
    }
} else {
    echo "The payment_deadline column already exists in the bookings table.";
} 