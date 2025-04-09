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
    <link rel="stylesheet" href="/../../../public/css/bootstrap/bootstrap.min.css">  
    <link rel="stylesheet" href="/../../../public/css/client/booking.css">  
    <title>Book a Trip</title>
</head>
<body>

    <?php include_once __DIR__ . "/../assets/sidebar.php"; ?>

    <div class="modal fade message-modal" aria-labelledby="messageModal" tabindex="-1" id="messageModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="messageTitle"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p id="messageBody"></p>
                </div>

                <div class="modal-footer">
                    <div class="d-flex gap-3 w-25">
                        <button type="button" class="btn btn-outline-success btn-sm w-100" data-bs-dismiss="modal">Close</button>
                    </div>  
                </div>
            </div>
        </div>
    </div>
    
    <div class="content collapsed" id="content">
        <div class="container-fluid py-4 px-4 px-xl-5">
            <div class="container-fluid d-flex justify-content-between align-items-center p-0 m-0">
                <div class="p-0">
                    <h3>Book a Trip</h3>
                </div>
                <?php include_once __DIR__ . "/../assets/user_profile.php"; ?>
            </div>
            <div class="container-fluid d-flex justify-content-center align-items-center mt-4 gap-5">
                <form action="" id="bookingForm" class="mt-4 border rounded p-3">
                    <input type="hidden" name="id" value="1">
                    <div class="mb-3 position-relative">
                        <label for="pickup_point" class="form-label">Pick-up point</label>
                        <input type="text" name="pickup_point" id="pickup_point" class="form-control text-truncate address" autocomplete="off" >
                        <ul id="pickupPointSuggestions" class="suggestions"></ul>
                    </div>
                    <div class="mb-3 position-relative">
                        <div class="d-flex justify-content-between">
                            <label for="destination" class="form-label">Destination</label>
                            <p id="addStop" class="m-0">Add Stop</p>
                        </div>
                        <input type="text" name="destination" id="destination" class="form-control text-truncate address destination added-stop" autocomplete="off" >
                        <ul id="destinationSuggestions" class="suggestions"></ul>
                    </div>
                    <div class="mb-3">  
                        <label for="date_of_tour" class="form-label">Date of Tour</label>
                        <input type="date" name="date_of_tour" id="date_of_tour" class="form-control" palceholder="Date of Tour" >
                    </div>
                    <div class="row mb-3 g-3">
                        <div class="col">
                            <label for="number_of_days" class="form-label">Number of days</label>
                            <input type="number" name="number_of_days" id="number_of_days" class="form-control" >
                        </div>
                        <div class="col">
                            <label for="number_of_buses" class="form-label">Number of buses</label>
                            <input type="number" name="number_of_buses" id="number_of_buses" class="form-control" >
                        </div>
                    </div>

                    <div class="mb-4">
                        <div id="busSelection"></div>
                    </div>

                    <div class="container-fluid d-flex justify-content-between align-items-center mb-4 gap">
                        <button type="submit" name="submit_booking" class="btn btn-primary">Book Now</button>
                        <p class="booking-message" style="color: green"></p>
                        <p id="totalCost"></p>
                    </div>
                </form>
                <div class="border rounded w-50" id="map">
                    I'm the map
                </div>
            </div>
        </div>
    </div>

    <script src="../../../public/js/jquery/jquery-3.6.4.min.js"></script>
    <script src="../../../public/js/client/booking.js"></script>
    <script src="../../../public/js/assets/sidebar.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASHotkPROmUL_mheV_L9zXarFIuRAIMRs&callback=initMap" async defer></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>