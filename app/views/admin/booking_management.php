<?php
require_once "../../controllers/admin/BookingManagementController.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/styles.css">
    <link rel="stylesheet" href="../../../public/css/home.css">
    <link rel="stylesheet" href="../../../public/css/admin/booking_management.css">
    <title>Document</title>
</head>
<body>
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
            <a href="logout.php">Logout</a>
        </div>
        <table>
            <thead>
                <tr><th>Client Name</th><th>Contact Number</th><th>Destination</th><th>Pick-up Point</th><th>Date of Tour</th><th>End of Tour</th><th>Days</th><th>Buses</th><th>Status</th><th>Action</th></tr>
            </thead>
            <tbody>
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
                        <td>
                            <?php if ($booking["status"] === "pending" && $booking["total_cost"] === NULL): ?>
                                <form action="../../controllers/admin/BookingManagementController.php" method="post">
                                    <input type="hidden" name="booking_id" value="<?= $booking["booking_id"]; ?>">
                                    <div class="button-group">
                                        <button 
                                            type="submit" name="status" class="calculateTotalCost"
                                            data-days="<?= htmlspecialchars($booking["number_of_days"]); ?>"
                                            data-buses="<?= htmlspecialchars($booking["number_of_buses"]); ?>"
                                            data-bookingID="<?= htmlspecialchars($booking["booking_id"]); ?>"
                                            style="background-color: green; color: white;"
                                        >Compute</button>
                                        <button type="submit" name="status" style="background-color: red; color: white;">Reject</button>
                                    </div>
                                </form>
                            <?php else: ?>
                                <span>No action needed</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    

    <div class="payment-calculator">
        <form action="../../controllers/admin/BookingManagementController.php" method="post">
            <div class="input">
                <label for="distance">Distance (KM)</label>    
                <input type="number" step="0.01" name="distance" id="distance" required>
            </div>
            <div class="input">
                <label for="diesel">Diesel Price Per Liter</label>
                <input type="number" min="0"  step="0.01" name="diesel" id="diesel" required>       
            </div>
            <div class="input">
                <label for="numberOfDays">Number of Days</label>
                <input type="number" min="0" name="number_of_days" id="numberOfDays" disabled>
            </div>
            <div class="input">
                <label for="numberOfBuses">Number of Buses</label>
                <input type="number" name="number_of_buses" id="numberOfBuses" disabled> 
            </div>

            <input type="hidden" name="total_cost" id="totalCost">
            <input type="hidden" name="booking_id" id="bookingID">

            <p>Total Cost: <span id="totalCostDisplay"></span></p>

            <button type="submit" id="confirm">Send Quote</button>
        </form>
    </div>

    <script src="../../../public/js/admin/booking_management.js"></script>
</body>
</html>