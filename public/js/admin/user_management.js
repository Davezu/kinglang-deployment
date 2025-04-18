const addUserModal = new bootstrap.Modal(document.getElementById("addUserModal"));
const editUserModal = new bootstrap.Modal(document.getElementById("editUserModal"));
const deleteUserModal = new bootstrap.Modal(document.getElementById("deleteUserModal"));
const messageModal = new bootstrap.Modal(document.getElementById("messageModal"));

const messageTitle = document.getElementById("messageTitle");
const messageBody = document.getElementById("messageBody");

document.addEventListener("DOMContentLoaded", async function () {
    const limit = document.getElementById("limitSelect").value;
    loadUsers(1, "", limit);
});

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
        messageTitle.textContent = "Error";
        messageBody.textContent = "Failed to load users. Please try again.";
        messageModal.show();
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
        
        nameCell.textContent = `${user.first_name} ${user.last_name}`;
        nameCell.style.maxWidth = "120px";
        nameCell.style.overflow = "hidden";
        nameCell.style.textOverflow = "ellipsis";
        nameCell.style.whiteSpace = "nowrap";
        nameCell.title = `${user.first_name} ${user.last_name}`;
        
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
        document.getElementById("deleteUserId").value = user.user_id;
        deleteUserModal.show();
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
    
    const ul = document.createElement("ul");
    ul.classList.add("pagination", "justify-content-center", "mt-4");
    
    // Previous button
    const prevLi = document.createElement("li");
    prevLi.classList.add("page-item");
    if (currentPage === 1) {
        prevLi.classList.add("disabled");
    }
    
    const prevLink = document.createElement("a");
    prevLink.classList.add("page-link");
    prevLink.href = "#";
    prevLink.textContent = "Previous";
    prevLink.addEventListener("click", function(e) {
        e.preventDefault();
        if (currentPage > 1) {
            const searchTerm = document.getElementById("searchUser").value;
            const limit = document.getElementById("limitSelect").value;
            loadUsers(currentPage - 1, searchTerm, limit);
        }
    });
    
    prevLi.appendChild(prevLink);
    ul.appendChild(prevLi);
    
    // Page numbers
    for (let i = 1; i <= totalPages; i++) {
        const li = document.createElement("li");
        li.classList.add("page-item");
        if (i === currentPage) {
            li.classList.add("active");
        }
        
        const link = document.createElement("a");
        link.classList.add("page-link");
        link.href = "#";
        link.textContent = i;
        link.addEventListener("click", function(e) {
            e.preventDefault();
            const searchTerm = document.getElementById("searchUser").value;
            const limit = document.getElementById("limitSelect").value;
            loadUsers(i, searchTerm, limit);
        });
        
        li.appendChild(link);
        ul.appendChild(li);
    }
    
    // Next button
    const nextLi = document.createElement("li");
    nextLi.classList.add("page-item");
    if (currentPage === totalPages) {
        nextLi.classList.add("disabled");
    }
    
    const nextLink = document.createElement("a");
    nextLink.classList.add("page-link");
    nextLink.href = "#";
    nextLink.textContent = "Next";
    nextLink.addEventListener("click", function(e) {
        e.preventDefault();
        if (currentPage < totalPages) {
            const searchTerm = document.getElementById("searchUser").value;
            const limit = document.getElementById("limitSelect").value;
            loadUsers(currentPage + 1, searchTerm, limit);
        }
    });
    
    nextLi.appendChild(nextLink);
    ul.appendChild(nextLi);
    
    paginationContainer.appendChild(ul);
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
            messageTitle.textContent = "Error";
            messageBody.textContent = user.error;
            messageModal.show();
            return;
        }
        
        document.getElementById("editUserId").value = user.user_id;
        document.getElementById("editFirstName").value = user.first_name;
        document.getElementById("editLastName").value = user.last_name;
        document.getElementById("editEmail").value = user.email;
        document.getElementById("editContactNumber").value = user.contact_number;
        document.getElementById("editPassword").value = ""; // Clear password field
        document.getElementById("editRole").value = user.role;
        
        editUserModal.show();
    } catch (error) {
        console.error("Error fetching user details:", error);
        messageTitle.textContent = "Error";
        messageBody.textContent = "Failed to load user details. Please try again.";
        messageModal.show();
    }
}

// Form submissions
document.getElementById("addUserForm").addEventListener("submit", async function(event) {
    event.preventDefault();
    
    const formData = new FormData(this);
    const formDataObject = {
        firstName: formData.get("firstName"),
        lastName: formData.get("lastName"),
        email: formData.get("email"),
        contactNumber: formData.get("contactNumber"),
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
            messageTitle.textContent = "Error";
            messageBody.textContent = data.error;
        } else {
            messageTitle.textContent = "Success";
            messageBody.textContent = "User created successfully";
            this.reset();
            
            const searchTerm = document.getElementById("searchUser").value;
            const limit = document.getElementById("limitSelect").value;
            loadUsers(1, searchTerm, limit);
        }
        
        messageModal.show();
    } catch (error) {
        console.error("Error adding user:", error);
        addUserModal.hide();
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        
        messageTitle.textContent = "Error";
        messageBody.textContent = "Failed to add user. Please try again.";
        messageModal.show();
    }
});

document.getElementById("editUserForm").addEventListener("submit", async function(event) {
    event.preventDefault();
    
    const formData = new FormData(this);
    const formDataObject = {
        userId: formData.get("userId"),
        firstName: formData.get("firstName"),
        lastName: formData.get("lastName"),
        email: formData.get("email"),
        contactNumber: formData.get("contactNumber"),
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
            messageTitle.textContent = "Error";
            messageBody.textContent = data.error;
        } else {
            messageTitle.textContent = "Success";
            messageBody.textContent = "User updated successfully";
            
            const searchTerm = document.getElementById("searchUser").value;
            const limit = document.getElementById("limitSelect").value;
            const currentPage = document.querySelector(".pagination .active") ? 
                parseInt(document.querySelector(".pagination .active").textContent) : 1;
            loadUsers(currentPage, searchTerm, limit);
        }
        
        messageModal.show();
    } catch (error) {
        console.error("Error updating user:", error);
        editUserModal.hide();
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        
        messageTitle.textContent = "Error";
        messageBody.textContent = "Failed to update user. Please try again.";
        messageModal.show();
    }
});

document.getElementById("deleteUserForm").addEventListener("submit", async function(event) {
    event.preventDefault();
    
    const userId = document.getElementById("deleteUserId").value;
    
    try {
        const response = await fetch("/admin/delete-user", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ userId })
        });
        
        deleteUserModal.hide();
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        
        const data = await response.json();
        
        if (data.error) {
            messageTitle.textContent = "Error";
            messageBody.textContent = data.error;
        } else {
            messageTitle.textContent = "Success";
            messageBody.textContent = "User deleted successfully";
            
            const searchTerm = document.getElementById("searchUser").value;
            const limit = document.getElementById("limitSelect").value;
            const currentPage = document.querySelector(".pagination .active") ? 
                parseInt(document.querySelector(".pagination .active").textContent) : 1;
            loadUsers(currentPage, searchTerm, limit);
        }
        
        messageModal.show();
    } catch (error) {
        console.error("Error deleting user:", error);
        deleteUserModal.hide();
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
        
        messageTitle.textContent = "Error";
        messageBody.textContent = "Failed to delete user. Please try again.";
        messageModal.show();
    }
}); 