const bookingForm = document.querySelector(".container");
const bookingButton = document.getElementById("bookATrip");

bookingButton.addEventListener("click", () => {
    bookingForm.style.display = "flex";
});

document.addEventListener("click", (event) => {
    if (event.target === bookingForm) {
        bookingForm.style.display = "none";
    }
});
