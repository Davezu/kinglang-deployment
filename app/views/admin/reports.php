<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Kinglang Booking</title>
    <link rel="stylesheet" href="../../../public/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .report-card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .report-card:hover {
            transform: translateY(-5px);
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
    </style>
</head>
<body>
    <?php include_once __DIR__ . "/../assets/admin_sidebar.php"; ?>

    <div class="content collapsed" id="content">
        <div class="container-fluid py-4 px-4 px-xl-5">
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0">
                <h3>Reports</h3>
                <?php include_once __DIR__ . "/../assets/admin_profile.php"; ?>
            </div>

            <!-- Date Range Filters -->
            <div class="filters mt-4">
                <div class="row">
                    <div class="col-md-3">
                        <label for="startDate" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="startDate">
                    </div>
                    <div class="col-md-3">
                        <label for="endDate" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="endDate">
                    </div>
                    <div class="col-md-3">
                        <label for="yearSelect" class="form-label">Year</label>
                        <select class="form-select" id="yearSelect">
                            <?php 
                                $currentYear = date('Y');
                                for ($year = $currentYear; $year >= $currentYear - 5; $year--) {
                                    echo "<option value=\"$year\">$year</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button id="applyFilters" class="btn btn-success w-100">Apply Filters</button>
                    </div>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mt-4">
                <div class="col-md-3 mb-3">
                    <div class="card report-card bg-success-subtle h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total Bookings</h5>
                            <h2 id="totalBookings" class="display-4 fw-bold">-</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card report-card bg-success-subtle h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">Total Revenue</h5>
                            <h2 id="totalRevenue" class="display-4 fw-bold">-</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card report-card bg-success-subtle h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">Outstanding Balance</h5>
                            <h2 id="outstandingBalance" class="display-4 fw-bold">-</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card report-card bg-success-subtle h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title">Avg. Booking Value</h5>
                            <h2 id="avgBookingValue" class="display-4 fw-bold">-</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Status -->
            <div class="row mt-4">
                <div class="col-md-6 mb-4">
                    <div class="card report-card h-100">
                        <div class="card-header bg-success-subtle">
                            <h5 class="mb-0">Booking Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="bookingStatusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-4">
                    <div class="card report-card h-100">
                        <div class="card-header bg-success-subtle">
                            <h5 class="mb-0">Payment Methods</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="paymentMethodChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Monthly Trends -->
            <div class="row mt-4">
                <div class="col-12 mb-4">
                    <div class="card report-card">
                        <div class="card-header bg-success-subtle">
                            <h5 class="mb-0">Monthly Booking Trends</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="monthlyTrendsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Destinations -->
            <div class="row mt-4">
                <div class="col-md-12 mb-4">
                    <div class="card report-card">
                        <div class="card-header bg-success-subtle">
                            <h5 class="mb-0">Top Destinations</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="topDestinationsChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detailed Booking Report -->
            <div class="row mt-4">
                <div class="col-12 mb-4">
                    <div class="card report-card">
                        <div class="card-header bg-success-subtle d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Detailed Booking Report</h5>
                            <button id="exportCsv" class="btn btn-success btn-sm">Export CSV</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover">
                                    <thead class="table-success">
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
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="../../../public/js/assets/sidebar.js"></script>
    <script src="../../../public/js/admin/reports.js"></script>
</body>
</html> 