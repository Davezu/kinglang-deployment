<?php 
require_client_auth(); // Use helper function
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"> -->
    <link rel="stylesheet" href="/../../../public/css/bootstrap/bootstrap.min.css">  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/../../../public/css/client/payment_styles.css">
    <link rel="stylesheet" href="/../../../public/css/assets/cancel_modal.css">
    <link rel="stylesheet" href="/../../../public/css/client/booking_requests.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>My Bookings | Kinglang Bus</title>
    <style>
        .content.collapsed {
            margin-left: 78px;
            transition: margin-left 0.3s ease;
            width: calc(100% - 78px);
        }
        .content {
            margin-left: 250px;
            transition: margin-left 0.3s ease;
            width: calc(100% - 250px);
        }
        .compact-card {
            padding: 0.5rem;
        }
        .compact-card .card-body {
            padding: 0.75rem;
        }
        .stats-dashboard {
            margin-bottom: 1rem;
        }
        .stats-number {
            font-size: 1.5rem;
        }
        .table-container {
            max-height: calc(100vh - 350px);
            overflow-y: auto;
            margin-bottom: 1rem;
        }
        .actions-compact {
            display: flex;
            gap: 0.25rem;
        }
        .actions-compact .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        @media (min-width: 1400px) {
            .container-fluid {
                max-width: 98%;
            }
        }
    </style>
</head>
<body>
    <?php include_once __DIR__ . "/../assets/sidebar.php"; ?> 
    
    <div class="content collapsed" id="content">
        <div class="container-fluid py-3 px-3 px-xl-4">
            <!-- Header with user profile -->
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0 mb-2">
                <div class="p-0">
                    <h3><i class="bi bi-bookmark-check me-2 text-success"></i>My Bookings</h3>
                    <p class="text-muted mb-0">Manage and track all your booking requests</p>
                </div>
                <?php include_once __DIR__ . "/../assets/user_profile.php"; ?>
            </div>

            <!-- Stats Dashboard Cards -->
            <div class="row stats-dashboard g-2">
                <div class="col-xl-3 col-md-6 col-sm-6">
                    <div class="card border-0 shadow-sm stats-card compact-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-primary-subtle text-primary">
                                    <i class="bi bi-calendar-check"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 text-muted">Total Bookings</h6>
                                    <h3 class="fw-bold mb-0 stats-number" id="totalBookingsCount">-</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-6">
                    <div class="card border-0 shadow-sm stats-card compact-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-success-subtle text-success">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 text-muted">Confirmed</h6>
                                    <h3 class="fw-bold mb-0 stats-number" id="confirmedBookingsCount">-</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-6">
                    <div class="card border-0 shadow-sm stats-card compact-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-warning-subtle text-warning">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 text-muted">Pending</h6>
                                    <h3 class="fw-bold mb-0 stats-number" id="pendingBookingsCount">-</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-6 col-sm-6">
                    <div class="card border-0 shadow-sm stats-card compact-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-info-subtle text-info">
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 text-muted">Upcoming Tours</h6>
                                    <h3 class="fw-bold mb-0 stats-number" id="upcomingToursCount">-</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upcoming Booking Reminder (if any) -->
            <div id="upcomingReminder" class="alert alert-info d-flex align-items-center mb-3" style="display: none !important;">
                <i class="bi bi-bell me-3 fs-4"></i>
                <div>
                    <strong>Upcoming Tour:</strong> 
                    <span id="upcomingTourDetails">You have an upcoming tour to <b id="upcomingDestination"></b> on <b id="upcomingDate"></b>.</span>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>

            <div class="row g-3 mb-3">
                <!-- Search and Filters Bar -->
                <div class="col-xl-8">
                    <div class="card mb-0 border-0 shadow-sm">
                        <div class="card-body py-2">
                            <div class="row g-2 align-items-center">
                                <!-- Search -->
                                <div class="col-lg-5 col-md-5">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="bi bi-search"></i>
                                        </span>
                                        <input type="text" id="searchBookings" class="form-control border-start-0" placeholder="Search destinations...">
                                        <button id="searchBtn" class="btn btn-success">Search</button>
                                    </div>
                                </div>
                                
                                <!-- Status Filter -->
                                <div class="col-lg-4 col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="bi bi-filter"></i>
                                        </span>
                                        <select name="status" id="statusSelect" class="form-select">
                                            <option value="all">All Bookings</option>
                                            <option value="pending" selected>Pending</option>
                                            <option value="confirmed">Confirmed</option>
                                            <option value="processing">Processing</option>
                                            <option value="canceled">Canceled</option>
                                            <option value="rejected">Rejected</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <!-- Records Per Page -->
                                <div class="col-lg-3 col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="bi bi-list-ol"></i>
                                        </span>
                                        <select name="limit" id="limitSelect" class="form-select">
                                            <option value="5">5 rows</option>
                                            <option value="10" selected>10 rows</option>
                                            <option value="25">25 rows</option>
                                            <option value="50">50 rows</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- View Switcher -->
                <div class="col-xl-4">
                    <div class="card mb-0 border-0 shadow-sm">
                        <div class="card-body py-2">
                            <div class="btn-group w-100" role="group" aria-label="View options">
                                <input type="radio" class="btn-check" name="viewOption" id="tableView" autocomplete="off" checked>
                                <label class="btn btn-outline-secondary" for="tableView">
                                    <i class="bi bi-table"></i> Table
                                </label>
                                
                                <input type="radio" class="btn-check" name="viewOption" id="cardView" autocomplete="off">
                                <label class="btn btn-outline-secondary" for="cardView">
                                    <i class="bi bi-grid-3x3-gap"></i> Cards
                                </label>
                                
                                <input type="radio" class="btn-check" name="viewOption" id="calendarView" autocomplete="off">
                                <label class="btn btn-outline-secondary" for="calendarView">
                                    <i class="bi bi-calendar3"></i> Calendar
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Filter Pills & Export Tools Row -->
            <div class="row g-3 mb-3">
                <div class="col-xl-8">
                    <div class="d-flex gap-2 flex-wrap">
                        <button class="btn btn-sm btn-outline-secondary quick-filter" data-status="all">
                            <i class="bi bi-funnel"></i> All
                        </button>
                        <button class="btn btn-sm btn-outline-warning quick-filter" data-status="pending">
                            <i class="bi bi-hourglass-split"></i> Pending
                        </button>
                        <button class="btn btn-sm btn-outline-success quick-filter" data-status="confirmed">
                            <i class="bi bi-check-circle"></i> Confirmed
                        </button>
                        <button class="btn btn-sm btn-outline-info quick-filter" data-status="processing">
                            <i class="bi bi-arrow-repeat"></i> Processing
                        </button>
                        <button class="btn btn-sm btn-outline-primary quick-filter" data-date="upcoming">
                            <i class="bi bi-calendar-check"></i> Upcoming
                        </button>
                        <button class="btn btn-sm btn-outline-primary quick-filter" data-date="past">
                            <i class="bi bi-calendar-x"></i> Past
                        </button>
                        <button class="btn btn-sm btn-outline-danger quick-filter" data-balance="unpaid">
                            <i class="bi bi-cash"></i> Unpaid
                        </button>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="d-flex gap-2 justify-content-end">
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-download"></i> Export
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" id="exportPDF"><i class="bi bi-file-pdf text-danger"></i> Export as PDF</a></li>
                                <li><a class="dropdown-item" href="#" id="exportCSV"><i class="bi bi-file-spreadsheet text-success"></i> Export as CSV</a></li>
                            </ul>
                        </div>
                        <button class="btn btn-sm btn-outline-success" id="refreshBookings">
                            <i class="bi bi-arrow-clockwise"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>

            <!-- TABLE VIEW -->
            <div id="tableViewContainer">
                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover overflow-hidden rounded shadow-sm">
                            <thead>
                                <tr>
                                    <th class="sort" data-order="asc" data-column="destination" style="white-space: nowrap;">Destination</th>
                                    <th class="sort" data-order="asc" data-column="date_of_tour" style="white-space: nowrap;">Date of Tour</th>
                                    <th class="sort" data-order="asc" data-column="end_of_tour" style="white-space: nowrap;">End of Tour</th>
                                    <th class="sort" data-order="asc" data-column="number_of_days" style="white-space: nowrap;">Days</th>
                                    <th class="sort" data-order="asc" data-column="number_of_buses" style="white-space: nowrap;">Buses</th>
                                    <th class="sort" data-order="asc" data-column="total_cost" style="white-space: nowrap;">Total Cost</th>
                                    <th class="sort" data-order="asc" data-column="balance" style="white-space: nowrap;">Balance</th>
                                    <th class="sort" data-order="asc" data-column="status" style="white-space: nowrap;">Remarks</th>
                                    <th style="text-align: center; width: 18%; white-space: nowrap;">Action</th>
                                </tr>
                            </thead>
                            <tbody class="table-group" id="tableBody"></tbody>
                        </table>     
                    </div>
                </div>
            </div>

            <!-- CARD VIEW -->
            <div id="cardViewContainer" class="row g-0" style="display:none;"></div>

            <!-- CALENDAR VIEW -->
            <div id="calendarViewContainer" class="card border-0 shadow-sm" style="display:none;">
                <div class="card-body p-2">
                    <div id="bookingCalendar"></div>
                </div>
            </div>

            <!-- Pagination Container -->
            <div id="paginationContainer" class="d-flex justify-content-center mt-3"></div>

            <!-- No Results Message -->
            <div id="noResultsFound" class="text-center my-4" style="display:none;">
                <i class="bi bi-search fs-1 text-muted"></i>
                <h4 class="mt-3">No bookings found</h4>
                <p class="text-muted">Try adjusting your search or filter criteria</p>
                <button class="btn btn-outline-primary mt-2" id="resetFilters">
                    <i class="bi bi-arrow-counterclockwise"></i> Reset Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Payment Modal -->
    <div class="modal fade payment-modal" aria-labelledby="paymentModal" tabindex="-1" id="paymentModal">
        <div class="modal-dialog modal-dialog-centered">
            <form class="payment-content modal-content" action="" id="paymentForm" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h3 class="modal-title"><i class="bi bi-credit-card-2-front me-2"></i>Payment Details</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body container">
                    <div class="row">
                        <!-- Left Column - Payment Options -->
                        <div class="col-md-6">
                            <p class="lead mb-4">Payment Options:</p>
                            <div class="d-flex flex-column gap-3">
                                <div class="text-bg-success p-3 rounded-3 amount-payment" id="fullAmnt">
                                    <h3>Full payment</h3>
                                    <p id="fullAmount" class="amount"></p>  
                                </div>

                                <div class="text-bg-danger p-3 rounded-3 amount-payment">
                                    <h3 id="downPayment">Down payment</h3>
                                    <p id="partialAmount" class="amount"></p>
                                </div>
                            </div>
                            
                            <div class="mt-3 total-amount">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Selected Amount:</span>
                                    <span id="amount" class="text-success"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column - Payment Method & Upload -->
                        <div class="col-md-6">
                            <div class="payment-method">
                                <label for="paymentMethod" class="form-label">Payment Method</label>
                                <select name="payment_method" id="paymentMethod" class="form-select" aria-label="Payment method selection">
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Online Payment">Online Payment</option>
                                    <option value="GCash">GCash</option>
                                    <option value="Maya">Maya</option>
                                </select>
                            </div>

                            <!-- Account Information Section -->
                            <div id="accountInfoSection" class="mt-3" style="display: none;">
                                <div class="alert alert-info">
                                    <h5 class="alert-heading"><i class="bi bi-info-circle me-2"></i>Account Details</h5>
                                    <div class="mt-2">
                                        <p class="mb-1"><strong>Bank:</strong> <span id="bankName">BDO</span></p>
                                        <p class="mb-1"><strong>Name:</strong> <span id="accountName">Kinglang Bus</span></p>
                                        <p class="mb-0"><strong>Number:</strong> <span id="accountNumber">1234567890</span></p>
                                    </div>
                                </div>
                            </div>

                            <!-- GCash/Maya Info -->
                            <div id="mobilePaymentSection" class="mt-3" style="display: none;">
                                <div class="alert alert-primary">
                                    <h5 class="alert-heading"><i class="bi bi-phone me-2"></i><span id="mobilePaymentTitle">Mobile Payment</span></h5>
                                    <div class="mt-2">
                                        <p class="mb-1"><strong>Name:</strong> <span id="mobileName">Kinglang Bus</span></p>
                                        <p class="mb-0"><strong>Number:</strong> <span id="mobileNumber">09123456789</span></p>
                                        <div id="qrCodeContainer" class="text-center mt-2">
                                            <!-- QR code will be displayed here -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Proof of Payment Upload Section -->
                            <div id="proofUploadSection" class="mt-3" style="display: none;">
                                <label for="proofOfPayment" class="form-label">Upload Proof</label>
                                <input type="file" class="form-control" id="proofOfPayment" name="proof_of_payment" accept="image/*,.pdf">
                                <small class="text-muted">Upload receipt (JPG, PNG, PDF)</small>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden inputs -->
                    <input type="hidden" name="booking_id" id="bookingID">
                    <input type="hidden" name="user_id" id="userID">
                    <input type="hidden" name="amount" id="amountInput">
                </div>
                                        
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-outline-success pay" type="submit"><i class="bi bi-check-circle me-2"></i>Confirm Payment</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Booking Details Modal -->
    <div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title" id="bookingDetailsModalLabel">Booking Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="bookingDetailsContent">
                    <!-- Content will be loaded dynamically -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="viewFullDetails">View Full Details</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <script src="../../../public/js/utils/pagination.js"></script>
    <script src="../../../public/js/client/booking_request.js"></script>
    <script src="../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="../../../public/js/assets/sidebar.js"></script>
</body>
</html>