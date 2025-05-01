const messageModal = new bootstrap.Modal(document.getElementById("messageModal"));

const messageTitle = document.getElementById("messageTitle");
const messageBody = document.getElementById("messageBody");

document.addEventListener("DOMContentLoaded", async function () {
    const limit = document.getElementById("limitSelect").value;
    
    // Initialize stats counters
    await updateBookingStats();
    
    // Check the counts for Pending status
    const pendingBookings = await getAllBookings("Pending", "desc", "booking_id", 1, limit);
    
    // First check if there are any pending records
    if (pendingBookings && pendingBookings.pagination && pendingBookings.pagination.total > 0) {
        // If there are pending records, keep default as Pending
        document.getElementById("statusSelect").value = "Pending";
        renderBookings(pendingBookings);
        renderPagination(pendingBookings.pagination);
    } else {
        // If no pending records, check for confirmed records
        const confirmedBookings = await getAllBookings("Confirmed", "desc", "booking_id", 1, limit);
        
        if (confirmedBookings && confirmedBookings.pagination && confirmedBookings.pagination.total > 0) {
            // If there are confirmed records, set default to Confirmed
            document.getElementById("statusSelect").value = "Confirmed";
            renderBookings(confirmedBookings);
            renderPagination(confirmedBookings.pagination);
        } else {
            // If no pending and no confirmed records, set to All
            document.getElementById("statusSelect").value = "All";
            const allBookings = await getAllBookings("All", "asc", "booking_id", 1, limit);
            renderBookings(allBookings);
            renderPagination(allBookings.pagination);
        }
    }
    
    // Set up view switchers
    setupViewSwitchers();
    
    // Set up search functionality
    setupSearch();
    
    // Set up quick filters
    setupQuickFilters();
    
    // Set up export buttons
    setupExportButtons();
});

// Function to update the booking stats dashboard
async function updateBookingStats() {
    try {
        const response = await fetch("/admin/booking-stats", {
            method: "GET",
            headers: { "Content-Type": "application/json" }
        });

        if (response.ok) {
            const data = await response.json();
            
            if (data.success) {
                // Update the stats counters
                document.getElementById("totalBookingsCount").textContent = data.stats.total || 0;
                document.getElementById("confirmedBookingsCount").textContent = data.stats.confirmed || 0;
                document.getElementById("pendingBookingsCount").textContent = data.stats.pending || 0;
                document.getElementById("upcomingToursCount").textContent = data.stats.upcoming || 0;
            }
        }
    } catch (error) {
        console.error("Error fetching booking stats:", error);
    }
}

// Function to set up view switchers (Table, Card, Calendar)
function setupViewSwitchers() {
    const tableView = document.getElementById("tableView");
    const cardView = document.getElementById("cardView");
    const calendarView = document.getElementById("calendarView");
    
    const tableViewContainer = document.getElementById("tableViewContainer");
    const cardViewContainer = document.getElementById("cardViewContainer");
    const calendarViewContainer = document.getElementById("calendarViewContainer");
    
    tableView.addEventListener("change", function() {
        if (this.checked) {
            tableViewContainer.style.display = "block";
            cardViewContainer.style.display = "none";
            calendarViewContainer.style.display = "none";
            document.getElementById("paginationContainer").style.display = "flex";
        }
    });
    
    cardView.addEventListener("change", function() {
        if (this.checked) {
            tableViewContainer.style.display = "none";
            cardViewContainer.style.display = "flex";
            calendarViewContainer.style.display = "none";
            document.getElementById("paginationContainer").style.display = "flex";
            renderCardView();
        }
    });
    
    calendarView.addEventListener("change", function() {
        if (this.checked) {
            tableViewContainer.style.display = "none";
            cardViewContainer.style.display = "none";
            calendarViewContainer.style.display = "block";
            document.getElementById("paginationContainer").style.display = "none";
            initializeCalendar();
        }
    });
}

// Function to initialize the calendar view
function initializeCalendar() {
    const calendarEl = document.getElementById('bookingCalendar');
    
    if (!calendarEl._calendar) {
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,listWeek'
            },
            events: fetchCalendarEvents,
            eventClick: function(info) {
                showBookingDetails(info.event.id);
            },
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: 'short'
            }
        });
        
        calendar.render();
        calendarEl._calendar = calendar;
    } else {
        calendarEl._calendar.refetchEvents();
    }
}

// Function to fetch calendar events
async function fetchCalendarEvents(info, successCallback, failureCallback) {
    try {
        const start = info.startStr;
        const end = info.endStr;
        
        const response = await fetch("/admin/calendar-bookings", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ start, end })
        });
        
        if (response.ok) {
            const data = await response.json();
            
            if (data.success) {
                // Transform bookings into calendar events
                const events = data.bookings.map(booking => {
                    const statusColors = {
                        'Pending': '#ffc107',
                        'Confirmed': '#198754',
                        'Canceled': '#dc3545',
                        'Rejected': '#6c757d',
                        'Completed': '#0dcaf0'
                    };
                    
                    return {
                        id: booking.booking_id,
                        title: `${booking.client_name} - ${booking.destination}`,
                        start: booking.date_of_tour,
                        end: booking.end_of_tour || booking.date_of_tour,
                        backgroundColor: statusColors[booking.status] || '#6c757d',
                        borderColor: statusColors[booking.status] || '#6c757d',
                        extendedProps: {
                            booking: booking
                        }
                    };
                });
                
                successCallback(events);
            } else {
                failureCallback(new Error('Failed to fetch events'));
            }
        } else {
            failureCallback(new Error('Failed to fetch events'));
        }
    } catch (error) {
        console.error("Error fetching calendar events:", error);
        failureCallback(error);
    }
}

// Function to setup search
function setupSearch() {
    const searchInput = document.getElementById("searchBookings");
    const searchBtn = document.getElementById("searchBtn");
    
    // Search on button click
    searchBtn.addEventListener("click", performSearch);
    
    // Search on Enter key
    searchInput.addEventListener("keyup", function(event) {
        if (event.key === "Enter") {
            performSearch();
        }
    });
    
    // Reset filters button
    document.getElementById("resetFilters")?.addEventListener("click", function() {
        searchInput.value = "";
        document.getElementById("statusSelect").value = "Pending";
        document.getElementById("limitSelect").value = "10";
        refreshBookings();
    });
}

// Function to perform search
async function performSearch() {
    const searchTerm = document.getElementById("searchBookings").value.trim();
    const status = document.getElementById("statusSelect").value;
    const limit = document.getElementById("limitSelect").value;
    
    try {
        const response = await fetch("/admin/search-bookings", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ 
                searchTerm, 
                status,
                limit,
                page: 1 
            })
        });
        
        if (response.ok) {
            const data = await response.json();
            
            if (data.success) {
                renderBookings(data);
                renderPagination(data.pagination);
                
                // Show/hide no results message
                document.getElementById("noResultsFound").style.display = 
                    (!data.bookings || data.bookings.length === 0) ? "block" : "none";
                
                // Update active view
                if (document.getElementById("cardView").checked) {
                    renderCardView();
                }
            }
        }
    } catch (error) {
        console.error("Error searching bookings:", error);
    }
}

// Function to set up quick filters
function setupQuickFilters() {
    const quickFilterBtns = document.querySelectorAll(".quick-filter");

    quickFilterBtns.forEach(button => {
        button.addEventListener("click", function() {
            // Remove active class from all buttons
            quickFilterBtns.forEach(btn => btn.classList.remove("active"));

            // Add active class to the clicked button
            this.classList.add("active");   

            const status = this.getAttribute("data-status");
            const payment = this.getAttribute("data-payment");
            
            if (status) {
                document.getElementById("statusSelect").value = status;
                const event = new Event("change");
                document.getElementById("statusSelect").dispatchEvent(event);
            } else if (payment) {
                // Handle payment filter (Unpaid)
                filterUnpaidBookings();
            }
        });
    });
}

// Function to filter unpaid bookings
async function filterUnpaidBookings() {
    try {
        const limit = document.getElementById("limitSelect").value;
        
        const response = await fetch("/admin/unpaid-bookings", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ 
                limit,
                page: 1 
            })
        });
        
        if (response.ok) {
            const data = await response.json();
            
            if (data.success) {
                renderBookings(data);
                renderPagination(data.pagination);
                
                // Show/hide no results message
                document.getElementById("noResultsFound").style.display = 
                    (!data.bookings || data.bookings.length === 0) ? "block" : "none";
                
                // Update active view
                if (document.getElementById("cardView").checked) {
                    renderCardView();
                }
            }
        }
    } catch (error) {
        console.error("Error fetching unpaid bookings:", error);
    }
}

// Function to setup export buttons
function setupExportButtons() {
    // Export as PDF
    document.getElementById("exportPDF")?.addEventListener("click", function(e) {
        e.preventDefault();
        exportBookings("pdf");
    });
    
    // Export as CSV
    document.getElementById("exportCSV")?.addEventListener("click", function(e) {
        e.preventDefault();
        exportBookings("csv");
    });
    
    // Refresh bookings
    document.getElementById("refreshBookings")?.addEventListener("click", function(e) {
        e.preventDefault();
        refreshBookings();
    });
}

// Function to export bookings
async function exportBookings(format) {
    const status = document.getElementById("statusSelect").value;
    
    try {
        const response = await fetch(`/admin/export-bookings?format=${format}&status=${status}`, {
            method: "GET"
        });
        
        if (response.ok) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement("a");
            a.style.display = "none";
            a.href = url;
            a.download = `bookings_${status.toLowerCase()}_${new Date().toISOString().slice(0, 10)}.${format}`;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
        } else {
            showMessage("Export Failed", "Could not export bookings. Please try again.");
        }
    } catch (error) {
        console.error("Error exporting bookings:", error);
        showMessage("Export Failed", "Could not export bookings. Please try again.");
    }
}

// Function to refresh bookings
async function refreshBookings() {
    const status = document.getElementById("statusSelect").value;
    const limit = document.getElementById("limitSelect").value;
    const column = document.querySelector(".sort.active") ? 
        document.querySelector(".sort.active").getAttribute("data-column") : "booking_id";
    const order = document.querySelector(".sort.active") ? 
        document.querySelector(".sort.active").getAttribute("data-order") : "desc";
    
    // Update stats
    await updateBookingStats();
    
    // Fetch bookings
    const bookings = await getAllBookings(status, order, column, 1, limit);
    renderBookings(bookings);
    renderPagination(bookings.pagination);
    
    // Update active view
    if (document.getElementById("cardView").checked) {
        renderCardView();
    } else if (document.getElementById("calendarView").checked) {
        if (document.getElementById("bookingCalendar")._calendar) {
            document.getElementById("bookingCalendar")._calendar.refetchEvents();
        }
    }
}

// Function to render the card view
function renderCardView() {
    const cardContainer = document.getElementById("cardViewContainer");
    cardContainer.innerHTML = "";
    
    const tbody = document.getElementById("tableBody");
    const rows = tbody.querySelectorAll("tr");
    
    if (rows.length === 1 && rows[0].querySelector("td").colSpan) {
        // No results found, show message
        cardContainer.innerHTML = `
            <div class="col-12 text-center py-5">
                <i class="bi bi-search fs-1 text-muted"></i>
                <h4 class="mt-3">No bookings found</h4>
                <p class="text-muted">Try adjusting your search or filter criteria</p>
            </div>
        `;
        return;
    }
    
    // Get all bookings data from the current API response
    getCurrentBookings().then(bookings => {
        if (!bookings || bookings.length === 0) {
            cardContainer.innerHTML = `
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search fs-1 text-muted"></i>
                    <h4 class="mt-3">No bookings found</h4>
                    <p class="text-muted">Try adjusting your search or filter criteria</p>
                </div>
            `;
            return;
        }
        
        bookings.forEach(booking => {
            const card = document.createElement("div");
            card.className = "col-xl-4 col-md-6";
            
            const statusColors = {
                'Pending': 'warning',
                'Confirmed': 'success',
                'Canceled': 'danger',
                'Rejected': 'secondary',
                'Completed': 'info'
            };
            
            const statusColor = statusColors[booking.status] || 'secondary';
            
            card.innerHTML = `
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-${statusColor}-subtle border-0 py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 fw-bold text-${statusColor}">
                                <i class="bi bi-bookmark me-1"></i> ${booking.status}
                            </h6>
                            <span class="badge bg-${statusColor}">ID: ${booking.booking_id}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-1">${booking.client_name}</h5>
                        <p class="text-muted small mb-2">
                            <i class="bi bi-telephone me-1"></i> ${booking.contact_number}
                        </p>
                        <div class="row mb-2">
                            <div class="col-12">
                                <p class="mb-1">
                                    <i class="bi bi-geo-alt text-success me-1"></i> 
                                    <strong>Destination:</strong> ${booking.destination}
                                </p>
                                <p class="mb-1">
                                    <i class="bi bi-calendar3 text-primary me-1"></i>
                                    <strong>Date:</strong> ${formatDate(booking.date_of_tour)}
                                </p>
                                <p class="mb-1">
                                    <i class="bi bi-hourglass text-warning me-1"></i>
                                    <strong>Days:</strong> ${booking.number_of_days}
                                </p>
                                <p class="mb-1">
                                    <i class="bi bi-bus-front text-danger me-1"></i>
                                    <strong>Buses:</strong> ${booking.number_of_buses}
                                </p>
                                <p class="mb-0">
                                    <i class="bi bi-cash-coin text-success me-1"></i>
                                    <strong>Total Cost:</strong> ${booking.total_cost}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light border-0 py-2">
                        <div class="d-flex gap-2 justify-content-between">
                            <button class="btn btn-sm btn-outline-primary view-booking-btn" data-booking-id="${booking.booking_id}">
                                <i class="bi bi-eye"></i> View
                            </button>
                            <div class="d-flex gap-1">
                                ${booking.status === 'Pending' ? `
                                    <button class="btn btn-sm btn-outline-success confirm-btn" data-booking-id="${booking.booking_id}">
                                        <i class="bi bi-check-circle"></i> Confirm
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger reject-btn" data-booking-id="${booking.booking_id}">
                                        <i class="bi bi-x-circle"></i> Reject
                                    </button>
                                ` : ''}
                                ${booking.status === 'Confirmed' ? `
                                    <button class="btn btn-sm btn-outline-danger cancel-btn" data-booking-id="${booking.booking_id}">
                                        <i class="bi bi-x-circle"></i> Cancel
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            cardContainer.appendChild(card);
        });
        
        // Attach event listeners to the card buttons
        attachCardEventListeners();
    });
}

// Function to attach event listeners to card buttons
function attachCardEventListeners() {
    // View booking
    document.querySelectorAll('.view-booking-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const bookingId = this.getAttribute('data-booking-id');
            showBookingDetails(bookingId);
        });
    });
    
    // Confirm booking
    document.querySelectorAll('.confirm-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const bookingId = this.getAttribute('data-booking-id');
            confirmBooking(bookingId);
        });
    });
    
    // Reject booking
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const bookingId = this.getAttribute('data-booking-id');
            // We'll need to prompt for a reason
            Swal.fire({
                title: 'Reject Booking',
                text: 'Please provide a reason for rejecting this booking',
                input: 'text',
                inputAttributes: {
                    required: true
                },
                showCancelButton: true,
                confirmButtonText: 'Reject',
                confirmButtonColor: '#dc3545',
                cancelButtonText: 'Cancel',
                preConfirm: (reason) => {
                    if (!reason || reason.trim() === '') {
                        Swal.showValidationMessage('Please provide a reason');
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    rejectBooking(bookingId, null, result.value);
                }
            });
        });
    });
    
    // Cancel booking
    document.querySelectorAll('.cancel-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const bookingId = this.getAttribute('data-booking-id');
            // We'll need to prompt for a reason
            Swal.fire({
                title: 'Cancel Booking',
                text: 'Please provide a reason for canceling this booking',
                input: 'text',
                inputAttributes: {
                    required: true
                },
                showCancelButton: true,
                confirmButtonText: 'Cancel Booking',
                confirmButtonColor: '#dc3545',
                cancelButtonText: 'Back',
                preConfirm: (reason) => {
                    if (!reason || reason.trim() === '') {
                        Swal.showValidationMessage('Please provide a reason');
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    cancelBooking(bookingId, null, result.value);
                }
            });
        });
    });
}

// Function to show booking details in a modal
function showBookingDetails(bookingId) {
    // Get full booking details including stops 
    getBookingDetails(bookingId).then(booking => {
        console.log("Booking details received:", booking);
        
        if (!booking) {
            console.error("No booking details found for ID:", bookingId);
            showMessage("Error", "Could not load booking details. Please try again.");
            return;
        }
        
        const bookingDetailsContent = document.getElementById('bookingDetailsContent');
        if (!bookingDetailsContent) {
            console.error("Booking details content element not found in the DOM");
            return;
        }
        
        // Get status color classes
        const statusColors = {
            'Pending': 'warning',
            'Confirmed': 'success',
            'Canceled': 'danger',
            'Rejected': 'secondary',
            'Completed': 'info'
        };
        
        const statusColor = statusColors[booking.status] || 'secondary';
        const paymentStatusColor = getPaymentStatusBadgeClass(booking.payment_status);
        
        bookingDetailsContent.innerHTML = `
            <div class="booking-detail-section mb-3">
                <h6 class="border-bottom pb-2"><i class="bi bi-geo-alt me-2"></i>Booking Information</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Booking ID:</strong> #${booking.booking_id}</p>
                        <p><strong>Booking Date:</strong> ${formatDate(booking.booked_at)}</p>
                        <p><strong>Status:</strong> <span class="status-badge status-${(booking.status || 'pending').toLowerCase()}">${booking.status || 'Pending'}</span></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Client Name:</strong> ${booking.client_name || 'N/A'}</p>    
                        <p><strong>Email:</strong> ${booking.email || 'N/A'}</p>
                        <p><strong>Phone:</strong> ${booking.contact_number || 'N/A'}</p>
                    </div>
                </div>
            </div>
            <div class="booking-detail-section mb-4">
                <h6 class="border-bottom pb-2"><i class="bi bi-geo-alt me-2"></i>Trip Details</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Pickup Point:</strong> ${booking.pickup_point}</p>
                        <p class="mb-2"><strong>Destination:</strong> 
                             
                            ${booking.stops && booking.stops.length > 0 ? 
                                `${booking.stops.map(stop => 
                                    `<span>${stop.location}</span>`
                                ).join('<i class="bi bi-arrow-right mx-1 text-danger"></i>')} 
                                <i class="bi bi-arrow-right mx-1 text-danger"></i>` 
                            : ''}
                            <span>${booking.destination}</span>
                        </p>
                    </div>
                    <div class="col-md-6">
                    <p class="mb-2"><strong>Tour Date:</strong> ${formatDate(booking.date_of_tour)}${booking.end_of_tour ? ` to ${formatDate(booking.end_of_tour)}` : ''}</p>
                        <p class="mb-2"><strong>Duration:</strong> ${booking.number_of_days} day${booking.number_of_days > 1 ? 's' : ''}</p>
                        <p class="mb-2"><strong>Number of Buses:</strong> ${booking.number_of_buses}</p>
                        <p class="mb-2"><strong>Status:</strong> <span class="badge bg-${statusColor}">${booking.status}</span></p>
                    </div>
                </div>
            </div>
            
            <div class="booking-detail-section mb-4">
                <h6 class="border-bottom pb-2"><i class="bi bi-cash-coin me-2"></i>Payment Information</h6>
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Total Cost:</strong> ₱${parseFloat(booking.total_cost).toLocaleString('en-PH')}</p>
                        <p class="mb-2"><strong>Amount Paid:</strong> ₱${parseFloat(booking.amount_paid || 0).toLocaleString('en-PH')}</p>
                        <p class="mb-2"><strong>Balance:</strong> ₱${(parseFloat(booking.total_cost) - parseFloat(booking.amount_paid || 0)).toLocaleString('en-PH')}</p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2"><strong>Payment Status:</strong> 
                            <span class="badge bg-${paymentStatusColor}">
                                ${booking.payment_status}
                            </span>
                        </p>
                        <p><strong>Last Payment Date:</strong> ${booking?.payments && booking.payments.length > 0 && booking.payments[0]?.payment_date ? formatDate(booking.payments[0].payment_date) : 'No payments yet'}</p>
                        <p><strong>Payment Method:</strong> ${booking?.payments && booking.payments.length > 0 ? booking.payments[0]?.payment_method || 'N/A' : 'N/A'}</p>
                    </div>
                </div>
            </div>
            
            <div class="booking-detail-section mb-2">
                <h6 class="text-success mb-3"><i class="bi bi-list-check me-2"></i>Actions</h6>
                <div class="d-flex flex-wrap gap-2">
                    ${booking.status === "Pending" ? `
                        <button class="btn btn-sm btn-outline-success confirm-booking-modal" data-booking-id="${booking.booking_id}">
                            <i class="bi bi-check-circle"></i> Confirm Booking
                        </button>
                        <button class="btn btn-sm btn-outline-danger reject-booking-modal" data-booking-id="${booking.booking_id}" data-user-id="${booking.user_id}">
                            <i class="bi bi-x-circle"></i> Reject Booking
                        </button>
                    ` : ''}
                    
                    ${booking.status === "Confirmed" ? `
                        <button class="btn btn-sm btn-outline-danger cancel-booking-modal" data-booking-id="${booking.booking_id}" data-user-id="${booking.user_id}">
                            <i class="bi bi-x-circle"></i> Cancel Booking
                        </button>
                    ` : ''}
                    
                    <button class="btn btn-sm btn-outline-primary view-invoice" data-booking-id="${booking.booking_id}">
                        <i class="bi bi-file-earmark-text"></i> Invoice
                    </button>
                    
                    <button class="btn btn-sm btn-outline-success view-contract" data-booking-id="${booking.booking_id}">
                        <i class="bi bi-file-earmark-text"></i> Contract
                    </button>
                </div>
            </div>
        `;
        
        // Add event listeners to action buttons
        const viewInvoiceBtn = bookingDetailsContent.querySelector(".view-invoice");
        if (viewInvoiceBtn) {
            viewInvoiceBtn.addEventListener("click", function () {
                const bookingId = this.getAttribute("data-booking-id");
                window.open(`/admin/print-invoice/${bookingId}`);
            });
        }

        const viewContractBtn = bookingDetailsContent.querySelector(".view-contract");
        if (viewContractBtn) {
            viewContractBtn.addEventListener("click", function () {
                const bookingId = this.getAttribute("data-booking-id");
                window.open(`/admin/print-contract/${bookingId}`);
            });
        }
        
        const confirmBtn = bookingDetailsContent.querySelector('.confirm-booking-modal');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                const bookingId = this.getAttribute('data-booking-id');
                
                // Close the modal before showing SweetAlert
                bootstrap.Modal.getInstance(document.getElementById('bookingDetailsModal')).hide();
                
                Swal.fire({
                    title: 'Enter Discount Rate',
                    text: 'Enter a discount percentage (0-100)',
                    input: 'number',
                    inputPlaceholder: 'e.g., 15 for 15%',
                    showCancelButton: true,
                    confirmButtonText: 'Confirm Booking',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    inputAttributes: {
                        min: 0,
                        max: 100,
                        step: 0.01
                    },
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Please enter a discount rate';
                        }
                        const numValue = parseFloat(value);
                        if (isNaN(numValue) || numValue < 0 || numValue > 100) {
                            return 'Discount must be between 0 and 100';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const discount = parseFloat(result.value || 0);
                        confirmBooking(bookingId, discount);
                    }
                });
            });
        }
        
        const rejectBtn = bookingDetailsContent.querySelector('.reject-booking-modal');
        if (rejectBtn) {
            rejectBtn.addEventListener('click', function() {
                const bookingId = this.getAttribute('data-booking-id');
                const userId = this.getAttribute('data-user-id');
                
                // Close the modal before showing SweetAlert
                bootstrap.Modal.getInstance(document.getElementById('bookingDetailsModal')).hide();
                
                Swal.fire({
                    title: 'Reject Booking?',
                    html: '<p>Are you sure you want to reject this booking request?</p>',
                    input: 'textarea',
                    inputPlaceholder: 'Kindly provide the reason here.',
                    inputAttributes: {
                        'aria-label': 'Rejection reason'
                    },
                    footer: '<p class="text-secondary mb-0">Note: This action cannot be undone.</p>',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Reject',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    showCloseButton: true,
                    focusConfirm: false,
                    allowOutsideClick: false,
                    width: '32em',
                    padding: '1em',
                    didOpen: () => {
                        // Fix textarea styling
                        const textarea = Swal.getInput();
                        textarea.style.height = '120px';
                        textarea.style.marginTop = '10px';
                        textarea.style.marginBottom = '10px';
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const reason = result.value;
                        rejectBooking(bookingId, userId, reason);
                    }
                });
            });
        }
        
        const cancelBtn = bookingDetailsContent.querySelector('.cancel-booking-modal');
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                const bookingId = this.getAttribute('data-booking-id');
                const userId = this.getAttribute('data-user-id');
                
                // Close the modal before showing SweetAlert
                bootstrap.Modal.getInstance(document.getElementById('bookingDetailsModal')).hide();
                
                Swal.fire({
                    title: 'Cancel Booking?',
                    html: '<p>Are you sure you want to cancel this booking?</p>',
                    input: 'textarea',
                    inputPlaceholder: 'Kindly provide the reason here.',
                    inputAttributes: {
                        'aria-label': 'Cancellation reason'
                    },
                    footer: '<p class="text-secondary mb-0">Note: This action cannot be undone.</p>',
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    confirmButtonText: 'Confirm',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    showCloseButton: true,
                    focusConfirm: false,
                    allowOutsideClick: false,
                    width: '32em',
                    padding: '1em',
                    didOpen: () => {
                        // Fix textarea styling
                        const textarea = Swal.getInput();
                        textarea.style.height = '120px';
                        textarea.style.marginTop = '10px';
                        textarea.style.marginBottom = '10px';
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const reason = result.value;
                        cancelBooking(bookingId, userId, reason);
                    }
                });
            });
        }
        
        try {
            // Show the modal
            console.log("Attempting to show modal");
            const bookingDetailsModal = new bootstrap.Modal(document.getElementById('bookingDetailsModal'));
            bookingDetailsModal.show();
            console.log("Modal shown successfully");
        } catch (error) {
            console.error("Error showing modal:", error);
        }
    }).catch(error => {
        console.error("Error in showBookingDetails:", error);
        showMessage("Error", "An error occurred while loading booking details.");
    });
}

// Function to get detailed booking information including stops
async function getBookingDetails(bookingId) {
    try {
        const response = await fetch("/admin/get-booking-details", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ bookingId })
        });

        if (response.ok) {
            const data = await response.json();
            console.log("Booking details response:", data);
            
            if (data.success) {
                return data.booking; // The API returns data.booking not data
            }
        }
        
        // If there's an error, fall back to the current bookings data
        const currentBookings = await getCurrentBookings();
        const booking = currentBookings.find(b => b.booking_id == bookingId);
        console.log("Fallback to current bookings:", booking);
        return booking;
        
    } catch (error) {
        console.error("Error fetching booking details:", error);
        
        // If there's an exception, fall back to the current bookings data
        const currentBookings = await getCurrentBookings();
        return currentBookings.find(b => b.booking_id == bookingId);
    }
}

// Helper function to get payment status badge class
function getPaymentStatusBadgeClass(status) {
    switch (status) {
        case 'Paid': return 'success';
        case 'Partially Paid': return 'warning';
        case 'Unpaid': return 'danger';
        default: return 'secondary';
    }
}

// Helper function to get current bookings data
async function getCurrentBookings() {
    const status = document.getElementById("statusSelect").value;
    const limit = document.getElementById("limitSelect").value;
    const currentPage = document.querySelector(".pagination .active") ? 
        parseInt(document.querySelector(".pagination .active").textContent) : 1;
    
    const column = document.querySelector(".sort.active") ? 
        document.querySelector(".sort.active").getAttribute("data-column") : "booking_id";
    const order = document.querySelector(".sort.active") ? 
        document.querySelector(".sort.active").getAttribute("data-order") : "desc";
    
    const response = await getAllBookings(status, order, column, currentPage, limit);
    return response.bookings;
}

// Function to show message modal
function showMessage(title, message) {
    messageTitle.textContent = title;
    messageBody.textContent = message;
    messageModal.show();
}

document.getElementById("statusSelect").addEventListener("change", async function () {
    const status = this.value;  
    const limit = document.getElementById("limitSelect").value;
    console.log(status);    
    const bookings = await getAllBookings(status, "asc", "client_name", 1, limit);
    renderBookings(bookings);
    renderPagination(bookings.pagination);
    
    // Update card view if active
    if (document.getElementById("cardView").checked) {
        renderCardView();
    }
});

document.getElementById("limitSelect").addEventListener("change", async function () {
    const status = document.getElementById("statusSelect").value;
    const limit = this.value;
    const column = document.querySelector(".sort.active") ? 
        document.querySelector(".sort.active").getAttribute("data-column") : "client_name";
    const order = document.querySelector(".sort.active") ? 
        document.querySelector(".sort.active").getAttribute("data-order") : "asc";
    
    const bookings = await getAllBookings(status, order, column, 1, limit);
    renderBookings(bookings);
    renderPagination(bookings.pagination);
});

document.querySelectorAll(".sort").forEach(button => {
    button.addEventListener("click", async function () {
        // Clear active class from all headers
        document.querySelectorAll(".sort").forEach(header => {
            header.classList.remove("active");
            
            // Reset sort icons
            const icon = header.querySelector(".sort-icon");
            if (icon) {
                icon.textContent = "↑";
            }
        });
        
        // Add active class to the clicked header
        this.classList.add("active");
        
        const status = document.getElementById("statusSelect").value;
        const column = this.getAttribute("data-column");
        const order = this.getAttribute("data-order");
        const limit = document.getElementById("limitSelect").value;
        const currentPage = document.querySelector(".pagination .active") ? 
            parseInt(document.querySelector(".pagination .active").textContent) : 1;

        // Update sort icon
        const sortIcon = this.querySelector(".sort-icon");
        if (sortIcon) {
            sortIcon.textContent = order === "asc" ? "↑" : "↓";
        }

        const bookings = await getAllBookings(status, order, column, currentPage, limit);
        console.log(bookings);
        renderBookings(bookings);
        renderPagination(bookings.pagination);
        
        // Toggle sort order for next click
        this.setAttribute("data-order", order === "asc" ? "desc" : "asc");
    });
});

function formatDate(date) {
    return new Date(date).toLocaleDateString("en-US", {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

async function getAllBookings(status, order, column, page = 1, limit = 10) {
    try {
        const response = await fetch("/admin/bookings", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ status, order, column, page, limit })
        });

        const data = await response.json();
        console.log("API Response:", data);
        console.log("Pagination details - Total:", data.pagination.total, "TotalPages:", data.pagination.totalPages, "Limit:", data.pagination.limit);

        if (data.success) {
            return data;
        }
    } catch (error) {
        console.error(error);
    }
}

async function renderBookings(data) {
    const bookings = data.bookings;
    const tbody = document.getElementById("tableBody");
    console.log("Booking table: ", bookings);
    tbody.innerHTML = "";

    if (!bookings || bookings.length === 0) {
        const row = document.createElement("tr");
        const cell = document.createElement("td");
        cell.colSpan = 10; // Updated column count
        cell.textContent = "No records found";
        cell.className = "text-center";
        row.appendChild(cell);
        tbody.appendChild(row);

        return;
    }

    bookings.forEach(booking => {
        const row = document.createElement("tr");

        // Create booking ID cell
        const bookingIdCell = document.createElement("td");
        bookingIdCell.textContent = booking.booking_id;
        bookingIdCell.style.width = "80px";

        const clientNameCell = document.createElement("td");
        const contactNumberCell = document.createElement("td");
        const destinationCell = document.createElement("td");
        const pickupPointCell = document.createElement("td");
        const dateOfTourCell = document.createElement("td");
        const endOfTourCell = document.createElement("td");
        const numberOfDaysCell = document.createElement("td");
        const numberOfBusesCell = document.createElement("td");
        const statusCell = document.createElement("td");
        const paymentStatusCell = document.createElement("td");

       
        clientNameCell.textContent = booking.client_name;
        clientNameCell.style.maxWidth = "120px";
        clientNameCell.style.overflow = "hidden";
        clientNameCell.style.textOverflow = "ellipsis";
        clientNameCell.style.whiteSpace = "nowrap";
        clientNameCell.title = booking.client_name;
        
        contactNumberCell.style.maxWidth = "80px";
        contactNumberCell.style.overflow = "hidden";
        contactNumberCell.style.textOverflow = "ellipsis";
        contactNumberCell.style.whiteSpace = "nowrap";
        contactNumberCell.textContent = booking.contact_number;
        
        destinationCell.textContent = booking.destination;
        destinationCell.style.maxWidth = "150px";
        destinationCell.style.overflow = "hidden";
        destinationCell.style.textOverflow = "ellipsis";
        destinationCell.style.whiteSpace = "nowrap";
        destinationCell.title = booking.destination;
        
        pickupPointCell.textContent = parseFloat(booking.total_cost).toLocaleString('en-PH', { style: 'currency', currency: 'PHP' });
        
        dateOfTourCell.textContent = formatDate(booking.date_of_tour);
        dateOfTourCell.style.maxWidth = "120px";
        dateOfTourCell.style.overflow = "hidden";
        dateOfTourCell.style.textOverflow = "ellipsis";
        dateOfTourCell.style.whiteSpace = "nowrap";
        dateOfTourCell.title = formatDate(booking.date_of_tour);
        
        numberOfDaysCell.textContent = booking.number_of_days;
        numberOfBusesCell.textContent = booking.number_of_buses;
        
        // Apply status styling
        statusCell.textContent = booking.status;
        statusCell.className = `text-${getStatusTextClass(booking.status)}`;
        statusCell.style.width = "100px";
        statusCell.style.fontWeight = "bold";
        
        paymentStatusCell.textContent = booking.payment_status;
        paymentStatusCell.className = `text-${getPaymentStatusTextClass(booking.payment_status)}`;
        paymentStatusCell.style.width = "120px";
        paymentStatusCell.style.fontWeight = "bold";
        paymentStatusCell.style.whiteSpace = "nowrap";
        paymentStatusCell.style.overflow = "hidden";
        paymentStatusCell.style.textOverflow = "ellipsis";
        paymentStatusCell.title = booking.payment_status;

        // Add the booking ID as the first column
        row.append(bookingIdCell, clientNameCell, contactNumberCell, destinationCell, pickupPointCell, dateOfTourCell, numberOfDaysCell, numberOfBusesCell, paymentStatusCell, actionButton(booking));
        tbody.appendChild(row);
    });
}

function actionButton(booking) {
    const actionCell = document.createElement("td");
    const buttonSection = document.createElement("div");
    buttonSection.className = 'actions-compact d-flex gap-2 justify-content-start';

    // View Details Button
    buttonSection.appendChild(
        createActionButton(
            'btn btn-sm btn-outline-primary',
            'bi bi-info-circle',
            'Details',
            function() {
                showBookingDetails(booking.booking_id);
            }
        )
    );

    // Confirm Button - only for Pending bookings
    if (booking.status === "Pending") {
        buttonSection.appendChild(
            createActionButton(
                'btn btn-sm btn-outline-success',
                'bi bi-check-circle',
                'Confirm',
                function() {
                    const bookingId = booking.booking_id;
        
                    Swal.fire({
                        title: 'Enter Discount Rate',
                        text: 'Enter a discount percentage (0-100)',
                        input: 'number',
                        inputPlaceholder: 'e.g., 15 for 15%',
                        showCancelButton: true,
                        confirmButtonText: 'Confirm Booking',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        inputAttributes: {
                            min: 0,
                            max: 100,
                            step: 0.01
                        },
                        inputValidator: (value) => {
                            if (!value) {
                                return 'Please enter a discount rate';
                            }
                            const numValue = parseFloat(value);
                            if (isNaN(numValue) || numValue < 0 || numValue > 100) {
                                return 'Discount must be between 0 and 100';
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const discount = parseFloat(result.value || 0);
                            confirmBooking(bookingId, discount);
                        }
                    });
                }
            )
        );
    }

    // Reject Button - only for Pending bookings
    if (booking.status === "Pending") {
        buttonSection.appendChild(
            createActionButton(
                'btn btn-sm btn-outline-danger',
                'bi bi-x-circle',
                'Reject',
                function() {
                    const bookingId = booking.booking_id;
                    const userId = booking.user_id;
        
        Swal.fire({
            title: 'Reject Booking?',
            html: '<p>Are you sure you want to reject this booking request?</p>',
            input: 'textarea',
            inputPlaceholder: 'Kindly provide the reason here.',
            inputAttributes: {
                'aria-label': 'Rejection reason'
            },
            footer: '<p class="text-secondary mb-0">Note: This action cannot be undone.</p>',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Reject',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            showCloseButton: true,
            focusConfirm: false,
            allowOutsideClick: false,
            width: '32em',
            padding: '1em',
            didOpen: () => {
                // Fix textarea styling
                const textarea = Swal.getInput();
                textarea.style.height = '120px';
                textarea.style.marginTop = '10px';
                textarea.style.marginBottom = '10px';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const reason = result.value;
                rejectBooking(bookingId, userId, reason);
            }
        });
                }
            )
        );
    }

    // Cancel Button - only for Confirmed bookings
    if (booking.status === "Confirmed") {
        buttonSection.appendChild(
            createActionButton(
                'btn btn-sm btn-outline-danger',
                'bi bi-x-circle',
                'Cancel',
                function() {
                    const bookingId = booking.booking_id;
                    const userId = booking.user_id;
        
        Swal.fire({
            title: 'Cancel Booking?',
            html: '<p>Are you sure you want to cancel this booking?</p>',
            input: 'textarea',
            inputPlaceholder: 'Kindly provide the reason here.',
            inputAttributes: {
                'aria-label': 'Cancellation reason'
            },
            footer: '<p class="text-secondary mb-0">Note: This action cannot be undone.</p>',
            showCancelButton: true,
            cancelButtonText: 'Cancel',
            confirmButtonText: 'Confirm',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            showCloseButton: true,
            focusConfirm: false,
            allowOutsideClick: false,
            width: '32em',
            padding: '1em',
            didOpen: () => {
                // Fix textarea styling
                const textarea = Swal.getInput();
                textarea.style.height = '120px';
                textarea.style.marginTop = '10px';
                textarea.style.marginBottom = '10px';
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const reason = result.value;
                cancelBooking(bookingId, userId, reason);
            }
        });
                }
            )
        );
    }

    actionCell.appendChild(buttonSection);
    return actionCell;
}

// Helper function to create action buttons
function createActionButton(className, iconClass, text, clickHandler) {
    const button = document.createElement('button');
    button.className = className + ' d-inline-flex align-items-center justify-content-center';
    button.title = text;
    
    // Create icon element
    const icon = document.createElement('i');
    icon.className = iconClass + ' me-1';
    button.appendChild(icon);
    
    // Add text as span
    const textSpan = document.createElement('span');
    textSpan.textContent = text;
    button.appendChild(textSpan);
    
    button.addEventListener('click', clickHandler);
    return button;
}

function renderPagination(pagination) {
    if (!pagination) {
        console.log("No pagination data provided");
        return;
    }
    
    // Make sure totalPages and currentPage are treated as numbers
    const totalPages = parseInt(pagination.totalPages, 10);
    const currentPage = parseInt(pagination.currentPage, 10);
    
    if (totalPages <= 1) {
        // Clear the pagination container if no pagination is needed
        const paginationContainer = document.getElementById("paginationContainer");
        if (paginationContainer) {
            paginationContainer.innerHTML = "";
        }
        return;
    }
    
    // Use the centralized pagination utility
    createPagination({
        containerId: "paginationContainer",
        totalPages: totalPages,
        currentPage: currentPage,
        paginationType: 'advanced',
        onPageChange: async (page) => {
            const status = document.getElementById("statusSelect").value;
            const column = document.querySelector(".sort.active") ? 
                document.querySelector(".sort.active").getAttribute("data-column") : "client_name";
            const order = document.querySelector(".sort.active") ? 
                document.querySelector(".sort.active").getAttribute("data-order") : "asc";
            const limit = document.getElementById("limitSelect").value;
            
            const bookings = await getAllBookings(status, order, column, page, limit);
            renderBookings(bookings);
            renderPagination(bookings.pagination);
        }
    });
}

// New function to handle confirm booking API call
async function confirmBooking(bookingId, discount) {
    try {
        const response = await fetch("/admin/confirm-booking", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ bookingId, discount })
        });
    
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message || 'Booking confirmed successfully.',
                timer: 2000,
                timerProgressBar: true
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to confirm booking.',
                timer: 2000,
                timerProgressBar: true
            });
        }
        
        const status = document.getElementById("statusSelect").value;
        const limit = document.getElementById("limitSelect").value;
        const bookings = await getAllBookings(status, "asc", "booking_id", 1, limit);
        renderBookings(bookings);
        renderPagination(bookings.pagination);
    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred. Please try again.',
            timer: 2000,
            timerProgressBar: true
        });
    }
}

// New function to handle reject booking API call
async function rejectBooking(bookingId, userId, reason) {
    try {
        const response = await fetch("/admin/reject-booking", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ bookingId, reason, userId })
        });
    
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message || 'Booking rejected successfully.',
                timer: 2000,
                timerProgressBar: true
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to reject booking.',
                timer: 2000,
                timerProgressBar: true
            });
        }
        
        const status = document.getElementById("statusSelect").value;
        const limit = document.getElementById("limitSelect").value;
        const bookings = await getAllBookings(status, "asc", "booking_id", 1, limit);
        renderBookings(bookings);
        renderPagination(bookings.pagination);
    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred. Please try again.',
            timer: 2000,
            timerProgressBar: true
        });
    }
}

// New function to handle cancel booking API call
async function cancelBooking(bookingId, userId, reason) {
    try {
        const response = await fetch("/admin/cancel-booking", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ bookingId, userId, reason })
        });
    
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: data.message || 'Booking canceled successfully.',
                timer: 2000,
                timerProgressBar: true
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'Failed to cancel booking.',
                timer: 2000,
                timerProgressBar: true
            });
        }
        
        const status = document.getElementById("statusSelect").value;
        const limit = document.getElementById("limitSelect").value;
        const bookings = await getAllBookings(status, "booking_id", "asc", 1, limit);
        renderBookings(bookings);
        renderPagination(bookings.pagination);
    } catch (error) {
        console.error(error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'An unexpected error occurred. Please try again.',
            timer: 2000,
            timerProgressBar: true
        });
    }
}

// Add status text color helper function
function getStatusTextClass(status) {
    switch (status) {
        case 'Pending':
            return 'warning';
        case 'Confirmed':
            return 'success';
        case 'Processing':
            return 'info';
        case 'Canceled':
        case 'Rejected':
            return 'danger';
        case 'Completed':
            return 'primary';
        default:
            return 'secondary';
    }
}

// Add payment status text color helper function
function getPaymentStatusTextClass(status) {
    switch (status) {
        case 'Paid':
            return 'success';
        case 'Partially Paid':
            return 'warning';
        case 'Unpaid':
            return 'danger';
        default:
            return 'secondary';
    }
}

fetch('/admin/check-payment-deadlines')
  .then(response => response.json())
  .then(data => {
    // Optionally handle the result
    console.log(data);
  });

// Also check for completed bookings
fetch('/admin/check-booking-completions')
  .then(response => response.json())
  .then(data => {
    // Optionally handle the result
    console.log(data);
  });
