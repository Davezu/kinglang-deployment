const confirmBookingModal = new bootstrap.Modal(document.getElementById("confirmRebookingModal"));
const rejectBookingModal = new bootstrap.Modal(document.getElementById("rejectRebookingModal"));
const messageModal = new bootstrap.Modal(document.getElementById("messageModal"));

const messageTitle = document.getElementById("messageTitle");
const messageBody = document.getElementById("messageBody");


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

    confirmButton.classList.add('btn', 'bg-success-subtle', 'text-success', 'btn-sm', 'fw-bold', 'w-100', 'approve');
    confirmButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");

    rejectButton.classList.add('btn', 'bg-danger-subtle', 'text-danger', 'w-100', 'fw-bold', 'decline');
    rejectButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");

    viewButton.classList.add('btn', 'bg-primary-subtle', 'text-primary', 'w-100', 'fw-bold', 'decline');
    viewButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");

    // text
    confirmButton.textContent = 'Confrim';
    rejectButton.textContent = 'Reject';
    viewButton.textContent = 'View';
    
    // modal
    confirmButton.setAttribute("data-bs-toggle", "modal");
    confirmButton.setAttribute("data-bs-target", "#confirmRebookingModal");

    rejectButton.setAttribute("data-bs-toggle", "modal");
    rejectButton.setAttribute("data-bs-target", "#rejectRebookingModal");

    
    // data attribute
    confirmButton.setAttribute("data-booking-id", request.booking_id);

    rejectButton.setAttribute("data-booking-id", request.booking_id);
    rejectButton.setAttribute("data-user-id", request.user_id);

    
    
    // logic
    confirmButton.addEventListener('click', function () {
        document.getElementById("confirmBookingId").value = this.getAttribute("data-booking-id");
    });

    viewButton.addEventListener("click", () => {
        localStorage.setItem("bookingId", request.booking_id);
        window.location.href = "/admin/rebooking-request";
    });

    rejectButton.addEventListener("click", function () {
        document.getElementById("rejectBookingId").value = this.getAttribute("data-booking-id");
        document.getElementById("rejectUserId").value = this.getAttribute("data-user-id");
    });


    if (request.status === 'Pending') {   
        buttonGroup.append(confirmButton, rejectButton, viewButton);
    } else {
        buttonGroup.append(viewButton);
    }

    actionCell.appendChild(buttonGroup);

    
    return actionCell;
}

document.getElementById("confirmRebookingForm").addEventListener("submit", async function (event) {
    event.preventDefault(); 

    const formData = new FormData(this);
    const bookingId = formData.get("booking_id");

    try {
        const response = await fetch("/admin/confirm-rebooking-request", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ bookingId })
        });
        
        confirmBookingModal.hide();
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
    
        const data = await response.json();
        
        if (data.success) {
            messageTitle.textContent = "Success";
            messageBody.textContent = data.message;
            messageModal.show();
        } else {
            messageTitle.textContent = "Error";
            messageBody.textContent = data.message;
            messageModal.show();
        }
        
        const status = document.getElementById("statusSelect").value;
        const bookings = await getRebookingRequests(status, "asc", "booking_id");
        renderRebookingRequests(bookings);
    } catch (error) {
        console.error(error);
    }
});

document.getElementById("rejectBookingForm").addEventListener("submit", async function (event) {
    event.preventDefault(); 

    const formData = new FormData(this);
    const bookingId = formData.get("booking_id");
    const userId = formData.get("user_id");
    const reason = formData.get("reason");

    try {
        const response = await fetch("/admin/reject-rebooking", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ bookingId, reason, userId })
        });
        
        rejectBookingModal.hide();
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
    
        const data = await response.json();
        
        if (data.success) {
            messageTitle.textContent = "Success";
            messageBody.textContent = data.message;
            messageModal.show();
        } else {
            messageTitle.textContent = "Error";
            messageBody.textContent = data.message;
            messageModal.show();
        }
        
        const status = document.getElementById("statusSelect").value;
        const bookings = await getAllBookings(status, "asc", "booking_id");
        renderBookings(bookings);
    } catch (error) {
        console.error(error);
    }
});