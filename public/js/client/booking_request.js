const cancelBookingModal = new bootstrap.Modal(document.getElementById("cancelBookingModal"));
const messageModal = new bootstrap.Modal(document.getElementById("messageModal"));

const messageTitle = document.getElementById("messageTitle");
const messageBody = document.getElementById("messageBody");


// disable past dates in date of tour input
const today = new Date();
today.setDate(today.getDate() + 3);
const minDate = today.toISOString().split("T")[0];
document.getElementById("date_of_tour").min = minDate; 

function formatDate(date) {
    return new Date(date).toLocaleDateString("en-US", {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

// get all of booking record
document.addEventListener("DOMContentLoaded", async function () {
    const bookings = await getAllBookings("all", "date_of_tour", "asc");
    renderBookings(bookings);
});

// filter booking record by status
document.getElementById("statusSelect").addEventListener("change", async function () {
    const status = this.value;
    const bookings = await getAllBookings(status, "date_of_tour", "asc");
    renderBookings(bookings);
});

// sort booking record by column
document.querySelectorAll(".sort").forEach(button => {
    button.style.cursor = "pointer";
    button.style.backgroundColor = "#d1f7c4";

    button.addEventListener("click", async function () {
        const status = document.getElementById("statusSelect").value;
        const column = this.getAttribute("data-column");
        const order = this.getAttribute("data-order");

        const bookings = await getAllBookings(status, column, order);
        console.log(bookings);
        renderBookings(bookings);   

        this.setAttribute("data-order", order === "asc" ? "desc" : "asc");
    });
});

const fullAmount = document.getElementById("fullAmount");
const partialAmount = document.getElementById("partialAmount");

const bookingIDinput = document.getElementById("bookingID");
const userIDinput = document.getElementById("userID");
const amountInput = document.getElementById("amountInput");

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

const openPaymentModalButton = document.getElementsByClassName("open-payment-modal");
const paymentModal = document.querySelector(".payment-modal");
console.log(openPaymentModalButton);


// open the payment modal and get the value associated with row selected
document.querySelectorAll(".btn-container").forEach(container => {
    console.log(container)
    container.addEventListener("click", function (e) {
        console.log("test");
        if (e.target.contains('open-payment-modal')) {
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
        }
    })
});

async function getAllBookings(status, column, order) {
    try {
        const response = await fetch("/home/get-booking-requests", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ status, column, order })
        });

        const data = await response.json();

        const tbody = document.getElementById("tableBody");
        tbody.innerHTML = "";   

        if (data.success) {
            return data.bookings;
        } else {
            console.log(data.message);
        }
    } catch (error) {
        console.error("Error fetching data: ", error.message);
    }
}

function renderBookings(bookings) {
    const tbody = document.getElementById("tableBody");
    tbody.innerHTML = "";

    bookings.forEach(booking => {
        const tr = document.createElement("tr");

        const destinationCell = document.createElement("td");
        const dateOfTourCell = document.createElement("td");
        const endOfTourCell = document.createElement("td");
        const daysCell = document.createElement("td");
        const busesCell = document.createElement("td");
        const totalCostCell = document.createElement("td");
        const balanceCell = document.createElement("td");
        const remarksCell = document.createElement("td");

        destinationCell.textContent = booking.destination;
        dateOfTourCell.textContent = formatDate(booking.date_of_tour);
        endOfTourCell.textContent = formatDate(booking.end_of_tour);
        daysCell.textContent = booking.number_of_days;
        busesCell.textContent = booking.number_of_buses;
        totalCostCell.textContent = formatNumber(booking.total_cost);
        balanceCell.textContent = formatNumber(booking.balance);
        remarksCell.textContent = booking.status;

        tr.append(destinationCell, dateOfTourCell, endOfTourCell, daysCell, busesCell, totalCostCell, balanceCell, remarksCell, actionCell(booking));
        tbody.appendChild(tr);
    });
}

function actionCell(booking) {
    const td = document.createElement("td");
    const btnGroup = document.createElement("div");
    const payButton = document.createElement("button");
    const editButton = document.createElement("button");
    const cancelButton = document.createElement("button");
    const viewButton = document.createElement("button");

    // style
    btnGroup.classList.add("container", "btn-container", "d-flex", "gap-2");
    payButton.classList.add("open-payment-modal", "btn", "bg-success-subtle", "text-success", "fw-bold", "w-100");
    editButton.classList.add("btn", "bg-primary-subtle", "w-100", "fw-bold", "text-primary");
    cancelButton.classList.add("btn", "bg-danger-subtle", "w-100", "fw-bold", "text-danger");
    viewButton.classList.add("btn", "bg-success-subtle", "w-100", "fw-bold", "text-success");

    payButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");
    editButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");
    cancelButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");
    viewButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");

    // text
    payButton.textContent = "Pay";
    editButton.textContent = "Edit";
    cancelButton.textContent = "Cancel";
    viewButton.textContent = "View";

    // modal
    payButton.setAttribute("data-bs-toggle", "modal");
    payButton.setAttribute("data-bs-target", "#paymentModal");

    cancelButton.setAttribute("data-bs-toggle", "modal");
    cancelButton.setAttribute("data-bs-target", "#cancelBookingModal");

    // data attributes
    payButton.setAttribute("data-booking-id", booking.booking_id);
    payButton.setAttribute("data-total-cost", booking.total_cost);
    payButton.setAttribute("data-client-id", booking.client_id);

    editButton.setAttribute("data-booking-id", booking.booking_id);
    editButton.setAttribute("data-days", booking.number_of_days);
    editButton.setAttribute("data-buses", booking.number_of_buses);

    cancelButton.setAttribute("data-booking-id", booking.booking_id);

    // event listeners
    viewButton.addEventListener("click", function () {
        localStorage.setItem("bookingId", booking.booking_id);
        window.location.href = "/home/booking-request";
    })

    payButton.addEventListener("click", function () {
        document.getElementById("amount").textContent = "";
        const totalCost = this.getAttribute("data-total-cost");
        const bookingID = this.getAttribute("data-booking-id");

        document.getElementById("fullAmnt").style.display = "block";  
        document.getElementById("downPayment").textContent = "Down Payment";
        
        if (parseFloat(booking.balance) < parseFloat(booking.total_cost)) {
            document.getElementById("fullAmnt").style.display = "none";   
            document.getElementById("downPayment").textContent = "Final Payment";
        } else {
            fullAmount.textContent = formatNumber(totalCost);
        }
        partialAmount.textContent = formatNumber(totalCost / 2);
        bookingIDinput.value = bookingID;
        userIDinput.value = booking.user_id;
    });

    editButton.addEventListener("click", function () {
        sessionStorage.setItem("bookingId", booking.booking_id);
        window.location.href = "/home/book";
    });

    cancelButton.addEventListener("click", function () {
        document.getElementById("cancelBookingId").value = this.getAttribute("data-booking-id");
        document.getElementById("cancelUserId").value = this.getAttribute("data-user-id");
    });

    if (booking.status === "Confirmed" && parseFloat(booking.balance) > 0.0) {
        btnGroup.append(payButton, editButton, cancelButton, viewButton);
    } else if (booking.status === "Confirmed" && parseFloat(booking.balance) === 0) {
        btnGroup.append(editButton, cancelButton, viewButton); 
    } else if (booking.status === "Pending") {
        btnGroup.append(editButton, cancelButton, viewButton);  
    } else {
        btnGroup.append(viewButton);
    }

    td.appendChild(btnGroup);

    return td;
}

function formatNumber(number) {
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP"
    }).format(number);
};

document.getElementById("cancelBookingForm").addEventListener("submit", async function (event) {
    event.preventDefault(); 

    const formData = new FormData(this);
    const bookingId = formData.get("booking_id");
    const reason = formData.get("reason");

    try {
        const response = await fetch("/cancel-booking", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ bookingId, reason })
        });
        
        cancelBookingModal.hide();
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
        const bookings = await getAllBookings(status, "booking_id", "asc");
        renderBookings(bookings);
    } catch (error) {
        console.error(error);
    }
});
