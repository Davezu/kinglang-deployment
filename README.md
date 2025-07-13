# Kinglang Booking System

A comprehensive booking management system for Kinglang Bus Tours.

## Features

### Client Features
- User registration and authentication
- Booking request submission
- Real-time booking status tracking
- Payment management
- Notifications system
- Booking history and details

### Admin Features
- Booking management (approve, reject, cancel)
- Bus fleet management
- Driver assignment
- Payment processing
- Reporting and analytics
- Audit trail
- Notification system

### Automated Features
- Booking completion tracking
- Payment deadline monitoring
- Booking review reminders and auto-cancellation
  - Admins receive notifications 3 days before tour date for unreviewed bookings
  - Bookings are automatically cancelled if not reviewed by the tour date
  - Clients receive notifications when their booking is auto-cancelled

## Installation

1. Clone the repository
2. Configure your web server to point to the project directory
3. Import the database schema from `database/database.sql`
4. Configure database connection in `config/database.php`
5. Set up cron jobs for automated features (see `app/cron/README.md`)

## Configuration

### Database Configuration
Edit `config/database.php` with your database credentials.

### Google Authentication
For Google Sign-In integration, see `README-google-auth.md`.

## Cron Jobs

The system requires several cron jobs to automate various tasks:

1. **Booking Completions**: Marks bookings as completed after the tour date
2. **Payment Deadlines**: Cancels bookings that haven't been paid by the deadline
3. **Booking Reviews**: Sends reminders for unreviewed bookings and auto-cancels if not reviewed

For detailed cron job setup instructions, see `app/cron/README.md`.

## License

This project is proprietary software owned by Kinglang Bus Tours. 