<?php

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/styles.css">
    <link rel="stylesheet" href="../../../public/css/login-signup.css">
    <title>Document</title>
</head>
<body>
    <div class="log-in center-column">
        <form action="../../controllers/client/auth.php" method="POST">
            <div class="input">
                <label for="email">Username</label>
                <input type="text" name="username" required>
            </div>
            <div class="input">
                <label for="password">Password</label>
                <input type="password" name="password" requred>
            </div>
            <button type="submit" name="login">Log In</button>
        </form>
        <?php
        if (isset($_SESSION['login_message'])) {
            echo "<p style='color: red'>" . $_SESSION['login_message'] . "</p>";
            unset($_SESSION['login_message']);
        }
        ?>
    </div>
</body>
</html>