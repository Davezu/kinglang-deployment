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


    <script>

        document.addEventListener("DOMContentLoaded", async function () {
            const bookingId = localStorage.getItem("bookingId");
            try {
                const response = await fetch("/get-booking", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ bookingId })
                });

                const data = await response.json();

                const booking = data.booking;
                console.log("Booking info: ", data);

                document.getElementById("pickupPoint").textContent = booking.pickup_point;
                document.getElementById("destination").textContent = booking.destination;
            } catch (error) {
            } catch (error) {
                console.error(error);
            }
        });
    </script>
</body>
</html>