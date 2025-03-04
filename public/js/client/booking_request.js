document.addEventListener("DOMContentLoaded", () => {
    const totalCosts = Array.from(document.querySelectorAll(".total-cost")).map(cost => cost.textContent);

    document.querySelectorAll(".total-cost").forEach((cost, i) => {
        const formattedCost = new Intl.NumberFormat("en-PH", {
            style: "currency",
            currency: "PHP"  
        }).format(totalCosts[i]);

        cost.textContent = formattedCost;
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

        paymentModal.style.display = "flex";
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
}
