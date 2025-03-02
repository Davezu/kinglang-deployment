<?php
session_start();

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
    <title>Log In</title>
</head>
<body>
   
    <div class="header">
        <div class="logo">
            <img src="../../../public/images/logo.png" alt="">
        </div>
        <div class="navbar">
            <a href="../../../public/index.php">Home</a>
            <a href="#">About</a>
        </div>
        <div class="buttons">
            <a href="login.php" class="bg-primary">Log In</a>
            <a href="signup.php">Sign up</a>
        </div>
    </div>
    <div class="container center-row">
        <div class="images">
            <img src="../../../public/images/bus3.jpg" alt="">
            <div class="overlay"><p class="content">YOUR ON-THE-GO TOURIST BUS RENTAL!</p></div>
        </div>
        <div class="log-in center-column">
            <form action="../../controllers/client/auth.php" method="POST">
                <div class="message">
                    <p class="welcome">Welcome Back!</p>
                    <p class="sub-message">Please login to continue to your account.</p>
                </div>
                <div class="input">
                    <label for="email">Username
                        <?php  
                            if (isset($_SESSION["message"]) && $_SESSION["message"] === "Username not found!") {
                                echo "<p style='color: red'>" . $_SESSION["message"] . "</p>";
                            } 
                        ?>
                    </label>
                    <input type="text" name="username" value="<?php echo isset($_SESSION["entered_username"]) ? $_SESSION["entered_username"] : ""; ?>" required>
                </div>
                <div class="input">
                    <label for="password">Password 
                        <?php  
                            if (isset($_SESSION["message"]) && $_SESSION["message"] === "Incorrect password!") {
                                echo "<p style='color: red'>" . $_SESSION["message"] . "</p>";
                            } 
                        ?>
                    </label>
                    <input type="password" name="password" requred>
                    <p class="sub-message">Use 8 or more characters with a mix of letters, numbers, & symbols</p>
                </div>
                <div class="login-button">
                    <button type="submit" name="login">Log In</button>
                    <p>Need an account? <a href="signup.php">Create one</a></p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>