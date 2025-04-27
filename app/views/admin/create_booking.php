<?php
// Include authentication check from controller 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Booking | Admin</title>
    <!-- CSS Libraries -->
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/public/css/admin/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css">
    <!-- Addon CSS -->
    <style>
        :root {
            --primary: #2c7be5;
            --primary-dark: #1657af;
            --secondary: #6c757d;
            --success: #0d6efd;
            --danger: #e63757;
            --warning: #f6c343;
            --info: #39afd1;
            --light: #f9fbfd;
            --dark: #12263f;
            --border-color: #e3ebf6;
            --shadow-sm: 0 0.125rem 0.25rem rgba(18, 38, 63, 0.075);
            --shadow: 0 0.5rem 1rem rgba(18, 38, 63, 0.15);
            --shadow-lg: 0 1rem 2rem rgba(18, 38, 63, 0.175);
            --transition-base: all 0.2s ease;
        }

        body {
            background-color: #f9fbfd;
            color: #12263f;
        }

        .page-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }

        .page-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
            color: var(--dark);
        }

        .page-subtitle {
            color: var(--secondary);
            font-weight: 400;
            margin-bottom: 0;
        }

        .booking-form-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        @media (max-width: 992px) {
            .booking-form-container {
                grid-template-columns: 1fr;
            }
        }
        
        .card {
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            overflow: hidden;
            height: 100%;
        }
        
        .booking-form {
            padding: 1.5rem;
        }
        
        .map-container {
            height: 100%;
            min-height: 600px;
        }
        
        .form-section {
            margin-bottom: 2rem;
            position: relative;
        }
        
        .section-title {
            display: flex;
            align-items: center;
            font-size: 1.125rem;
            font-weight: 600;
            margin-bottom: 1.25rem;
            color: var(--dark);
            position: relative;
        }
        
        .section-title::before {
            content: '';
            display: inline-block;
            width: 0.25rem;
            height: 1.5rem;
            background-color: var(--primary);
            margin-right: 0.75rem;
            border-radius: 0.125rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-full {
            grid-column: 1 / -1;
        }
        
        .form-group {
            margin-bottom: 1.25rem;
            position: relative;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            font-size: 0.875rem;
            color: var(--dark);
        }
        
        .form-label-required::after {
            content: '*';
            color: var(--danger);
            margin-left: 0.25rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: var(--transition-base);
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.075);
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.125rem rgba(44, 123, 229, 0.25);
            outline: none;
        }

        .form-control::placeholder {
            color: #b1c2d9;
        }
        
        .form-text {
            display: block;
            margin-top: 0.25rem;
            font-size: 0.75rem;
            color: var(--secondary);
        }
        
        .address-field {
            position: relative;
        }
        
        .add-stop {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--primary);
            font-size: 1.125rem;
            z-index: 5;
            background-color: #fff;
            padding: 0.25rem;
            border-radius: 50%;
            box-shadow: var(--shadow-sm);
            transition: var(--transition-base);
        }
        
        .add-stop:hover {
            color: var(--primary-dark);
            transform: translateY(-50%) scale(1.1);
        }
        
        .remove-stop {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: var(--danger);
            font-size: 1.125rem;
            z-index: 5;
            background-color: #fff;
            padding: 0.25rem;
            border-radius: 50%;
            box-shadow: var(--shadow-sm);
            transition: var(--transition-base);
        }
        
        .remove-stop:hover {
            transform: translateY(-50%) scale(1.1);
        }
        
        .suggestions {
            position: absolute;
            top: calc(100% + 0.25rem);
            left: 0;
            right: 0;
            background: white;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            max-height: 250px;
            overflow-y: auto;
            z-index: 100;
            list-style: none;
            padding: 0;
            margin: 0;
            display: none;
            box-shadow: var(--shadow);
        }
        
        .suggestion-item {
            padding: 0.75rem 1rem;
            cursor: pointer;
            border-bottom: 1px solid var(--border-color);
            transition: var(--transition-base);
        }
        
        .suggestion-item:last-child {
            border-bottom: none;
        }
        
        .suggestion-item:hover {
            background-color: rgba(44, 123, 229, 0.1);
        }
        
        .counter-input {
            display: flex;
            align-items: center;
            background-color: #f9fbfd;
            border: 1px solid var(--border-color);
            border-radius: 0.375rem;
            overflow: hidden;
        }
        
        .counter-input .counter-value {
            flex: 1;
            text-align: center;
            font-weight: 600;
            padding: 0.75rem 0;
            min-width: 40px;
        }
        
        .counter-btn {
            background: #f1f4f8;
            border: none;
            color: var(--primary);
            font-size: 1.125rem;
            cursor: pointer;
            padding: 0.75rem 1rem;
            transition: var(--transition-base);
            flex-shrink: 0;
        }
        
        .counter-btn:hover {
            background-color: #e3ebf6;
            color: var(--primary-dark);
        }
        
        .counter-btn:disabled {
            color: var(--secondary);
            cursor: not-allowed;
            background-color: #f1f4f8;
        }
        
        .card-summary {
            background-color: #f9fbfd;
            border-radius: 0.375rem;
            margin-top: 1.5rem;
            overflow: hidden;
            border: 1px solid var(--border-color);
        }
        
        .card-summary-header {
            padding: 1rem 1.25rem;
            background-color: #f1f4f8;
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            color: var(--dark);
            font-size: 0.9375rem;
        }
        
        .card-summary-body {
            padding: 1.25rem;
        }
        
        .cost-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            font-size: 0.875rem;
        }

        .cost-item-label {
            color: var(--secondary);
        }
        
        .cost-item-value {
            font-weight: 500;
            color: var(--dark);
        }
        
        .cost-total {
            display: flex;
            justify-content: space-between;
            padding-top: 0.75rem;
            margin-top: 0.75rem;
            border-top: 1px dashed var(--border-color);
        }
        
        .cost-total-label {
            font-weight: 600;
            font-size: 1rem;
            color: var(--dark);
        }
        
        .cost-total-value {
            font-weight: 700;
            font-size: 1.125rem;
            color: var(--primary);
        }
        
        .btn {
            display: inline-block;
            font-weight: 600;
            text-align: center;
            vertical-align: middle;
            user-select: none;
            border: 1px solid transparent;
            padding: 0.75rem 1.5rem;
            font-size: 0.9375rem;
            line-height: 1.5;
            border-radius: 0.375rem;
            transition: var(--transition-base);
            cursor: pointer;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }
        
        .btn-block {
            display: block;
            width: 100%;
        }
        
        .payment-card {
            background-color: #f9fbfd;
            border-radius: 0.375rem;
            padding: 1.25rem;
            border: 1px solid var(--border-color);
        }
        
        /* Status indicators */
        .is-verified {
            border-color: var(--success) !important;
        }
        
        .is-valid {
            border-color: var(--success) !important;
        }
        
        .is-invalid {
            border-color: var(--danger) !important;
        }
        
        .error-feedback {
            display: none;
            color: var(--danger);
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }

        /* Tooltip for address verification */
        .address-verified-icon {
            position: absolute;
            top: 50%;
            right: 2.5rem;
            transform: translateY(-50%);
            color: var(--success);
            display: none;
        }

        /* Badge styling */
        .badge {
            display: inline-block;
            padding: 0.25em 0.5em;
            font-size: 75%;
            font-weight: 600;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: 0.25rem;
        }

        .badge-required {
            background-color: rgba(230, 55, 87, 0.1);
            color: var(--danger);
        }

        /* Make the page look good on mobile */
        @media (max-width: 576px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Include Admin Sidebar -->
    <?php include_once __DIR__ . "/../assets/admin_sidebar.php"; ?>
    
    <div class="content collapsed" id="content">
        
        <div class="container-fluid py-3 px-4 px-xl-4">
            <!-- Header with admin profile -->
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0 mb-2">
                <div class="p-0">
                    <h3><i class="bi bi-calendar-check me-2 text-success"></i>Create Booking</h3>
                    <p class="text-muted mb-0">Create a new booking on behalf of a client</p>
                </div>
                <?php include_once __DIR__ . "/../assets/admin_profile.php"; ?>
            </div>
            
            <div class="booking-form-container">
                <div class="card">
                    <div class="booking-form">
                        <form id="adminBookingForm">
                            <!-- Client Information Section -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="bi bi-person-fill me-2"></i>
                                    Client Information
                                </h3>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="clientName" class="form-label-required">Client Name</label>
                                        <input type="text" id="clientName" name="clientName" class="form-control" placeholder="Enter full name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="contactNumber" class="form-label-required">Contact Number</label>
                                        <input type="text" id="contactNumber" name="contactNumber" class="form-control" placeholder="Enter phone number" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label-required">Email Address</label>
                                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter email address" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="address">Residential Address</label>
                                        <input type="text" id="address" name="address" class="form-control" placeholder="Enter residential address">
                                        <small class="form-text">Optional: Needed only for billing purposes</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Trip Details Section -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="bi bi-geo-alt-fill me-2"></i>
                                    Trip Details
                                </h3>
                                
                                <div class="form-group">
                                    <label for="pickupPoint" class="form-label-required">Pickup Location</label>
                                    <div class="address-field">
                                        <input type="text" id="pickupPoint" name="pickupPoint" class="form-control address" 
                                            autocomplete="off" placeholder="Enter pickup location" data-validated="false" required>
                                        <i class="bi bi-check-circle-fill address-verified-icon"></i>
                                        <ul id="pickupPointSuggestions" class="suggestions"></ul>
                                    </div>
                                    <div class="error-feedback" id="pickupPointError">Please enter a valid pickup location</div>
                                </div>
                                
                                <div id="stopContainer">
                                    <div class="form-group">
                                        <label for="destination" class="form-label-required">Destination</label>
                                        <div class="address-field">
                                            <input type="text" id="destination" name="destination" class="form-control address added-stop" 
                                                autocomplete="off" placeholder="Enter destination" data-validated="false" required>
                                            <i class="bi bi-check-circle-fill address-verified-icon"></i>
                                            <i class="bi bi-plus-circle-fill add-stop" id="addStop" title="Add stop"></i>
                                            <ul id="destinationSuggestions" class="suggestions"></ul>
                                        </div>
                                        <div class="error-feedback" id="destinationError">Please enter a valid destination</div>
                                    </div>
                                </div>
                                
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="dateOfTour" class="form-label-required">Date of Tour</label>
                                        <input type="text" id="dateOfTour" name="dateOfTour" class="form-control" placeholder="Select start date" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="pickupTime" class="form-label-required">Pickup Time</label>
                                        <select id="pickupTime" name="pickupTime" class="form-control" required>
                                            <option value="" disabled selected>Select pickup time</option>
                                            <option value="04:00:00">4:00 AM</option>
                                            <option value="04:30:00">4:30 AM</option>
                                            <option value="05:00:00">5:00 AM</option>
                                            <option value="05:30:00">5:30 AM</option>
                                            <option value="06:00:00">6:00 AM</option>
                                            <option value="06:30:00">6:30 AM</option>
                                            <option value="07:00:00">7:00 AM</option>
                                            <option value="07:30:00">7:30 AM</option>
                                            <option value="08:00:00">8:00 AM</option>
                                            <option value="08:30:00">8:30 AM</option>
                                            <option value="09:00:00">9:00 AM</option>
                                            <option value="09:30:00">9:30 AM</option>
                                            <option value="10:00:00">10:00 AM</option>
                                            <option value="10:30:00">10:30 AM</option>
                                            <option value="11:00:00">11:00 AM</option>
                                            <option value="11:30:00">11:30 AM</option>
                                            <option value="12:00:00">12:00 PM</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="numberOfDays" class="form-label-required">Number of Days</label>
                                        <div class="counter-input">
                                            <button type="button" class="counter-btn" id="decreaseDays" disabled>-</button>
                                            <span class="counter-value" id="numberOfDays">1</span>
                                            <button type="button" class="counter-btn" id="increaseDays">+</button>
                                            <input type="hidden" name="numberOfDays" id="numberOfDaysInput" value="1">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="numberOfBuses" class="form-label-required">Number of Buses</label>
                                        <div class="counter-input">
                                            <button type="button" class="counter-btn" id="decreaseBuses" disabled>-</button>
                                            <span class="counter-value" id="numberOfBuses">1</span>
                                            <button type="button" class="counter-btn" id="increaseBuses">+</button>
                                            <input type="hidden" name="numberOfBuses" id="numberOfBusesInput" value="1">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group form-full">
                                        <label for="estimatedPax">Estimated Number of Passengers</label>
                                        <input type="number" id="estimatedPax" name="estimatedPax" class="form-control" min="1" placeholder="Enter estimated number of passengers">
                                        <small class="form-text">This helps us prepare adequately for the group size</small>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Cost Details Section -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="bi bi-currency-dollar me-2"></i>
                                    Cost Details
                                </h3>
                                
                                <div class="card-summary">
                                    <div class="card-summary-header">
                                        Trip Cost Summary
                                    </div>
                                    <div class="card-summary-body">
                                        <div class="cost-item">
                                            <span class="cost-item-label">Base Cost:</span>
                                            <span class="cost-item-value" id="baseCost">₱0.00</span>
                                        </div>
                                        <div class="cost-item">
                                            <span class="cost-item-label">Diesel Cost:</span>
                                            <span class="cost-item-value" id="dieselCost">₱0.00</span>
                                        </div>
                                        <div class="form-group">
                                            <label for="discount">Discount (%)</label>
                                            <input type="number" id="discount" name="discount" class="form-control" min="0" max="100" value="0" placeholder="Enter discount percentage">
                                        </div>
                                        <div class="cost-total">
                                            <span class="cost-total-label">Total Cost:</span>
                                            <span class="cost-total-value" id="totalCost">₱0.00</span>
                                            <input type="hidden" name="totalCost" id="totalCostInput" value="0">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group mt-3">
                                    <label for="notes">Notes</label>
                                    <textarea id="notes" name="notes" class="form-control" rows="3" placeholder="Add any special instructions or notes here"></textarea>
                                </div>
                            </div>
                            
                            <!-- Payment Information -->
                            <div class="form-section">
                                <h3 class="section-title">
                                    <i class="bi bi-credit-card-fill me-2"></i>
                                    Initial Payment <span class="badge badge-required">Optional</span>
                                </h3>
                                <div class="payment-card">
                                    <div class="form-grid">
                                        <div class="form-group">
                                            <label for="amountPaid">Amount Paid</label>
                                            <input type="number" id="amountPaid" name="initialPayment[amountPaid]" class="form-control" min="0" step="0.01" placeholder="Enter amount paid">
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="paymentMethod">Payment Method</label>
                                            <select id="paymentMethod" name="initialPayment[paymentMethod]" class="form-control">
                                                <option value="">Select payment method</option>
                                                <option value="Cash">Cash</option>
                                                <option value="GCash">GCash</option>
                                                <option value="Maya">Maya</option>
                                                <option value="Bank Transfer">Bank Transfer</option>
                                                <option value="Credit Card">Credit Card</option>
                                            </select>
                                        </div>
                                        
                                        <div class="form-group form-full">
                                            <label for="paymentReference">Reference Number</label>
                                            <input type="text" id="paymentReference" name="initialPayment[paymentReference]" class="form-control" placeholder="Enter payment reference number">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary btn-block">Create Booking</button>
                        </form>
                    </div>
                </div>
                
                <div class="card map-container" id="map">
                    <!-- Google Map will be loaded here -->
                </div>
            </div>
        </div>
    </div>
    
    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="/public/js/admin/admin_booking.js"></script>
    <script src="../../../public/js/assets/sidebar.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASHotkPROmUL_mheV_L9zXarFIuRAIMRs&callback=initMap" async defer></script>

    <script>
        // Add verified icon showing when address is validated
        document.addEventListener('DOMContentLoaded', function() {
            // Setup address verification visual indicators
            document.querySelectorAll('.address').forEach(input => {
                const verifiedIcon = input.parentElement.querySelector('.address-verified-icon');
                
                if (verifiedIcon) {
                    input.addEventListener('input', function() {
                        verifiedIcon.style.display = 'none';
                    });
                    
                    // Add a mutation observer to watch for data-validated attribute changes
                    const observer = new MutationObserver(function(mutations) {
                        mutations.forEach(function(mutation) {
                            if (mutation.attributeName === 'data-validated') {
                                if (input.dataset.validated === 'true') {
                                    verifiedIcon.style.display = 'block';
                                } else {
                                    verifiedIcon.style.display = 'none';
                                }
                            }
                        });
                    });
                    
                    observer.observe(input, { attributes: true });
                }
            });

            // Manual cost calculation when fields change
            setupManualCostCalculation();
        });

        // Function to set up manual cost calculation
        function setupManualCostCalculation() {
            // Define regional rates as in BookingController
            const regionalRates = {
                'NCR': 19560, // Metro Manila
                'CAR': 117539, // Cordillera Administrative Region
                'Region 1': 117539, // Ilocos Region
                'Region 2': 71040, // Cagayan Valley
                'Region 3': 45020, // Central Luzon
                'Region 4A': 20772, // Calabarzon
            };
            
            // Default region and rate
            let currentRegion = 'Region 4A';
            let baseRate = regionalRates[currentRegion];
            
            // Diesel price
            const averageDieselPrice = 65; // Average diesel price in PHP
            const fuelEfficiency = 3; // km per liter
            const averageDistancePerDay = 150; // km - estimated average distance

            // Elements to watch for changes
            const pickupPointEl = document.getElementById('pickupPoint');
            const destinationEl = document.getElementById('destination');
            const discountEl = document.getElementById('discount');

            // Add event listeners to update costs when values change
            document.getElementById('increaseDays').addEventListener('click', updateCosts);
            document.getElementById('decreaseDays').addEventListener('click', updateCosts);
            document.getElementById('increaseBuses').addEventListener('click', updateCosts);
            document.getElementById('decreaseBuses').addEventListener('click', updateCosts);
            discountEl.addEventListener('input', updateCosts);
            
            // Also update when addresses change
            if (pickupPointEl) {
                pickupPointEl.addEventListener('change', updateRegionBasedOnAddress);
            }
            if (destinationEl) {
                destinationEl.addEventListener('change', updateRegionBasedOnAddress);
            }

            // Initial calculation
            updateCosts();

            // Function to detect region from address input
            function updateRegionBasedOnAddress() {
                const addresses = [];
                if (pickupPointEl && pickupPointEl.value) addresses.push(pickupPointEl.value);
                if (destinationEl && destinationEl.value) addresses.push(destinationEl.value);
                
                // Add any stop addresses if they exist
                document.querySelectorAll('.added-stop').forEach(stopEl => {
                    if (stopEl.value) addresses.push(stopEl.value);
                });
                
                if (addresses.length === 0) return;
                
                // Simple region detection based on keywords (simplified version of the PHP implementation)
                const regionKeywords = {
                    'NCR': ['metro manila', 'ncr', 'manila', 'quezon city', 'makati', 'pasig', 'taguig', 'mandaluyong', 'pasay', 'bgc'],
                    'CAR': ['cordillera', 'car', 'baguio', 'benguet', 'mountain province', 'mt. province', 'ifugao', 'abra', 'kalinga'],
                    'Region 1': ['ilocos', 'region 1', 'la union', 'pangasinan', 'laoag', 'vigan', 'san fernando', 'dagupan'],
                    'Region 2': ['cagayan valley', 'region 2', 'cagayan', 'isabela', 'nueva vizcaya', 'quirino', 'batanes', 'tuguegarao'],
                    'Region 3': ['central luzon', 'region 3', 'bulacan', 'pampanga', 'tarlac', 'zambales', 'nueva ecija', 'bataan', 'angeles'],
                    'Region 4A': ['calabarzon', 'region 4a', 'cavite', 'laguna', 'batangas', 'rizal', 'quezon', 'lucena', 'tagaytay', 'calamba']
                };
                
                // Count matches for each region
                const regionMatches = {
                    'NCR': 0,
                    'CAR': 0,
                    'Region 1': 0,
                    'Region 2': 0,
                    'Region 3': 0,
                    'Region 4A': 0
                };
                
                // Look for keywords in the addresses
                addresses.forEach(address => {
                    const lowerAddress = address.toLowerCase();
                    
                    for (const [region, keywords] of Object.entries(regionKeywords)) {
                        keywords.forEach(keyword => {
                            if (lowerAddress.includes(keyword)) {
                                regionMatches[region]++;
                            }
                        });
                    }
                });
                
                // Find the region with the most matches
                let maxMatches = 0;
                let matchedRegion = 'Region 4A'; // Default
                
                for (const [region, matches] of Object.entries(regionMatches)) {
                    if (matches > maxMatches) {
                        maxMatches = matches;
                        matchedRegion = region;
                    }
                }
                
                // Update current region and base rate
                currentRegion = matchedRegion;
                baseRate = regionalRates[currentRegion];
                
                // Update costs with new rate
                updateCosts();
            }

            function updateCosts() {
                const days = parseInt(document.getElementById('numberOfDays').textContent) || 1;
                const buses = parseInt(document.getElementById('numberOfBuses').textContent) || 1;
                const discountPercentage = parseFloat(discountEl.value) || 0;

                // Calculate base cost using regional rate
                const baseCost = baseRate * days * buses;
                
                // Calculate diesel cost
                const estimatedDistance = averageDistancePerDay;
                const litersDiesel = estimatedDistance / fuelEfficiency;
                const dieselCost = litersDiesel * averageDieselPrice * days * buses;

                // Calculate total cost
                let totalCost = baseCost + dieselCost;
                
                // Apply discount
                if (discountPercentage > 0) {
                    totalCost = totalCost * (1 - (discountPercentage / 100));
                }

                // Update UI with regionalized cost info
                document.getElementById('baseCost').textContent = `₱${baseCost.toFixed(2)}`;
                document.getElementById('dieselCost').textContent = `₱${dieselCost.toFixed(2)}`;
                document.getElementById('totalCost').textContent = `₱${totalCost.toFixed(2)}`;
                document.getElementById('totalCostInput').value = totalCost.toFixed(2);
                
                // Add region info to the cost summary if it doesn't exist
                const summaryBody = document.querySelector('.card-summary-body');
                let regionInfoElement = document.getElementById('regionInfo');
                
                if (!regionInfoElement && summaryBody) {
                    const regionInfo = document.createElement('div');
                    regionInfo.id = 'regionInfo';
                    regionInfo.className = 'cost-item';
                    regionInfo.innerHTML = `
                        <span class="cost-item-label">Region:</span>
                        <span class="cost-item-value" id="regionValue">${currentRegion}</span>
                    `;
                    
                    // Insert before discount
                    const discountElement = summaryBody.querySelector('div.form-group');
                    if (discountElement) {
                        summaryBody.insertBefore(regionInfo, discountElement);
                    } else {
                        summaryBody.appendChild(regionInfo);
                    }
                    
                    regionInfoElement = document.getElementById('regionValue');
                } else if (regionInfoElement) {
                    regionInfoElement.textContent = currentRegion;
                }
            }
        }
    </script>
</body>
</html> 