<?php
require_once __DIR__ . "/../../controllers/admin/BookingManagementController.php";

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "Super Admin") {
    header("Location: /admin/login");
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/../../../public/css/bootstrap/bootstrap.min.css">  
    <title>Booking Management</title>
    
</head>
<body> 
    <div class="modal fade" aria-labelledby="confirmBookingModal" tabindex="-1" id="confirmBookingModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="" method="post" class="modal-content" id="confirmBookingForm">
                <div class="modal-header">
                    <h4 class="modal-title">Confirm Booking?</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to confirm this booking request?</p>
                    <p class="text-secondary">Note: This action cannot be undone.</p>
                </div>

                <div class="modal-footer">
                    <div class="d-flex gap-3 w-50">
                        <input type="hidden" name="booking_id" id="confirmBookingId" value="">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-50" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="confirm" class="btn btn-success btn-sm w-50">Confirm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" aria-labelledby="rejectBookingModal" tabindex="-1" id="rejectBookingModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="" method="post" class="modal-content" id="rejectBookingForm">
                <div class="modal-header">
                    <h4 class="modal-title">Reject Booking?</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to reject this booking request?</p>
                    
                    <textarea class="form-control" placeholder="Kindly provide the reason here." name="reason" id="reason" style="height: 100px"></textarea>
                    
                    <p class="text-secondary mb-0 mt-4">Note: This action cannot be undone.</p>
                </div>

                <div class="modal-footer">
                    <div class="d-flex gap-3 w-50">
                        <input type="hidden" name="booking_id" id="rejectBookingId" value="">
                        <input type="hidden" name="user_id" id="rejectUserId" value="">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-50" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="reject" class="btn btn-success btn-sm w-50">Reject</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" aria-labelledby="cancelBookingModal" tabindex="-1" id="cancelBookingModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="" method="post" class="modal-content" id="cancelBookingForm">
                <div class="modal-header">
                    <h4 class="modal-title">Cancel Booking?</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to cancel this booking?</p>
                    
                    <textarea class="form-control" placeholder="Kindly provide the reason here." name="reason" id="reason" style="height: 100px"></textarea>
                    
                    <p class="text-secondary mb-0 mt-4">Note: This action cannot be undone.</p>
                </div>

                <div class="modal-footer">
                    <div class="d-flex gap-3 w-50">
                        <input type="hidden" name="booking_id" id="cancelBookingId" value="">
                        <input type="hidden" name="user_id" id="cancelUserId" value="">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-50" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="reject" class="btn btn-success btn-sm w-50">Confirm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade message-modal" aria-labelledby="messageModal" tabindex="-1" id="messageModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="messageTitle"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p id="messageBody"></p>
                </div>

                <div class="modal-footer">
                    <div class="d-flex gap-3 w-25">
                        <button type="button" class="btn btn-outline-success btn-sm w-100" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include_once __DIR__ . "/../assets/admin_sidebar.php"; ?>

    <div class="content collapsed" id="content">
        <div class="container-fluid py-4 px-4 px-xl-5">
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0">
                <h3>Booking Management</h3>
                <?php include_once __DIR__ . "/../assets/admin_profile.php"; ?>
            </div>
            <?php include_once __DIR__ . "/../assets/admin_navtab.php"; ?>
            <div class="d-flex gap-3 my-3">
                <div class="input-group w-25 w-md-50">
                    <span class="input-group-text bg-success-subtle" id="basic-addon1">Filter by Remarks</span>
                    <select name="status" id="statusSelect" class="form-select">
                        <option value="All">All</option>
                        <option value="Pending" selected>Pending</option>
                        <option value="Confirmed">Confirmed</option>
                        <option value="Canceled">Canceled</option>
                        <option value="Rejected">Rejected</option>
                        <option value="Completed">Completed</option>
                    </select>
                </div>
                <div class="input-group w-25 w-md-50">
                    <span class="input-group-text bg-success-subtle" id="basic-addon2">Records per page</span>
                    <select name="limit" id="limitSelect" class="form-select">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
            <div class="table-responsive-xl" >
                <table class="table table-hover text-secondary overflow-hidden border rounded px-4">
                    <thead>
                        <tr>
                            <th class="sort" data-order="asc" data-column="client_name" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Client Name</th>
                            <th class="sort" data-order="asc" data-column="contact_number" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Contact Number</th>
                            <th class="sort" data-order="asc" data-column="destination" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Destination</th>
                            <th class="sort" data-order="asc" data-column="pickup_point" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Total Cost</th>
                            <th class="sort" data-order="asc" data-column="date_of_tour" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Date of Tour</th>
                            <th class="sort" data-order="asc" data-column="number_of_days" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Days</th>
                            <th class="sort" data-order="asc" data-column="number_of_buses" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Buses</th>
                            <th class="sort" data-order="asc" data-column="payment_status" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Payment Status</th>
                            <th class="sort" style="text-align: center; width: 15%; background-color: #d1f7c4; white-space: nowrap;">Action</th></tr>
                    </thead>
                    <tbody id="tableBody"> 
                       
                    </tbody>
                </table>
            </div>
            <div id="paginationContainer" class="mt-4"></div>
        </div>
    </div>
    

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="../../../public/js/admin/booking_management.js"></script>
    <script src="../../../public/js/assets/sidebar.js"></script>
    <script src="../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>