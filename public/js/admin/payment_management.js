const confirmPaymentModal = new bootstrap.Modal(document.getElementById("confirmPaymentModal"));
const rejectPaymentModal = new bootstrap.Modal(document.getElementById("rejectPaymentModal"));
const messageModal = new bootstrap.Modal(document.getElementById("messageModal"));

const messageTitle = document.getElementById("messageTitle");
const messageBody = document.getElementById("messageBody");

// Add pagination variables
let currentPage = 1;
let limit = 10; // Number of records per page
let currentSort = {
    column: 'payment_id',
    order: 'desc'
};
let currentFilter = 'all';
let payments = [];

// DOM Elements
const tableBody = document.getElementById('tableBody');
const statusSelect = document.getElementById('statusSelect');
const limitSelect = document.getElementById('limitSelect');
const paginationContainer = document.getElementById('paginationContainer');

// Event Listeners
document.querySelectorAll('.sort').forEach(header => {
    header.addEventListener('click', () => handleSort(header));
});

statusSelect.addEventListener('change', () => {
    currentFilter = statusSelect.value;
    currentPage = 1;
    loadPayments();
});

limitSelect.addEventListener('change', () => {
    limit = parseInt(limitSelect.value);
    currentPage = 1;
    loadPayments();
});

// Initial load
loadPayments();

// Add cursor style for sort headers
document.querySelectorAll(".sort").forEach(button => {
    button.style.cursor = "pointer";
    button.style.backgroundColor = "#d1f7c4";
});

// Functions
async function loadPayments() {
    try {
        // Show loading state
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';

        const response = await fetch("/admin/payments/get", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                page: currentPage,
                limit: limit,
                sort: currentSort.column,
                order: currentSort.order,
                filter: currentFilter
            })
        });
        
        if (!response.ok) {
            console.log('Response not OK:', response);
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            payments = data.payments;
            renderPayments();
            renderPagination(data.total);
        } else {
            showMessage('Error', data.message || 'Failed to load payments');
        }
    } catch (error) {
        console.error('Error loading payments:', error);
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center text-danger">Error loading payments. Please try again.</td></tr>';
    }
}

function renderPayments() {
    if (!payments || payments.length === 0) {
        tableBody.innerHTML = '<tr><td colspan="7" class="text-center">No payments found.</td></tr>';
        return;
    }

    console.log("Payments data:", payments);
    
    // Log each payment status to debug
    payments.forEach(payment => {
        console.log(`Payment ID: ${payment.payment_id}, Status: ${payment.status}, Status type: ${typeof payment.status}`);
    });

    tableBody.innerHTML = payments.map(payment => `
        <tr>
            <td>${payment.booking_id}</td>
            <td style="max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="${payment.client_name}">${payment.client_name}</td>
            <td>${formatCurrency(payment.amount)}</td>
            <td >${payment.payment_method}</td>
            <td  title="${payment.payment_date}">${formatDate(payment.payment_date)}</td>
            <td class="text-${getStatusTextClass(payment.status)}" style="width: 85px; font-weight: bold;">${payment.status}</td>
            <td>
                <div class="d-flex justify-content-center gap-2">
                    ${payment.status.toUpperCase() === 'PENDING' ? `
                        <button class="btn bg-success-subtle text-success btn-sm fw-bold w-100 d-flex align-items-center justify-content-center gap-1" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;" onclick="confirmPayment(${payment.payment_id})">
                            <i class="bi bi-check-circle"></i><span>Confirm</span>
                        </button>
                        <button class="btn bg-danger-subtle text-danger btn-sm fw-bold w-100 d-flex align-items-center justify-content-center gap-1" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;" onclick="rejectPayment(${payment.payment_id})">
                            <i class="bi bi-x-circle"></i><span>Reject</span>
                        </button>
                    ` : ''}
                    <button class="btn bg-primary-subtle text-primary btn-sm fw-bold w-100 d-flex align-items-center justify-content-center gap-1" style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;" onclick="viewProof('${payment.proof_of_payment}')">
                        <i class="bi bi-eye"></i><span>View</span>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function renderPagination(total) {
    const totalPages = Math.ceil(total / limit);
    let paginationHTML = '';

    if (totalPages > 1) {
        paginationHTML += `
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center">
                    <li class="page-item ${currentPage === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="changePage(${currentPage - 1})">Previous</a>
                    </li>
        `;

        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                paginationHTML += `
                    <li class="page-item ${i === currentPage ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                    </li>
                `;
            } else if (i === currentPage - 3 || i === currentPage + 3) {
                paginationHTML += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }

        paginationHTML += `
                    <li class="page-item ${currentPage === totalPages ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="changePage(${currentPage + 1})">Next</a>
                    </li>
                </ul>
            </nav>
        `;
    }

    paginationContainer.innerHTML = paginationHTML;
}

function handleSort(header) {
    const column = header.dataset.column;
    
    if (currentSort.column === column) {
        currentSort.order = currentSort.order === 'asc' ? 'desc' : 'asc';
    } else {
        currentSort.column = column;
        currentSort.order = 'asc';
    }

    // Update sort indicators
    document.querySelectorAll('.sort').forEach(h => {
        h.removeAttribute('data-order');
        if (h === header) {
            h.setAttribute('data-order', currentSort.order);
        }
    });

    loadPayments();
}

function changePage(page) {
    if (page < 1 || page > Math.ceil(payments.length / limit)) return;
    currentPage = page;
    loadPayments();
}

// Utility Functions
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'PHP'
    }).format(amount);
}

function formatDate(dateString) {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function getStatusTextClass(status) {
    switch (status.toUpperCase()) {
        case 'PENDING':
            return 'warning';
        case 'CONFIRMED':
            return 'success';
        case 'REJECTED':
            return 'danger';
        default:
            return 'secondary';
    }
}

// Modal Functions
window.confirmPayment = function(paymentId) {
    document.getElementById('confirmPaymentId').value = paymentId;
    confirmPaymentModal.show();
};

window.rejectPayment = function(paymentId) {
    document.getElementById('rejectPaymentId').value = paymentId;
    rejectPaymentModal.show();
};

window.viewProof = function(proofFile) {
    if (!proofFile) {
        showMessage('Error', 'No proof file available');
        return;
    }
    
    // Open the proof file in a new tab with the correct path
    window.open('/app/uploads/payments/' + proofFile, '_blank');
};

function showMessage(title, message) {
    messageTitle.textContent = title;
    messageBody.textContent = message;
    messageModal.show();
}

// Form submission handlers
document.getElementById('confirmPaymentForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const paymentId = document.getElementById('confirmPaymentId').value;
    
    try {
        const response = await fetch('/admin/payments/confirm', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `payment_id=${paymentId}`
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        confirmPaymentModal.hide();
        
        if (data.success) {
            showMessage('Success', 'Payment confirmed successfully');
            loadPayments();
        } else {
            showMessage('Error', data.message || 'Failed to confirm payment');
        }
    } catch (error) {
        console.error('Error:', error);
        showMessage('Error', 'An error occurred while confirming the payment');
    }
});

document.getElementById('rejectPaymentForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const paymentId = document.getElementById('rejectPaymentId').value;
    const reason = document.getElementById('reason').value;
    
    if (!reason.trim()) {
        showMessage('Error', 'Please provide a reason for rejection');
        return;
    }
    
    try {
        const response = await fetch('/admin/payments/reject', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `payment_id=${paymentId}&reason=${encodeURIComponent(reason)}`
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const data = await response.json();
        rejectPaymentModal.hide();
        
        if (data.success) {
            showMessage('Success', 'Payment rejected successfully');
            loadPayments();
        } else {
            showMessage('Error', data.message || 'Failed to reject payment');
        }
    } catch (error) {
        console.error('Error:', error);
        showMessage('Error', 'An error occurred while rejecting the payment');
    }
});