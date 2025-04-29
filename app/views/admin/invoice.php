<?php 
// Ensure admin authentication
if (!isset($_SESSION["role"]) || ($_SESSION["role"] !== "Super Admin" && $_SESSION["role"] !== "Admin")) {
    header("Location: /admin/login");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Invoice - Booking #<?php echo $booking['booking_id']; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/../../../public/css/bootstrap/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            font-size: 0.8rem;
        }
        .table-invoice td {
            padding: 4px;
            vertical-align: middle;
            font-size: 0.8rem;
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
        .admin-actions {
            margin-top: 15px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .admin-actions .btn {
            font-size: 0.75rem;
            padding: 0.2rem 0.5rem;
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
                font-size: 0.85rem;
            }
            .invoice-container {
                box-shadow: none;
                padding: 0;
                max-width: 100%;
            }
            .print-btn, .admin-actions, .no-print {
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
                    <div class="invoice-number">Invoice #<?php echo $booking['booking_id']; ?></div>
                    <div class="invoice-date">Issued Date: <?php 
                        $issued_date = new DateTime($booking['confirmed_at']);
                        echo $issued_date->format('F j, Y') ?? "N/A"; 
                    ?></div>
                    <div class="invoice-date">Due Date: <?php 
                        $due_date = new DateTime($booking['date_of_tour']);
                        $due_date->modify('-7 day');
                        echo $due_date->format('F j, Y') ?? "N/A"; 
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
                        if (isset($stops) && !empty($stops)) {
                            foreach ($stops as $stop) {
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
        
        <?php if (!empty($payments)): ?>
        <div class="mt-3 invoice-details">
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
        
        <!-- Admin-only section -->
        <div class="admin-actions no-print">
            <h5 class="mb-2"><i class="bi bi-gear"></i> Admin Actions</h5>
            <div class="row g-1 mt-1">
                <div class="col-md-6">
                    <button class="btn btn-sm btn-primary w-100" id="updatePaymentBtn">
                        <i class="bi bi-cash-coin"></i> Record Payment
                    </button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-sm btn-info w-100" id="emailInvoiceBtn">
                        <i class="bi bi-envelope"></i> Email Invoice
                    </button>
                </div>
                <?php if ($booking['status'] == 'Pending'): ?>
                <div class="col-md-6">
                    <button class="btn btn-sm btn-success w-100" id="confirmBookingBtn" data-booking-id="<?php echo $booking['booking_id']; ?>">
                        <i class="bi bi-check-circle"></i> Confirm Booking
                    </button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-sm btn-danger w-100" id="rejectBookingBtn" data-booking-id="<?php echo $booking['booking_id']; ?>">
                        <i class="bi bi-x-circle"></i> Reject Booking
                    </button>
                </div>
                <?php endif; ?>
                <?php if ($booking['status'] != 'Canceled' && $booking['status'] != 'Rejected'): ?>
                <div class="col-md-6">
                    <button class="btn btn-sm btn-outline-danger w-100" id="cancelBookingBtn" data-booking-id="<?php echo $booking['booking_id']; ?>">
                        <i class="bi bi-x-octagon"></i> Cancel Booking
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="print-btn no-print">
        <button class="btn btn-success" onclick="window.print()">
            <i class="bi bi-printer"></i> Print Invoice
        </button>
        <a href="/admin/booking-requests" class="btn btn-secondary">
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
        
        // Connect to admin actions buttons
        document.addEventListener('DOMContentLoaded', function() {
            // Confirmation button
            const confirmBtn = document.getElementById('confirmBookingBtn');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', function() {
                    const bookingId = this.getAttribute('data-booking-id');
                    
                    Swal.fire({
                        title: 'Enter Discount Rate',
                        text: 'Enter a discount percentage (0-100)',
                        input: 'number',
                        inputPlaceholder: 'e.g., 15 for 15%',
                        showCancelButton: true,
                        confirmButtonText: 'Confirm Booking',
                        cancelButtonText: 'Cancel',
                        inputAttributes: {
                            min: 0,
                            max: 100,
                            step: 0.01
                        },
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Please enter a discount rate';
                            }
                            const numValue = parseFloat(value);
                            if (isNaN(numValue) || numValue < 0 || numValue > 100) {
                                return 'Discount must be between 0 and 100';
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const discount = parseFloat(result.value || 0);
                            
                            fetch('/admin/confirm-booking', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json'
                                },
                                body: JSON.stringify({
                                    bookingId: bookingId,
                                    discount: discount
                                })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Success',
                                        text: 'Booking confirmed successfully!',
                                        timer: 2000,
                                        timerProgressBar: true
                                    }).then(() => {
                                        location.reload();
                                    });
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: 'Error: ' + data.message,
                                        timer: 2000,
                                        timerProgressBar: true
                                    });
                                }
                            });
                        }
                    });
                });
            }
            
            // Reject booking button
            const rejectBtn = document.getElementById('rejectBookingBtn');
            if (rejectBtn) {
                rejectBtn.addEventListener('click', function() {
                    const bookingId = this.getAttribute('data-booking-id');
                    const reason = prompt('Please provide a reason for rejecting this booking:');
                    if (reason) {
                        fetch('/admin/reject-booking', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                bookingId: bookingId,
                                userId: <?php echo $booking['user_id']; ?>,
                                reason: reason
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Booking rejected successfully!');
                                location.reload();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        });
                    }
                });
            }
            
            // Cancel booking button
            const cancelBtn = document.getElementById('cancelBookingBtn');
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    const bookingId = this.getAttribute('data-booking-id');
                    if (confirm('Are you sure you want to cancel this booking? This action cannot be undone.')) {
                        fetch('/admin/cancel-booking', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                bookingId: bookingId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Booking canceled successfully!');
                                location.reload();
                            } else {
                                alert('Error: ' + data.message);
                            }
                        });
                    }
                });
            }
            
            // Email invoice button functionality could be added here
            const emailBtn = document.getElementById('emailInvoiceBtn');
            if (emailBtn) {
                emailBtn.addEventListener('click', function() {
                    alert('This feature is coming soon!');
                });
            }
            
            // Record payment button - opens a modal or redirects to payment page
            const paymentBtn = document.getElementById('updatePaymentBtn');
            if (paymentBtn) {
                paymentBtn.addEventListener('click', function() {
                    window.location.href = `/admin/payment-management?booking_id=<?php echo $booking['booking_id']; ?>`;
                });
            }
        });
    </script>
</body>
</html> 