<?php
require_admin_auth(); // Use helper function
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../../../public/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../../../public/icons/bootstrap-icons.css">
    <link rel="stylesheet" href="../../../public/css/admin/dashboard.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        :root {
            --primary-green: #198754;
            --secondary-green: #28a745;
            --light-green: #d1f7c4;
            --hover-green: #20c997;
        }
        @media (min-width: 1400px) {
            .container-fluid {
                max-width: 98%;
            }
        }
        .filters {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        /* Flatpickr custom styles */
        .date-input-wrapper {
            position: relative;
        }
        .date-input-wrapper i {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            pointer-events: none;
            color: #198754;
        }
        /* Customizing Flatpickr */
        .flatpickr-day.selected, 
        .flatpickr-day.startRange, 
        .flatpickr-day.endRange, 
        .flatpickr-day.selected.inRange, 
        .flatpickr-day.startRange.inRange, 
        .flatpickr-day.endRange.inRange, 
        .flatpickr-day.selected:focus, 
        .flatpickr-day.startRange:focus, 
        .flatpickr-day.endRange:focus,
        .flatpickr-day.selected:hover, 
        .flatpickr-day.startRange:hover, 
        .flatpickr-day.endRange:hover {
            background: #198754;
            border-color: #198754;
        }
        /* Quick filter buttons */
        .quick-filter {
            transition: all 0.2s ease;
            border-radius: 20px;
            font-size: 0.85rem;
        }
        .quick-filter.active {
            background-color: var(--primary-green) !important;
            color: white !important;
            border-color: var(--primary-green) !important;
        }
    </style>
</head>
<body>
    <?php include_once __DIR__ . "/../assets/admin_sidebar.php"; ?>

    <div class="content collapsed" id="content">
        <div class="container-fluid py-3 px-3 px-xl-4">
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0 mb-2">
                <div class="p-0">
                    <h3><i class="bi bi-speedometer2 me-2 text-success"></i>Dashboard</h3>
                    <p class="text-muted mb-0">Overview of bookings, revenue, and activity</p>
                </div>
                <?php include_once __DIR__ . "/../assets/admin_profile.php"; ?>
            </div>
            <hr>

            <!-- Date Range Filters -->
            <div class="filters mt-0">
                <div class="row">
                    <div class="col-md-5">
                        <label for="startDate" class="form-label">Start Date</label>
                        <div class="date-input-wrapper">
                            <input type="text" class="form-control" id="startDate" placeholder="Select start date">
                            <i class="bi bi-calendar-date"></i>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <label for="endDate" class="form-label">End Date</label>
                        <div class="date-input-wrapper">
                            <input type="text" class="form-control" id="endDate" placeholder="Select end date">
                            <i class="bi bi-calendar-date"></i>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end gap-2">
                        <button id="applyFilters" class="btn btn-success flex-grow-1">Apply</button>
                        <button id="resetFilters" class="btn btn-outline-secondary" title="Reset to default date range">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-sm btn-outline-success quick-filter" data-range="today">Today</button>
                            <button type="button" class="btn btn-sm btn-outline-success quick-filter" data-range="yesterday">Yesterday</button>
                            <button type="button" class="btn btn-sm btn-outline-success quick-filter" data-range="this-week">This Week</button>
                            <button type="button" class="btn btn-sm btn-outline-success quick-filter" data-range="last-week">Last Week</button>
                            <button type="button" class="btn btn-sm btn-outline-success quick-filter" data-range="this-month">This Month</button>
                            <button type="button" class="btn btn-sm btn-outline-success quick-filter" data-range="last-month">Last Month</button>
                            <button type="button" class="btn btn-sm btn-outline-success quick-filter" data-range="this-year">This Year</button>
                            <button type="button" class="btn btn-sm btn-outline-success quick-filter" data-range="last-year">Last Year</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary quick-filter" data-range="all-time">All Time</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6 col-lg-4 col-xl mb-4">
                    <div class="summary-metrics-card d-flex gap-4 align-items-center p-4 h-100">
                        <div class="icon bg-warning-subtle rounded-circle px-3 py-2">
                            <i class="bi bi-calendar-check fs-4 text-success"></i>
                        </div>
                        <div>
                            <h4 id="totalBookings" class="fw-bolder"></h4>
                            <p class="text-secondary">Total Bookings</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl mb-4">
                    <div class="summary-metrics-card d-flex gap-4 align-items-center p-4 h-100">
                        <div class="icon bg-warning-subtle rounded-circle px-3 py-2">
                            <i class="bi bi-cash-stack fs-4 text-success"></i>
                        </div>
                        <div>
                            <h4 id="totalRevenue" class="fw-bolder"></h4>
                            <p class="text-secondary">Total Revenue</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl mb-4">
                    <div class="summary-metrics-card d-flex gap-4 align-items-center p-4 h-100">
                        <div class="icon bg-warning-subtle rounded-circle px-3 py-2">
                            <i class="bi bi-bus-front fs-4 text-success"></i>
                        </div>
                        <div>
                            <h4 id="upcomingTrips" class="fw-bolder"></h4>
                            <p class="text-secondary">Upcoming Trips</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl mb-4">
                    <div class="summary-metrics-card d-flex gap-4 align-items-center p-4 h-100">
                        <div class="icon bg-warning-subtle rounded-circle px-3 py-2">
                            <i class="bi bi-hourglass-split fs-4 text-success"></i>
                        </div>
                        <div>
                            <h4 id="pendingBookings" class="fw-bolder"></h4>
                            <p class="text-secondary">Pending Bookings</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl mb-4">
                    <div class="summary-metrics-card d-flex gap-4 align-items-center p-4 h-100">
                        <div class="icon bg-warning-subtle rounded-circle px-3 py-2">
                            <i class="bi bi-exclamation-triangle fs-4 text-success"></i>
                        </div>
                        <div>
                            <h4 id="flaggedBookings" class="fw-bolder"></h4>
                            <p class="text-secondary">Flagged Bookings</p>
                        </div>
                    </div>
                </div>  
            </div>

            <div class="row mt-4">
                <div class="col-md-6 mb-4">
                    <div class="rounded p-4 summary-metrics-card">
                        <h4>Monthly Booking Trends</h4>
                        <canvas id="monthlyTrendsChart" height="300"></canvas>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="rounded p-4 summary-metrics-card">
                        <h4>Revenue Trends</h4>
                        <canvas id="revenueTrendsChart" height="300"></canvas>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="rounded p-4 summary-metrics-card">
                        <h4>Top Destinations</h4>
                        <canvas id="destinationsChart" height="300"></canvas>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="rounded p-4 summary-metrics-card">
                        <h4>Booking Status</h4>
                        <canvas id="bookingStatusChart" height="300"></canvas>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="rounded p-4 summary-metrics-card">
                        <h4>Payment Method Distribution</h4>
                        <canvas id="paymentMethodChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../../../public/js/assets/sidebar.js"></script>
    <script src="../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>

    <!-- <script src="/../../../public/jquery/jquery-3.6.4.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="../../../public/js/admin/dashboard.js" type="module"></script>
</body>
</html>