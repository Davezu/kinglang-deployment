<?php
require_once __DIR__ . '/../../../config/database.php';
require_once __DIR__ . '/../../controllers/client/AuthController.php';


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
    <link rel="stylesheet" href="../../../public/css/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="../../../public/css/login-signup.css">
    <link rel="stylesheet" href="../../../public/css/slideshow.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Sign Up</title>
    <style>
        /* More compact form styling */
        .form-container .form-label {
            margin-bottom: 0.25rem;
        }
        .form-container .mb-3 {
            margin-bottom: 0.75rem !important;
        }
        .welcome {
            margin-bottom: 0.25rem;
        }
        .sub-message {
            margin-bottom: 0.5rem;
        }
        /* Password field styling */
        .password-container {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            z-index: 10;
        }
        /* Password validation styling */
        .password-requirements {
            font-size: 0.75rem;
            line-height: 1.1;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.25rem;
        }
        .requirement {
            display: inline-flex;
            align-items: center;
            margin-right: 0.5rem;
        }
        .requirement i {
            font-size: 0.7rem;
            margin-right: 0.15rem;
        }
        .requirement i.bi-check-circle {
            color: #5db434 !important;
        }
    </style>
</head>
<body>
    <div class="header d-flex justify-content-between align-items-center px-4 border">
        <div class="logo">
            <img src="../../../public/images/logo.png" alt="">
        </div>
        <div class="d-flex gap-4 user-actions">
            <a href="avbar">
            <a href="/home">Home</a>
            <a href="#">About</a>
        </div>
        <div class="d-flex gap-2">
            <a href="/home/login" class="btn btn-outline-success btn-sm ">Log In</a>
            <a href="/home/signup" class="btn btn-success btn-sm">Sign up</a>
        </div>
    </div>

    <div class="content container-fluid d-flex p-0 m-0">
        <div class="form-container d-flex flex-column justify-content-center px-xl-3">
            <form action="" method="" id="signupForm" class="d-flex flex-column px-xl-4 mx-xl-4 px-md-3 mx-md-3 px-sm-1 mx-sm-1">
                <div class="mb-3">
                    <p class="welcome h3 text-success">Create an account</p>
                    <p class="sub-message text-warning">Already have an account? <a href="/home/login" class="link-warning link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">Log In</a></p>
                    <p class="signup-text text-success"></p>
                    <p class="signup-error-text text-danger"></p>
                </div>
                <div class="row mb-3 g-2">
                    <div class="col">
                        <label for="firstName" class="form-label text-secondary">First Name</label>
                        <input type="text" name="firstName" id="firstName" class="form-control"> 
                    </div>
                    <div class="col">
                        <label for="lastName" class="form-label text-secondary">Last Name</label>
                        <input type="text" name="lastName" id="lastName" class="form-control">    
                    </div>   
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label text-secondary">Email</label>
                    <input type="email" name="email" id="email" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="contactNumber" class="form-label text-secondary">Contact Number</label>
                    <input type="text" name="contactNumber" id="contactNumber" class="form-control" placeholder="0939-494-4394" maxlength="13">
                    <small id="contactNumberHelp" class="form-text text-muted">Format: 09XX-XXX-XXXX</small>
                </div>
                <div class="mb-3">
                    <label for="new_password" class="form-label text-secondary">Create password</label>
                    <div class="password-container">
                        <input type="password" name="new_password" id="password" class="form-control">
                        <span class="password-toggle" onclick="togglePasswordVisibility('password', 'togglePassword1')">
                            <i class="bi bi-eye" id="togglePassword1"></i>
                        </span>
                    </div>
                    <div id="passwordRequirements" class="password-requirements">
                        <small class="requirement" id="length-req"><i class="bi bi-x-circle text-danger"></i> 8+ chars</small>
                        <small class="requirement" id="uppercase-req"><i class="bi bi-x-circle text-danger"></i> 1 uppercase</small>
                        <small class="requirement" id="lowercase-req"><i class="bi bi-x-circle text-danger"></i> 1 lowercase</small>
                        <small class="requirement" id="number-req"><i class="bi bi-x-circle text-danger"></i> 1 number</small>
                        <small class="requirement" id="special-req"><i class="bi bi-x-circle text-danger"></i> 1 special</small>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label text-secondary">Confirm password</label>
                    <div class="password-container">
                        <input type="password" name="confirm_password" id="confirmPassword" class="form-control">
                        <span class="password-toggle" onclick="togglePasswordVisibility('confirmPassword', 'togglePassword2')">
                            <i class="bi bi-eye" id="togglePassword2"></i>
                        </span>
                    </div>
                    <small id="passwordMatch" class="form-text"></small>
                </div>
                <div class="mb-2">
                    <p class="sub-message small">By creating an account, you agree to our <a href="#" class="link-body-emphasis link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover">Terms of Use</a> and <a href="#" class="link-body-emphasis link-offset-2 link-underline-opacity-25 link-underline-opacity-75-hover">Privacy Policy</a></p>
                </div>
                <div class="button-message">
                    <button type="submit" name="signup" class="btn btn-success text-white w-100 rounded-pill">Create an account</button> 
                </div>
            </form>  
        </div>
        <div class="image-container">   
            <div class="slideshow-container">
                <div class="slideshow-slide">
                    <img src="../../../public/images/bus3.jpg" alt="Bus Image 1">
                    <div class="slideshow-text">YOUR ON-THE-GO TOURIST BUS RENTAL!</div>
                    <div class="slideshow-contact-info">
                        <div class="slideshow-contact-details">
                            <a href="tel:0917-8822727" class="contact-item">
                                <span>📞 0917 882 2727 | 0933 862 4323</span>
                            </a>
                            <a href="mailto:bsmillamina@yahoo.com" class="contact-item">
                                <span>✉️ bsmillamina@yahoo.com</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="slideshow-slide">
                    <img src="../../../public/images/slideshow/slide2.jpg" alt="Bus Image 2">
                    <div class="slideshow-text">EXPERIENCE COMFORT AND LUXURY</div>
                    <div class="slideshow-contact-info">
                        <div class="slideshow-contact-details">
                            <a href="tel:0917-8822727" class="contact-item">
                                <span>📞 0917 8822 727 | 0933 862 4323</span>
                            </a>
                            <a href="mailto:bsmillamina@yahoo.com" class="contact-item">
                                <span>✉️ bsmillamina@yahoo.com</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="slideshow-slide">
                    <img src="../../../public/images/slideshow/slide3.jpg" alt="Bus Image 3">
                    <div class="slideshow-text">TRAVEL WITH STYLE AND SAFETY</div>
                    <div class="slideshow-contact-info">
                        <div class="slideshow-contact-details">
                            <a href="tel:0917-8822727" class="contact-item">
                                <span>📞 0917 882 2727 | 0933 862 4323</span>
                            </a>
                            <a href="mailto:bsmillamina@yahoo.com" class="contact-item">
                                <span>✉️ bsmillamina@yahoo.com</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="../../../public/js/slideshow.js"></script>
    <script src="../../../public/js/page-transition.js"></script>
    <script src="../../../public/js/client/signup.js"></script>
    <script>
        function togglePasswordVisibility(inputId, toggleId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(toggleId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }

        // Phone number formatting and validation
        document.addEventListener('DOMContentLoaded', function() {
            const contactNumberInput = document.getElementById('contactNumber');
            const contactNumberHelp = document.getElementById('contactNumberHelp');
            
            contactNumberInput.addEventListener('input', function(e) {
                // Remove all non-digit characters
                let value = this.value.replace(/\D/g, '');
                
                // Check if it starts with anything other than 09
                if (value.length >= 2 && value.substring(0, 2) !== '09') {
                    contactNumberHelp.classList.remove('text-muted');
                    contactNumberHelp.classList.add('text-danger');
                    contactNumberHelp.textContent = 'Phone number must start with 09';
                } else {
                    contactNumberHelp.classList.remove('text-danger');
                    contactNumberHelp.classList.add('text-muted');
                    contactNumberHelp.textContent = 'Format: 09XX-XXX-XXXX';
                }
                
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
            
            // Validate on form submission
            const signupForm = document.getElementById('signupForm');
            signupForm.addEventListener('submit', function(e) {
                const phoneValue = contactNumberInput.value.replace(/\D/g, '');
                
                // Validate phone number format and length
                if (phoneValue.length > 0) {
                    if (phoneValue.substring(0, 2) !== '09' || phoneValue.length !== 11) {
                        e.preventDefault();
                        contactNumberHelp.classList.remove('text-muted');
                        contactNumberHelp.classList.add('text-danger');
                        contactNumberHelp.textContent = 'Invalid phone number. Must be 11 digits starting with 09';
                        return false;
                    }
                }
            });
        });

        // Password validation
        document.addEventListener('DOMContentLoaded', function() {
            // Password validation elements
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('confirmPassword');
            const passwordMatchMsg = document.getElementById('passwordMatch');
            
            // Password requirement elements
            const lengthReq = document.getElementById('length-req');
            const uppercaseReq = document.getElementById('uppercase-req');
            const lowercaseReq = document.getElementById('lowercase-req');
            const numberReq = document.getElementById('number-req');
            const specialReq = document.getElementById('special-req');
            
            // Password validation
            passwordInput.addEventListener('input', function() {
                const value = this.value;
                
                // Check length (at least 8 characters)
                if (value.length >= 8) {
                    updateRequirement(lengthReq, true);
                } else {
                    updateRequirement(lengthReq, false);
                }
                
                // Check uppercase letter
                if (/[A-Z]/.test(value)) {
                    updateRequirement(uppercaseReq, true);
                } else {
                    updateRequirement(uppercaseReq, false);
                }
                
                // Check lowercase letter
                if (/[a-z]/.test(value)) {
                    updateRequirement(lowercaseReq, true);
                } else {
                    updateRequirement(lowercaseReq, false);
                }
                
                // Check number
                if (/[0-9]/.test(value)) {
                    updateRequirement(numberReq, true);
                } else {
                    updateRequirement(numberReq, false);
                }
                
                // Check special character
                if (/[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(value)) {
                    updateRequirement(specialReq, true);
                } else {
                    updateRequirement(specialReq, false);
                }
                
                // Check if passwords match
                checkPasswordsMatch();
            });
            
            // Check if passwords match
            confirmPasswordInput.addEventListener('input', checkPasswordsMatch);
            
            function checkPasswordsMatch() {
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                
                if (confirmPassword === '') {
                    passwordMatchMsg.textContent = '';
                    passwordMatchMsg.className = 'form-text';
                } else if (password === confirmPassword) {
                    passwordMatchMsg.textContent = 'Passwords match';
                    passwordMatchMsg.className = 'form-text text-success';
                } else {
                    passwordMatchMsg.textContent = 'Passwords do not match';
                    passwordMatchMsg.className = 'form-text text-danger';
                }
            }
            
            function updateRequirement(element, isValid) {
                if (isValid) {
                    element.querySelector('i').classList.remove('bi-x-circle', 'text-danger');
                    element.querySelector('i').classList.add('bi-check-circle', 'text-success');
                } else {
                    element.querySelector('i').classList.remove('bi-check-circle', 'text-success');
                    element.querySelector('i').classList.add('bi-x-circle', 'text-danger');
                }
            }
            
            // Validate on form submission
            const signupForm = document.getElementById('signupForm');
            signupForm.addEventListener('submit', function(e) {
                // Check if all password requirements are met
                const password = passwordInput.value;
                const confirmPassword = confirmPasswordInput.value;
                
                const lengthValid = password.length >= 8;
                const uppercaseValid = /[A-Z]/.test(password);
                const lowercaseValid = /[a-z]/.test(password);
                const numberValid = /[0-9]/.test(password);
                const specialValid = /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/.test(password);
                const passwordsMatch = password === confirmPassword;
                
                if (!(lengthValid && uppercaseValid && lowercaseValid && numberValid && specialValid)) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Password Requirements',
                        text: 'Please meet all password requirements before submitting.'
                    });
                    return false;
                }
                
                if (!passwordsMatch) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Passwords Do Not Match',
                        text: 'Please make sure your passwords match.'
                    });
                    return false;
                }
            });
        });
    </script>
</body>
</html>