const today = new Date();

today.setDate(today.getDate() + 3);

const minDate = today.toISOString().split("T")[0];

document.getElementById("date_of_tour").min = minDate;  

// submit booking
document.getElementById("date_of_tour").addEventListener("input", findAvailableBuses);
document.getElementById("number_of_days").addEventListener("input", findAvailableBuses);

async function findAvailableBuses() {
    const tourDate = document.getElementById("date_of_tour").value;
    const numDays = document.getElementById("number_of_days").value;
    console.log(tourDate);
    console.log(numDays);

    if (tourDate.trim() === "" || numDays.trim() === "") return;

    try {
        const response = await fetch('/get-available-buses', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ date_of_tour: tourDate, number_of_days: numDays })
        });
    
        const data = await response.json();
        const busSelectionDiv = document.getElementById("busSelection");
    
        if (data.success && data.buses.length > 0) {
            let options = `<label for="bus_id">Choose a Bus:</label><select id="bus_id" name="bus_id">`;
            data.buses.forEach(bus => {
                options += `<option value="${bus.bus_id}">${bus.bus_name} - ${bus.capacity} seats</option>`;
            });
            options += `</select>`;
            busSelectionDiv.innerHTML = options;
        } else {
            busSelectionDiv.innerHTML = `No available buses for the selected date.`;
        }
    } catch (error) {
        console.error("Error fetching data: ", error.message);
    }
}