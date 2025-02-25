<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/index.css">
    <title>Login & Signup</title>
</head>
<body>
    <div class="header">
        <a href="#" id="login">Log in</a>
        <a href="#" id="signup">Sign up</a>
    </div>

    <!-- LOGIN FORM -->
    <div class="log-in center-column">
        <form action="login.php" method="POST">
            <div class="input">
                <label for="email">Email</label>
                <input type="email" name="email" required>
            </div>
            <div class="input">
                <label for="password">Password</label>
                <input type="password" name="password" required>
            </div>
            <button type="submit">Log In</button>
        </form>
        <?php
        if (isset($_SESSION['login_message'])) {
            echo "<p style='color: red'>" . $_SESSION['login_message'] . "</p>";
            unset($_SESSION['login_message']);
        }
        ?>
    </div>

    <!-- SIGNUP FORM -->
    <div class="sign-up center-column">
        <form action="signup.php" method="POST">
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
            </div>
            <div class="input">
                <label for="confirm_password">Confirm password</label>
                <input type="password" name="confirm_password" required>    
            </div>
            <button type="submit">Sign Up</button>
        </form>
        <?php
        if (isset($_SESSION['signup_message'])) {
            echo "<p style='color: red'>" . $_SESSION['signup_message'] . "</p>";
            unset($_SESSION['signup_message']);
        }
        ?>
    </div>

    <!-- <script src="landing_page.js"></script> -->
</body>
</html>