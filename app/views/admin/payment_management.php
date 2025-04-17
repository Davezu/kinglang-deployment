<?php
require_once __DIR__ . "/../../controllers/admin/PaymentManagementController.php";

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
    <title>Payment Management</title>
</head>

<body> 
    <div class="modal fade" aria-labelledby="confirmPaymentModal" tabindex="-1" id="confirmPaymentModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="" method="post" class="modal-content" id="confirmPaymentForm">
                <div class="modal-header">
                    <h4 class="modal-title">Confirm Payment?</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to confirm this payment?</p>
                    <p class="text-secondary">Note: This action cannot be undone.</p>
                </div>

                <div class="modal-footer">
                    <div class="d-flex gap-3 w-50">
                        <input type="hidden" name="payment_id" id="confirmPaymentId" value="">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-50" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="confirm" class="btn btn-success btn-sm w-50">Confirm</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" aria-labelledby="rejectPaymentModal" tabindex="-1" id="rejectPaymentModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="" method="post" class="modal-content" id="rejectPaymentForm">
                <div class="modal-header">
                    <h4 class="modal-title">Reject Payment?</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to reject this payment?</p>
                    
                    <textarea class="form-control" placeholder="Kindly provide the reason here." name="reason" id="reason" style="height: 100px"></textarea>
                    
                    <p class="text-secondary mb-0 mt-4">Note: This action cannot be undone.</p>
                </div>

                <div class="modal-footer">
                    <div class="d-flex gap-3 w-50">
                        <input type="hidden" name="payment_id" id="rejectPaymentId" value="">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-50" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="reject" class="btn btn-success btn-sm w-50">Reject</button>
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
                <h3>Payment Management</h3>
                <?php include_once __DIR__ . "/../assets/admin_profile.php"; ?>
            </div>
            <div class="d-flex gap-3 my-3">
                <div class="input-group w-25 w-md-50">
                    <span class="input-group-text bg-success-subtle" id="basic-addon1">Filter by Status</span>
                    <select name="status" id="statusSelect" class="form-select">
                        <option value="all">All</option>
                        <option value="PENDING">Pending</option>
                        <option value="CONFIRMED">Confirmed</option>
                        <option value="REJECTED">Rejected</option>
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
            <div class="table-responsive-xl">
                <table class="table table-hover text-secondary overflow-hidden border rounded px-4">
                    <thead>
                        <tr>
                            <th class="sort" data-order="asc" data-column="booking_id" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Booking ID</th>
                            <th class="sort" data-order="asc" data-column="client_name" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Client Name</th>
                            <th class="sort" data-order="asc" data-column="amount" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Amount</th>
                            <th class="sort" data-order="asc" data-column="payment_method" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Payment Method</th>
                            <th class="sort" data-order="asc" data-column="payment_date" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Payment Date</th>
                            <th class="sort" data-order="asc" data-column="status" style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Status</th>
                            <th style="text-align: center; width: 15%; background-color: #d1f7c4; white-space: nowrap;">Action</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                    </tbody>
                </table>
            </div>
            <div id="paginationContainer" class="mt-4"></div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="../../../public/js/admin/payment_management.js"></script>
    <script src="../../../public/js/assets/sidebar.js"></script>
    <script src="../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
</body>

</html> 