<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Invoice</title>
    <!-- Document generation libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/docxtemplater/3.37.11/docxtemplater.js"></script>
    <script src="https://unpkg.com/pizzip@3.1.4/dist/pizzip.js"></script>
    <script src="https://unpkg.com/pizzip@3.1.4/dist/pizzip-utils.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            border-radius: 5px;
        }
        .invoice-header {
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4a6fdc;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h1 {
            margin: 0;
            color: #4a6fdc;
        }
        .invoice-details {
            margin-bottom: 40px;
        }
        .client-info, .trip-info {
            margin-bottom: 20px;
        }
        .info-group {
            margin-bottom: 15px;
        }
        .info-group h3 {
            margin: 0 0 10px 0;
            color: #4a6fdc;
            font-size: 18px;
        }
        .info-label {
            font-weight: bold;
        }
        .stops-list {
            margin: 10px 0;
            padding-left: 20px;
        }
        .stops-list li {
            margin-bottom: 5px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        th {
            background-color: #f2f7ff;
            color: #4a6fdc;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .cost-summary {
            margin-top: 30px;
            background-color: #f2f7ff;
            padding: 20px;
            border-radius: 5px;
        }
        .cost-summary p {
            margin: 10px 0;
        }
        .total-cost {
            font-size: 18px;
            font-weight: bold;
            color: #4a6fdc;
            margin-top: 15px;
        }
        .invoice-footer {
            margin-top: 50px;
            text-align: center;
            font-size: 14px;
            color: #777;
            border-top: 1px solid #f0f0f0;
            padding-top: 20px;
        }
        @media print {
            body {
                background-color: #fff;
            }
            .invoice-container {
                box-shadow: none;
                margin: 0;
                padding: 20px;
                max-width: 100%;
            }
            .print-button {
                display: none;
            }
        }
        .button-container {
            text-align: center; 
            margin-top: 30px;
        }
        .button {
            padding: 10px 20px; 
            color: white; 
            border: none; 
            border-radius: 5px; 
            cursor: pointer;
            margin: 0 5px;
        }
        .print-btn {
            background-color: #4a6fdc;
        }
        .export-btn {
            background-color: #1e7145;
        }
        .loading {
            display: inline-block;
            margin-left: 10px;
            display: none;
        }
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3498db;
            border-radius: 50%;
            width: 16px;
            height: 16px;
            animation: spin 1s linear infinite;
            display: inline-block;
            vertical-align: middle;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <div class="invoice-header">
            <div class="logo">KingLang Booking</div>
            <div class="invoice-title">
                <h1>BOOKING INVOICE</h1>
                <p id="invoiceDate"></p>
            </div>
        </div>

        <div class="invoice-details">
            <div class="client-info info-group">
                <h3>CLIENT INFORMATION</h3>
                <p><span class="info-label">Name:</span> <span id="clientName"></span></p>
                <p><span class="info-label">Email:</span> <span id="email"></span></p>
                <p><span class="info-label">Contact:</span> <span id="contactNumber"></span></p>
            </div>

            <div class="terms-agreement info-group">
                <h3>TERMS AND CONDITIONS AGREEMENT</h3>
                <div id="termsAgreementInfo">
                    <p>Loading terms agreement information...</p>
                </div>
            </div>

            <div class="trip-info info-group">
                <h3>TRIP DETAILS</h3>
                <p><span class="info-label">Pickup Point:</span> <span id="pickupPoint"></span></p>
                <p><span class="info-label">Destination:</span> <span id="destination"></span></p>
                <p><span class="info-label">Stops:</span></p>
                <div id="stops"></div>
            </div>
        </div>

        <div class="distance-table info-group">
            <h3>ROUTE BREAKDOWN</h3>
            <table>
                <thead>
                    <tr>
                        <th>Origin</th>
                        <th>Destination</th>
                        <th>Distance</th>
                    </tr>
                </thead>
                <tbody id="tbody"></tbody>
            </table>
        </div>

        <div class="cost-summary">
            <h3>COST CALCULATION</h3>
            <p id="numberOfBuses"></p>
            <p id="numberOfDays"></p>
            <p id="dieselPrice"></p>
            <p id="totalDistance"></p>
            <p class="total-cost" id="totalCost"></p>
        </div>

        <div class="button-container">
            <button onclick="window.print()" class="button print-btn">Print Invoice</button>
            <button onclick="exportToCompanyTemplate()" class="button export-btn">Export to Company Template</button>
            <div class="loading" id="loadingSpinner">
                <span class="spinner"></span> Loading template...
            </div>
        </div>

        <div class="invoice-footer">
            <p>Thank you for choosing KingLang Booking. For any inquiries, please contact our support team.</p>
            <p>© 2023 KingLang Booking. All Rights Reserved.</p>
        </div>
    </div>

    <script>
        // Global variables to store booking data
        let bookingData = {};
        let stopsData = [];
        let distancesData = [];
        let totalDistanceInKm = 0;
        let dieselPrice = 0;
        let numberOfDays = 0;
        let numberOfBuses = 0;
        let totalCost = 0;

        document.addEventListener("DOMContentLoaded", async function() {
            const urlParams = new URLSearchParams(window.location.search);
            const bookingId = urlParams.get("id") || localStorage.getItem("bookingId");
            
            if (localStorage.getItem("bookingId")) {
                localStorage.removeItem("bookingId");
            }
            
            if (!bookingId) {
                alert("No booking ID provided");
                return;
            }
            
            // Set invoice date
            const currentDate = new Date();
            document.getElementById("invoiceDate").textContent = "Date: " + currentDate.toLocaleDateString();
            
            try {
                const response = await fetch("/admin/get-booking", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ bookingId: bookingId })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    bookingData = data.booking;
                    stopsData = data.stops;
                    distancesData = data.distances;
                    
                    // Try to get additional details including terms agreement
                    try {
                        const detailsResponse = await fetch('/admin/get-booking-details', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                booking_id: bookingId
                            })
                        });
                        
                        const detailsData = await detailsResponse.json();
                        
                        if (detailsData.success) {
                            // If we have detailed data, use it
                            if (detailsData.distances && detailsData.distances.length > 0) {
                                distancesData = detailsData.distances;
                            }
                            
                            // Display terms agreement information
                            displayTermsAgreement(detailsData.terms_agreement);
                        }
                    } catch (detailsError) {
                        console.error('Error fetching details:', detailsError);
                    }
                    
                    // Process distance and cost calculations
                    totalDistanceInKm = distancesData.map(distance => parseFloat(distance.distance)).reduce((acc, curr) => acc + curr, 0) / 1000;
                    dieselPrice = parseFloat(data.diesel);
                    numberOfDays = parseInt(bookingData.number_of_days);
                    numberOfBuses = parseInt(bookingData.number_of_buses);
                    
                    totalCost = new Intl.NumberFormat().format(totalDistanceInKm * dieselPrice * numberOfDays * numberOfBuses);
                    
                    console.log("Booking info: ", data);
                    
                    // Populate client info
                    document.getElementById("clientName").textContent = bookingData.client_name;
                    document.getElementById("email").textContent = bookingData.email;
                    document.getElementById("contactNumber").textContent = bookingData.contact_number;
                    
                    // Populate trip details
                    document.getElementById("pickupPoint").textContent = bookingData.pickup_point;
                    document.getElementById("destination").textContent = bookingData.destination;
                    document.getElementById("numberOfBuses").textContent = "Number of buses: " + numberOfBuses;
                    document.getElementById("numberOfDays").textContent = "Number of days: " + numberOfDays;
                    document.getElementById("dieselPrice").textContent = "Diesel price per liter: " + dieselPrice;
                    document.getElementById("totalDistance").textContent = "Total Distance: " + totalDistanceInKm + " km";
                    document.getElementById("totalCost").textContent = "Total Cost: ₱" + totalCost;
                    
                    // Populate distance table
                    const tbody = document.getElementById("tbody");
                    tbody.innerHTML = "";
                    
                    distancesData.forEach(distance => {
                        const distanceInKm = distance.distance / 1000;
                        
                        const tr = document.createElement("tr");
                        
                        const originCell = document.createElement("td");
                        const destinationCell = document.createElement("td");
                        const distanceCell = document.createElement("td");
                        
                        originCell.textContent = distance.origin;
                        destinationCell.textContent = distance.destination;
                        distanceCell.textContent = distanceInKm + " km";
                        
                        tr.append(originCell, destinationCell, distanceCell);
                        tbody.appendChild(tr);
                    });
                    
                    // Display stops
                    const stopsContainer = document.getElementById("stops");
                    stopsContainer.innerHTML = "";
                    
                    if (stopsData.length === 0) {
                        const p = document.createElement("p");
                        p.textContent = "None";
                        stopsContainer.appendChild(p);
                    } else {
                        const ul = document.createElement("ul");
                        ul.className = "stops-list";
                        stopsData.forEach(stop => {
                            const li = document.createElement("li");
                            li.textContent = stop.location;
                            ul.appendChild(li);
                        });
                        stopsContainer.appendChild(ul);
                    }
                } else {
                    alert("Error loading booking: " + data.message);
                }
            } catch (error) {
                console.error(error);
                alert("Error loading booking information");
            }
        });

        // Function to load the template file
        function loadFile(url, callback) {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', url, true);
            xhr.responseType = 'arraybuffer';
            xhr.onload = function() {
                if (xhr.status === 200) {
                    callback(null, xhr.response);
                } else {
                    callback(new Error(`Failed to load file: ${xhr.status}`));
                }
            };
            xhr.onerror = function() {
                callback(new Error('Network error'));
            };
            xhr.send();
        }

        function exportToCompanyTemplate() {
            const loadingSpinner = document.getElementById('loadingSpinner');
            loadingSpinner.style.display = 'inline-block';
            
            // Format data for template
            const formattedDate = new Date().toLocaleDateString();
            let stopsText = "None";
            if (stopsData && stopsData.length > 0) {
                stopsText = stopsData.map(stop => stop.location).join(', ');
            }
            
            // Create table rows for routes as a formatted string
            let routeTable = '';
            if (distancesData && distancesData.length > 0) {
                distancesData.forEach(distance => {
                    const distanceInKm = distance.distance / 1000;
                    routeTable += `${distance.origin} → ${distance.destination}: ${distanceInKm} km\n`;
                });
            }
            
            // Load the company template
            loadFile('/KingLang_Paper_Template.docx', function(error, content) {
                if (error) {
                    console.error('Error loading template:', error);
                    alert('Error loading company template. Please try again.');
                    loadingSpinner.style.display = 'none';
                    return;
                }
                
                try {
                    // Create a zip object from the docx content
                    const zip = new PizZip(content);
                    
                    // Create a new instance of docxtemplater with options for better error handling
                    const doc = new window.docxtemplater();
                    
                    // Load the document
                    doc.loadZip(zip);
                    
                    // Configure error handler for better debugging
                    doc.setOptions({
                        parser: function(tag) {
                            return {
                                get: function(scope) {
                                    if (tag === '.') {
                                        return scope;
                                    }
                                    // Handle both direct tag names and nested properties
                                    // This makes the template more flexible
                                    const parts = tag.split('.');
                                    let result = scope;
                                    for (let i = 0; i < parts.length; i++) {
                                        if (result == null) return '';
                                        result = result[parts[i]];
                                    }
                                    return result || '';
                                }
                            };
                        }
                    });
                    
                    // Create a comprehensive data object with multiple variations of naming
                    // This increases chances of matching template placeholders
                    const templateData = {
                        // Standard fields with underscores (common in templates)
                        date: formattedDate,
                        current_date: formattedDate,
                        invoice_date: formattedDate,
                        
                        // Client info
                        client_name: bookingData.client_name || 'N/A',
                        clientName: bookingData.client_name || 'N/A',
                        client: bookingData.client_name || 'N/A',
                        name: bookingData.client_name || 'N/A',
                        
                        email: bookingData.email || 'N/A',
                        client_email: bookingData.email || 'N/A',
                        clientEmail: bookingData.email || 'N/A',
                        
                        contact: bookingData.contact_number || 'N/A',
                        contact_number: bookingData.contact_number || 'N/A',
                        contactNumber: bookingData.contact_number || 'N/A',
                        phone: bookingData.contact_number || 'N/A',
                        
                        // Trip details
                        pickup: bookingData.pickup_point || 'N/A',
                        pickup_point: bookingData.pickup_point || 'N/A',
                        pickupPoint: bookingData.pickup_point || 'N/A',
                        origin: bookingData.pickup_point || 'N/A',
                        
                        destination: bookingData.destination || 'N/A',
                        final_destination: bookingData.destination || 'N/A',
                        finalDestination: bookingData.destination || 'N/A',
                        
                        stops: stopsText,
                        stop_points: stopsText,
                        stopPoints: stopsText,
                        
                        // Route information
                        route: routeTable,
                        route_table: routeTable,
                        routeTable: routeTable,
                        routes: routeTable,
                        
                        // Calculation details
                        buses: numberOfBuses,
                        number_of_buses: numberOfBuses,
                        numberOfBuses: numberOfBuses,
                        
                        days: numberOfDays,
                        number_of_days: numberOfDays,
                        numberOfDays: numberOfDays,
                        
                        diesel: dieselPrice,
                        diesel_price: dieselPrice,
                        dieselPrice: dieselPrice,
                        
                        distance: totalDistanceInKm + ' km',
                        total_distance: totalDistanceInKm + ' km',
                        totalDistance: totalDistanceInKm + ' km',
                        
                        cost: '₱' + totalCost,
                        total_cost: '₱' + totalCost,
                        totalCost: '₱' + totalCost,
                        
                        // Include raw data as well
                        booking: bookingData,
                        raw_distance: totalDistanceInKm,
                        raw_cost: totalCost.replace(/,/g, '')
                    };
                    
                    // Add the full objects as well
                    templateData.bookingData = bookingData;
                    templateData.stopsData = stopsData;
                    templateData.distancesData = distancesData;
                    
                    // Set the template variables with our data
                    doc.setData(templateData);
                    
                    // Perform the template substitution
                    doc.render();
                    
                    // Get the document as a blob
                    const blob = doc.getZip().generate({
                        type: 'blob',
                        mimeType: 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                    });
                    
                    // Save the document
                    saveAs(blob, 'KingLang_Booking_Invoice.docx');
                    
                    loadingSpinner.style.display = 'none';
                    
                } catch (error) {
                    console.error('Error rendering template:', error);
                    
                    // More detailed error handling for better debugging
                    if (error.properties && error.properties.errors instanceof Array) {
                        const errorMessages = error.properties.errors.map(function (error) {
                            return error.properties.explanation;
                        }).join("\n");
                        console.log("Template Error: " + errorMessages);
                        alert('Template error: ' + errorMessages);
                    } else {
                        alert('Error processing template. Please check console for details.');
                    }
                    
                    loadingSpinner.style.display = 'none';
                }
            });
        }

        // Function to display terms agreement information
        function displayTermsAgreement(termsAgreement) {
            const termsAgreementInfoDiv = document.getElementById('termsAgreementInfo');
            
            if (!termsAgreement) {
                termsAgreementInfoDiv.innerHTML = `
                    <p style="color: #856404; background-color: #fff3cd; padding: 10px; border-radius: 5px;">
                        <i style="margin-right: 5px;">⚠️</i>
                        No terms agreement information available for this booking.
                    </p>
                `;
                return;
            }
            
            const agreementDate = new Date(termsAgreement.agreed_date).toLocaleString();
            const agreementStatus = termsAgreement.agreed_terms ? 
                '<span style="color: green; font-weight: bold;">✓ Agreed</span>' : 
                '<span style="color: red; font-weight: bold;">✗ Not Agreed</span>';
                
            termsAgreementInfoDiv.innerHTML = `
                <table>
                    <tr>
                        <th>Agreement Status</th>
                        <td>${agreementStatus}</td>
                    </tr>
                    <tr>
                        <th>Agreement Date</th>
                        <td>${agreementDate}</td>
                    </tr>
                    <tr>
                        <th>Client IP Address</th>
                        <td>${termsAgreement.user_ip}</td>
                    </tr>
                </table>
            `;
        }
    </script>
</body>
</html>