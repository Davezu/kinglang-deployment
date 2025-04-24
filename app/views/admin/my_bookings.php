<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Bookings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary-color: #28a745;
            --primary-light: #d1f7c4;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        .header-title {
            color: #333;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .header-title i {
            color: var(--primary-color);
        }
        .booking-stats {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            flex: 1;
            min-width: 200px;
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .total-icon {
            background-color: #e7f1ff;
            color: #0d6efd;
        }
        .confirmed-icon {
            background-color: #d1f7c4;
            color: #28a745;
        }
        .pending-icon {
            background-color: #fff3cd;
            color: #ffc107;
        }
        .upcoming-icon {
            background-color: #d1ecf1;
            color: #17a2b8;
        }
        .stat-info h2 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
        }
        .stat-info p {
            margin: 0;
            color: #6c757d;
            font-size: 14px;
        }
        .search-section {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }
        .search-container {
            display: flex;
            flex: 1;
            min-width: 300px;
        }
        .search-input {
            border-right: none;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        .search-btn {
            background-color: var(--primary-color);
            color: white;
            border: 1px solid var(--primary-color);
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }
        .status-filter, .rows-filter {
            min-width: 200px;
        }
        .view-options {
            display: flex;
            gap: 5px;
        }
        .view-btn {
            border: 1px solid #dee2e6;
            background-color: white;
            padding: 6px 15px;
        }
        .view-btn.active {
            background-color: #e9ecef;
        }
        .filters {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 20px;
        }
        .filter-btn {
            border-radius: 20px;
            padding: 5px 15px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
            background-color: white;
            border: 1px solid #dee2e6;
        }
        .filter-btn.active {
            background-color: var(--primary-light);
            border-color: var(--primary-color);
            color: var(--primary-color);
        }
        .filter-btn i {
            font-size: 12px;
        }
        .booking-table {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .booking-table th {
            background-color: #f8f9fa;
            font-weight: 500;
            color: #495057;
            padding: 12px 15px;
            position: relative;
        }
        .booking-table th:after {
            content: "";
            height: 50%;
            width: 1px;
            background-color: #dee2e6;
            position: absolute;
            right: 0;
            top: 25%;
        }
        .booking-table th:last-child:after {
            display: none;
        }
        .booking-table td {
            padding: 12px 15px;
            vertical-align: middle;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        .pending {
            background-color: #fff3cd;
            color: #856404;
        }
        .confirmed {
            background-color: #d1f7c4;
            color: #155724;
        }
        .action-btn {
            border: none;
            background-color: transparent;
            color: #6c757d;
            margin-right: 5px;
        }
        .details-btn {
            color: #007bff;
        }
        .pay-btn {
            color: #28a745;
        }
        .export-btn, .refresh-btn {
            border: 1px solid #dee2e6;
            background-color: white;
            color: #495057;
            padding: 6px 12px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .export-btn i, .refresh-btn i {
            font-size: 14px;
        }
    </style>
</head>
<body>
    <?php include_once __DIR__ . "/../assets/admin_sidebar.php"; ?>

    <div class="content collapsed" id="content">
        <div class="container-fluid py-4 px-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="header-title">
                    <i class="bi bi-bookmark"></i> My Bookings
                </h1>
                <?php include_once __DIR__ . "/../assets/admin_profile.php"; ?>
            </div>
            
            <p class="text-muted mb-4">Manage and track all your booking requests</p>
            
            <!-- Booking Stats Cards -->
            <div class="booking-stats">
                <div class="stat-card">
                    <div class="stat-icon total-icon">
                        <i class="bi bi-calendar-check"></i>
                    </div>
                    <div class="stat-info">
                        <h2 id="totalBookings">3</h2>
                        <p>Total Bookings</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon confirmed-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <h2 id="confirmedBookings">2</h2>
                        <p>Confirmed</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon pending-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="stat-info">
                        <h2 id="pendingBookings">1</h2>
                        <p>Pending</p>
                    </div>
                </div>
                
                <div class="stat-card">
                    <div class="stat-icon upcoming-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div class="stat-info">
                        <h2 id="upcomingTours">2</h2>
                        <p>Upcoming Tours</p>
                    </div>
                </div>
            </div>
            
            <!-- Search, Filter and View Options -->
            <div class="search-section">
                <div class="search-container">
                    <input type="text" class="form-control search-input" placeholder="Search destinations...">
                    <button class="btn search-btn">Search</button>
                </div>
                
                <div class="dropdown status-filter">
                    <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                        Pending
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">All Status</a></li>
                        <li><a class="dropdown-item" href="#">Pending</a></li>
                        <li><a class="dropdown-item" href="#">Confirmed</a></li>
                        <li><a class="dropdown-item" href="#">Rejected</a></li>
                    </ul>
                </div>
                
                <div class="dropdown rows-filter">
                    <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                        10 rows
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">5 rows</a></li>
                        <li><a class="dropdown-item" href="#">10 rows</a></li>
                        <li><a class="dropdown-item" href="#">25 rows</a></li>
                        <li><a class="dropdown-item" href="#">50 rows</a></li>
                    </ul>
                </div>
                
                <div class="view-options">
                    <button class="btn view-btn active" data-view="table">
                        <i class="bi bi-table"></i> Table
                    </button>
                    <button class="btn view-btn" data-view="cards">
                        <i class="bi bi-grid"></i> Cards
                    </button>
                    <button class="btn view-btn" data-view="calendar">
                        <i class="bi bi-calendar3"></i> Calendar
                    </button>
                </div>
            </div>
            
            <!-- Filter Buttons -->
            <div class="filters">
                <button class="filter-btn active">
                    <i class="bi bi-funnel"></i> All
                </button>
                <button class="filter-btn">
                    <i class="bi bi-hourglass"></i> Pending
                </button>
                <button class="filter-btn">
                    <i class="bi bi-check-circle"></i> Confirmed
                </button>
                <button class="filter-btn">
                    <i class="bi bi-arrow-repeat"></i> Processing
                </button>
                <button class="filter-btn">
                    <i class="bi bi-calendar-event"></i> Upcoming
                </button>
                <button class="filter-btn">
                    <i class="bi bi-clock-history"></i> Past
                </button>
                <button class="filter-btn">
                    <i class="bi bi-x-circle"></i> Unpaid
                </button>
                
                <div class="ms-auto d-flex gap-2">
                    <button class="export-btn">
                        <i class="bi bi-download"></i> Export
                    </button>
                    <button class="refresh-btn">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                </div>
            </div>
            
            <!-- Bookings Table -->
            <div class="booking-table table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Destination</th>
                            <th>Date of Tour</th>
                            <th>End of Tour</th>
                            <th>Days</th>
                            <th>Buses</th>
                            <th>Total Cost</th>
                            <th>Balance</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="bookingsTableBody">
                        <tr>
                            <td>Marilao Public Market, Marikina</td>
                            <td>May 20, 2025</td>
                            <td>May 21, 2025</td>
                            <td>2</td>
                            <td>1</td>
                            <td>₱94,897.48</td>
                            <td>₱94,897.48</td>
                            <td><span class="status-badge pending">Pending</span></td>
                            <td>
                                <button class="action-btn details-btn" title="View Details">
                                    <i class="bi bi-info-circle"></i>
                                </button>
                                <button class="action-btn pay-btn" title="Make Payment">
                                    <i class="bi bi-credit-card"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="../../../public/js/assets/sidebar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../../../public/js/admin/my_bookings.js"></script>
</body>
</html> 