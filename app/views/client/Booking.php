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
    <link rel="stylesheet" href="/../../../public/css/bootstrap/bootstrap.min.css">  
    <title>Book a Trip</title>
</head>
<body>

    <div class="d-flex">
        <?php include_once __DIR__ . "/../assets/sidebar.php"; ?>
        
        <div class="flex-grow-1 p-4">
        <div class="container-fluid d-flex justify-content-between align-items-center p-0 m-0">
                <div class="p-0">
                    <h3>Book a Trip</h3>
                </div>
                <?php include_once __DIR__ . "/../assets/user_profile.php"; ?>
            </div>
            <div class="container-fluid d-flex justify-content-center align-items-center mt-4">
                <div class="col-md-6 col-lg-4 border rounded p-3">
                    <form action="" id="bookingForm" class="mt-4">
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
                            <div id="busSelection"></div>
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
    </div>

    <script src="../../../public/js/client/booking.js"></script>    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>