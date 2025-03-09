<?php
?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="../../../public/css/styles.css">
    <link rel="stylesheet" href="../../../public/css/login-signup.css"> -->
    <title>Log In</title>
</head>
<body>

    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a href="#" class="navbar-brand">
                <img src="../../../public/images/logo.png" alt="KingLang" height="40">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-lg-2 me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="#" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">About</a>
                    </li>
                </ul>
                <ul class="navbar-nav d-flex">
                    <li class="nav-item">
                        <a href="#" class="btn btn-outline-success" aria-current="page">Log In</a>
                    </li>
                </ul>
            </div>
        </div>
       
    </nav>
   
    <!-- <div class="header">
        <div class="logo">
            <img src="../../../public/images/logo.png" alt="">
        </div>
        <div class="navbar">
            <a href="../../../public/index.php">Home</a>
            <a href="#">About</a>
        </div>
        <div class="buttons">
            <a href="login.php" class="btn btn-outline-success btn-sm">Log In</a>
        </div>
    </div> -->


    <div class="container-fluid">
        <div class="row w-100">
            <div class="col text-bg-primary">
                <!-- <img src="../../../public/images/bus3.jpg" alt="" class="object-fit-cover"> -->
                <div class="overlay"><p class="content">YOUR ON-THE-GO TOURIST BUS RENTAL!</p></div>
            </div>
            <div class="col text-bg-warning">
                <form action="/admin/login/process" method="POST">
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
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>