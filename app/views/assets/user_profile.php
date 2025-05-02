<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
<style>
    .booking-details-popup {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        border: 1px solid rgba(0, 0, 0, 0.1);
        transition: opacity 0.2s ease-in-out;
    }
    
    .notification-item:hover {
        background-color: rgba(25, 135, 84, 0.05);
    }
    
    .booking-summary small {
        color: #6c757d;
        font-size: 0.75rem;
    }
    
    .booking-summary .badge {
        font-size: 0.75rem;
        padding: 0.35em 0.65em;
    }
</style>
</head>

<div class="p-2 d-flex align-items-center gap-2">
    <a href="/home/book" class="text-success"><i class="bi bi-plus-square-fill me-2 fs-5"></i></a>
    
    <!-- Notification Bell with Badge -->
    <div class="dropdown">
        <a href="#" class="position-relative text-success notification-toggle" id="notificationToggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-bell-fill me-2 fs-5"></i>
            <span class="position-absolute top-0 translate-middle badge rounded-pill bg-danger notification-badge" style="display: none; left: 1.3rem;">
                <span class="notification-count">0</span>
                <span class="visually-hidden">unread notifications</span>
            </span>
        </a>
        
        <!-- Notification Dropdown -->
        <div class="dropdown-menu dropdown-menu-end p-0 notification-dropdown" style="width: 320px; max-height: none; overflow: visible;" id="notificationDropdownMenu">
            <div class="p-2 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="m-0">Notifications</h6>
                <a href="javascript:void(0)" class="text-decoration-none small mark-all-read">Mark all as read</a>
            </div>
            <div class="notification-list" style="max-height: 350px; overflow-y: auto; overflow-x: hidden;">
                <!-- Notifications will be loaded here dynamically -->
                <div class="p-3 text-center text-muted small no-notifications">No notifications</div>
            </div>
            <div class="p-2 border-top text-center">
                <a href="/client/notifications" class="text-decoration-none small">View all notifications</a>
            </div>
        </div>
    </div>
    
    <!-- Booking Details Popup -->
    <div class="booking-details-popup position-absolute bg-white rounded shadow-lg p-3" style="display: none; width: 300px; z-index: 1060; right: 0; transform: translateX(310px);">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h6 class="m-0 booking-title">Booking Details</h6>
            <button type="button" class="btn-close close-booking-details" aria-label="Close"></button>
        </div>
        <div class="booking-details-content">
            <div class="text-center p-3">
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
        <div class="mt-2 text-center">
            <a href="#" class="btn btn-sm btn-success view-full-details">View Full Details</a>
        </div>
    </div>
    
    <img src="../../../public/images/profile.png" alt="profile" class="me-2" height="35px">
    <div class="text-sm">
        <div class="name text-success fw-bold" style="font-size: 12px"><?= $_SESSION["client_name"]; ?> </div>
        <div class="email" style="font-size: 10px"><?= $_SESSION["email"]; ?></div>
    </div>
</div>

<!-- Add notification scripts - will fetch notifications via AJAX -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationToggle = document.getElementById('notificationToggle');
    const notificationDropdown = document.getElementById('notificationDropdownMenu');
    const notificationBadge = document.querySelector('.notification-badge');
    const notificationCount = document.querySelector('.notification-count');
    const notificationList = document.querySelector('.notification-list');
    const noNotifications = document.querySelector('.no-notifications');
    const markAllReadBtn = document.querySelector('.mark-all-read');
    const bookingDetailsPopup = document.querySelector('.booking-details-popup');
    const bookingDetailsContent = document.querySelector('.booking-details-content');
    const closeBookingDetailsBtn = document.querySelector('.close-booking-details');
    const viewFullDetailsBtn = document.querySelector('.view-full-details');
    let currentBookingId = null;
    
    // Function to load notifications
    async function loadNotifications() {
        try {
            const response = await fetch('/client/notifications/get', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success) {
                if (data.unreadCount > 0) {
                    notificationBadge.style.display = 'block';
                    notificationCount.textContent = data.unreadCount;
                } else {
                    notificationBadge.style.display = 'none';
                }
                
                // Render notifications
                if (data.notifications && data.notifications.length > 0) {
                    noNotifications.style.display = 'none';
                    notificationList.innerHTML = '';
                    
                    data.notifications.forEach(notification => {
                        const notificationItem = document.createElement('a');
                        notificationItem.href = getNotificationLink(notification);
                        notificationItem.className = `dropdown-item p-2 border-bottom notification-item ${!notification.is_read ? 'bg-light' : ''}`;
                        notificationItem.setAttribute('data-id', notification.notification_id);
                        notificationItem.setAttribute('data-reference-id', notification.reference_id);
                        notificationItem.setAttribute('data-type', notification.type);
                        
                        // Icon based on notification type
                        let iconClass = 'bi-info-circle-fill text-primary';
                        if (notification.type.includes('confirmed')) {
                            iconClass = 'bi-check-circle-fill text-success';
                        } else if (notification.type.includes('rejected') || notification.type.includes('canceled')) {
                            iconClass = 'bi-x-circle-fill text-danger';
                        } else if (notification.type.includes('payment')) {
                            iconClass = 'bi-credit-card-fill text-info';
                        }
                        
                        const date = new Date(notification.created_at);
                        const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                        
                        notificationItem.innerHTML = `
                            <div class="d-flex align-items-start">
                                <div class="me-2">
                                    <i class="bi ${iconClass} fs-5"></i>
                                </div>
                                <div class="flex-grow-1" style="min-width: 0;">
                                    <div class="small fw-semibold">${notification.message}</div>
                                    <div class="text-muted small">${formattedDate}</div>
                                </div>
                                ${!notification.is_read ? '<span class="badge bg-primary rounded-pill">New</span>' : ''}
                            </div>
                        `;
                        
                        notificationList.appendChild(notificationItem);
                    });
                } else {
                    noNotifications.style.display = 'block';
                    notificationList.innerHTML = '';
                    notificationList.appendChild(noNotifications);
                }
            }
        } catch (error) {
            console.error('Error loading notifications:', error);
        }
    }
    
    // Function to get notification link based on type
    function getNotificationLink(notification) {
        switch(notification.type) {
            case 'booking_confirmed':
            case 'booking_rejected':
            case 'booking_canceled':
            case 'payment_confirmed':
            case 'payment_rejected':
                return `/home/booking-details/${notification.reference_id}`;
            default:
                return 'javascript:void(0)';
        }
    }
    
    // Mark notification as read when clicked
    document.addEventListener('click', async function(e) {
        const notificationItem = e.target.closest('.notification-item');
        if (notificationItem) {
            e.preventDefault(); // Prevent navigation
            const notificationId = notificationItem.getAttribute('data-id');
            const referenceId = notificationItem.getAttribute('data-reference-id');
            const notificationType = notificationItem.getAttribute('data-type');
            
            // Only show booking details for booking-related notifications
            if (notificationType && (
                notificationType.includes('booking') || 
                notificationType.includes('payment')
            )) {
                // Position the booking details popup relative to the notification item
                const rect = notificationItem.getBoundingClientRect();
                
                bookingDetailsPopup.style.position = 'fixed';
                bookingDetailsPopup.style.top = `${rect.top}px`;
                bookingDetailsPopup.style.left = `${rect.right}px`;
                bookingDetailsPopup.style.transform = 'translateX(10px)';
                
                // Get notification message and created time
                const notificationMessage = notificationItem.querySelector('.small.fw-semibold').textContent;
                const notificationTime = notificationItem.querySelector('.text-muted.small').textContent;
                
                // Set the current booking ID for the "View Full Details" button
                currentBookingId = referenceId;
                viewFullDetailsBtn.href = `/home/booking-details/${referenceId}`;
                
                // Get notification status from type
                let status = 'pending';
                if (notificationType.includes('confirmed')) {
                    status = 'confirmed';
                } else if (notificationType.includes('rejected')) {
                    status = 'rejected';
                } else if (notificationType.includes('canceled')) {
                    status = 'canceled';
                }
                
                // Display notification content directly
                bookingDetailsPopup.style.display = 'block';
                bookingDetailsContent.innerHTML = `
                    <div class="booking-summary">
                        <div class="mb-3">
                            <div class="fw-semibold">${notificationMessage}</div>
                            <small class="text-muted">${notificationTime}</small>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Status</small>
                            <div class="badge ${getStatusBadgeClass(status)}">${status}</div>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted d-block">Booking ID</small>
                            <div>${referenceId}</div>
                        </div>
                    </div>
                `;
            }
            
            try {
                const response = await fetch('/client/notifications/mark-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ notification_id: notificationId })
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data.success) {
                    // Update UI
                    notificationItem.classList.remove('bg-light');
                    const badge = notificationItem.querySelector('.badge');
                    if (badge) badge.remove();
                    
                    // Reload notification count
                    loadNotifications();
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        }
        
        // Close booking details popup when clicking outside or when notification dropdown is closed
        if (!bookingDetailsPopup.contains(e.target) && 
            !e.target.closest('.notification-item') &&
            bookingDetailsPopup.style.display === 'block') {
            bookingDetailsPopup.style.display = 'none';
        }
    });
    
    // Close booking details popup when close button is clicked
    if (closeBookingDetailsBtn) {
        closeBookingDetailsBtn.addEventListener('click', function() {
            bookingDetailsPopup.style.display = 'none';
        });
    }
    
    // Handle bootstrap dropdown events to close booking details popup
    notificationToggle.addEventListener('hidden.bs.dropdown', function() {
        if (bookingDetailsPopup.style.display === 'block') {
            bookingDetailsPopup.style.display = 'none';
        }
    });
    
    // Helper function to get badge class based on status
    function getStatusBadgeClass(status) {
        switch(status.toLowerCase()) {
            case 'confirmed':
                return 'bg-success';
            case 'pending':
                return 'bg-warning';
            case 'rejected':
            case 'canceled':
                return 'bg-danger';
            default:
                return 'bg-secondary';
        }
    }
    
    // Mark all as read
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            
            try {
                const response = await fetch('/client/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({})
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data.success) {
                    loadNotifications();
                }
            } catch (error) {
                console.error('Error marking all notifications as read:', error);
            }
        });
    }
    
    // Load notifications when the page loads
    loadNotifications();
    
    // Refresh notifications every 30 seconds
    // setInterval(loadNotifications, 30000);
});
</script>