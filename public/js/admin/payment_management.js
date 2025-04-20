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

// Add cursor style for sort headers
document.querySelectorAll(".sort").forEach(button => {
    button.style.cursor = "pointer";
    button.style.backgroundColor = "#d1f7c4";
});

// Initialize the page with the sort indicators and check for available payment records
document.addEventListener("DOMContentLoaded", async function() {
    // Add initial sort indicator to the default sorted column
    const initialSortHeader = document.querySelector(`.sort[data-column="${currentSort.column}"]`);
    if (initialSortHeader) {
        initialSortHeader.setAttribute('data-order', currentSort.order);
        
        const icon = document.createElement('span');
        icon.className = 'sort-icon ms-1';
        icon.innerHTML = currentSort.order === 'asc' ? '&#9650;' : '&#9660;';
        initialSortHeader.appendChild(icon);
    }
    
    // Check for PENDING payments first
    try {
        const pendingResponse = await fetch("/admin/payments/get", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                page: 1,
                limit: limit,
                sort: currentSort.column,
                order: currentSort.order,
                filter: 'PENDING'
            })
        });
        
        if (pendingResponse.ok) {
            const pendingData = await pendingResponse.json();
            
            if (pendingData.success && pendingData.total > 0) {
                // If there are pending payments, keep filter as PENDING
                currentFilter = 'PENDING';
                statusSelect.value = 'PENDING';
                payments = pendingData.payments;
                renderPayments();
                renderPagination(pendingData.total);
                return;
            }
            
            // If no pending payments, check for CONFIRMED payments
            const confirmedResponse = await fetch("/admin/payments/get", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    page: 1,
                    limit: limit,
                    sort: currentSort.column,
                    order: currentSort.order,
                    filter: 'CONFIRMED'
                })
            });
            
            if (confirmedResponse.ok) {
                const confirmedData = await confirmedResponse.json();
                
                if (confirmedData.success && confirmedData.total > 0) {
                    // If there are confirmed payments, set filter to CONFIRMED
                    currentFilter = 'CONFIRMED';
                    statusSelect.value = 'CONFIRMED';
                    payments = confirmedData.payments;
                    renderPayments();
                    renderPagination(confirmedData.total);
                    return;
                }
                
                // If no pending and no confirmed payments, load all payments
                currentFilter = 'all';
                statusSelect.value = 'all';
                loadPayments();
            }
        }
    } catch (error) {
        console.error('Error during initial status check:', error);
        // If any error occurs, fall back to loading all payments
        loadPayments();
    }
});

// Initial load - this will be triggered only if DOMContentLoaded event doesn't handle loading
// Keep this as a fallback
setTimeout(() => {
    if (payments.length === 0) {
        loadPayments();
    }
}, 500);

// Functions
async function loadPayments() {
    try {
        // Store the current content for fallback if there's an error
        const currentContent = tableBody.innerHTML;
        
        // Don't show loading state when just changing pages or sorting
        // Only show loading state if the table is empty
        if (!payments.length) {
            tableBody.innerHTML = '<tr><td colspan="7" class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';
        }

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
            // Restore previous content on failure
            if (currentContent && currentContent !== '') {
                tableBody.innerHTML = currentContent;
            }
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
    
    // Skip rendering if only one page
    if (totalPages <= 1) {
        paginationContainer.innerHTML = '';
        return;
    }
    
    // Use the centralized pagination utility
    createPagination({
        containerId: paginationContainer.id,
        totalPages: totalPages,
        currentPage: currentPage,
        paginationType: 'standard',
        onPageChange: (page) => {
            changePage(page);
        }
    });
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
    });
    
    // Add the data-order attribute to the clicked header
    header.setAttribute('data-order', currentSort.order);
    
    // Add visual indicator of sort direction
    document.querySelectorAll('.sort .sort-icon').forEach(icon => {
        icon.remove();
    });
    
    const icon = document.createElement('span');
    icon.className = 'sort-icon ms-1';
    icon.innerHTML = currentSort.order === 'asc' ? '&#9650;' : '&#9660;';
    header.appendChild(icon);
    
    // Load payments with new sort
    loadPayments();
}

function changePage(page) {
    if (page < 1) return;
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