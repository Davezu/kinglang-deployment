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
    <title>Document</title>
</head>
<body>
    <div class="side-bar">
        <div class="company-name"><p>KingLang Transport</p></div>
        <div class="menu">
            <a href="#">Dashboard</a>
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
        <div class="overview">
            <div class="card">Upcoming Trip</div>
            <div class="card">Pending Payments</div>
            <div class="card">Confirmed Bookings</div>      
        </div>
        <div class="trip-history">  
            <table>
                <thead>
                    <tr><th>Destination</th><th>Date of Tour</th><th>Payemnt Status</th></tr>
                </thead>
            </table>
        </div>
    </div>
</body>
</html>