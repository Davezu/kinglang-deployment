// function updatePastBookings() {
//     fetch("../../../app/controllers/client/BookingController.php")
//     .then(response => response.text())
//     .then(data => console.log(data))
//     .catch(error => console.error(error));
// }

// window.onload = updatePastBookings;

console.log("hello");

document.addEventListener("DOMContentLoaded", () => {

    const totalCosts = Array.from(document.querySelectorAll(".total-cost")).map(cost => cost.textContent);

    document.querySelectorAll(".total-cost").forEach((cost, i) => {
        cost.textContent = formatNumber(totalCosts[i]);
    });

    const balance = Array.from(document.querySelectorAll(".balance")).map(balance => balance.textContent);

    document.querySelectorAll(".balance").forEach((bal, i) => {
        bal.textContent = formatNumber(balance[i]);
    });
});

const openPaymentModalButton = document.querySelectorAll(".open-payment-modal");
const paymentModal = document.querySelector(".payment-modal");

const fullAmount = document.getElementById("fullAmount");
const partialAmount = document.getElementById("partialAmount");

const bookingIDinput = document.getElementById("bookingID");
const clientIDinput = document.getElementById("clientID");
const amountInput = document.getElementById("amountInput");

openPaymentModalButton.forEach(button => {
    button.addEventListener("click", function (event) {
        event.preventDefault();
        
        const totalCost = this.getAttribute("data-amount");
        const bookingID = this.getAttribute("data-bookingID");
        const clientID = this.getAttribute("data-clientID");

        fullAmount.textContent = formatNumber(totalCost);
        partialAmount.textContent = formatNumber(totalCost / 2);
        bookingIDinput.value = bookingID;
        clientIDinput.value = clientID;
    });
});

document.addEventListener("click", (event) => {
    if (event.target === paymentModal) {
        paymentModal.style.display = "none";
    }
});


document.querySelectorAll(".amount-payment").forEach(amount => {
    amount.addEventListener("click", (event) => {
        const amt = event.currentTarget.querySelector(".amount");
        if (amt) {
            document.getElementById("amount").textContent = amt.textContent;
            amountInput.value = parseFloat(amt.textContent.replace(/[^0-9.]/g, ""));
        }
    })
});

function formatNumber(number) {
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP"
    }).format(number);
};

document.getElementById("status").addEventListener("change", function () {
    const status = this.value;
    // if (status) {
        let segments = window.location.pathname.split("/"); 

        if (segments.length >= 3) {
            let newPath = `/home/bookings/${segments[3] || "1"}/${status || ""}`.replace(/\/+$/, "");
            window.location.href = newPath;
            console.log(newPath);
        }   
    // }
});

document.addEventListener("DOMContentLoaded", function () {
    let segments = window.location.pathname.split("/");
    let status = segments[segments.length - 1];

    let statusSelect = document.getElementById("status");

    if (statusSelect.querySelector(`option[value=${status}]`)) {
        statusSelect.value = status;
    }
})