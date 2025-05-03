<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Kinglang Booking</title>
    <link rel="stylesheet" href="../../../public/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="../../../public/css/admin/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <style>
        :root {
            --primary-green: #198754;
            --secondary-green: #28a745;
            --light-green: #d1f7c4;
            --hover-green: #20c997;
        }
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
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
        .flatpickr-day.selected.startRange + .endRange:not(:nth-child(7n+1)), 
        .flatpickr-day.startRange.startRange + .endRange:not(:nth-child(7n+1)), 
        .flatpickr-day.endRange.startRange + .endRange:not(:nth-child(7n+1)) {
            box-shadow: -10px 0 0 #198754;
        }
        .flatpickr-day.inRange, 
        .flatpickr-day.prevMonthDay.inRange, 
        .flatpickr-day.nextMonthDay.inRange, 
        .flatpickr-day.today.inRange, 
        .flatpickr-day.prevMonthDay.today.inRange, 
        .flatpickr-day.nextMonthDay.today.inRange {
            background: rgba(25, 135, 84, 0.1);
            border-color: rgba(25, 135, 84, 0.2);
        }
        .flatpickr-day.today {
            border-color: #198754;
        }
        .flatpickr-months .flatpickr-month,
        .flatpickr-current-month .flatpickr-monthDropdown-months,
        .flatpickr-months .flatpickr-prev-month, 
        .flatpickr-months .flatpickr-next-month {
            color: #198754;
            fill: #198754;
        }
        /* Detailed Booking Report Table Enhancements */
        .summary-metrics-card table {
            font-size: 0.97rem;
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
        }
        .summary-metrics-card table thead th {
            position: sticky;
            top: 0;
            background: #e9f7ef;
            z-index: 2;
            border-bottom: 2px solid #198754;
            color: #198754;
            font-weight: 600;
            letter-spacing: 0.02em;
        }
        .summary-metrics-card table tbody tr {
            transition: background 0.2s;
        }
        .summary-metrics-card table tbody tr:hover {
            background: #f6fdf9;
        }
        .summary-metrics-card table tbody td {
            vertical-align: middle;
            padding-top: 0.65rem;
            padding-bottom: 0.65rem;
        }
        .summary-metrics-card table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }
        .badge-status {
            font-size: 0.97em;
            padding: 0.5em 1.2em;
            font-weight: 500;
        }
        /* Use Bootstrap's rounded-pill and badge classes in HTML */
        /* Soften specific status backgrounds using Bootstrap palette */
        .badge-status.bg-success {
            background: #e9f7ef !important;
            color: #198754 !important;
        }
        .badge-status.bg-warning {
            background: #fffbe6 !important;
            color: #b8860b !important;
        }
        .badge-status.bg-danger {
            background: #fdeaea !important;
            color: #d9534f !important;
        }
        .badge-status.bg-info {
            background: #e6f4fa !important;
            color: #17a2b8 !important;
        }
        .badge-status.bg-dark {
            background: #e9ecef !important;
            color: #343a40 !important;
        }
        .badge-status.bg-secondary {
            background: #f3f6f9 !important;
            color: #6c757d !important;
        }
        .badge-status.text-dark {
            color: #343a40 !important;
        }
        @media (min-width: 1400px) {
            .container-fluid {
                max-width: 98%;
            }
        }
    </style>
</head>
<body>
    <?php include_once __DIR__ . "/../assets/admin_sidebar.php"; ?>

    <div class="content collapsed" id="content">
        <div class="container-fluid py-3 px-3 px-xl-4">
            <!-- Header with admin profile styled like payment management -->
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0 mb-2">
                <div class="p-0">
                    <h3><i class="bi bi-bar-chart-line me-2 text-success"></i>Reports</h3>
                    <p class="text-muted mb-0">View and analyze booking and payment statistics</p>
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
                    <div class="col-md-2 d-flex align-items-end">
                        <button id="applyFilters" class="btn btn-success w-100">Apply Filters</button>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mt-3">
                <div class="col-md-6 col-lg-4 col-xl mb-4">
                    <div class="summary-metrics-card d-flex gap-4 align-items-center p-4 h-100">
                        <div class="icon bg-warning-subtle rounded-circle px-3 py-2">
                            <i class="bi bi-calendar-check fs-4 text-success"></i>
                        </div>
                        <div>
                            <h4 id="totalBookings" class="fw-bolder">-</h4>
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
                            <h4 id="totalRevenue" class="fw-bolder">-</h4>
                            <p class="text-secondary">Total Revenue</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl mb-4">
                    <div class="summary-metrics-card d-flex gap-4 align-items-center p-4 h-100">
                        <div class="icon bg-warning-subtle rounded-circle px-3 py-2">
                            <i class="bi bi-wallet2 fs-4 text-success"></i>
                        </div>
                        <div>
                            <h4 id="outstandingBalance" class="fw-bolder">-</h4>
                            <p class="text-secondary">Outstanding Balance</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 col-xl mb-4">
                    <div class="summary-metrics-card d-flex gap-4 align-items-center p-4 h-100">
                        <div class="icon bg-warning-subtle rounded-circle px-3 py-2">
                            <i class="bi bi-bar-chart-line fs-4 text-success"></i>
                        </div>
                        <div>
                            <h4 id="avgBookingValue" class="fw-bolder">-</h4>
                            <p class="text-secondary">Avg. Booking Value</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Status -->
            <div class="row mt-4">
                <div class="col-md-6 mb-4">
                    <div class="summary-metrics-card p-4">
                        <h4>Booking Status</h4>
                        <div class="chart-container">
                            <canvas id="bookingStatusChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="summary-metrics-card p-4">
                        <h4>Payment Methods</h4>
                        <div class="chart-container">
                            <canvas id="paymentMethodChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Trends -->
            <div class="row mt-4">
                <div class="col-12 mb-4">
                    <div class="summary-metrics-card p-4">
                        <h4>Monthly Booking Trends</h4>
                        <div class="chart-container">
                            <canvas id="monthlyTrendsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Destinations -->
            <div class="row mt-4">
                <div class="col-md-12 mb-4">
                    <div class="summary-metrics-card p-4">
                        <h4>Top Destinations</h4>
                        <div class="chart-container">
                            <canvas id="topDestinationsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Booking Report -->
            <div class="row mt-4">
                <div class="col-12 mb-4">
                    <div class="summary-metrics-card p-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0">Detailed Booking Report</h4>
                            <button id="exportCsv" class="btn btn-success btn-sm">Export CSV</button>
                        </div>
                        <div class="table-responsive ">
                            <table class="table table-hover text-secondary">
                                <thead class="">
                                    <tr>
                                        <th>Client Name</th>
                                        <th>Destination</th>
                                        <th>Date of Tour</th>
                                        <th>Total Cost</th>
                                        <th>Status</th>
                                        <th>Payment Status</th>
                                    </tr>
                                </thead>
                                <tbody id="bookingReportTableBody">
                                    <!-- Data will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                <select id="pageSize" class="form-select form-select-sm">
                                    <option value="10">10 per page</option>
                                    <option value="20">20 per page</option>
                                    <option value="50">50 per page</option>
                                </select>
                            </div>
                            <div id="paginationControls">
                                <!-- Pagination will be added here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="../../../public/js/utils/pagination.js"></script>
    <script src="../../../public/js/admin/reports.js"></script>
    <script src="../../../public/js/assets/sidebar.js"></script>
    <script src="../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html> 