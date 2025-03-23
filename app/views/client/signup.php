<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../controllers/client/AuthController.php';


// if (isset($_SESSION["user_id"])) {
//     header("Location: booking.php");
//     exit();
// }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet"> 
    <link rel="stylesheet" href="../../../public/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../../../public/css/login-signup.css">
    <title>Sign Up</title>
</head>
<body>
    <div class="header d-flex justify-content-between align-items-center px-4 border">
        <div class="logo">
            <img src="../../../public/images/logo.png" alt="">
        </div>
        <div class="d-flex gap-4">
            <a href="avbar">
            <a href="/home" class="text-dark">Home</a>
            <a href="#" class="text-dark">About</a>
        </div>
        <div class="d-flex gap-2">
            <a href="/home/login" class="btn btn-outline-success btn-sm ">Log In</a>
            <a href="/home/signup" class="btn btn-success btn-sm">Sign up</a>
        </div>
    </div>

    <div class="content container-fluid d-flex p-0 m-0">
        <div class="image-container">   
            <img src="../../../public/images/bus3.jpg" alt="" class="image">
            <div class="overlay"><p class="overlay-text lh-sm fw-bolder">YOUR ON-THE-GO TOURIST BUS RENTAL!</p></div>
        </div>
        <div class="form-container d-flex flex-column justify-content-center px-xl-4">
            <form action="" method="" id="signupForm" class="d-flex flex-column px-xl-5 mx-xl-5 px-md-3 mx-md-3 px-sm-1 mx-sm-1">
                <div class="mb-3">
                    <p class="welcome h3 text-success">Create an account</p>
                    <p class="sub-message text-warning">Already have an account? <a href="/home/login" class="link-warning link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">Log In</a></p>
                    <p class="sub-message message-text"></p>
                </div>
                <div class="row mb-3 g-3">
                    <div class="col">
                        <label for="firstName" class="form-label text-secondary">First Name</label>
                        <input type="text" name="firstName" id="firstName" class="form-control"> 
                    </div>
                    <div class="col">
                        <label for="lastName" class="form-label text-secondary">Last Name</label>
                        <input type="text" name="lastName" id="lastName" class="form-control">    
                    </div>   
                </div>
                <div class="mb-lg-3 mb-md-2">
                    <label for="email" class="form-label text-secondary">Email</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="contactNumber" class="form-label text-secondary">Contact Number</label>
                    <input type="number" name="contactNumber" id="contactNumber" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label text-secondary">Create password</label>
                    <input type="password" name="new_password" id="password" class="form-control">
                    <!-- <p class="sub-message">Use 8 or more characters with a mix of letters, numbers, & symbols</p> -->
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label text-secondary">Confirm password</label>
                    <input type="password" name="confirm_password" id="confirmPassword" class="form-control">    
                </div>
                <div class="mb-3">
                    <p class="sub-message">By creating an account, you agree to our <a href="#" class="link-body-emphasis link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover">Terms of Use</a> and <a href="#" class="link-body-emphasis link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover">Privacy Policy</a></p>
                </div>
                <div class="button-message">
                    <button type="submit" name="signup" class="btn btn-success text-white w-100 rounded-pill">Create an account</button> 
                </div>
            </form>  
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../../../public/js/client/signup.js"></script>
</body>
</html>