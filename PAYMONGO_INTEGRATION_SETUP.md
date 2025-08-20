# PayMongo Integration Setup Guide

## Overview
This guide explains how to complete the PayMongo integration setup for the Kinglang Booking System.

## Files Created/Modified

### Database Changes
- `database/migrations/add_paymongo_support.sql` - Database schema updates
- Run this migration to add PayMongo support to your payments table

### New Files Created
- `app/services/PayMongoService.php` - Main PayMongo service integration
- `app/controllers/client/PayMongoController.php` - PayMongo callback handlers
- `app/views/client/paymongo_success.php` - Success page view
- `paymongo_routes.php` - Standalone route handler (optional)

### Modified Files
- `routes/web.php` - Added PayMongo routes
- `app/controllers/client/BookingController.php` - Added PayMongo payment handling
- `app/views/client/booking_requests.php` - Updated payment UI
- `public/js/client/booking_request.js` - Updated JavaScript for PayMongo

## Setup Steps

### 1. Database Migration
```sql
-- Run the migration script
mysql -u your_username -p your_database < database/migrations/add_paymongo_support.sql
```

### 2. PayMongo Configuration
Update the PayMongo configuration in `PayMongo/config.php`:

```php
return [
    'paymongo' => [
        'secret_key' => 'your_paymongo_secret_key',
        'public_key' => 'your_paymongo_public_key',
        'api_url' => 'https://api.paymongo.com/v1',
    ],
    'app' => [
        'url' => 'https://yourdomain.com', // Update with your domain
    ],
    'urls' => [
        'success' => 'https://yourdomain.com/paymongo/success',
        'cancel' => 'https://yourdomain.com/paymongo/cancel',
    ],
    'webhook' => [
        'secret' => 'your_webhook_secret',
    ],
];
```

### 3. Webhook Setup
1. Go to your PayMongo Dashboard
2. Create a webhook endpoint: `https://yourdomain.com/paymongo/webhook`
3. Subscribe to these events:
   - `checkout_session.payment.paid`
   - `checkout_session.payment.failed`
4. Copy the webhook secret and update it in `config.php`

### 4. SSL Certificate
Ensure your domain has a valid SSL certificate as PayMongo requires HTTPS for webhooks and callbacks.

### 5. Test the Integration

#### Test Flow:
1. Create a booking as a client
2. Go to "My Bookings" and click "Pay" on a booking
3. Select "GCash (PayMongo)" as payment method
4. Click "Confirm Payment"
5. You should be redirected to PayMongo's payment page
6. Complete the payment using GCash
7. You should be redirected back to a success page
8. Check the admin panel to see the payment status

## Features Included

### For Clients:
- Secure GCash payments through PayMongo
- Automatic payment confirmation
- No need to upload proof of payment
- Real-time payment status updates
- Beautiful success page with payment details

### For Admins:
- Automatic payment confirmation in admin panel
- PayMongo transaction details stored in database
- Webhook event audit trail
- Integration with existing payment management system

### Security Features:
- SSL encrypted payments
- PCI compliant payment processing
- Webhook signature verification
- Secure callback handling
- Payment status verification

## Payment Flow

1. **Client initiates payment** → PayMongo checkout session created
2. **Client redirected to PayMongo** → Secure payment gateway
3. **Payment completed** → Webhook confirms payment
4. **Client redirected back** → Success page shown
5. **Admin notified** → Payment appears in admin panel
6. **Booking status updated** → Balance and payment status updated

## Troubleshooting

### Common Issues:

1. **Webhook not receiving events**
   - Check SSL certificate
   - Verify webhook URL is accessible
   - Check webhook secret configuration

2. **Payment not confirming**
   - Check webhook endpoint logs
   - Verify database permissions
   - Check PayMongo dashboard for payment status

3. **Redirect issues**
   - Verify success/cancel URLs in config
   - Check domain configuration
   - Ensure routes are properly set up

### Debug Mode:
Enable debug logging by checking the `logs/payment.log` file for detailed information about PayMongo API calls.

## Support
- PayMongo Documentation: https://developers.paymongo.com/
- PayMongo Support: support@paymongo.com

## Security Notes
- Never expose your secret key in client-side code
- Always validate webhook signatures
- Use HTTPS for all PayMongo interactions
- Regularly rotate webhook secrets
- Monitor payment logs for suspicious activity
