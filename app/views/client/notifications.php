<?php 
require_once __DIR__ . "/../../../config/database.php";
require_client_auth(); // Use helper function
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/../../../public/css/bootstrap/bootstrap.min.css">  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Notification styling */
        .list-group {
            background-color: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .list-group-item {
            transition: all 0.2s ease;
            margin-bottom: 0.75rem;
            border-radius: 0.375rem !important;
            border: 1px solid rgba(0, 0, 0, 0.075);
        }
        
        .list-group-item:last-child {
            margin-bottom: 0;
        }
        
        .list-group-item:hover {
            background-color: rgba(25, 135, 84, 0.05);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }
        
        .notification-item {
            border-left: 4px solid transparent;
            padding: 1rem;
        }
        
        .notification-item.unread {
            border-left-color: #198754;
            background-color: rgba(25, 135, 84, 0.05);
        }
        
        /* Icon colors and styling by notification type */
        .notification-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background-color: rgba(25, 135, 84, 0.1);
            margin-right: 1rem;
        }
        
        .notification-icon.success {
            background-color: rgba(25, 135, 84, 0.1);
        }
        
        .notification-icon.danger {
            background-color: rgba(220, 53, 69, 0.1);
        }
        
        .notification-icon.info {
            background-color: rgba(13, 110, 253, 0.1);
        }
        
        .notification-icon.secondary {
            background-color: rgba(108, 117, 125, 0.1);
        }
        
        .bi-check-circle-fill {
            color: #198754;
        }
        
        .bi-x-circle-fill {
            color: #dc3545;
        }
        
        .bi-credit-card-fill {
            color: #0d6efd;
        }
        
        .bi-info-circle-fill {
            color: #6c757d;
        }
        
        /* Clean notification styles */
        #notifications-list {
            max-height: 600px;
            overflow-y: auto;
            padding: 0.5rem;
        }
        
        .notification-time {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        /* Card styling */
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            height: 20vh;
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1rem 1.25rem;
        }
        
        .card-title {
            margin-bottom: 0;
            color: #198754;
            font-weight: 600;
        }

        .card-footer {
            background-color: #fff;
            z-index: 1000;
        }
        
        .btn-outline-success {
            border-color: #198754;
            color: #198754;
        }
        
        .btn-outline-success:hover {
            background-color: #198754;
            color: white;
        }
        
        .btn-outline-primary {
            border-color: #0d6efd;
            color: #0d6efd;
        }
        
        .btn-outline-primary:hover {
            background-color: #0d6efd;
            color: white;
        }
        
        /* Empty state styling */
        #no-notifications-message {
            padding: 3rem 1rem;
        }
        
        #no-notifications-message i {
            font-size: 3rem;
            color: #d1d1d1;
            margin-bottom: 1rem;
        }
        
        #no-notifications-message p {
            color: #6c757d;
            font-weight: 500;
            font-size: 1.1rem;
        }
        
        /* Pagination styling */
        .pagination .page-link {
            color: #198754;
            border-color: #e9ecef;
        }
        
        .pagination .active .page-link {
            background-color: #198754;
            border-color: #198754;
        }
        
        .pagination .page-link:hover {
            background-color: rgba(25, 135, 84, 0.1);
        }
        
        /* View details button styling */
        .view-details-btn {
            border-color: #198754;
            color: #198754;
            transition: all 0.2s ease;
        }
        
        .view-details-btn:hover {
            background-color: #198754;
            color: white;
        }
    </style>
    <title>Notifications</title>
</head>

<body>
    <?php include_once __DIR__ . "/../assets/sidebar.php"; ?> 
    
    <div class="content collapsed" id="content">
        <div class="container-fluid py-4 px-4 px-xl-5">
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0">
                <div class="p-0">
                    <h3 class="fw-bold text-dark">My Notifications</h3>
                    <p class="text-muted mb-0">View and manage all your notifications</p>
                </div>
                <?php include_once __DIR__ . "/../assets/user_profile.php"; ?>
            </div>
            
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">
                        <i class="bi bi-bell-fill me-2"></i>All Notifications
                    </h5>
                    <div>
                        
                        <button id="markAllReadBtn" class="btn btn-sm btn-outline-success">
                            <i class="bi bi-check-all me-1"></i>Mark All Read
                        </button>
                    </div>
                </div>
                
                <div class="card-body p-3">
                    <!-- Clean notification list -->
                    <div id="notifications-container">
                        <div id="notifications-list" class="list-group mb-3">
                            <!-- Notifications will be loaded here -->
                            <!-- Loading state -->
                            <div class="list-group-item notification-item d-flex align-items-center justify-content-center p-4">
                                <div class="text-center">
                                    <div class="spinner-border text-success mb-3" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <p class="mb-0 fw-medium">Loading your notifications...</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- No notifications message -->
                        <div id="no-notifications-message" class="text-center p-4" style="display: none;">
                            <i class="bi bi-bell-slash fs-1 text-muted mb-3 d-block"></i>
                            <p>You don't have any notifications yet</p>
                            <button class="btn btn-outline-primary btn-sm mt-2" onclick="loadNotifications(1)">
                                <i class="bi bi-arrow-clockwise me-1"></i>Refresh
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-white">
                    <nav aria-label="Notification pagination">
                        <ul class="pagination justify-content-center mb-0" id="notificationPagination">
                            <!-- Pagination will be dynamically generated -->
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="../../../public/css/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="../../../public/js/assets/sidebar.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentPage = 1;
            const limit = 20;
            
            // Function to load all notifications with pagination
            async function loadNotifications(page = 1) {
                try {
                    const response = await fetch(`/client/notifications/get`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ page: page, limit: limit })
                    });
                    
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        const notificationsList = document.getElementById('notifications-list');
                        const noNotificationsMessage = document.getElementById('no-notifications-message');
                        
                        if (data.notifications && data.notifications.length > 0) {
                            // Hide no notifications message
                            noNotificationsMessage.style.display = 'none';
                            
                            // Clear existing notifications
                            notificationsList.innerHTML = '';
                            
                            // Add each notification
                            data.notifications.forEach(notification => {
                                // Determine icon class and background based on notification type
                                let iconClass = 'bi-info-circle-fill text-primary';
                                let iconBg = 'info';
                                
                                if (notification.type.includes('confirmed')) {
                                    iconClass = 'bi-check-circle-fill text-success';
                                    iconBg = 'success';
                                } else if (notification.type.includes('rejected') || notification.type.includes('canceled')) {
                                    iconClass = 'bi-x-circle-fill text-danger';
                                    iconBg = 'danger';
                                } else if (notification.type.includes('payment')) {
                                    iconClass = 'bi-credit-card-fill text-info';
                                    iconBg = 'info';
                                }
                                
                                // Format date
                                const date = new Date(notification.created_at);
                                const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
                                
                                // Set link for detail view if applicable
                                let link = 'javascript:void(0)';
                                if (['booking_confirmed', 'booking_rejected', 'booking_canceled', 'payment_confirmed', 'payment_rejected'].includes(notification.type)) {
                                    link = `/home/booking-details/${notification.reference_id}`;
                                }
                                
                                // Create notification item
                                const notificationItem = document.createElement('div');
                                notificationItem.className = `list-group-item notification-item ${!notification.is_read ? 'unread' : ''}`;
                                notificationItem.setAttribute('data-id', notification.notification_id);
                                notificationItem.setAttribute('data-read', notification.is_read ? 'true' : 'false');
                                
                                notificationItem.innerHTML = `
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="d-flex align-items-start">
                                            <div class="notification-icon ${iconBg}">
                                                <i class="bi ${iconClass} fs-4"></i>
                                            </div>
                                            <div style="min-width: 0; word-wrap: break-word; overflow-wrap: break-word;">
                                                <p class="mb-1 fw-semibold">${notification.message}</p>
                                                <div class="d-flex align-items-center">
                                                    <small class="text-muted"><i class="bi bi-clock me-1"></i>${formattedDate}</small>
                                                    <span class="ms-2 badge ${notification.is_read ? 'bg-secondary' : 'bg-success'} rounded-pill">
                                                        ${notification.is_read ? 'Read' : 'New'}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ms-2 flex-shrink-0">
                                            ${notification.reference_id ? 
                                                `<a href="${link}" class="btn btn-sm view-details-btn">
                                                    <i class="bi bi-arrow-right me-1"></i>View Details
                                                </a>` : ''}
                                        </div>
                                    </div>
                                `;
                                
                                // Add to DOM
                                notificationsList.appendChild(notificationItem);
                                
                                // Add click event listener
                                if (!notification.is_read) {
                                    notificationItem.addEventListener('click', function(e) {
                                        // Only mark as read if not clicking on the View Details button
                                        if (!e.target.closest('.btn')) {
                                            markAsRead(notification.notification_id, notificationItem);
                                        }
                                    });
                                }
                            });
                            
                            // Create pagination if available
                            if (data.pagination) {
                                createPagination(data.pagination);
                            } else {
                                document.getElementById('notificationPagination').innerHTML = '';
                            }
                        } else {
                            // Show no notifications message
                            notificationsList.innerHTML = '';
                            noNotificationsMessage.style.display = 'block';
                            document.getElementById('notificationPagination').innerHTML = '';
                        }
                    } else {
                        throw new Error(data.message || 'Failed to load notifications');
                    }
                } catch (error) {
                    console.error('Error loading notifications:', error);
                    
                    // Display error message
                    const notificationsList = document.getElementById('notifications-list');
                    notificationsList.innerHTML = `
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="notification-icon danger me-3">
                                    <i class="bi bi-exclamation-triangle text-danger fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">Error loading notifications</h5>
                                    <p class="mb-2">${error.message || 'An unexpected error occurred'}</p>
                                    <button class="btn btn-sm btn-outline-primary" onclick="loadNotifications(${currentPage})">
                                        <i class="bi bi-arrow-clockwise me-1"></i>Try Again
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    document.getElementById('notificationPagination').innerHTML = '';
                }
            }
            
            // Function to create pagination
            function createPagination(pagination) {
                const paginationEl = document.getElementById('notificationPagination');
                paginationEl.innerHTML = '';
                
                if (pagination.total_pages <= 1) return;
                
                // Previous button
                const prevLi = document.createElement('li');
                prevLi.className = `page-item ${pagination.current_page === 1 ? 'disabled' : ''}`;
                prevLi.innerHTML = `<a class="page-link" href="#" data-page="${pagination.current_page - 1}"><i class="bi bi-chevron-left"></i></a>`;
                paginationEl.appendChild(prevLi);
                
                // Page numbers
                for (let i = 1; i <= pagination.total_pages; i++) {
                    const li = document.createElement('li');
                    li.className = `page-item ${pagination.current_page === i ? 'active' : ''}`;
                    li.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`;
                    paginationEl.appendChild(li);
                }
                
                // Next button
                const nextLi = document.createElement('li');
                nextLi.className = `page-item ${pagination.current_page === pagination.total_pages ? 'disabled' : ''}`;
                nextLi.innerHTML = `<a class="page-link" href="#" data-page="${pagination.current_page + 1}"><i class="bi bi-chevron-right"></i></a>`;
                paginationEl.appendChild(nextLi);
                
                // Add event listeners to pagination links
                document.querySelectorAll('.page-link').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const page = parseInt(this.getAttribute('data-page'));
                        if (page > 0 && page <= pagination.total_pages) {
                            currentPage = page;
                            loadNotifications(currentPage);
                        }
                    });
                });
            }
            
            // Function to mark notification as read
            async function markAsRead(notificationId, element) {
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
                        element.setAttribute('data-read', 'true');
                        element.classList.remove('unread');
                        
                        // Update badge
                        const badge = element.querySelector('.badge');
                        if (badge) {
                            badge.classList.remove('bg-success');
                            badge.classList.add('bg-secondary');
                            badge.textContent = 'Read';
                        }
                    }
                } catch (error) {
                    console.error('Error marking notification as read:', error);
                }
            }
            
            // Mark all notifications as read
            document.getElementById('markAllReadBtn').addEventListener('click', async function() {
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
                        // Refresh the notification list
                        await loadNotifications(currentPage);
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'All notifications marked as read',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                } catch (error) {
                    console.error('Error marking all notifications as read:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to mark notifications as read',
                        confirmButtonColor: '#28a745'
                    });
                }
            });
            
            // Add test notification
            // document.getElementById('addTestNotificationBtn').addEventListener('click', async function() {
            //     try {
            //         const response = await fetch('/client/notifications/add-test', {
            //             method: 'POST',
            //             headers: {
            //                 'Content-Type': 'application/json',
            //                 'Accept': 'application/json'
            //             }
            //         });
                    
            //         if (!response.ok) {
            //             throw new Error(`HTTP error! Status: ${response.status}`);
            //         }
                    
            //         const data = await response.json();
                    
            //         if (data.success) {
            //             // Refresh the notification list
            //             await loadNotifications(currentPage);
                        
            //             Swal.fire({
            //                 icon: 'success',
            //                 title: 'Success',
            //                 text: 'Test notification added successfully',
            //                 timer: 1500,
            //                 showConfirmButton: false
            //             });
            //         } else {
            //             throw new Error(data.message || 'Failed to add test notification');
            //         }
            //     } catch (error) {
            //         console.error('Error adding test notification:', error);
            //         Swal.fire({
            //             icon: 'error',
            //             title: 'Error',
            //             text: error.message || 'Failed to add test notification',
            //             confirmButtonColor: '#28a745'
            //         });
            //     }
            // });
            
            // Make loadNotifications function available globally
            window.loadNotifications = loadNotifications;
            
            // Load notifications when page loads
            loadNotifications();
        });
    </script>
</body>
</html> 