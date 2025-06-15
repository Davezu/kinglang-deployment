<?php
require_once __DIR__ . "/../../controllers/admin/DriverManagementController.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/../../../public/css/bootstrap/bootstrap.min.css">  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Driver Management</title>
    <style>
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
        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }
        .table thead th {
            background-color: #d1f7c4;
            font-weight: 600;
            padding: 12px 8px;
        }
        .table tbody tr:hover {
            background-color: rgba(40, 167, 69, 0.05);
        }
        .driver-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .driver-avatar-placeholder {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: #e9ecef;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6c757d;
        }
        .license-expiring {
            color: #dc3545;
            font-weight: 600;
        }
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .status-active {
            background-color: #d1f7c4;
            color: #198754;
        }
        .status-inactive {
            background-color: #f8d7da;
            color: #dc3545;
        }
        .status-on-leave {
            background-color: #fff3cd;
            color: #ffc107;
        }
        .availability-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .availability-available {
            background-color: #d1f7c4;
            color: #198754;
        }
        .availability-assigned {
            background-color: #cfe2ff;
            color: #0d6efd;
        }
        @media (min-width: 1400px) {
            .container-fluid {
                max-width: 98%;
            }
        }
        
        /* Schedule modal styles */
        .schedule-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 0.25rem;
            font-size: 0.75rem;
            font-weight: 600;
        }
        #scheduleTableBody tr:hover {
            background-color: rgba(40, 167, 69, 0.05);
        }
        #driverScheduleModal .table thead th {
            background-color: #d1f7c4;
            font-weight: 600;
            padding: 12px 8px;
        }
    </style>
</head>
<body>
    <?php include_once __DIR__ . "/../assets/admin_sidebar.php"; ?>

    <div class="content collapsed" id="content">
        <div class="container-fluid py-3 px-4 px-xl-4">
            <!-- Header with admin profile -->
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0 mb-2">
                <div class="p-0">
                    <h3><i class="bi bi-person-badge me-2 text-success"></i>Driver Management</h3>
                    <p class="text-muted mb-0">Manage and monitor your drivers</p>
                </div>
                <?php include_once __DIR__ . "/../assets/admin_profile.php"; ?>
            </div>
            <hr>
            
            <!-- Stats Dashboard Cards -->
            <div class="row stats-dashboard g-2 mt-3">
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm stats-card compact-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-primary-subtle text-primary">
                                    <i class="bi bi-people"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 text-muted">Total Drivers</h6>
                                    <h3 class="fw-bold mb-0 stats-number" id="totalDriversCount">-</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm stats-card compact-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-success-subtle text-success">
                                    <i class="bi bi-check-circle"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 text-muted">Active Drivers</h6>
                                    <h3 class="fw-bold mb-0 stats-number" id="activeDriversCount">-</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm stats-card compact-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-danger-subtle text-danger">
                                    <i class="bi bi-x-circle"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 text-muted">Inactive Drivers</h6>
                                    <h3 class="fw-bold mb-0 stats-number" id="inactiveDriversCount">-</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm stats-card compact-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-warning-subtle text-warning">
                                    <i class="bi bi-calendar2-minus"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 text-muted">On Leave</h6>
                                    <h3 class="fw-bold mb-0 stats-number" id="onLeaveDriversCount">-</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm stats-card compact-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-success-subtle text-success">
                                    <i class="bi bi-person-check"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 text-muted">Available</h6>
                                    <h3 class="fw-bold mb-0 stats-number" id="availableDriversCount">-</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 col-md-4 col-sm-6">
                    <div class="card border-0 shadow-sm stats-card compact-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="stats-icon bg-info-subtle text-info">
                                    <i class="bi bi-truck"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 text-muted">Assigned</h6>
                                    <h3 class="fw-bold mb-0 stats-number" id="assignedDriversCount">-</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="row mt-3">
                <!-- Driver List -->
                <div class="col-lg-9">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <h5 class="mb-0"><i class="bi bi-list-ul text-success me-2"></i>Driver List</h5>
                            <div>
                                <button id="refreshDriversBtn" class="btn btn-outline-secondary btn-sm me-2">
                                    <i class="bi bi-arrow-clockwise"></i> Refresh
                                </button>
                                <button id="addDriverBtn" class="btn btn-success btn-sm">
                                    <i class="bi bi-plus-lg"></i> Add Driver
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>Photo</th>
                                            <th>Name</th>
                                            <th>License</th>
                                            <th>Contact</th>
                                            <th>License Expiry</th>
                                            <th>Status</th>
                                            <th>Availability</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="driverTableBody">
                                        <tr>
                                            <td colspan="8" class="text-center py-3">Loading drivers...</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Driver Statistics -->
                <div class="col-lg-3">
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="bi bi-graph-up text-success me-2"></i>Most Active Drivers</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush" id="mostActiveDriversList">
                                <li class="list-group-item text-center">Loading data...</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-white py-3">
                            <h5 class="mb-0"><i class="bi bi-exclamation-triangle text-warning me-2"></i>Expiring Licenses</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush" id="expiringLicensesList">
                                <li class="list-group-item text-center">Loading data...</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add/Edit Driver Modal -->
    <div class="modal fade" id="driverModal" tabindex="-1" aria-labelledby="driverModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="driverModalLabel">Add New Driver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="driverForm" enctype="multipart/form-data">
                        <input type="hidden" id="driver_id" name="driver_id">
                        
                        <div class="row mb-3">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="full_name" class="form-label">Full Name*</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" required>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="license_number" class="form-label">License Number*</label>
                                        <input type="text" class="form-control" id="license_number" name="license_number" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="license_expiry" class="form-label">License Expiry Date</label>
                                        <input type="date" class="form-control" id="license_expiry" name="license_expiry">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3 text-center">
                                    <label for="profile_photo" class="form-label">Profile Photo</label>
                                    <div class="mb-2">
                                        <img id="photoPreview" src="/public/images/icons/user-placeholder.png" alt="Profile Preview" class="img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                                    </div>
                                    <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept="image/*">
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contact_number" class="form-label">Contact Number*</label>
                                <input type="text" class="form-control" id="contact_number" name="contact_number" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="date_hired" class="form-label">Date Hired</label>
                                <input type="date" class="form-control" id="date_hired" name="date_hired">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2"></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="On Leave">On Leave</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="availability" class="form-label">Availability</label>
                                <select class="form-select" id="availability" name="availability">
                                    <option value="Available">Available</option>
                                    <option value="Assigned">Assigned</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" id="saveDriverBtn">Save Driver</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteDriverModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this driver? This action cannot be undone.</p>
                    <input type="hidden" id="delete_driver_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Driver Schedule Modal -->
    <div class="modal fade" id="driverScheduleModal" tabindex="-1" aria-labelledby="scheduleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="scheduleModalLabel">Driver Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="schedule_driver_id">
                    
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="scheduleStartDate" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="scheduleStartDate">
                        </div>
                        <div class="col-md-4">
                            <label for="scheduleEndDate" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="scheduleEndDate">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="button" class="btn btn-primary w-100" id="filterScheduleBtn">
                                <i class="bi bi-filter"></i> Filter
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Client</th>
                                    <th>Destination</th>
                                    <th>Trip Dates</th>
                                    <th>Pickup Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="scheduleTableBody">
                                <tr>
                                    <td colspan="6" class="text-center py-3">No schedule data available</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="/../../../public/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/../../../public/js/admin/driver_management.js"></script>
</body>
</html> 