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
    <title>Notifications</title>
</head>

<body>
    <?php include_once __DIR__ . "/../assets/sidebar.php"; ?> 
    
    <div class="content collapsed" id="content">
        <div class="container-fluid py-4 px-4 px-xl-5">
            <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap p-0 m-0">
                <div class="p-0">
                    <h3>My Notifications</h3>
                </div>
                <?php include_once __DIR__ . "/../assets/user_profile.php"; ?>
            </div>
            
            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">All Notifications</h5>
                    <div>
                        <button id="addTestNotificationBtn" class="btn btn-sm btn-outline-primary me-2">Add Test Notification</button>
                        <button id="markAllReadBtn" class="btn btn-sm btn-outline-success">Mark All as Read</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="list-group notification-list">
                        <!-- Notifications will be loaded here dynamically -->
                        <div class="p-4 text-center text-muted no-notifications">
                            <i class="bi bi-bell-slash fs-1 mb-3 d-block"></i>
                            <p>No notifications found</p>
                            <p class="small">If you're expecting notifications, try adding a test notification or check back later.</p>
                            <div class="mt-3">
                                <p class="small text-muted">Troubleshooting options:</p>
                                <button class="btn btn-sm btn-outline-secondary me-2 mb-2" onclick="testFetch('./notifications/get')">
                                    Test Relative Path
                                </button>
                                <button class="btn btn-sm btn-outline-secondary me-2 mb-2" onclick="testFetch('/client/notifications/get')">
                                    Test Absolute Path
                                </button>
                                <a href="<?= $_SERVER['REQUEST_URI'] ?>/get" target="_blank" class="btn btn-sm btn-outline-secondary mb-2">
                                    Test Direct Link
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
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
        // Test function for diagnosing URL issues
        async function testFetch(url) {
            try {
                console.log(`Testing fetch to: ${url}`);
                
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                
                console.log(`Response status: ${response.status}`);
                const data = await response.json();
                console.log('Response data:', data);
                
                Swal.fire({
                    icon: 'info',
                    title: 'Test Result',
                    html: `
                        <p>URL: ${url}</p>
                        <p>Status: ${response.status}</p>
                        <p>Check console for complete response</p>
                    `,
                    confirmButtonColor: '#28a745'
                });
            } catch (error) {
                console.error(`Error testing ${url}:`, error);
                
                Swal.fire({
                    icon: 'error',
                    title: 'Test Failed',-
                    html: `
                        <p>URL: ${url}</p>
                        <p>Error: ${error.message}</p>
                    `,
                    confirmButtonColor: '#28a745'
                });
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            let currentPage = 1;
            const limit = 20;
            
            // Function to load all notifications with pagination
            async function loadNotifications(page = 1) {
                try {
                    const response = await fetch(`./notifications/get`, {
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

                    console.log('Notifications data:', data);
                    
                    if (data.success) {
                        const notificationList = document.querySelector('.notification-list');
                        const noNotifications = document.querySelector('.no-notifications');
                        
                        if (data.notifications && data.notifications.length > 0) {
                            noNotifications.style.display = 'none';
                            notificationList.innerHTML = '';
                            
                            data.notifications.forEach(notification => {
                                const notificationItem = document.createElement('div');
                                notificationItem.className = `list-group-item notification-item`;
                                notificationItem.setAttribute('data-id', notification.notification_id);
                                notificationItem.setAttribute('data-read', notification.is_read ? 'true' : 'false');
                                
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
                                
                                let link = 'javascript:void(0)';
                                if (['booking_confirmed', 'booking_rejected', 'booking_canceled', 'payment_confirmed', 'payment_rejected'].includes(notification.type)) {
                                    link = `/home/booking-details/${notification.reference_id}`;
                                }
                                
                                notificationItem.innerHTML = `
                                    <div class="d-flex w-100 justify-content-between align-items-start">
                                        <div class="d-flex align-items-start">
                                            <div class="me-3">
                                                <i class="bi ${iconClass} fs-3"></i>
                                            </div>
                                            <div style="min-width: 0; word-wrap: break-word; overflow-wrap: break-word;">
                                                <p class="mb-1">${notification.message}</p>
                                                <small class="text-muted">${formattedDate}</small>
                                                <small class="text-muted ms-2">${notification.is_read ? '(Read)' : '(Unread)'}</small>
                                            </div>
                                        </div>
                                        <div class="ms-2 flex-shrink-0">
                                            ${notification.reference_id ? `<a href="${link}" class="btn btn-sm btn-outline-primary ms-2">View Details</a>` : ''}
                                        </div>
                                    </div>
                                `;
                                
                                notificationList.appendChild(notificationItem);
                                
                                // Mark as read when clicked
                                notificationItem.addEventListener('click', function() {
                                    if (!notification.is_read) {
                                        markAsRead(notification.notification_id, notificationItem);
                                    }
                                });
                            });
                            
                            // Create pagination
                            createPagination(data.pagination);
                        } else {
                            noNotifications.style.display = 'block';
                            notificationList.innerHTML = '';
                            notificationList.appendChild(noNotifications);
                            document.getElementById('notificationPagination').innerHTML = '';
                        }
                    }
                } catch (error) {
                    console.error('Error loading notifications:', error);
                    
                    // Display error message in the notification list
                    const notificationList = document.querySelector('.notification-list');
                    const errorDiv = document.createElement('div');
                    errorDiv.className = 'p-4 text-center text-danger';
                    
                    // Determine if it's a network issue
                    const isNetworkError = error.message.includes('Failed to fetch') || 
                                          error.message.includes('NetworkError') ||
                                          error.message.includes('Network request failed');
                    
                    if (isNetworkError) {
                        errorDiv.innerHTML = `
                            <i class="bi bi-wifi-off fs-1 mb-3 d-block"></i>
                            <p>Network connection error</p>
                            <p class="small">Please check your internet connection and try again.</p>
                            <button class="btn btn-outline-primary btn-sm mt-2 retry-btn">Retry</button>
                        `;
                    } else {
                        errorDiv.innerHTML = `
                            <i class="bi bi-exclamation-triangle fs-1 mb-3 d-block"></i>
                            <p>Error loading notifications</p>
                            <p class="small">${error.message}</p>
                            <button class="btn btn-outline-primary btn-sm mt-2 retry-btn">Retry</button>
                        `;
                    }
                    
                    notificationList.innerHTML = '';
                    notificationList.appendChild(errorDiv);
                    document.getElementById('notificationPagination').innerHTML = '';
                    
                    // Add event listener to retry button
                    const retryBtn = errorDiv.querySelector('.retry-btn');
                    if (retryBtn) {
                        retryBtn.addEventListener('click', () => {
                            loadNotifications(currentPage);
                        });
                    }
                    
                    // In case of a serious error, display a more detailed error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to load notifications. Please try again later.',
                        confirmButtonColor: '#28a745'
                    });
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
                prevLi.innerHTML = `<a class="page-link" href="#" data-page="${pagination.current_page - 1}">Previous</a>`;
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
                nextLi.innerHTML = `<a class="page-link" href="#" data-page="${pagination.current_page + 1}">Next</a>`;
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
                    const response = await fetch('./notifications/mark-read', {
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
                        const statusText = element.querySelector('small.text-muted + small.text-muted');
                        if (statusText) {
                            statusText.textContent = '(Read)';
                        }
                    }
                } catch (error) {
                    console.error('Error marking notification as read:', error);
                }
            }
            
            // Mark all notifications as read
            document.getElementById('markAllReadBtn').addEventListener('click', async function() {
                try {
                    const response = await fetch('./notifications/mark-all-read', {
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
            document.getElementById('addTestNotificationBtn').addEventListener('click', async function() {
                try {
                    const response = await fetch('./notifications/add-test', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
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
                            text: 'Test notification added successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        throw new Error(data.message || 'Failed to add test notification');
                    }
                } catch (error) {
                    console.error('Error adding test notification:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error.message || 'Failed to add test notification',
                        confirmButtonColor: '#28a745'
                    });
                }
            });
            
            // Load notifications when page loads
            loadNotifications();
        });
    </script>
</body>
</html> 