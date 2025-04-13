const confirmBookingModal = new bootstrap.Modal(document.getElementById("confirmBookingModal"));
const messageModal = new bootstrap.Modal(document.getElementById("messageModal"));

const messageTitle = document.getElementById("messageTitle");
const messageBody = document.getElementById("messageBody");

document.addEventListener("DOMContentLoaded", async function () {
    const bookings = await getAllBookings("All", "asc", "booking_id");    
    renderBookings(bookings);
});

document.getElementById("statusSelect").addEventListener("change", async function () {
    const status = this.value;  
    console.log(status);    
    const bookings = await getAllBookings(status, "asc", "client_name");
    renderBookings(bookings);
});

document.querySelectorAll(".sort").forEach(button => {
    button.style.cursor = "pointer";
    button.style.backgroundColor = "#d1f7c4";

    button.addEventListener("click", async function () {
        const status = document.getElementById("statusSelect").value;
        const column = this.getAttribute("data-column");
        const order = this.getAttribute("data-order");

        const bookings = await getAllBookings(status, order, column);
        console.log(bookings);
        renderBookings(bookings);
        
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

// const calculateTotalCostButton = document.querySelectorAll(".calculateTotalCost");
// const distance = document.getElementById("distance");
// const diesel = document.getElementById("diesel");
// const days = document.getElementById("numberOfDays");
// const buses = document.getElementById("numberOfBuses");
// const totalCostDisplay = document.getElementById("totalCostDisplay");

// const bookingID = document.getElementById("bookingID");
// const totalCost = document.getElementById("totalCost");

// function calculateTotalCost() {
//     day = parseFloat(days.value) || 0;
//     bus = parseFloat(buses.value) || 0;
//     diesl = parseFloat(diesel.value) || 0;
//     distanc = parseFloat(distance.value) || 0;

//     const product = day * bus * diesl * distanc;

//     const formattedProduct = new Intl.NumberFormat("en-PH", {
//         style: "currency",
//         currency: "PHP"
//     }).format(product)

//     totalCost.value = product;
//     totalCostDisplay.textContent = formattedProduct;
// }

// distance.addEventListener("input", calculateTotalCost);
// diesel.addEventListener("input", calculateTotalCost);

// [distance, diesel].forEach(input => {
//     input.addEventListener("keydown", (event) => {
//         if (event.key === "-" || event.key === "e") {
//             event.preventDefault();
//         }
//     });
// });

async function getAllBookings(status, order, column) {
    try {
        const response = await fetch("/admin/bookings", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ status, order, column })
        });

        const data = await response.json();
        console.log(data);

        if (data.success) {
            return data.bookings;
        }
    } catch (error) {
        console.error(error);
    }
}

async function renderBookings(bookings) {
    // const bookings = await getAllBookings();

    const tbody = document.getElementById("tableBody");
    tbody.innerHTML = "";

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
        contactNumberCell.textContent = booking.contact_number;
        destinationCell.textContent = booking.destination;
        pickupPointCell.textContent = booking.total_cost;
        dateOfTourCell.textContent = formatDate(booking.date_of_tour);
        numberOfDaysCell.textContent = booking.number_of_days;
        numberOfBusesCell.textContent = booking.number_of_buses;
        statusCell.textContent = booking.status;
        paymentStatusCell.textContent = booking.payment_status;

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

    confirmButton.classList.add("btn", "bg-success-subtle", "text-success", "btn-sm", "fw-bold", "w-100", "calculateTotalCost");

    rejectButton.classList.add("btn", "bg-danger-subtle", "text-danger", "btn-sm", "fw-bold", "w-100");
    rejectButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");

    cancelButton.classList.add("btn", "bg-danger-subtle", "text-danger", "btn-sm", "fw-bold", "w-100");
    cancelButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");

    viewButton.classList.add("btn", "bg-primary-subtle", "text-primary", "btn-sm", "fw-bold", "w-100");
    viewButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");

    confirmButton.textContent = "Confirm";
    rejectButton.textContent = "Reject";
    cancelButton.textContent = "Cancel";
    viewButton.textContent = "View";

    confirmButton.setAttribute("data-booking-id", booking.booking_id);
    confirmButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");

    confirmButton.setAttribute("data-bs-toggle", "modal");
    confirmButton.setAttribute("data-bs-target", "#confirmBookingModal");

    confirmButton.addEventListener("click", function () {
        document.getElementById("bookingId").value = this.getAttribute("data-booking-id");
    });

    viewButton.addEventListener("click", function () {
        localStorage.setItem("bookingId", booking.booking_id);
        window.location.href = "/admin/booking-request";
    })

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

// confirming booking
document.getElementById("confirmBookingForm").addEventListener("submit", async function (event) {
    event.preventDefault(); 

    const formData = new FormData(this);
    const bookingId = formData.get("booking_id");

    try {
        const response = await fetch("/admin/confirm-booking", {
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
        const bookings = await getAllBookings(status, "asc", "booking_id");
        renderBookings(bookings);
    } catch (error) {
        console.error(error);
    }
});


