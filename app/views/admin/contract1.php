<?php 
// Include the settings helper functions
require_once __DIR__ . '/../../../config/settings.php';

// Get company details from settings
$company_name = get_setting('company_name', 'KINGLANG TOURS AND TRANSPORT SERVICES INC.');
$company_address = get_setting('company_address', '295-B, Purok 4, M. L. Quezon Ave, Lower Bicutan, Taguig, 1632 Metro Manila');
$company_contact = get_setting('company_contact', '0917-882-2727 / 0932-882-2727');
$company_email = get_setting('company_email', 'bsmillamina@yahoo.com');

// Get bank details from settings
$bank_name = get_setting('bank_name', 'BPI Cainta Ortigas Extension Branch');
$bank_account_name = get_setting('bank_account_name', 'KINGLANG TOURS AND TRANSPORT SERVICES INC.');
$bank_account_number = get_setting('bank_account_number', '4091-0050-05');
$bank_swift_code = get_setting('bank_swift_code', 'BPOIPHMM');

// Process destinations list
$destinations = $booking['destination'] ?? 'N/A';
if (!empty($stops)) {
    $stopList = array_column($stops, 'location');
    if (!empty($stopList)) {
        $destinations = implode(' <i class="bi bi-arrow-right mx-1 text-danger"></i> ', $stopList) . ' <i class="bi bi-arrow-right mx-1 text-danger"></i> ' . $booking['destination'];
    }
}

// Format the date
function formatDate($date) {
    if (!$date) return 'N/A';
    $dateObj = new DateTime($date);
    return $dateObj->format('F j, Y');
}

// Calculate rates
$totalCost = (float)($booking['total_cost'] ?? 0);
$numberOfBuses = (int)($booking['number_of_buses'] ?? 1);
$unitCost = $numberOfBuses > 0 ? $totalCost / $numberOfBuses : 0;
$regularRate = $unitCost * 1.4; // 40% markup for "regular" rate

// Format currency
function formatCurrency($amount) {
    return '₱' . number_format($amount, 2, '.', ',');
}

// Get contract date
$contract_date = isset($booking['confirmed_at']) && !empty($booking['confirmed_at']) 
    ? formatDate($booking['confirmed_at']) 
    : date('F j, Y');

// Get client information
$client_name = $booking['client_name'] ?? '';
$company_name_client = $booking['company_name'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transportation Agreement - #<?php echo $booking['booking_id'] ?? 'New'; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/../../../public/css/bootstrap/bootstrap.min.css">
    <style>
        :root {
            --primary-green: #198754;
            --secondary-green: #28a745;
            --light-green: #d1f7c4;
            --hover-green: #20c997;
        }
        
        body {
            font-family: 'Work Sans', sans-serif;
            background-color: #f8f9fa;
            padding: 15px;
            font-size: 0.85rem;
            line-height: 1.4;
            color: #333;
        }
        
        .contract-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            padding: 25px;
            animation: fadeIn 0.5s ease;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e9ecef;
        }
        
        .header h3 {
            color: var(--primary-green);
            font-weight: 700;
            margin-bottom: 6px;
            font-size: 1.4rem;
        }
        
        .header p {
            margin-bottom: 3px;
            font-size: 0.85rem;
        }
        
        .title {
            font-size: 1.3rem;
            font-weight: 700;
            text-align: center;
            margin: 15px 0;
            text-transform: uppercase;
            color: var(--primary-green);
            padding-bottom: 10px;
            position: relative;
        }
        
        .title:after {
            content: "";
            position: absolute;
            width: 60px;
            height: 3px;
            background-color: var(--primary-green);
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }
        
        .booking-details {
            background-color: rgba(209, 247, 196, 0.2);
            border-radius: 8px;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-left: 4px solid var(--primary-green);
        }
        
        .booking-details p {
            margin-bottom: 6px;
        }
        
        .booking-details strong {
            color: #333;
            font-weight: 600;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0 20px;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.05);
            font-size: 0.85rem;
        }
        
        table th {
            background-color: var(--light-green);
            color: #333;
            font-weight: 600;
            text-align: left;
            padding: 10px;
        }
        
        table td {
            padding: 10px;
            border-bottom: 1px solid #e9ecef;
        }
        
        table tr:last-child td {
            border-bottom: none;
        }
        
        table tr:hover td {
            background-color: rgba(209, 247, 196, 0.1);
        }
        
        .total {
            text-align: right;
            font-weight: 700;
            font-size: 1rem;
            padding: 12px 15px;
            margin-bottom: 20px;
            color: var(--primary-green);
            background-color: #f8f9fa;
            border-radius: 8px;
            border-right: 4px solid var(--primary-green);
        }
        
        .section {
            margin: 20px 0;
        }
        
        .section-title {
            font-weight: 700;
            margin-bottom: 8px;
            text-transform: uppercase;
            color: var(--primary-green);
            font-size: 1rem;
            display: flex;
            align-items: center;
        }
        
        .section-title::before {
            content: "";
            display: inline-block;
            width: 5px;
            height: 18px;
            background-color: var(--primary-green);
            margin-right: 8px;
            border-radius: 3px;
        }
        
        .section-title i {
            margin-right: 5px;
            font-size: 0.9rem;
        }
        
        ul {
            padding-left: 18px;
            margin-bottom: 10px;
        }
        
        ul li {
            margin-bottom: 6px;
            position: relative;
        }
        
        ul li::marker {
            color: var(--primary-green);
        }
        
        .compact-list li {
            margin-bottom: 4px;
        }
        
        .agreement-section {
            background-color: rgba(209, 247, 196, 0.2);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        
        .signatures {
            margin-top: 30px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-block {
            width: 48%;
            text-align: center;
        }
        
        .signature-line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
            font-weight: 600;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
            font-size: 0.8rem;
        }
        
        .footer a {
            color: var(--primary-green);
            text-decoration: none;
        }
        
        .print-info {
            text-align: right;
            color: #6c757d;
            font-size: 0.7rem;
            margin-top: 5px;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background-color: var(--primary-green);
            color: white;
            border: none;
            border-radius: 50px;
            padding: 8px 15px;
            font-size: 0.85rem;
            cursor: pointer;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }
        
        .print-button i {
            margin-right: 5px;
        }
        
        .print-button:hover {
            background-color: var(--secondary-green);
            transform: translateY(-2px);
            box-shadow: 0 5px 12px rgba(0, 0, 0, 0.3);
        }
        
        @media print {
            body {
                padding: 0;
                background-color: white;
            }
            
            .contract-container {
                box-shadow: none;
                padding: 10px;
                max-width: 100%;
            }
            
            .print-button {
                display: none;
            }
            
            a {
                text-decoration: none;
                color: inherit;
            }
            
            .table th {
                background-color: #f8f9fa !important;
                color: #333 !important;
            }
            
            @page {
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()"><i class="bi bi-printer"></i> Print Contract</button>
    
    <div class="contract-container">
        <div class="header">
            <h3><?php echo htmlspecialchars($company_name); ?></h3>
            <p><?php echo htmlspecialchars($company_address); ?></p>
            <p>Contact: <?php echo htmlspecialchars($company_contact); ?></p>
            <p>Email: <?php echo htmlspecialchars($company_email); ?></p>
        </div>
        
        <h4 class="title">Transportation Service Agreement</h4>
        
        <div class="booking-details">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Contract Date:</strong> <?php echo $contract_date; ?></p>
                    <p><strong>Client Name:</strong> <?php echo htmlspecialchars($client_name); ?></p>
                    <?php if (!empty($company_name_client)): ?>
                    <p><strong>Company:</strong> <?php echo htmlspecialchars($company_name_client); ?></p>
                    <?php endif; ?>
                    <p><strong>Contact Number:</strong> <?php echo htmlspecialchars($booking['contact_number'] ?? 'N/A'); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($booking['email'] ?? 'N/A'); ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Booking Reference:</strong> #<?php echo $booking['booking_id'] ?? 'New'; ?></p>
                    <p><strong>Trip Duration:</strong> <?php echo $booking['number_of_days'] ?? '1'; ?> day(s)</p>
                    <p><strong>Trip Date:</strong> <?php echo formatDate($booking['date_of_tour'] ?? null); ?></p>
                    <?php if (!empty($booking['end_of_tour'])): ?>
                    <p><strong>End Date:</strong> <?php echo formatDate($booking['end_of_tour']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="section">
            <div class="section-title">
                TRIP DETAILS
            </div>
            <p>This agreement covers the provision of transportation services as detailed below:</p>
            
            <table>
                <tr>
                    <th>Service Description</th>
                    <th>Details</th>
                </tr>
                <tr>
                    <td>Pickup Point</td>
                    <td><?php echo htmlspecialchars($booking['pickup_point'] ?? 'N/A'); ?></td>
                </tr>
                <tr>
                    <td>Destination</td>
                    <td><?php echo $destinations; ?></td>
                </tr>
                <tr>
                    <td>Number of Buses</td>
                    <td><?php echo $booking['number_of_buses'] ?? '1'; ?></td>
                </tr>
                <tr>
                    <td>Service Period</td>
                    <td><?php echo formatDate($booking['date_of_tour'] ?? null); ?> 
                    <?php if (!empty($booking['end_of_tour'])): ?>
                    to <?php echo formatDate($booking['end_of_tour']); ?>
                    <?php endif; ?>
                    (<?php echo $booking['number_of_days'] ?? '1'; ?> day(s))</td>
                </tr>
            </table>
        </div>
        
        <div class="section">
            <div class="section-title">
                RATE & PAYMENT TERMS
            </div>
            <table>
                <tr>
                    <th>Item</th>
                    <th>Description</th>
                    <th>Amount</th>
                </tr>
                <tr>
                    <td>Regular Rate</td>
                    <td>Standard tour package rate</td>
                    <td><?php echo formatCurrency($regularRate); ?></td>
                </tr>
                <tr>
                    <td>Special Client Rate</td>
                    <td>Discounted rate for booking #<?php echo $booking['booking_id'] ?? 'New'; ?></td>
                    <td><?php echo formatCurrency($unitCost); ?></td>
                </tr>
                <tr>
                    <td>Total for <?php echo $booking['number_of_buses'] ?? '1'; ?> bus(es)</td>
                    <td>Complete tour package</td>
                    <td><?php echo formatCurrency($totalCost); ?></td>
                </tr>
            </table>
            
            <div class="total">
                Total Contract Value: <?php echo formatCurrency($totalCost); ?>
            </div>
            
            <div class="section-title">
                PAYMENT INFORMATION
            </div>
            <p>Please make payments to the following bank account:</p>
            <ul class="compact-list">
                <li><strong>Bank Name:</strong> <?php echo htmlspecialchars($bank_name); ?></li>
                <li><strong>Account Name:</strong> <?php echo htmlspecialchars($bank_account_name); ?></li>
                <li><strong>Account Number:</strong> <?php echo htmlspecialchars($bank_account_number); ?></li>
                <?php if (!empty($bank_swift_code)): ?>
                <li><strong>Swift Code:</strong> <?php echo htmlspecialchars($bank_swift_code); ?></li>
                <?php endif; ?>
            </ul>
            
            <p><strong>Payment Schedule:</strong></p>
            <ul>
                <li>50% deposit is required to confirm the booking</li>
                <li>Remaining balance must be paid at least 3 days before the trip date</li>
                <li>Proof of payment should be sent to <?php echo htmlspecialchars($company_email); ?></li>
            </ul>
        </div>
        
        <div class="section">
            <div class="section-title">
                TERMS & CONDITIONS
            </div>
            
            <div class="agreement-section">
                <p>By accepting this contract, both parties agree to the following terms:</p>
                
                <p><strong>Bus Services:</strong></p>
                <ul>
                    <li>The service includes transportation via air-conditioned bus(es) with a licensed driver for the specified duration.</li>
                    <li>Operation hours are from 5:00 AM to 10:00 PM. Extended hours may incur additional charges.</li>
                    <li>The bus will be available at the designated pickup point 30 minutes before the scheduled departure time.</li>
                </ul>
                
                <p><strong>Cancellation Policy:</strong></p>
                <ul>
                    <li>Cancellations made 7 or more days before the trip date: 80% refund of the deposit</li>
                    <li>Cancellations made 3-6 days before the trip date: 50% refund of the deposit</li>
                    <li>Cancellations made less than 3 days before the trip date: No refund</li>
                </ul>
                
                <p><strong>Client Responsibilities:</strong></p>
                <ul>
                    <li>Ensure all passengers adhere to safety regulations and the driver's instructions</li>
                    <li>Be responsible for any damage to the vehicle caused by passengers</li>
                    <li>Provide an accurate itinerary and adhere to the agreed schedule</li>
                </ul>
                
                <p><strong>Service Provider Responsibilities:</strong></p>
                <ul>
                    <li>Provide well-maintained, clean, and fully functional vehicles</li>
                    <li>Ensure punctual arrival at the pickup point</li>
                    <li>Offer professional and courteous service throughout the trip</li>
                </ul>
            </div>
        </div>
        
        <div class="signatures">
            <div class="signature-block">
                <p class="signature-line"><?php echo htmlspecialchars($client_name); ?></p>
                <p>Client</p>
            </div>
            
            <div class="signature-block">
                <p class="signature-line">Authorized Representative</p>
                <p><?php echo htmlspecialchars($company_name); ?></p>
            </div>
        </div>
        
        <div class="footer">
            <p>Thank you for choosing <?php echo htmlspecialchars($company_name); ?>!</p>
            <p>For any inquiries, please contact us at <?php echo htmlspecialchars($company_contact); ?> or <?php echo htmlspecialchars($company_email); ?></p>
        </div>
        
        <div class="print-info">
            Generated on <?php echo date('Y-m-d H:i:s'); ?> | Booking #<?php echo $booking['booking_id'] ?? 'New'; ?>
        </div>
    </div>
    
    <script>
        // Automatically open print dialog when page loads
        window.onload = function() {
            // Wait a short time for styling to render
            setTimeout(function() {
                // Only auto-print if this isn't being displayed in an iframe or embedded context
                if (window.self === window.top) {
                    window.print();
                }
            }, 500);
        };
    </script>
</body>
</html> 