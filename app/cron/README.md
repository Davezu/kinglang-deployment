# Cron Jobs for Kinglang Booking System

This directory contains cron job scripts that automate various tasks in the booking system.

## Available Cron Jobs

### 1. Check Booking Completions (`check_booking_completions.php`)

This script checks for bookings that have completed tours and marks them as completed.
It also notifies admins about partially paid completed bookings.

**Recommended Schedule**: Daily at midnight
```
0 0 * * * /usr/bin/php /path/to/app/cron/check_booking_completions.php
```

### 2. Check Payment Deadlines (`check_payment_deadlines.php`)

This script checks for bookings that are past their payment deadline and cancels them automatically.

**Recommended Schedule**: Daily at midnight
```
0 0 * * * /usr/bin/php /path/to/app/cron/check_payment_deadlines.php
```

### 3. Check Booking Reviews (`check_booking_reviews.php`)

This script performs two important tasks:
- Sends reminders to admins about bookings that need to be reviewed (3 days before tour date)
- Auto-cancels bookings that have not been reviewed by their tour date

**Recommended Schedule**: Daily at midnight
```
0 0 * * * /usr/bin/php /path/to/app/cron/check_booking_reviews.php
```

## Setting Up Cron Jobs

### On Linux/Unix Systems

1. Open the crontab editor:
   ```
   crontab -e
   ```

2. Add the cron job entries (adjust paths as needed):
   ```
   0 0 * * * /usr/bin/php /path/to/app/cron/check_booking_completions.php >> /path/to/logs/cron.log 2>&1
   0 0 * * * /usr/bin/php /path/to/app/cron/check_payment_deadlines.php >> /path/to/logs/cron.log 2>&1
   0 0 * * * /usr/bin/php /path/to/app/cron/check_booking_reviews.php >> /path/to/logs/cron.log 2>&1
   ```

### On Windows Systems

1. Open Task Scheduler
2. Create a new task for each script
3. Set the trigger to run daily at midnight
4. Set the action to run the PHP executable with the script path as an argument:
   ```
   C:\path\to\php.exe C:\path\to\app\cron\check_booking_reviews.php
   ```

## Logging

All scripts output their results to stdout, which can be redirected to a log file:
```
/usr/bin/php /path/to/app/cron/check_booking_reviews.php >> /path/to/logs/cron.log 2>&1
``` 