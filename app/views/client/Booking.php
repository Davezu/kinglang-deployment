<?php
if (!isset($_SESSION["user_id"])) {    
    header("Location: /home");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">  
    <!-- <link rel="stylesheet" href="../../../public/css/styles.css">
    <link rel="stylesheet" href="../../../public/css/home.css">
    <link rel="stylesheet" href="../../../public/css/booking.css"> -->
    <title>Book a Trip</title>
</head>
<body>

    <nav class="navbar navbar-expand-md bg-success" data-bs-theme="dark">
        <div class="container-fluid">
            <a href="#" class="navbar-brand">KingLang Transport</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="/client/home" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="/home/bookings/<?= $_SESSION["user_id"] ?>" class="nav-link" aria-current="page">My Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Book a Trip</a>
                    </li>
                </ul>
                <ul class="navbar-nav d-flex">
                    <li class="nav-item">
                        <a href="/logout" class="btn btn-outline-warning">Logout</a>
                    </li>
                </ul>   
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4 border rounded">
                <form action="/request-booking" id="bookingForm" method="POST" class="mt-4">
                    <input type="hidden" name="id" value="1">
                    <div class="mb-3">  
                        <label for="date_of_tour" class="form-label">Date of Tour</label>
                        <input type="date" name="date_of_tour" id="date_of_tour" class="form-control" palceholder="Date of Tour" required>
                    </div>
                    <div class="mb-3">
                        <label for="destination" class="form-label">Destination</label>
                        <input type="text" name="destination" id="destination" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="pickup_point" class="form-label">Pick-up point</label>
                        <input type="text" name="pickup_point" id="pickup_point" class="form-control" required>
                    </div>
                    <div class="row mb-3 g-3">
                        <div class="col">
                            <label for="number_of_days" class="form-label">Number of days</label>
                            <input type="number" name="number_of_days" id="number_of_days" class="form-control" required>
                        </div>
                        <div class="col">
                            <label for="number_of_buses" class="form-label">Number of buses</label>
                            <input type="number" name="number_of_buses" id="number_of_buses" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <div id="busSelection">hello</div>
                    </div>

                    <div class="row mb-4">
                        <div class="col">           
                            <button type="submit" name="submit_booking" class="btn btn-primary">Book Now</button>
                        </div>
                        <div class="col">
                            <p class="booking-message" style="color: green"><?php echo isset($_SESSION["booking_message"]) ? $_SESSION["booking_message"] : ""; ?></p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="../../../public/js/client/booking.js"></script>    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>