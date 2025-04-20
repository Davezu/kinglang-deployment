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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <title>Book a Trip</title>
</head>
<body>

    <?php include_once __DIR__ . "/../assets/sidebar.php"; ?>

    <div class="content collapsed" id="content">
        <div class="container-fluid p-0 m-0">
            <div class="container-fluid d-flex justify-content-end align-items-center py-4 px-4 px-xl-5">
                <?php include_once __DIR__ . "/../assets/user_profile.php"; ?>
            </div>
            <div class="container-fluid d-flex justify-content-center gap-5 p-0 m-0">
                <form action="" id="bookingForm" class="border rounded p-3 height-auto align-self-start">
                    <input type="hidden" name="id" value="1">
                    <div id="firstInfo">
                        <h3 class="mb-3" id="bookingHeader">Book a Trip</h3>
                        <div class="mb-3 position-relative">
                            <i class="bi bi-geo-alt-fill location-icon"></i>
                            <input type="text" name="pickup_point" id="pickup_point" class="form-control text-truncate address py-2 px-4" autocomplete="off" placeholder="Pickup Location" required>
                            <ul id="pickupPointSuggestions" class="suggestions"></ul>
                        </div> 
                        <div class="mb-3 position-relative">
                            <i class="bi bi-geo-alt-fill location-icon"></i>
                            <i class="bi bi-plus-circle-fill add-icon" id="addStop" title="Add stop"></i>
                            <input type="text" name="destination" id="destination" class="form-control text-truncate address destination added-stop py-2 px-4" autocomplete="off" placeholder="Dropoff Location" required>
                            <ul id="destinationSuggestions" class="suggestions"></ul>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-success w-100" id="nextButton">Next</button>
                        </div>
                    </div>
                    
                    <div class="d-none" id="nextInfo">
                        <div class="mb-3">
                            <i class="bi bi-chevron-left fs-4" id="back"></i>
                        </div>
                        <div class="mb-3 position-relative">  
                            <i class="bi bi-calendar-fill calendar-icon"></i>
                            <input type="text" name="date_of_tour" id="date_of_tour" class="form-control py-2 px-4" placeholder="Pickup Date" required>
                        </div>   
                        <div class="mb-3 position-relative">
                            <select name="" id="" class="form-select">
                                <option value="">Pickup Time</option>
                            </select>
                        </div>      
                        <div class="mb-3 d-flex gap-3">
                            <div class="d-flex flex-column">
                                <p>Number of Days</p>
                                <p>Number of Buses</p>
                            </div>
                            
                            <div class="d-flex flex-column">
                                <div class="d-flex gap-3">
                                    <i class="bi bi-dash-square" id="decreaseDays" title="Decrease days"></i>
                                    <p id="number_of_days">0</p>
                                    <i class="bi bi-plus-square" id="increaseDays" title="Add days"></i>
                                </div>
                                <div class="d-flex gap-3">
                                    <i class="bi bi-dash-square" id="decreaseBuses"></i>
                                    <p id="number_of_buses">0</p>
                                    <i class="bi bi-plus-square" id="increaseBuses"></i>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <p id="totalCost" class="fw-bold text-success"></p>
                        </div>

                        <div class="container-fluid d-flex justify-content-between align-items-center gap p-0">
                            <button type="submit" class="btn btn-success w-100" id="submitBooking">Request Booking</button>
                        </div>
                    </div>
                </form>
                <div class="border rounded" id="map">
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