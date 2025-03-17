document.addEventListener('DOMContentLoaded', renderReschedRequest); 

async function getReschedRequest() {
    try {
        const response = await fetch("/admin/get-resched-requests");

        const data = await response.json();
        console.log(data.requests);
        if (data.success) {
            return data.requests;
        }
    } catch (error) {
        console.error('Fetch error:', error);
    }
}

async function renderReschedRequest() {
    const requests = await getReschedRequest();
    console.log(requests);
    const tbody = document.getElementById('tableBody');
    tbody.innerHTML = '';

    requests.forEach(request => {
        const row = document.createElement('tr');
        
        const clientNameCell = document.createElement('td');
        const clientContactCell = document.createElement('td');
        const dateOfTourCell = document.createElement('td');
        const endOfTourCell = document.createElement('td');
        const statusCell = document.createElement('td');

        clientNameCell.textContent = request.client_name;
        clientContactCell.textContent = request.contact_number;
        dateOfTourCell.textContent = request.new_date_of_tour;
        endOfTourCell.textContent = request.new_end_of_tour;
        statusCell.textContent = request.status;

        row.append(clientNameCell, clientContactCell, dateOfTourCell, endOfTourCell, statusCell, actionButtons(request));
        tbody.appendChild(row);
    });
}

function actionButtons(request) {
    const actionCell = document.createElement('td');
    const buttonGroup = document.createElement('div');
    const confirmButton = document.createElement('button');
    const rejectButton = document.createElement('button');

    buttonGroup.classList.add('d-flex', 'gap-2');

    confirmButton.classList.add('btn', 'btn-success', 'btn-sm', 'w-100', 'approve');
    confirmButton.textContent = 'Confrim';

    confirmButton.addEventListener('click', async () => {
        const response = await fetch('/admin/confirm-resched-request', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                requestId: request.request_id, 
                bookingId: request.booking_id,
                dateOfTour: request.new_date_of_tour,
                endOfTour: request.new_end_of_tour
            })
        });

        const data = await response.json();
        if (data.success) {
            renderReschedRequest();
        }
    });

    rejectButton.classList.add('btn', 'btn-danger', 'btn-sm', 'w-100', 'decline');
    rejectButton.textContent = 'Reject';


    if (request.status === 'confirmed') {   
        buttonGroup.textContent = 'No action needed';
    } else {
        buttonGroup.append(confirmButton, rejectButton);
    }

    actionCell.appendChild(buttonGroup);

    
    return actionCell;
}