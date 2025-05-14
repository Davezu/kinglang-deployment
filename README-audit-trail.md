# Audit Trail Module Documentation

## Overview

The Audit Trail module provides comprehensive tracking of user actions throughout the Kinglang Booking system. It records who performed which actions, when they occurred, and what data was changed. This helps with accountability, security monitoring, and troubleshooting.

## Features

- Records user actions (create, update, delete, view, login, logout)
- Tracks changes to important entities (bookings, payments, users, settings)
- Captures before/after values for all changes
- Records contextual information (IP address, user agent, timestamp)
- Provides search, filtering, and export capabilities
- Displays detailed history for individual entities

## Installation

1. Run the database migration to create the audit trail table:
   ```sql
   -- Use the migration file or directly run the SQL
   mysql -u username -p database_name < database/migrations/audit_trail_table.sql
   ```

2. Alternatively, apply the changes via the database updates:
   ```sql
   -- Add audit trails table via updates.sql
   mysql -u username -p database_name < database/updates.sql
   ```

## Implementation Guide

### 1. Using the Trait in Controllers

Include the `AuditTrailTrait` in any controller where you want to add audit logging:

```php
require_once __DIR__ . "/../AuditTrailTrait.php";

class YourController {
    use AuditTrailTrait;
    
    // Your methods here
}
```

### 2. Logging Create Actions

```php
// Example: Create a new booking
$bookingData = [
    'destination' => $destination,
    'pickup_point' => $pickupPoint,
    'date_of_tour' => $dateOfTour,
    // ... other fields
];

// Insert the booking
$statement = $pdo->prepare("INSERT INTO bookings (...) VALUES (...)");
$statement->execute([...]);
$bookingId = $pdo->lastInsertId();

// Log the creation to audit trail
$this->logAudit('create', 'booking', $bookingId, null, $bookingData);
```

### 3. Logging Update Actions

```php
// Example: Update a booking status
// First, get the current state for the "before" snapshot
$oldData = $this->getEntityBeforeUpdate('bookings', 'booking_id', $bookingId);

// Perform the update
$statement = $pdo->prepare("UPDATE bookings SET status = ? WHERE booking_id = ?");
$statement->execute(['Confirmed', $bookingId]);

// Get the new state for the "after" snapshot
$statement = $pdo->prepare("SELECT * FROM bookings WHERE booking_id = ?");
$statement->execute([$bookingId]);
$newData = $statement->fetch(PDO::FETCH_ASSOC);

// Log the update to audit trail
$this->logAudit('update', 'booking', $bookingId, $oldData, $newData);
```

### 4. Logging Delete Actions

```php
// Example: Delete a booking
// First, get the current state for the "before" snapshot
$oldData = $this->getEntityBeforeUpdate('bookings', 'booking_id', $bookingId);

// Perform the delete
$statement = $pdo->prepare("DELETE FROM bookings WHERE booking_id = ?");
$statement->execute([$bookingId]);

// Log the deletion to audit trail
$this->logAudit('delete', 'booking', $bookingId, $oldData, null);
```

### 5. Logging Other Actions

```php
// Example: Log a view action
$this->logAudit('view', 'booking', $bookingId);

// Example: Log a login action
$this->logAudit('login', 'user', $userId);

// Example: Log a logout action
$this->logAudit('logout', 'user', $userId);
```

## Accessing Audit Trails

Super Admin users can access the Audit Trail interface by clicking on "Audit Trail" in the sidebar menu. From there, they can:

1. View all audit events with filtering options
2. See detailed information about specific events
3. View change history for a specific entity
4. Export audit data to CSV for further analysis

## Security Considerations

- Audit trail data is sensitive and should only be accessible to Super Admin users
- The system doesn't log sensitive data like passwords in plain text
- Consider implementing a data retention policy for audit records
- Use the audit trail as part of security monitoring procedures

## Best Practices

1. Log all significant actions, especially those affecting data integrity
2. Always capture both before and after states for updates
3. Be consistent with entity type names and action names
4. Include context data when relevant (e.g., notes on why an action was taken)
5. Review audit trails regularly as part of system administration
6. Consider adding more detailed logging for critical operations

## Troubleshooting

- If audit events aren't being recorded, check that the table exists and the trait is properly included
- If database errors occur, check the PHP error log for details
- For performance issues, consider implementing a cleanup policy for old audit records 