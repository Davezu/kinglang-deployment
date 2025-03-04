<?php
require_once "../../controllers/client/BookingController.php";

if (!isset($_SESSION["user_id"])) {    
    header("Location: ../../../public/index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/styles.css">
    <link rel="stylesheet" href="../../../public/css/home.css">
    <link rel="stylesheet" href="../../../public/css/client/booking_request.css">
    <title>Document</title>
</head>
<body>
    <div class="side-bar">
        <div class="company-name"><p>KingLang Transport</p></div>
        <div class="menu">
            <a href="home.php">Dashboard</a>
            <a href="">My Bookings</a>
            <a href="../../controllers/client/BookingController.php?user_id=<?php echo $_SESSION["user_id"] ?>">Book a Trip</a>
        </div>
    </div>

    <div class="main-section">
        <div class="header">
            <p class="username">Welcome, <?php echo $_SESSION["username"] ?></p>
            <input type="text" name="search" id="search" placeholder="Search Bookings">
            <a href="logout.php">Logout</a>
        </div>
        <div class="booking_requests">  
            <table>
                <thead>
                    <tr><th>Destination</th><th>Date of Tour</th><th>End of Tour</th><th>Days</th><th>Buses</th><th>Total Cost</th><th>Action</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking["destination"]); ?></td>
                            <td><?= htmlspecialchars($booking["date_of_tour"]); ?></td>
                            <td><?= htmlspecialchars($booking["end_of_tour"]); ?></td>
                            <td width="5%"><?= htmlspecialchars($booking["number_of_days"]); ?></td>
                            <td width="10%"><?= htmlspecialchars($booking["number_of_buses"]); ?></td>
                            <td class="total-cost"><?= htmlspecialchars($booking["total_cost"]); ?></td>
                            <td>
                                <?php if ($booking["total_cost"] !== NULL && $booking["payment_status"] !== "paid"): ?>
                                    <form action="" method="post">
                                        <button data-amount="<?= $booking["total_cost"] ?>" data-bookingID="<?= $booking["booking_id"] ?>" data-clientID="<?= $booking["client_id"]; ?>" class="open-payment-modal">Pay</button>
                                    </form>
                                <?php else: ?>
                                    <form action="" method="post">
                                        <button type="submit" name="status" value="canceled">Cancel</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="payment-modal">
        <form class="payment-content" action="../../controllers/client/PaymentController.php" method="post">
            <p>Please select the amount you want to pay.</p>
            <div class="amount-payment">  
                <h3>Full payment</h3>
                <p id="fullAmount" class="amount"></p>
            </div>
            <div class="amount-payment">
                <h3>Down payment</h3>
                <p id="partialAmount" class="amount"></p>
            </div>

            <input type="hidden" name="booking_id" id="bookingID">
            <input type="hidden" name="client_id" id="clientID">
            <input type="hidden" name="amount" id="amountInput">

            <div class="total-amount">Amount: <span id="amount"></span></div>

            <div class="payment-method">
                <label for="">Payment method</label>
                <select name="payment_method" id="">
                    <option value="Cash">Cash</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Online Payment">Online Payment</option>
                </select>
            </div>
                                    
            <button class="pay" type="submit">Pay Now</button>
        </form>
    </div>

    <script src="../../../public/js/client/booking_request.js"></script>
</body>
</html>