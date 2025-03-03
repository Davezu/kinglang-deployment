<?php
require_once "../../controllers/admin/BookingManagementController.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/styles.css">
    <link rel="stylesheet" href="../../../public/css/admin/booking_management.css">
    <title>Document</title>
</head>
<body>
    <table>
        <thead>
            <tr><th>Client Name</th><th>Contact Number</th><th>Destination</th><th>Pick-up Point</th><th>Date of Tour</th><th>End of Tour</th><th>Number of Days</th><th>Number of Buses</th><th>Status</th><th>Action</th></tr>
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
                        <?php if ($booking["status"] === "pending"): ?>
                            <form action="../../controllers/admin/BookingManagementController.php" method="post">
                                <input type="hidden" name="booking_id" value="<?= $booking["booking_id"]; ?>">
                                <button 
                                    type="submit" name="status" value="confirmed" class="calculateTotalCost"
                                    data-days="<?= htmlspecialchars($booking["number_of_days"]); ?>"
                                    data-buses="<?= htmlspecialchars($booking["number_of_buses"]); ?>"
                                    data-bookingID="<?= htmlspecialchars($booking["booking_id"]); ?>"
                                >Compute</button>
                                <button type="submit" name="status" value="rejected">Reject</button>
                            </form>
                        <?php else: ?>
                            <span>No action needed</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="payment-calculator">
        <form action="../../controllers/admin/BookingManagementController.php" method="post">
            <div class="input">
                <label for="distance">Distance (KM)</label>    
                <input type="number" step="0.01" name="distance" id="distance">
            </div>
            <div class="input">
                <label for="diesel">Diesel Price Per Liter</label>
                <input type="number" min="0"  step="0.01" name="diesel" id="diesel">       
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