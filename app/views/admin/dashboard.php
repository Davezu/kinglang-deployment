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
    <link rel="stylesheet" href="../../../public/css/admin/dashboard.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include_once __DIR__ . "/../assets/admin_sidebar.php"; ?>

    <div class="content collapsed" id="content">
        <div class="container-fluid py-4 px-4 px-xl-5">
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0">
                <h3>Dashboard</h3>
                <?php include_once __DIR__ . "/../assets/admin_profile.php"; ?>
            </div>

            <div class="d-flex gap-4 flex-wrap mt-3">
                <div class="flex-grow-1 rounded p-4 summary-metrics-card d-flex gap-4 align-items-center">
                    <div class="icon bg-warning-subtle rounded-circle px-3 py-2px-3 py-2">
                        <i class="bi bi-calendar-check fs-4 text-success"></i>
                    </div>
                    <div>
                        <h4 id="totalBookings" class="fw-bolder"></h4>
                        <p class="text-secondary">Total Bookings</p>
                    </div>
                </div>
                <div class="flex-grow-1 rounded p-4 summary-metrics-card d-flex gap-4 align-items-center">
                    <div class="icon bg-warning-subtle rounded-circle px-3 py-2">
                        <i class="bi bi-cash-stack fs-4 text-success"></i>
                    </div>
                    <div>
                        <h4 id="totalRevenue" class="fw-bolder"></h4>
                        <p class="text-secondary">Total Revenue</p>
                    </div>
                </div>
                <div class="flex-grow-1 rounded p-4 summary-metrics-card d-flex gap-4 align-items-center">
                    <div class="icon bg-warning-subtle rounded-circle px-3 py-2">
                        <i class="bi bi-bus-front fs-4 text-success"></i>
                    </div>
                    <div>
                        <h4 id="upcomingTrips" class="fw-bolder"></h4>
                        <p class="text-secondary">Upcoming Trips</p>
                    </div>
                </div>
                <div class="flex-grow-1 rounded p-4 summary-metrics-card d-flex gap-4 align-items-center">
                    <div class="icon bg-warning-subtle rounded-circle px-3 py-2">
                        <i class="bi bi-hourglass-split fs-4 text-success"></i>
                    </div>
                    <div>
                        <h4 id="pendingBookings" class="fw-bolder"></h4>
                        <p class="text-secondary">Pending Bookings</p>
                    </div>
                </div>
                <div class="flex-grow-1 rounded p-4 summary-metrics-card d-flex gap-4 align-items-center">
                    <div class="icon bg-warning-subtle rounded-circle px-3 py-2">
                        <i class="bi bi-exclamation-triangle fs-4 text-success"></i>
                    </div>
                    <div>
                        <h4 id="flaggedBookings" class="fw-bolder"></h4>
                        <p class="text-secondary">Flagged Bookings</p>
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
                        <h4>Payment Method Distribution</h4>
                        <canvas id="paymentMethodChart" height="300"></canvas>
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
                        <h4>Revenue Trends</h4>
                        <canvas id="revenueTrendsChart" height="300"></canvas>
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