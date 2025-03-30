const today = new Date();

today.setDate(today.getDate() + 3);
const minDate = today.toISOString().split("T")[0];
document.getElementById("date_of_tour").min = minDate;      


// find available buses
// document.getElementById("date_of_tour").addEventListener("input", findAvailableBuses);
// document.getElementById("number_of_days").addEventListener("input", findAvailableBuses);

// async function findAvailableBuses() {
//     const tourDate = document.getElementById("date_of_tour").value;
//     const numDays = document.getElementById("number_of_days").value;
//     console.log(tourDate);
//     console.log(numDays);

//     if (tourDate.trim() === "" || numDays.trim() === "") return;

//     try {
//         const response = await fetch('/get-available-buses', {
//             method: 'POST',
//             headers: { 'Content-Type': 'application/json' },
//             body: JSON.stringify({ date_of_tour: tourDate, number_of_days: numDays })
//         });
    
//         const data = await response.json();
//         const busSelectionDiv = document.getElementById("busSelection");
    
//         if (data.success && data.buses.length > 0) {
//             console.log(data.buses);
//             let options = `<label for="bus_id">Choose a Bus:</label><br>`;
//             data.buses.forEach(bus => {
//                 options += `<input type="checkbox" name="bus_ids[]" value="${bus.bus_id}">${bus.bus_name} - ${bus.capacity} seats <br>`;
//             });
//             options += `</select>`;
//             busSelectionDiv.innerHTML = options;
//         } else {
//             busSelectionDiv.innerHTML = `No available buses for the selected date.`;
//         }
//     } catch (error) {
//         console.error("Error fetching data: ", error.message);
//     }
// }

// submit booking 
document.getElementById("bookingForm").addEventListener("submit", async function (e) {
    e.preventDefault(); 

    console.log("test");

    // const numberOfBuses = document.getElementById("number_of_buses").value;
    // const selectedBuses = Array.from(document.querySelectorAll("input[name='bus_ids[]']:checked")).map(bus => bus.value);

    // console.log("selected buses: ", selectedBuses.length);
    // console.log("number of buses: ", numberOfBuses);

    // if (parseInt(numberOfBuses) !== selectedBuses.length) return;
    
    const formData = {
        dateOfTour: document.getElementById("date_of_tour")?.value,
        destination: document.getElementById("destination")?.value,
        pickupPoint: document.getElementById("pickup_point")?.value,
        numberOfBuses: document.getElementById("number_of_buses")?.value,
        numberOfDays: document.getElementById("number_of_days")?.value
        // busIds: selectedBuses
    }

    try {
        const response = await fetch("/request-booking", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            document.querySelector(".booking-message").textContent = data.message;
            this.reset();
            document.getElementById("busSelection").innerHTML = "";
        } else {
            document.querySelector(".booking-message").textContent = data.message;
        }
    } catch (error) {
        console.error("Error fetching data: ", error.message);
    }
});

document.querySelectorAll(".address").forEach(input => {
    input.addEventListener("input", function (e) {
        const suggestionList = e.target.nextElementSibling;
        const input = this.value;
        const inputElement = this;
    
        getAddress(input, suggestionList, inputElement);
    });     
});

async function getAddress(input, suggestionList, inputElement) {

    if (input.length < 3) {
        return;
    }

    try {
        const response = await fetch("/get-address", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ address: input })
        });

        const data = await response.json();

        if (!data.predictions) {
            console.log(data);
            return;
        }

        suggestionList.innerHTML = "";
        data.predictions.forEach(place => {
            const list = document.createElement("li");
            list.textContent = place.description;

            list.addEventListener("click", function () {
                inputElement.value = place.description;
                suggestionList.innerHTML = "";
                suggestionList.style.border = "none";
            });

            document.addEventListener("click", function (e) {
                if (e.target !== list) {
                    suggestionList.innerHTML = "";
                    suggestionList.style.border = "none";
                }
            });

            suggestionList.style.border = "1px solid #ccc"; 
            suggestionList.appendChild(list);
        })
    } catch (error) {
        console.error(error);
    }
};

document.getElementById("addStop").addEventListener("click", () => {
    const form = document.getElementById("bookingForm");
    const div = document.createElement("div");
    const input = document.createElement("input");
    const ul = document.createElement("ul");

    div.classList.add("mb-3", "position-relative");
    input.id = "destination";
    input.classList.add("form-control", "address");
    ul.classList.add("suggesstions");

    const referenceElement = form.children[3];

    div.append(input, ul);
    form.insertBefore(div, referenceElement);
});