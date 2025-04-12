document.addEventListener('DOMContentLoaded', async function () {
    const requests = await getRebookingRequests('All', 'asc', 'booking_id');
    renderRebookingRequests(requests);
}); 

document.getElementById('statusSelect').addEventListener('change', async function () {
    const status = this.value;
    console.log(status);
    const requests = await getRebookingRequests(status, 'asc', 'client_name');
    renderRebookingRequests(requests);
});

document.querySelectorAll('.sort').forEach(button => {
    button.style.cursor = 'pointer';
    button.style.backgroundColor = '#d1f7c4';

    button.addEventListener('click', async function () {
        const status = document.getElementById('statusSelect').value;
        const column = this.getAttribute('data-column');
        const order = this.getAttribute('data-order');

        const requests = await getRebookingRequests(status, order, column);
        renderRebookingRequests(requests);

        this.setAttribute('data-order', order === 'asc' ? 'desc' : 'asc');
    });
});

function formatDate(date) {
    return new Date(date).toLocaleDateString("en-US", {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

async function getRebookingRequests(status, order, column) {
    try {
        const response = await fetch("/admin/get-rebooking-requests", {
            method: 'POST', 
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ status, order, column })
        });

        const data = await response.json();
        console.log(data);
        if (data.success) {
            return data.requests;
        }
    } catch (error) {
        console.error('Fetch error:', error);
    }
}

async function renderRebookingRequests(requests) {
    // const requests = await getRebookingRequests();
    console.log(requests);
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = '';

    requests.forEach(request => {
        const row = document.createElement('tr');
        
        const clientNameCell = document.createElement('td');
        const clientContactCell = document.createElement('td');
        const clientEmailCell = document.createElement("td");
        const dateOfTourCell = document.createElement('td');
        const statusCell = document.createElement('td');

        clientNameCell.textContent = request.client_name;
        clientContactCell.textContent = request.contact_number;
        clientEmailCell.textContent = request.email;
        dateOfTourCell.textContent = formatDate(request.date_of_tour);
        statusCell.textContent = request.status;

        row.append(clientNameCell, clientContactCell, clientEmailCell, dateOfTourCell, statusCell, actionButtons(request));
        tbody.appendChild(row);
    });
}

function actionButtons(request) {
    const actionCell = document.createElement('td');
    const buttonGroup = document.createElement('div');
    const confirmButton = document.createElement('button');
    const rejectButton = document.createElement('button');
    const viewButton = document.createElement('button');

    buttonGroup.classList.add('d-flex', 'gap-2');   

    confirmButton.classList.add('btn', 'bg-success-subtle', 'text-success', 'btn-sm', 'fw-bold', 'w-100', 'approve');
    confirmButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");

    confirmButton.textContent = 'Confrim';

    confirmButton.addEventListener('click', async () => {
        const response = await fetch('/admin/confirm-rebooking-request', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ bookingId: request.booking_id })
        });

        const data = await response.json();
        if (data.success) {
            const requests = await getRebookingRequests('all', 'asc', 'client_name');
            renderRebookingRequests(requests);
        }
    });

    rejectButton.classList.add('btn', 'bg-danger-subtle', 'text-danger', 'w-100', 'fw-bold', 'decline');
    rejectButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");
    rejectButton.textContent = 'Reject';

    // reject logic


    viewButton.classList.add('btn', 'bg-primary-subtle', 'text-primary', 'w-100', 'fw-bold', 'decline');
    viewButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");
    viewButton.textContent = 'View';

    if (request.status === 'Confirmed') {   
        buttonGroup.textContent = 'No action needed';
    } else {
        buttonGroup.append(confirmButton, rejectButton, viewButton);
    }

    actionCell.appendChild(buttonGroup);

    
    return actionCell;
}

