const addUserModal = new bootstrap.Modal(document.getElementById("addUserModal"));
const editUserModal = new bootstrap.Modal(document.getElementById("editUserModal"));

document.addEventListener("DOMContentLoaded", async function () {
    const limit = document.getElementById("limitSelect").value;
    loadUsers(1, "", limit);
    
    // Initialize phone number formatting and validation
    setupPhoneNumberValidation("contactNumber");
    setupPhoneNumberValidation("editContactNumber");
    
    // Initialize password validation
    setupPasswordValidation("password");
    setupPasswordValidation("editPassword", true); // optional for edit form
});

// Function to format phone numbers consistently
function formatPhoneNumber(value) {
    // Remove all non-digits from the value
    const digits = value.replace(/\D/g, '');
    
    // Format based on number of digits
    if (digits.length <= 4) {
        return digits;
    } else if (digits.length <= 7) {
        return `${digits.substring(0, 4)}-${digits.substring(4)}`;
    } else {
        return `${digits.substring(0, 4)}-${digits.substring(4, 7)}-${digits.substring(7, 11)}`;
    }
}

// Function to setup phone number validation and formatting
function setupPhoneNumberValidation(inputId) {
    const inputElement = document.getElementById(inputId);
    if (!inputElement) return;
    
    // Get the validation message element
    const validationMsg = inputElement.nextElementSibling;
    
    // Prevent non-numeric input
    inputElement.addEventListener('keypress', function(e) {
        // Allow only digits (0-9) and control keys
        if (!/^\d$/.test(e.key) && !isControlKey(e)) {
            e.preventDefault();
        }
    });
    
    // Clear validation message when starting to type
    inputElement.addEventListener('focus', function() {
        if (this.value === '') {
            validationMsg.textContent = '';
            validationMsg.className = 'form-text phone-validation';
        }
    });
    
    // Format input as user types
    inputElement.addEventListener('input', function() {
        // Store cursor position
        const cursorPos = this.selectionStart;
        const previousLength = this.value.length;
        
        // Format the value
        const formattedValue = formatPhoneNumber(this.value);
        this.value = formattedValue;
        
        // Restore cursor position accounting for formatting
        if (document.activeElement === this) {
            const newCursorPos = cursorPos + (this.value.length - previousLength);
            this.setSelectionRange(newCursorPos, newCursorPos);
        }
    });
    
    // Validate on blur
    inputElement.addEventListener('blur', function() {
        validatePhoneNumber(this);
    });
}

// Function to validate phone number format
function validatePhoneNumber(inputElement) {
    // Get the raw value and digits only version
    const rawValue = inputElement.value;
    const digitsOnly = rawValue.replace(/\D/g, '');
    
    // Get the validation message element (sibling with class phone-validation)
    const validationMsg = inputElement.nextElementSibling;
    
    // Debug logging for troubleshooting
    console.log('Phone validation details:', {
        inputId: inputElement.id,
        rawValue: rawValue,
        digitsOnly: digitsOnly,
        length: digitsOnly.length,
        startsWith09: digitsOnly.substring(0, 2) === '09',
        validationMsgFound: !!validationMsg,
    });
    
    // If empty, consider valid (since phone can be optional)
    if (digitsOnly.length === 0) {
        if (validationMsg) {
            validationMsg.textContent = '';
            validationMsg.className = 'form-text phone-validation';
        }
        return true;
    }
    
    // Check the format - SIMPLIFIED VERSION
    let isValid = true;
    let errorMessage = '';
    
    // Check if it starts with 09
    if (digitsOnly.substring(0, 2) !== '09') {
        isValid = false;
        errorMessage = 'Phone number must start with 09';
    }
    // Check if it has 11 digits
    else if (digitsOnly.length !== 11) {
        isValid = false;
        errorMessage = `Phone number must be 11 digits (currently: ${digitsOnly.length})`;
    }
    
    // Update the validation message
    if (validationMsg) {
        if (isValid) {
            validationMsg.textContent = 'Valid phone number';
            validationMsg.className = 'form-text phone-validation text-success';
        } else {
            validationMsg.textContent = errorMessage;
            validationMsg.className = 'form-text phone-validation text-danger';
        }
    }
    
    console.log('Final validation result:', isValid);
    return isValid;
}

// Helper function to check if a key is a control key
function isControlKey(e) {
    const controlKeys = [
        'Backspace', 'Tab', 'Enter', 'Shift', 'Control', 'Alt', 
        'Escape', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown',
        'Delete', 'Home', 'End'
    ];
    return controlKeys.includes(e.key);
}

// Function to setup password validation
function setupPasswordValidation(inputId, isOptional = false) {
    const passwordInput = document.getElementById(inputId);
    if (!passwordInput) return;
    
    // Get existing requirements container
    const requirementsContainer = document.getElementById(`${inputId}Requirements`);
    if (!requirementsContainer) return;
    
    // Create requirements elements
    const requirements = [
        { id: `${inputId}-length`, text: '8+ chars', regex: /.{8,}/ },
        { id: `${inputId}-uppercase`, text: '1 uppercase', regex: /[A-Z]/ },
        { id: `${inputId}-lowercase`, text: '1 lowercase', regex: /[a-z]/ },
        { id: `${inputId}-number`, text: '1 number', regex: /[0-9]/ },
        { id: `${inputId}-special`, text: '1 special', regex: /[!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]/ }
    ];
    
    // Clear any existing requirements
    requirementsContainer.innerHTML = '';
    
    requirements.forEach(req => {
        const reqElement = document.createElement('small');
        reqElement.id = req.id;
        reqElement.className = 'requirement d-inline-flex align-items-center me-2';
        reqElement.innerHTML = `<i class="bi bi-x-circle text-danger"></i> ${req.text}`;
        reqElement.dataset.regex = req.regex.toString().slice(1, -1);
        requirementsContainer.appendChild(reqElement);
    });
    
    // Show the requirements container for new users
    // For edit form, only show when user starts typing a new password
    requirementsContainer.style.display = (isOptional && !passwordInput.value) ? 'none' : 'flex';
    
    // Initial validation if there's a value (like for prefilled fields)
    if (passwordInput.value) {
        validatePassword(passwordInput, requirementsContainer, isOptional);
    }
    
    passwordInput.addEventListener('input', function() {
        // For optional fields, only show requirements if there's input
        if (isOptional) {
            requirementsContainer.style.display = this.value ? 'flex' : 'none';
            if (!this.value) return; // Don't validate empty optional passwords
        }
        
        validatePassword(this, requirementsContainer, isOptional);
    });
}

// Separate function to validate password against requirements
function validatePassword(passwordInput, requirementsContainer, isOptional) {
    const value = passwordInput.value;
    
    // Validate against each requirement
    const requirements = requirementsContainer.querySelectorAll('.requirement');
    let allValid = true;
    
    requirements.forEach(req => {
        const regex = new RegExp(req.dataset.regex);
        const isValid = regex.test(value);
        
        const icon = req.querySelector('i');
        if (isValid) {
            icon.classList.remove('bi-x-circle', 'text-danger');
            icon.classList.add('bi-check-circle', 'text-success');
        } else {
            icon.classList.remove('bi-check-circle', 'text-success');
            icon.classList.add('bi-x-circle', 'text-danger');
            allValid = false;
        }
    });
    
    // Update the existing small help text if available
    const helpText = passwordInput.closest('.mb-3').querySelector('.form-text:not(.requirement)');
    if (helpText && !isOptional) {
        if (allValid) {
            helpText.textContent = 'Password meets all requirements';
            helpText.className = 'form-text text-success';
        } else {
            helpText.textContent = 'Password must meet all requirements';
            helpText.className = 'form-text text-danger';
        }
    }
    
    // Return validity status
    return allValid;
}

// Validate form before submission
function validateForm(formElement) {
    let isValid = true;
    
    // Validate phone number
    const phoneInput = formElement.querySelector('input[name="contactNumber"]');
    if (phoneInput && phoneInput.value.trim() !== '') {
        if (!validatePhoneNumber(phoneInput)) {
            isValid = false;
        }
    }
    
    // Validate password
    const passwordInput = formElement.querySelector('input[name="password"]');
    if (passwordInput && passwordInput.value.trim() !== '') {
        const requirementsContainer = document.getElementById(
            passwordInput.id === 'password' ? 'passwordRequirements' : 'editPasswordRequirements'
        );
        
        if (requirementsContainer && !validatePassword(passwordInput, requirementsContainer, passwordInput.id === 'editPassword')) {
            isValid = false;
        }
    }
    
    return isValid;
}

document.getElementById("limitSelect").addEventListener("change", function() {
    const limit = this.value;
    const searchTerm = document.getElementById("searchUser").value;
    loadUsers(1, searchTerm, limit);
});

document.getElementById("searchBtn").addEventListener("click", function() {
    const searchTerm = document.getElementById("searchUser").value;
    const limit = document.getElementById("limitSelect").value;
    loadUsers(1, searchTerm, limit);
});

document.getElementById("searchUser").addEventListener("keypress", function(e) {
    if (e.key === "Enter") {
        const searchTerm = this.value;
        const limit = document.getElementById("limitSelect").value;
        loadUsers(1, searchTerm, limit);
    }
});

function formatDate(date) {
    return new Date(date).toLocaleDateString("en-US", {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

async function loadUsers(page, searchTerm = "", limit = 10) {
    try {
        const response = await fetch("/admin/get-users", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ page, limit, search: searchTerm })
        });
        
        const data = await response.json();
        
        if (data.users) {
            displayUsers(data.users);
            displayPagination(data.totalPages, data.currentPage);
            return data;
        }
    } catch (error) {
        console.error("Error fetching users:", error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to load users. Please try again.',
            timer: 2000,
            timerProgressBar: true
        });
    }
}

function displayUsers(users) {
    const tableBody = document.getElementById("usersTableBody");
    tableBody.innerHTML = "";

    if (users.length === 0) {
        const row = document.createElement("tr");
        const cell = document.createElement("td");
        cell.setAttribute("colspan", "7");
        cell.textContent = "No users found";
        cell.classList.add("text-center");
        row.appendChild(cell);
        tableBody.appendChild(row);
        return;
    }

    users.forEach(user => {
        const row = document.createElement("tr");
        
        const idCell = document.createElement("td");
        const nameCell = document.createElement("td");
        const emailCell = document.createElement("td");
        const contactNumberCell = document.createElement("td");
        const roleCell = document.createElement("td");
        const createdAtCell = document.createElement("td");
        const actionsCell = document.createElement("td");
        
        idCell.textContent = user.user_id;
        
        // Add company name if available
        const nameContent = document.createElement("div");
        nameContent.textContent = `${user.first_name} ${user.last_name}`;
        nameCell.appendChild(nameContent);
        
        if (user.company_name) {
            const companyInfo = document.createElement("small");
            companyInfo.classList.add("text-muted", "d-block");
            companyInfo.textContent = user.company_name;
            nameCell.appendChild(companyInfo);
        }
        
        nameCell.style.maxWidth = "150px";
        nameCell.style.overflow = "hidden";
        nameCell.style.textOverflow = "ellipsis";
        nameCell.title = user.company_name ? 
            `${user.first_name} ${user.last_name} (${user.company_name})` : 
            `${user.first_name} ${user.last_name}`;
        
        emailCell.textContent = user.email;
        emailCell.style.maxWidth = "150px";
        emailCell.style.overflow = "hidden";
        emailCell.style.textOverflow = "ellipsis";
        emailCell.style.whiteSpace = "nowrap";
        emailCell.title = user.email;
        
        contactNumberCell.textContent = user.contact_number || "-";
        
        roleCell.textContent = user.role;
        if (user.role === "Super Admin") {
            roleCell.classList.add("text-danger", "fw-bold");
        } else if (user.role === "Admin") {
            roleCell.classList.add("text-warning", "fw-bold");
        } else {
            roleCell.classList.add("text-info", "fw-bold");
        }
        
        createdAtCell.textContent = formatDate(user.created_at);
        
        actionsCell.appendChild(createActionButtons(user));
        
        row.append(idCell, nameCell, emailCell, contactNumberCell, roleCell, createdAtCell, actionsCell);
        tableBody.appendChild(row);
    });
}

function createActionButtons(user) {
    const buttonGroup = document.createElement("div");
    buttonGroup.classList.add("d-flex", "gap-2", "align-items-center", "justify-content-center");
    
    const editButton = document.createElement("button");
    editButton.classList.add("btn", "bg-primary-subtle", "text-primary", "btn-sm", "fw-bold", "edit-user");
    editButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1rem; --bs-btn-font-size: .75rem;");
    editButton.setAttribute("data-id", user.user_id);
    
    const editIcon = document.createElement("i");
    editIcon.classList.add("bi", "bi-pencil-square");
    const editText = document.createTextNode(" Edit");
    editButton.appendChild(editIcon);
    editButton.appendChild(editText);
    
    const deleteButton = document.createElement("button");
    deleteButton.classList.add("btn", "bg-danger-subtle", "text-danger", "btn-sm", "fw-bold", "delete-user");
    deleteButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1rem; --bs-btn-font-size: .75rem;");
    deleteButton.setAttribute("data-id", user.user_id);
    
    const deleteIcon = document.createElement("i");
    deleteIcon.classList.add("bi", "bi-trash");
    const deleteText = document.createTextNode(" Delete");
    deleteButton.appendChild(deleteIcon);
    deleteButton.appendChild(deleteText);
    
    editButton.addEventListener("click", function() {
        getUserDetails(user.user_id);
    });
    
    deleteButton.addEventListener("click", function() {
        const userId = user.user_id;
        
        Swal.fire({
            title: 'Delete User?',
            html: '<p>Are you sure you want to delete this user?</p><p class="text-secondary">Note: Users with existing bookings cannot be deleted.</p>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            focusCancel: true
        }).then((result) => {
            if (result.isConfirmed) {
                deleteUser(userId);
            }
        });
    });
    
    buttonGroup.appendChild(editButton);
    buttonGroup.appendChild(deleteButton);
    
    return buttonGroup;
}

function displayPagination(totalPages, currentPage) {
    const paginationContainer = document.getElementById("paginationContainer");
    paginationContainer.innerHTML = "";
    
    if (totalPages <= 1) {
        return;
    }
    
    // Use the centralized pagination utility
    createPagination({
        containerId: "paginationContainer",
        totalPages: totalPages,
        currentPage: currentPage,
        paginationType: 'standard',
        onPageChange: (page) => {
            const searchTerm = document.getElementById("searchUser").value;
            const limit = document.getElementById("limitSelect").value;
            loadUsers(page, searchTerm, limit);
        }
    });
}

async function getUserDetails(userId) {
    try {
        const response = await fetch("/admin/get-user-details", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ userId })
        });
        
        const user = await response.json();
        
        if (user.error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: user.error,
                timer: 2000,
                timerProgressBar: true
            });
            return;
        }
        
        document.getElementById("editUserId").value = user.user_id;
        document.getElementById("editFirstName").value = user.first_name;
        document.getElementById("editLastName").value = user.last_name;
        document.getElementById("editCompanyName").value = user.company_name || '';
        document.getElementById("editEmail").value = user.email;
        document.getElementById("editContactNumber").value = user.contact_number;
        document.getElementById("editPassword").value = ""; // Clear password field
        document.getElementById("editRole").value = user.role;
        
        editUserModal.show();
    } catch (error) {
        console.error("Error fetching user details:", error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to load user details. Please try again.',
            timer: 2000,
            timerProgressBar: true
        });
    }
}

// Form submissions
document.getElementById("addUserForm").addEventListener("submit", async function(event) {
    event.preventDefault();
    console.log('Form submit triggered');
    
    // Direct validation of the phone number - no complex logic
    const phoneInput = this.querySelector('input[name="contactNumber"]');
    let phoneValid = true;
    
    if (phoneInput && phoneInput.value.trim() !== '') {
        // Extract digits only
        const digitsOnly = phoneInput.value.replace(/\D/g, '');
        console.log('Phone submit check:', {
            value: phoneInput.value,
            digitsOnly: digitsOnly,
            length: digitsOnly.length,
            startsWith09: digitsOnly.substring(0, 2) === '09'
        });
        
        // Simple validation - must be 11 digits starting with 09
        if (digitsOnly.length !== 11 || digitsOnly.substring(0, 2) !== '09') {
            phoneValid = false;
            console.log('Phone validation failed');
            
            // Update validation message
            const validationMsg = phoneInput.nextElementSibling;
            if (validationMsg) {
                if (digitsOnly.substring(0, 2) !== '09') {
                    validationMsg.textContent = 'Phone number must start with 09';
                } else {
                    validationMsg.textContent = `Phone number must be 11 digits (currently: ${digitsOnly.length})`;
                }
                validationMsg.className = 'form-text phone-validation text-danger';
            }
        }
    }
    
    // Password validation is handled separately
    
    // Stop if validation failed
    if (!phoneValid) {
        console.log('Form validation failed - phone');
        Swal.fire({
            icon: 'error',
            title: 'Phone Number Error',
            text: 'Please enter a valid phone number in the format 09XX XXX XXXX.',
        });
        return;
    }
    
    // Continue with form submission if validation passes
    const formData = new FormData(this);
    
    // Create object with form data, cleaning up the phone number
    const formDataObject = {
        firstName: formData.get("firstName"),
        lastName: formData.get("lastName"),
        companyName: formData.get("companyName"),
        email: formData.get("email"),
        contactNumber: formatPhoneNumberForDB(formData.get("contactNumber")),
        password: formData.get("password"),
        role: formData.get("role")
    };
    
    try {
        const response = await fetch("/admin/add-user", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formDataObject)
        });
        
        addUserModal.hide();
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        
        const data = await response.json();
        
        if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.error,
                timer: 2000,
                timerProgressBar: true
            });
        } else {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'User created successfully',
                timer: 2000,
                timerProgressBar: true
            });
            this.reset();
            
            const searchTerm = document.getElementById("searchUser").value;
            const limit = document.getElementById("limitSelect").value;
            loadUsers(1, searchTerm, limit);
        }
    } catch (error) {
        console.error("Error adding user:", error);
        addUserModal.hide();
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to add user. Please try again.',
            timer: 2000,
            timerProgressBar: true
        });
    }
});

document.getElementById("editUserForm").addEventListener("submit", async function(event) {
    event.preventDefault();
    console.log('Edit form submit triggered');
    
    // Direct validation of the phone number - no complex logic
    const phoneInput = this.querySelector('input[name="contactNumber"]');
    let phoneValid = true;
    
    if (phoneInput && phoneInput.value.trim() !== '') {
        // Extract digits only
        const digitsOnly = phoneInput.value.replace(/\D/g, '');
        console.log('Edit phone submit check:', {
            value: phoneInput.value,
            digitsOnly: digitsOnly,
            length: digitsOnly.length,
            startsWith09: digitsOnly.substring(0, 2) === '09'
        });
        
        // Simple validation - must be 11 digits starting with 09
        if (digitsOnly.length !== 11 || digitsOnly.substring(0, 2) !== '09') {
            phoneValid = false;
            console.log('Edit phone validation failed');
            
            // Update validation message
            const validationMsg = phoneInput.nextElementSibling;
            if (validationMsg) {
                if (digitsOnly.substring(0, 2) !== '09') {
                    validationMsg.textContent = 'Phone number must start with 09';
                } else {
                    validationMsg.textContent = `Phone number must be 11 digits (currently: ${digitsOnly.length})`;
                }
                validationMsg.className = 'form-text phone-validation text-danger';
            }
        }
    }
    
    // Password validation is handled separately
    
    // Stop if validation failed
    if (!phoneValid) {
        console.log('Edit form validation failed - phone');
        Swal.fire({
            icon: 'error',
            title: 'Phone Number Error',
            text: 'Please enter a valid phone number in the format 09XX XXX XXXX.',
        });
        return;
    }
    
    // Continue with form submission if validation passes
    const formData = new FormData(this);
    
    // Create object with form data, cleaning up the phone number
    const formDataObject = {
        userId: formData.get("userId"),
        firstName: formData.get("firstName"),
        lastName: formData.get("lastName"),
        companyName: formData.get("companyName"),
        email: formData.get("email"),
        contactNumber: formatPhoneNumberForDB(formData.get("contactNumber")),
        password: formData.get("password"),
        role: formData.get("role")
    };
    
    try {
        const response = await fetch("/admin/update-user", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formDataObject)
        });
        
        editUserModal.hide();
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        
        const data = await response.json();
        
        if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.error,
                timer: 2000,
                timerProgressBar: true
            });
        } else {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'User updated successfully',
                timer: 2000,
                timerProgressBar: true
            });
            
            const searchTerm = document.getElementById("searchUser").value;
            const limit = document.getElementById("limitSelect").value;
            const currentPage = document.querySelector(".pagination .active") ? 
                parseInt(document.querySelector(".pagination .active").textContent) : 1;
            loadUsers(currentPage, searchTerm, limit);
        }
    } catch (error) {
        console.error("Error updating user:", error);
        editUserModal.hide();
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to update user. Please try again.',
            timer: 2000,
            timerProgressBar: true
        });
    }
});

// New function to handle user deletion
async function deleteUser(userId) {
    try {
        const response = await fetch("/admin/delete-user", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ userId })
        });
        
        const data = await response.json();
        
        if (data.error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.error,
                timer: 2000,
                timerProgressBar: true
            });
        } else {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: 'User deleted successfully',
                timer: 2000,
                timerProgressBar: true
            });
            
            const searchTerm = document.getElementById("searchUser").value;
            const limit = document.getElementById("limitSelect").value;
            const currentPage = document.querySelector(".pagination .active") ? 
                parseInt(document.querySelector(".pagination .active").textContent) : 1;
            loadUsers(currentPage, searchTerm, limit);
        }
    } catch (error) {
        console.error("Error deleting user:", error);
        
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Failed to delete user. Please try again.',
            timer: 2000,
            timerProgressBar: true
        });
    }
}

// Helper function to format phone number for database storage
function formatPhoneNumberForDB(value) {
    if (!value || value.trim() === '') return '';
    
    // Remove all non-digits from the value
    const digits = value.replace(/\D/g, '');
    
    // If it doesn't have 11 digits or doesn't start with 09, return as is
    if (digits.length !== 11 || digits.substring(0, 2) !== '09') {
        return digits;
    }
    
    // Format as 09XX-XXX-XXXX
    return `${digits.substring(0, 4)}-${digits.substring(4, 7)}-${digits.substring(7, 11)}`;
} 