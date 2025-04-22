<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/../../../public/css/bootstrap/bootstrap.min.css">
    <title>My Account</title>
</head>
<body>
    <?php include_once __DIR__ . "/../assets/sidebar.php"; ?>

    <div class="content collapsed" id="content">

        <div class="container-fluid py-4 px-4 px-xl-5">
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0">
                <div class="p-0">
                    <h3>Welcome, <span class="text-capitalize text-success"><?= $_SESSION["client_name"]; ?></span></h3>
                </div>
                <?php include_once __DIR__ . "/../assets/user_profile.php"; ?>
            </div>
            <div class="container-fluid border rounded hv-100 my-3">
                <div class="container w-50">
                    <form action="" id="userForm" class="mt-4">
                        <input type="hidden" name="id" value="1">
                        
                        <div class="row mb-3 g-3">
                            <div class="col">
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" name="" id="firstName" class="form-control" value="" required>
                            </div>
                            <div class="col">
                                <label for="" class="form-label">Last Name</label>
                                <input type="text" name="" id="lastName" class="form-control" required>
                            </div>
                        </div>
                        <div class="row mb-3 g-3">
                            <div class="col">
                                <label for="number_of_days" class="form-label">Email Address</label>
                                <input type="text" name="number_of_days" id="email" class="form-control" required>
                            </div> 
                            <div class="col">
                                <label for="" class="form-label">Phone Number</label>
                                <input type="text" name="" id="contactNumber" class="form-control" placeholder="0939-494-4394" maxlength="13" required>
                                <small id="phoneHelp" class="form-text text-muted">Format: 09XX-XXX-XXXX</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div id="busSelection"></div>
                        </div>

                        <div class="row mb-4">  
                            <div class="col">           
                                <button type="submit" name="submit_booking" class="btn btn-success">Edit</button>
                            </div>
                            <div class="col">
                                <p id="userMessage" style="color: green"></p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="../../../public/js/client/user_account.js"></script>
    <script src="../../../public/js/assets/sidebar.js"></script>
    <script>
        // Format phone number as user types
        document.addEventListener('DOMContentLoaded', function() {
            const contactNumberInput = document.getElementById('contactNumber');
            
            contactNumberInput.addEventListener('input', function(e) {
                // Remove all non-digit characters
                let value = this.value.replace(/\D/g, '');
                
                // Format the number as it's being typed
                if (value.length > 0) {
                    // Add the first part (09XX)
                    if (value.length <= 4) {
                        this.value = value;
                    } 
                    // Add hyphen after 4 digits (09XX-XXX)
                    else if (value.length <= 7) {
                        this.value = value.substring(0, 4) + '-' + value.substring(4);
                    } 
                    // Add hyphen after 7 digits (09XX-XXX-XXXX)
                    else {
                        this.value = value.substring(0, 4) + '-' + value.substring(4, 7) + '-' + value.substring(7, 11);
                    }
                }
            });
        });
    </script>
</body>
</html>