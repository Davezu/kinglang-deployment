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
</head>
<body>
    <?php include_once __DIR__ . "/../assets/sidebar.php"; ?>
    
    <div class="content collapsed" id="content">
        <div class="container-fluid py-4 px-4 px-xl-5">
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0">
                <div class="p-0">
                    <h3>Booking Request Details</h3>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <button id="viewInvoiceBtn" class="btn btn-primary">
                        <i class="bi bi-file-earmark-text me-2"></i>View Invoice
                    </button>
                    <?php include_once __DIR__ . "/../assets/user_profile.php"; ?>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-success-subtle">Pickup Point</span>
                                <input type="text" class="form-control" id="pickupPoint" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-success-subtle">Destination</span>
                                <input type="text" class="form-control" id="destination" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-success-subtle">Number of Buses</span>
                                <input type="text" class="form-control" id="numberOfBuses" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-success-subtle">Number of Days</span>
                                <input type="text" class="form-control" id="numberOfDays" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="mb-3">Stops</h5>
                        <div id="stops" class="list-group"></div>
                    </div>

                    <div class="mb-4">
                        <h5 class="mb-3">Route Details</h5>
                        <div class="table-responsive">
                            <table class="table table-hover overflow-hidden rounded">
                                <thead>
                                    <tr>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Distance</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody"></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-success-subtle">Diesel Price</span>
                                <input type="text" class="form-control" id="dieselPrice" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-success-subtle">Total Distance</span>
                                <input type="text" class="form-control" id="totalDistance" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Cost</h5>
                                    <p class="card-text h4" id="totalCost"></p>
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
                                                <p class="mb-1"><strong>Rate Per Bus:</strong> <span id="invoiceBaseRatePerBus"></span></p>
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
                const dieselPrice = parseFloat(data.diesel);
                const numberOfDays = parseInt(booking.number_of_days);
                const numberOfBuses = parseInt(booking.number_of_buses);

                const totalCost = new Intl.NumberFormat().format(totalDistanceInKm * dieselPrice * numberOfDays * numberOfBuses);

                console.log("Booking info: ", data);

                document.getElementById("pickupPoint").value = booking.pickup_point;
                document.getElementById("destination").value = booking.destination;
                document.getElementById("numberOfBuses").value = numberOfBuses;
                document.getElementById("numberOfDays").value = numberOfDays;
                document.getElementById("dieselPrice").value = dieselPrice + " Pesos per liter";
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
                    stopsContainer.innerHTML = '<div class="list-group-item">No stops</div>';
                } else {
                    stops.forEach(stop => {
                        const stopElement = document.createElement("div");
                        stopElement.className = "list-group-item";
                        stopElement.textContent = stop.location;
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
            if (booking.payment_status === "paid") {
                invoiceStatus.textContent = "Paid";
                invoiceStatus.className = "badge bg-success";
            } else if (booking.payment_status === "partially paid") {
                invoiceStatus.textContent = "Partially Paid";
                invoiceStatus.className = "badge bg-warning text-dark";
            } else {
                invoiceStatus.textContent = "Unpaid";
                invoiceStatus.className = "badge bg-danger";
            }
            
            // Set trip details
            document.getElementById("invoicePickupPoint").textContent = booking.pickup_point;
            document.getElementById("invoiceDestination").textContent = booking.destination;
            document.getElementById("invoiceDateOfTour").textContent = new Date(booking.date_of_tour).toLocaleDateString();
            document.getElementById("invoiceEndOfTour").textContent = new Date(booking.end_of_tour).toLocaleDateString();
            document.getElementById("invoiceNumberOfDays").textContent = numberOfDays;
            document.getElementById("invoiceNumberOfBuses").textContent = numberOfBuses;
            document.getElementById("invoiceTotalDistance").textContent = totalDistanceInKm + " km";
            document.getElementById("invoiceBaseRatePerBus").textContent = booking.base_cost + " Pesos per bus";
            
            // Calculate costs and populate the invoice items
            const baseRatePerBus = booking.base_cost;
            const baseRatePerKm = dieselPrice;
            const distanceCost = totalDistanceInKm * baseRatePerKm;
            const daysMultiplier = numberOfDays;
            const busesMultiplier = numberOfBuses;
            
            // Format numbers using currency formatter
            const formatter = new Intl.NumberFormat('en-PH', {
                style: 'currency',
                currency: 'PHP'
            });
            
            // Populate invoice items table
            const invoiceItems = document.getElementById("invoiceItems");
            invoiceItems.innerHTML = "";
            
            // Add base rate item
            const baseRateRow = document.createElement("tr");
            baseRateRow.innerHTML = `
                <td>Base rate per kilometer</td>
                <td class="text-end">${formatter.format(baseRatePerKm)} per km</td>
            `;
            invoiceItems.appendChild(baseRateRow);
            
            // Add distance cost
            const distanceCostRow = document.createElement("tr");
            distanceCostRow.innerHTML = `
                <td>Distance cost (${totalDistanceInKm} km × ${formatter.format(baseRatePerKm)})</td>
                <td class="text-end">${formatter.format(distanceCost)}</td>
            `;
            invoiceItems.appendChild(distanceCostRow);
            
            // Add days multiplier
            const daysRow = document.createElement("tr");
            daysRow.innerHTML = `
                <td>Number of days × ${daysMultiplier}</td>
                <td class="text-end">${formatter.format(distanceCost * daysMultiplier)}</td>
            `;
            invoiceItems.appendChild(daysRow);
            
            // Add buses multiplier
            const busesRow = document.createElement("tr");
            busesRow.innerHTML = `
                <td>Number of buses × ${busesMultiplier}</td>
                <td class="text-end">${formatter.format(booking.total_cost)}</td>
            `;
            invoiceItems.appendChild(busesRow);
            
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
            printWindow.document.write('<style>body { padding: 20px; } @media print { body { padding: 0; } }</style>');
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