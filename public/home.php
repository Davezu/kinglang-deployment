<?php
// Use our common authentication helper function
if (is_client_authenticated()) {
    header("Location: /home/booking-requests");
    exit();
}   

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="public/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="public/css/login-signup.css">
    <title>Home</title>
</head>
<body>
   
    <div class="header d-flex justify-content-between align-items-center px-4 border">
        <div class="logo">
            <img src="public/images/logo.png" alt="">
        </div>
        <div class="d-flex gap-3">
            <a href="/home" class="text-dark">Home</a>
            <a href="#" class="text-dark">About</a>
        </div>
        <div class="d-flex gap-2">
            <a href="/home/login" class="btn btn-outline-success btn-sm">Log In</a>
            <a href="/home/signup" class="btn btn-outline-success btn-sm">Sign up</a>
        </div>
    </div>
    <div class="container center-row">
    </div>
</body>
</html>