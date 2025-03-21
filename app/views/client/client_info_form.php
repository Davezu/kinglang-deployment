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
    <!-- <link rel="stylesheet" href="../../../public/css/styles.css">
    <link rel="stylesheet" href="../../../public/css/home.css">
    <link rel="stylesheet" href="../../../public/css/booking.css"> -->
    <title>Document</title>
</head>
<body>
        
    <?php include_once __DIR__ . "/../assets/sidebar.php"; ?>
    
    <div class="content collapsed" id="content">
        <div class="container-fluid p-4">
            <div class="container-fluid d-flex justify-content-between align-items-center p-0 m-0">
                <div class="p-0">
                    <h3>Book a Trip</h3>
                </div>
                <?php include_once __DIR__ . "/../assets/user_profile.php"; ?>
            </div>
            <div class="container-fluid d-flex justify-content-center align-items-center mt-4">
                <div class="col-md-6 col-lg-4 border rounded p-3">
                    <form action="/contact/submit" method="POST" id="bookingForm" class="mt-4">
                        <input type="hidden" name="id" value="1">
                        <div class="row mb-3 g-3">
                            <div class="col">  
                                <label for="" class="form-label">First Name</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Last Name</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3 g-3">
                            <div class="col">
                                <label for="emailAddress" class="form-label">Email Address</label>
                                <input type="number" name="email_address" class="form-control" required>   
                            </div>
                            <div class="col">
                                <label for="confirm_password" class="form-label">Contact Number</label>
                                <input type="number" name="contact_number" class="form-control" required>   
                            </div>
                        </div>

                        <div class="container-fluid d-flex justify-content-between align-items-center mb-4 gap">    
                            <button type="submit" name="client_info" class="btn btn-primary">Proceed</button>
                            <p class="booking-message" style="color: green"></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../../../public/js/assets/sidebar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>