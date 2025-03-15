
document.addEventListener("DOMContentLoaded", async () => {
    const firstName = document.getElementById("firstName");
    const lastName = document.getElementById("lastName");
    const address = document.getElementById("address");
    const username = document.getElementById("username");
    const companyName = document.getElementById("companyName");
    const email = document.getElementById("email");
    const contactNumber = document.getElementById("contactNumber");
   
    try {
        const response = await fetch("/get-client-information");
        const data = await response.json(); 

        if (data.success) {
            const client = data.info.client[0];
            const user = data.info.user[0];

            console.log(data);
            console.log(user);

            firstName.value = client.first_name;
            lastName.value = client.last_name;
            address.value = client.address;
            companyName.value = client.company_name;
            contactNumber.value = client.contact_number;

            username.value = user.username;
            email.value = user.email;
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
        address: document.getElementById("address")?.value,
        username: document.getElementById("username")?.value,
        companyName: document.getElementById("companyName")?.value || "",
        contactNumber: document.getElementById("contactNumber")?.value,
        email: document.getElementById("email")?.value
    }

    console.log(document.getElementById("username").value);      

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