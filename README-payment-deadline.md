# Payment Deadline Feature

This feature implements automatic cancellation of bookings when clients don't complete payment within the specified deadline.

## Overview

When an admin confirms a booking, a 2-day payment deadline is automatically set. If the client does not complete the payment within this timeframe, the system will automatically cancel the booking.

## Implemented Features

1. **Payment Deadline Setting**:
   - When admin confirms a booking, a payment deadline is set to 2 days from confirmation time
   - The deadline is stored in the `payment_deadline` column in the bookings table

2. **Automatic Cancellation**:
   - A CRON job runs daily to check for bookings past their payment deadline
   - If a booking is still unpaid or partially paid after the deadline, it's automatically canceled
   - Cancellation information is recorded in the existing `canceled_trips` table

3. **Notifications**:
   - The client receives a notification when their booking is canceled due to non-payment
   - Admins also receive a notification about the automatic cancellation

## Technical Implementation

### Files Created/Modified

- `app/controllers/admin/BookingDeadlineController.php` - Controller for handling payment deadlines
- `app/cron/check_payment_deadlines.php` - Script to be run as a CRON job
- `app/models/admin/BookingManagementModel.php` - Updated to set payment deadline on booking confirmation
- `database/migrations/add_payment_deadline.php` - Migration to add payment_deadline field to the database
- `routes/web.php` - Added route for manually checking payment deadlines

### Database Changes

Added only one column to the `bookings` table:
- `payment_deadline` - Date/time when payment should be completed by

Cancellations use the existing `canceled_trips` table which already contains:
- `booking_id` - The booking that was canceled
- `reason` - Why the booking was canceled
- `user_id` - The user whose booking was canceled
- `canceled_by` - Who canceled the booking (in this case, "System")

## Setup Instructions

1. **Run the Database Migration**:
   ```
   php database/migrations/add_payment_deadline.php
   ```

2. **Set Up CRON Job**:
   Add the following line to your server's crontab to run the check daily at midnight:
   ```
   0 0 * * * /usr/bin/php /path/to/app/cron/check_payment_deadlines.php
   ```

3. **Manual Testing**:
   You can manually trigger the payment deadline check by visiting:
   ```
   /admin/check-payment-deadlines
   ```

## Notification Messages

### Client Notification
"Your booking for [trip details] has been canceled due to non-payment. Please contact us if you need further assistance."

### Admin Notification
"Booking ID [BookingID] has been automatically canceled due to the client not making payment within 2 days. Please review." 