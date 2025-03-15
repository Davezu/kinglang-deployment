// function updatePastBookings() {
//     fetch("../../../app/controllers/client/BookingController.php")
//     .then(response => response.text())
//     .then(data => console.log(data))
//     .catch(error => console.error(error));
// }

// window.onload = updatePastBookings;

// format currency column (total cost, balance)
// document.addEventListener("DOMContentLoaded", () => {

//     const totalCosts = Array.from(document.querySelectorAll(".total-cost")).map(cost => cost.textContent);

//     document.querySelectorAll(".total-cost").forEach((cost, i) => {
//         cost.textContent = formatNumber(totalCosts[i]);
//     });

//     const balance = Array.from(document.querySelectorAll(".balance")).map(balance => balance.textContent);

//     document.querySelectorAll(".balance").forEach((bal, i) => {
//         bal.textContent = formatNumber(balance[i]);
//     });
// });

// get all of booking record
document.addEventListener("DOMContentLoaded", getAllBookings);
document.getElementById("status").addEventListener("change", getAllBookings);


const fullAmount = document.getElementById("fullAmount");
const partialAmount = document.getElementById("partialAmount");

const bookingIDinput = document.getElementById("bookingID");
const clientIDinput = document.getElementById("clientID");
const amountInput = document.getElementById("amountInput");

// closing the modal
// document.addEventListener("click", (event) => {
//     if (event.target === paymentModal) {
//         paymentModal.style.display = "none";
//     }
// });

// getting the actual value of the selected formatted currency and place it in the hidden input to insert in database
document.querySelectorAll(".amount-payment").forEach(amount => {
    amount.addEventListener("click", (event) => {
        const amt = event.currentTarget.querySelector(".amount");
        if (amt) {
            document.getElementById("amount").textContent = amt.textContent;
            amountInput.value = parseFloat(amt.textContent.replace(/[^0-9.]/g, ""));
        }
    })
});

const openPaymentModalButton = document.getElementsByClassName("open-payment");
const paymentModal = document.querySelector(".payment-modal");
console.log(openPaymentModalButton);

// open the payment modal and get the value associated with row selected
Array.from(openPaymentModalButton).forEach(button => {
    console.log("test");
    button.addEventListener("click", function () {
        console.log("test");
        const totalCost = this.getAttribute("data-total-cost");
        const bookingID = this.getAttribute("data-booking-id");
        const clientID = this.getAttribute("data-client-id");

        console.log("total cost: ", totalCost);
        console.log("booking id: ", bookingID);
        console.log("client id: ", clientID);

        fullAmount.textContent = formatNumber(totalCost);
        partialAmount.textContent = formatNumber(totalCost / 2);
        bookingIDinput.value = bookingID;
        clientIDinput.value = clientID;
    });
});

async function getAllBookings() {
    const status = this.value || "";

    try {
        const response = await fetch("/home/get-booking-requests", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ status: status })
        });

        const data = await response.json();

        const tbody = document.getElementById("tableBody");
        tbody.innerHTML = "";   

        if (data.success) {
            data.bookings.forEach(booking => {
                const tr = document.createElement("tr");

                const destinationCell = document.createElement("td");
                const dateOfTourCell = document.createElement("td");
                const endOfTourCell = document.createElement("td");
                const daysCell = document.createElement("td");
                const busesCell = document.createElement("td");
                const totalCostCell = document.createElement("td");
                const balanceCell = document.createElement("td");
                const remarksCell = document.createElement("td");
                const actionCell = document.createElement("td");

                destinationCell.textContent = booking.destination;
                endOfTourCell.textContent = booking.end_of_tour;
                dateOfTourCell.textContent = booking.date_of_tour;
                daysCell.textContent = booking.number_of_days;
                busesCell.textContent = booking.number_of_buses;
                totalCostCell.textContent = formatNumber(booking.total_cost);
                balanceCell.textContent = formatNumber(booking.balance);
                remarksCell.textContent = booking.status;

                createPayReschedCancelButton(actionCell, booking);

                tr.append(destinationCell, dateOfTourCell, endOfTourCell, daysCell, busesCell, totalCostCell, balanceCell, remarksCell, actionCell);
                tbody.appendChild(tr);
            });
        } else {
            console.log(data.message);
        }
    } catch (error) {
        console.error("Error fetching data: ", error.message);
    }
}

function formatNumber(number) {
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP"
    }).format(number);
};

function createPayReschedCancelButton(td, booking) {    
    const btnGroup = document.createElement("div");
    const payButton = document.createElement("button");
    const reschedButton = document.createElement("button");
    const cancelButton = document.createElement("button");

    btnGroup.classList.add("container");
    btnGroup.classList.add("d-flex");
    btnGroup.classList.add("gap-2");

    payButton.classList.add("btn");
    payButton.classList.add("btn-success");
    payButton.classList.add("btn-sm");
    payButton.classList.add("open-payment");
    payButton.classList.add("w-100");

    reschedButton.classList.add("btn");
    reschedButton.classList.add("btn-primary");
    reschedButton.classList.add("w-100");
    reschedButton.classList.add("btn-sm");

    cancelButton.classList.add("btn");
    cancelButton.classList.add("btn-danger");
    cancelButton.classList.add("w-100");
    cancelButton.classList.add("btn-sm");

    payButton.setAttribute("data-booking-id", booking.booking_id);
    payButton.setAttribute("data-total-cost", booking.total_cost);
    payButton.setAttribute("data-client-id", booking.client_id);

    payButton.setAttribute("data-bs-toggle", "modal");
    payButton.setAttribute("data-bs-target", "#paymentModal");

    payButton.textContent = "Pay";
    reschedButton.textContent = "Resched";
    cancelButton.textContent = "Cancel";

    if (booking.status === "pending" && booking.total_cost === null) {
        btnGroup.append(reschedButton, cancelButton);
    } else if (booking.totalCost !== null && booking.payment_status !== "paid" && booking.status !== "completed") {
        btnGroup.append(payButton, reschedButton, cancelButton);
    } else {
        btnGroup.textContent = "No action needed";
    }

    td.appendChild(btnGroup);
}

