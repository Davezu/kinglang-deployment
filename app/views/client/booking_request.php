<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/../../../public/css/bootstrap/bootstrap.min.css">
    <title>Booking Request Details</title>
</head>
<body>
    <?php include_once __DIR__ . "/../assets/sidebar.php"; ?>
    
    <div class="content collapsed" id="content">
        <div class="container-fluid py-4 px-4 px-xl-5">
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0">
                <div class="p-0">
                    <h3>Booking Request Details</h3>
                </div>
                <?php include_once __DIR__ . "/../assets/user_profile.php"; ?>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-success-subtle">Pickup Point</span>
                                <input type="text" class="form-control" id="pickupPoint" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-success-subtle">Destination</span>
                                <input type="text" class="form-control" id="destination" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-success-subtle">Number of Buses</span>
                                <input type="text" class="form-control" id="numberOfBuses" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-success-subtle">Number of Days</span>
                                <input type="text" class="form-control" id="numberOfDays" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5 class="mb-3">Stops</h5>
                        <div id="stops" class="list-group"></div>
                    </div>

                    <div class="mb-4">
                        <h5 class="mb-3">Route Details</h5>
                        <div class="table-responsive">
                            <table class="table table-hover overflow-hidden rounded">
                                <thead>
                                    <tr>
                                        <th>From</th>
                                        <th>To</th>
                                        <th>Distance</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody"></tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-success-subtle">Diesel Price</span>
                                <input type="text" class="form-control" id="dieselPrice" readonly>
                            </div>
                            <div class="input-group mb-3">
                                <span class="input-group-text bg-success-subtle">Total Distance</span>
                                <input type="text" class="form-control" id="totalDistance" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Total Cost</h5>
                                    <p class="card-text h4" id="totalCost"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="/../../../public/js/assets/sidebar.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", async function () {
            const bookingId = localStorage.getItem("bookingId");
            localStorage.removeItem("bookingId");
            try {
                const response = await fetch("/get-booking", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ bookingId })
                });

                const data = await response.json();

                const booking = data.booking;
                const stops = data.stops;
                const distances = data.distances;

                const totalDistanceInKm = (data.distances.map(distance => parseFloat(distance.distance)).reduce((acc, curr) => acc + curr, 0) / 1000).toFixed(2);
                const dieselPrice = parseFloat(data.diesel);
                const numberOfDays = parseInt(booking.number_of_days);
                const numberOfBuses = parseInt(booking.number_of_buses);

                const totalCost = new Intl.NumberFormat().format(totalDistanceInKm * dieselPrice * numberOfDays * numberOfBuses);

                console.log("Booking info: ", data);

                document.getElementById("pickupPoint").value = booking.pickup_point;
                document.getElementById("destination").value = booking.destination;
                document.getElementById("numberOfBuses").value = numberOfBuses;
                document.getElementById("numberOfDays").value = numberOfDays;
                document.getElementById("dieselPrice").value = dieselPrice + " Petot per liter";
                document.getElementById("totalDistance").value = totalDistanceInKm + " km";
                document.getElementById("totalCost").textContent = totalCost + " Petot";

                const tbody = document.getElementById("tbody");
                tbody.innerHTML = "";
                distances.forEach(distance => {
                    const distanceInKm = (distance.distance / 1000).toFixed(2);
                    
                    const tr = document.createElement("tr");
                    tr.innerHTML = `
                        <td>${distance.origin}</td>
                        <td>${distance.destination}</td>
                        <td>${distanceInKm} km</td>
                    `;
                    tbody.appendChild(tr);
                });

                const stopsContainer = document.getElementById("stops");
                if (stops.length === 0) {
                    stopsContainer.innerHTML = '<div class="list-group-item">No stops</div>';
                } else {
                    stops.forEach(stop => {
                        const stopElement = document.createElement("div");
                        stopElement.className = "list-group-item";
                        stopElement.textContent = stop.location;
                        stopsContainer.appendChild(stopElement);
                    });
                }

            } catch (error) {
                console.error(error);
            }
        });
    </script>
</body>
</html>