const messageModal = new bootstrap.Modal(document.getElementById("messageModal"));

const messageTitle = document.getElementById("messageTitle");
const messageBody = document.getElementById("messageBody");

// Add pagination variables
let currentPage = 1;
let limit = 10; // Number of records per page
let currentSort = {
    column: 'booking_id',
    order: 'desc'
};
let currentFilter = 'Pending';
let searchQuery = '';
let bookings = [];

// Helper function for formatting dates
function formatDate(date) {
    if (!date) return 'N/A';
    
    return new Date(date).toLocaleDateString("en-US", {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

document.addEventListener("DOMContentLoaded", async function () {
    // Get initial limit from the select
    limit = parseInt(document.getElementById("limitSelect").value);
    
    // Initialize stats counters
    await updateBookingStats();
    
    // Add initial sort indicator to the default sorted column
    updateSortIcons();
    
    // Check the counts for Pending status
    try {
        const pendingResponse = await fetch("/admin/bookings", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ 
                status: "Pending", 
                order: currentSort.order, 
                column: currentSort.column, 
                page: 1, 
                limit: limit 
            })
        });
        
        if (pendingResponse.ok) {
            const pendingData = await pendingResponse.json();
            
            // First check if there are any pending records
            if (pendingData.success && pendingData.pagination && pendingData.pagination.total > 0) {
                // If there are pending records, keep default as Pending
                currentFilter = "Pending";
                document.getElementById("statusSelect").value = "Pending";
                bookings = pendingData.bookings;
                renderBookings();
                renderPagination(pendingData.pagination.total, pendingData.pagination.totalPages);
                document.querySelectorAll('.quick-filter').forEach(b => b.classList.remove('active'));
                document.querySelector('.quick-filter[data-status="Pending"]').classList.add('active');
            } else {
                // If no pending records, check for confirmed records
                const confirmedResponse = await fetch("/admin/bookings", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ 
                        status: "Confirmed", 
                        order: currentSort.order, 
                        column: currentSort.column, 
                        page: 1, 
                        limit: limit 
                    })
                });
                
                if (confirmedResponse.ok) {
                    const confirmedData = await confirmedResponse.json();
                    
                    if (confirmedData.success && confirmedData.pagination && confirmedData.pagination.total > 0) {
                        // If there are confirmed records, set default to Confirmed
                        currentFilter = "Confirmed";
                        document.getElementById("statusSelect").value = "Confirmed";
                        bookings = confirmedData.bookings;
                        renderBookings();
                        renderPagination(confirmedData.pagination.total, confirmedData.pagination.totalPages);
                        document.querySelectorAll('.quick-filter').forEach(b => b.classList.remove('active'));
                        document.querySelector('.quick-filter[data-status="Confirmed"]').classList.add('active');
                    } else {
                        // If no pending and no confirmed records, set to All
                        currentFilter = "All";
                        document.getElementById("statusSelect").value = "All";
                        loadBookings();
                    }
                }
            }
        }
    } catch (error) {
        console.error("Error during initial status check:", error);
        // If any error occurs, fall back to loading all bookings
        loadBookings();
    }
    
    // Set up view switchers
    setupViewSwitchers();
    
    // Set up search functionality
    setupSearch();
    
    // Set up quick filters
    setupQuickFilters();
    
    // Set up export buttons
    setupExportButtons();
    
    // Set up sorting
    setupSorting();
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
    searchBtn.addEventListener("click", function() {
        searchQuery = searchInput.value.trim();
        currentPage = 1;
        loadBookings();
    });
    
    // Search on Enter key
    searchInput.addEventListener("keyup", function(event) {
        if (event.key === "Enter") {
            searchQuery = searchInput.value.trim();
            currentPage = 1;
            loadBookings();
        }
    });
    
    // Reset filters button
    document.getElementById("resetFilters")?.addEventListener("click", function() {
        // Hide no results message
        document.getElementById("noResultsFound").style.display = "none";
        
        // Remove active class from all filter buttons
        document.querySelectorAll(".quick-filter").forEach(btn => btn.classList.remove("active"));
        
        // Reset form inputs
        searchInput.value = "";
        searchQuery = "";
        document.getElementById("statusSelect").value = "All";
        currentFilter = "All";

        const matchingBtn = document.querySelector(`.quick-filter[data-status="${currentFilter}"]`);
        if (matchingBtn) matchingBtn.classList.add("active");
        
        document.getElementById("limitSelect").value = "10";
        limit = 10;
        currentPage = 1;
        
        loadBookings();
    });
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

            // Hide no results message initially when switching filters
            document.getElementById("noResultsFound").style.display = "none";

            const status = this.getAttribute("data-status");
            const payment = this.getAttribute("data-payment");
            
            // Reset to first page when filtering
            currentPage = 1;
            
            if (status) {
                currentFilter = status;
                document.getElementById("statusSelect").value = status;
            }
            
            // Load bookings with the new filter
            loadBookings();
        });
    });
    
    // Status select change
    document.getElementById("statusSelect").addEventListener("change", function() {
        currentFilter = this.value;
        currentPage = 1;
        
        // Update active state on quick filters
        document.querySelectorAll(".quick-filter").forEach(btn => btn.classList.remove("active"));
        const matchingBtn = document.querySelector(`.quick-filter[data-status="${currentFilter}"]`);
        if (matchingBtn) {
            matchingBtn.classList.add("active");
        }
        
        loadBookings();
    });
    
    // Limit select change
    document.getElementById("limitSelect").addEventListener("change", function() {
        limit = parseInt(this.value);
        currentPage = 1;
        loadBookings();
    });
}

// Function to filter unpaid bookings
async function filterUnpaidBookings() {
    try {
        // Hide no results message initially while loading
        document.getElementById("noResultsFound").style.display = "none";
        
        const limit = document.getElementById("limitSelect").value;
        // Get current sort settings (if any)
        const column = document.querySelector(".sort.active") ? 
            document.querySelector(".sort.active").getAttribute("data-column") : "booking_id";
        const order = document.querySelector(".sort.active") ? 
            document.querySelector(".sort.active").getAttribute("data-order") : "desc";
        
        const response = await fetch("/admin/unpaid-bookings", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ 
                limit,
                page: 1,
                column,
                order
            })
        });
        
        if (response.ok) {
            const data = await response.json();
            
            if (data.success) {
                // Update sort headers to reflect current sort state
                updateSortHeaders(column, order);
                
                renderBookings(data);
                renderPagination(data.pagination.total, data.pagination.totalPages);
                
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

// Function to filter partially paid bookings
async function filterPartiallyPaidBookings() {
    try {
        // Hide no results message initially while loading
        document.getElementById("noResultsFound").style.display = "none";
        
        const limit = document.getElementById("limitSelect").value;
        // Get current sort settings (if any)
        const column = document.querySelector(".sort.active") ? 
            document.querySelector(".sort.active").getAttribute("data-column") : "booking_id";
        const order = document.querySelector(".sort.active") ? 
            document.querySelector(".sort.active").getAttribute("data-order") : "desc";
        
        const response = await fetch("/admin/partially-paid-bookings", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ 
                limit,
                page: 1,
                column,
                order
            })
        });
        
        if (response.ok) {
            const data = await response.json();
            
            if (data.success) {
                // Update sort headers to reflect current sort state
                updateSortHeaders(column, order);
                
                renderBookings(data);
                renderPagination(data.pagination.total, data.pagination.totalPages);
                
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
        console.error("Error fetching partially paid bookings:", error);
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
        refreshBookings(); // Use refreshBookings to reset pagination and sort
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
            
            ${['Paid', 'Partially Paid'].includes(booking.payment_status) ? `
                <div class="booking-detail-section mb-4">
                    <h6 class="border-bottom pb-2"><i class="bi bi-cash-coin me-2"></i>Payment Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Total Cost:</strong> ₱${parseFloat(booking.total_cost).toLocaleString('en-PH')}</p>
                            <p class="mb-2"><strong>Amount Paid:</strong> ₱${parseFloat(booking.amount_paid || 0).toLocaleString('en-PH')}</p>
                            <p class="mb-2"><strong>Balance:</strong> ₱${parseFloat(booking.balance).toLocaleString('en-PH')}</p>
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
            ` : ''}
            
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
                        <button class="btn btn-sm btn-outline-success view-contract" data-booking-id="${booking.booking_id}">
                            <i class="bi bi-file-earmark-text"></i> Contract
                        </button>
                    ` : ''}
                    
                    <button class="btn btn-sm btn-outline-primary view-invoice" data-booking-id="${booking.booking_id}">
                        <i class="bi bi-file-earmark-text"></i> Invoice
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
                    title: 'Apply Discount?',
                    icon: 'question',
                    html: `
                        <p>Would you like to apply a discount to this booking?</p>
                        <div class="form-check mb-3">
                            <input type="radio" class="form-check-input" id="noDiscount" name="discountOption" value="none" checked>
                            <label class="form-check-label" for="noDiscount">No discount</label>
                        </div>
                        <div class="form-check mb-3">
                            <input type="radio" class="form-check-input" id="percentageDiscount" name="discountOption" value="percentage">
                            <label class="form-check-label" for="percentageDiscount">Percentage discount</label>
                        </div>
                        <div class="form-check mb-3">
                            <input type="radio" class="form-check-input" id="flatDiscount" name="discountOption" value="flat">
                            <label class="form-check-label" for="flatDiscount">Flat amount discount</label>
                        </div>
                        <div id="discountInputContainer" style="display: none; margin-top: 15px;">
                            <div id="percentageInput" style="display: none;">
                                <label for="percentageValue">Discount percentage (0-100)</label>
                                <div class="input-group">
                                    <input type="number" id="percentageValue" class="form-control" min="0" max="100" step="0.01" value="0">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                            <div id="flatInput" style="display: none;">
                                <label for="flatValue">Discount amount (PHP)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" id="flatValue" class="form-control" min="0" step="0.01" value="0">
                                </div>
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Confirm Booking',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    didOpen: () => {
                        // Show/hide discount inputs based on selection
                        const discountOptions = document.getElementsByName('discountOption');
                        const discountInputContainer = document.getElementById('discountInputContainer');
                        const percentageInput = document.getElementById('percentageInput');
                        const flatInput = document.getElementById('flatInput');
                        
                        discountOptions.forEach(option => {
                            option.addEventListener('change', function() {
                                if (this.value === 'none') {
                                    discountInputContainer.style.display = 'none';
                                } else {
                                    discountInputContainer.style.display = 'block';
                                    if (this.value === 'percentage') {
                                        percentageInput.style.display = 'block';
                                        flatInput.style.display = 'none';
                                    } else if (this.value === 'flat') {
                                        percentageInput.style.display = 'none';
                                        flatInput.style.display = 'block';
                                    }
                                }
                            });
                        });
                    },
                    preConfirm: () => {
                        const selectedOption = document.querySelector('input[name="discountOption"]:checked').value;
                        
                        if (selectedOption === 'none') {
                            return { discountValue: null, discountType: null };
                        } else if (selectedOption === 'percentage') {
                            const percentageValue = document.getElementById('percentageValue').value;
                            const numValue = parseFloat(percentageValue);
                            
                            if (isNaN(numValue) || numValue < 0 || numValue > 100) {
                                Swal.showValidationMessage('Percentage must be between 0 and 100');
                                return false;
                            }
                            
                            return { discountValue: numValue, discountType: 'percentage' };
                        } else if (selectedOption === 'flat') {
                            const flatValue = document.getElementById('flatValue').value;
                            const numValue = parseFloat(flatValue);
                            
                            if (isNaN(numValue) || numValue < 0) {
                                Swal.showValidationMessage('Flat amount must be greater than or equal to 0');
                                return false;
                            }
                            
                            return { discountValue: numValue, discountType: 'flat' };
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        confirmBooking(bookingId, result.value.discountValue, result.value.discountType);
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
    
    // Hide no results message initially while loading
    document.getElementById("noResultsFound").style.display = "none";
    
    console.log(status);    
    const bookings = await getAllBookings(status, "asc", "client_name", 1, limit);
    renderBookings(bookings);
    renderPagination(bookings.pagination.total, bookings.pagination.totalPages);
    
    // Show/hide no results message
    document.getElementById("noResultsFound").style.display = 
        (!bookings.bookings || bookings.bookings.length === 0) ? "block" : "none";
    
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
    renderPagination(bookings.pagination.total, bookings.pagination.totalPages);
});

// Function to set up sorting
function setupSorting() {
    document.querySelectorAll(".sort").forEach(header => {
        header.addEventListener("click", () => handleSort(header));
    });
}

// Function to handle sort
function handleSort(header) {
    const column = header.getAttribute('data-column');
    let order = header.getAttribute('data-order');
    
    // Toggle sorting direction
    order = order === 'asc' ? 'desc' : 'asc';
    
    // Update current sort
    currentSort = {
        column: column,
        order: order
    };

    // Update all headers
    document.querySelectorAll('.sort').forEach(h => {
        h.setAttribute('data-order', h === header ? order : 'asc');
    });
    
    // Update sort icons
    updateSortIcons();
    
    // Reset to first page when sorting
    currentPage = 1;
    
    // Reload bookings with new sort
    loadBookings();
}

// Function to update sort icons
function updateSortIcons() {
    document.querySelectorAll('.sort').forEach(header => {
        const column = header.getAttribute('data-column');
        const order = header.getAttribute('data-order');
        const sortIconSpan = header.querySelector('.sort-icon');
        
        if (column === currentSort.column) {
            if (sortIconSpan) {
                sortIconSpan.innerHTML = order === 'asc' ? '↑' : '↓';
            }
        } else {
            if (sortIconSpan) {
                sortIconSpan.innerHTML = '↑';
            }
        }
    });
}

// Function to load bookings
async function loadBookings() {
    try {
        const tableBody = document.getElementById("tableBody");
        
        // Don't show loading state when just changing pages or sorting
        // Only show loading state if the table is empty
        if (!bookings.length) {
            tableBody.innerHTML = '<tr><td colspan="10" class="text-center"><div class="spinner-border text-success" role="status"><span class="visually-hidden">Loading...</span></div></td></tr>';
        }

        // Hide no results message initially while loading
        document.getElementById("noResultsFound").style.display = "none";
        
        // Check if we're in a filtered view for payment status
        const activeFilterBtn = document.querySelector(".quick-filter.active");
        let endpoint = "/admin/bookings";
        let requestBody = {
            page: currentPage,
            limit: limit,
            order: currentSort.order,
            column: currentSort.column,
            status: currentFilter
        };
        
        if (searchQuery) {
            requestBody.searchTerm = searchQuery;
            endpoint = "/admin/search-bookings";
        }
        
        if (activeFilterBtn && activeFilterBtn.getAttribute("data-payment")) {
            const paymentFilter = activeFilterBtn.getAttribute("data-payment");
            
            if (paymentFilter === "Unpaid") {
                endpoint = "/admin/unpaid-bookings";
            } else if (paymentFilter === "Partially Paid") {
                endpoint = "/admin/partially-paid-bookings";
            }
        }
        
        const response = await fetch(endpoint, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(requestBody)
        });
        
        if (response.ok) {
            const data = await response.json();
            
            if (data.success) {
                bookings = data.bookings;
                renderBookings();
                renderPagination(data.pagination.total, data.pagination.totalPages);
                
                // Update active view
                if (document.getElementById("cardView").checked) {
                    renderCardView();
                }
            }
        }
    } catch (error) {
        console.error("Error loading bookings:", error);
    }
}

// Function to change page
function changePage(page) {
    currentPage = page;
    loadBookings();
}

// Function to render bookings
function renderBookings() {
    const tableBody = document.getElementById("tableBody");
    
    // Show/hide no results message
    document.getElementById("noResultsFound").style.display = 
        (!bookings || bookings.length === 0) ? "block" : "none";

    if (!bookings || bookings.length === 0) {
        tableBody.innerHTML = "";
        return;
    }

    tableBody.innerHTML = "";

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
        tableBody.appendChild(row);
    });
}

// Function to render pagination
function renderPagination(total, totalPages) {
    const paginationContainer = document.getElementById("paginationContainer");
    
    // Skip rendering if only one page
    if (totalPages <= 1) {
        paginationContainer.innerHTML = '';
        return;
    }
    
    // Build pagination HTML
    let html = '<nav aria-label="Booking navigation"><ul class="pagination justify-content-center">';
    
    // Previous button
    const prevDisabled = currentPage === 1 ? 'disabled' : '';
    html += `
        <li class="page-item ${prevDisabled}">
            <a class="page-link" href="#" aria-label="Previous" ${currentPage === 1 ? '' : `onclick="changePage(${currentPage - 1}); return false;"`}>
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
    `;
    
    // Calculate visible page range
    const maxPagesToShow = 5;
    let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
    let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);
    
    // Adjust if at the end
    if (endPage - startPage + 1 < maxPagesToShow) {
        startPage = Math.max(1, endPage - maxPagesToShow + 1);
    }
    
    // First page and ellipsis
    if (startPage > 1) {
        html += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(1); return false;">1</a></li>`;
        if (startPage > 2) {
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
    }
    
    // Page numbers
    for (let i = startPage; i <= endPage; i++) {
        const active = i === currentPage ? 'active' : '';
        html += `<li class="page-item ${active}"><a class="page-link" href="#" onclick="changePage(${i}); return false;">${i}</a></li>`;
    }
    
    // Last page and ellipsis
    if (endPage < totalPages) {
        if (endPage < totalPages - 1) {
            html += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
        }
        html += `<li class="page-item"><a class="page-link" href="#" onclick="changePage(${totalPages}); return false;">${totalPages}</a></li>`;
    }
    
    // Next button
    const nextDisabled = currentPage === totalPages ? 'disabled' : '';
    html += `
        <li class="page-item ${nextDisabled}">
            <a class="page-link" href="#" aria-label="Next" ${currentPage === totalPages ? '' : `onclick="changePage(${currentPage + 1}); return false;"`}>
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    `;
    
    html += '</ul></nav>';
    
    paginationContainer.innerHTML = html;
}

// Helper function to update sort headers to reflect current sort state
function updateSortHeaders(column, order) {
    // First, remove active class and reset sort icons on all headers
    document.querySelectorAll(".sort").forEach(header => {
        header.classList.remove("active");
        const icon = header.querySelector(".sort-icon");
        if (icon) {
            icon.textContent = "↑";
        }
    });
    
    // Then, set the active class and correct icon on the current sort header
    const activeHeader = document.querySelector(`.sort[data-column="${column}"]`);
    if (activeHeader) {
        activeHeader.classList.add("active");
        activeHeader.setAttribute("data-order", order);
        
        const icon = activeHeader.querySelector(".sort-icon");
        if (icon) {
            icon.textContent = order === "asc" ? "↑" : "↓";
        }
    }
}

// Function to handle confirm booking API call
async function confirmBooking(bookingId, discount = null, discountType = null) {
    try {
        const response = await fetch("/admin/confirm-booking", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ bookingId, discount, discountType })
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
        
        // Refresh bookings
        loadBookings();
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
        
        // Refresh bookings
        loadBookings();
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
        
        // Refresh bookings
        loadBookings();
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

// Function to refresh bookings
async function refreshBookings() {
    // Reset to first page
    currentPage = 1;
    
    // Update stats
    await updateBookingStats();
    
    // Load bookings with current settings
    loadBookings();
}

// Make sure changePage function is in global scope
window.changePage = function(page) {
    currentPage = page;
    loadBookings();
};

// Function to render the card view
function renderCardView() {
    const cardContainer = document.getElementById("cardViewContainer");
    cardContainer.innerHTML = "";
    
    if (!bookings || bookings.length === 0) {
        // Show the centralized no results message instead of duplicating it
        document.getElementById("noResultsFound").style.display = "block";
        return;
    }
    
    // Make sure the no results message is hidden if we have bookings
    document.getElementById("noResultsFound").style.display = "none";
    
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
                    <div class="d-flex gap-2 justify-content-start">
                        <button class="btn btn-sm btn-outline-primary view-booking-btn" data-booking-id="${booking.booking_id}">
                            <i class="bi bi-info-circle"></i> Details
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
}

// Function to attach event listeners to card view buttons
function attachCardEventListeners() {
    document.querySelectorAll(".view-booking-btn").forEach(button => {
        button.addEventListener("click", function() {
            const bookingId = this.getAttribute("data-booking-id");
            showBookingDetails(bookingId);
        });
    });

    document.querySelectorAll(".confirm-btn").forEach(button => {
        button.addEventListener("click", function() {
            const bookingId = this.getAttribute("data-booking-id");
            confirmBooking(bookingId);
        });
    });

    document.querySelectorAll(".reject-btn").forEach(button => {
        button.addEventListener("click", function() {
            const bookingId = this.getAttribute("data-booking-id");
            const userId = this.closest(".card-footer").querySelector(".reject-booking-modal").getAttribute("data-user-id");
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
    });

    document.querySelectorAll(".cancel-btn").forEach(button => {
        button.addEventListener("click", function() {
            const bookingId = this.getAttribute("data-booking-id");
            const userId = this.closest(".card-footer").querySelector(".cancel-booking-modal").getAttribute("data-user-id");
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
    });
}

// Function to create action buttons for a booking
function actionButton(booking) {
    const actionCell = document.createElement("td");
    const buttonSection = document.createElement("div");
    buttonSection.className = 'actions-compact d-flex gap-2 justify-content-start';

    // View Details Button
    const viewDetailsBtn = document.createElement('button');
    viewDetailsBtn.className = 'btn btn-sm btn-outline-primary d-inline-flex align-items-center justify-content-center';
    viewDetailsBtn.title = 'Details';
    
    const viewIcon = document.createElement('i');
    viewIcon.className = 'bi bi-info-circle me-1';
    viewDetailsBtn.appendChild(viewIcon);
    
    const viewText = document.createElement('span');
    viewText.textContent = 'Details';
    viewDetailsBtn.appendChild(viewText);
    
    viewDetailsBtn.addEventListener('click', function() {
        showBookingDetails(booking.booking_id);
    });
    
    buttonSection.appendChild(viewDetailsBtn);

    // Confirm Button - only for Pending bookings
    if (booking.status === "Pending") {
        const confirmBtn = document.createElement('button');
        confirmBtn.className = 'btn btn-sm btn-outline-success d-inline-flex align-items-center justify-content-center';
        confirmBtn.title = 'Confirm';
        
        const confirmIcon = document.createElement('i');
        confirmIcon.className = 'bi bi-check-circle me-1';
        confirmBtn.appendChild(confirmIcon);
        
        const confirmText = document.createElement('span');
        confirmText.textContent = 'Confirm';
        confirmBtn.appendChild(confirmText);
        
        confirmBtn.addEventListener('click', function() {
            const bookingId = booking.booking_id;

             Swal.fire({
                title: 'Apply Discount?',
                icon: 'question',
                html: `
                    <p>Would you like to apply a discount to this booking?</p>
                    <div class="form-check mb-3">
                        <input type="radio" class="form-check-input" id="noDiscount" name="discountOption" value="none" checked>
                        <label class="form-check-label" for="noDiscount">No discount</label>
                    </div>
                    <div class="form-check mb-3">
                        <input type="radio" class="form-check-input" id="percentageDiscount" name="discountOption" value="percentage">
                        <label class="form-check-label" for="percentageDiscount">Percentage discount</label>
                    </div>
                    <div class="form-check mb-3">
                        <input type="radio" class="form-check-input" id="flatDiscount" name="discountOption" value="flat">
                        <label class="form-check-label" for="flatDiscount">Flat amount discount</label>
                    </div>
                    <div id="discountInputContainer" style="display: none; margin-top: 15px;">
                        <div id="percentageInput" style="display: none;">
                            <label for="percentageValue">Discount percentage (0-100)</label>
                            <div class="input-group">
                                <input type="number" id="percentageValue" class="form-control" min="0" max="100" step="0.01" value="0">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <div id="flatInput" style="display: none;">
                            <label for="flatValue">Discount amount (PHP)</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" id="flatValue" class="form-control" min="0" step="0.01" value="0">
                            </div>
                        </div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Confirm Booking',
                cancelButtonText: 'Cancel',
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                didOpen: () => {
                    // Show/hide discount inputs based on selection
                    const discountOptions = document.getElementsByName('discountOption');
                    const discountInputContainer = document.getElementById('discountInputContainer');
                    const percentageInput = document.getElementById('percentageInput');
                    const flatInput = document.getElementById('flatInput');
                    
                    discountOptions.forEach(option => {
                        option.addEventListener('change', function() {
                            if (this.value === 'none') {
                                discountInputContainer.style.display = 'none';
                            } else {
                                discountInputContainer.style.display = 'block';
                                if (this.value === 'percentage') {
                                    percentageInput.style.display = 'block';
                                    flatInput.style.display = 'none';
                                } else if (this.value === 'flat') {
                                    percentageInput.style.display = 'none';
                                    flatInput.style.display = 'block';
                                }
                            }
                        });
                    });
                },
                preConfirm: () => {
                    const selectedOption = document.querySelector('input[name="discountOption"]:checked').value;
                    
                    if (selectedOption === 'none') {
                        return { discountValue: null, discountType: null };
                    } else if (selectedOption === 'percentage') {
                        const percentageValue = document.getElementById('percentageValue').value;
                        const numValue = parseFloat(percentageValue);
                        
                        if (isNaN(numValue) || numValue < 0 || numValue > 100) {
                            Swal.showValidationMessage('Percentage must be between 0 and 100');
                            return false;
                        }
                        
                        return { discountValue: numValue, discountType: 'percentage' };
                    } else if (selectedOption === 'flat') {
                        const flatValue = document.getElementById('flatValue').value;
                        const numValue = parseFloat(flatValue);
                        
                        if (isNaN(numValue) || numValue < 0) {
                            Swal.showValidationMessage('Flat amount must be greater than or equal to 0');
                            return false;
                        }
                        
                        return { discountValue: numValue, discountType: 'flat' };
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    confirmBooking(bookingId, result.value.discountValue, result.value.discountType);
                }
            });
        });
        
        buttonSection.appendChild(confirmBtn);
        
        // Reject Button
        const rejectBtn = document.createElement('button');
        rejectBtn.className = 'btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center';
        rejectBtn.title = 'Reject';
        
        const rejectIcon = document.createElement('i');
        rejectIcon.className = 'bi bi-x-circle me-1';
        rejectBtn.appendChild(rejectIcon);
        
        const rejectText = document.createElement('span');
        rejectText.textContent = 'Reject';
        rejectBtn.appendChild(rejectText);
        
        rejectBtn.addEventListener('click', function() {
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
                    rejectBooking(booking.booking_id, null, reason);
                }
            });
        });
        
        buttonSection.appendChild(rejectBtn);
    }
    
    // Cancel Button - only for Confirmed bookings
    if (booking.status === "Confirmed") {
        const cancelBtn = document.createElement('button');
        cancelBtn.className = 'btn btn-sm btn-outline-danger d-inline-flex align-items-center justify-content-center';
        cancelBtn.title = 'Cancel';
        
        const cancelIcon = document.createElement('i');
        cancelIcon.className = 'bi bi-x-circle me-1';
        cancelBtn.appendChild(cancelIcon);
        
        const cancelText = document.createElement('span');
        cancelText.textContent = 'Cancel';
        cancelBtn.appendChild(cancelText);
        
        cancelBtn.addEventListener('click', function() {
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
                    cancelBooking(booking.booking_id, null, reason);
                }
            });
        });
        
        buttonSection.appendChild(cancelBtn);
    }

    actionCell.appendChild(buttonSection);
    return actionCell;
}
