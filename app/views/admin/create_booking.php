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
    <link rel="stylesheet" href="/../../../public/css/admin/styles.css">
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

        .booking-container {
            display: flex;
            height: calc(100vh - 100px);
            margin-bottom: 2rem;
        }

        .booking-sidebar {
            width: 450px;
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            padding: 1.5rem;
            overflow-y: auto;
            flex-shrink: 0;
            z-index: 10;
            margin-right: 1rem;
        }

        .booking-map {
            flex-grow: 1;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            position: relative;
        }
        
        .booking-header {
            margin-bottom: 1.5rem;
        }
        
        .booking-title {
            font-size: 1.75rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
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

        .location-input {
            position: relative;
        }

        .location-input-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 18px;
            color: var(--secondary);
        }

        .location-input .form-control {
            padding-left: 40px;
        }

        .add-stop {
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 500;
            color: var(--primary);
            background: none;
            border: none;
            padding: 0.5rem;
            margin-top: 0.5rem;
            cursor: pointer;
            border-radius: 0.375rem;
            width: 100%;
            text-align: center;
        }

        .add-stop:hover {
            background-color: rgba(44, 123, 229, 0.1);
        }

        .add-stop i {
            margin-right: 0.5rem;
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
            width: 100%;
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

        .details-form {
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
            margin-top: 1.5rem;
            display: none;
        }

        /* Make the page look good on mobile */
        @media (max-width: 992px) {
            .booking-container {
                flex-direction: column;
                height: auto;
            }
            
            .booking-sidebar {
                width: 100%;
                margin-right: 0;
                margin-bottom: 1rem;
            }
            
            .booking-map {
                height: 400px;
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
            
            <div class="booking-container">
                <div class="booking-sidebar">
                    <div class="booking-header">
                        <h2 class="booking-title">Book a Trip</h2>
                    </div>
                    
                    <form id="adminBookingForm">
                        <!-- Pickup Location -->
                        <div class="form-group">
                            <div class="location-input">
                                <i class="bi bi-geo-alt-fill location-input-icon"></i>
                                <input type="text" id="pickupPoint" name="pickupPoint" class="form-control" 
                                    placeholder="Pickup Location" autocomplete="off" data-validated="false" required>
                                <ul id="pickupPointSuggestions" class="suggestions"></ul>
                            </div>
                        </div>
                        
                        <!-- Destination Location -->
                        <div class="form-group">
                            <div class="location-input">
                                <i class="bi bi-geo-alt-fill location-input-icon"></i>
                                <input type="text" id="destination" name="destination" class="form-control" 
                                    placeholder="Dropoff Location" autocomplete="off" data-validated="false" required>
                                <ul id="destinationSuggestions" class="suggestions"></ul>
                            </div>
                        </div>
                        
                        <div id="additionalStops"></div>
                        
                        <!-- Add Stop Button -->
                        <button type="button" class="add-stop" id="addStop">
                            <i class="bi bi-plus-circle"></i> Add Stop
                        </button>
                        
                        <!-- Next Button -->
                        <button type="button" id="nextStep" class="btn btn-primary mt-3">Next</button>
                        
                        <!-- Additional Details Form (initially hidden) -->
                        <div class="details-form" id="detailsForm">
                            <h4 class="mb-3">Booking Details</h4>
                            
                            <!-- Client Information -->
                            <div class="form-group">
                                <label for="clientName">Client Name</label>
                                <input type="text" id="clientName" name="clientName" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="contactNumber">Contact Number</label>
                                <input type="text" id="contactNumber" name="contactNumber" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="companyName">Company Name</label>
                                <input type="text" id="companyName" name="companyName" class="form-control">
                                <small class="form-text text-muted">Optional: For corporate bookings</small>
                            </div>
                            
                            <!-- Trip Details -->
                            <div class="form-group">
                                <label for="dateOfTour">Date of Tour</label>
                                <input type="text" id="dateOfTour" name="dateOfTour" class="form-control" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="pickupTime">Pickup Time</label>
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
                                <label for="numberOfDaysSelect">Number of Days</label>
                                <select id="numberOfDaysSelect" name="numberOfDays" class="form-control" required>
                                    <option value="1">1 Day</option>
                                    <option value="2">2 Days</option>
                                    <option value="3">3 Days</option>
                                    <option value="4">4 Days</option>
                                    <option value="5">5 Days</option>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="numberOfBusesSelect">Number of Buses</label>
                                <select id="numberOfBusesSelect" name="numberOfBuses" class="form-control" required>
                                    <option value="1">1 Bus</option>
                                    <option value="2">2 Buses</option>
                                    <option value="3">3 Buses</option>
                                    <option value="4">4 Buses</option>
                                    <option value="5">5 Buses</option>
                                </select>
                            </div>
                            
                            <!-- Cost Details -->
                            <div class="form-group">
                                <label for="discount">Discount (%)</label>
                                <input type="number" id="discount" name="discount" class="form-control" min="0" max="100" value="0">
                            </div>
                            
                            <div class="form-group">
                                <label for="totalCostDisplay">Total Cost</label>
                                <input type="text" id="totalCostDisplay" class="form-control" readonly>
                                <input type="hidden" name="totalCost" id="totalCostInput" value="0">
                            </div>
                            
                            <div class="form-group">
                                <label for="notes">Notes</label>
                                <textarea id="notes" name="notes" class="form-control" rows="3"></textarea>
                            </div>
                            
                            <!-- Submit Button -->
                            <button type="submit" class="btn btn-primary">Create Booking</button>
                        </div>
                    </form>
                </div>
                
                <div class="booking-map" id="map">
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
</body>
</html> 