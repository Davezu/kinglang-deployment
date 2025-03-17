document.addEventListener("DOMContentLoaded", renderBookings);

const calculateTotalCostButton = document.querySelectorAll(".calculateTotalCost");
const distance = document.getElementById("distance");
const diesel = document.getElementById("diesel");
const days = document.getElementById("numberOfDays");
const buses = document.getElementById("numberOfBuses");
const totalCostDisplay = document.getElementById("totalCostDisplay");

const bookingID = document.getElementById("bookingID");
const totalCost = document.getElementById("totalCost");

const calculatorModal = document.querySelector(".payment-calculator");

calculateTotalCostButton.forEach(button => {
    button.addEventListener("click", function (event) {
        event.preventDefault();

        days.value = this.getAttribute("data-days");
        buses.value = this.getAttribute("data-buses");
        bookingID.value = this.getAttribute("data-bookingID");
    });
})

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

async function getAllBookings() {
    try {
        const response = await fetch("/admin/bookings");
        const data = await response.json();

        if (data.success) {
            return data.bookings;
        }
    } catch (error) {
        console.error(error);
    }
}

async function renderBookings() {
    const bookings = await getAllBookings();

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
        dateOfTourCell.textContent = booking.date_of_tour;
        endOfTourCell.textContent = booking.end_of_tour;
        numberOfDaysCell.textContent = booking.number_of_days;
        numberOfBusesCell.textContent = booking.number_of_buses;
        statusCell.textContent = booking.status;
        paymentStatusCell.textContent = booking.payment_status; 

        row.append(clientNameCell, contactNumberCell, destinationCell, pickupPointCell, dateOfTourCell, endOfTourCell, numberOfDaysCell, numberOfBusesCell, statusCell, paymentStatusCell);
        tbody.appendChild(row);
    });
}