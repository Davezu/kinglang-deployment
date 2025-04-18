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
    <title>User Management</title>
    
</head>
<body>
    <!-- Add User Modal -->
    <div class="modal fade" aria-labelledby="addUserModal" tabindex="-1" id="addUserModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="" method="post" class="modal-content" id="addUserForm">
                <div class="modal-header">
                    <h4 class="modal-title">Add New User</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" required>
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="contactNumber" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="contactNumber" name="contactNumber" placeholder="11-digit number">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required minlength="6">
                        <small class="form-text text-muted">Password must be at least 6 characters</small>
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
                    <div class="d-flex gap-3 w-50">
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
            <form action="" method="post" class="modal-content" id="editUserForm">
                <div class="modal-header">
                    <h4 class="modal-title">Edit User</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="editUserId" name="userId">
                    <div class="mb-3">
                        <label for="editFirstName" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="editFirstName" name="firstName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editLastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="editLastName" name="lastName" required>
                    </div>
                    <div class="mb-3">
                        <label for="editEmail" class="form-label">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="editContactNumber" class="form-label">Contact Number</label>
                        <input type="text" class="form-control" id="editContactNumber" name="contactNumber" placeholder="11-digit number">
                    </div>
                    <div class="mb-3">
                        <label for="editPassword" class="form-label">Password</label>
                        <input type="password" class="form-control" id="editPassword" name="password" placeholder="Leave blank to keep current password">
                        <small class="form-text text-muted">Password must be at least 6 characters</small>
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
                    <div class="d-flex gap-3 w-50">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-50" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="updateUserBtn" class="btn btn-success btn-sm w-50">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete User Modal -->
    <div class="modal fade" aria-labelledby="deleteUserModal" tabindex="-1" id="deleteUserModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="" method="post" class="modal-content" id="deleteUserForm">
                <div class="modal-header">
                    <h4 class="modal-title">Delete User?</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p>Are you sure you want to delete this user?</p>
                    <p class="text-secondary">Note: Users with existing bookings cannot be deleted.</p>
                </div>

                <div class="modal-footer">
                    <div class="d-flex gap-3 w-50">
                        <input type="hidden" name="userId" id="deleteUserId" value="">
                        <button type="button" class="btn btn-outline-secondary btn-sm w-50" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="confirmDeleteBtn" class="btn btn-danger btn-sm w-50">Delete</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade message-modal" aria-labelledby="messageModal" tabindex="-1" id="messageModal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="messageTitle"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p id="messageBody"></p>
                </div>

                <div class="modal-footer">
                    <div class="d-flex gap-3 w-25">
                        <button type="button" class="btn btn-outline-success btn-sm w-100" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
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
    <script src="../../../public/js/admin/user_management.js"></script>
    <script src="../../../public/js/assets/sidebar.js"></script>
    <script src="../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
</body>
</html> 