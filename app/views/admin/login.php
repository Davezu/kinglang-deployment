<?php
?>

<!DOCTYPE html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../../public/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../../../public/css/login-signup.css">
    <title>Log In</title>
</head>
<body>

    <div class="header d-flex justify-content-between align-items-center px-4 border">
        <div class="logo">
            <img src="../../../public/images/logo.png" alt="">
        </div>
        <div class="d-flex gap-3">
            <a href="/home" class="text-dark">Home</a>
            <a href="#" class="text-dark">About</a>
        </div>
        <div class="">
            <a href="/admin/login" class="btn btn-success btn-sm">Log In</a>
        </div>
    </div>

    <div class="content container-fluid p-0 m-0 d-flex flex-wrap">
        <div class="image-container">
            <img src="../../../public/images/bus3.jpg" alt="" class="image">
            <div class="overlay"><p class="overlay-text lh-sm fw-bolder">YOUR ON-THE-GO TOURIST BUS RENTAL!</p></div>
        </div>
        <div class="form-container d-flex flex-column justify-content-center">
            <form action="" method="" id="loginForm" class="d-flex flex-column p-lg-5 m-lg-5">
                <div class="mb-3">
                    <p class="welcome h3 text-success">Welcome back Admin!</p>
                    <p class="sub-message text-warning">Please login to continue to your account.</p>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label text-secondary">Email Address</label>
                    <input type="email" name="username" value="" id="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label text-secondary">Password</label>
                    <input type="password" name="password" id="password" class="form-control" requred>
                    <!-- <p class="sub-message">Use 8 or more characters with a mix of letters, numbers, & symbols</p> -->
                </div>

                <button type="submit" name="login" class="btn btn-success w-100 text-white fw-bold rounded-pill p-2">Log In</button>    
                
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../../../public/js/jquery/jquery-3.6.4.min.js"></script>
    <script src="../../../public/js/admin/login.js"></script>
</body>
</html>