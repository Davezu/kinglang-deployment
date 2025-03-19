document.addEventListener("DOMContentLoaded", async function () {
    const bookings = await getAllBookings("all", "asc", "booking_id");    
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

        // try {
        //     const response = await fetch("/admin/order-bookings", {
        //         method: "POST",
        //         headers: { "Content-Type": "application/json" },
        //         body: JSON.stringify({ column, order })
        //     });

        //     const data = await response.json();
        //     renderBookings(data.bookings);

        //     this.setAttribute("data-order", order === "asc" ? "desc" : "asc");
        // } catch (error) {
        //     console.error(error);
        // }
    });
});

function formatDate(date) {
    return new Date(date).toLocaleDateString("en-US", {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

const calculateTotalCostButton = document.querySelectorAll(".calculateTotalCost");
const distance = document.getElementById("distance");
const diesel = document.getElementById("diesel");
const days = document.getElementById("numberOfDays");
const buses = document.getElementById("numberOfBuses");
const totalCostDisplay = document.getElementById("totalCostDisplay");

const bookingID = document.getElementById("bookingID");
const totalCost = document.getElementById("totalCost");

function calculateTotalCost() {
    day = parseFloat(days.value) || 0;
    bus = parseFloat(buses.value) || 0;
    diesl = parseFloat(diesel.value) || 0;
    distanc = parseFloat(distance.value) || 0;

    const product = day * bus * diesl * distanc;

    const formattedProduct = new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP"
    }).format(product)

    totalCost.value = product;
    totalCostDisplay.textContent = formattedProduct;
}

distance.addEventListener("input", calculateTotalCost);
diesel.addEventListener("input", calculateTotalCost);

[distance, diesel].forEach(input => {
    input.addEventListener("keydown", (event) => {
        if (event.key === "-" || event.key === "e") {
            event.preventDefault();
        }
    });
});

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
        pickupPointCell.textContent = booking.pickup_point;
        dateOfTourCell.textContent = formatDate(booking.date_of_tour);
        endOfTourCell.textContent = formatDate(booking.end_of_tour);
        numberOfDaysCell.textContent = booking.number_of_days;
        numberOfBusesCell.textContent = booking.number_of_buses;
        statusCell.textContent = booking.status;
        paymentStatusCell.textContent = booking.payment_status; 

        row.append(clientNameCell, contactNumberCell, destinationCell, pickupPointCell, dateOfTourCell, endOfTourCell, numberOfDaysCell, numberOfBusesCell, statusCell, paymentStatusCell, actionButton(booking));
        tbody.appendChild(row);
    });
}

function actionButton(booking) {
    const actionCell = document.createElement("td");
    const buttonGroup = document.createElement("div");
    const computeButton = document.createElement("button");
    const rejectButton = document.createElement("button");

    buttonGroup.classList.add("d-flex", "gap-2", "align-items-center");

    computeButton.classList.add("btn", "bg-success-subtle", "text-success", "btn-sm", "fw-bold", "w-100", "calculateTotalCost");
    rejectButton.classList.add("btn", "bg-danger-subtle", "text-danger", "btn-sm", "fw-bold", "w-100");
    rejectButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");

    computeButton.textContent = "Compute";
    rejectButton.textContent = "Reject";

    computeButton.setAttribute("data-days", booking.number_of_days);
    computeButton.setAttribute("data-buses", booking.number_of_buses);
    computeButton.setAttribute("data-booking-id", booking.booking_id);
    computeButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");

    computeButton.setAttribute("data-bs-toggle", "modal");
    computeButton.setAttribute("data-bs-target", "#calculatorModal");

    computeButton.addEventListener("click", function () {
        days.value = this.getAttribute("data-days");
        buses.value = this.getAttribute("data-buses");
        bookingID.value = this.getAttribute("data-booking-id");
    });

    if (booking.total_cost === null || parseFloat(booking.total_cost) === 0) {
        buttonGroup.append(computeButton, rejectButton);
    } else {
        buttonGroup.textContent = "No action needed";
    }
    actionCell.appendChild(buttonGroup);

    return actionCell;
} 

document.getElementById("calculatorForm").addEventListener("submit", async function (event) {
    event.preventDefault(); 

    const formData = new FormData(this);
    const bookingId = formData.get("booking_id");
    const totalCost = formData.get("total_cost");

    console.log(bookingId, totalCost);

    try {
        const response = await fetch("/admin/send-quote", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ totalCost, bookingId })
        });
    
        const data = await response.json();
    
        document.getElementById("messageElement").style.color = data.success ? "green" : "red";   
        document.getElementById("messageElement").textContent = data.success ? data.message : data.message;
        
        const status = document.getElementById("statusSelect").value;
        const bookings = getAllBookings(status, "asc", "booking_id");
        renderBookings(bookings);
    } catch (error) {
        console.error(error);
    }
});


