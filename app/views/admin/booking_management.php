<?php
require_once __DIR__ . "/../../controllers/admin/BookingManagementController.php";

// session_start();

if (!isset($_SESSION["role"]) || $_SESSION["role"] !== "super_admin") {
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
    <!-- <link rel="stylesheet" href="../../../public/css/styles.css">
    <link rel="stylesheet" href="../../../public/css/home.css">
    <link rel="stylesheet" href="../../../public/css/admin/booking_management.css"> -->
    <title>Document</title>
</head>
<body>
    <div class="modal fade payment-calculator" aria-labelledby="calcualtorModal" tabindex="-1" id="calculatorModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="/send-quote" method="post" class="modal-content">
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
                    <button type="submit" id="confirm" class="btn btn-outline-success btn-sm">Send Quote</button>
                </div>
            </form>
        </div>
    </div>

    
    <div class="side-bar">
        <div class="company-name"><p>KingLang Transport</p></div>
        <div class="menu">
            <a href="#">Booking Requests</a>
        </div>
    </div>

    <div class="main-section">
        <div class="header">
            <p class="username">Welcome, [Admin]</p>
            <input type="text" name="search" id="search" placeholder="Search Bookings">
            <a href="/admin/logout">Logout</a>
        </div>
        
    </div>

    <div class="container-fluid">
        <table class="table table-hover">
            <thead>
                <tr><th>Client Name</th><th>Contact Number</th><th>Destination</th><th>Pick-up Point</th><th>Date of Tour</th><th>End of Tour</th><th>Days</th><th>Buses</th><th>Remarks</th><th>Payment Status</th><th>Action</th></tr>
            </thead>
            <tbody class="table-group-divider">
                <?php if (!empty($bookings) && is_array($bookings)): ?>
                <?php foreach ($bookings as $booking): ?>
                    <tr>
                        <td><?= htmlspecialchars($booking["client_name"]); ?></td>
                        <td><?= htmlspecialchars($booking["contact_number"]); ?></td>
                        <td><?= htmlspecialchars($booking["destination"]); ?></td>
                        <td><?= htmlspecialchars($booking["pickup_point"]); ?></td>
                        <td><?= htmlspecialchars($booking["date_of_tour"]); ?></td>
                        <td><?= htmlspecialchars($booking["end_of_tour"]); ?></td>
                        <td><?= htmlspecialchars($booking["number_of_days"]); ?></td>
                        <td><?= htmlspecialchars($booking["number_of_buses"]); ?></td>
                        <td><?= htmlspecialchars($booking["status"]); ?></td>
                        <td><?= htmlspecialchars($booking["payment_status"]); ?></td>
                        <td>
                            <?php if ($booking["status"] === "pending" && $booking["total_cost"] === NULL): ?>
                                <form action="" method="post">
                                    <input type="hidden" name="booking_id" value="<?= $booking["booking_id"]; ?>">
                                    <div class="btn-group w-100">
                                        <button 
                                            type="button" name="status"
                                            class="btn btn-outline-success btn-sm open-payment-modal calculateTotalCost" data-bs-toggle="modal" data-bs-target="#calculatorModal"
                                            data-days="<?= htmlspecialchars($booking["number_of_days"]); ?>"
                                            data-buses="<?= htmlspecialchars($booking["number_of_buses"]); ?>"
                                            data-bookingID="<?= htmlspecialchars($booking["booking_id"]); ?>"
                                        >Compute</button>
                                        <button type="submit" name="status" class="btn btn-outline-danger btn-sm">Reject</button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <span>No action needed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    

    <script src="../../../public/js/admin/booking_management.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>