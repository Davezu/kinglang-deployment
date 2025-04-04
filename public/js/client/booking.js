const today = new Date();

today.setDate(today.getDate() + 3);
const minDate = today.toISOString().split("T")[0];
document.getElementById("date_of_tour").min = minDate;    
document.addEventListener("DOMContentLoaded", initMap);


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
    calculateRoute();
    
    console.log(distance);
});

function debounce(func, delay) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
    };
}

const debouncedGetAddress = debounce(getAddress, 500);

document.querySelectorAll(".address").forEach(input => {
    input.addEventListener("input", function (e) {
        const suggestionList = e.target.nextElementSibling;
        const input = this.value;
        const inputElement = this;
    
        debouncedGetAddress(input, suggestionList, inputElement);
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
                calculateRoute();
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

let map, directionsService, directionsRenderer;
let stops = [];

function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 14.5995, lng: 120.9842 },
        zoom: 10,
    });
    
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer();
    directionsRenderer.setMap(map);
}

async function calculateRoute() {
    const pickupPoint = document.getElementById("pickup_point").value;
    const destinationInputs = document.querySelectorAll(".address");
    const destination = destinationInputs[destinationInputs.length - 1].value;
    const stops = Array.from(document.querySelectorAll(".added-stop")).map((stop, i) => stop.value ).filter(stop => stop.trim() !== "");
    stops.pop();

    if (!pickupPoint || !destination) return;

    console.log("stops: ", stops);

    try {
        const response = await fetch("/get-route", {
            method: "POST", 
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ pickupPoint, destination, stops })
        });
    
        const data = await response.json();
        if (data.error) {
            console.error(data.error);
            return;
        }

        const waypoints = data.stops.map(stop => ({ location: stop, stopover: true }));
        const request = {
            origin: pickupPoint,
            destination: destination,
            waypoints: waypoints,
            travelMode: google.maps.TravelMode.DRIVING,
        };
    
        directionsService.route(request, (result, status) => {
            if (status === google.maps.DirectionsStatus.OK) {
                directionsRenderer.setDirections(result);
            }
            else {
                console.error("Directions request failed due to " + status);
            }
        });
    } catch (error) {
        console.error("Error fetching route: ", error.message);
        return;
    }   
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
    
        debouncedGetAddress(input, suggestionList, inputElement);    
    });

    const removeButton = document.createElement("span");
    removeButton.classList.add("remove-button");
    removeButton.textContent = "\u00d7";

    removeButton.addEventListener("click", function () {
        div.remove();
        calculateRoute();
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