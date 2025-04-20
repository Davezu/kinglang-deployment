const messageModal = new bootstrap.Modal(document.getElementById("messageModal"));

const messageTitle = document.getElementById("messageTitle");
const messageBody = document.getElementById("messageBody");

document.addEventListener("DOMContentLoaded", async function () {
    const limit = document.getElementById("limitSelect").value;
    
    // Check the counts for Pending status
    const pendingBookings = await getAllBookings("Pending", "asc", "booking_id", 1, limit);
    
    // First check if there are any pending records
    if (pendingBookings && pendingBookings.pagination && pendingBookings.pagination.total > 0) {
        // If there are pending records, keep default as Pending
        document.getElementById("statusSelect").value = "Pending";
        renderBookings(pendingBookings);
        renderPagination(pendingBookings.pagination);
    } else {
        // If no pending records, check for confirmed records
        const confirmedBookings = await getAllBookings("Confirmed", "asc", "booking_id", 1, limit);
        
        if (confirmedBookings && confirmedBookings.pagination && confirmedBookings.pagination.total > 0) {
            // If there are confirmed records, set default to Confirmed
            document.getElementById("statusSelect").value = "Confirmed";
            renderBookings(confirmedBookings);
            renderPagination(confirmedBookings.pagination);
        } else {
            // If no pending and no confirmed records, set to All
            document.getElementById("statusSelect").value = "All";
            const allBookings = await getAllBookings("All", "asc", "booking_id", 1, limit);
            renderBookings(allBookings);
            renderPagination(allBookings.pagination);
        }
    }
});

document.getElementById("statusSelect").addEventListener("change", async function () {
    const status = this.value;  
    const limit = document.getElementById("limitSelect").value;
    console.log(status);    
    const bookings = await getAllBookings(status, "asc", "client_name", 1, limit);
    renderBookings(bookings);
    renderPagination(bookings.pagination);
});

document.getElementById("limitSelect").addEventListener("change", async function () {
    const status = document.getElementById("statusSelect").value;
    const limit = this.value;
    const column = document.querySelector(".sort.active") ? 
        document.querySelector(".sort.active").getAttribute("data-column") : "client_name";
    const order = document.querySelector(".sort.active") ? 
        document.querySelector(".sort.active").getAttribute("data-order") : "asc";
    
    const bookings = await getAllBookings(status, order, column, 1, limit);
    renderBookings(bookings);
    renderPagination(bookings.pagination);
});

document.querySelectorAll(".sort").forEach(button => {
    button.style.cursor = "pointer";
    button.style.backgroundColor = "#d1f7c4";

    button.addEventListener("click", async function () {
        const status = document.getElementById("statusSelect").value;
        const column = this.getAttribute("data-column");
        const order = this.getAttribute("data-order");
        const limit = document.getElementById("limitSelect").value;
        const currentPage = document.querySelector(".pagination .active") ? 
            parseInt(document.querySelector(".pagination .active").textContent) : 1;

        const bookings = await getAllBookings(status, order, column, currentPage, limit);
        console.log(bookings);
        renderBookings(bookings);
        renderPagination(bookings.pagination);
        
        this.setAttribute("data-order", order === "asc" ? "desc" : "asc");
    });
});

function formatDate(date) {
    return new Date(date).toLocaleDateString("en-US", {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

async function getAllBookings(status, order, column, page = 1, limit = 10) {
    try {
        const response = await fetch("/admin/bookings", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ status, order, column, page, limit })
        });

        const data = await response.json();
        console.log("API Response:", data);
        console.log("Pagination details - Total:", data.pagination.total, "TotalPages:", data.pagination.totalPages, "Limit:", data.pagination.limit);

        if (data.success) {
            return data;
        }
    } catch (error) {
        console.error(error);
    }
}

async function renderBookings(data) {
    const bookings = data.bookings;
    const tbody = document.getElementById("tableBody");
    tbody.innerHTML = "";

    if (!bookings || bookings.length === 0) {
        const row = document.createElement("tr");
        const cell = document.createElement("td");
        cell.colSpan = 9; // Match the number of columns in the table
        cell.textContent = "No records found";
        cell.className = "text-center";
        row.appendChild(cell);
        tbody.appendChild(row);

        return;
    }

    bookings.forEach(booking => {
        const row = document.createElement("tr");

        const clientNameCell = document.createElement("td");
        const contactNumberCell = document.createElement("td");
        const destinationCell = document.createElement("td");
        const pickupPointCell = document.createElement("td");
        const dateOfTourCell = document.createElement("td");
        const endOfTourCell = document.createElement("td");
        const numberOfDaysCell = document.createElement("td");
        const numberOfBusesCell = document.createElement("td");
        const statusCell = document.createElement("td");
        const paymentStatusCell = document.createElement("td");

       
        clientNameCell.textContent = booking.client_name;
        clientNameCell.style.maxWidth = "120px";
        clientNameCell.style.overflow = "hidden";
        clientNameCell.style.textOverflow = "ellipsis";
        clientNameCell.style.whiteSpace = "nowrap";
        clientNameCell.title = booking.client_name;
        
        contactNumberCell.style.maxWidth = "80px";
        contactNumberCell.style.overflow = "hidden";
        contactNumberCell.style.textOverflow = "ellipsis";
        contactNumberCell.style.whiteSpace = "nowrap";
        contactNumberCell.textContent = booking.contact_number;
        
        destinationCell.textContent = booking.destination;
        destinationCell.style.maxWidth = "150px";
        destinationCell.style.overflow = "hidden";
        destinationCell.style.textOverflow = "ellipsis";
        destinationCell.style.whiteSpace = "nowrap";
        destinationCell.title = booking.destination;
        
        pickupPointCell.textContent = booking.total_cost;
        
        dateOfTourCell.textContent = formatDate(booking.date_of_tour);
        dateOfTourCell.style.maxWidth = "120px";
        dateOfTourCell.style.overflow = "hidden";
        dateOfTourCell.style.textOverflow = "ellipsis";
        dateOfTourCell.style.whiteSpace = "nowrap";
        dateOfTourCell.title = formatDate(booking.date_of_tour);
        
        numberOfDaysCell.textContent = booking.number_of_days;
        numberOfBusesCell.textContent = booking.number_of_buses;
        
        // Apply status styling
        statusCell.textContent = booking.status;
        statusCell.className = `text-${getStatusTextClass(booking.status)}`;
        statusCell.style.width = "100px";
        statusCell.style.fontWeight = "bold";
        
        paymentStatusCell.textContent = booking.payment_status;
        paymentStatusCell.className = `text-${getPaymentStatusTextClass(booking.payment_status)}`;
        paymentStatusCell.style.width = "120px";
        paymentStatusCell.style.fontWeight = "bold";
        paymentStatusCell.style.whiteSpace = "nowrap";
        paymentStatusCell.style.overflow = "hidden";
        paymentStatusCell.style.textOverflow = "ellipsis";
        paymentStatusCell.title = booking.payment_status;

        row.append(clientNameCell, contactNumberCell, destinationCell, pickupPointCell, dateOfTourCell, numberOfDaysCell, numberOfBusesCell, paymentStatusCell, actionButton(booking));
        tbody.appendChild(row);
    });
}

function actionButton(booking) {
    const actionCell = document.createElement("td");
    const buttonGroup = document.createElement("div");
    const confirmButton = document.createElement("button");
    const rejectButton = document.createElement("button");
    const cancelButton = document.createElement("button");
    const viewButton = document.createElement("button");

    buttonGroup.classList.add("d-flex", "gap-2", "align-items-center");

    confirmButton.classList.add("btn", "bg-success-subtle", "text-success", "btn-sm", "fw-bold", "w-100", "d-flex", "align-items-center", "justify-content-center", "gap-1");
    confirmButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;"); 

    rejectButton.classList.add("btn", "bg-danger-subtle", "text-danger", "btn-sm", "fw-bold", "w-100", "d-flex", "align-items-center", "justify-content-center", "gap-1");
    rejectButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");

    cancelButton.classList.add("btn", "bg-danger-subtle", "text-danger", "btn-sm", "fw-bold", "w-100", "d-flex", "align-items-center", "justify-content-center", "gap-1");
    cancelButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");

    viewButton.classList.add("btn", "bg-primary-subtle", "text-primary", "btn-sm", "fw-bold", "w-100", "d-flex", "align-items-center", "justify-content-center", "gap-1");
    viewButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");

    // Add icons and text
    const confirmIcon = document.createElement("i");
    confirmIcon.classList.add("bi", "bi-check-circle");
    const confirmText = document.createElement("span");
    confirmText.textContent = "Confirm";
    confirmButton.appendChild(confirmIcon);
    confirmButton.appendChild(confirmText);

    const rejectIcon = document.createElement("i");
    rejectIcon.classList.add("bi", "bi-x-circle");
    const rejectText = document.createElement("span");
    rejectText.textContent = "Reject";
    rejectButton.appendChild(rejectIcon);
    rejectButton.appendChild(rejectText);

    const cancelIcon = document.createElement("i");
    cancelIcon.classList.add("bi", "bi-x-circle");
    const cancelText = document.createElement("span");
    cancelText.textContent = "Cancel";
    cancelButton.appendChild(cancelIcon);
    cancelButton.appendChild(cancelText);

    const viewIcon = document.createElement("i");
    viewIcon.classList.add("bi", "bi-eye");
    const viewText = document.createElement("span");
    viewText.textContent = "View";
    viewButton.appendChild(viewIcon);
    viewButton.appendChild(viewText);

    // data attributes
    confirmButton.setAttribute("data-booking-id", booking.booking_id);

    rejectButton.setAttribute("data-booking-id", booking.booking_id);
    rejectButton.setAttribute("data-user-id", booking.user_id);

    cancelButton.setAttribute("data-booking-id", booking.booking_id);
    cancelButton.setAttribute("data-user-id", booking.user_id);

    // Replace modal with SweetAlert for confirm booking
    confirmButton.addEventListener("click", function () {
        const bookingId = this.getAttribute("data-booking-id");
        
        Swal.fire({
            title: 'Confirm Booking?',
            html: '<p>Are you sure you want to confirm this booking request?</p>',
            footer: '<p class="text-secondary mb-0">Note: This action cannot be undone.</p>',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            focusConfirm: false
        }).then((result) => {
            if (result.isConfirmed) {
                confirmBooking(bookingId);
            }
        });
    });

    viewButton.addEventListener("click", function () {
        localStorage.setItem("bookingId", booking.booking_id);
        window.location.href = "/admin/booking-request";
    });

    // Replace modal with SweetAlert for reject booking
    rejectButton.addEventListener("click", function () {
        const bookingId = this.getAttribute("data-booking-id");
        const userId = this.getAttribute("data-user-id");
        
        Swal.fire({
            title: 'Reject Booking?',
            html: '<p>Are you sure you want to reject this booking request?</p>',
            input: 'textarea',
            inputPlaceholder: 'Kindly provide the reason here.',
            inputAttributes: {
                'aria-label': 'Rejection reason'
            },
            footer: '<p class="text-secondary mb-0">Note: This action cannot be undone.</p>',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Reject',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            showCloseButton: true,
            focusConfirm: false,
            allowOutsideClick: false,
            width: '32em',
            padding: '1em',
            didOpen: () => {
                // Fix textarea styling
                const textarea = Swal.getInput();
                textarea.style.height = '120px';
                textarea.style.marginTop = '10px';
                textarea.style.marginBottom = '10px';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const reason = result.value;
                rejectBooking(bookingId, userId, reason);
            }
        });
    });

    // Replace modal with SweetAlert for cancel booking
    cancelButton.addEventListener("click", function () {
        const bookingId = this.getAttribute("data-booking-id");
        const userId = this.getAttribute("data-user-id");
        
        Swal.fire({
            title: 'Cancel Booking?',
            html: '<p>Are you sure you want to cancel this booking?</p>',
            input: 'textarea',
            inputPlaceholder: 'Kindly provide the reason here.',
            inputAttributes: {
                'aria-label': 'Cancellation reason'
            },
            footer: '<p class="text-secondary mb-0">Note: This action cannot be undone.</p>',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Confirm',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            showCloseButton: true,
            focusConfirm: false,
            allowOutsideClick: false,
            width: '32em',
            padding: '1em',
            didOpen: () => {
                // Fix textarea styling
                const textarea = Swal.getInput();
                textarea.style.height = '120px';
                textarea.style.marginTop = '10px';
                textarea.style.marginBottom = '10px';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const reason = result.value;
                cancelBooking(bookingId, userId, reason);
            }
        });
    });

    if (booking.status === "Pending") {
        buttonGroup.append(confirmButton, rejectButton, viewButton);
    } else if (booking.status === "Confirmed") {
        buttonGroup.append(cancelButton, viewButton);
    } else {
        buttonGroup.append(viewButton);
    }
    actionCell.appendChild(buttonGroup);

    return actionCell;
}

function renderPagination(pagination) {
    if (!pagination) {
        console.log("No pagination data provided");
        return;
    }
    
    // Make sure totalPages and currentPage are treated as numbers
    const totalPages = parseInt(pagination.totalPages, 10);
    const currentPage = parseInt(pagination.currentPage, 10);
    
    if (totalPages <= 1) {
        // Clear the pagination container if no pagination is needed
        const paginationContainer = document.getElementById("paginationContainer");
        if (paginationContainer) {
            paginationContainer.innerHTML = "";
        }
        return;
    }
    
    // Use the centralized pagination utility
    createPagination({
        containerId: "paginationContainer",
        totalPages: totalPages,
        currentPage: currentPage,
        paginationType: 'advanced',
        onPageChange: async (page) => {
            const status = document.getElementById("statusSelect").value;
            const column = document.querySelector(".sort.active") ? 
                document.querySelector(".sort.active").getAttribute("data-column") : "client_name";
            const order = document.querySelector(".sort.active") ? 
                document.querySelector(".sort.active").getAttribute("data-order") : "asc";
            const limit = document.getElementById("limitSelect").value;
            
            const bookings = await getAllBookings(status, order, column, page, limit);
            renderBookings(bookings);
            renderPagination(bookings.pagination);
        }
    });
}

// New function to handle confirm booking API call
async function confirmBooking(bookingId) {
    try {
        const response = await fetch("/admin/confirm-booking", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ bookingId })
        });
    
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message || 'Booking confirmed successfully.',
                timer: 2000,
                timerProgressBar: true
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to confirm booking.',
                timer: 2000,
                timerProgressBar: true
            });
        }
        
        const status = document.getElementById("statusSelect").value;
        const limit = document.getElementById("limitSelect").value;
        const bookings = await getAllBookings(status, "asc", "booking_id", 1, limit);
        renderBookings(bookings);
        renderPagination(bookings.pagination);
    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred. Please try again.',
            timer: 2000,
            timerProgressBar: true
        });
    }
}

// New function to handle reject booking API call
async function rejectBooking(bookingId, userId, reason) {
    try {
        const response = await fetch("/admin/reject-booking", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ bookingId, reason, userId })
        });
    
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message || 'Booking rejected successfully.',
                timer: 2000,
                timerProgressBar: true
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to reject booking.',
                timer: 2000,
                timerProgressBar: true
            });
        }
        
        const status = document.getElementById("statusSelect").value;
        const limit = document.getElementById("limitSelect").value;
        const bookings = await getAllBookings(status, "asc", "booking_id", 1, limit);
        renderBookings(bookings);
        renderPagination(bookings.pagination);
    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred. Please try again.',
            timer: 2000,
            timerProgressBar: true
        });
    }
}

// New function to handle cancel booking API call
async function cancelBooking(bookingId, userId, reason) {
    try {
        const response = await fetch("/admin/cancel-booking", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ bookingId, userId, reason })
        });
    
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message || 'Booking canceled successfully.',
                timer: 2000,
                timerProgressBar: true
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to cancel booking.',
                timer: 2000,
                timerProgressBar: true
            });
        }
        
        const status = document.getElementById("statusSelect").value;
        const limit = document.getElementById("limitSelect").value;
        const bookings = await getAllBookings(status, "booking_id", "asc", 1, limit);
        renderBookings(bookings);
        renderPagination(bookings.pagination);
    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred. Please try again.',
            timer: 2000,
            timerProgressBar: true
        });
    }
}

// Add status text color helper function
function getStatusTextClass(status) {
    switch (status) {
        case 'Pending':
            return 'warning';
        case 'Confirmed':
            return 'success';
        case 'Processing':
            return 'info';
        case 'Canceled':
        case 'Rejected':
            return 'danger';
        case 'Completed':
            return 'primary';
        default:
            return 'secondary';
    }
}

// Add payment status text color helper function
function getPaymentStatusTextClass(status) {
    switch (status) {
        case 'Paid':
            return 'success';
        case 'Partially Paid':
            return 'warning';
        case 'Unpaid':
            return 'danger';
        default:
            return 'secondary';
    }
}
