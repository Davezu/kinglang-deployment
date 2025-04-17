const messageModal = new bootstrap.Modal(document.getElementById("messageModal"), {
    backdrop: false,
    keyboard: true
});

const messageTitle = document.getElementById("messageTitle");
const messageBody = document.getElementById("messageBody");

const picker = flatpickr("#date_of_tour", {
    dateFormat: "Y-m-d",
    altInput: true,
    altFormat: "D, M j", 
    minDate: new Date(Date.now() + 3 * 24 * 60 * 60 * 1000),
    maxDate: new Date(new Date().setMonth(new Date().getMonth() + 1)),
  });



let isRebooking = false;

const bookingId = sessionStorage.getItem("bookingId") || 0;
sessionStorage.removeItem("bookingId");

if (bookingId > 0) isRebooking = !isRebooking;

document.addEventListener("DOMContentLoaded", async function () {
    if (!isRebooking) return;

    const data = await getBooking(bookingId);

    const booking = data.booking;
    const stops = data.stops;
    const locations = data.distances;

    console.log("Booking detail: ", booking);
    console.log("Stops detail: ", data.distances);

    document.getElementById("bookingHeader").textContent = "Rebook a Trip";
    document.getElementById("submitBooking").textContent = "Request Rebooking";

    if (stops.length > 0) {
        for (let i = 0; i < stops.length; i++) 
            document.getElementById("addStop").click();
    }

    const addressInputs = Array.from(document.querySelectorAll(".address"));

    locations.forEach((location, i) => {
        addressInputs[i].value = location.origin;
    });

    const date = new Date(booking.date_of_tour);

    const options = { weekday: 'short', month: 'short', day: 'numeric' };
    const formatted = date.toLocaleDateString('en-US', options);

    console.log(formatted);

    picker.setDate(booking.date_of_tour);
    
    // Set the number of days and buses
    const daysElement = document.getElementById("number_of_days");
    const busesElement = document.getElementById("number_of_buses");
    
    daysElement.textContent = booking.number_of_days;
    busesElement.textContent = booking.number_of_buses;
    
    // Update localStorage with the booking values
    localStorage.setItem("buses", booking.number_of_buses);
    localStorage.setItem("days", booking.number_of_days);
    
    // Calculate route and total cost
    calculateRoute();
    if (allInputsFilled()) {
        renderTotalCost();
    }
});


async function getBooking(bookingId) {
    try {
        const response = await fetch("/get-booking", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ bookingId })
        });

        const data = await response.json();

        if (data.success) {
            return data;
        } else {
            return [];
        }
    } catch (error) {
        console.error(error);
    }
}














document.addEventListener("DOMContentLoaded", initMap);

document.addEventListener("DOMContentLoaded", () => {
    const decBusesButton = document.getElementById("decreaseBuses");
    const incBusesButton = document.getElementById("increaseBuses");
    const incDaysButton = document.getElementById("increaseDays");
    const decDaysButton = document.getElementById("decreaseDays");

    const days = document.getElementById("number_of_days");
    const buses = document.getElementById("number_of_buses");

    // Initialize with values from localStorage or default to 0
    let bus = parseInt(localStorage.getItem("buses")) || 0;
    let day = parseInt(localStorage.getItem("days")) || 0;
    
    // Set initial display values
    days.textContent = day;
    buses.textContent = bus;
    
    // If we're rebooking, get the values from the booking data
    if (isRebooking) {
        const bookingDays = parseInt(document.getElementById("number_of_days").textContent);
        const bookingBuses = parseInt(document.getElementById("number_of_buses").textContent);
        
        if (!isNaN(bookingDays) && bookingDays > 0) {
            day = bookingDays;
            days.textContent = day;
            localStorage.setItem("days", day);
        }
        
        if (!isNaN(bookingBuses) && bookingBuses > 0) {
            bus = bookingBuses;
            buses.textContent = bus;
            localStorage.setItem("buses", bus);
        }
    }

    // Decrease buses button
    decBusesButton.addEventListener("click", () => {
        if (bus <= 0) return;
        bus--;
        buses.textContent = bus;
        localStorage.setItem("buses", bus);
        if (allInputsFilled()) {
            renderTotalCost();
        }
    });
    
    // Increase buses button
    incBusesButton.addEventListener("click", () => {
        if (bus >= 13) return;
        bus++;
        buses.textContent = bus;
        localStorage.setItem("buses", bus);
        if (allInputsFilled()) {
            renderTotalCost();
        }
    });
    
    // Decrease days button
    decDaysButton.addEventListener("click", () => {
        if (day <= 0) return;
        day--;
        days.textContent = day;
        localStorage.setItem("days", day);
        if (allInputsFilled()) {
            renderTotalCost();
        }
    });
    
    // Increase days button
    incDaysButton.addEventListener("click", () => {
        day++;
        days.textContent = day;
        localStorage.setItem("days", day);
        if (allInputsFilled()) {
            renderTotalCost();
        }
    });
});

document.getElementById("nextButton").addEventListener("click", function () {
    document.getElementById("firstInfo").classList.add("d-none");
    document.getElementById("nextInfo").classList.remove("d-none");
});

document.getElementById("back").addEventListener("click", function () {
    document.getElementById("firstInfo").classList.remove("d-none");
    document.getElementById("nextInfo").classList.add("d-none");
});




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

    const tripDistances = await getTripDistances();
    console.log("Trip Distances: ", tripDistances);

    const addressInputs = document.querySelectorAll(".address");
    const addresses = Array.from(addressInputs).map(input => input.value.trim()).filter(Boolean);

    const totalCost = await getTotalCost();
    console.log("Total Cost: ", totalCost);
    if (!totalCost || totalCost === 0) return;
    
    const formData = {
        dateOfTour: document.getElementById("date_of_tour")?.value,
        destination: destination,
        pickupPoint: document.getElementById("pickup_point")?.value,
        stops: stops,
        numberOfBuses: document.getElementById("number_of_buses")?.textContent,
        numberOfDays: document.getElementById("number_of_days")?.textContent,
        totalCost: totalCost,
        balance: totalCost,
        tripDistances: tripDistances,
        addresses: addresses,
        isRebooking: isRebooking,
        rebookingId: bookingId
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
            messageTitle.textContent = "Success";
            messageBody.textContent = data.message;
            messageModal.show();
            
            // Clear form data
            this.reset(); 
            document.getElementById("totalCost").textContent = "";
            document.getElementById("number_of_buses").textContent = "0";
            document.getElementById("number_of_days").textContent = "0";
            
            // Clear localStorage
            localStorage.removeItem("buses");
            localStorage.removeItem("days");
            
            // Redirect to My Bookings page after a short delay
            setTimeout(() => {
                window.location.href = "/home/booking-requests";
            }, 2000); // 2 second delay to allow the user to see the success message
        } else {
            messageTitle.textContent = "Error";
            messageBody.textContent = data.message;
            messageModal.show();
        }
    } catch (error) {
        console.error("Error fetching data: ", error.message);
        messageTitle.textContent = "Error";
        messageBody.textContent = "An error occurred while processing your request. Please try again.";
        messageModal.show();
    }

    initMap(); 
});

// Array.from(document.getElementsByTagName("input")).forEach(input => {
//     input.addEventListener("change", renderTotalCost);
// });

async function getTripDistances() {
    const addressInputs = document.querySelectorAll(".address");
    const stops = Array.from(addressInputs).map(input => input.value.trim()).filter(Boolean);

    try {
        const response = await fetch("/get-distance", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ stops })
        });

        const data = await response.json();

        if (data.status === "OK") {
            return data;
        }
    } catch (error) {
        console.error(error);
    }
}

async function renderTotalCost() {
    if (!allInputsFilled()) return;
    
    const costElement = document.getElementById("totalCost");
    costElement.textContent = "Calculating...";

    const totalCost = await getTotalCost();
    if (!totalCost) {
        costElement.textContent = "Unable to get total cost.";
        return;
    };

    costElement.textContent = "Estimated total cost: " + totalCost.toLocaleString("en-US", { style: "currency", currency: "PHP" });
}



function debounce(func, delay) {
    let timeout;
    return function (...args) {
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(this, args), delay);
    };
}

// Increase debounce delay for better performance
const debouncedGetAddress = debounce(getAddress, 800);

// Create a cache for address suggestions
const addressCache = new Map();

document.querySelectorAll(".address").forEach(input => {
    input.addEventListener("input", function (e) {
        const suggestionList = e.target.nextElementSibling;
        const input = this.value;
        const inputElement = this;
    
        // Only search if input is at least 3 characters
        if (input.length >= 3) {
            debouncedGetAddress(input, suggestionList, inputElement);
        } else {
            suggestionList.innerHTML = "";
            suggestionList.style.border = "none";
        }
    });     
});

function allInputsFilled() {
    const pickupPoint = document.getElementById("pickup_point").value.trim();
    const destinationInputs = document.querySelectorAll(".address");
    const destination = destinationInputs[destinationInputs.length - 1].value.trim();
    const dateOfTour = document.getElementById("date_of_tour").value.trim();
    const numberOfDays = document.getElementById("number_of_days").textContent;
    const numberOfBuses = document.getElementById("number_of_buses").textContent;
    
    // Check if all required fields are filled
    return pickupPoint !== "" && 
           destination !== "" && 
           dateOfTour !== "" && 
           parseInt(numberOfDays) > 0 && 
           parseInt(numberOfBuses) > 0;
}

async function getAddress(input, suggestionList, inputElement) {
    // Check if we have cached results for this input
    if (addressCache.has(input)) {
        displaySuggestions(addressCache.get(input), suggestionList, inputElement);
        return;
    }
    
    try {
        const response = await fetch("/get-address", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ address: input })
        });

        const data = await response.json();
        
        // Cache the results
        addressCache.set(input, data);
        
        displaySuggestions(data, suggestionList, inputElement);
    } catch (error) {
        console.error(error);
    }
}

function displaySuggestions(data, suggestionList, inputElement) {
    suggestionList.innerHTML = "";
    suggestionList.style.border = "1px solid #ccc"; 
    
    if (data.status !== "OK") {
        const list = document.createElement("li");
        list.textContent = "No places found.";
        suggestionList.appendChild(list);
        return;
    }

    // Limit to top 5 results for better performance
    const topResults = data.predictions.slice(0, 5);
    
    topResults.forEach(place => {
        const list = document.createElement("li");
        
        // Create a container for the suggestion
        const suggestionContainer = document.createElement("div");
        suggestionContainer.className = "suggestion-item";
        
        // Add an icon based on the place type
        const icon = document.createElement("i");
        icon.className = getPlaceTypeIcon(place.types);
        suggestionContainer.appendChild(icon);
        
        // Add the main text
        const mainText = document.createElement("span");
        mainText.className = "main-text";
        mainText.textContent = place.structured_formatting?.main_text || place.description.split(',')[0];
        suggestionContainer.appendChild(mainText);
        
        // Add the secondary text if available
        if (place.structured_formatting?.secondary_text) {
            const secondaryText = document.createElement("span");
            secondaryText.className = "secondary-text";
            secondaryText.textContent = place.structured_formatting.secondary_text;
            suggestionContainer.appendChild(secondaryText);
        } else {
            // If no structured formatting, use the rest of the description
            const parts = place.description.split(',');
            if (parts.length > 1) {
                const secondaryText = document.createElement("span");
                secondaryText.className = "secondary-text";
                secondaryText.textContent = parts.slice(1).join(',').trim();
                suggestionContainer.appendChild(secondaryText);
            }
        }
        
        list.appendChild(suggestionContainer);

        list.addEventListener("click", function () {
            inputElement.value = place.description; 
            calculateRoute();
            suggestionList.innerHTML = "";
            suggestionList.style.border = "none";
        });

        document.addEventListener("click", function (e) {
            if (e.target !== list && !list.contains(e.target)) {
                suggestionList.innerHTML = "";
                suggestionList.style.border = "none";
            }
        });

        suggestionList.appendChild(list);
    });
}

// Helper function to determine the appropriate icon based on place type
function getPlaceTypeIcon(types) {
    if (!types || types.length === 0) return "bi bi-geo-alt";
    
    // Check for specific place types and return appropriate icon
    if (types.includes("establishment") || types.includes("point_of_interest")) {
        return "bi bi-building";
    } else if (types.includes("route") || types.includes("street_address")) {
        return "bi bi-signpost-split";
    } else if (types.includes("locality") || types.includes("sublocality")) {
        return "bi bi-geo";
    } else if (types.includes("park") || types.includes("natural_feature")) {
        return "bi bi-tree";
    } else if (types.includes("transit_station") || types.includes("bus_station")) {
        return "bi bi-bus-front";
    } else if (types.includes("restaurant") || types.includes("food")) {
        return "bi bi-cup-hot";
    } else if (types.includes("lodging") || types.includes("hotel")) {
        return "bi bi-house-door";
    } else {
        return "bi bi-geo-alt";
    }
}

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

    const numberOfDays = document.getElementById("number_of_days").textContent;
    const numberOfBuses = document.getElementById("number_of_buses").textContent;

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
        } else {
            console.error(data.message);
        }
    } catch (error) {
        console.error(error);
    }
}

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

    // Check if pickup and destination are filled
    if (!pickupPoint || !destination) {
        // Show a notification if either pickup or destination is missing
        // messageTitle.textContent = "Missing Information";
        // messageBody.textContent = "Please enter both pickup and destination locations to calculate the route.";
        // messageModal.show();
        return;
    }

    // Show loading indicator
    const mapElement = document.getElementById("map");
    mapElement.innerHTML = '<div class="d-flex justify-content-center align-items-center h-100"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>';

    try {
        const response = await fetch("/get-route", {
            method: "POST", 
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ pickupPoint, destination, stops })
        });
    
        const data = await response.json();
        
        // Reinitialize the map
        initMap();
        
        if (data.error) {
            console.error(data.error);
            // Show error notification
            messageTitle.textContent = "Route Calculation Error";
            messageBody.textContent = "Unable to calculate the route. Please check your locations and try again.";
            messageModal.show();
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
                
                // Show success notification with route details
                const route = result.routes[0];
                const distance = route.legs.reduce((total, leg) => total + leg.distance.value, 0) / 1000; // in km
                const duration = route.legs.reduce((total, leg) => total + leg.duration.value, 0) / 60; // in minutes
                
                // Only show notification if the route is significantly different from previous calculations
                if (window.lastCalculatedDistance && Math.abs(window.lastCalculatedDistance - distance) < 1) {
                    return; // Skip notification if distance is similar to previous calculation
                }
                
                window.lastCalculatedDistance = distance;
                
                // messageTitle.textContent = "Route Calculated";
                // messageBody.textContent = `Route found! Total distance: ${distance.toFixed(1)} km, estimated time: ${Math.round(duration)} minutes.`;
                // messageModal.show();
            } else {
                console.error("Directions request failed due to " + status);
                
                // Show specific error message based on status
                let errorMessage = "Unable to calculate the route. ";
                
                switch (status) {
                    case google.maps.DirectionsStatus.NOT_FOUND:
                        errorMessage += "One or more locations could not be found.";
                        break;
                    case google.maps.DirectionsStatus.ZERO_RESULTS:
                        errorMessage += "No route could be found between the specified locations.";
                        break;
                    case google.maps.DirectionsStatus.MAX_WAYPOINTS_EXCEEDED:
                        errorMessage += "Too many waypoints. Please reduce the number of stops.";
                        break;
                    default:
                        errorMessage += "Please check your locations and try again.";
                }
                
                messageTitle.textContent = "Route Calculation Failed";
                messageBody.textContent = errorMessage;
                messageModal.show();
            }
        });
    } catch (error) {
        console.error("Error fetching route: ", error.message);
        
        // Reinitialize the map
        initMap();
        
        // Show error notification
        messageTitle.textContent = "Connection Error";
        messageBody.textContent = "Unable to connect to the route service. Please check your internet connection and try again.";
        messageModal.show();
        return;
    }   
}




// add stop
let position = 3, count = 0;
document.getElementById("addStop").addEventListener("click", () => {
    count++;
    document.getElementById("destination").placeholder = "Add a stop";

    const form = document.getElementById("firstInfo");
    const div = document.createElement("div");
    const input = document.createElement("input");
    const ul = document.createElement("ul");

    div.classList.add("mb-3", "position-relative");
    input.id = "destination";
    input.placeholder = "Add a stop";
    input.autocomplete = "off";
    input.classList.add("form-control", "address", "added-stop", "position-relative", "px-4", "py-2", "destination");
    ul.classList.add("suggestions");

    input.addEventListener("input", function (e) {
        const suggestionList = e.target.nextElementSibling;
        const input = this.value;
        const inputElement = this;
    
        debouncedGetAddress(input, suggestionList, inputElement);    
    });

    input.addEventListener("change", async function () {
        if (!allInputsFilled()) return;
        
        const debouncedRenderTotalCost = debounce(renderTotalCost, 500);
        debouncedRenderTotalCost();
    });

    const locationIcon = document.createElement("i");
    locationIcon.classList.add("bi", "bi-geo-alt-fill", "location-icon")

    const removeButton = document.createElement("i");
    removeButton.classList.add("bi", "bi-x-circle-fill", "remove-icon");
    removeButton.title = "Remove stop";

    removeButton.addEventListener("click", function () {
        div.remove();
        if (input.value.length > 5) {
            calculateRoute();
        }
        position--;
        count--;
        if (count === 0) document.getElementById("destination").placeholder = "Dropoff Location";
    });
    
    div.append(locationIcon, removeButton);

    const referenceElement = form.children[position];
    position++;

    div.append(input, ul);
    form.insertBefore(div, referenceElement);
});

// Add event listeners to all address inputs to check for total cost calculation
document.querySelectorAll(".address").forEach(input => {
    input.addEventListener("change", function() {
        if (allInputsFilled()) {
            renderTotalCost();
        }
    });
});

// Add event listener to date input
document.getElementById("date_of_tour").addEventListener("change", function() {
    if (allInputsFilled()) {
        renderTotalCost();
    }
});