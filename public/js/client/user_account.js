
document.addEventListener("DOMContentLoaded", async () => {
    const firstName = document.getElementById("firstName");
    const lastName = document.getElementById("lastName");
    const email = document.getElementById("email");
    const contactNumber = document.getElementById("contactNumber");
   
    try {
        const response = await fetch("/get-client-information");
        const data = await response.json(); 

        if (data.success) {
            const client = data.client[0];

            console.log(data.client[0]);

            firstName.value = client.first_name;
            lastName.value = client.last_name;
            contactNumber.value = client.contact_number;
            email.value = client.email;
        }
    } catch (error) {
        console.error("Error fetching data: ", error);
    }
});

document.getElementById("userForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = {
        firstName: document.getElementById("firstName")?.value,
        lastName: document.getElementById("lastName")?.value,
        contactNumber: document.getElementById("contactNumber")?.value,
        email: document.getElementById("email")?.value
    }    

    try {
        const response = await fetch("/update-client-information", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData)
        });
    
        const data = await response.json();
        console.log(data);
    
        if (data.success) {
            document.getElementById("userMessage").textContent = data.message;
        } else {
            document.getElementById("userMessage").textContent = data.message;
        }
    } catch (error) {
        console.error("Error fetching data: ", error);
    }
})