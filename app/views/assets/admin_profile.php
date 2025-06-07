<?php
require_once __DIR__ . "/../../../app/models/admin/NotificationModel.php";
$notificationModel = new NotificationModel();
$unreadCount = $notificationModel->getNotificationCount();
$notifications = $notificationModel->getUnreadNotifications();
?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
    
    <style>
        /* Notification dropdown styling */
        .dropdown-menu {
            border-radius: 0.75rem !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15) !important;
            border: none;
            padding: 0;
            overflow: hidden;
            animation: dropdownFadeIn 0.3s ease-out forwards;
            min-width: 360px;
            max-width: 100vw;
        }
        
        @keyframes dropdownFadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .notification-list {
            max-height: 400px;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(25, 135, 84, 0.3) rgba(0, 0, 0, 0.05);
            background: #fff;
        }
        
        .notification-list::-webkit-scrollbar {
            width: 6px;
        }
        
        .notification-list::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
        }
        
        .notification-list::-webkit-scrollbar-thumb {
            background-color: rgba(25, 135, 84, 0.3);
            border-radius: 10px;
        }
        
        .notification-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px 18px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none;
            color: inherit;
            position: relative;
            animation: itemFadeIn 0.5s ease-out forwards;
        }
        
        .notification-item:last-child {
            border-bottom: none;
        }
        
        @keyframes itemFadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .notification-item:hover {
            background: rgba(25, 135, 84, 0.05) !important;
            transform: translateY(-2px);
        }
        
        .notification-item.unread::after {
            content: '';
            position: absolute;
            top: 12px;
            right: 12px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #198754;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(25, 135, 84, 0.4);
            }
            70% {
                box-shadow: 0 0 0 6px rgba(25, 135, 84, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(25, 135, 84, 0);
            }
        }
        
        .notification-icon {
            flex-shrink: 0;
            font-size: 1.25rem;
            margin-top: 2px;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: rgba(25, 135, 84, 0.1);
            transition: all 0.3s ease;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }
        
        .notification-item:hover .notification-icon {
            transform: scale(1.05);
        }
        
        .notification-icon.text-success {
            background-color: rgba(25, 135, 84, 0.15);
        }
        
        .notification-icon.text-danger {
            background-color: rgba(220, 53, 69, 0.15);
        }
        
        .notification-icon.text-warning {
            background-color: rgba(255, 193, 7, 0.15);
        }
        
        .notification-icon.text-primary {
            background-color: rgba(13, 110, 253, 0.15);
        }
        
        .notification-badge {
            position: absolute;
            top: -8px;
            right: -1px;
            font-size: 0.65rem;
            padding: 0.25rem 0.4rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        
        .notification-header, .notification-footer {
            background: #f8f9fa;
            padding: 14px 18px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .notification-header h6 {
            font-weight: 600;
            color: #198754;
            margin: 0;
            display: flex;
            align-items: center;
        }
        
        .notification-header h6 i {
            margin-right: 8px;
        }
        
        .mark-all-read {
            color: #198754;
            font-weight: 500;
            font-size: 0.8rem;
            transition: all 0.2s ease;
        }
        
        .mark-all-read:hover {
            color: #0d6a3e;
            text-decoration: none;
        }
        
        .notification-empty {
            padding: 2.5rem 1.5rem;
            text-align: center;
            color: #6c757d;
        }
        
        .notification-footer {
            border-top: 1px solid rgba(0, 0, 0, 0.05);
            border-bottom: none;
            text-align: center;
            padding: 12px;
        }
        
        .notification-footer a {
            color: #198754;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .notification-footer a:hover {
            color: #0d6a3e;
            text-decoration: none;
        }
        
        .notification-message {
            font-weight: 500;
            font-size: 0.95rem;
            line-height: 1.4;
            white-space: normal;
            overflow-wrap: break-word;
            word-break: break-word;
            margin-bottom: 4px;
            color: #333;
        }
        
        .notification-time {
            font-size: 0.8rem;
            color: #6c757d;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        
        .notification-time i {
            font-size: 0.75rem;
        }
    </style>
</head>


<div class="p-2 d-flex align-items-center gap-2">
    <a href="#" class="text-success"><i class="bi bi-plus-square-fill me-2 fs-5"></i></a>
    
    <div class="dropdown">
        <a href="#" class="position-relative text-success" data-bs-toggle="dropdown" aria-expanded="false" id="notificationDropdown">
            <i class="bi bi-bell-fill me-2 fs-5 text-success"></i>
            <?php if ($unreadCount > 0): ?>
                <span class="notification-badge badge rounded-pill bg-danger">
                    <?= $unreadCount ?>
                </span>
            <?php endif; ?>
        </a>
        <div class="dropdown-menu dropdown-menu-end notification-dropdown">
            <div class="notification-header">
                <h6>
                    <i class="bi bi-bell-fill"></i>Notifications
                </h6>
                <?php if ($unreadCount > 0): ?>
                    <a href="javascript:void(0)" class="text-decoration-none mark-all-read">Mark all as read</a>
                <?php endif; ?>
            </div>
            <div class="notification-list">
                <?php if (count($notifications) > 0): ?>
                    <?php foreach ($notifications as $notification): ?>
                        <a href="<?php 
                            if (strpos($notification['type'], 'booking_confirmed') !== false || 
                                strpos($notification['type'], 'booking_rejected') !== false || 
                                strpos($notification['type'], 'booking_canceled') !== false || 
                                strpos($notification['type'], 'booking_cancelled_by_client') !== false) {
                                echo '/admin/booking/view/' . $notification['reference_id'];
                            } elseif (strpos($notification['type'], 'payment_submitted') !== false) {
                                echo '/admin/payment-management?booking_id=' . $notification['reference_id'];
                            } else {
                                echo '#';
                            }
                        ?>" class="notification-item<?= $notification['is_read'] ? '' : ' unread' ?>" data-id="<?= $notification['notification_id'] ?>">
                            <div class="notification-icon <?php 
                                if (strpos($notification['type'], 'booking_confirmed') !== false): 
                                    echo 'text-success'; 
                                elseif (strpos($notification['type'], 'booking_rejected') !== false || strpos($notification['type'], 'booking_canceled') !== false || strpos($notification['type'], 'booking_cancelled_by_client') !== false): 
                                    echo 'text-danger'; 
                                elseif (strpos($notification['type'], 'rebooking') !== false): 
                                    echo 'text-warning'; 
                                elseif (strpos($notification['type'], 'payment_submitted') !== false): 
                                    echo 'text-primary'; 
                                else: 
                                    echo 'text-primary'; 
                                endif; 
                            ?>">
                                <?php if (strpos($notification['type'], 'booking_confirmed') !== false): ?>
                                    <i class="bi bi-check-circle-fill"></i>
                                <?php elseif (strpos($notification['type'], 'booking_rejected') !== false || strpos($notification['type'], 'booking_canceled') !== false): ?>
                                    <i class="bi bi-x-circle-fill"></i>
                                <?php elseif (strpos($notification['type'], 'rebooking') !== false): ?>
                                    <i class="bi bi-arrow-repeat"></i>
                                <?php elseif (strpos($notification['type'], 'payment_submitted') !== false): ?>
                                    <i class="bi bi-cash-coin"></i>
                                <?php elseif (strpos($notification['type'], 'booking_cancelled_by_client') !== false): ?>
                                    <i class="bi bi-person-x-fill"></i>
                                <?php else: ?>
                                    <i class="bi bi-info-circle-fill"></i>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p class="notification-message"><?= htmlspecialchars($notification['message']) ?></p>
                                <div class="notification-time">
                                    <i class="bi bi-clock"></i>
                                    <span><?= date('M d, H:i', strtotime($notification['created_at'])) ?></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="notification-empty">
                        <i class="bi bi-bell-slash d-block mb-3 text-muted" style="font-size: 3rem; opacity: 0.5;"></i>
                        <p class="mb-0 text-muted fw-medium">No new notifications</p>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (count($notifications) > 0): ?>
                <div class="notification-footer">
                    <a href="/admin/notifications" class="text-decoration-none">View all notifications</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <img src="../../../public/images/profile.png" alt="profile" class="me-2" height="35px">
    <div class="">
        <div class="name text-success fw-bold" style="font-size: 12px"><?= $_SESSION["admin_name"]; ?> </div>
        <div class="role" style="font-size: 10px"><?= $_SESSION["role"]; ?></div>
    </div>
</div>

<!-- Notification Details Popup -->
<div class="notification-details-popup position-fixed bg-white rounded-4 shadow-lg p-0" style="display: none; width: 350px; z-index: 1060; right: auto; left: auto;">
    <div class="p-3 border-bottom d-flex justify-content-between align-items-center">
        <h6 class="m-0 fw-semibold d-flex align-items-center">
            <i class="bi bi-bell-fill me-2 text-success"></i>Notification Details
        </h6>
        <button type="button" class="btn-close close-notification-details" aria-label="Close"></button>
    </div>
    <div class="notification-detail-content p-3">
        <!-- Notification details will be loaded here -->
    </div>
    <div class="p-3 border-top d-flex justify-content-end">
        <a href="#" class="btn btn-sm btn-success view-related-content" style="display: none;">
            <i class="bi bi-arrow-right me-1"></i>View Details
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const notificationDetailsPopup = document.querySelector('.notification-details-popup');
    const closeNotificationDetailsBtn = document.querySelector('.close-notification-details');
    const viewRelatedContentBtn = document.querySelector('.view-related-content');
    
    // Function to show notification details
    function showNotificationDetails(notification, notificationItem) {
        // Position the popup next to the notification item
        const rect = notificationItem.getBoundingClientRect();
        notificationDetailsPopup.style.position = 'fixed';
        
        // Calculate position to keep popup within viewport
        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;
        const popupWidth = 350; // Width of the popup
        
        // Check if there's enough space to the right
        let left;
        if (rect.right + popupWidth + 20 < viewportWidth) {
            // Position to the right of the notification
            left = rect.right + 10;
        } else {
            // Position to the left of the notification
            left = Math.max(10, rect.left - popupWidth - 10);
        }
        
        // Ensure the popup doesn't go below the viewport
        const top = Math.min(rect.top, viewportHeight - 400); // 400 is an estimated height
        
        // Apply the calculated position
        notificationDetailsPopup.style.top = `${top}px`;
        notificationDetailsPopup.style.left = `${left}px`;
        notificationDetailsPopup.style.display = 'block';
        
        // Determine icon class and background based on notification type
        let iconClass = 'bi-info-circle-fill text-primary';
        let iconBg = 'info';
        let statusClass = 'bg-primary';
        let statusText = 'Information';
        
        if (notification.type.includes('booking_confirmed')) {
            iconClass = 'bi-check-circle-fill text-success';
            iconBg = 'success';
            statusClass = 'bg-success';
            statusText = 'Confirmed';
        } else if (notification.type.includes('booking_rejected') || notification.type.includes('booking_canceled') || notification.type.includes('booking_cancelled_by_client')) {
            iconClass = 'bi-x-circle-fill text-danger';
            iconBg = 'danger';
            statusClass = 'bg-danger';
            statusText = notification.type.includes('rejected') ? 'Rejected' : 'Canceled';
        } else if (notification.type.includes('payment')) {
            iconClass = 'bi-credit-card-fill text-info';
            iconBg = 'info';
            statusClass = 'bg-info';
            statusText = 'Payment';
        } else if (notification.type.includes('rebooking')) {
            iconClass = 'bi-arrow-repeat text-warning';
            iconBg = 'warning';
            statusClass = 'bg-warning';
            statusText = 'Rebooking';
        }
        
        // Format date
        const date = new Date(notification.created_at);
        const formattedDate = date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        
        // Set the view related content link
        const viewRelatedBtn = document.querySelector('.view-related-content');
        let detailsLink = '#';
        
        if (notification.reference_id) {
            if (notification.type.includes('booking_confirmed') || 
                notification.type.includes('booking_rejected') || 
                notification.type.includes('booking_canceled') || 
                notification.type.includes('booking_cancelled_by_client')) {
                detailsLink = '/admin/booking/view/' + notification.reference_id;
                viewRelatedBtn.style.display = 'block';
            } else if (notification.type.includes('payment')) {
                detailsLink = '/admin/payment-management?booking_id=' + notification.reference_id;
                viewRelatedBtn.style.display = 'block';
            } else {
                viewRelatedBtn.style.display = 'none';
            }
            
            viewRelatedBtn.href = detailsLink;
        } else {
            viewRelatedBtn.style.display = 'none';
        }
        
        // Load notification details
        const detailContent = notificationDetailsPopup.querySelector('.notification-detail-content');
        
        detailContent.innerHTML = `
            <div class="mb-3 d-flex align-items-center">
                <div class="notification-icon ${iconBg} me-3" style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 50%; background-color: rgba(25, 135, 84, 0.1);">
                    <i class="bi ${iconClass} fs-4"></i>
                </div>
                <div>
                    <span class="badge ${statusClass} mb-2">${statusText}</span>
                    <h6 class="fw-bold mb-0">${notification.type.replace(/_/g, ' ').replace(/\b\w/g, l => l.toUpperCase())}</h6>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-medium">Message</label>
                <p class="mb-0">${notification.message}</p>
            </div>
            
            <div class="mb-3">
                <label class="form-label fw-medium">Date & Time</label>
                <p class="mb-0"><i class="bi bi-clock me-1"></i>${formattedDate}</p>
            </div>
            
            ${notification.reference_id ? `
            <div class="mb-3">
                <label class="form-label fw-medium">Reference ID</label>
                <p class="mb-0">${notification.reference_id}</p>
            </div>
            ` : ''}
        `;
    }
    
    // Mark notification as read when clicked and show details
    document.querySelectorAll('.notification-item').forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            const notificationId = this.getAttribute('data-id');
            
            // Safely get text content with null checks
            const messageElement = this.querySelector('p');
            const timeElement = this.querySelector('.text-muted');
            
            const notificationMessage = messageElement ? messageElement.textContent : 'No message available';
            const notificationTime = timeElement ? timeElement.textContent : new Date().toLocaleString();
            const notificationLink = this.getAttribute('href') || '#';
            
            // Extract notification type from classes
            let notificationType = '';
            if (this.querySelector('.bi-check-circle-fill')) {
                notificationType = 'booking_confirmed';
            } else if (this.querySelector('.bi-x-circle-fill')) {
                notificationType = 'booking_rejected';
            } else if (this.querySelector('.bi-arrow-repeat')) {
                notificationType = 'rebooking';
            } else if (this.querySelector('.bi-cash-coin')) {
                notificationType = 'payment_submitted';
            } else if (this.querySelector('.bi-person-x-fill')) {
                notificationType = 'booking_cancelled_by_client';
            } else {
                notificationType = 'information';
            }
            
            // Extract reference ID from link if available
            let referenceId = null;
            if (notificationLink && notificationLink !== '#') {
                const parts = notificationLink.split('/');
                if (parts.length > 0) {
                    referenceId = parts[parts.length - 1];
                }
                
                // Handle query parameter case
                if (notificationLink.includes('?booking_id=')) {
                    referenceId = notificationLink.split('?booking_id=')[1];
                }
            }
            
            // Create notification object
            const notification = {
                notification_id: notificationId,
                message: notificationMessage,
                created_at: notificationTime,
                type: notificationType,
                reference_id: referenceId,
                is_read: false // Will be marked as read
            };
            
            // Show notification details
            showNotificationDetails(notification, this);
            
            // Mark as read
            fetch('/admin/notifications/mark-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'notification_id=' + notificationId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI if needed
                    this.style.backgroundColor = 'rgba(0, 0, 0, 0.03)';
                }
            });
        });
    });
    
    // Close notification details popup when clicking outside
    document.addEventListener('click', function(e) {
        if (notificationDetailsPopup && 
            !notificationDetailsPopup.contains(e.target) && 
            !e.target.closest('.notification-item') &&
            notificationDetailsPopup.style.display === 'block') {
            notificationDetailsPopup.style.display = 'none';
        }
    });
    
    // Close notification details popup when close button is clicked
    if (closeNotificationDetailsBtn) {
        closeNotificationDetailsBtn.addEventListener('click', function() {
            notificationDetailsPopup.style.display = 'none';
        });
    }
    
    // Mark all notifications as read
    const markAllReadBtn = document.querySelector('.mark-all-read');
    if (markAllReadBtn) {
        markAllReadBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            fetch('/admin/notifications/mark-all-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Refresh the page or update UI
                    window.location.reload();
                }
            });
        });
    }
});
</script>