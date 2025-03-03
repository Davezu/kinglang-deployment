<?php
require_once '../../../config/database.php';
require_once '../../controllers/client/AuthController.php';


if (isset($_SESSION["user_id"])) {
    header("Location: booking.php");
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
    <link rel="stylesheet" href="../../../public/css/login-signup.css">
    <title>Sign Up</title>
</head>
<body>
    <div class="header">
        <div class="logo">
            <img src="../../../public/images/logo.png" alt="">
        </div>
        <div class="navbar">
            <a href="avbar">
            <a href="../../../public/index.php">Home</a>
            <a href="#">About</a>
        </div>
        <div class="buttons">
            <a href="login.php">Log In</a>
            <a href="signup.php" class="bg-primary">Sign up</a>
        </div>
    </div>

    <div class="container center-row">
        <div class="images">
            <img src="../../../public/images/bus3.jpg" alt="">
            <div class="overlay"><p class="content">YOUR ON-THE-GO TOURIST BUS RENTAL!</p></div>
        </div>
        <div class="sign-up center-column">
            <form action="../../controllers/client/AuthController.php" method="POST">
                <div class="message">
                    <p class="welcome">Create an account</p>
                    <p class="sub-message">Already have an account? <a href="login.php" style="color: var(--secondary-color);">Log In</a></p>
                </div>
                <div class="input">
                    <label for="username">Username</label>
                    <input type="text" name="username" required>    
                </div>
                <div class="input">
                    <label for="email">Email</label>
                    <input type="email" name="email" required>
                </div>
                <div class="input">
                    <label for="new_password">Create password</label>
                    <input type="password" name="new_password" required>
                    <p class="sub-message">Use 8 or more characters with a mix of letters, numbers, & symbols</p>
                </div>
                <div class="input">
                    <label for="confirm_password">Confirm password</label>
                    <input type="password" name="confirm_password" required>    
                </div>
                <div class="input">
                    <p class="sub-message">By creating an account, you agree to our <a href="#">Terms of Use</a> and <a href="#">Privacy Policy</a></p>
                </div>
                <div class="button-message">
                    <button type="submit" name="signup">Create an account</button> 
                    <p style="color: red"><?php echo isset($_SESSION["signup_message"]) && $_SESSION["signup_message"] !== "Signup successfully!" ? $_SESSION["signup_message"] : ""; ?></p>
                    <p style="color: green"><?php echo isset($_SESSION["signup_message"]) && $_SESSION["signup_message"] === "Signup successfully!" ? $_SESSION["signup_message"] : ""; ?></p>
                </div>
            </form>  
        </div>
    </div>
    
</body>
</html>