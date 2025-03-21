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
    <link rel="stylesheet" href="/../../../public/css/bootstrap/bootstrap.min.css">  
    <title>Document</title>
    
</head>
<body> 
    <div class="modal fade payment-calculator" aria-labelledby="calcualtorModal" tabindex="-1" id="calculatorModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="/send-quote" method="post" class="modal-content" id="calculatorForm">
                <div class="modal-header">
                    <h3 class="modal-title">Total Cost Calculator</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="distance" class="form-label">Distance (KM)</label>    
                        <input type="number" step="0.01" name="distance" id="distance" class="form-control" required>
                    </div>  
                    <div class="mb-3">
                        <label for="diesel" class="form-label">Diesel Price Per Liter</label>
                        <input type="number" min="0"  step="0.01" name="diesel" id="diesel" class="form-control" required>       
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col">
                            <label for="numberOfDays" class="form-label">Number of Days</label>
                            <input type="number" min="0" name="number_of_days" id="numberOfDays" class="form-control" disabled>
                        </div>
                        <div class="col">
                            <label for="numberOfBuses" class="form-label">Number of Buses</label>
                            <input type="number" name="number_of_buses" id="numberOfBuses" class="form-control" disabled> 
                        </div>
                    </div>

                    <input type="hidden" name="total_cost" id="totalCost">
                    <input type="hidden" name="booking_id" id="bookingID">

                    <p class="form-text">Total Cost: <span id="totalCostDisplay" class="text-success"></span></p>
                </div>

                <div class="modal-footer">
                    <div class="container-fluid d-flex justify-content-between">
                        <button type="submit" id="confirm" class="btn btn-outline-success btn-sm">Send Quote</button>
                        <p id="messageElement"></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <?php include_once __DIR__ . "/../assets/admin_sidebar.php"; ?>

    <div class="content collapsed" id="content">
        <div class="container-fluid p-4">
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0">
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
                    <option value="Canceled">Canceled</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
            <div class="table-responsive-xl" >
                <table class="table table-hover text-secondary overflow-hidden border rounded px-4">
                    <thead>
                        <tr>
                            <th class="sort" data-order="asc" data-column="client_name">Client Name</th>
                            <th class="sort" data-order="asc" data-column="contact_number">Contact Number</th>
                            <th class="sort" data-order="asc" data-column="destination">Destination</th>
                            <th class="sort" data-order="asc" data-column="pickup_point">Pick-up Point</th>
                            <th class="sort" data-order="asc" data-column="date_of_tour">Date of Tour</th>
                            <th class="sort" data-order="asc" data-column="end_of_tour">End of Tour</th>
                            <th class="sort" data-order="asc" data-column="number_of_days">Days</th>
                            <th class="sort" data-order="asc" data-column="number_of_buses">Buses</th>
                            <th class="sort" data-order="asc" data-column="status">Remarks</th>
                            <th class="sort" data-order="asc" data-column="payment_status">Payment Status</th>
                            <th class="sort" style="text-align: center; width: 15%;">Action</th></tr>
                    </thead>
                    <tbody id="tableBody"> 
                       
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="../../../public/js/admin/booking_management.js"></script>
    <script src="../../../public/js/assets/sidebar.js"></script>
    <script src="../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html>