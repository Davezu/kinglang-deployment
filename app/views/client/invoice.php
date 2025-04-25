<?php 
require_client_auth(); // Use helper function

// echo round((float) $booking["diesel_price"] * (float) $booking["total_distance"], 2); // Example calculation
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
            font-size: 0.9rem;
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            padding: 20px;
        }
        .invoice-header {
            border-bottom: 1px solid #eee;
            padding-bottom: 15px;
            margin-bottom: 15px;
        }
        .invoice-logo-container {
            display: flex;
            align-items: center;
        }
        .invoice-logo {
            max-width: 100px;
            height: auto;
            object-fit: contain;
        }
        .company-info {
            margin-top: 5px;
        }
        .company-info p {
            margin-bottom: 2px;
            line-height: 1.3;
        }
        .invoice-number {
            font-size: 1.3rem;
            font-weight: 700;
            color: #198754;
        }
        .invoice-date {
            color: #6c757d;
        }
        .invoice-details {
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        .client-info {
            margin-bottom: 15px;
        }
        h5 {
            font-size: 1rem;
            margin-bottom: 8px;
            font-weight: 600;
        }
        p {
            margin-bottom: 5px;
        }
        .table-invoice th {
            background-color: #d1f7c4;
            color: #333;
            padding: 6px;
        }
        .table-invoice td {
            padding: 4px;
            vertical-align: middle;
        }
        .table-invoice {
            margin-bottom: 10px;
        }
        .table-totals {
            width: 300px;
            margin-left: auto;
        }
        .table-totals td {
            padding: 3px;
        }
        .payment-history {
            margin-top: 15px;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 20px;
            font-size: 0.7rem;
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
        .letterhead {
            background-color: #f8f9fa;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 15px;
            border-bottom: 3px solid #198754;
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
            .letterhead {
                background-color: transparent;
                border-bottom: 2px solid #198754;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="letterhead">
            <div class="invoice-header d-flex justify-content-between align-items-start mb-2">
                <div class="invoice-logo-container">
                    <img src="/../../../public/images/logo.png" alt="Kinglang Bus Logo" class="invoice-logo">
                    <div class="company-info ms-3">
                        <h5 class="mb-1">Kinglang Transport</h5>
                        <p class="mb-0">123 Main Street, Manila</p>
                        <p class="mb-0">Phone: (02) 123-4567</p>
                        <p class="mb-0">Email: kinglang.transport@gmail.com</p>
                    </div>
                </div>
                <div class="text-end">
                    <div class="invoice-number">Invoice #<?php echo $booking['booking_id']; ?></div>
                    <div class="invoice-date">Date: <?php 
                        $booking['confirmed_at'] = new DateTime($booking['confirmed_at']);
                        echo $booking['confirmed_at']->format('F j, Y'); 
                    ?></div>
                    <div class="mt-3"> 
                        <span class="status-badge status-<?php echo strtolower($booking['status']); ?>">
                            <?php echo $booking['status']; ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between invoice-details pb-2">
            <div class="w-50 pe-2">
                <div>
                    <h5>Client Information</h5>
                    <p class="mb-1"><strong>Name:</strong> <?php echo $booking['client_name']; ?></p>
                    <p class="mb-1"><strong>Email:</strong> <?php echo $booking['email']; ?></p>
                    <p class="mb-1"><strong>Phone:</strong> <?php echo $booking['contact_number']; ?></p>
                </div>
            </div>
            <div class="w-50">
                <div>
                    <h5>Booking Details</h5>
                    <p class="mb-1"><strong>Booking Date:</strong> <?php echo date('F d, Y', strtotime($booking['booked_at'])); ?></p>
                    <p class="mb-1"><strong>Tour Date:</strong> <?php echo date('M d, Y', strtotime($booking['date_of_tour'])) . " to " . date('M d, Y', strtotime($booking['end_of_tour'])); ?></p>
                    <p class="mb-1"><strong>Duration:</strong> <?php echo $booking['number_of_days']; ?> day(s)</p>
                    <p class="mb-1"><strong>Pickup Time:</strong> <?php echo $booking['pickup_time']; ?></p>
                </div>
            </div>
        </div>
        
        <div class="invoice-details">
            <h5 class="mt-2">Trip Details</h5>
            <div class="d-flex justify-content-between">
                <div class="w-50 pe-2">
                    <p class="mb-1"><strong>Pickup Point:</strong> <?php echo $booking['pickup_point']; ?></p>
                    <p class="mb-1"><strong>Destination:</strong> <?php 
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
            print_r($booking);
            echo "</pre>";
        ?> -->
         <?php if (!empty($payments)): ?>
        <div class="mt-3">
            <h5>Payment History</h5>
            <table class="table table-bordered table-invoice table-sm">
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
                        <td><?php echo date('M d, Y', strtotime($payment['payment_date'])); ?></td>
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
                        
        <div class="mt-3">
            <table class="table-totals">
                <tr>
                    <td><strong>Base Cost:</strong></td>
                    <td class="text-start">₱<?php echo number_format($booking['base_cost'], 2); ?></td>
                </tr>
                <tr>
                    <td><strong>Diesel Cost:</strong></td>
                    <td class="text-start">₱<?php echo number_format($booking['diesel_cost'], 2); ?></td>
                </tr>
                <?php if (!empty($booking['gross_price']) && $booking['discount'] > 0): ?>
                <tr>
                    <td><strong>Original Price:</strong></td>
                    <td class="text-start">₱<?php echo number_format($booking['gross_price'], 2); ?></td>
                </tr>
                <tr>
                    <td><strong>Discount Rate:</strong></td>
                    <td class="text-start"><?php echo number_format($booking['discount'], 2); ?>%</td>
                </tr>
                <tr>
                    <td><strong>Discount Amount:</strong></td>
                    <td class="text-start">₱<?php echo number_format($booking['gross_price'] - $booking['total_cost'], 2); ?></td>
                </tr>
                <?php endif; ?>
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
        
        <div class="mt-2">
            <p class="small"><strong>Note:</strong> This is an official invoice from Kinglang Bus. Thank you for choosing our services!</p>
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