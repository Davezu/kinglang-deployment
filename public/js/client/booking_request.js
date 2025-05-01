// disable past dates in date of tour input
const today = new Date();
today.setDate(today.getDate() + 3);
const minDate = today.toISOString().split("T")[0];
const dateOfTourInput = document.getElementById("date_of_tour");
if (dateOfTourInput) {
    dateOfTourInput.min = minDate;
}

function formatDate(date) {
    return new Date(date).toLocaleDateString("en-US", {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}

// Add pagination variables
let currentPage = 1;
let limit = 10; // Number of records per page
let searchTerm = "";
let filterStatus = "pending";
let filterDate = null;
let filterBalance = null;
let currentViewMode = "table";
let bookingStatistics = {
    total: 0,
    confirmed: 0,
    pending: 0,
    upcoming: 0
};
let bookingsCache = {}; // Object to store bookings by ID
let displayedBookings = []; // Array to track currently displayed bookings
let calendar = null;

const fullAmount = document.getElementById("fullAmount");
const partialAmount = document.getElementById("partialAmount");

const bookingIDinput = document.getElementById("bookingID");
const userIDinput = document.getElementById("userID");
const amountInput = document.getElementById("amountInput");

// getting the actual value of the selected formatted currency and place it in the hidden input to insert in database
const amountPaymentElements = document.querySelectorAll(".amount-payment");
if (amountPaymentElements.length > 0) {
    amountPaymentElements.forEach(amount => {
        amount.addEventListener("click", (event) => {
            // Remove selected class from all options
            document.querySelectorAll(".amount-payment").forEach(el => {
                el.classList.remove("selected");
            });
            
            // Add selected class to clicked option
            event.currentTarget.classList.add("selected");
            
            const amt = event.currentTarget.querySelector(".amount");
            if (amt) {
                const amountElement = document.getElementById("amount");
                if (amountElement) {
                    amountElement.textContent = amt.textContent;
                }
                if (amountInput) {
                    amountInput.value = parseFloat(amt.textContent.replace(/[^0-9.]/g, ""));
                }
            }
        });
    });
}

// get all of booking records and initialize the page
document.addEventListener("DOMContentLoaded", async function () {
    // Get the initial limit value from the selector
    const limitSelect = document.getElementById("limitSelect");
    const statusSelect = document.getElementById("statusSelect");
    
    if (limitSelect && statusSelect) {
        limit = parseInt(limitSelect.value);
        filterStatus = statusSelect.value;
        
        // Get initial data with pending status
        let result = await getAllBookings(filterStatus, "booking_id", "desc", currentPage, limit, searchTerm);
        
        // If no pending bookings, try processing bookings first
        if (result.bookings.length === 0 && filterStatus === "pending") {
            filterStatus = "processing";
            statusSelect.value = filterStatus;
            result = await getAllBookings(filterStatus, "booking_id", "desc", currentPage, limit, searchTerm);
            
            // If no processing bookings, try confirmed bookings
            if (result.bookings.length === 0) {
                filterStatus = "confirmed";
                statusSelect.value = filterStatus;
                result = await getAllBookings(filterStatus, "booking_id", "desc", currentPage, limit, searchTerm);
                
                // If no confirmed bookings either, use "all"
                if (result.bookings.length === 0) {
                    filterStatus = "all";
                    statusSelect.value = filterStatus;
                    result = await getAllBookings(filterStatus, "booking_id", "desc", currentPage, limit, searchTerm);
                }
            }
        }
        
        // Update views based on the data
        renderBookings(result.bookings);
        renderPagination(result.pagination);
        updateStatistics();
        checkForUpcomingTours(result.bookings);
        
        // Initialize calendar if available
        initializeCalendarView();
    }

    // Payment method change handler
    const paymentMethodSelect = document.getElementById("paymentMethod");
    const accountInfoSection = document.getElementById("accountInfoSection");
    const proofUploadSection = document.getElementById("proofUploadSection");
    const mobilePaymentSection = document.getElementById("mobilePaymentSection");
    
    if (paymentMethodSelect && accountInfoSection && proofUploadSection && mobilePaymentSection) {
        // Show account info section by default when modal opens
        accountInfoSection.style.display = "block";
        proofUploadSection.style.display = "block";
        
        paymentMethodSelect.addEventListener("change", function() {
            const selectedMethod = this.value;
            
            // Reset all sections
            accountInfoSection.style.display = "none";
            proofUploadSection.style.display = "none";
            mobilePaymentSection.style.display = "none";
            
            // Show relevant sections based on payment method
            if (selectedMethod === "Bank Transfer") {
                accountInfoSection.style.display = "block";
                proofUploadSection.style.display = "block";
            } else if (selectedMethod === "Online Payment") {
                accountInfoSection.style.display = "block";
                proofUploadSection.style.display = "block";
            } else if (selectedMethod === "GCash" || selectedMethod === "Maya") {
                mobilePaymentSection.style.display = "block";
                proofUploadSection.style.display = "block";
                
                // Update mobile payment section
                document.getElementById("mobilePaymentTitle").textContent = selectedMethod;
                document.getElementById("qrCodeContainer").innerHTML = `<img src="../../../public/images/payments/${selectedMethod.toLowerCase()}-qr.png" class="img-fluid mt-2" alt="${selectedMethod} QR Code">`;
            }
        });
    }
    
    // Initialize view mode switchers
    initializeViewSwitchers();
    
    // Initialize search functionality
    initializeSearch();
    
    // Initialize quick filters
    initializeQuickFilters();
    
    // Initialize export buttons
    initializeExportButtons();
    
    // Initialize refresh button
    document.getElementById("refreshBookings")?.addEventListener("click", refreshBookings);
    
    // Initialize reset filters button
    document.getElementById("resetFilters")?.addEventListener("click", resetFilters);
    
    // Initialize the payment modal event
    const paymentModal = document.getElementById('paymentModal');
    if (paymentModal) {
        paymentModal.addEventListener('show.bs.modal', function() {
            // Make sure account info section is shown by default
            const accountInfoSection = document.getElementById("accountInfoSection");
            const proofUploadSection = document.getElementById("proofUploadSection");
            if (accountInfoSection && proofUploadSection) {
                accountInfoSection.style.display = "block";
                proofUploadSection.style.display = "block";
            }
        });
    }
});

// Update booking statistics
async function updateStatistics() {
    try {
        const response = await fetch("/home/booking-statistics", {
            method: "POST",
            headers: { "Content-Type": "application/json" }
        });
        
        if (response.ok) {
            const data = await response.json();
            
            if (data.success) {
                bookingStatistics = data.statistics;
                
                // Update the stats dashboard
                document.getElementById("totalBookingsCount").textContent = bookingStatistics.total;
                document.getElementById("confirmedBookingsCount").textContent = bookingStatistics.confirmed;
                document.getElementById("pendingBookingsCount").textContent = bookingStatistics.pending;
                document.getElementById("upcomingToursCount").textContent = bookingStatistics.upcoming;
            }
        }
    } catch (error) {
        console.error("Error fetching booking statistics:", error);
    }
}

// Initialize view mode switchers (Table, Card, Calendar)
function initializeViewSwitchers() {
    // View mode switching
    document.getElementById("tableView")?.addEventListener("change", function() {
        if (this.checked) {
            currentViewMode = "table";
            document.getElementById("tableViewContainer").style.display = "block";
            document.getElementById("cardViewContainer").style.display = "none";
            document.getElementById("calendarViewContainer").style.display = "none";
        }
    });
    
    document.getElementById("cardView")?.addEventListener("change", function() {
        if (this.checked) {
            currentViewMode = "card";
            document.getElementById("tableViewContainer").style.display = "none";
            document.getElementById("cardViewContainer").style.display = "flex";
            document.getElementById("calendarViewContainer").style.display = "none";
            renderCardView();
        }
    });
    
    document.getElementById("calendarView")?.addEventListener("change", function() {
        if (this.checked) {
            currentViewMode = "calendar";
            document.getElementById("tableViewContainer").style.display = "none";
            document.getElementById("cardViewContainer").style.display = "none";
            document.getElementById("calendarViewContainer").style.display = "block";
            
            // Refresh calendar events
            if (calendar) {
                calendar.refetchEvents();
            }
        }
    });
}

// Initialize search functionality
function initializeSearch() {
    const searchInput = document.getElementById("searchBookings");
    const searchBtn = document.getElementById("searchBtn");
    
    if (searchInput && searchBtn) {
        // Search when the search button is clicked
        searchBtn.addEventListener("click", function() {
            searchTerm = searchInput.value.trim();
            currentPage = 1; // Reset to first page for new search
            refreshBookings();
        });
        
        // Search when Enter key is pressed in the search input
        searchInput.addEventListener("keypress", function(e) {
            if (e.key === "Enter") {
                searchTerm = searchInput.value.trim();
                currentPage = 1; // Reset to first page for new search
                refreshBookings();
            }
        });
    }
}

// Initialize quick filters
function initializeQuickFilters() {
    const quickFilterBtns = document.querySelectorAll(".quick-filter");
    
    if (quickFilterBtns.length > 0) {
        quickFilterBtns.forEach(btn => {
            btn.addEventListener("click", function() {
                // Remove active class from all filter buttons
                quickFilterBtns.forEach(b => b.classList.remove("active"));
                
                // Add active class to clicked button
                this.classList.add("active");
                
                // Reset to first page
                currentPage = 1;
                
                // Reset all filters first
                if (this.dataset.status) {
                    // Status filter clicked - clear other filters
                    filterStatus = this.dataset.status;
                    filterDate = null;
                    filterBalance = null;
                    
                    // Update the status select dropdown to match
                    const statusSelect = document.getElementById("statusSelect");
                    if (statusSelect) {
                        statusSelect.value = filterStatus;
                    }
                } else if (this.dataset.date) {
                    // Date filter clicked - we'll use "all" for status but specifically filter confirmed/completed bookings
                    filterDate = this.dataset.date;
                    
                    // For "upcoming" we only want confirmed bookings
                    // For "past" we want all non-canceled/rejected bookings
                    if (filterDate === "upcoming") {
                        filterStatus = "confirmed"; // Only show confirmed bookings for upcoming
                    } else {
                        filterStatus = "all"; // For past, we'll filter at the server side
                    }
                    
                    filterBalance = null;
                    
                    // Update the status select dropdown
                    const statusSelect = document.getElementById("statusSelect");
                    if (statusSelect) {
                        statusSelect.value = filterStatus;
                    }
                } else if (this.dataset.balance) {
                    // Balance filter clicked - set to confirmed or processing status only
                    filterBalance = this.dataset.balance;
                    
                    // For unpaid filter, we only want confirmed or processing bookings
                    // not pending, canceled, or rejected
                    if (filterBalance === "unpaid") {
                        filterStatus = "confirmed"; // Default to confirmed, but we'll include processing in the API call
                    } else {
                        filterStatus = "all";
                    }
                    
                    filterDate = null;
                    
                    // Update the status select dropdown
                    const statusSelect = document.getElementById("statusSelect");
                    if (statusSelect) {
                        statusSelect.value = filterStatus;
                    }
                }
                
                // Update the UI to show the current filter state
                // const filterDescription = document.getElementById("currentFilter");
                // if (filterDescription) {
                //     let filterText = "";
                    
                //     if (this.dataset.status === "canceled") {
                //         filterText = "Showing all canceled bookings";
                //     } else if (this.dataset.date === "past") {
                //         filterText = "Showing completed bookings";
                //     } else if (this.dataset.date === "upcoming") {
                //         filterText = "Showing upcoming bookings";
                //     } else if (this.dataset.balance === "unpaid") {
                //         filterText = "Showing bookings with outstanding balances";
                //     } else if (this.dataset.status) {
                //         filterText = `Showing ${this.dataset.status} bookings`;
                //     }
                    
                //     if (filterText) {
                //         filterDescription.textContent = filterText;
                //         filterDescription.style.display = "block";
                //     } else {
                //         filterDescription.style.display = "none";
                //     }
                // }
                
                refreshBookings();
            });
        });
    }
}

// filter booking record by status
const statusSelectElement = document.getElementById("statusSelect");
if (statusSelectElement) {
    statusSelectElement.addEventListener("change", async function () {
        filterStatus = this.value;
        currentPage = 1; // Reset to first page when filter changes
        
        // Clear other filters when status filter is used
        filterDate = null;
        filterBalance = null;
        
        // Remove active class from all quick filter buttons
        document.querySelectorAll(".quick-filter").forEach(btn => btn.classList.remove("active"));
        
        // Highlight the corresponding quick filter button if exists
        const matchingQuickFilter = document.querySelector(`.quick-filter[data-status="${filterStatus}"]`);
        if (matchingQuickFilter) {
            matchingQuickFilter.classList.add("active");
        }
        
        refreshBookings();
    });
}

// Handle limit selector change
const limitSelectElement = document.getElementById("limitSelect");
if (limitSelectElement) {
    limitSelectElement.addEventListener("change", async function() {
        limit = parseInt(this.value);
        currentPage = 1; // Reset to first page when limit changes
        refreshBookings();
    });
}

// sort booking record by column
const sortButtons = document.querySelectorAll(".sort");
if (sortButtons.length > 0) {
    sortButtons.forEach(button => {
        button.style.cursor = "pointer";
        
        button.addEventListener("click", async function () {
            const column = this.getAttribute("data-column");
            const order = this.getAttribute("data-order");
            currentPage = 1; // Reset to first page when sort changes

            const result = await getAllBookings(filterStatus, column, order, currentPage, limit, searchTerm);
            renderBookings(result.bookings);
            renderPagination(result.pagination);
            
            // Update the sort order
            this.setAttribute("data-order", order === "asc" ? "desc" : "asc");
        });
    });
}

// Initialize export buttons
function initializeExportButtons() {
    // Export to PDF
    document.getElementById("exportPDF")?.addEventListener("click", async function() {
        // Show loading indicator
        Swal.fire({
            title: 'Generating PDF',
            text: 'Please wait...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        try {
            const response = await fetch("/home/export-bookings", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ 
                    format: "pdf", 
                    status: filterStatus, 
                    search: searchTerm,
                    date_filter: filterDate,
                    balance_filter: filterBalance
                })
            });
            
            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = `bookings-${new Date().toISOString().split('T')[0]}.pdf`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'PDF has been generated successfully',
                    timer: 2000,
                    timerProgressBar: true
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to generate PDF',
                    timer: 2000,
                    timerProgressBar: true
                });
            }
        } catch (error) {
            console.error("Error exporting to PDF:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An unexpected error occurred',
                timer: 2000,
                timerProgressBar: true
            });
        }
    });
    
    // Export to CSV
    document.getElementById("exportCSV")?.addEventListener("click", async function() {
        // Show loading indicator
        Swal.fire({
            title: 'Generating CSV',
            text: 'Please wait...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        try {
            const response = await fetch("/home/export-bookings", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ 
                    format: "csv", 
                    status: filterStatus, 
                    search: searchTerm,
                    date_filter: filterDate,
                    balance_filter: filterBalance
                })
            });
            
            if (response.ok) {
                const blob = await response.blob();
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement("a");
                a.href = url;
                a.download = `bookings-${new Date().toISOString().split('T')[0]}.csv`;
                document.body.appendChild(a);
                a.click();
                window.URL.revokeObjectURL(url);
                
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'CSV has been generated successfully',
                    timer: 2000,
                    timerProgressBar: true
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to generate CSV',
                    timer: 2000,
                    timerProgressBar: true
                });
            }
        } catch (error) {
            console.error("Error exporting to CSV:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An unexpected error occurred',
                timer: 2000,
                timerProgressBar: true
            });
        }
    });
}

// Check for upcoming tours to display in the reminder
function checkForUpcomingTours(bookings) {
    if (!bookings || bookings.length === 0) return;

    // Find the nearest upcoming confirmed tour
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    const upcomingTours = bookings.filter(booking => {
        const tourDate = new Date(booking.date_of_tour);
        tourDate.setHours(0, 0, 0, 0);
        return booking.status === "Confirmed" && tourDate > today;
    });
    
    // Sort by nearest date
    upcomingTours.sort((a, b) => new Date(a.date_of_tour) - new Date(b.date_of_tour));
    
    // If there's an upcoming tour, show the reminder
    if (upcomingTours.length > 0) {
        const nextTour = upcomingTours[0];
        const tourDate = new Date(nextTour.date_of_tour);
        const daysUntilTour = Math.ceil((tourDate - today) / (1000 * 60 * 60 * 24));
        
        // Only show reminder if tour is within 14 days
        if (daysUntilTour <= 14) {
            document.getElementById("upcomingDestination").textContent = nextTour.destination;
            document.getElementById("upcomingDate").textContent = formatDate(nextTour.date_of_tour);
            document.getElementById("upcomingReminder").style.display = "flex";
        }
    }
}

// Refresh bookings based on current filters
async function refreshBookings() {
    // Get the current sort column and order
    const activeSort = document.querySelector(".sort[data-order]");
    let column = "booking_id";
    let order = "desc";
    
    if (activeSort) {
        column = activeSort.getAttribute("data-column");
        order = activeSort.getAttribute("data-order");
    }
    
    // Fetch the bookings with the current filters
    const result = await getAllBookings(
        filterStatus, 
        column, 
        order, 
        currentPage, 
        limit, 
        searchTerm, 
        filterDate, 
        filterBalance
    );
    
    // Update the views
    renderBookings(result.bookings);
    renderPagination(result.pagination);
    
    // Update other visual elements based on the view mode
    if (currentViewMode === "card") {
        renderCardView();
    } else if (currentViewMode === "calendar") {
        if (calendar) {
            calendar.refetchEvents();
        }
    }
}

// Reset all filters to default values
function resetFilters() {
    // Reset filter variables
    searchTerm = "";
    filterStatus = "all";
    filterDate = null;
    filterBalance = null;
    currentPage = 1;
    
    // Reset UI elements
    document.getElementById("searchBookings").value = "";
    document.getElementById("statusSelect").value = "all";
    
    // Remove active class from all quick filter buttons
    document.querySelectorAll(".quick-filter").forEach(btn => btn.classList.remove("active"));
    
    // Activate the "All" filter button if it exists
    const allFilterBtn = document.querySelector('.quick-filter[data-status="all"]');
    if (allFilterBtn) {
        allFilterBtn.classList.add("active");
    }
    
    // Reset to table view if not already
    document.getElementById("tableView").checked = true;
    document.getElementById("tableViewContainer").style.display = "block";
    document.getElementById("cardViewContainer").style.display = "none";
    document.getElementById("calendarViewContainer").style.display = "none";
    currentViewMode = "table";
    
    // Refresh bookings with cleared filters
    refreshBookings();
    
    // Show confirmation toast
    Swal.fire({
        icon: 'success',
        title: 'Filters Reset',
        text: 'All filters have been cleared.',
        timer: 1500,
        timerProgressBar: true,
        toast: true,
        position: 'top-end',
        showConfirmButton: false
    });
}

// Initialize calendar view
function initializeCalendarView() {
    const calendarEl = document.getElementById('bookingCalendar');
    if (!calendarEl) return;
    
    // Load FullCalendar library if not already loaded
    if (typeof FullCalendar === 'undefined') {
        console.error('FullCalendar library not loaded');
        return;
    }
    
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listMonth'
        },
        events: fetchCalendarEvents,
        eventClick: function(info) {
            openBookingDetailsModal(info.event.extendedProps.bookingId);
        },
        eventContent: function(arg) {
            return {
                html: `<div class="fc-event-title">
                         <i class="bi ${getStatusIcon(arg.event.extendedProps.status)}"></i> 
                         ${arg.event.title}
                       </div>`
            };
        }
    });
    
    calendar.render();
}

// Fetch events for the calendar view
function fetchCalendarEvents(info, successCallback, failureCallback) {
    // Convert the calendar date range to ISO strings for the API
    const start = info.start.toISOString();
    const end = info.end.toISOString();
    
    // Fetch the events from the server
    fetch("/home/calendar-events", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ start, end })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Map the server data to calendar events
            const events = data.events.map(booking => ({
                id: booking.booking_id,
                title: booking.destination,
                start: booking.date_of_tour,
                end: booking.end_of_tour ? booking.end_of_tour : null,
                className: `fc-event-${booking.status.toLowerCase()}`,
                extendedProps: {
                    bookingId: booking.booking_id,
                    status: booking.status,
                    totalCost: booking.total_cost,
                    balance: booking.balance
                }
            }));
            
            successCallback(events);
        } else {
            failureCallback(new Error(data.message || 'Failed to load events'));
        }
    })
    .catch(error => {
        console.error("Error fetching calendar events:", error);
        failureCallback(error);
    });
}

// Get icon class based on booking status
function getStatusIcon(status) {
    switch (status.toLowerCase()) {
        case 'pending':
            return 'bi-hourglass-split';
        case 'confirmed':
            return 'bi-check-circle';
        case 'processing':
            return 'bi-arrow-repeat';
        case 'canceled':
        case 'rejected':
            return 'bi-x-circle';
        case 'completed':
            return 'bi-trophy';
        default:
            return 'bi-question-circle';
    }
}

function formatNumber(number) {
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP"
    }).format(number);
};

// New function to handle booking cancellation API call
async function cancelBooking(bookingId, reason) {
    try {
        const response = await fetch("/cancel-booking", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ bookingId, reason })
        });
    
        const data = await response.json();
        
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message,
                timer: 2000,
                timerProgressBar: true
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                timer: 2000,
                timerProgressBar: true
            });
        }
        
        // Refresh the bookings table
        refreshBookings();
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

// Payment form submission
const paymentForm = document.getElementById("paymentForm");
if (paymentForm) {
    paymentForm.addEventListener("submit", async function (event) {
        event.preventDefault();

        // Validate that an amount has been selected
        if (!document.getElementById("amount").textContent) {
            Swal.fire({
                icon: 'warning',
                title: 'Please select an amount',
                text: 'You must select either full payment or down payment to proceed.',
                timer: 3000,
                timerProgressBar: true
            });
            return;
        }
        
        // Validate proof of payment for bank transfer and online payment
        const paymentMethod = document.getElementById("paymentMethod").value;
        const proofFile = document.getElementById("proofOfPayment").files[0];
        
        if ((paymentMethod === "Bank Transfer" || paymentMethod === "Online Payment" || 
             paymentMethod === "GCash" || paymentMethod === "Maya") && !proofFile) {
            Swal.fire({
                icon: 'warning',
                title: 'Proof of payment required',
                text: 'Please upload your proof of payment to proceed.',
                timer: 3000,
                timerProgressBar: true
            });
            return;
        }

        const formData = new FormData(this);
        
        // Show loading state
        Swal.fire({
            title: 'Processing Payment',
            text: 'Please wait while we process your payment...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });
        
        try {
            const response = await fetch("/payment/process", {
                method: "POST",
                body: formData
            });
            
            // Hide the payment modal
            const paymentModal = bootstrap.Modal.getInstance(document.getElementById("paymentModal"));
            paymentModal.hide();
            document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
            document.body.classList.remove('modal-open');
            
            if (response.ok) {
                let data;
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.includes("application/json")) {
                    data = await response.json();
                } else {
                    data = { success: response.ok, message: "Payment submitted successfully!" };
                }
                
                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Payment submitted successfully!',
                    timer: 2000,
                    timerProgressBar: true
                });
                
                // Set the status filter to "processing" to show the user their processing booking
                filterStatus = "processing";
                const statusSelect = document.getElementById("statusSelect");
                if (statusSelect) {
                    statusSelect.value = "processing";
                }
                
                // Remove active class from all quick filter buttons
                document.querySelectorAll(".quick-filter").forEach(btn => btn.classList.remove("active"));
                
                // Highlight the processing quick filter if it exists
                const processingQuickFilter = document.querySelector('.quick-filter[data-status="processing"]');
                if (processingQuickFilter) {
                    processingQuickFilter.classList.add("active");
                }
                
                // Refresh the bookings
                refreshBookings();
            } else {
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'There was an error submitting your payment.',
                    timer: 2000,
                    timerProgressBar: true
                });
            }
        } catch (error) {
            console.error("Error submitting payment:", error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'There was an error processing your payment. Please try again.',
                timer: 2000,
                timerProgressBar: true
            });
        }
        
        // Reset the form
        this.reset();
    });
}

// Add pagination rendering function
function renderPagination(pagination) {
    const paginationContainer = document.getElementById("paginationContainer");
    if (!paginationContainer) {
        // If there's no pagination container in the page, return
        const tableResponsive = document.querySelector(".table-responsive-xl");
        if (!tableResponsive) {
            return;
        }
        
        // Create pagination container if it doesn't exist
        const container = document.createElement("div");
        container.id = "paginationContainer";
        container.className = "d-flex justify-content-center mt-4";
        tableResponsive.after(container);
    }
    
    // If no pagination data, return
    if (!pagination) return;
    
    const { total_pages, current_page, total_records } = pagination;
    
    // If no pagination needed, clear container and return
    if (!total_records || total_records < 10 || total_pages <= 1) {
        if (paginationContainer) {
            paginationContainer.innerHTML = "";
        }
        return;
    }
    
    // Use the centralized pagination utility if available
    if (typeof createPagination === 'function') {
        createPagination({
            containerId: "paginationContainer",
            totalPages: total_pages,
            currentPage: current_page,
            paginationType: 'advanced',
            onPageChange: async (page) => {
                currentPage = page;
                refreshBookings();
            }
        });
    }
}

// Enhanced getAllBookings to support all filtering options
async function getAllBookings(status, column, order, page = 1, limit = 10, search = "", dateFilter = null, balanceFilter = null) {
    try {
        // Log the filter parameters for debugging
        console.log("Filters:", { 
            status, 
            dateFilter, 
            balanceFilter,
            search,
            page,
            limit
        });
        
        const response = await fetch("/home/get-booking-requests", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ 
                status, 
                column, 
                order, 
                page, 
                limit,
                search,
                date_filter: dateFilter,
                balance_filter: balanceFilter
            })
        });

        const data = await response.json();
        
        // Show/hide no results message
        const noResultsElement = document.getElementById("noResultsFound");
        if (noResultsElement) {
            if (data.bookings && data.bookings.length === 0) {
                noResultsElement.style.display = "block";
            } else {
                noResultsElement.style.display = "none";
            }
        }

        if (data.success) {
            // Reset the bookings cache on each request to prevent duplicates
            bookingsCache = {};
            displayedBookings = [];
            
            // Store each booking in the cache with booking_id as key
            if (data.bookings && Array.isArray(data.bookings)) {
                data.bookings.forEach(booking => {
                    if (booking.booking_id) {
                        bookingsCache[booking.booking_id] = booking;
                    }
                });
            }
            
            return data;
        } else {
            console.log(data.message);
            return { bookings: [], pagination: { total_records: 0, total_pages: 0, current_page: 1 } };
        }
    } catch (error) {
        console.error("Error fetching data: ", error.message);
        return { bookings: [], pagination: { total_records: 0, total_pages: 0, current_page: 1 } };
    }
}

// Render bookings in the table view
function renderBookings(bookings) {
    const tableBody = document.getElementById("tableBody");
    const cardContainer = document.getElementById("cardViewContainer");
    tableBody.innerHTML = "";
    
    // Show/hide no results message
    const noResultsElement = document.getElementById("noResultsFound");
    if (bookings.length === 0) {
        noResultsElement.style.display = "block";
        if (currentViewMode === "table") {
            document.getElementById("tableViewContainer").style.display = "none";
        } else if (currentViewMode === "card") {
            cardContainer.style.display = "none";
        }
        document.getElementById("paginationContainer").style.display = "none";
        return;
    } else {
        noResultsElement.style.display = "none";
        if (currentViewMode === "table") {
            document.getElementById("tableViewContainer").style.display = "block";
        } else if (currentViewMode === "card") {
            cardContainer.style.display = "flex";
        }
        document.getElementById("paginationContainer").style.display = "flex";
    }
    
    // Reset displayed bookings array
    displayedBookings = [];
    
    // Create a Set to track processed booking IDs and prevent duplication
    const processedBookingIds = new Set();
    
    // Render appropriate view
    if (currentViewMode === "table") {
        // Table View - Use a filtered array to prevent duplicates
        bookings.forEach(booking => {
            // Skip if this booking ID has already been processed
            if (processedBookingIds.has(booking.booking_id)) {
                return;
            }
            
            // Mark this booking ID as processed
            processedBookingIds.add(booking.booking_id);
            
            // Add to displayed bookings array for tracking
            displayedBookings.push(booking);
            
            const row = document.createElement("tr");
            row.className = "align-middle";
            row.dataset.bookingId = booking.booking_id;
            
            // Destination
            const destinationCell = document.createElement("td");
            destinationCell.className = "destination-cell";
            destinationCell.innerHTML = `<strong>${booking.destination}</strong>`;
            destinationCell.title = booking.destination; // Add tooltip for full text on hover
            row.appendChild(destinationCell);
            
            // Date of Tour
            const dateCell = document.createElement("td");
            dateCell.textContent = formatDate(booking.date_of_tour);
            row.appendChild(dateCell);
            
            // End of Tour
            const endDateCell = document.createElement("td");
            endDateCell.textContent = formatDate(booking.end_of_tour);
            row.appendChild(endDateCell);
            
            // Number of Days
            const daysCell = document.createElement("td");
            daysCell.textContent = booking.number_of_days;
            daysCell.classList.add("text-center");
            row.appendChild(daysCell);
            
            // Number of Buses
            const busesCell = document.createElement("td");
            busesCell.textContent = booking.number_of_buses;
            busesCell.classList.add("text-center");
            row.appendChild(busesCell);
            
            // Total Cost
            const costCell = document.createElement("td");
            costCell.textContent = formatNumber(booking.total_cost);
            // costCell.classList.add("text-end");
            row.appendChild(costCell);
            
            // Balance
            const balanceCell = document.createElement("td");
            balanceCell.textContent = formatNumber(booking.balance);
            // balanceCell.classList.add("text-end");
            if (parseFloat(booking.balance) <= 0) {
                balanceCell.classList.add("text-success");
                balanceCell.innerHTML = `<span class="badge bg-success-subtle px-3 py-2 rounded-pill text-success-emphasis fw-semibold">Paid</span>`;
            } else if (parseFloat(booking.balance) < parseFloat(booking.total_cost)) {
                balanceCell.classList.add("text-warning");
            } else {
                balanceCell.classList.add("text-danger");
            }
            row.appendChild(balanceCell);
            
            // Status/Remarks
            const statusCell = document.createElement("td");
            const statusBadge = document.createElement("span");
            statusBadge.className = `status-badge status-${booking.status.toLowerCase()}`;
            statusBadge.textContent = booking.status.charAt(0).toUpperCase() + booking.status.slice(1);
            statusCell.appendChild(statusBadge);
            row.appendChild(statusCell);
            
            // Actions
            const actionTd = document.createElement("td");
            actionTd.appendChild(actionCell(booking))
            row.appendChild(actionTd);
            
            tableBody.appendChild(row);
        });
    } else if (currentViewMode === "card") {
        // Card View
        renderCardView();
    }
}

// Render bookings in card view
function renderCardView() {
    const cardContainer = document.getElementById("cardViewContainer");
    if (!cardContainer) return;
    
    cardContainer.innerHTML = "";
    
    if (Object.keys(bookingsCache).length === 0) {
        cardContainer.innerHTML = `
            <div class="col-12 text-center py-5">
                <div class="text-muted">No bookings found matching your criteria</div>
            </div>`;
        return;
    }
    
    // Create a Set to track processed booking IDs and prevent duplication
    const processedBookingIds = new Set();
    
    // Use the values from bookingsCache to render cards
    Object.values(bookingsCache).forEach(booking => {
        // Skip if this booking ID has already been processed
        if (processedBookingIds.has(booking.booking_id)) {
            return;
        }
        
        // Mark this booking ID as processed
        processedBookingIds.add(booking.booking_id);
        
        // Create card column - updated classes for better spacing
        const col = document.createElement("div");
        col.className = "col-lg-4 col-md-6 col-sm-12 mb-4";
        col.dataset.bookingId = booking.booking_id;
        
        // Create card with improved styling
        const card = document.createElement("div");
        card.className = "card booking-card h-100 border-0 shadow-sm";
        
        // Set card header background color based on status
        let headerClass = "bg-secondary";
        switch (booking.status.toLowerCase()) {
            case 'pending':
                headerClass = "bg-warning-subtle text-warning";
                break;
            case 'confirmed':
                headerClass = "bg-success-subtle text-success";
                break;
            case 'processing':
                headerClass = "bg-info-subtle text-info";
                break;
            case 'canceled':
            case 'rejected':
                headerClass = "bg-danger-subtle text-danger";
                break;
            case 'completed':
                headerClass = "bg-primary-subtle text-primary";
                break;
        }
        
        // Create card content with improved spacing
        card.innerHTML = `
            <div class="card-header ${headerClass}">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-truncate" title="${booking.destination}" style="max-width: 70%;">${booking.destination}</h5>
                    <span class="status-badge status-${booking.status.toLowerCase()}">${booking.status}</span>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="booking-info-item mb-2">
                    <i class="bi bi-calendar-event me-2"></i>
                    <div>
                        <span class="label">Tour Date:</span>
                        ${formatDate(booking.date_of_tour)} to ${formatDate(booking.end_of_tour)}
                    </div>
                </div>
                <div class="booking-info-item mb-2">
                    <i class="bi bi-calendar-week me-2"></i>
                    <div>
                        <span class="label">Duration:</span>
                        ${booking.number_of_days} day${booking.number_of_days > 1 ? 's' : ''}
                    </div>
                </div>
                <div class="booking-info-item mb-2">
                    <i class="bi bi-truck me-2"></i>
                    <div>
                        <span class="label">Buses:</span>
                        ${booking.number_of_buses}
                    </div>
                </div>
                <div class="booking-info-item mb-2">
                    <i class="bi bi-cash-coin me-2"></i>
                    <div>
                        <span class="label">Total Cost:</span>
                        ${formatNumber(booking.total_cost)}
                    </div>
                </div>
                <div class="booking-info-item mb-2">
                    <i class="bi bi-wallet2 me-2"></i>
                    <div>
                        <span class="label">Balance:</span>
                        ${parseFloat(booking.balance) <= 0 
                          ? '<span class="badge bg-success">Paid</span>' 
                          : formatNumber(booking.balance)}
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex flex-wrap gap-2 justify-content-center bg-light" id="card-actions-${booking.booking_id}">
                <!-- Action buttons will be added here -->
            </div>
        `;
        
        col.appendChild(card);
        cardContainer.appendChild(col);
        
        // Add action buttons to card footer
        const actionsContainer = card.querySelector(`#card-actions-${booking.booking_id}`);
        addCardActions(actionsContainer, booking);
    });
}

// Add action buttons to card view
function addCardActions(container, booking) {
    // View button (always present)
    const viewBtn = document.createElement("button");
    viewBtn.className = "btn btn-sm btn-outline-success";
    viewBtn.innerHTML = '<i class="bi bi-eye"></i> View';
    viewBtn.addEventListener("click", function() {
        openBookingDetailsModal(booking.booking_id);
    });
    container.appendChild(viewBtn);
    
    // Pay button (only for confirmed bookings with balance)
    if (booking.status === "Confirmed" && parseFloat(booking.balance) > 0.0) {
        const payBtn = document.createElement("button");
        payBtn.className = "btn btn-sm btn-outline-primary";
        payBtn.innerHTML = '<i class="bi bi-credit-card"></i> Pay';
        payBtn.setAttribute("data-bs-toggle", "modal");
        payBtn.setAttribute("data-bs-target", "#paymentModal");
        payBtn.setAttribute("data-booking-id", booking.booking_id);
        payBtn.setAttribute("data-total-cost", booking.total_cost);
        payBtn.setAttribute("data-balance", booking.balance);
        payBtn.setAttribute("data-client-id", booking.client_id);
        
        payBtn.addEventListener("click", function() {
            // Reset form state
            document.getElementById("amount").textContent = "";
            document.querySelectorAll(".amount-payment").forEach(el => {
                el.classList.remove("selected");
            });
            
            // Reset payment method
            const paymentMethodSelect = document.getElementById("paymentMethod");
            if (paymentMethodSelect) {
                paymentMethodSelect.selectedIndex = 0;
                
                // Show account info section by default
                const accountInfoSection = document.getElementById("accountInfoSection");
                const proofUploadSection = document.getElementById("proofUploadSection");
                if (accountInfoSection && proofUploadSection) {
                    accountInfoSection.style.display = "block";
                    proofUploadSection.style.display = "block";
                }
            }
            
            const totalCost = this.getAttribute("data-total-cost");
            const balance = this.getAttribute("data-balance");
            const bookingID = this.getAttribute("data-booking-id");

            document.getElementById("fullAmnt").style.display = "block";  
            document.getElementById("downPayment").textContent = "Down Payment";
            
            if (parseFloat(balance) < parseFloat(totalCost)) {
                document.getElementById("fullAmnt").style.display = "none";   
                document.getElementById("downPayment").textContent = "Final Payment";
                // Use the balance amount as the final payment amount
                partialAmount.textContent = formatNumber(balance);
                // Auto-select the down payment option when full payment is not available
                setTimeout(() => {
                    document.querySelectorAll(".amount-payment")[1].click();
                }, 300);
            } else {
                fullAmount.textContent = formatNumber(totalCost);
                partialAmount.textContent = formatNumber(totalCost / 2);
            }
            
            bookingIDinput.value = bookingID;
            userIDinput.value = booking.user_id;
        });
        
        container.appendChild(payBtn);
    }
    
    // Edit button (for pending, confirmed, processing bookings)
    if (["Pending", "Confirmed", "Processing"].includes(booking.status)) {
        const editBtn = document.createElement("button");
        editBtn.className = "btn btn-sm btn-outline-secondary";
        editBtn.innerHTML = '<i class="bi bi-pencil"></i> Edit';
        editBtn.addEventListener("click", function() {
            sessionStorage.setItem("bookingId", booking.booking_id);
            window.location.href = "/home/book";
        });
        container.appendChild(editBtn);
    }
    
    // Cancel button (for pending, confirmed, processing bookings)
    if (["Pending", "Confirmed", "Processing"].includes(booking.status)) {
        const cancelBtn = document.createElement("button");
        cancelBtn.className = "btn btn-sm btn-outline-danger";
        cancelBtn.innerHTML = '<i class="bi bi-x-circle"></i> Cancel';
        cancelBtn.setAttribute("data-booking-id", booking.booking_id);
        
        cancelBtn.addEventListener("click", function() {
            const bookingId = this.getAttribute("data-booking-id");
            
            Swal.fire({
                title: 'Cancel Booking?',
                html: '<p>Are you sure you want to cancel your booking?</p>',
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
                customClass: {
                    container: 'swal2-container',
                    popup: 'swal2-popup',
                    header: 'swal2-header',
                    title: 'swal2-title',
                    input: 'form-control'
                },
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
                    cancelBooking(bookingId, reason);
                }
            });
        });
        // Print Invoice button (for all bookings)
        const invoiceBtn = document.createElement("button");
        invoiceBtn.className = "btn btn-sm btn-outline-info";
        invoiceBtn.innerHTML = '<i class="bi bi-printer"></i> Invoice';
        invoiceBtn.addEventListener("click", function() {
            printInvoice(booking.booking_id);
        });
        container.appendChild(invoiceBtn);
        container.appendChild(cancelBtn);
    }
    
    
}

// Open booking details modal
function openBookingDetailsModal(bookingId) {
    // Get the modal
    const modal = document.getElementById("bookingDetailsModal");
    const modalContent = document.getElementById("bookingDetailsContent");
    
    // Show loading
    modalContent.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <div class="mt-3">Loading booking details...</div>
        </div>
    `;
    
    // Open the modal
    const modalInstance = new bootstrap.Modal(modal);
    modalInstance.show();
    
    // Fetch booking details
    fetch("/home/get-booking-details", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ bookingId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const booking = data.booking;
            const payment = data.payments;
            
            // Format the booking details as HTML with comprehensive information
            modalContent.innerHTML = `
                
                <div class="booking-detail-section mb-4">
                    <h6 class="border-bottom pb-2"><i class="bi bi-geo-alt me-2"></i>Trip Details</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="bm-2"><strong>Pickup Point:</strong> ${booking.pickup_point}</p>
                            <p class="mb-2"><strong>Destination:</strong> 
                                 
                                ${booking.stops && booking.stops.length > 0 ? 
                                    `${booking.stops.map(stop => 
                                        `<span>${stop.location}</span>`
                                    ).join('<i class="bi bi-arrow-right mx-1 text-danger"></i>')} 
                                    <i class="bi bi-arrow-right mx-1 text-danger"></i>` 
                                : ''}
                                <span">${booking.destination}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                        <p class="mb-2"><strong>Tour Date:</strong> ${formatDate(booking.date_of_tour)}${booking.end_of_tour ? ` to ${formatDate(booking.end_of_tour)}` : ''}</p>
                            <p class="mb-2"><strong>Duration:</strong> ${booking.number_of_days} day${booking.number_of_days > 1 ? 's' : ''}</p>
                            <p class="mb-2"><strong>Number of Buses:</strong> ${booking.number_of_buses}</p>
                            <p class="mb-2"><strong>Status:</strong> <span  class="status-badge status-${booking.status.toLowerCase()}" >${booking.status}</span></p>
                        </div>
                    </div>
                </div>
                
                <div class="booking-detail-section mb-3">
                    <h6 class="border-bottom pb-2"><i class="bi bi-cash-coin me-2"></i>Payment Information</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Total Cost:</strong> ${formatNumber(booking.total_cost)}</p>
                            <p><strong>Amount Paid:</strong> ${formatNumber(booking.total_cost - booking.balance)}</p>
                            <p><strong>Balance:</strong> ${formatNumber(booking.balance)}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Payment Status:</strong> 
                                <span class="badge ${parseFloat(booking.balance) == 0.0 ? 'bg-success' : parseFloat(booking.balance) >= parseFloat(booking.total_cost) ? 'bg-danger' : 'bg-warning'} p-2">
                                    ${booking.payment_status}
                                </span>
                            </p>
                            <p><strong>Last Payment Date:</strong> ${payment[0]?.payment_date ? formatDate(payment[0]?.payment_date) : 'No payments yet'}</p>
                            <p><strong>Payment Method:</strong> ${payment[0]?.payment_method || 'N/A'}</p>
                        </div>
                    </div>
                </div>
                
                <div class="booking-detail-section mb-2">
                    <h6 class="border-bottom pb-2"><i class="bi bi-list-check me-2"></i>Actions</h6>
                    <div class="d-flex flex-wrap gap-2">
                        ${booking.status === "Confirmed" && parseFloat(booking.balance) > 0 ? `
                            <button class="btn btn-sm btn-outline-primary pay-booking" 
                                data-bs-toggle="modal" 
                                data-bs-target="#paymentModal"
                                data-booking-id="${booking.booking_id}"
                                data-total-cost="${booking.total_cost}"
                                data-balance="${booking.balance}"
                                data-client-id="${booking.client_id}">
                                <i class="bi bi-credit-card"></i> Make Payment
                            </button>
                        ` : ''}

                        ${["Pending", "Confirmed", "Processing"].includes(booking.status) ? `
                            <button class="btn btn-sm btn-outline-secondary edit-booking" data-booking-id="${booking.booking_id}">
                                <i class="bi bi-pencil"></i> Edit Booking
                            </button>
                        ` : ''}
                        
                        ${["Pending", "Confirmed", "Processing"].includes(booking.status) ? `
                            <button class="btn btn-sm btn-outline-danger cancel-booking" data-booking-id="${booking.booking_id}">
                                <i class="bi bi-x-circle"></i> Cancel Booking
                            </button>
                        ` : ''}

                        ${["Confirmed", "Processing", "Completed", "Canceled"].includes(booking.status) ? `
                            <button class="btn btn-sm btn-outline-success print-invoice" data-booking-id="${booking.booking_id}">
                                <i class="bi bi-printer"></i> Print Invoice
                            </button>
                            <button class="btn btn-sm btn-outline-primary print-contract" data-booking-id="${booking.booking_id}">
                                <i class="bi bi-printer"></i> Print Contract
                            </button>
                        ` : ''}
                    </div>
                </div>
            `;
            
            // Add event listeners to the buttons
            modalContent.querySelector(".edit-booking")?.addEventListener("click", function() {
                sessionStorage.setItem("bookingId", this.dataset.bookingId);
                window.location.href = "/home/book";
            });
            
            modalContent.querySelector(".pay-booking")?.addEventListener("click", function() {
                // Close the details modal
                modalInstance.hide();
                
                // Reset form state
                document.getElementById("amount").textContent = "";
                document.querySelectorAll(".amount-payment").forEach(el => {
                    el.classList.remove("selected");
                });
                
                // Get payment details
                const totalCost = this.getAttribute("data-total-cost");
                const balance = this.getAttribute("data-balance");
                const bookingID = this.getAttribute("data-booking-id");
                
                // Show account info section by default
                const accountInfoSection = document.getElementById("accountInfoSection");
                const proofUploadSection = document.getElementById("proofUploadSection");
                if (accountInfoSection && proofUploadSection) {
                    accountInfoSection.style.display = "block";
                    proofUploadSection.style.display = "block";
                }
                
                document.getElementById("fullAmnt").style.display = "block";  
                document.getElementById("downPayment").textContent = "Down Payment";
                
                if (parseFloat(balance) < parseFloat(totalCost)) {
                    document.getElementById("fullAmnt").style.display = "none";   
                    document.getElementById("downPayment").textContent = "Final Payment";
                    partialAmount.textContent = formatNumber(balance);
                    
                    // Auto-select the down payment option  
                    setTimeout(() => {
                        document.querySelectorAll(".amount-payment")[1].click();
                    }, 300);
                } else {
                    fullAmount.textContent = formatNumber(totalCost);
                    partialAmount.textContent = formatNumber(totalCost / 2);
                }
                
                bookingIDinput.value = bookingID;
                userIDinput.value = booking.user_id;
            });
            
            modalContent.querySelector(".cancel-booking")?.addEventListener("click", function() {
                const bookingId = this.dataset.bookingId;
                
                // Close the details modal
                modalInstance.hide();
                
                Swal.fire({
                    title: 'Cancel Booking?',
                    html: '<p>Are you sure you want to cancel your booking?</p>',
                    input: 'textarea',
                    inputPlaceholder: 'Kindly provide the reason here.',
                    inputAttributes: {
                        'aria-label': 'Cancellation reason'
                    },
                    footer: '<p class="text-secondary mb-0">Note: This action cannot be undone.</p>',
                    showCancelButton: true,
                }).then(result => {
                    if (result.isConfirmed) {
                        const reason = result.value;
                        cancelBooking(bookingId, reason);
                    }
                });
            });
            
            // Add event listeners for print buttons safely
            const printInvoiceBtn = modalContent.querySelector(".print-invoice");
            if (printInvoiceBtn) {
                printInvoiceBtn.addEventListener("click", function() {
                    printInvoice(this.dataset.bookingId);
                });
            }
            
            // Add event listener for the print contract button
            const printContractBtn = modalContent.querySelector(".print-contract");
            if (printContractBtn) {
                printContractBtn.addEventListener("click", function() {
                    printContract(this.dataset.bookingId);
                });
            }
            
        } else {
            modalContent.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Failed to load booking details. Please try again.
                </div>
            `;
        }
    })
    .catch(error => {
        console.error("Error fetching booking details:", error);
        modalContent.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                An error occurred while loading booking details. Please try again.
            </div>
        `;
    });
}

function actionCell(booking) {
    const isFullyPaid = parseFloat(booking.balance) <= 0;
    const isPending = booking.status === 'Pending';
    const isProcessing = booking.status === 'Processing';
    const isConfirmed = booking.status === 'Confirmed';
    const isCanceled = booking.status === 'Canceled';
    const isRejected = booking.status === 'Rejected';
    const isCompleted = booking.status === 'Completed';
    const canBeCanceled = isPending || isProcessing;
    const canBePaid = !isRejected && !isCanceled && !isPending && !isProcessing  && !isFullyPaid && !isCompleted && parseFloat(booking.balance) > 0;
    const hasCompletedPayment = parseFloat(booking.balance) <= 0 && (isConfirmed || isProcessing);

    const buttonSection = document.createElement('div');
    buttonSection.className = 'actions-compact d-flex gap-2 justify-content-start';

    // View Details Button
    buttonSection.appendChild(
        createActionButton(
            'btn btn-sm btn-outline-primary',
            'bi bi-info-circle',
            'Details',
            () => openBookingDetailsModal(booking.booking_id)
        )
    );

    // Pay Button - only show if balance exists and booking is not canceled/rejected
    if (canBePaid) {
        buttonSection.appendChild(
            createActionButton(
                'btn btn-sm btn-outline-success',
                'bi bi-credit-card',
                'Pay',
                () => {
                    // Reset form state
                    document.getElementById("amount").textContent = "";
                    document.querySelectorAll(".amount-payment").forEach(el => {
                        el.classList.remove("selected");
                    });
                    
                    // Show payment modal
                    $('#paymentModal').modal('show');
                    
                    // Show account info section by default
                    const accountInfoSection = document.getElementById("accountInfoSection");
                    const proofUploadSection = document.getElementById("proofUploadSection");
                    if (accountInfoSection && proofUploadSection) {
                        accountInfoSection.style.display = "block";
                        proofUploadSection.style.display = "block";
                    }
                    
                    // Set booking details in the payment form
                    let bookingDetails = {
                        id: booking.booking_id,
                        user_id: booking.user_id,
                        balance: parseFloat(booking.balance),
                        total_cost: parseFloat(booking.total_cost)
                    };

                    bookingIDinput.value = bookingDetails.id;
                    userIDinput.value = bookingDetails.user_id;

                    // Check if partial payment already made
                    if (bookingDetails.balance < bookingDetails.total_cost) {
                        // Only show final payment option
                        document.getElementById("fullAmnt").style.display = "none";
                        document.getElementById("downPayment").textContent = "Final Payment";
                        partialAmount.innerHTML = formatNumber(bookingDetails.balance);
                        
                        // Auto-select the final payment option
                        setTimeout(() => {
                            document.querySelectorAll(".amount-payment")[1].click();
                        }, 300);
                    } else {
                        // Show both payment options
                        document.getElementById("fullAmnt").style.display = "block";
                        document.getElementById("downPayment").textContent = "Down Payment";
                        fullAmount.innerHTML = formatNumber(bookingDetails.total_cost);
                        partialAmount.innerHTML = formatNumber(bookingDetails.total_cost * 0.5);
                        
                        // Simulate a click on the full payment option
                        setTimeout(() => {
                            document.getElementById('fullAmnt').click();
                        }, 300);
                    }
                }
            )
        );
    }

    // Print Receipt Button - only show if payment is completed
    if (hasCompletedPayment) {
        buttonSection.appendChild(
            createActionButton(
                'btn btn-sm btn-outline-info',
                'bi bi-printer',
                'Receipt',
                () => printInvoice(booking.booking_id)
            )
        );
    }

    // Cancel Button - only show if booking can be canceled
    if (canBeCanceled) {
        buttonSection.appendChild(
            createActionButton(
                'btn btn-sm btn-outline-danger',
                'bi bi-x-circle',
                'Cancel',
                () => {
                    Swal.fire({
                        title: 'Cancel Booking?',
                        text: 'Please provide a reason for cancellation:',
                        icon: 'warning',
                        input: 'textarea',
                        inputPlaceholder: 'Enter your reason here...',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, cancel it!',
                        confirmButtonColor: '#dc3545',
                        cancelButtonText: 'No, keep it',
                        cancelButtonColor: '#6c757d',
                        inputValidator: (value) => {
                            if (!value) {
                                return 'You need to provide a reason!';
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            cancelBooking(booking.booking_id, result.value);
                        }
                    });
                }
            )
        );
    }

    return buttonSection;
}

function createActionButton(className, iconClass, text, clickHandler) {
    const button = document.createElement('button');
    button.className = className;
    button.title = text;
    
    // Create icon element
    const icon = document.createElement('i');
    icon.className = iconClass + ' me-1';
    button.appendChild(icon);
    
    // Add text as span to allow for responsive hiding
    const textSpan = document.createElement('span');
    textSpan.className = 'button-text';
    textSpan.textContent = text;
    button.appendChild(textSpan);
    
    button.addEventListener('click', clickHandler);
    return button;
}

// Format number as currency
function formatNumber(number) {
    return new Intl.NumberFormat("en-PH", {
        style: "currency",
        currency: "PHP"
    }).format(number);
}

// Print invoice for a booking
function printInvoice(bookingId) {
    window.open(`/home/print-invoice/${bookingId}`, '_blank');
}

// Function to print contract
function printContract(bookingId) {
    window.open(`/home/print-contract/${bookingId}`, '_blank');
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
