// const confirmBookingModal = new bootstrap.Modal(document.getElementById("confirmRebookingModal"));
// const rejectBookingModal = new bootstrap.Modal(document.getElementById("rejectRebookingModal"));
// const messageModal = new bootstrap.Modal(document.getElementById("messageModal"));

// const messageTitle = document.getElementById("messageTitle");
// const messageBody = document.getElementById("messageBody");


document.addEventListener('DOMContentLoaded', async function () {
    const requests = await getRebookingRequests('All', 'asc', 'booking_id');
    renderRebookingRequests(requests);
}); 

document.getElementById('statusSelect').addEventListener('change', async function () {
    const status = this.value;
    console.log(status);
    const requests = await getRebookingRequests(status, 'asc', 'client_name');
    renderRebookingRequests(requests);
});

document.querySelectorAll('.sort').forEach(button => {
    button.style.cursor = 'pointer';
    button.style.backgroundColor = '#d1f7c4';

    button.addEventListener('click', async function () {
        const status = document.getElementById('statusSelect').value;
        const column = this.getAttribute('data-column');
        const order = this.getAttribute('data-order');

        const requests = await getRebookingRequests(status, order, column);
        renderRebookingRequests(requests);

        this.setAttribute('data-order', order === 'asc' ? 'desc' : 'asc');
    });
});

function formatDate(date) {
    return new Date(date).toLocaleDateString("en-US", {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

async function getRebookingRequests(status, order, column) {
    try {
        const response = await fetch("/admin/get-rebooking-requests", {
            method: 'POST', 
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ status, order, column })
        });

        const data = await response.json();
        console.log(data);
        if (data.success) {
            return data.requests;
        }
    } catch (error) {
        console.error('Fetch error:', error);
    }
}

async function renderRebookingRequests(requests) {
    // const requests = await getRebookingRequests();
    console.log(requests);
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = '';

    requests.forEach(request => {
        const row = document.createElement('tr');
        
        const clientNameCell = document.createElement('td');
        const clientContactCell = document.createElement('td');
        const clientEmailCell = document.createElement("td");
        const dateOfTourCell = document.createElement('td');
        const statusCell = document.createElement('td');

        clientNameCell.textContent = request.client_name;
        clientContactCell.textContent = request.contact_number;
        clientEmailCell.textContent = request.email;
        dateOfTourCell.textContent = formatDate(request.date_of_tour);
        statusCell.textContent = request.status;

        row.append(clientNameCell, clientContactCell, clientEmailCell, dateOfTourCell, statusCell, actionButtons(request));
        tbody.appendChild(row);
    });
}

function actionButtons(request) {
    const actionCell = document.createElement('td');
    const buttonGroup = document.createElement('div');
    const confirmButton = document.createElement('button');
    const rejectButton = document.createElement('button');
    const viewButton = document.createElement('button');

    // style
    buttonGroup.classList.add('d-flex', 'gap-2');   

    confirmButton.classList.add('btn', 'btn-outline-success', 'd-flex', 'align-items-center', 'gap-1', 'btn-sm', 'approve');
    // confirmButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");

    rejectButton.classList.add('btn', 'btn-outline-danger', 'd-flex', 'align-items-center', 'gap-1', 'btn-sm', 'decline');
    // rejectButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");

    viewButton.classList.add('btn', 'btn-outline-primary', 'd-flex', 'align-items-center', 'gap-1', 'btn-sm', 'view');
    // viewButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");

    // icons
    const confirmIcon = document.createElement("i");
    confirmIcon.classList.add("bi", "bi-check-circle");
    const confirmText = document.createTextNode(" Confirm");
    confirmButton.appendChild(confirmIcon);
    confirmButton.appendChild(confirmText);

    const rejectIcon = document.createElement("i");
    rejectIcon.classList.add("bi", "bi-x-circle");
    const rejectText = document.createTextNode(" Reject");
    rejectButton.appendChild(rejectIcon);
    rejectButton.appendChild(rejectText);

    const viewIcon = document.createElement("i");
    viewIcon.classList.add("bi", "bi-info-circle");
    const viewText = document.createTextNode(" Details");
    viewButton.appendChild(viewIcon);
    viewButton.appendChild(viewText);
    
    // data attribute
    confirmButton.setAttribute("data-booking-id", request.booking_id);

    rejectButton.setAttribute("data-booking-id", request.booking_id);
    rejectButton.setAttribute("data-user-id", request.user_id);

    viewButton.setAttribute("data-booking-id", request.booking_id);
    
    // logic
    confirmButton.addEventListener('click', function () {
        const bookingId = this.getAttribute("data-booking-id");
        
        Swal.fire({
            title: 'Enter Discount Rate',
            text: 'Enter a discount percentage (0-100)',
            input: 'number',
            inputPlaceholder: 'e.g., 15 for 15%',
            showCancelButton: true,
            confirmButtonColor: '#198754',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Confirm',
            cancelButtonText: 'Cancel',
            inputAttributes: {
                min: 0,
                max: 100,
                step: 0.01
            },
            inputValidator: (value) => {
                if (!value) {
                    return 'Please enter a discount rate';
                }
                const numValue = parseFloat(value);
                if (isNaN(numValue) || numValue < 0 || numValue > 100) {
                    return 'Discount must be between 0 and 100';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const discount = parseFloat(result.value || 0);
                confirmBookingRequest(bookingId, discount);
            }
        });
    });

    viewButton.addEventListener("click", function() {
        const bookingId = this.getAttribute("data-booking-id");
        showBookingDetails(bookingId);
    });

    rejectButton.addEventListener("click", function () {
        const bookingId = this.getAttribute("data-booking-id");
        const userId = this.getAttribute("data-user-id");
        
        Swal.fire({
            title: 'Reject Booking?',
            text: 'Are you sure you want to reject this booking request?',
            input: 'textarea',
            inputLabel: 'Reason',
            inputPlaceholder: 'Kindly provide the reason here.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Reject',
            cancelButtonText: 'Cancel',
            inputValidator: (value) => {
                if (!value) {
                    return 'Please provide a reason for rejection!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                rejectBookingRequest(bookingId, result.value, userId);
            }
        });
    });

    if (request.status === 'Pending') {   
        buttonGroup.append(confirmButton, rejectButton, viewButton);
    } else {
        buttonGroup.append(viewButton);
    }

    actionCell.appendChild(buttonGroup);
    
    return actionCell;
}

// Function to show booking details in a modal
function showBookingDetails(bookingId) {
    // Get full booking details including stops 
    getBookingDetails(bookingId).then(booking => {
        if (booking) {
            const bookingDetailsContent = document.getElementById('bookingDetailsContent');
            console.log("booking details: ", booking);
            // Get status color classes
            const statusColors = {
                'Pending': 'warning',
                'Confirmed': 'success',
                'Canceled': 'danger',
                'Rejected': 'secondary',
                'Completed': 'info'
            };
            
            const statusColor = statusColors[booking.status] || 'secondary';
            
            bookingDetailsContent.innerHTML = `
                <div class="booking-detail-section mb-3">
                    <h6 class="border-bottom pb-2"><i class="bi bi-geo-alt me-2"></i>Booking Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Booking ID:</strong> #${booking.booking_id}</p>
                            <p><strong>Booking Date:</strong> ${formatDate(booking.booked_at)}</p>
                            <p><strong>Status:</strong> <span class="badge bg-${statusColor}">${booking.status}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Client Name:</strong> ${booking.client_name || 'N/A'}</p>
                            <p><strong>Email:</strong> ${booking.email || 'N/A'}</p>
                            <p><strong>Phone:</strong> ${booking.contact_number || 'N/A'}</p>
                        </div>
                    </div>
                </div>
                <div class="booking-detail-section mb-4">
                    <h6 class="border-bottom pb-2"><i class="bi bi-geo-alt me-2"></i>Trip Details</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Pickup Point:</strong> ${booking.pickup_point || 'N/A'}</p>
                            <p class="mb-2"><strong>Destination:</strong> ${booking.destination || 'N/A'}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Tour Date:</strong> ${formatDate(booking.date_of_tour)}${booking.end_of_tour ? ` to ${formatDate(booking.end_of_tour)}` : ''}</p>
                            <p class="mb-2"><strong>Duration:</strong> ${booking.number_of_days} day${booking.number_of_days > 1 ? 's' : ''}</p>
                            <p class="mb-2"><strong>Number of Buses:</strong> ${booking.number_of_buses}</p>
                        </div>
                    </div>
                </div>
                
                <div class="booking-detail-section mb-3">
                    <h6 class="border-bottom pb-2"><i class="bi bi-cash-coin me-2"></i>Payment Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Total Cost:</strong> ₱${parseFloat(booking.total_cost).toLocaleString('en-PH')}</p>
                            <p><strong>Payment Status:</strong> <span class="badge bg-${booking.payment_status === 'Paid' ? 'success' : booking.payment_status === 'Partially Paid' ? 'warning' : 'danger'}">${booking.payment_status}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Payment Date:</strong> ${booking.payments && booking.payments.length > 0 ? formatDate(booking.payments[0].payment_date) : 'No payments yet'}</p>
                            <p><strong>Payment Method:</strong> ${booking.payments && booking.payments.length > 0 ? booking.payments[0].payment_method : 'N/A'}</p>
                        </div>
                    </div>
                </div>
                
                <div class="booking-detail-section mb-2">
                    <h6 class="text-success mb-3"><i class="bi bi-list-check me-2"></i>Actions</h6>
                    <div class="d-flex flex-wrap gap-2">
                        ${booking.status === "Pending" ? `
                            <button class="btn btn-sm btn-outline-success confirm-booking-modal" data-booking-id="${booking.booking_id}">
                                <i class="bi bi-check-circle"></i> Confirm Booking
                            </button>
                            <button class="btn btn-sm btn-outline-danger reject-booking-modal" data-booking-id="${booking.booking_id}" data-user-id="${booking.user_id}">
                                <i class="bi bi-x-circle"></i> Reject Booking
                            </button>
                        ` : ''}
                        
                        <button class="btn btn-sm btn-outline-primary view-invoice" data-booking-id="${booking.booking_id}">
                            <i class="bi bi-file-earmark-text"></i> Invoice
                        </button>
                    </div>
                </div>
            `;
            
            // Add event listeners to action buttons
            const confirmBtn = bookingDetailsContent.querySelector('.confirm-booking-modal');
            if (confirmBtn) {
                confirmBtn.addEventListener('click', function() {
                    const bookingId = this.getAttribute('data-booking-id');
                    
                    // Close the modal before showing SweetAlert
                    bootstrap.Modal.getInstance(document.getElementById('bookingDetailsModal')).hide();
                    
                    Swal.fire({
                        title: 'Enter Discount Rate',
                        text: 'Enter a discount percentage (0-100)',
                        input: 'number',
                        inputPlaceholder: 'e.g., 15 for 15%',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Confirm',
                        cancelButtonText: 'Cancel',
                        inputAttributes: {
                            min: 0,
                            max: 100,
                            step: 0.01
                        },
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Please enter a discount rate';
                            }
                            const numValue = parseFloat(value);
                            if (isNaN(numValue) || numValue < 0 || numValue > 100) {
                                return 'Discount must be between 0 and 100';
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const discount = parseFloat(result.value || 0);
                            confirmBookingRequest(bookingId, discount);
                        }
                    });
                });
            }
            
            const rejectBtn = bookingDetailsContent.querySelector('.reject-booking-modal');
            if (rejectBtn) {
                rejectBtn.addEventListener('click', function() {
                    const bookingId = this.getAttribute('data-booking-id');
                    const userId = this.getAttribute('data-user-id');
                    
                    // Close the modal before showing SweetAlert
                    bootstrap.Modal.getInstance(document.getElementById('bookingDetailsModal')).hide();
                    
                    Swal.fire({
                        title: 'Reject Booking?',
                        text: 'Are you sure you want to reject this booking request?',
                        input: 'textarea',
                        inputLabel: 'Reason',
                        inputPlaceholder: 'Kindly provide the reason here.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Reject',
                        cancelButtonText: 'Cancel',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Please provide a reason for rejection!';
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            rejectBookingRequest(bookingId, result.value, userId);
                        }
                    });
                });
            }
            
            // Add event listener for the view invoice button
            const viewInvoiceBtn = bookingDetailsContent.querySelector('.view-invoice');
            if (viewInvoiceBtn) {
                viewInvoiceBtn.addEventListener('click', function() {
                    const bookingId = this.getAttribute('data-booking-id');
                    // Close the modal before opening the invoice
                    bootstrap.Modal.getInstance(document.getElementById('bookingDetailsModal')).hide();
                    // Navigate to the invoice page
                    window.open(`/admin/print-invoice/${bookingId}`, '_blank');
                });
            }
            
            // Show the modal
            const bookingDetailsModal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
            bookingDetailsModal.show();
        }
    });
}

// Function to get detailed booking information including stops
async function getBookingDetails(bookingId) {
    try {
        const response = await fetch("/admin/get-booking-details", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ bookingId })
        });

        if (response.ok) {
            const data = await response.json();
            console.log("Booking details response:", data);
            
            if (data.success) {
                return data.booking;
            }
        }
        
        // If there's an error, fall back to the rebooking requests data
        const currentRequests = await getRebookingRequests('All', 'asc', 'booking_id');
        const booking = currentRequests.find(b => b.booking_id == bookingId);
        console.log("Fallback to current requests:", booking);
        return booking;
        
    } catch (error) {
        console.error("Error fetching booking details:", error);
        
        // If there's an exception, fall back to the rebooking requests data
        const currentRequests = await getRebookingRequests('All', 'asc', 'booking_id');
        return currentRequests.find(b => b.booking_id == bookingId);
    }
}

async function confirmBookingRequest(bookingId, discount) {
    try {
        const response = await fetch("/admin/confirm-rebooking-request", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ bookingId, discount })
        });
    
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                title: 'Success!',
                text: data.message,
                icon: 'success',
                confirmButtonColor: '#198754'
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: data.message,
                icon: 'error',
                confirmButtonColor: '#dc3545'
            });
        }
        
        const status = document.getElementById("statusSelect").value;
        const bookings = await getRebookingRequests(status, "asc", "booking_id");
        renderRebookingRequests(bookings);
    } catch (error) {
        console.error(error);
        Swal.fire({
            title: 'Error!',
            text: 'An unexpected error occurred.',
            icon: 'error',
            confirmButtonColor: '#dc3545'
        });
    }
}

async function rejectBookingRequest(bookingId, reason, userId) {
    try {
        const response = await fetch("/admin/reject-rebooking", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ bookingId, reason, userId })
        });
    
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                title: 'Success!',
                text: data.message,
                icon: 'success',
                confirmButtonColor: '#198754'
            });
        } else {
            Swal.fire({
                title: 'Error!',
                text: data.message,
                icon: 'error',
                confirmButtonColor: '#dc3545'
            });
        }
        
        const status = document.getElementById("statusSelect").value;
        const bookings = await getRebookingRequests(status, "asc", "booking_id");
        renderRebookingRequests(bookings);
    } catch (error) {
        console.error(error);
        Swal.fire({
            title: 'Error!',
            text: 'An unexpected error occurred.',
            icon: 'error',
            confirmButtonColor: '#dc3545'
        });
    }
}