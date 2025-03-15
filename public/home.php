<?php
// session_start();

if (isset($_SESSION["user_id"])) {
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
    <link rel="stylesheet" href="public/css/styles.css">
    <link rel="stylesheet" href="public/css/login-signup.css">
    <title>Home</title>
</head>
<body>
   
    <div class="header">
        <div class="logo">
            <img src="public/images/logo.png" alt="">
        </div>
        <div class="navbar">
            <a href="/home">Home</a>
            <a href="#">About</a>
        </div>
        <div class="buttons">
            <a href="/home/login">Log In</a>
            <a href="/home/signup">Sign up</a>
        </div>
    </div>
    <div class="container center-row">
    </div>
</body>
</html>