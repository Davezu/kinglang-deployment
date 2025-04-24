<?php 
require_client_auth(); // Use helper function
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - Booking #<?php echo $booking['booking_id']; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/../../../public/css/bootstrap/bootstrap.min.css">
    <style>
        body {
            font-family: 'Work Sans', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            padding: 30px;
        }
        .invoice-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .invoice-logo {
            max-width: 150px;
            height: auto;
        }
        .company-info {
            margin-top: 10px;
        }
        .invoice-number {
            font-size: 1.5rem;
            font-weight: 700;
            color: #198754;
        }
        .invoice-date {
            color: #6c757d;
        }
        .invoice-details {
            border-bottom: 1px solid #eee;
        }
        .client-info {
            margin-bottom: 20px;
        }
        .table-invoice th {
            background-color: #d1f7c4;
            color: #333;
        }
        .table-totals {
            width: 300px;
            margin-left: auto;
        }
        .table-totals td {
            padding: 8px;
        }
        .payment-history {
            margin-top: 20px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-align: center;
        }
        .status-pending {
            background-color: #fff3cd;
            color: #664d03;
        }
        .status-confirmed {
            background-color: #d1e7dd;
            color: #0f5132;
        }
        .status-processing {
            background-color: #cff4fc;
            color: #055160;
        }
        .status-canceled, .status-rejected {
            background-color: #f8d7da;
            color: #842029;
        }
        .status-completed {
            background-color: #c3e6cb;
            color: #155724;
        }
        .print-btn {
            text-align: center;
            margin: 20px 0;
        }
        @media print {
            body {
                background-color: #fff;
                padding: 0;
            }
            .invoice-container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .print-btn {
                display: none;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header d-flex justify-content-between align-items-start">
            <div>
                <img src="/../../../public/images/logo.png" alt="Kinglang Bus Logo" class="invoice-logo">
                <div class="company-info">
                    <h5>Kinglang Transport</h5>
                    <p class="mb-0">123 Main Street, Manila</p>
                    <p class="mb-0">Phone: (02) 123-4567</p>
                    <p class="mb-0">Email: kinglang.transport@gmail.com</p>
                </div>
            </div>
            <div class="text-end">
                <div class="invoice-number">Invoice #<?php echo $booking['booking_id']; ?></div>
                <div class="invoice-date">Date: <?php echo date('F d, Y'); ?></div>
                <div class="mt-3"> 
                    <span class="status-badge status-<?php echo strtolower($booking['status']); ?>">
                        <?php echo $booking['status']; ?>
                    </span>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between invoice-details pb-3">
            <div class=" w-50">
                <div>
                    <h5>Client Information</h5>
                    <p class="mb-1"><strong>Name:</strong> <?php echo $booking['client_name']; ?></p>
                    <p class="mb-1"><strong>Email:</strong> <?php echo $booking['email']; ?></p>
                    <p class="mb-1"><strong>Phone:</strong> <?php echo $booking['contact_number']; ?></p>
                </div>
            </div>
            <div class="w-50">
                <div >
                    <h5>Booking Details</h5>
                    <p class="mb-1"><strong>Booking Date:</strong> <?php echo date('F d, Y', strtotime($booking['booked_at'])); ?></p>
                    <p class="mb-1"><strong>Tour Date:</strong> <?php echo date('F d, Y', strtotime($booking['date_of_tour'])) . " to " . date('F d, Y', strtotime($booking['end_of_tour'])); ?></p>
                    <p class="mb-1"><strong>Duration:</strong> <?php echo $booking['number_of_days']; ?> day(s)</p>
                    <p class="mb-1"><strong>Pickup Time:</strong> <?php echo $booking['pickup_time']; ?></p>
                </div>
            </div>
        </div>
        
        <div class="invoice-details">
            <h5 class="mt-4">Trip Details</h5>
            <div class="d-flex justify-content-between gap-2">
                <div class="w-50">
                    <p><strong>Pickup Point:</strong> <?php echo $booking['pickup_point']; ?></p>
                    <p><strong>Destination:</strong> <?php 
                        if ($booking['stops'] != null) {
                            foreach ($booking['stops'] as $stop) {
                                echo $stop['location'] . "<i class='bi bi-arrow-right mx-1 text-danger'></i>";
                            }
                        }
                        echo $booking['destination']; 
                    ?>
                    </p>
                </div>
                <div class="w-50">
                    <p class="mb-1"><strong>Number of Buses:</strong> <?php echo $booking['number_of_buses']; ?></p>
                    <p class="mb-1"><strong>Current Diesel Price:</strong> ₱<?php echo number_format($booking['diesel_price'], 2); ?></p>
                    <p class="mb-1"><strong>Base Rate:</strong> ₱<?php echo number_format($booking['base_rate'], 2); ?></p>
                    <p class="mb-1"><strong>Total Distance:</strong> <?php echo $booking['total_distance']; ?> km</p>
                </div>
            </div>
        </div>
        <!-- <?php
            echo "<pre>";
            print_r($payments);
            echo "</pre>";
        ?> -->
         <?php if (!empty($payments)): ?>
        <div class="mt-4 invoice-details">
            <h5>Payment History</h5>
            <table class="table table-bordered table-invoice">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($payments as $payment): ?>
                    <?php if ($payment['is_canceled'] == 0): ?>
                    <tr>
                        <td><?php echo date('F d, Y', strtotime($payment['payment_date'])); ?></td>
                        <td>₱<?php echo number_format($payment['amount'], 2); ?></td>
                        <td><?php echo $payment['payment_method']; ?></td>
                        <td>
                            <span class="status-badge status-<?php echo strtolower($payment['status']); ?>">
                                <?php echo $payment['status']; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
                        
        <div class="mt-4">
            <table class="table-totals">
                <tr>
                    <td><strong>Base Cost:</strong></td>
                    <td class="text-start">₱<?php echo number_format($booking['base_cost'], 2); ?></td>
                </tr>
                <tr>
                    <td><strong>Diesel Cost:</strong></td>
                    <td class="text-start">₱<?php echo number_format($booking['diesel_cost'], 2); ?></td>
                </tr>
                <tr>
                    <td><strong>Total Cost:</strong></td>
                    <td class="text-start">₱<?php echo number_format($booking['total_cost'], 2); ?></td>
                </tr>
                <tr>
                    <td><strong>Amount Paid:</strong></td>
                    <td class="text-start">₱<?php echo number_format($booking['total_cost'] - $booking['balance'], 2); ?></td>
                </tr>
                <tr>
                    <td><strong>Balance:</strong></td>
                    <td class="text-start">₱<?php echo number_format($booking['balance'], 2); ?></td>
                </tr>
            </table>
        </div>
        
        <div class="mt-4">
            <p><strong>Note:</strong> This is an official invoice from Kinglang Bus. Thank you for choosing our services!</p>
        </div>
    </div>
    
    <div class="print-btn no-print">
        <button class="btn btn-success" onclick="window.print()">
            <i class="bi bi-printer"></i> Print Invoice
        </button>
        <a href="/home/booking-requests" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back to Bookings
        </a>
    </div>
    
    <script>
        // Auto-print when page loads
        window.onload = function() {
            // Automatically open print dialog when loaded from print link
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('print') === 'true') {
                window.print();
            }
        };
    </script>
</body>
</html> 