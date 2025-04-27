let isRebooking = false;

const bookingId = sessionStorage.getItem("bookingId") || 0;
sessionStorage.removeItem("bookingId");

if (bookingId > 0) isRebooking = !isRebooking;

document.addEventListener("DOMContentLoaded", async function () {
    const picker = flatpickr("#date_of_tour", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "D, M j", 
        minDate: new Date(Date.now() + 3 * 24 * 60 * 60 * 1000),
        maxDate: new Date(new Date().setMonth(new Date().getMonth() + 1)),
    });

    // Initialize all remove icons on page load
    document.querySelectorAll(".address").forEach(input => {
        updateRemoveIconPosition(input);
    });

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

    const date_of_tour = new Date(booking.date_of_tour);

    picker.setDate(date_of_tour);

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

// Initialize days and buses counters
document.addEventListener("DOMContentLoaded", function() {
    const daysElement = document.getElementById("number_of_days");
    const busesElement = document.getElementById("number_of_buses");
    
    if (!isRebooking) {
        // Always set to zero for new bookings
        daysElement.textContent = "0";
        busesElement.textContent = "0";
        
        // Clear any previous values in localStorage
        localStorage.removeItem("days");
        localStorage.removeItem("buses");
    }
    
    // Add event listeners for days and buses counter buttons
    document.getElementById("increaseDays").addEventListener("click", function() {
        const currentDays = parseInt(daysElement.textContent);
        const newDays = currentDays + 1;
        daysElement.textContent = newDays;
        localStorage.setItem("days", newDays);
        if (allInputsFilled()) {
            renderTotalCost();
        }
    });
    
    document.getElementById("decreaseDays").addEventListener("click", function() {
        const currentDays = parseInt(daysElement.textContent);
        if (currentDays > 0) {
            const newDays = currentDays - 1;
            daysElement.textContent = newDays;
            localStorage.setItem("days", newDays);
            if (allInputsFilled()) {
                renderTotalCost();
            }
        }
    });
    
    document.getElementById("increaseBuses").addEventListener("click", function() {
        const currentBuses = parseInt(busesElement.textContent);
        const newBuses = currentBuses + 1;
        busesElement.textContent = newBuses;
        localStorage.setItem("buses", newBuses);
        if (allInputsFilled()) {
            renderTotalCost();
        }
    });
    
    document.getElementById("decreaseBuses").addEventListener("click", function() {
        const currentBuses = parseInt(busesElement.textContent);
        if (currentBuses > 0) {
            const newBuses = currentBuses - 1;
            busesElement.textContent = newBuses;
            localStorage.setItem("buses", newBuses);
            if (allInputsFilled()) {
                renderTotalCost();
            }
        }
    });
});

document.getElementById("nextButton").addEventListener("click", function () {
    // Get all address inputs
    const addressInputs = document.querySelectorAll(".address");
    
    // Check if all inputs are filled AND validated
    let allValid = true;
    let invalidInputs = [];
    
    addressInputs.forEach(input => {
        if (input.value.trim() === "") {
            input.classList.add("is-invalid");
            allValid = false;
            invalidInputs.push("empty fields");
        } else if (input.dataset.validated !== "true") {
            input.classList.add("is-invalid");
            allValid = false;
            invalidInputs.push("unverified locations");
        } else {
            input.classList.add("is-valid");
            input.classList.remove("is-invalid");
        }
    });
    
    if (!allValid) {
        // Show detailed error message
        let errorMessage = "Please fix the following issues: " + [...new Set(invalidInputs)].join(", ");
        
        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            text: errorMessage,
            timer: 3000,
            timerProgressBar: true
        });
        return;
    }
    
    // If all addresses are valid, proceed to next step
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
    
    // Get the cost breakdown (if available)
    const costBreakdown = window.costBreakdown || {};
    
    const formData = {
        dateOfTour: document.getElementById("date_of_tour")?.value,
        destination: destination,
        pickupPoint: document.getElementById("pickup_point")?.value,
        pickupTime: document.getElementById("pickup_time")?.value,
        stops: stops,
        numberOfBuses: document.getElementById("number_of_buses")?.textContent,
        numberOfDays: document.getElementById("number_of_days")?.textContent,
        totalCost: totalCost,
        balance: totalCost,
        tripDistances: tripDistances,
        addresses: addresses,
        isRebooking: isRebooking,
        rebookingId: bookingId,

        baseCost: costBreakdown.baseCost || null,
        dieselCost: costBreakdown.dieselCost || null,
        baseRate: costBreakdown.baseRate || null,
        dieselPrice: costBreakdown.dieselPrice || null,
        totalDistance: costBreakdown.totalDistance || null
    }

    console.log("Total distance: ", costBreakdown.totalDistance);

    try {
        const response = await fetch("/request-booking", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            // Always clear localStorage values regardless of booking success
            localStorage.removeItem("days");
            localStorage.removeItem("buses");
            
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                timer: 2000,
                timerProgressBar: true
            });
            
            // Clear form data
            this.reset(); 
            document.getElementById("totalCost").textContent = "";
            document.getElementById("number_of_days").textContent = "0";
            document.getElementById("number_of_buses").textContent = "0";
            
            // Redirect to My Bookings page after a short delay
            setTimeout(() => {
                window.location.href = "/home/booking-requests";
            }, 2000); // 2 second delay to allow the user to see the success message
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                timer: 2000,
                timerProgressBar: true
            });
        }
    } catch (error) {
        console.error("Error fetching data: ", error.message);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An error occurred while processing your request. Please try again.',
            timer: 2000,
            timerProgressBar: true
        });
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

    // Get the cost breakdown details
    const costBreakdown = window.costBreakdown || {};
    console.log("Cost breakdown: ", costBreakdown);
    
    // Format the total cost
    const formattedTotal = totalCost.toLocaleString("en-US", { style: "currency", currency: "PHP" });
    
    // Create breakdown HTML
    let costHTML = `<div>Estimated total cost: <strong>${formattedTotal}</strong></div>`;
    
    // Add region info if available
    if (costBreakdown.region) {
        costHTML += `<div class="small text-muted mt-2">
            <div>Rate region: ${costBreakdown.region} (highest rate used)</div>
            <div>Base rate: ${costBreakdown.baseRate?.toLocaleString("en-US", { style: "currency", currency: "PHP" })} per bus</div>
            <div>Base cost: ${costBreakdown.baseCost?.toLocaleString("en-US", { style: "currency", currency: "PHP" })}</div>
            <div>Diesel cost: ${costBreakdown.dieselCost?.toLocaleString("en-US", { style: "currency", currency: "PHP" })}</div>
        </div>`;
    }
    
    costElement.innerHTML = costHTML;
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
    const pickupPoint = document.getElementById("pickup_point");
    const destinationInputs = document.querySelectorAll(".address");
    const destination = destinationInputs[destinationInputs.length - 1];
    const numberOfDays = document.getElementById("number_of_days").textContent;
    const numberOfBuses = document.getElementById("number_of_buses").textContent;
    
    // Check if all required fields are filled AND validated
    return pickupPoint.value.trim() !== "" && 
           pickupPoint.dataset.validated === "true" &&
           destination.value.trim() !== "" && 
           destination.dataset.validated === "true" &&
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

// Add a function to manage icon visibility and positioning
function updateRemoveIconPosition(inputElement) {
    if (!inputElement) return;
    
    // Find the closest remove icon
    const removeIcon = inputElement.parentNode.querySelector('.remove-icon');
    if (removeIcon) {
        // If input is validated (valid or invalid), position the remove icon correctly
        if (inputElement.classList.contains('is-valid') || inputElement.classList.contains('is-invalid')) {
            removeIcon.style.right = '30px';
        } else {
            removeIcon.style.right = '8px';
        }
    }
    
    // Also update the add icon if present
    const addIcon = inputElement.parentNode.querySelector('.add-icon');
    if (addIcon) {
        // If input is validated, position the add icon correctly
        if (inputElement.classList.contains('is-valid') || inputElement.classList.contains('is-invalid')) {
            addIcon.style.right = '30px';
        } else {
            addIcon.style.right = '8px';
        }
    }
}

// Update the displaySuggestions function
function displaySuggestions(data, suggestionList, inputElement) {
    suggestionList.innerHTML = "";
    suggestionList.style.border = "1px solid #ccc"; 
    
    if (data.status !== "OK" || data.predictions.length === 0) {
        const list = document.createElement("li");
        list.textContent = "No places found.";
        list.className = "no-results";
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
            inputElement.dataset.validated = "true"; // Mark as validated
            inputElement.classList.add("is-valid");
            inputElement.classList.remove("is-invalid");
            
            // Update the position of the remove icon
            updateRemoveIconPosition(inputElement);
            
            calculateRoute();
            suggestionList.innerHTML = "";
            suggestionList.style.border = "none";
            
            // Check if we should calculate total cost
            if (allInputsFilled()) {
                renderTotalCost();
            }
        });

        document.addEventListener("click", function (e) {
            if (e.target !== list && !list.contains(e.target) && e.target !== inputElement) {
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

    // The first address is the pickup point, the last is the destination
    const pickupPoint = document.getElementById("pickup_point").value;
    const destination = stops[stops.length - 1];

    const totalDistanceInMeters = await getDistanceMatrix(stops);
    const distanceInKm = totalDistanceInMeters / 1000;

    const numberOfDays = document.getElementById("number_of_days").textContent;
    const numberOfBuses = document.getElementById("number_of_buses").textContent;

    if (!distanceInKm || !numberOfDays || !numberOfBuses) return;

    try {
        const response = await fetch("/get-total-cost", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ 
                distance: distanceInKm, 
                numberOfBuses, 
                numberOfDays,
                locations: stops,
                destination: destination,
                pickupPoint: pickupPoint
            })
        });

        const data = await response.json();
        
        if (data.success) {
            // Store cost breakdown for display
            window.costBreakdown = {
                region: data.region,
                baseRate: data.base_rate,
                baseCost: data.base_cost,
                dieselPrice: data.diesel_price,
                dieselCost: data.diesel_cost,
                locationRegions: data.location_regions,
                totalDistance: distanceInKm
            };
            
            // Log additional details for debugging
            // console.log("Cost breakdown:", window.costBreakdown);
            
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

    // Validate if we have both pickup and destination
    if (!pickupPoint || !destination) {
        return; // Not enough data to calculate route
    }

    // Show loading indicator on the map
    const mapElement = document.getElementById("map");
    if (!mapElement.querySelector('.route-loading')) {
        mapElement.innerHTML = `
            <div class="d-flex flex-column justify-content-center align-items-center h-100 route-loading">
                <div class="spinner-border text-success mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-center">Calculating best route...</p>
            </div>`;
    }
    
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
            Swal.fire({
                icon: 'error',
                title: 'Route Calculation Error',
                text: 'Unable to calculate the route. Please check your locations and try again.',
                timer: 3000,
                timerProgressBar: true
            });
            
            // Mark all address inputs as invalid
            document.querySelectorAll(".address").forEach(input => {
                input.dataset.validated = "false";
                input.classList.add("is-invalid");
            });
            
            return;
        }

        // Mark all inputs as validated if we got here (successful route)
        document.querySelectorAll(".address").forEach(input => {
            if (input.value.trim() !== "") {
                input.dataset.validated = "true";
                input.classList.add("is-valid");
                input.classList.remove("is-invalid");
            }
        });

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
                
                // Extract route information for display
                const route = result.routes[0];
                const distance = route.legs.reduce((total, leg) => total + leg.distance.value, 0) / 1000; // in km
                const duration = route.legs.reduce((total, leg) => total + leg.duration.value, 0) / 60; // in minutes
                
                // Format the duration for display
                let durationText = '';
                if (duration >= 60) {
                    const hours = Math.floor(duration / 60);
                    const mins = Math.round(duration % 60);
                    durationText = `${hours} hr${hours > 1 ? 's' : ''} ${mins} min`;
                } else {
                    durationText = `${Math.round(duration)} min`;
                }
                
                // Add route info to the map
                const routeInfoDiv = document.createElement('div');
                routeInfoDiv.className = 'route-info bg-light p-2 rounded position-absolute';
                routeInfoDiv.style.bottom = '10px';
                routeInfoDiv.style.left = '10px';
                routeInfoDiv.style.zIndex = '1000';
                routeInfoDiv.innerHTML = `
                    <div class="small">
                        <div class="fw-bold text-success mb-1">Route Details</div>
                        <div>Distance: ${distance.toFixed(1)} km</div>
                        <div>Est. travel time: ${durationText}</div>
                    </div>
                `;
                
                // Remove any existing route info
                const existingRouteInfo = document.querySelector('.route-info');
                if (existingRouteInfo) {
                    existingRouteInfo.remove();
                }
                
                // Add the route info to the map
                document.getElementById('map').appendChild(routeInfoDiv);
                
                // Only show notification if this is a significant route change
                if (window.lastCalculatedDistance && Math.abs(window.lastCalculatedDistance - distance) < 1) {
                    return; // Skip notification for minor changes
                }
                
                window.lastCalculatedDistance = distance;
            } else {
                console.error("Directions request failed due to " + status);
                
                // Mark inputs as invalid
                document.querySelectorAll(".address").forEach(input => {
                    input.dataset.validated = "false";
                    input.classList.add("is-invalid");
                });
                
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
                
                Swal.fire({
                    icon: 'error',
                    title: 'Route Calculation Failed',
                    text: errorMessage,
                    timer: 3000,
                    timerProgressBar: true
                });
            }
        });
    } catch (error) {
        console.error("Error fetching route: ", error.message);
        
        // Reinitialize the map
        initMap();
        
        // Mark inputs as invalid on connection error
        document.querySelectorAll(".address").forEach(input => {
            input.dataset.validated = "false";
            input.classList.add("is-invalid");
        });
        
        // Show error notification
        Swal.fire({
            icon: 'error',
            title: 'Connection Error',
            text: 'Unable to connect to the route service. Please check your internet connection and try again.',
            timer: 3000,
            timerProgressBar: true
        });
        return;
    }   

    // After marking all inputs as valid or invalid, update remove icon positions
    document.querySelectorAll(".address").forEach(input => {
        updateRemoveIconPosition(input);
    });
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
    input.dataset.validated = "false"; // Add validation attribute
    input.classList.add("form-control", "address", "added-stop", "position-relative", "px-4", "py-2", "destination");
    ul.classList.add("suggestions");

    input.addEventListener("input", function (e) {
        const suggestionList = e.target.nextElementSibling;
        const input = this.value;
        const inputElement = this;
        
        // Reset validation state when input changes
        this.dataset.validated = "false";
        this.classList.remove("is-valid");
        this.classList.remove("is-invalid");
        
        // Update remove icon position
        updateRemoveIconPosition(this);
    
        debouncedGetAddress(input, suggestionList, inputElement);    
    });
    
    // Add blur validation
    input.addEventListener("blur", function() {
        if (this.value.trim() !== "" && this.dataset.validated !== "true") {
            validateManualAddress(this);
        }
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
    removeButton.style.right = "8px"; // Initialize position

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
    
    // Initialize remove icon position
    updateRemoveIconPosition(input);
});

// Add event listeners to all address inputs to check for total cost calculation
document.querySelectorAll(".address").forEach(input => {
    input.addEventListener("change", function() {
        if (allInputsFilled()) {
            renderTotalCost();
        }
    });
});

// Add validation for manual input (when user doesn't select from dropdown)
document.querySelectorAll(".address").forEach(input => {
    // On focus out, validate if the input has a value but wasn't selected from dropdown
    input.addEventListener("blur", function() {
        if (this.value.trim() !== "" && this.dataset.validated !== "true") {
            validateManualAddress(this);
        }
    });
    
    // Reset validation state when input changes
    input.addEventListener("input", function() {
        this.dataset.validated = "false";
        this.classList.remove("is-valid");
        this.classList.remove("is-invalid");
        
        // Reset remove icon position
        updateRemoveIconPosition(this);
    });
});

// Update the validateManualAddress function
async function validateManualAddress(inputElement) {
    const address = inputElement.value.trim();
    
    if (address === "") return;
    
    try {
        // Show loading indicator
        const loadingIcon = document.createElement("span");
        loadingIcon.className = "spinner-border spinner-border-sm validation-spinner";
        loadingIcon.setAttribute("role", "status");
        inputElement.parentNode.appendChild(loadingIcon);
        
        const response = await fetch("/get-address", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ address: address })
        });

        const data = await response.json();
        
        // Remove loading indicator
        const spinner = inputElement.parentNode.querySelector(".validation-spinner");
        if (spinner) spinner.remove();
        
        if (data.status === "OK" && data.predictions.length > 0) {
            // Use the first prediction as the validated address
            inputElement.value = data.predictions[0].description;
            inputElement.dataset.validated = "true";
            inputElement.classList.add("is-valid");
            inputElement.classList.remove("is-invalid");
            
            // Update the position of the remove icon
            updateRemoveIconPosition(inputElement);
            
            // Calculate route with the validated address
            calculateRoute();
            
            // Check if we should calculate total cost
            if (allInputsFilled()) {
                renderTotalCost();
            }
        } else {
            // Mark as invalid
            inputElement.dataset.validated = "false";
            inputElement.classList.add("is-invalid");
            
            // Update the position of the remove icon
            updateRemoveIconPosition(inputElement);
            
            // Show small feedback message near the input
            const existingFeedback = inputElement.parentNode.querySelector(".invalid-feedback");
            if (!existingFeedback) {
                const feedback = document.createElement("div");
                feedback.className = "invalid-feedback";
                feedback.textContent = "Please select a valid location from the suggestions";
                inputElement.parentNode.appendChild(feedback);
            }
        }
    } catch (error) {
        console.error("Error validating address:", error);
        // Handle error case
        inputElement.dataset.validated = "false";
        inputElement.classList.add("is-invalid");
        
        // Update the position of the remove icon
        updateRemoveIconPosition(inputElement);
    }
}
