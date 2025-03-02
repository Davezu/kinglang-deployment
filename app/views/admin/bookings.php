<?php
require_once "../../controllers/admin/bookings.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/styles.css ">
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
                            <form action="../../controllers/admin/booking.php" method="post">
                                <input type="hidden" name="booking_id" value="<?= $booking["booking_id"]; ?>">
                                <button type="submit" name="status" value="confirmed">Confirm</button>
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
</body>
</html>