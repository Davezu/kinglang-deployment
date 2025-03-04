console.log("Test");

const today = new Date();

today.setDate(today.getDate() + 3);

const minDate = today.toISOString().split("T")[0];

document.getElementById("date_of_tour").min = minDate;  