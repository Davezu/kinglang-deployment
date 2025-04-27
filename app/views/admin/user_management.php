<?php
require_once __DIR__ . "/../../controllers/admin/UserManagementController.php";

if (!isset($_SESSION["role"]) || ($_SESSION["role"] !== "Super Admin" && $_SESSION["role"] !== "Admin")) {
    header("Location: /admin/login");
    exit(); 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/../../../public/css/bootstrap/bootstrap.min.css">  
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>User Management</title>
    <style>
        /* Compact form styling */
        .modal-body {
            padding: 1rem 1.5rem;
        }
        .compact-form .mb-3 {
            margin-bottom: 0.5rem !important;
        }
        .compact-form .form-label {
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }
        .compact-form .form-control,
        .compact-form .form-select {
            padding: 0.375rem 0.5rem;
            font-size: 0.875rem;
        }
        .compact-form .form-text {
            font-size: 0.75rem;
            margin-top: 0.1rem;
        }
        .modal-footer {
            padding: 0.5rem 1.5rem 1rem;
        }
        /* Password field styling */
        .input-group {
            position: relative;
            z-index: 1;
        }
        .password-container {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 5;
            background: none;
            border: none;
            color: #6c757d;
            cursor: pointer;
            padding: 0;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .password-toggle:hover, .password-toggle:focus {
            color: #495057;
            outline: none;
        }
        .password-requirements {
            font-size: 0.75rem;
            line-height: 1.1;
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-top: 0.25rem;
            position: relative;
            z-index: 0;
            padding-top: 0.25rem;
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
    <!-- Add User Modal -->
    <div class="modal fade" aria-labelledby="addUserModal" tabindex="-1" id="addUserModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="" method="post" class="modal-content compact-form" id="addUserForm">
                <div class="modal-header py-2">
                    <h5 class="modal-title">Add User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="firstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="firstName" name="firstName" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="lastName" name="lastName" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="companyName" class="form-label">Company Name (Optional)</label>
                        <input type="text" class="form-control" id="companyName" name="companyName" placeholder="Company or organization name">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="contactNumber" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="contactNumber" name="contactNumber" placeholder="0939-494-4394" maxlength="13">
                        <small class="form-text phone-validation"></small>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="password-container">
                            <input type="password" class="form-control" id="password" name="password" required minlength="8">
                            <button type="button" class="password-toggle" id="toggleAddPassword">
                                <i class="bi bi-eye" id="toggleAddPasswordIcon"></i>
                            </button>
                        </div>
                        <small class="form-text text-muted">Password must be at least 8 characters</small>
                        <!-- Container for password requirements -->
                        <div id="passwordRequirements" class="password-requirements"></div>
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select" id="role" name="role" required>
                            <option value="Client">Client</option>
                            <option value="Admin">Admin</option>
                            <option value="Super Admin">Super Admin</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="d-flex gap-2 w-100">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-50" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="saveUserBtn" class="btn btn-success btn-sm w-50">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div class="modal fade" aria-labelledby="editUserModal" tabindex="-1" id="editUserModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="" method="post" class="modal-content compact-form" id="editUserForm">
                <div class="modal-header py-2">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="editUserId" name="userId">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="editFirstName" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="editFirstName" name="firstName" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="editLastName" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="editLastName" name="lastName" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="editCompanyName" class="form-label">Company Name (Optional)</label>
                        <input type="text" class="form-control" id="editCompanyName" name="companyName" placeholder="Company or organization name">
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editContactNumber" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="editContactNumber" name="contactNumber" placeholder="0939-494-4394" maxlength="13">
                        <small class="form-text phone-validation"></small>
                    </div>
                    <div class="mb-3">
                        <label for="editPassword" class="form-label">Password</label>
                        <div class="password-container">
                            <input type="password" class="form-control" id="editPassword" name="password" placeholder="Leave blank to keep current password" minlength="8">
                            <button type="button" class="password-toggle" id="toggleEditPassword">
                                <i class="bi bi-eye" id="toggleEditPasswordIcon"></i>
                            </button>
                        </div>
                        <small class="form-text text-muted">Password must be at least 8 characters</small>
                        <!-- Container for password requirements -->
                        <div id="editPasswordRequirements" class="password-requirements"></div>
                    </div>
                    <div class="mb-3">
                        <label for="editRole" class="form-label">Role</label>
                        <select class="form-select" id="editRole" name="role" required>
                            <option value="Client">Client</option>
                            <option value="Admin">Admin</option>
                            <option value="Super Admin">Super Admin</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer">
                    <div class="d-flex gap-2 w-100">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-50" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="updateUserBtn" class="btn btn-success btn-sm w-50">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
    <?php include_once __DIR__ . "/../assets/admin_sidebar.php"; ?>

    <div class="content collapsed" id="content">
        <div class="container-fluid py-4 px-4 px-xl-5">
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0">
                <h3>User Management</h3>
                <?php include_once __DIR__ . "/../assets/admin_profile.php"; ?>
            </div>
            <div class="d-flex gap-3 my-3 flex-wrap">
                <div class="input-group" style="max-width: 300px;">
                    <input type="text" class="form-control" id="searchUser" placeholder="Search by name, email or contact">
                    <button class="btn btn-outline-success" type="button" id="searchBtn">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
                <div class="input-group" style="max-width: 250px;">
                    <span class="input-group-text bg-success-subtle" id="basic-addon2">Records per page</span>
                    <select name="limit" id="limitSelect" class="form-select">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                </div>
                <button type="button" class="btn btn-success ms-auto" data-bs-toggle="modal" data-bs-target="#addUserModal">
                    <i class="bi bi-plus-circle"></i> Add New User
                </button>
            </div>
            <div class="table-responsive-xl">
                <table class="table table-hover text-secondary overflow-hidden border rounded px-4">
                    <thead>
                        <tr>
                            <th style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">ID</th>
                            <th style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Name</th>
                            <th style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Email</th>
                            <th style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Contact Number</th>
                            <th style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Role</th>
                            <th style="cursor: pointer; background-color: #d1f7c4; white-space: nowrap;">Created At</th>
                            <th style="text-align: center; width: 15%; background-color: #d1f7c4; white-space: nowrap;">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="usersTableBody">
                        <!-- Users data will be loaded here -->
                    </tbody>
                </table>
            </div>
            <div id="paginationContainer" class="mt-4"></div>
        </div>
    </div>
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="../../../public/js/utils/pagination.js"></script>
    <script src="../../../public/js/admin/user_management.js"></script>
    <script src="../../../public/js/assets/sidebar.js"></script>
    <script src="../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle password visibility
        document.addEventListener('DOMContentLoaded', function() {
            // Add toggle for password field in Add User form
            const toggleAddPasswordBtn = document.getElementById('toggleAddPassword');
            const toggleAddPasswordIcon = document.getElementById('toggleAddPasswordIcon');
            const passwordField = document.getElementById('password');
            
            toggleAddPasswordBtn.addEventListener('click', function() {
                if (passwordField.type === 'password') {
                    passwordField.type = 'text';
                    toggleAddPasswordIcon.classList.remove('bi-eye');
                    toggleAddPasswordIcon.classList.add('bi-eye-slash');
                } else {
                    passwordField.type = 'password';
                    toggleAddPasswordIcon.classList.remove('bi-eye-slash');
                    toggleAddPasswordIcon.classList.add('bi-eye');
                }
            });
            
            // Add toggle for password field in Edit User form
            const toggleEditPasswordBtn = document.getElementById('toggleEditPassword');
            const toggleEditPasswordIcon = document.getElementById('toggleEditPasswordIcon');
            const editPasswordField = document.getElementById('editPassword');
            
            toggleEditPasswordBtn.addEventListener('click', function() {
                if (editPasswordField.type === 'password') {
                    editPasswordField.type = 'text';
                    toggleEditPasswordIcon.classList.remove('bi-eye');
                    toggleEditPasswordIcon.classList.add('bi-eye-slash');
                } else {
                    editPasswordField.type = 'password';
                    toggleEditPasswordIcon.classList.remove('bi-eye-slash');
                    toggleEditPasswordIcon.classList.add('bi-eye');
                }
            });
        });
    </script>
</body>
</html> 