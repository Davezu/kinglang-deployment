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

    // const numberOfBuses = document.getElementById("number_of_buses").value;
    // const selectedBuses = Array.from(document.querySelectorAll("input[name='bus_ids[]']:checked")).map(bus => bus.value);

    // if (parseInt(numberOfBuses) !== selectedBuses.length) return;
    
    const stops = Array.from(document.querySelectorAll(".added-stop")).map((stop, i) => stop.value).filter(stop => stop.trim() !== "");
    const destination = stops[stops.length - 1];
    stops.pop();

    const totalCost = await getTotalCost();
    console.log(totalCost);
    if (!totalCost || totalCost === 0) return;
    
    const formData = {
        dateOfTour: document.getElementById("date_of_tour")?.value,
        destination: destination,
        pickupPoint: document.getElementById("pickup_point")?.value,
        stops: stops,
        numberOfBuses: document.getElementById("number_of_buses")?.value,
        numberOfDays: document.getElementById("number_of_days")?.value,
        totalCost: totalCost,
        balance: totalCost
        // busIds: selectedBuses
    }

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

    initMap();  

    const costElement = document.getElementById("totalCost");
    costElement.textContent = "Estimated total cost: " + totalCost.toLocaleString("en-US", { style: "currency", currency: "PHP" });
});

Array.from(document.getElementsByTagName("input")).forEach(input => {
    input.addEventListener("change", renderTotalCost);
});

async function renderTotalCost() {
    if (!allInputsFilled()) return;

    const totalCost = await getTotalCost();
    if (!totalCost) return;

    const costElement = document.getElementById("totalCost");
    costElement.textContent = "Estimated total cost: " + totalCost.toLocaleString("en-US", { style: "currency", currency: "PHP" });
}



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

function allInputsFilled() {
    console.log("Checking if all inputs are filled...");
    const inputs = document.getElementsByTagName("input");
    const allInputsFilled = Array.from(inputs).every(input => {
        if (input.type === "text" || input.type === "number") {
            return input.value.trim() !== ""; // Check if the input is not empty
        }
        return true; // Ignore other types of inputs
    });
    return allInputsFilled;
}

async function getAddress(input, suggestionList, inputElement) {
    try {
        const response = await fetch("/get-address", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ address: input })
        });

        const data = await response.json();
        console.log(data);

        suggestionList.innerHTML = "";
        suggestionList.style.border = "1px solid #ccc"; 

        if (data.status !== "OK") {
            const list = document.createElement("li");
            console.log(data);
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

            suggestionList.appendChild(list);
        })
    } catch (error) {
        console.error(error);
    }
};

// async function getDistance(origin, destination) {
//     try {
//         const response = await fetch("/get-distance", {
//             method: "POST",
//             headers: { "Content-Type": "application/json" },
//             body: JSON.stringify({ origin, destination })
//         });

//         const data = await response.json();
//         console.log("distance: ", data)

//         if (data.status === "OK") {
//             const distance = data.rows[0].elements[0].distance.value;
//             return parseFloat(distance);
//         } else {
//             console.log(data);
//         }
//     } catch (error) {
//         console.error(error);
//     }
// }
// async function processDistance() {
//     let origin;
//     let distance = [];
//     const inputs = document.querySelectorAll(".address");
    
//     for (let i = 0; i < inputs.length; i++) {
//         if (i > 0) {
//             let dist = await getDistance(origin, inputs[i].value);
//             distance.push(dist);
//         }   
//         if (i === inputs.length - 1) {
//             let dist = await getDistance(inputs[i].value, inputs[0].value);
//             distance.push(dist);
//         }
//         origin = inputs[i].value; 
//     }
//     return distance;
// }

async function getDistanceMatrix(stops) {
    try {
        const response = await fetch("/get-distance", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ stops })
        });

        const data = await response.json();

        if (data.status === "OK") {
            let total = 0;
            for (let i = 0; i < data.rows.length; i++) {
                const element = data.rows[i].elements[i]; // diagonal contains the desired distances
                if (element.status === "OK") {
                    total += element.distance.value;
                }
            }
            return total; // in meters
        } else {
            console.error("Distance API error:", data.status);
        }
    } catch (error) {
        console.error("Fetch error:", error);
    }
    return 0;
}

async function getTotalCost() {
    const addressInputs = document.querySelectorAll(".address");
    const stops = Array.from(addressInputs).map(input => input.value.trim()).filter(Boolean);

    if (stops.length < 2) return;

    const totalDistanceInMeters = await getDistanceMatrix(stops);
    const distanceInKm = totalDistanceInMeters / 1000;

    console.log("Total distance in km: ", distanceInKm);

    const numberOfDays = document.getElementById("number_of_days").value;
    const numberOfBuses = document.getElementById("number_of_buses").value;

    if (!distanceInKm || !numberOfDays || !numberOfBuses) return;

    try {
        const response = await fetch("/get-total-cost", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ distance: distanceInKm, numberOfBuses, numberOfDays })
        });

        const data = await response.json();
        if (data.success) {
            return data.total_cost;
        }
    } catch (error) {
        console.error(error);
    }
}

// async function getTotalCost() {  
//     const distance = await processDistance();
//     const totalDistance = distance.reduce((acc, curr) => acc + curr, 0);
   
//     const distanceInKm = totalDistance / 1000;
//     const numberOfDays = document.getElementById("number_of_days").value;
//     const numberOfBuses = document.getElementById("number_of_buses").value;

//     console.log("Distance: ", distanceInKm);

//     if (!distanceInKm || !numberOfDays || !numberOfBuses) return;

//     try {
//         const response = await fetch("/get-total-cost", {
//             method: "POST",
//             headers: { "Content-Type": "application/json" },
//             body: JSON.stringify({ distance: distanceInKm, numberOfBuses, numberOfDays })
//         });

//         const data = await response.json();

//         if (data.success) {
//             return data.total_cost;
//         }
//     } catch (error) {
//         console.error(error);
//     }
// }

function initMap() {
    let map;
    const mapOptions = {
        center: { lat: 14.5995, lng: 120.9842 }, // Default center (e.g., Manila)
        zoom: 10,
        disableDefaultUI: true, // disable all controls
        zoomControl: true,
        fullscreenControl: false,
        streetViewControl: false,
        mapTypeControl: false,
        rotateControl: false
      };

    map = new google.maps.Map(document.getElementById("map"), mapOptions);
    
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({ map: map});
}


let directionsService, directionsRenderer;

async function calculateRoute() {

    const pickupPoint = document.getElementById("pickup_point").value;
    const destinationInputs = document.querySelectorAll(".address");
    const destination = destinationInputs[destinationInputs.length - 1].value;
    const stops = Array.from(document.querySelectorAll(".added-stop")).map((stop, i) => stop.value ).filter(stop => stop.trim() !== "");
    stops.pop();

    if (!pickupPoint || !destination) return;   

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
    input.autocomplete = "off";
    input.classList.add("form-control", "address", "added-stop", "position-relative", "pe-4", "destination");
    ul.classList.add("suggestions");

    input.addEventListener("input", function (e) {
        const suggestionList = e.target.nextElementSibling;
        const input = this.value;
        const inputElement = this;
    
        debouncedGetAddress(input, suggestionList, inputElement);    
    });

    input.addEventListener("change", async function () {
        console.log("Input changed?: ", this.value);
        if (!allInputsFilled()) return;

        const totalCost = await getTotalCost();
        console.log("Total cost: ", totalCost);
        if (!totalCost) return;
        const costElement = document.getElementById("totalCost");
        costElement.textContent = "Estimated total cost: " + totalCost.toLocaleString("en-US", { style: "currency", currency: "PHP" });
    });

    const removeButton = document.createElement("span");
    removeButton.classList.add("remove-button");
    removeButton.textContent = "\u00d7";

    removeButton.addEventListener("click", function () {
        div.remove();
        calculateRoute();
        renderTotalCost();
        position--;``
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