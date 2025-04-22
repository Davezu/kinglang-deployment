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


// get all of booking record
document.addEventListener("DOMContentLoaded", async function () {
    // Get the initial limit value from the selector
    const limitSelect = document.getElementById("limitSelect");
    const statusSelect = document.getElementById("statusSelect");
    
    if (limitSelect && statusSelect) {
        limit = parseInt(limitSelect.value);
        let status = statusSelect.value;
        
        // Get initial data with pending status
        let result = await getAllBookings(status, "booking_id", "desc", currentPage, limit);
        
        // If no pending bookings, try processing bookings first
        if (result.bookings.length === 0 && status === "pending") {
            status = "processing";
            statusSelect.value = status;
            result = await getAllBookings(status, "booking_id", "desc", currentPage, limit);
            
            // If no processing bookings, try confirmed bookings
            if (result.bookings.length === 0) {
                status = "confirmed";
                statusSelect.value = status;
                result = await getAllBookings(status, "booking_id", "desc", currentPage, limit);
                
                // If no confirmed bookings either, use "all"
                if (result.bookings.length === 0) {
                    status = "all";
                    statusSelect.value = status;
                    result = await getAllBookings(status, "booking_id", "desc", currentPage, limit);
                }
            }
        }
        
        renderBookings(result.bookings);
        renderPagination(result.pagination);
    }

    // Payment method change handler
    const paymentMethodSelect = document.getElementById("paymentMethod");
    const accountInfoSection = document.getElementById("accountInfoSection");
    const proofUploadSection = document.getElementById("proofUploadSection");
    
    if (paymentMethodSelect && accountInfoSection && proofUploadSection) {
        paymentMethodSelect.addEventListener("change", function() {
            const selectedMethod = this.value;
            
            // Show/hide account info for Bank Transfer and Online Payment
            if (selectedMethod === "Bank Transfer" || selectedMethod === "Online Payment") {
                accountInfoSection.style.display = "block";
                proofUploadSection.style.display = "block";
            } else {
                accountInfoSection.style.display = "none";
                proofUploadSection.style.display = "none";
            }
        });
    }
});

// filter booking record by status
const statusSelectElement = document.getElementById("statusSelect");
if (statusSelectElement) {
    statusSelectElement.addEventListener("change", async function () {
        const status = this.value;
        currentPage = 1; // Reset to first page when filter changes
        const result = await getAllBookings(status, "date_of_tour", "asc", currentPage, limit);
        renderBookings(result.bookings);
        renderPagination(result.pagination);
    });
}

// Handle limit selector change
const limitSelectElement = document.getElementById("limitSelect");
if (limitSelectElement) {
    limitSelectElement.addEventListener("change", async function() {
        limit = parseInt(this.value);
        currentPage = 1; // Reset to first page when limit changes
        
        const status = document.getElementById("statusSelect").value;
        const column = document.querySelector(".sort[data-order]").getAttribute("data-column");
        const order = document.querySelector(".sort[data-order]").getAttribute("data-order");
        
        const result = await getAllBookings(status, column, order, currentPage, limit);
        renderBookings(result.bookings);
        renderPagination(result.pagination);
    });
}

// sort booking record by column
const sortButtons = document.querySelectorAll(".sort");
if (sortButtons.length > 0) {
    sortButtons.forEach(button => {
        button.style.cursor = "pointer";
        button.style.backgroundColor = "#d1f7c4";

        button.addEventListener("click", async function () {
            const status = document.getElementById("statusSelect").value;
            const column = this.getAttribute("data-column");
            const order = this.getAttribute("data-order");
            currentPage = 1; // Reset to first page when sort changes

            const result = await getAllBookings(status, column, order, currentPage, limit);
            renderBookings(result.bookings);
            renderPagination(result.pagination);

            this.setAttribute("data-order", order === "asc" ? "desc" : "asc");
        });
    });
}

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

const openPaymentModalButton = document.getElementsByClassName("open-payment-modal");
const paymentModal = document.querySelector(".payment-modal");

// open the payment modal and get the value associated with row selected
const btnContainers = document.querySelectorAll(".btn-container");
if (btnContainers.length > 0) {
    btnContainers.forEach(container => {
        container.addEventListener("click", function (e) {
            if (e.target.contains('open-payment-modal')) {
                const totalCost = this.getAttribute("data-total-cost");
                const bookingID = this.getAttribute("data-booking-id");
                const clientID = this.getAttribute("data-client-id");

                if (fullAmount && partialAmount && bookingIDinput && userIDinput) {
                    fullAmount.textContent = formatNumber(totalCost);
                    partialAmount.textContent = formatNumber(totalCost / 2);
                    bookingIDinput.value = bookingID;
                    userIDinput.value = clientID;
                }
            }
        });
    });
}

async function getAllBookings(status, column, order, page = 1, limit = 10) {
    try {
        const response = await fetch("/home/get-booking-requests", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ status, column, order, page, limit })
        });

        const data = await response.json();

        const tbody = document.getElementById("tableBody");
        tbody.innerHTML = "";   

        if (data.success) {
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

function renderBookings(bookings) {
    const tbody = document.getElementById("tableBody");
    if (!tbody) return; // If the table body doesn't exist, exit the function
    
    tbody.innerHTML = "";
    
    if (!bookings || bookings.length === 0) return;

    bookings.forEach(booking => {
        const tr = document.createElement("tr");

        const destinationCell = document.createElement("td");
        const dateOfTourCell = document.createElement("td");
        const endOfTourCell = document.createElement("td");
        const daysCell = document.createElement("td");
        const busesCell = document.createElement("td");
        const totalCostCell = document.createElement("td");
        const balanceCell = document.createElement("td");
        const remarksCell = document.createElement("td");

        destinationCell.textContent = booking.destination;
        destinationCell.style.maxWidth = "150px";
        destinationCell.style.overflow = "hidden";
        destinationCell.style.textOverflow = "ellipsis";
        destinationCell.style.whiteSpace = "nowrap";
        destinationCell.title = booking.destination; // Add full text as tooltip
        
        dateOfTourCell.textContent = formatDate(booking.date_of_tour);
        endOfTourCell.textContent = formatDate(booking.end_of_tour);
        daysCell.textContent = booking.number_of_days;
        busesCell.textContent = booking.number_of_buses;
        totalCostCell.textContent = formatNumber(booking.total_cost);
        balanceCell.textContent = formatNumber(booking.balance);
        
        // Apply color styling directly to the cell
        remarksCell.textContent = booking.status;
        remarksCell.className = `text-${getStatusTextClass(booking.status)}`;
        remarksCell.style.width = "85px";
        remarksCell.style.textAlign = "center";
        remarksCell.style.fontWeight = "bold";
        
        // Add tooltip for Processing status
        if (booking.status.toLowerCase() === 'processing') {
            remarksCell.title = "Your payment proof has been submitted and is awaiting admin verification";
            remarksCell.style.cursor = "help";
        }

        tr.append(destinationCell, dateOfTourCell, endOfTourCell, daysCell, busesCell, totalCostCell, balanceCell, remarksCell, actionCell(booking));
        tbody.appendChild(tr);
    });
}

// Update function to return just the text color class
function getStatusTextClass(status) {
    switch (status.toLowerCase()) {
        case 'pending':
            return 'warning';
        case 'confirmed':
            return 'success';
        case 'processing':
            return 'info';
        case 'canceled':
        case 'rejected':
            return 'danger';
        case 'completed':
            return 'primary';
        default:
            return 'secondary';
    }
}

function actionCell(booking) {
    const td = document.createElement("td");
    const btnGroup = document.createElement("div");
    const payButton = document.createElement("button");
    const editButton = document.createElement("button");
    const cancelButton = document.createElement("button");
    const viewButton = document.createElement("button");
    const invoiceButton = document.createElement("button");

    // style
    btnGroup.classList.add("container", "btn-container", "d-flex", "gap-2");
    
    // Payment button - enhanced styling
    payButton.classList.add("btn", "bg-success-subtle", "text-success", "fw-bold", "w-100", "d-flex", "align-items-center", "justify-content-center", "gap-1");
    payButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");
    
    // Only add open-payment-modal class to the button, not the content
    payButton.classList.add("open-payment-modal");
    
    editButton.classList.add("btn", "bg-primary-subtle", "text-primary", "fw-bold", "w-100", "d-flex", "align-items-center", "justify-content-center", "gap-1");
    cancelButton.classList.add("btn", "bg-danger-subtle", "text-danger", "fw-bold", "w-100", "d-flex", "align-items-center", "justify-content-center", "gap-1");
    viewButton.classList.add("btn", "bg-success-subtle", "text-success", "fw-bold", "w-100", "d-flex", "align-items-center", "justify-content-center", "gap-1");
    invoiceButton.classList.add("btn", "bg-info-subtle", "text-info", "fw-bold", "w-100", "d-flex", "align-items-center", "justify-content-center", "gap-1");

    editButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");
    cancelButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");
    viewButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");
    invoiceButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");

    // text
    const payIcon = document.createElement("i");
    payIcon.classList.add("bi", "bi-credit-card");
    const payText = document.createElement("span");
    payText.textContent = "Pay";
    payButton.appendChild(payIcon);
    payButton.appendChild(payText);

    const editIcon = document.createElement("i");
    editIcon.classList.add("bi", "bi-pencil");
    const editText = document.createElement("span");
    editText.textContent = "Edit";
    editButton.appendChild(editIcon);
    editButton.appendChild(editText);

    const cancelIcon = document.createElement("i");
    cancelIcon.classList.add("bi", "bi-x-circle");
    const cancelText = document.createElement("span");
    cancelText.textContent = "Cancel";
    cancelButton.appendChild(cancelIcon);
    cancelButton.appendChild(cancelText);

    const viewIcon = document.createElement("i");
    viewIcon.classList.add("bi", "bi-eye");
    const viewText = document.createElement("span");
    viewText.textContent = "View";
    viewButton.appendChild(viewIcon);
    viewButton.appendChild(viewText);

    // modal
    payButton.setAttribute("data-bs-toggle", "modal");
    payButton.setAttribute("data-bs-target", "#paymentModal");

    // data attributes
    payButton.setAttribute("data-booking-id", booking.booking_id);
    payButton.setAttribute("data-total-cost", booking.total_cost);
    payButton.setAttribute("data-client-id", booking.client_id);
    
    // Add tooltip to payment button
    payButton.setAttribute("title", "Make a payment for this booking");

    editButton.setAttribute("data-booking-id", booking.booking_id);
    editButton.setAttribute("data-days", booking.number_of_days);
    editButton.setAttribute("data-buses", booking.number_of_buses);
    editButton.setAttribute("title", "Edit booking details");

    cancelButton.setAttribute("data-booking-id", booking.booking_id);
    cancelButton.setAttribute("title", "Cancel this booking");
    
    viewButton.setAttribute("title", "View booking details");
    
    invoiceButton.setAttribute("data-booking-id", booking.booking_id);
    invoiceButton.setAttribute("title", "View booking invoice");

    // event listeners
    viewButton.addEventListener("click", function () {
        localStorage.setItem("bookingId", booking.booking_id);
        window.location.href = "/home/booking-request";
    });

    payButton.addEventListener("click", function () {
        // Reset form state
        document.getElementById("amount").textContent = "";
        document.querySelectorAll(".amount-payment").forEach(el => {
            el.classList.remove("selected");
        });
        
        // Reset payment method
        const paymentMethodSelect = document.getElementById("paymentMethod");
        if (paymentMethodSelect) {
            paymentMethodSelect.selectedIndex = 0;
            const event = new Event('change');
            paymentMethodSelect.dispatchEvent(event);
        }
        
        const totalCost = this.getAttribute("data-total-cost");
        const bookingID = this.getAttribute("data-booking-id");

        document.getElementById("fullAmnt").style.display = "block";  
        document.getElementById("downPayment").textContent = "Down Payment";
        
        if (parseFloat(booking.balance) < parseFloat(booking.total_cost)) {
            document.getElementById("fullAmnt").style.display = "none";   
            document.getElementById("downPayment").textContent = "Final Payment";
            // Auto-select the down payment option when full payment is not available
            setTimeout(() => {
                document.querySelectorAll(".amount-payment")[1].click();
            }, 300);
        } else {
            fullAmount.textContent = formatNumber(totalCost);
        }
        partialAmount.textContent = formatNumber(totalCost / 2);
        bookingIDinput.value = bookingID;
        userIDinput.value = booking.user_id;
    });

    editButton.addEventListener("click", function () {
        sessionStorage.setItem("bookingId", booking.booking_id);
        window.location.href = "/home/book";
    });

    // Modified: Using SweetAlert for cancel booking
    cancelButton.addEventListener("click", function () {
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

    if ((booking.status === "Confirmed") && parseFloat(booking.balance) > 0.0) {
        btnGroup.append(payButton, editButton, cancelButton, viewButton);
    } else if (booking.status === "Confirmed" && parseFloat(booking.balance) === 0 || booking.status == "Processing") {
        btnGroup.append(editButton, cancelButton, viewButton); 
    } else if (booking.status === "Pending") {
        btnGroup.append(editButton, cancelButton, viewButton);  
    } else {
        btnGroup.append(viewButton);
    }

    td.appendChild(btnGroup);

    return td;
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
        
        const status = document.getElementById("statusSelect").value;
        const column = document.querySelector(".sort[data-order]").getAttribute("data-column");
        const order = document.querySelector(".sort[data-order]").getAttribute("data-order");
        const result = await getAllBookings(status, column, order, currentPage, limit);
        renderBookings(result.bookings);
        renderPagination(result.pagination);
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
        
        if ((paymentMethod === "Bank Transfer" || paymentMethod === "Online Payment") && !proofFile) {
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
                const statusSelect = document.getElementById("statusSelect");
                if (statusSelect) {
                    statusSelect.value = "processing";
                }
                
                // Refresh the bookings table with processing status
                const column = document.querySelector(".sort[data-order]")?.getAttribute("data-column") || "date_of_tour";
                const order = document.querySelector(".sort[data-order]")?.getAttribute("data-order") || "asc";
                const result = await getAllBookings("processing", column, order, currentPage, limit);
                renderBookings(result.bookings);
                renderPagination(result.pagination);
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
                const statusSelect = document.getElementById("statusSelect");
                if (!statusSelect) return;
                
                const status = statusSelect.value;
                const column = document.querySelector(".sort[data-order]")?.getAttribute("data-column") || "date_of_tour";
                const order = document.querySelector(".sort[data-order]")?.getAttribute("data-order") || "asc";
                const result = await getAllBookings(status, column, order, page, limit);
                renderBookings(result.bookings);
                renderPagination(result.pagination);
            }
        });
    }
}
