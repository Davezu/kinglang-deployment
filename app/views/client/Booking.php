<?php
session_start();

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
    <link rel="stylesheet" href="../../../public/css/booking.css">
    <title>Document</title>
</head>
<body>

    <div class="side-bar">
        <div class="company-name"><p>KingLang Transport</p></div>
        <div class="menu">
            <a href="home.php">Dashboard</a>
            <a href="#">My Bookings</a>
            <a href="../../controllers/client/Booking.php?user_id=<?php echo $_SESSION["user_id"] ?>">Book a Trip</a>
        </div>
    </div>

    <div class="main-section">

        <div class="header">
            <p class="username">Welcome, <?php echo $_SESSION["username"] ?></p>
            <input type="text" name="search" id="search" placeholder="Search Bookings">
            <a href="logout.php">Logout</a>
        </div>

        <form action="../../controllers/client/Booking.php" method="POST" class="center-row">
            <input type="hidden" name="id" value="1">
            <div class="input">
                <label for="date_of_tour">Date of Tour:</label>
                <input type="date" name="date_of_tour" id="date_of_tour" palceholder="Date of Tour" required>
            </div>
            <div class="input">
                <label for="destination">Destination</label>
                <input type="text" name="destination" id="destination" required>
            </div>
            <div class="input">
                <label for="pickup_point">Pick-up point</label>
                <input type="text" name="pickup_point" id="pickup_point" required>
            </div>
            <div class="number-container">
                <div class="input">
                    <label for="number_of_days">Number of days</label>
                    <input type="number" name="number_of_days" id="number_of_days" required>
                </div>
                <div class="input">
                    <label for="number_of_buses">Number of buses</label>
                    <input type="number" name="number_of_buses" id="number_of_buses" required>
                </div>
            </div>

            <div class="button-message">
                <button type="submit" name="submit_booking">Book Now</button>
                <p class="booking-message" style="color: green"><?php echo isset($_SESSION["booking_message"]) ? $_SESSION["booking_message"] : ""; ?></p>
            </div>
        </form>

    </div>

</body>
</html>