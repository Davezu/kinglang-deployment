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

        // calculatorModal.style.display = "flex";

        days.value = this.getAttribute("data-days");
        buses.value = this.getAttribute("data-buses");
        bookingID.value = this.getAttribute("data-bookingID");
    });
})

// document.addEventListener("click", (event) => {
//     if (event.target === calculatorModal) {
//         calculatorModal.style.display = "none";
//     }
// });

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
