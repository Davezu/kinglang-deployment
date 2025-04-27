/**
 * Admin Booking JavaScript
 * This file handles the admin booking form functionality
 */

let map;
let directionsService;
let directionsRenderer;
let markers = [];
let addressInputs = [];
let currentStopCount = 1; // We start with one destination field

// Initialize the date picker
document.addEventListener("DOMContentLoaded", function() {
    // Initialize Flatpickr for date picker with minimum date of today + 1
    flatpickr("#dateOfTour", {
        dateFormat: "Y-m-d",
        minDate: new Date(Date.now() + 24 * 60 * 60 * 1000), // Tomorrow
        maxDate: new Date(new Date().setMonth(new Date().getMonth() + 3)), // 3 months from now
    });

    // Set up the days and buses counter buttons
    setupCounters();
    
    // Add stop button
    document.getElementById("addStop").addEventListener("click", addStopField);
    
    // Form submission
    document.getElementById("adminBookingForm").addEventListener("submit", handleFormSubmit);

    // Discount input changes
    document.getElementById("discount").addEventListener("input", updateTotalCostWithDiscount);

    // Initial calculation when addresses are entered
    document.querySelectorAll(".address").forEach(input => {
        input.addEventListener("input", debounce(function() {
            if (allAddressInputsValid()) {
                calculateRoute();
            }
        }, 500));
    });
});

/**
 * Initialize Google Map
 */
function initMap() {
    // Create a map centered on Philippines
    map = new google.maps.Map(document.getElementById("map"), {
        center: { lat: 12.8797, lng: 121.7740 }, // Philippines center
        zoom: 6,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        mapTypeControl: true,
        streetViewControl: true,
    });

    // Initialize the Directions service and renderer
    directionsService = new google.maps.DirectionsService();
    directionsRenderer = new google.maps.DirectionsRenderer({
        map: map,
        suppressMarkers: false,
        polylineOptions: {
            strokeColor: "#2c7be5",
            strokeOpacity: 0.8,
            strokeWeight: 5
        }
    });

    // Set up address input event listeners for Google Places autocomplete
    setupAddressAutocomplete();
}

/**
 * Set up address input event listeners
 */
function setupAddressAutocomplete() {
    // Setup for initial pickup and destination fields
    setupAddressInput("pickupPoint", "pickupPointSuggestions");
    setupAddressInput("destination", "destinationSuggestions");
}

/**
 * Set up autocomplete for address input
 * @param {string} inputId - The ID of the input field
 * @param {string} suggestionListId - The ID of the suggestion list
 */
function setupAddressInput(inputId, suggestionListId) {
    const inputElement = document.getElementById(inputId);
    const suggestionsList = document.getElementById(suggestionListId);
    
    inputElement.addEventListener("input", debounce(function() {
        if (inputElement.value.length > 2) {
            getAddressSuggestions(inputElement.value, suggestionsList, inputElement);
        } else {
            suggestionsList.style.display = "none";
            inputElement.dataset.validated = "false";
        }
    }, 500));
    
    // Clear validation when input changes
    inputElement.addEventListener("input", function() {
        inputElement.dataset.validated = "false";
        inputElement.classList.remove("is-verified");
    });
}

/**
 * Get address suggestions from Google Places API
 * @param {string} input - The input text
 * @param {HTMLElement} suggestionList - The suggestion list element
 * @param {HTMLElement} inputElement - The input element
 */
async function getAddressSuggestions(input, suggestionList, inputElement) {
    try {
        const response = await fetch("/get-address", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ address: input })
        });
        
        const data = await response.json();
        
        if (data.predictions && data.predictions.length > 0) {
            displaySuggestions(data, suggestionList, inputElement);
        } else {
            suggestionList.style.display = "none";
        }
    } catch (error) {
        console.error("Error fetching address suggestions:", error);
    }
}

/**
 * Display address suggestions in the dropdown
 * @param {Object} data - The suggestion data
 * @param {HTMLElement} suggestionList - The suggestion list element
 * @param {HTMLElement} inputElement - The input element
 */
function displaySuggestions(data, suggestionList, inputElement) {
    // Clear previous suggestions
    suggestionList.innerHTML = "";
    
    // Create suggestion items
    data.predictions.forEach(prediction => {
        const item = document.createElement("li");
        item.className = "suggestion-item";
        
        // Get the main text and secondary text for better display
        const mainText = prediction.structured_formatting.main_text;
        const secondaryText = prediction.structured_formatting.secondary_text;
        
        // Get icon based on place type
        const icon = getPlaceTypeIcon(prediction.types);
        
        item.innerHTML = `
            <div class="d-flex align-items-center">
                <span class="me-2">${icon}</span>
                <div>
                    <strong>${mainText}</strong>
                    <div><small>${secondaryText}</small></div>
                </div>
            </div>
        `;
        
        // When a suggestion is clicked
        item.addEventListener("click", function() {
            inputElement.value = prediction.description;
            inputElement.dataset.validated = "true";
            inputElement.classList.add("is-verified");
            suggestionList.style.display = "none";
            
            // If all inputs are valid, calculate route
            if (allAddressInputsValid()) {
                calculateRoute();
            }
        });
        
        suggestionList.appendChild(item);
    });
    
    // Show the suggestions
    suggestionList.style.display = "block";
}

/**
 * Get icon for place type
 * @param {Array} types - The place types
 * @returns {string} - HTML for the icon
 */
function getPlaceTypeIcon(types) {
    if (types.includes("establishment")) {
        return '<i class="bi bi-building"></i>';
    } else if (types.includes("street_address") || types.includes("route")) {
        return '<i class="bi bi-signpost"></i>';
    } else if (types.includes("locality") || types.includes("administrative_area_level_1")) {
        return '<i class="bi bi-geo-alt"></i>';
    } else {
        return '<i class="bi bi-pin-map"></i>';
    }
}

/**
 * Add a new stop field
 */
function addStopField() {
    currentStopCount++;
    
    // Create a new container for the stop
    const stopContainer = document.getElementById("stopContainer");
    
    // Create the new form group
    const formGroup = document.createElement("div");
    formGroup.className = "form-group";
    
    // Create a unique ID for the new stop
    const stopId = `additionalStop${currentStopCount}`;
    const suggestionListId = `${stopId}Suggestions`;
    
    // Create the form group content
    formGroup.innerHTML = `
        <label for="${stopId}">Additional Stop ${currentStopCount}</label>
        <div class="address-field">
            <input type="text" id="${stopId}" name="stops[]" class="form-control address additional-stop" 
                autocomplete="off" placeholder="Enter stop location" data-validated="false" required>
            <i class="bi bi-x-circle remove-stop" title="Remove stop"></i>
            <ul id="${suggestionListId}" class="suggestions"></ul>
        </div>
        <div class="error-feedback">Please enter a valid location</div>
    `;
    
    // Add it to the container
    stopContainer.appendChild(formGroup);
    
    // Set up the autocomplete for the new input
    const input = document.getElementById(stopId);
    const suggestionList = document.getElementById(suggestionListId);
    
    setupAddressInput(stopId, suggestionListId);
    
    // Add remove event listener to the new remove button
    formGroup.querySelector(".remove-stop").addEventListener("click", function() {
        stopContainer.removeChild(formGroup);
        currentStopCount--;
        
        // Recalculate the route if there are still addresses and they're valid
        if (allAddressInputsValid()) {
            calculateRoute();
        }
    });
}

/**
 * Calculate the route between addresses
 */
async function calculateRoute() {
    // Get all address inputs
    const addressInputs = document.querySelectorAll(".address");
    
    // Create an array of addresses
    const addresses = Array.from(addressInputs).map(input => input.value.trim()).filter(addr => addr !== "");
    
    if (addresses.length < 2) {
        return; // Need at least pickup and destination
    }
    
    // Create waypoints for any additional stops (between pickup and destination)
    const waypoints = addresses.slice(1, addresses.length - 1).map(address => ({
        location: address,
        stopover: true
    }));
    
    // Create a route request
    const request = {
        origin: addresses[0],
        destination: addresses[addresses.length - 1],
        waypoints: waypoints,
        travelMode: google.maps.TravelMode.DRIVING,
        optimizeWaypoints: false
    };
    
    // Request directions
    directionsService.route(request, function(result, status) {
        if (status === google.maps.DirectionsStatus.OK) {
            // Display the route on the map
            directionsRenderer.setDirections(result);
            
            // Calculate total distance
            let totalDistance = 0;
            const legs = result.routes[0].legs;
            
            for (let i = 0; i < legs.length; i++) {
                totalDistance += legs[i].distance.value;
            }
            
            // Convert to kilometers
            totalDistance = totalDistance / 1000;
            
            // Get the total cost based on distance
            calculateTotalCost(totalDistance);
        } else {
            console.error("Directions request failed due to " + status);
        }
    });
}

/**
 * Calculate the total cost based on distance
 * @param {number} totalDistance - Total distance in kilometers
 */
async function calculateTotalCost(totalDistance) {
    try {
        // Get the number of days and buses
        const numberOfDays = parseInt(document.getElementById("numberOfDays").textContent);
        const numberOfBuses = parseInt(document.getElementById("numberOfBuses").textContent);
        
        // Don't calculate if values are invalid
        if (numberOfDays <= 0 || numberOfBuses <= 0) {
            return;
        }
        
        // Get the diesel price from the server
        const dieselResponse = await fetch("/getDieselPrice");
        const dieselData = await dieselResponse.json();
        const dieselPrice = parseFloat(dieselData.diesel_price);
        
        // Calculate costs
        const dieselCost = calculateDieselCost(totalDistance, dieselPrice, numberOfDays);
        const baseCost = calculateBaseCost(numberOfDays, numberOfBuses);
        
        // Set the costs in the UI
        document.getElementById("baseCost").textContent = `₱${baseCost.toFixed(2)}`;
        document.getElementById("dieselCost").textContent = `₱${dieselCost.toFixed(2)}`;
        
        // Calculate total
        let totalCost = baseCost + dieselCost;
        
        // Apply discount
        const discountPercentage = parseFloat(document.getElementById("discount").value) || 0;
        if (discountPercentage > 0) {
            totalCost = totalCost * (1 - (discountPercentage / 100));
        }
        
        // Update the UI
        document.getElementById("totalCost").textContent = `₱${totalCost.toFixed(2)}`;
        document.getElementById("totalCostInput").value = totalCost.toFixed(2);
    } catch (error) {
        console.error("Error calculating total cost:", error);
    }
}

/**
 * Calculate diesel cost
 * @param {number} distance - Distance in kilometers
 * @param {number} dieselPrice - Price per liter
 * @param {number} days - Number of days
 * @returns {number} - Diesel cost
 */
function calculateDieselCost(distance, dieselPrice, days) {
    // Assume a certain fuel efficiency (km per liter)
    const fuelEfficiency = 3; // Can be adjusted based on actual bus efficiency
    
    // Calculate diesel consumption
    const litersDiesel = distance / fuelEfficiency;
    
    // Calculate diesel cost
    const dieselCost = litersDiesel * dieselPrice * days;
    
    return dieselCost;
}

/**
 * Calculate base cost
 * @param {number} days - Number of days
 * @param {number} buses - Number of buses
 * @returns {number} - Base cost
 */
function calculateBaseCost(days, buses) {
    // Base rate per day per bus
    const baseRate = 10000; // Adjust based on actual rates
    
    return baseRate * days * buses;
}

/**
 * Update total cost when discount changes
 */
function updateTotalCostWithDiscount() {
    // Get base and diesel costs
    const baseCost = parseFloat(document.getElementById("baseCost").textContent.replace("₱", ""));
    const dieselCost = parseFloat(document.getElementById("dieselCost").textContent.replace("₱", ""));
    
    // Calculate total before discount
    const totalBeforeDiscount = baseCost + dieselCost;
    
    // Get discount percentage
    const discountPercentage = parseFloat(document.getElementById("discount").value) || 0;
    
    // Apply discount
    let totalCost = totalBeforeDiscount;
    if (discountPercentage > 0) {
        totalCost = totalBeforeDiscount * (1 - (discountPercentage / 100));
    }
    
    // Update the UI
    document.getElementById("totalCost").textContent = `₱${totalCost.toFixed(2)}`;
    document.getElementById("totalCostInput").value = totalCost.toFixed(2);
}

/**
 * Set up the counters for days and buses
 */
function setupCounters() {
    // Days counter
    document.getElementById("increaseDays").addEventListener("click", function() {
        const daysElement = document.getElementById("numberOfDays");
        const currentDays = parseInt(daysElement.textContent);
        const newDays = currentDays + 1;
        
        daysElement.textContent = newDays;
        document.getElementById("numberOfDaysInput").value = newDays;
        document.getElementById("decreaseDays").disabled = newDays <= 1;
        
        if (allAddressInputsValid()) {
            calculateRoute();
        }
    });
    
    document.getElementById("decreaseDays").addEventListener("click", function() {
        const daysElement = document.getElementById("numberOfDays");
        const currentDays = parseInt(daysElement.textContent);
        
        if (currentDays > 1) {
            const newDays = currentDays - 1;
            daysElement.textContent = newDays;
            document.getElementById("numberOfDaysInput").value = newDays;
            document.getElementById("decreaseDays").disabled = newDays <= 1;
            
            if (allAddressInputsValid()) {
                calculateRoute();
            }
        }
    });
    
    // Buses counter
    document.getElementById("increaseBuses").addEventListener("click", function() {
        const busesElement = document.getElementById("numberOfBuses");
        const currentBuses = parseInt(busesElement.textContent);
        const newBuses = currentBuses + 1;
        
        busesElement.textContent = newBuses;
        document.getElementById("numberOfBusesInput").value = newBuses;
        document.getElementById("decreaseBuses").disabled = newBuses <= 1;
        
        if (allAddressInputsValid()) {
            calculateRoute();
        }
    });
    
    document.getElementById("decreaseBuses").addEventListener("click", function() {
        const busesElement = document.getElementById("numberOfBuses");
        const currentBuses = parseInt(busesElement.textContent);
        
        if (currentBuses > 1) {
            const newBuses = currentBuses - 1;
            busesElement.textContent = newBuses;
            document.getElementById("numberOfBusesInput").value = newBuses;
            document.getElementById("decreaseBuses").disabled = newBuses <= 1;
            
            if (allAddressInputsValid()) {
                calculateRoute();
            }
        }
    });
}

/**
 * Handle form submission
 * @param {Event} event - The submit event
 */
async function handleFormSubmit(event) {
    event.preventDefault();
    
    // Validate form
    if (!validateForm()) {
        return;
    }
    
    // Get form data
    const formData = new FormData(event.target);
    const formObject = {};
    
    formData.forEach((value, key) => {
        // Handle nested objects like initialPayment[amountPaid]
        if (key.includes('[') && key.includes(']')) {
            const mainKey = key.substring(0, key.indexOf('['));
            const subKey = key.substring(key.indexOf('[') + 1, key.indexOf(']'));
            
            if (!formObject[mainKey]) {
                formObject[mainKey] = {};
            }
            
            formObject[mainKey][subKey] = value;
        } else {
            formObject[key] = value;
        }
    });
    
    // Handle stops array (collect all additional stops)
    formObject.stops = [];
    document.querySelectorAll('.additional-stop').forEach(input => {
        if (input.value.trim()) {
            formObject.stops.push(input.value.trim());
        }
    });
    
    try {
        // Send data to server
        const response = await fetch("/admin/submit-booking", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formObject)
        });
        
        const result = await response.json();
        
        if (result.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: result.message,
                confirmButtonColor: '#2c7be5'
            }).then(() => {
                // Redirect to booking details or booking list
                window.location.href = `/admin/booking-detail/${result.booking_id}`;
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: result.message,
                confirmButtonColor: '#2c7be5'
            });
        }
    } catch (error) {
        console.error("Error submitting form:", error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred. Please try again.',
            confirmButtonColor: '#2c7be5'
        });
    }
}

/**
 * Validate form before submission
 * @returns {boolean} - Whether the form is valid
 */
function validateForm() {
    let isValid = true;
    
    // Check required client information
    const requiredFields = ["clientName", "contactNumber", "email", "pickupPoint", "destination", "dateOfTour", "pickupTime"];
    
    requiredFields.forEach(field => {
        const element = document.getElementById(field);
        if (!element.value.trim()) {
            element.classList.add("is-invalid");
            isValid = false;
        } else {
            element.classList.remove("is-invalid");
        }
    });
    
    // Validate address fields
    document.querySelectorAll(".address").forEach(input => {
        if (input.value.trim() === "" || input.dataset.validated !== "true") {
            input.classList.add("is-invalid");
            const errorFeedback = input.parentElement.nextElementSibling;
            if (errorFeedback && errorFeedback.classList.contains("error-feedback")) {
                errorFeedback.style.display = "block";
            }
            isValid = false;
        } else {
            input.classList.remove("is-invalid");
            const errorFeedback = input.parentElement.nextElementSibling;
            if (errorFeedback && errorFeedback.classList.contains("error-feedback")) {
                errorFeedback.style.display = "none";
            }
        }
    });
    
    // Check if we have a valid total cost
    const totalCost = parseFloat(document.getElementById("totalCostInput").value);
    if (isNaN(totalCost) || totalCost <= 0) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Please ensure all trip details are completed to calculate the cost.',
            confirmButtonColor: '#2c7be5'
        });
        isValid = false;
    }
    
    // Validate payment information if any payment is entered
    const amountPaid = document.getElementById("amountPaid").value;
    if (amountPaid && amountPaid > 0) {
        const paymentMethod = document.getElementById("paymentMethod").value;
        if (!paymentMethod) {
            document.getElementById("paymentMethod").classList.add("is-invalid");
            isValid = false;
        } else {
            document.getElementById("paymentMethod").classList.remove("is-invalid");
        }
    }
    
    if (!isValid) {
        Swal.fire({
            icon: 'error',
            title: 'Form Validation Error',
            text: 'Please check the form for errors and try again.',
            confirmButtonColor: '#2c7be5'
        });
    }
    
    return isValid;
}

/**
 * Check if all address inputs are valid
 * @returns {boolean} - Whether all address inputs are valid
 */
function allAddressInputsValid() {
    const addressInputs = document.querySelectorAll(".address");
    
    // Check if we have at least two addresses (pickup and destination)
    if (addressInputs.length < 2) {
        return false;
    }
    
    // Check if all addresses are filled and validated
    for (const input of addressInputs) {
        if (input.value.trim() === "" || input.dataset.validated !== "true") {
            return false;
        }
    }
    
    return true;
}

/**
 * Debounce function to limit how often a function is called
 * @param {Function} func - The function to debounce
 * @param {number} delay - The delay in milliseconds
 * @returns {Function} - The debounced function
 */
function debounce(func, delay) {
    let timeoutId;
    return function(...args) {
        clearTimeout(timeoutId);
        timeoutId = setTimeout(() => {
            func.apply(this, args);
        }, delay);
    };
} 