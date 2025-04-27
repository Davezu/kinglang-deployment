<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/../../../public/css/bootstrap/bootstrap.min.css">  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Rebooking Requests</title>
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
        /* Table header styles */
        .table thead th {
            background-color: #d1f7c4;
            font-weight: 600;
            padding: 12px 8px;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        .table thead th:hover {
            background-color:rgba(40, 167, 69, 0.2);
        }
        .table thead th.active:after {
            content: attr(data-order) === "asc" ? " ↑" : " ↓";
            font-size: 0.8rem;
            margin-left: 5px;
        }
        .sort-icon {
            font-size: 0.75rem;
            margin-left: 5px;
            vertical-align: middle;
        }
        .table tbody tr:hover {
            background-color: rgba(40, 167, 69, 0.05);
        }
        .stats-dashboard {
            margin-bottom: 1rem;
        }
        .stats-number {
            font-size: 1.5rem;
        }
        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .compact-card {
            padding: 0.5rem;
        }
        .compact-card .card-body {
            padding: 0.75rem;
        }
    </style>
</head>
<body>
    <?php include_once __DIR__ . "/../assets/admin_sidebar.php"; ?>

    <div class="content collapsed" id="content">
        <div class="container-fluid py-3 px-3 px-xl-4">
            <!-- Header with admin profile -->
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0 mb-2">
                <div class="p-0">
                    <h3><i class="bi bi-arrow-repeat me-2 text-success"></i>Rebooking Requests</h3>
                    <p class="text-muted mb-0">Manage and track all rebooking requests from clients</p>
                </div>
                <?php include_once __DIR__ . "/../assets/admin_profile.php"; ?>
            </div>
            
            <?php include_once __DIR__ . "/../assets/admin_navtab.php"; ?>
            
            <!-- Stats Dashboard Cards -->
            <div class="row stats-dashboard g-2 mt-3">
                <div class="col-xl-4 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm stats-card compact-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-primary-subtle text-primary">
                                    <i class="bi bi-arrow-repeat"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 text-muted">Total Rebookings</h6>
                                    <h3 class="fw-bold mb-0 stats-number" id="totalRebookingsCount">-</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm stats-card compact-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-success-subtle text-success">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 text-muted">Confirmed</h6>
                                    <h3 class="fw-bold mb-0 stats-number" id="confirmedRebookingsCount">-</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm stats-card compact-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-warning-subtle text-warning">
                                    <i class="bi bi-hourglass-split"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 text-muted">Pending</h6>
                                    <h3 class="fw-bold mb-0 stats-number" id="pendingRebookingsCount">-</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filters -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-3">
                    <div class="row g-2 align-items-center">
                        <div class="col-lg-4 col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" id="searchRebookings" class="form-control border-start-0" placeholder="Search clients...">
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-success-subtle text-success border-end-0">
                                    <i class="bi bi-funnel"></i>
                                </span>
                                <select name="status" id="statusSelect" class="form-select border-start-0">
                                    <option value="All">All Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Confirmed">Confirmed</option>
                                    <option value="Rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-4">
                            <button id="searchBtn" class="btn btn-success w-100">
                                <i class="bi bi-search me-1"></i> Search
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th class="sort" data-order="asc" data-column="client_name">Client Name <i class="bi bi-arrow-down-up sort-icon"></i></th>
                                    <th class="sort" data-order="asc" data-column="contact_number">Contact Number <i class="bi bi-arrow-down-up sort-icon"></i></th>
                                    <th class="sort" data-order="asc" data-column="email">Email Address <i class="bi bi-arrow-down-up sort-icon"></i></th>
                                    <th class="sort" data-order="asc" data-column="date_of_tour">Date of Tour <i class="bi bi-arrow-down-up sort-icon"></i></th>
                                    <th class="sort" data-order="asc" data-column="status">Remarks <i class="bi bi-arrow-down-up sort-icon"></i></th>
                                    <th class="text-center" style="width: 15%;">Action</th>
                                </tr> 
                            </thead>
                            <tbody id="tableBody" class="fs-6">
                                <!-- Table data will be dynamically populated here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../../public/js/assets/sidebar.js"></script>
    <script src="../../../public/js/admin/rebooking_requests.js"></script>
    <script src="../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>