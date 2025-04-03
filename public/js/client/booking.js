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
    
    // const formData = {
    //     dateOfTour: document.getElementById("date_of_tour")?.value,
    //     destination: document.getElementById("destination")?.value,
    //     pickupPoint: document.getElementById("pickup_point")?.value,
    //     numberOfBuses: document.getElementById("number_of_buses")?.value,
    //     numberOfDays: document.getElementById("number_of_days")?.value
    //     // busIds: selectedBuses
    // }

    // try {
    //     const response = await fetch("/request-booking", {
    //         method: "POST",
    //         headers: { "Content-Type": "application/json" },
    //         body: JSON.stringify(formData)
    //     });

    //     const data = await response.json();

    //     if (data.success) {
    //         document.querySelector(".booking-message").textContent = data.message;
    //         this.reset();
    //         document.getElementById("busSelection").innerHTML = "";
    //     } else {
    //         document.querySelector(".booking-message").textContent = data.message;
    //     }
    // } catch (error) {
    //     console.error("Error fetching data: ", error.message);
    // }

    let origin;
    let distance = [];

    const processDistance = async () => {
        const inputs = document.querySelectorAll(".address");
        
        for (let i = 0; i < inputs.length; i++) {
            if (i > 0) {
                let dist = await getDistance(origin, inputs[i].value);
                distance.push(parseFloat(dist));
            }   
            origin = inputs[i].value;
        }

    };
    processDistance();
    
    console.log(distance);
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
    try {
        const response = await fetch("/get-address", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ address: input })
        });

        const data = await response.json();

        suggestionList.innerHTML = "";
        if (data.predictions.length === 0) {
            console.log(data);
            const list = document.createElement("li");
            list.textContent = "No places found.";
            suggestionList.appendChild(list);
            return;
        }

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

async function getDistance(origin, destination) {
    try {
        const response = await fetch("/get-distance", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ origin, destination })
        });

        const data = await response.json();
        console.log(data);

        if (data.status === "OK") {
            const distance = data.rows[0].elements[0].distance.value;
            const duration = data.rows[0].elements[0].duration.text;
            console.log(`Distance: ${distance}, Duration: ${duration}`);
            return parseFloat(distance);
        } else {
            console.log(data);
        }
    } catch (error) {
        console.error(error);
    }
}

async function getTotalCost() {

}

function plotRoute() {
    const pickup = document.getElementById("pickup_point").value;
    const destination = document.getElementById("destination").value;
    const stopInputs = document.querySelectorAll(".added-stop");

    if (!pickup || !destination) {
        alert("Please enter pickup and destination.");
        return;
    }

    let waypoints = [];
    stopInputs.forEach(input => {
        if (input.value.trim() !== "") {
            waypoints.push(input.value.trim());
        }
    });

    const requestData = {
        pickup: pickup,
        waypoints: waypoints,
        destination: destination
    };

    fetch("backend.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(requestData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "OK") {
            alert(`Total Distance: ${data.total_distance}\nTotal Duration: ${data.total_duration}`);
            drawRoute(data.polyline);
        } else {
            alert("Error: " + data.message);
        }
    })
    .catch(error => console.error("Error:", error));
}

function drawRoute(encodedPolyline) {
    const decodedPath = google.maps.geometry.encoding.decodePath(encodedPolyline);
    const routeLine = new google.maps.Polyline({
        path: decodedPath,
        geodesic: true,
        strokeColor: "#FF0000",
        strokeOpacity: 1.0,
        strokeWeight: 4
    });

    routeLine.setMap(map);
}



// add stop
let position = 3;
document.getElementById("addStop").addEventListener("click", () => {
    const form = document.getElementById("bookingForm");
    const div = document.createElement("div");
    const input = document.createElement("input");
    const ul = document.createElement("ul");

    div.classList.add("mb-3", "position-relative");
    input.id = "destination";
    input.classList.add("form-control", "address", "added-stop", "position-relative", "pe-4", "destination");
    ul.classList.add("suggestions");

    input.addEventListener("input", function (e) {
        const suggestionList = e.target.nextElementSibling;
        const input = this.value;
        const inputElement = this;
    
        getAddress(input, suggestionList, inputElement);    
    });

    const removeButton = document.createElement("span");
    removeButton.classList.add("remove-button");
    removeButton.textContent = "\u00d7";

    removeButton.addEventListener("click", function () {
        div.remove();
        position--;
    });
    
    div.appendChild(removeButton);

    const referenceElement = form.children[position];
    position++;

    div.append(input, ul);
    form.insertBefore(div, referenceElement);
});

function removeInput(divElement) {
    divElement.remove();
}