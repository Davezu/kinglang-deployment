<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/../../../public/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/../../../public/css/client/payment_styles.css">
    <link rel="stylesheet" href="/../../../public/css/assets/cancel_modal.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Booking Request Details</title>
    <style>
        body, html {
            overflow-x: hidden;
        }
        .content {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .booking-header {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 10px 15px;
            margin-bottom: 15px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .detail-card {
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: transform 0.2s ease;
            height: 100%;
            margin-bottom: 0;
        }
        /* .detail-card:hover {
            transform: translateY(-3px);
        } */
        .card-header {
            padding: 8px 15px;
        }
        .card-body {
            padding: 10px 15px;
        }
        .section-title {
            position: relative;
            padding-bottom: 5px;
            margin-bottom: 10px;
            font-weight: 600;
            color: #333;
            font-size: 0.95rem;
        }
        .cost-card {
            border-radius: 8px;
            background: linear-gradient(135deg, #198754 0%, #0f5132 100%);
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            transition: all 0.2s ease;
            padding: 10px !important;
        }
        /* .cost-card:hover {
            transform: scale(1.02);
        } */
        .input-group {
            margin-bottom: 8px !important;
        }
        .input-group-text {
            min-width: 120px;
            padding: 5px 10px;
            font-size: 0.85rem;
        }
        .form-control {
            padding: 5px 10px;
            font-size: 0.85rem;
            height: auto;
        }
        .route-table {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 0;
        }
        .route-table thead {
            background-color: #f0f9f4;
        }
        .table>:not(caption)>*>* {
            padding: 6px 10px;
        }
        .stops-list .list-group-item {
            border-left: 3px solid #198754;
            margin-bottom: 3px;
            border-radius: 0 5px 5px 0;
            padding: 5px 10px;
            font-size: 0.85rem;
        }
        .action-btn {
            border-radius: 50px;
            padding: 5px 15px;
            font-weight: 500;
        }
        .compact-table td, .compact-table th {
            font-size: 0.85rem;
            padding: 5px 10px !important;
        }
        .badge {
            font-size: 0.7rem;
        }
        h3 {
            font-size: 1.4rem;
        }
        h5 {
            font-size: 0.95rem;
            margin-bottom: 0;
        }
        .fs-1 {
            font-size: 1.8rem !important;
        }
        p.text-muted {
            font-size: 0.8rem;
            margin-bottom: 0;
        }
        .row.g-2 {
            --bs-gutter-y: 0.5rem;
            --bs-gutter-x: 0.5rem;
        }
        .container-fluid {
            padding: 10px 15px;
        }
    </style>
</head>
<body>
    <?php include_once __DIR__ . "/../assets/sidebar.php"; ?>
    
    <div class="content collapsed" id="content">
        <div class="container-fluid py-2 px-3">
            <div class="booking-header d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h3 class="mb-0"><i class="bi bi-clipboard-check me-2"></i>Booking Request Details</h3>
                    <p class="text-muted mb-0">View and manage your booking information</p>
                </div>
                <div class="d-flex align-items-center gap-2 mt-0">
                    <button id="viewInvoiceBtn" class="btn btn-primary btn-sm action-btn">
                        <i class="bi bi-file-earmark-text me-1"></i>View Invoice
                    </button>
                    <?php include_once __DIR__ . "/../assets/user_profile.php"; ?>
                </div>
            </div>

            <div class="row g-2">
                <!-- Trip Information -->
                <div class="col-12">
                    <div class="detail-card card">
                        <div class="card-header bg-white py-2">
                            <h5 class="mb-0"><i class="bi bi-geo-alt me-1"></i>Trip Information</h5>
                        </div>
                        <div class="card-body py-2">
                            <div class="row g-2">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-success-subtle text-success pe-0">
                                            <i class="bi bi-cursor-fill me-1"></i>Pickup
                                        </span>
                                        <input type="text" class="form-control" id="pickupPoint" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-success-subtle text-success">
                                            <i class="bi bi-geo-fill me-1"></i>Destination
                                        </span>
                                        <input type="text" class="form-control" id="destination" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-text bg-success-subtle text-success">
                                            <i class="bi bi-bus-front me-1"></i>Buses
                                        </span>
                                        <input type="text" class="form-control" id="numberOfBuses" readonly>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="input-group">
                                        <span class="input-group-text bg-success-subtle text-success">
                                            <i class="bi bi-calendar3 me-1"></i>Days
                                        </span>
                                        <input type="text" class="form-control" id="numberOfDays" readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-success-subtle text-success">
                                            <i class="bi bi-clock-fill me-1"></i>Pickup Time
                                        </span>
                                        <input type="text" class="form-control" id="pickupTime" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-8">
                    <!-- Route Details -->
                    <div class="detail-card card">
                        <div class="card-header bg-white py-2">
                            <h5 class="mb-0"><i class="bi bi-map me-1"></i>Route Details</h5>
                        </div>
                        <div class="card-body py-2">
                            <div class="table-responsive">
                                <table class="table table-hover route-table compact-table mb-0">
                                    <thead>
                                        <tr>
                                            <th><i class="bi bi-arrow-up-right-circle me-1"></i>From</th>
                                            <th><i class="bi bi-arrow-down-right-circle me-1"></i>To</th>
                                            <th><i class="bi bi-rulers me-1"></i>Distance</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="row g-2">
                        <!-- Cost Details -->
                        <div class="col-12">
                            <div class="detail-card card">
                                <div class="card-header bg-white py-2">
                                    <h5 class="mb-0"><i class="bi bi-cash-coin me-1"></i>Cost Information</h5>
                                </div>
                                <div class="card-body py-2">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-success-subtle text-success">
                                                    <i class="bi bi-fuel-pump me-1"></i>Diesel
                                                </span>
                                                <input type="text" class="form-control" id="dieselPrice" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group">
                                                <span class="input-group-text bg-success-subtle text-success">
                                                    <i class="bi bi-signpost-split me-1"></i>Distance
                                                </span>
                                                <input type="text" class="form-control" id="totalDistance" readonly>
                                            </div>
                                        </div>
                                        <div class="col-12 mt-1">
                                            <div class="cost-card card text-white">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-0 text-white-50" style="font-size: 0.8rem;">Total Trip Cost</h6>
                                                        <h3 class="mb-0" id="totalCost" style="font-size: 1.2rem;"></h3>
                                                    </div>
                                                    <i class="bi bi-cash-stack text-white-50" style="font-size: 1.5rem;"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Stops -->
                        <div class="col-12">
                            <div class="detail-card card">
                                <div class="card-header bg-white py-2">
                                    <h5 class="mb-0"><i class="bi bi-pin-map me-1"></i>Planned Stops</h5>
                                </div>
                                <div class="card-body py-2">
                                    <div id="stops" class="list-group stops-list" style="max-height: 120px; overflow-y: auto;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Modal -->
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="invoiceModalLabel">
                        <i class="bi bi-file-earmark-text me-2"></i>Booking Invoice
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="invoiceContent">
                    <div class="container-fluid">
                        <div class="row mb-4">
                            <div class="col-12 text-center mb-4">
                                <h3 class="mb-0">Kinglang Bus Booking</h3>
                                <p class="text-muted mb-0">M. L. Quezon Avenue, Lower Bicutan, Taguig, Metro Manila, Philippines</p>
                                <p class="text-muted">Phone: (123) 456-7890 | Email: info@kinglangbus.com</p>
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="fw-bold">INVOICE TO:</h6>
                                <p class="mb-1" id="invoiceClientName"></p>
                                <p class="mb-3" id="invoiceClientPhone"></p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h6 class="fw-bold">INVOICE DETAILS:</h6>
                                <p class="mb-1">Invoice #: <span id="invoiceNumber"></span></p>
                                <p class="mb-1">Date: <span id="invoiceDate"></span></p>
                                <p class="mb-3">Status: <span id="invoiceStatus" class="badge bg-success">Issued</span></p>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">Trip Details</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>From:</strong> <span id="invoicePickupPoint"></span></p>
                                                <p class="mb-1"><strong>To:</strong> <span id="invoiceDestination"></span></p>
                                                <p class="mb-1"><strong>Date of Tour:</strong> <span id="invoiceDateOfTour"></span></p>
                                                <p class="mb-3"><strong>End of Tour:</strong> <span id="invoiceEndOfTour"></span></p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Number of Days:</strong> <span id="invoiceNumberOfDays"></span></p>
                                                <p class="mb-1"><strong>Number of Buses:</strong> <span id="invoiceNumberOfBuses"></span></p>
                                                <p class="mb-1"><strong>Total Distance:</strong> <span id="invoiceTotalDistance"></span></p>
                                                <p class="mb-1"><strong>Base Rental Rate Per Bus:</strong> <span id="invoiceBaseRatePerBus"></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <table class="table table-bordered">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Description</th>
                                            <th class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="invoiceItems">
                                        <!-- Items will be dynamically added here -->
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th class="text-end">Total:</th>
                                            <th class="text-end" id="invoiceTotalCost"></th>
                                        </tr>
                                        <tr>
                                            <th class="text-end">Amount Paid:</th>
                                            <th class="text-end" id="invoiceAmountPaid"></th>
                                        </tr>
                                        <tr>
                                            <th class="text-end">Balance:</th>
                                            <th class="text-end" id="invoiceBalance"></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-12">
                                <div class="alert alert-light border">
                                    <p class="mb-0"><strong>Payment Terms:</strong> Payment is due within 14 days.</p>
                                    <p class="mb-0"><strong>Notes:</strong> Thank you for choosing Kinglang Bus Booking Service!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="printInvoiceBtn">
                        <i class="bi bi-printer me-2"></i>Print Invoice
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="../../../public/js/utils/pagination.js"></script>
    <script src="../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/../../../public/js/assets/sidebar.js"></script>
    <!-- Load booking_request.js last to ensure all dependencies are loaded first -->
    <script src="../../../public/js/client/booking_request.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", async function () {
            const bookingId = localStorage.getItem("bookingId");
            const showInvoice = localStorage.getItem("showInvoice");
            localStorage.removeItem("bookingId");
            localStorage.removeItem("showInvoice");
            try {
                const response = await fetch("/get-booking", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ bookingId })
                });

                const data = await response.json();

                const booking = data.booking;
                const stops = data.stops;
                const distances = data.distances;

                const totalDistanceInKm = (data.distances.map(distance => parseFloat(distance.distance)).reduce((acc, curr) => acc + curr, 0) / 1000).toFixed(2);
                const dieselPrice = parseFloat(booking.diesel_price);
                const numberOfDays = parseInt(booking.number_of_days);
                const numberOfBuses = parseInt(booking.number_of_buses);

                const totalCost = new Intl.NumberFormat().format(totalDistanceInKm * dieselPrice * numberOfDays * numberOfBuses);

                console.log("Booking info: ", data);

                document.getElementById("pickupPoint").value = booking.pickup_point;
                document.getElementById("pickupTime").value = booking.pickup_time;
                document.getElementById("destination").value = booking.destination;
                document.getElementById("numberOfBuses").value = numberOfBuses;
                document.getElementById("numberOfDays").value = numberOfDays;
                document.getElementById("dieselPrice").value = dieselPrice + " PHP";
                document.getElementById("totalDistance").value = totalDistanceInKm + " km";
                document.getElementById("totalCost").textContent = booking.total_cost + " Pesos";

                const tbody = document.getElementById("tbody");
                tbody.innerHTML = "";
                distances.forEach(distance => {
                    const distanceInKm = (distance.distance / 1000).toFixed(2);
                    
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td>${distance.origin}</td>
                        <td>${distance.destination}</td>
                        <td>${distanceInKm} km</td>
                    `;
                    tbody.appendChild(tr);
                });

                const stopsContainer = document.getElementById("stops");
                if (stops.length === 0) {
                    stopsContainer.innerHTML = '<div class="list-group-item">No stops planned for this trip</div>';
                } else {
                    stops.forEach((stop, index) => {
                        const stopElement = document.createElement("div");
                        stopElement.className = "list-group-item d-flex align-items-center";
                        stopElement.innerHTML = `
                            <span class="badge bg-success rounded-pill me-2">${index + 1}</span>
                            <span>${stop.location}</span>
                        `;
                        stopsContainer.appendChild(stopElement);
                    });
                }

                // Add event listener for the view invoice button
                document.getElementById("viewInvoiceBtn").addEventListener("click", function() {
                    populateInvoice(booking, stops, distances, totalDistanceInKm, dieselPrice, numberOfDays, numberOfBuses);
                    const invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
                    invoiceModal.show();
                });

                // Add event listener for the print invoice button
                document.getElementById("printInvoiceBtn").addEventListener("click", function() {
                    printInvoice();
                });

                // Automatically show invoice if redirected from invoice button
                if (showInvoice === "true") {
                    populateInvoice(booking, stops, distances, totalDistanceInKm, dieselPrice, numberOfDays, numberOfBuses);
                    const invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
                    invoiceModal.show();
                }

            } catch (error) {
                console.error(error);
            }
        });

        // Function to populate the invoice modal with data
        function populateInvoice(booking, stops, distances, totalDistanceInKm, dieselPrice, numberOfDays, numberOfBuses) {
            // Set client information
            document.getElementById("invoiceClientName").textContent = booking.first_name + " " + booking.last_name;
            document.getElementById("invoiceClientPhone").textContent = booking.contact_number;
            
            // Set invoice details
            document.getElementById("invoiceNumber").textContent = "INV-" + booking.booking_id;
            document.getElementById("invoiceDate").textContent = new Date().toLocaleDateString();
            
            // Update status badge color based on payment status
            const invoiceStatus = document.getElementById("invoiceStatus");
            if (booking.payment_status === "Paid") {
                invoiceStatus.textContent = "Paid";
                invoiceStatus.className = "badge bg-success";
            } else if (booking.payment_status === "Partially Paid") {
                invoiceStatus.textContent = "Partially Paid";
                invoiceStatus.className = "badge bg-warning text-dark";
            } else {
                invoiceStatus.textContent = "Unpaid";
                invoiceStatus.className = "badge bg-danger";
            }

            // Format numbers using currency formatter
            const formatter = new Intl.NumberFormat('en-PH', {
                style: 'currency',
                currency: 'PHP'
            });
            
            // Set trip details
            document.getElementById("invoicePickupPoint").textContent = booking.pickup_point;
            document.getElementById("invoiceDestination").textContent = booking.destination;
            document.getElementById("invoiceDateOfTour").textContent = new Date(booking.date_of_tour).toLocaleDateString();
            document.getElementById("invoiceEndOfTour").textContent = new Date(booking.end_of_tour).toLocaleDateString();
            document.getElementById("invoiceNumberOfDays").textContent = numberOfDays;
            document.getElementById("invoiceNumberOfBuses").textContent = numberOfBuses;
            document.getElementById("invoiceTotalDistance").textContent = totalDistanceInKm + " km";
            document.getElementById("invoiceBaseRatePerBus").textContent = formatter.format(parseFloat(booking.base_rate));
            
            // Calculate costs and populate the invoice items
            const baseRatePerBus = booking.base_cost;
            const baseRatePerKm = dieselPrice;
            const daysMultiplier = numberOfDays;
            const busesMultiplier = numberOfBuses;
            
            // Populate invoice items table
            const invoiceItems = document.getElementById("invoiceItems");
            invoiceItems.innerHTML = "";

            // Add diesel price item
            const dieselPriceRow = document.createElement("tr");
            dieselPriceRow.innerHTML = `
                <td>Current diesel price</td>
                <td class="text-end">${formatter.format(booking.diesel_price)}</td>
            `;
            
            // Add base rate item
            const baseRateRow = document.createElement("tr");
            baseRateRow.innerHTML = `
                <td>Base rental rate per bus</td>
                <td class="text-end">${formatter.format(booking.base_rate)}</td>
            `;

            // Add base cost item
            const baseCostRow = document.createElement("tr");
            baseCostRow.innerHTML = `
                <td>Base cost (${booking.number_of_days} days × ${booking.number_of_buses} buses × ${formatter.format(booking.base_rate)} base rate)</td>
                <td class="text-end">${formatter.format(booking.base_cost)}</td>
            `;
            
            // Add distance cost
            const dieselCostRow = document.createElement("tr");
            dieselCostRow.innerHTML = `
                <td>Diesel cost (${totalDistanceInKm} km × ${formatter.format(booking.diesel_price)} per liter)</td>
                <td class="text-end">${formatter.format(booking.diesel_cost)}</td>
            `;

            invoiceItems.append(dieselPriceRow, baseRateRow, baseCostRow, dieselCostRow);   
            
            // Set the totals
            document.getElementById("invoiceTotalCost").textContent = formatter.format(booking.total_cost);
            
            // Get amount paid from balance
            const amountPaid = booking.total_cost - parseFloat(booking.balance);
            document.getElementById("invoiceAmountPaid").textContent = formatter.format(amountPaid);
            document.getElementById("invoiceBalance").textContent = formatter.format(parseFloat(booking.balance));
        }

        // Function to print the invoice
        function printInvoice() {
            const invoiceContent = document.getElementById("invoiceContent").innerHTML;
            
            // Create a new window for printing - simpler approach to avoid template string issues
            const printWindow = window.open('', '_blank');
            
            // Write the HTML content without using template literals
            printWindow.document.write('<html>');
            printWindow.document.write('<head>');
            printWindow.document.write('<title>Invoice - Kinglang Bus Booking</title>');
            printWindow.document.write('<link rel="stylesheet" href="/../../../public/css/bootstrap/bootstrap.min.css">');
            printWindow.document.write('<style>');
            printWindow.document.write('body { padding: 10px; }');
            printWindow.document.write('@media print {');
            printWindow.document.write('  body { padding: 0; }');
            printWindow.document.write('  .container-fluid { max-width: 100%; }');
            printWindow.document.write('  .table { font-size: 12px; }');
            printWindow.document.write('  .mb-4 { margin-bottom: 0.5rem !important; }');
            printWindow.document.write('  .card { margin-bottom: 0.5rem !important; }');
            printWindow.document.write('  p { margin-bottom: 0.2rem !important; }');
            printWindow.document.write('  h3 { font-size: 18px; }');
            printWindow.document.write('  h6 { font-size: 14px; }');
            printWindow.document.write('  .card-body { padding: 0.5rem !important; }');
            printWindow.document.write('  .alert { padding: 0.5rem !important; margin-bottom: 0 !important; }');
            printWindow.document.write('  .table td, .table th { padding: 0.3rem !important; }');
            printWindow.document.write('  @page { size: auto; margin: 5mm; }');
            printWindow.document.write('}');
            printWindow.document.write('</style>');
            printWindow.document.write('</head>');
            printWindow.document.write('<body>');
            printWindow.document.write(invoiceContent);
            printWindow.document.write('<script>');
            printWindow.document.write('window.onload = function() {');
            printWindow.document.write('window.print();');
            printWindow.document.write('setTimeout(function() { window.close(); }, 500);');
            printWindow.document.write('};');
            printWindow.document.write('<\/script>');
            printWindow.document.write('</body>');
            printWindow.document.write('</html>');
            
            printWindow.document.close();
        }
    </script>
</body>
</html>