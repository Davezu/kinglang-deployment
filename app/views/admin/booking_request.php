<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p id="pickupPoint"></p>
    <p id="destination"></p>
    <p>Stops:</p>
    <div id="stops"></div>
    <p>Client Name: <span id="clientName"></span></p>
    <p>Email: <span id="email"></span></p>
    <p>Contact Number: <span id="contactNumber"></span></p>

    <table>
        <thead>
            <th>From</th>
            <th>To</th>
            <th>Distance</th>
        </thead>
        <tbody id="tbody"></tbody>
    </table>
    <p id="numberOfBuses"></p>
    <p id="numberOfDays"></p>
    <p id="dieselPrice"></p>
    <p id="totalDistance"></p>
    <p id="totalCost"></p>

    <script>

        document.addEventListener("DOMContentLoaded", async function () {
            const bookingId = localStorage.getItem("bookingId");
            localStorage.removeItem("bookingId");

            if (!bookingId) return;
            try {
                const response = await fetch("/admin/get-booking", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ bookingId })
                });

                const data = await response.json();

                const booking = data.booking;
                const stops = data.stops;
                const distances = data.distances;

                const totalDistanceInKm = data.distances.map(distance => parseFloat(distance.distance)).reduce((acc, curr) => acc + curr, 0) / 1000;
                const dieselPrice = parseFloat(data.diesel);
                const numberOfDays = parseInt(booking.number_of_days);
                const numberOfBuses = parseInt(booking.number_of_buses);

                const totalCost = new Intl.NumberFormat().format(totalDistanceInKm * dieselPrice * numberOfDays * numberOfBuses);

                console.log("Booking info: ", data);

                document.getElementById("clientName").textContent = booking.client_name;
                document.getElementById("email").textContent = booking.email;
                document.getElementById("contactNumber").textContent = booking.contact_number;
                document.getElementById("pickupPoint").textContent = "Pickup Point: " + booking.pickup_point;
                document.getElementById("destination").textContent = "Destination: " + booking.destination;
                document.getElementById("numberOfBuses").textContent = "Number of buses: " + numberOfBuses;
                document.getElementById("numberOfDays").textContent = "Number of days: " + numberOfDays;
                document.getElementById("dieselPrice").textContent = "Diesel price per liter: " + dieselPrice;
                document.getElementById("totalDistance").textContent = "Total Distance: " + totalDistanceInKm + " km";
                document.getElementById("totalCost").textContent = "Total Cost: (No. of days x No. of buses x Diesel price per liter x Distance in KM) = " + totalCost + " Petot";

                const tbody = document.getElementById("tbody");

                tbody.innerHTML = "";
                distances.forEach(distance => {
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

                if (stops.length === 0) {
                    const p = document.createElement("p");
                    p.textContent = "None";
                    document.getElementById("stops").appendChild(p);
                    return;
                }
                
                const ul = document.createElement("ul");
                stops.forEach(stop => {
                    const li = document.createElement("li");
                    li.textContent = stop.location;
                    ul.appendChild(li);
                });
                document.getElementById("stops").appendChild(ul);

            } catch (error) {
                console.error(error);
            }
        });
    </script>
</body>
</html>