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
    <link rel="stylesheet" href="../../../public/css/styles.css">
    <link rel="stylesheet" href="../../../public/css/login-signup.css">
    <title>Document</title>
</head>
<body>
    <div class="sign-up center-column">
        <form action="../../controllers/client/auth.php" method="POST">
            <div class="input">
                <label for="">First Name</label>
                <input type="text" name="first_name" required>
            </div>
            <div class="input">
                <label for="">Last Name</label>
                <input type="text" name="last_name" required>
            </div>
            <div class="input">
                <label for="new_password">Address</label>
                <input type="password" name="address" required>
            </div>
            <div class="input">
                <label for="confirm_password">Contact Number</label>
                <input type="password" name="contact_number" required>    
            </div>
            <div class="input">
                <label for="confirm_password">Company Name</label>
                <input type="password" name="company_name">    
            </div>
            <button type="submit" name="client_info">Proceed</button>
        </form>
    </div>
</body>
</html>
</body>
</html>