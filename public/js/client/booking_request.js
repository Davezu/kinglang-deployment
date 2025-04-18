const cancelBookingModal = new bootstrap.Modal(document.getElementById("cancelBookingModal"));
const messageModal = new bootstrap.Modal(document.getElementById("messageModal"));

const messageTitle = document.getElementById("messageTitle");
const messageBody = document.getElementById("messageBody");


// disable past dates in date of tour input
const today = new Date();
today.setDate(today.getDate() + 3);
const minDate = today.toISOString().split("T")[0];
document.getElementById("date_of_tour").min = minDate; 

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
    limit = parseInt(document.getElementById("limitSelect").value);
    const statusSelect = document.getElementById("statusSelect");
    let status = statusSelect.value;
    
    // Get initial data with pending status
    let result = await getAllBookings(status, "date_of_tour", "asc", currentPage, limit);
    
    // If no pending bookings, try confirmed bookings
    if (result.bookings.length === 0 && status === "pending") {
        status = "confirmed";
        statusSelect.value = status;
        result = await getAllBookings(status, "date_of_tour", "asc", currentPage, limit);
        
        // If no confirmed bookings either, use "all"
        if (result.bookings.length === 0) {
            status = "all";
            statusSelect.value = status;
            result = await getAllBookings(status, "date_of_tour", "asc", currentPage, limit);
        }
    }
    
    renderBookings(result.bookings);
    renderPagination(result.pagination);

    // Payment method change handler
    const paymentMethodSelect = document.getElementById("paymentMethod");
    const accountInfoSection = document.getElementById("accountInfoSection");
    const proofUploadSection = document.getElementById("proofUploadSection");
    
    if (paymentMethodSelect) {
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
document.getElementById("statusSelect").addEventListener("change", async function () {
    const status = this.value;
    currentPage = 1; // Reset to first page when filter changes
    const result = await getAllBookings(status, "date_of_tour", "asc", currentPage, limit);
    renderBookings(result.bookings);
    renderPagination(result.pagination);
});

// Handle limit selector change
document.getElementById("limitSelect").addEventListener("change", async function() {
    limit = parseInt(this.value);
    currentPage = 1; // Reset to first page when limit changes
    
    const status = document.getElementById("statusSelect").value;
    const column = document.querySelector(".sort[data-order]").getAttribute("data-column");
    const order = document.querySelector(".sort[data-order]").getAttribute("data-order");
    
    const result = await getAllBookings(status, column, order, currentPage, limit);
    renderBookings(result.bookings);
    renderPagination(result.pagination);
});

// sort booking record by column
document.querySelectorAll(".sort").forEach(button => {
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

const fullAmount = document.getElementById("fullAmount");
const partialAmount = document.getElementById("partialAmount");

const bookingIDinput = document.getElementById("bookingID");
const userIDinput = document.getElementById("userID");
const amountInput = document.getElementById("amountInput");

// getting the actual value of the selected formatted currency and place it in the hidden input to insert in database
document.querySelectorAll(".amount-payment").forEach(amount => {
    amount.addEventListener("click", (event) => {
        const amt = event.currentTarget.querySelector(".amount");
        if (amt) {
            document.getElementById("amount").textContent = amt.textContent;
            amountInput.value = parseFloat(amt.textContent.replace(/[^0-9.]/g, ""));
        }
    })
});

const openPaymentModalButton = document.getElementsByClassName("open-payment-modal");
const paymentModal = document.querySelector(".payment-modal");
console.log(openPaymentModalButton);


// open the payment modal and get the value associated with row selected
document.querySelectorAll(".btn-container").forEach(container => {
    console.log(container)
    container.addEventListener("click", function (e) {
        console.log("test");
        if (e.target.contains('open-payment-modal')) {
            const totalCost = this.getAttribute("data-total-cost");
            const bookingID = this.getAttribute("data-booking-id");
            const clientID = this.getAttribute("data-client-id");

            console.log("total cost: ", totalCost);
            console.log("booking id: ", bookingID);
            console.log("client id: ", clientID);

            fullAmount.textContent = formatNumber(totalCost);
            partialAmount.textContent = formatNumber(totalCost / 2);
            bookingIDinput.value = bookingID;
            clientIDinput.value = clientID;
        }
    })
});

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
    tbody.innerHTML = "";

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

    // style
    btnGroup.classList.add("container", "btn-container", "d-flex", "gap-2");
    payButton.classList.add("open-payment-modal", "btn", "bg-success-subtle", "text-success", "fw-bold", "w-100", "d-flex", "align-items-center", "justify-content-center", "gap-1");
    editButton.classList.add("btn", "bg-primary-subtle", "text-primary", "fw-bold", "w-100", "d-flex", "align-items-center", "justify-content-center", "gap-1");
    cancelButton.classList.add("btn", "bg-danger-subtle", "text-danger", "fw-bold", "w-100", "d-flex", "align-items-center", "justify-content-center", "gap-1");
    viewButton.classList.add("btn", "bg-success-subtle", "text-success", "fw-bold", "w-100", "d-flex", "align-items-center", "justify-content-center", "gap-1");

    payButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");
    editButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");
    cancelButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");
    viewButton.setAttribute("style", "--bs-btn-padding-y: .25rem; --bs-btn-padding-x: 1.5rem; --bs-btn-font-size: .75rem;");

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

    cancelButton.setAttribute("data-bs-toggle", "modal");
    cancelButton.setAttribute("data-bs-target", "#cancelBookingModal");

    // data attributes
    payButton.setAttribute("data-booking-id", booking.booking_id);
    payButton.setAttribute("data-total-cost", booking.total_cost);
    payButton.setAttribute("data-client-id", booking.client_id);

    editButton.setAttribute("data-booking-id", booking.booking_id);
    editButton.setAttribute("data-days", booking.number_of_days);
    editButton.setAttribute("data-buses", booking.number_of_buses);

    cancelButton.setAttribute("data-booking-id", booking.booking_id);

    // event listeners
    viewButton.addEventListener("click", function () {
        localStorage.setItem("bookingId", booking.booking_id);
        window.location.href = "/home/booking-request";
    })

    payButton.addEventListener("click", function () {
        document.getElementById("amount").textContent = "";
        const totalCost = this.getAttribute("data-total-cost");
        const bookingID = this.getAttribute("data-booking-id");

        document.getElementById("fullAmnt").style.display = "block";  
        document.getElementById("downPayment").textContent = "Down Payment";
        
        if (parseFloat(booking.balance) < parseFloat(booking.total_cost)) {
            document.getElementById("fullAmnt").style.display = "none";   
            document.getElementById("downPayment").textContent = "Final Payment";
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

    cancelButton.addEventListener("click", function () {
        document.getElementById("cancelBookingId").value = this.getAttribute("data-booking-id");
        document.getElementById("cancelUserId").value = this.getAttribute("data-user-id");
    });

    if ((booking.status === "Confirmed" || booking.status == "Processing") && parseFloat(booking.balance) > 0.0) {
        btnGroup.append(payButton, editButton, cancelButton, viewButton);
    } else if (booking.status === "Confirmed" && parseFloat(booking.balance) === 0) {
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

document.getElementById("cancelBookingForm").addEventListener("submit", async function (event) {
    event.preventDefault(); 

    const formData = new FormData(this);
    const bookingId = formData.get("booking_id");
    const reason = formData.get("reason");

    try {
        const response = await fetch("/cancel-booking", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ bookingId, reason })
        });
        
        cancelBookingModal.hide();
        document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
        document.body.classList.remove('modal-open');
    
        const data = await response.json();
        
        if (data.success) {
            messageTitle.textContent = "Success";
            messageBody.textContent = data.message;
            messageModal.show();
        } else {
            messageTitle.textContent = "Error";
            messageBody.textContent = data.message;
            messageModal.show();
        }
        
        const status = document.getElementById("statusSelect").value;
        const column = document.querySelector(".sort[data-order]").getAttribute("data-column");
        const order = document.querySelector(".sort[data-order]").getAttribute("data-order");
        const result = await getAllBookings(status, column, order, currentPage, limit);
        renderBookings(result.bookings);
        renderPagination(result.pagination);
    } catch (error) {
        console.error(error);
    }
});

document.getElementById("paymentForm").addEventListener("submit", async function (event) {
    event.preventDefault();

    const formData = new FormData(this);
    const bookingId = formData.get("booking_id");
    const userId = formData.get("user_id");
    const amount = formData.get("amount");
    const paymentMethod = formData.get("payment_method");
    
    // Check if we have a file
    const proofFile = document.getElementById("proofOfPayment").files[0];
    if (proofFile) {
        formData.append("proof_of_payment", proofFile);
    }
    
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
            messageTitle.textContent = "Success";
            messageBody.textContent = "Payment submitted successfully!";
            messageModal.show();
            
            // Refresh the bookings table
            const status = document.getElementById("statusSelect").value;
            const column = document.querySelector(".sort[data-order]").getAttribute("data-column");
            const order = document.querySelector(".sort[data-order]").getAttribute("data-order");
            const result = await getAllBookings(status, column, order, currentPage, limit);
            renderBookings(result.bookings);
            renderPagination(result.pagination);
        } else {
            // Show error message
            messageTitle.textContent = "Error";
            messageBody.textContent = "There was an error submitting your payment.";
            messageModal.show();
        }
    } catch (error) {
        console.error("Error submitting payment:", error);
        messageTitle.textContent = "Error";
        messageBody.textContent = "There was an error processing your payment. Please try again.";
        messageModal.show();
    }
    
    // Reset the form
    this.reset();
});

// Add pagination rendering function
function renderPagination(pagination) {
    const paginationContainer = document.getElementById("paginationContainer");
    if (!paginationContainer) {
        // Create pagination container if it doesn't exist
        const container = document.createElement("div");
        container.id = "paginationContainer";
        container.className = "d-flex justify-content-center mt-4";
        document.querySelector(".table-responsive-xl").after(container);
    }
    
    const { total_pages, current_page, total_records } = pagination;
    
    // Clear existing pagination
    document.getElementById("paginationContainer").innerHTML = "";
    
    // Add pagination info
    // const paginationInfo = document.createElement("div");
    // paginationInfo.className = "text-center mb-2";
    // paginationInfo.textContent = `Showing ${((current_page - 1) * limit) + 1} to ${Math.min(current_page * limit, total_records)} of ${total_records} records`;
    // document.getElementById("paginationContainer").appendChild(paginationInfo);
    
    // Create pagination controls
    const paginationNav = document.createElement("nav");
    paginationNav.setAttribute("aria-label", "Booking pagination");
    
    const paginationList = document.createElement("ul");
    paginationList.className = "pagination";
    
    // Previous button
    const prevLi = document.createElement("li");
    prevLi.className = `page-item ${current_page === 1 ? 'disabled' : ''}`;
    const prevLink = document.createElement("a");
    prevLink.className = "page-link";
    prevLink.href = "#";
    prevLink.setAttribute("aria-label", "Previous");
    prevLink.textContent = "Previous";
    prevLink.addEventListener("click", async (e) => {
        e.preventDefault();
        if (current_page > 1) {
            currentPage = current_page - 1;
            const status = document.getElementById("statusSelect").value;
            const column = document.querySelector(".sort[data-order]").getAttribute("data-column");
            const order = document.querySelector(".sort[data-order]").getAttribute("data-order");
            const result = await getAllBookings(status, column, order, currentPage, limit);
            renderBookings(result.bookings);
            renderPagination(result.pagination);
        }
    });
    prevLi.appendChild(prevLink);
    paginationList.appendChild(prevLi);
    
    // Page numbers
    // Show ellipsis for large page counts
    const maxVisiblePages = 5;
    let startPage = Math.max(1, current_page - Math.floor(maxVisiblePages / 2));
    let endPage = Math.min(total_pages, startPage + maxVisiblePages - 1);
    
    if (endPage - startPage + 1 < maxVisiblePages) {
        startPage = Math.max(1, endPage - maxVisiblePages + 1);
    }
    
    // First page
    if (startPage > 1) {
        const firstLi = document.createElement("li");
        firstLi.className = "page-item";
        const firstLink = document.createElement("a");
        firstLink.className = "page-link";
        firstLink.href = "#";
        firstLink.textContent = "1";
        firstLink.addEventListener("click", async (e) => {
            e.preventDefault();
            currentPage = 1;
            const status = document.getElementById("statusSelect").value;
            const column = document.querySelector(".sort[data-order]").getAttribute("data-column");
            const order = document.querySelector(".sort[data-order]").getAttribute("data-order");
            const result = await getAllBookings(status, column, order, currentPage, limit);
            renderBookings(result.bookings);
            renderPagination(result.pagination);
        });
        firstLi.appendChild(firstLink);
        paginationList.appendChild(firstLi);
        
        // Ellipsis
        if (startPage > 2) {
            const ellipsisLi = document.createElement("li");
            ellipsisLi.className = "page-item disabled";
            const ellipsisSpan = document.createElement("span");
            ellipsisSpan.className = "page-link";
            ellipsisSpan.textContent = "...";
            ellipsisLi.appendChild(ellipsisSpan);
            paginationList.appendChild(ellipsisLi);
        }
    }
    
    // Page numbers
    for (let i = startPage; i <= endPage; i++) {
        const pageLi = document.createElement("li");
        pageLi.className = `page-item ${i === current_page ? 'active' : ''}`;
        const pageLink = document.createElement("a");
        pageLink.className = "page-link";
        pageLink.href = "#";
        pageLink.textContent = i;
        pageLink.addEventListener("click", async (e) => {
            e.preventDefault();
            currentPage = i;
            const status = document.getElementById("statusSelect").value;
            const column = document.querySelector(".sort[data-order]").getAttribute("data-column");
            const order = document.querySelector(".sort[data-order]").getAttribute("data-order");
            const result = await getAllBookings(status, column, order, currentPage, limit);
            renderBookings(result.bookings);
            renderPagination(result.pagination);
        });
        pageLi.appendChild(pageLink);
        paginationList.appendChild(pageLi);
    }
    
    // Last page
    if (endPage < total_pages) {
        // Ellipsis
        if (endPage < total_pages - 1) {
            const ellipsisLi = document.createElement("li");
            ellipsisLi.className = "page-item disabled";
            const ellipsisSpan = document.createElement("span");
            ellipsisSpan.className = "page-link";
            ellipsisSpan.textContent = "...";
            ellipsisLi.appendChild(ellipsisSpan);
            paginationList.appendChild(ellipsisLi);
        }
        
        const lastLi = document.createElement("li");
        lastLi.className = "page-item";
        const lastLink = document.createElement("a");
        lastLink.className = "page-link";
        lastLink.href = "#";
        lastLink.textContent = total_pages;
        lastLink.addEventListener("click", async (e) => {
            e.preventDefault();
            currentPage = total_pages;
            const status = document.getElementById("statusSelect").value;
            const column = document.querySelector(".sort[data-order]").getAttribute("data-column");
            const order = document.querySelector(".sort[data-order]").getAttribute("data-order");
            const result = await getAllBookings(status, column, order, currentPage, limit);
            renderBookings(result.bookings);
            renderPagination(result.pagination);
        });
        lastLi.appendChild(lastLink);
        paginationList.appendChild(lastLi);
    }
    
    // Next button
    const nextLi = document.createElement("li");
    nextLi.className = `page-item ${current_page === total_pages ? 'disabled' : ''}`;
    const nextLink = document.createElement("a");
    nextLink.className = "page-link";
    nextLink.href = "#";
    nextLink.setAttribute("aria-label", "Next");
    nextLink.innerHTML = "Next";
    nextLink.addEventListener("click", async (e) => {
        e.preventDefault();
        if (current_page < total_pages) {
            currentPage = current_page + 1;
            const status = document.getElementById("statusSelect").value;
            const column = document.querySelector(".sort[data-order]").getAttribute("data-column");
            const order = document.querySelector(".sort[data-order]").getAttribute("data-order");
            const result = await getAllBookings(status, column, order, currentPage, limit);
            renderBookings(result.bookings);
            renderPagination(result.pagination);
        }
    });
    nextLi.appendChild(nextLink);
    paginationList.appendChild(nextLi);
    
    paginationNav.appendChild(paginationList);
    document.getElementById("paginationContainer").appendChild(paginationNav);
    
    // Add "Go to page" input
    // if (total_pages > 1) {
    //     const goToPageContainer = document.createElement("div");
    //     goToPageContainer.className = "d-flex justify-content-center align-items-center mt-2";
        
    //     const goToPageLabel = document.createElement("label");
    //     goToPageLabel.className = "me-2";
    //     goToPageLabel.textContent = "Go to page:";
        
    //     const goToPageInput = document.createElement("input");
    //     goToPageInput.type = "number";
    //     goToPageInput.className = "form-control form-control-sm";
    //     goToPageInput.style.width = "60px";
    //     goToPageInput.min = 1;
    //     goToPageInput.max = total_pages;
    //     goToPageInput.value = current_page;
        
    //     goToPageInput.addEventListener("change", async function() {
    //         let pageNum = parseInt(this.value);
    //         if (isNaN(pageNum) || pageNum < 1) {
    //             pageNum = 1;
    //         } else if (pageNum > total_pages) {
    //             pageNum = total_pages;
    //         }
            
    //         currentPage = pageNum;
    //         const status = document.getElementById("statusSelect").value;
    //         const column = document.querySelector(".sort[data-order]").getAttribute("data-column");
    //         const order = document.querySelector(".sort[data-order]").getAttribute("data-order");
    //         const result = await getAllBookings(status, column, order, currentPage, limit);
    //         renderBookings(result.bookings);
    //         renderPagination(result.pagination);
    //     });
        
    //     goToPageContainer.appendChild(goToPageLabel);
    //     goToPageContainer.appendChild(goToPageInput);
    //     document.getElementById("paginationContainer").appendChild(goToPageContainer);
    // }
}
