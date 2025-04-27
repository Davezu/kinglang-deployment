<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Management</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        /* Booking details modal styling */
        .booking-detail-section {
            margin-bottom: 1.5rem;
        }
        .booking-detail-section h6 {
            font-weight: 600;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            color: #28a745;
        }
        .booking-detail-section p {
            margin-bottom: 0.5rem;
        }
        .booking-detail-section:last-child {
            margin-bottom: 0;
        }
        .booking-detail-section strong {
            color: #495057;
        }
        #bookingDetailsModal .modal-header {
            background-color: var(--light-green);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        #bookingDetailsModal .modal-body {
            padding: 20px;
        }
        #bookingDetailsModal .modal-content {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        #bookingDetailsModal .badge {
            padding: 0.4rem 0.7rem;
            font-weight: 500;
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
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0 mb-2">
                <h3>Booking Management</h3>
                <?php include_once __DIR__ . "/../assets/admin_profile.php"; ?>
            </div>
            <?php include_once __DIR__ . "/../assets/admin_navtab.php"; ?>
            <div class="input-group w-25 w-md-50 my-3">
                <span class="input-group-text bg-success-subtle" id="basic-addon1">Filter by Remarks</span>
                <select name="status" id="statusSelect" class="form-select">
                    <option value="All">All</option>
                    <option value="Pending">Pending</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
            <div class="table-responsive-xl ">
                <table class="table table-hover border overflow-hidden rounded">
                    <thead>
                        <tr>
                            <th class="sort" data-order="asc" data-column="client_name">Client Name</th>
                            <th class="sort" data-order="asc" data-column="contact_number">Contact Number</th>
                            <th class="sort" data-order="asc" data-column="email">Email Address</th>
                            <th class="sort" data-order="asc" data-column="date_of_tour">Date of Tour</th>
                            <th class="sort" data-order="asc" data-column="status">Remarks</th>
                            <th class="sort" data-order="asc" style="text-align: center; width: 15%;">Action</th>
                        </tr> 
                    </thead>
                    <tbody id="tableBody" class="fs-6">

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Booking Details Modal -->
    <div class="modal fade" id="bookingDetailsModal" tabindex="-1" aria-labelledby="bookingDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <h5 class="modal-title fs-4" id="bookingDetailsModalLabel">Booking Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="bookingDetailsContent">
                    <!-- Content will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>

    <script src="../../../public/js/assets/sidebar.js"></script>
    <script src="../../../public/js/admin/rebooking_requests.js"></script>
    <script src="../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>