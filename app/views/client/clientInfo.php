<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
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
            <a href="booking_requests.php">My Bookings</a>
            <a href="../../controllers/client/BookingController.php?user_id=<?php echo $_SESSION["user_id"] ?>">Book a Trip</a>
        </div>
    </div>

    <div class="main-section">
        <div class="header">
            <p class="username">Welcome, <?php echo $_SESSION["username"] ?></p>
            <input type="text" name="search" id="search" placeholder="Search Bookings">
            <a href="logout.php">Logout</a>
        </div>
        <form action="../../controllers/client/ClientInfoController.php" method="POST">
            <div class="input">
                <label for="">First Name</label>
                <input type="text" name="first_name" required>
            </div>
            <div class="input">
                <label for="">Last Name</label>
                <input type="text" name="last_name" required>
            </div>
            <div class="input">
                <label for="new_password">Address</label>
                <input type="text" name="address" required>
            </div>
            <div class="input">
                <label for="confirm_password">Contact Number</label>
                <input type="number" name="contact_number" required>    
            </div>
            <div class="input">
                <label for="confirm_password">Company Name</label>
                <input type="text" name="company_name">    
            </div>
            <button type="submit" name="client_info">Proceed</button>
        </form>
    </div>
    
</body>
</html>